package goaliemind.components.camera
{
	import flash.display.PixelSnapping;
	import flash.display.StageOrientation;
	import flash.geom.Point;
	import flash.display.Bitmap;
	import flash.display.BitmapData;
	import flash.display3D.Context3D;
	import flash.display3D.Context3DTextureFormat;
	import flash.display3D.textures.VideoTexture;
	import flash.display3D.textures.Texture;
	import flash.events.Event;
	import flash.events.VideoTextureEvent;
	import flash.geom.Matrix;
	import flash.geom.Rectangle;
	import flash.media.Camera;
	import flash.utils.ByteArray;

	import feathers.controls.Label;
	import feathers.controls.Panel;
	import feathers.controls.Alert;
	import feathers.data.ListCollection;
	
	import starling.core.Starling;
	import starling.display.Image;
	import starling.textures.ConcreteTexture;
	import starling.textures.Texture;
	import starling.animation.Transitions;
	
	import goaliemind.components.ColorFilter;
	import goaliemind.system.DeviceResolution;
	
	import com.distriqt.extension.camera.Camera;
	import com.distriqt.extension.camera.CameraMode;
	import com.distriqt.extension.camera.CameraParameters;
	import com.distriqt.extension.camera.CaptureDevice;
	import com.distriqt.extension.camera.events.CameraDataEvent;
	import com.distriqt.extension.camera.events.CameraEvent;
	
	import org.qrcode.enum.QRCodeErrorLevel;
	import org.qrcode.QRCode;
	import goaliemind.system.LogUtil;
	
	[Event(name="onSaveImageAlert",type="starling.events.Event")]
	[Event(name="onCaptureImageAlert",type="starling.events.Event")]
	[Event(name="onCaptureVideoAlert",type="starling.events.Event")]
	[Event(name="onSaveVideoAlert",type="starling.events.Event")]
	
	/**
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class NativeCameraUI extends Panel
	{
		public static const ONSAVEIMAGE_ALERT:String = "onSaveImageAlert";
		public static const ONCAPTUREIMAGE_ALERT:String = "onCaptureImageAlert";
		
		public var bottomHeight:Number = 0;
		
		private var _options:CameraParameters
		private var _device:CaptureDevice;
		
		// Preview Camera Textures and BitmapData
		
		private var _preview:Image;
		private var _previewTexture:starling.textures.Texture;
		private var _previewData:ByteArray;
		private var _previewBitmapData:BitmapData;
		private var _previewBitmapRect:Rectangle;
		
		// Camera Image saved BitmapData 
		
		private var _tempBitmapData:BitmapData;
		public function get tempBitmapData():BitmapData { return _tempBitmapData; }
		
		private var _tempThumbBitmapData:BitmapData;
		public function get tempThumbBitmapData():BitmapData { return _tempThumbBitmapData; }
		
		// Fallback Camera
		
		private var _camera:flash.media.Camera;
		private var _cTexture:ConcreteTexture;
		private var _vTexture:VideoTexture;
		
		private var _statusDebugLabel:Label;
		private var _alert:Alert;
		
		// NativeCameraUI properties
		
		private var _isNativeCamera:Boolean;
		private var _isNativeCameraSupported:Boolean;
		
		private var _isIOS:Boolean = false;
		private var _isAndroid:Boolean = false;
		private var _scale:Number = 1;
		private var _stageOrientation:String;
		private var _orientation:Number = 0;
		
		private var _finalWidthOutput:int = 640;
		private var _finalHeightOutput:int = 640;
		private var _finalThumWidthOutput:int = 202;
		private var _finalThumbHeightOutput:int = 202;
		private var _lastFrameProcessed:Number = -1;
		
		private var _currentCameraPosition:int = 2;
		
		public function NativeCameraUI(scale:Number)
		{
			super();
			
			_scale = scale;
		}
		
		public function disposeAll(onComplete:Function):void
		{
			LogUtil.log("NativeCameraUI.disposeAll()");
			
			releaseDeviceOrientation();
			
			if (_isNativeCamera)
			{
				removeNativeCameraEvents();
				releaseNativeExtension();
			}
			else
			{
				if (_camera)
				{
					_camera.removeEventListener(flash.events.Event.VIDEO_FRAME, onVideoFrame);
					_camera = null;
				}
				
				if (_vTexture)
				{
					_vTexture.removeEventListener(VideoTextureEvent.RENDER_STATE, renderVideoFrame);
					_vTexture.attachCamera(_camera);
					_vTexture.dispose();
					_vTexture = null;
				}
			}
			
			if (_preview)
			{
				_previewTexture.dispose();
				_previewTexture = null;
				_previewData = null;
				_previewBitmapData.dispose();
				_preview.dispose();
				_previewBitmapData = null;
				_preview = null;
			}
			
			if (_tempBitmapData) _tempBitmapData.dispose();
			if (_tempThumbBitmapData) _tempThumbBitmapData.dispose();
			
			onComplete();
		}
		
		override public function dispose():void
		{
			LogUtil.log("NativeCameraUI.dispose()");
			
			if (_isNativeCamera) 
			{
				com.distriqt.extension.camera.Camera.instance.release();
				com.distriqt.extension.camera.Camera.instance.dispose();
			}
			
			super.dispose();
		}
		
		override protected function initialize():void
		{
			super.initialize();
			
			LogUtil.log("NativeCameraUI.initialize()");
			
			_isIOS = DeviceResolution.isIOS();
			_isAndroid = DeviceResolution.isAndroid();
			_isNativeCamera = (_isIOS || _isAndroid) ? true : false;
			_stageOrientation = Starling.current.nativeStage.orientation;
		}
		
		private function qrCoding():void
		{
			/*/ QRCODE ENCODING.... ////////////////////////////////////////////////////////////////////
			   var qrEncode:org.qrcode.QRCode = new QRCode(QRCodeErrorLevel.QRCODE_ERROR_LEVEL_HIGH);
			   var img:Bitmap;
			
			   var stToEncode:String = "fania,23";
			   qrEncode.encode(stToEncode);
			   img = new Bitmap(qrEncode.bitmapData);
			
			   var bm:starling.textures.Texture = starling.textures.Texture.fromBitmapData(qrEncode.bitmapData, false);
			   var qrcodeImg:Image = new Image(bm);
			   qrcodeImg.x = (GlobalSettings.APPORIGINALWIDTH / 2) - (qrcodeImg.width / 2);
			   qrcodeImg.y = (GlobalSettings.APPORIGINALHEIGHT / 2) - (qrcodeImg.height / 1.3);
			   addChild(qrcodeImg);
			
			   return;
			   ///////////////////////////////////////////////////////////////////////////////////////////*/
		}
		
		public function startCam():void
		{
			this.setDeviceOrientation();
			this.drawGraphicCameraCanvas();
			
			if (!_isNativeCamera) 	// Create Fallback, use AS3 Camera
			{
				_preview.y = 0;//_preview.height >> 1;
				createStatusLabel();
				initializeAirCamera();
			}
			else
			{
				if (_isIOS) this.paddingTop = -(bottomHeight);
				
				this.addNativeCameraEvents();
				this.startNativeCamera();
			}
		}
		
		public function initializeNativeExtension():void
		{
			try
			{
				com.distriqt.extension.camera.Camera.init(GlobalSettings.EXTENSION_KEY);
				
				_isNativeCameraSupported = com.distriqt.extension.camera.Camera.isSupported
				
				if (!_isNativeCameraSupported)
				{
					LogUtil.log("Camera.isSupported: " + com.distriqt.extension.camera.Camera.isSupported);
				}
				else
				{
					this.setCaptureDevicePosition();
					this.setNativeCameraParameters();
				}
				
			}
			catch (e:Error)
			{
				trace(e);
			}
		}		
		
		private function drawGraphicCameraCanvas():void
		{
			_previewData = new ByteArray();
			_previewBitmapData = new BitmapData(_finalWidthOutput * _scale, _finalHeightOutput * _scale, false, 0x000000);
			_previewBitmapRect = new Rectangle(0, 0, _finalWidthOutput * _scale, _finalHeightOutput * _scale);
			
			_previewTexture = starling.textures.Texture.fromBitmapData(_previewBitmapData);
			
			_preview = new Image(_previewTexture);
			
			this.addChild(_preview);
		}
		
		private function setDeviceOrientation():void
		{
			LogUtil.log("NativeCameraUI.setDeviceOrientation() " + _stageOrientation);
			
			if (_isNativeCamera && _stageOrientation == StageOrientation.DEFAULT || _stageOrientation == StageOrientation.UPSIDE_DOWN)
			{
				if (_stageOrientation == StageOrientation.UPSIDE_DOWN) 
				{
					_orientation = 90;
					Starling.current.nativeStage.setOrientation(StageOrientation.DEFAULT);
				}
				else if (_stageOrientation == StageOrientation.DEFAULT) 
				{
					_orientation = 90;
					Starling.current.nativeStage.setOrientation(StageOrientation.DEFAULT);
				}
				
				Starling.current.nativeStage.autoOrients = false;
				
				// _orientation = 90;
				//_preview.rotation = DEG2RAD(_orientation);
			}			
		}
		
		private function releaseDeviceOrientation():void
		{
			Starling.current.nativeStage.autoOrients = true;
		}
		
		private function setCaptureDevicePosition(position:String = CaptureDevice.POSITION_BACK):void
		{
			var devices:Array = com.distriqt.extension.camera.Camera.instance.getAvailableDevices();
			for each (var device:CaptureDevice in devices)
			{
				if (device.position == position)
				{
					_device = device;
					break;
				}
			}
		}
		
		private function setNativeCameraParameters():void
		{
			_options = new CameraParameters();
			_options.enableFrameBuffer = true;
			_options.cameraMode = new CameraMode(CameraMode.PRESET_MEDIUM);
			_options.deviceId = _device.id;
			_options.prepareForCapture = true;
			_options.correctOrientation = true;
			_options.frameBufferWidth = _finalWidthOutput * _scale;
			_options.frameBufferHeight = _finalHeightOutput * _scale;
		}		
		
		private function addNativeCameraEvents():void
		{
			com.distriqt.extension.camera.Camera.instance.addEventListener(CameraDataEvent.CAPTURED_IMAGE, camera_capturedImageHandler, false, 0, true);
			com.distriqt.extension.camera.Camera.instance.addEventListener(CameraEvent.VIDEO_FRAME, camera_videoFrameHandler);
		}

		private function removeNativeCameraEvents():void
		{
			com.distriqt.extension.camera.Camera.instance.removeEventListener(CameraDataEvent.CAPTURED_IMAGE, camera_capturedImageHandler);
			com.distriqt.extension.camera.Camera.instance.removeEventListener(CameraEvent.VIDEO_FRAME, camera_videoFrameHandler);
		}
		
		private function startNativeCamera():void
		{
			com.distriqt.extension.camera.Camera.instance.initialise(_options);
			com.distriqt.extension.camera.Camera.instance.setFocusMode(CameraParameters.FOCUS_MODE_CONTINUOUS);
			com.distriqt.extension.camera.Camera.instance.setFocusPointOfInterest(new Point(0.5, 0.5));
			// com.distriqt.extension.camera.Camera.instance.setExposureMode( CameraParameters.EXPOSURE_MODE_CONTINUOUS );
		}
		
		private function releaseNativeExtension():void
		{
			com.distriqt.extension.camera.Camera.instance.release();
		}
		
		private function camera_videoFrameHandler(event:CameraEvent):void
		{
			var s:Number, t:Matrix;
			
			var frame:Number = com.distriqt.extension.camera.Camera.instance.receivedFrames;
			if (frame != _lastFrameProcessed)
			{
				if (-1 != com.distriqt.extension.camera.Camera.instance.getFrameBuffer(_previewData))
				{
					try
					{
						//	Check we have an appropriately sized bitmapdata and texture
						//	Recreate if not
						if (_previewBitmapData.width != com.distriqt.extension.camera.Camera.instance.width || _previewBitmapData.height != com.distriqt.extension.camera.Camera.instance.height)
						{
							LogUtil.log("NativeCameraUI.camera_videoFrameHandler() size: (" + com.distriqt.extension.camera.Camera.instance.width + ", " + com.distriqt.extension.camera.Camera.instance.height + ")");
							
							_previewBitmapData.dispose();
							_previewTexture.dispose();
							
							_previewBitmapData = new BitmapData(com.distriqt.extension.camera.Camera.instance.width, com.distriqt.extension.camera.Camera.instance.height, false);
							_previewTexture = starling.textures.Texture.fromBitmapData(_previewBitmapData);
						
							_preview.texture = _previewTexture;
							_preview.readjustSize();
							
							_previewBitmapRect = new Rectangle(0, 0, _previewBitmapData.width, _previewBitmapData.height);
							
							s = 1.0;
							t = new Matrix();
							
							LogUtil.log("_device.orientation: " + _device.orientation);
							
							if (_orientation == 90 || _orientation == 270)
							{
								s = stage.stageWidth / _previewTexture.height;
								
								t.translate(-_previewTexture.width * 0.5, -_previewTexture.height * 0.5);
								t.rotate(DEG2RAD(_orientation));
								t.scale(s, s);
								t.translate(_previewTexture.height * s * 0.5, _previewTexture.width * s * 0.5);
							}
							
							// Something weird happens here with large bitmaps and it turns white? If you comment out this line it works but isn't rotated...
							_preview.transformationMatrix = t;
							
							if (_isAndroid && _currentCameraPosition == 1) this.flipCameraTexture();
						}
						
						//	Update the bitmapdata and texture
						_previewBitmapData.setPixels(_previewBitmapRect, _previewData);
						
						flash.display3D.textures.Texture(_previewTexture.base).uploadFromBitmapData(_previewBitmapData);
					}
					catch (e:Error)
					{
						LogUtil.log(e);
					}
					finally
					{
						_previewData.clear();
						_lastFrameProcessed = frame;
					}
				}
			}
		}
		
		private function flipCameraTexture():void
		{
			var mFlipHorizontal:Boolean = true;
			var mFlipVertical:Boolean = true;
			
			_preview.setTexCoords(0, new Point(mFlipHorizontal ? 1.0 : 0.0, mFlipVertical ? 1.0 : 0.0));
			_preview.setTexCoords(1, new Point(mFlipHorizontal ? 0.0 : 1.0, mFlipVertical ? 1.0 : 0.0));
			_preview.setTexCoords(2, new Point(mFlipHorizontal ? 1.0 : 0.0, mFlipVertical ? 0.0 : 1.0));
			_preview.setTexCoords(3, new Point(mFlipHorizontal ? 0.0 : 1.0, mFlipVertical ? 0.0 : 1.0));
		}
		
		// INTERFACE BUTTONS
		
		public function switchCameraPosition(currentCameraPosition:int, onCamSwitchPositionComplete:Function):void
		{
			if (!this._isIOS && !this._isAndroid) 
			{
				onCamSwitchPositionComplete();
				
				return;
			}
			
			_currentCameraPosition = currentCameraPosition;
			
			releaseNativeExtension();
			removeNativeCameraEvents();
			
			if (_preview)
			{
				this.removeChild(_preview);
				
				_previewTexture.dispose();
				_previewTexture = null;
				_previewData = null;
				_previewBitmapData.dispose();
				_preview.dispose();
				_previewBitmapData = null;
				_preview = null;
				
				startDisposeClear();
			}
			
			function startDisposeClear():void
			{
				Starling.juggler.delayCall(disposeAll, 0.5, onCompleteClear);
			}
			
			function onCompleteClear():void
			{
				var newPos:String = (currentCameraPosition == 1) ? CaptureDevice.POSITION_FRONT : CaptureDevice.POSITION_BACK;
			
				drawGraphicCameraCanvas();
				setCaptureDevicePosition(newPos);
				setNativeCameraParameters();
				addNativeCameraEvents();
				startNativeCamera();
				
				Starling.juggler.delayCall(onCamSwitchPositionComplete, 1.00);
			}
		}
		
		public function captureImage():void
		{
			var filterColor:ColorFilter = new ColorFilter();
			
			_preview.filter = filterColor;
			
			Starling.juggler.tween(_preview.filter, 0.35, {transition:Transitions.EASE_OUT, brightness:0.95, onComplete:startCapture});
			Starling.juggler.tween(_preview.filter, 0.39, {delay:0.31,transition:Transitions.EASE_OUT, brightness:0});
			
			function startCapture():void
			{
				if (_isNativeCamera)
				{
					// Start the image capture 
					com.distriqt.extension.camera.Camera.instance.captureImage(false);
				}
				else		// fallback to AS3 Camera
				{
					saveImage(null);
				}				
			}
		}
		
		private function camera_capturedImageHandler(event:CameraDataEvent):void
		{
			saveImage(event.data);
		}
		
		public function enableVideoStream():void
		{
			if (!_isNativeCamera)
			{
				if (_camera)
				{
					_camera.addEventListener(flash.events.Event.VIDEO_FRAME, onVideoFrame);
				
					// Enable video to texture functions...
					_vTexture = Starling.context.createVideoTexture();
					_vTexture.attachCamera(_camera);
					_vTexture.addEventListener(VideoTextureEvent.RENDER_STATE, renderVideoFrame);
					
					updateVideoTexture();
				}
			}
			else addNativeCameraEvents();
		}
		
		public function disableVideoStream():void
		{
			if (!_isNativeCamera)
			{
				// Deactivate video to texture functions...
				if (_vTexture)
				{
					_vTexture.removeEventListener(VideoTextureEvent.RENDER_STATE, renderVideoFrame);
					_camera.removeEventListener(flash.events.Event.VIDEO_FRAME, onVideoFrame);
				}
			}
			else
			{
				removeNativeCameraEvents();
			}
		}
		
		public function clearVideoStream():void
		{
			// Deactivate video to texture functions...
			_vTexture.removeEventListener(VideoTextureEvent.RENDER_STATE, renderVideoFrame);
			_camera.removeEventListener(flash.events.Event.VIDEO_FRAME, onVideoFrame);
			_vTexture.dispose();
		}		
		
		public function switchCamera():void
		{
			
		}
		
		
		private function saveImage(bmd:BitmapData):void
		{
			// For Quick Preview Test ********************************************************************
			// var im:Image = new Image(starling.textures.Texture.fromBitmapData(bmd, false));
			// this.addChild(im);
			// return;
			
			disableVideoStream();
			
			if (_tempBitmapData) _tempBitmapData.dispose();
			if (_tempThumbBitmapData) _tempThumbBitmapData.dispose();
			
			_tempBitmapData = new BitmapData(_finalWidthOutput, _finalHeightOutput, false, 0x000000);
			
			var t:Matrix, s:Number;
			var heightPercent:Number = (_isIOS) ? 0.25 : 0.5;
			
			// If null, we are using AS3 Camera and need to create a bitmapData
			if (!bmd)
			{
				bmd = new BitmapData(_finalWidthOutput, _finalHeightOutput, false, 0xFF0000);
				_camera.drawToBitmapData(bmd);
				
				if (_orientation == 90 || _orientation == 270)
				{
					t = new Matrix();
					t.translate(-bmd.width * 0.5, -bmd.height * 0.5);
					t.rotate(DEG2RAD(_orientation));
					t.translate(_previewTexture.height * 0.5, _previewTexture.width * 0.5);					
					_tempBitmapData.draw(bmd, t, null, null, null, true);
				}
				else _tempBitmapData.draw(bmd, null, null, null, null, true);
				
				_preview.texture = starling.textures.Texture.fromBitmapData(bmd, false, false);
			}
			else
			{
				// BitmapData is coming from native extension
				// If running on iOS we will need to correct its orientation
				// The result we get back from Native Camera iOS is at an angle
				// On Android, we get the correct orientation.  No need to rotate
				if (_orientation == 90 || _orientation == 270)
				{
					if (_isIOS)
					{
						s = _finalWidthOutput / bmd.height;
						t = new Matrix();
						t.translate(-bmd.width * 0.5, -bmd.height * 0.5);
						t.rotate(DEG2RAD(_orientation));
						t.scale(s, s);
						t.translate(bmd.height * s * 0.5, bmd.width * s * heightPercent);
						_tempBitmapData.draw(bmd, t, null, null, null, true);
					}
					else _tempBitmapData = this.fitImageProportionally(bmd, _finalWidthOutput, _finalHeightOutput, true, true).bitmapData;
				}
			}
			
			bmd.dispose();	
			
			_tempThumbBitmapData = this.fitImageProportionally(_tempBitmapData, _finalThumWidthOutput, _finalThumbHeightOutput, true, true).bitmapData;

			this.dispatchEventWith(ONCAPTUREIMAGE_ALERT);
		}
		
		protected function showAlert(message:String, title:String, buttons:Array):void
		{
			if (buttons)
			{
				_alert = Alert.show(message, title, new ListCollection(buttons), null, false);
			}
			else _alert = Alert.show(message, title);
		}
		
		protected function removeAlert():void
		{
			if (_alert) _alert.removeFromParent( true );
		}		
		
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		// AIR Camera
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		
		private function createStatusLabel():void
		{
			// var statusDebugLabelLayoutData:AnchorLayoutData = new AnchorLayoutData(0, NaN, NaN, 0);
			
			_statusDebugLabel = new Label();
			_statusDebugLabel.styleNameList.add(GlobalSettings.LABEL_30SIZE_WHITE);
			_statusDebugLabel.text = "Starting....";
			this.addChild(_statusDebugLabel);
		}
		
		private function initializeAirCamera():void
		{
			_camera = flash.media.Camera.getCamera();
			_camera.addEventListener(flash.events.Event.VIDEO_FRAME, onVideoFrame);
			
			updateCameraSettings();
			updateVideoTexture();
		}
		
		private function onVideoFrame(e:flash.events.Event):void
		{
			_statusDebugLabel.text = (_camera.width + " x " + _camera.height + " @ " + _camera.currentFPS.toFixed(1));
		}
		
		private function updateCameraSettings():void
		{
			var _camW:int = 640;
			var _camH:int = 640;
			_camera.setMode(_camW, _camH, 15);
			_camera.setQuality(0, 50);
		}
		
		public function updateVideoTexture():void
		{
			_statusDebugLabel.text = "init VideoTexture ...";
			
			if (!Context3D.supportsVideoTexture)
			{
				_statusDebugLabel.text = " - VideoTexture is NOT supported.";
				return;
			}
			
			if (_vTexture != null)
			{
				_statusDebugLabel.text = " - VideoTexture: clear old";
				
				_vTexture.removeEventListener(VideoTextureEvent.RENDER_STATE, renderVideoFrame);
				_vTexture.dispose();
				_vTexture = null;
			}
			
			_vTexture = Starling.context.createVideoTexture();
			_vTexture.attachCamera(_camera);
			_vTexture.addEventListener(VideoTextureEvent.RENDER_STATE, renderVideoFrame);
			
			_preview.texture.dispose();
			_preview.texture = new ConcreteTexture(_vTexture, Context3DTextureFormat.BGR_PACKED, 512, 512, false, false, false);
		}
		
		private function renderVideoFrame(e:VideoTextureEvent):void
		{
			LogUtil.log('renderVideoFrame: ' + e.status);
		}
		
		public function setCamIdx(_idx:int = 0):String
		{
			_idx = Math.max(0, Math.min(flash.media.Camera.names.length - 1, _idx));
			
			if (_camera.index == _idx)
			{
				trace(" => already set: " + _camera.name + ' / ' + _camera.index);
			}
			else
			{
				_camera = flash.media.Camera.getCamera(_idx.toString());
				
				if (Starling.current != null)
				{
					// StarlingMain.instance.updateVideoTexture();
					updateVideoTexture();
				}
			}
			
			return _camera.name;
		}
				
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Bitmap fitImageProportionally()
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		
		/**
		* fitImageProportionally
		* @srcBitmapData   	the source bitmapdata to work with
		* @newWidth   		width of the box to fit the image into
		* @newHeight   		height of the box to fit the image into
		* @isCenter   		should it offset to center the result in the box
		* @isFillBox  		should it fill the box, may crop the image (true), or fit the whole image within the bounds (false)
		**/		
		private function fitImageProportionally( srcBitmapData:BitmapData, newWidth:Number, newHeight:Number, isCenter:Boolean = true, isFillBox:Boolean = true ):Bitmap
		{
			var scaleBmpd:BitmapData, scaledBitmap:Bitmap, scaleMatrix:Matrix;
			var cropMatrix:Matrix, cropArea:Rectangle, croppedBmpd:BitmapData, croppedBitmap:Bitmap;
			var offsetX:Number, offsetY:Number;
			
			var bitmap:Bitmap = new Bitmap(srcBitmapData);
			var tempW:Number = bitmap.width;
			var tempH:Number = bitmap.height;
		 
			bitmap.width = newWidth;
			bitmap.height = newHeight;
		 
			var scale:Number = (isFillBox) ? Math.max(bitmap.scaleX, bitmap.scaleY) : Math.min(bitmap.scaleX, bitmap.scaleY);
		 
			bitmap.width = tempW;
			bitmap.height = tempH;
		 
			scaleBmpd = new BitmapData(bitmap.width * scale, bitmap.height * scale);
			scaledBitmap = new Bitmap(scaleBmpd, PixelSnapping.ALWAYS, true);
			scaleMatrix = new Matrix();
			scaleMatrix.scale(scale, scale);
			scaleBmpd.draw( bitmap, scaleMatrix );
		 
			if (scaledBitmap.width > newWidth || scaledBitmap.height > newHeight) 
			{
				cropMatrix = new Matrix();
				cropArea = new Rectangle(0, 0, newWidth, newHeight);
		 
				croppedBmpd = new BitmapData(newWidth, newHeight);
				croppedBitmap = new Bitmap(croppedBmpd, PixelSnapping.ALWAYS, true);
		 
				if (isCenter) 
				{
					offsetX = Math.abs((newWidth -scaleBmpd.width) / 2);
					offsetY = Math.abs((newHeight - scaleBmpd.height) / 2);
		 
					cropMatrix.translate(-offsetX, -offsetY);
				}
		 
				croppedBmpd.draw( scaledBitmap, cropMatrix, null, null, cropArea, true );
				
				return croppedBitmap;
			} 
			else return scaledBitmap;
		}			
		
		private function DEG2RAD(degrees:Number):Number
		{
			return degrees * Math.PI / 180;
		}
	}

}