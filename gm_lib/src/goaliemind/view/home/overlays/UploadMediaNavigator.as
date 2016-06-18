package goaliemind.view.home.overlays 
{
	import flash.display.Bitmap;
	import flash.display.BitmapData;
	import flash.display.JPEGEncoderOptions;
	import flash.events.IOErrorEvent;
	import flash.filesystem.File;
	import flash.filesystem.FileMode;
	import flash.filesystem.FileStream;
	import flash.geom.Rectangle;
	import flash.utils.ByteArray;

	import feathers.controls.StackScreenNavigator;
	import feathers.controls.StackScreenNavigatorItem;
	import feathers.layout.AnchorLayoutData;
	import feathers.motion.Slide;

	import starling.display.Quad;
	import starling.events.Event;
	
	import goaliemind.view.home.overlays.media.SaveComplete;
	import goaliemind.view.home.overlays.media.SaveHome;
	import goaliemind.core.AppNavigator;
	import goaliemind.system.LogUtil;

	[Event(name="onCreateProjectComplete",type="starling.events.Event")]
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class UploadMediaNavigator extends AppNavigator 
	{
		private static const HOME:String = "saveHomeScreen";
		private static const SAVECOMPLETE:String = "saveCompleteScreen";
		
		public function UploadMediaNavigator() 
		{
			super();
			
			isTransitionIn = true;
		}
		
		override public function dispose():void 
		{
			super.dispose();
		}
		
		override protected function initialize():void 
		{
			super.initialize();
			
			this.backgroundSkin = new Quad(20, 20, 0xFFFFFF);
			
			initializeNavigator();
			
			if (isTransitionIn) this.transitionIn();
		}
		
		private function initializeNavigator():void
		{
			var navigatorLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, 0, 0);
			
			_navigator = new StackScreenNavigator();
			_navigator.layoutData = navigatorLayoutData;
			
			var saveHomeScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(SaveHome);
			saveHomeScreen.setFunctionForPushEvent(AppNavigator.CLOSEPANEL, onClosePanel);
			saveHomeScreen.setFunctionForPushEvent(SAVECOMPLETE, onSaveComplete);
			saveHomeScreen.setFunctionForPushEvent("onBack", onBack);
			_navigator.addScreen(HOME, saveHomeScreen);
			
			var saveCompleteScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(SaveComplete);
			saveCompleteScreen.setFunctionForPushEvent("onBack", onBack);
			_navigator.addScreen(SAVECOMPLETE, saveCompleteScreen);

			_navigator.pushTransition = Slide.createSlideLeftTransition(); 
			_navigator.popTransition = Slide.createSlideRightTransition();
			
			_navigator.rootScreenID = HOME;
			
			//_navigator.width = _manager.appCore.width;
			// _navigator.height = _manager.appCore.height;
			
			addChild(this._navigator);
		}
		
		override public function transitionInComplete():void 
		{
			super.transitionInComplete();
			
		}
		
		private function startSavingProcess():void
		{
			saveImageToDevice("thumb", "thumb" + GlobalSettings.MEDIA_IMAGE_EXT, 100, 100, onSaveThumbComplete, true);
			
			function onSaveThumbComplete():void
			{
				saveFullImage();
			}
			
			function saveFullImage():void
			{
				saveImageToDevice("fullsize", "fullsize" + GlobalSettings.MEDIA_IMAGE_EXT, 640, 640, onSaveFullSizeComplete, false);
			}
			
			function onSaveFullSizeComplete():void
			{
				// Start Upload to Server
				
			}
		}
		
		private var _stream:FileStream;
		public function saveImageToDevice(type:String, imageFilename:String, imgWidth:int, imgHeight:int, onSaveComplete:Function, isCrop:Boolean = false):void
		{
			LogUtil.log("UploadMediaNavigator.saveImageToDevice() Full Asset Width: " + _manager.nativeCamPanel.tempBitmapData.width + " | Full Asset Height: " + _manager.nativeCamPanel.tempBitmapData.height + " imageFilename: " + imageFilename);
			
			/*var tempBM:Bitmap;
			if (type == "thumb")
			{
				tempBM = BitmapSerialize.fitImageProportionally(_manager.nativeCamPanel.tempBitmapData, imgWidth, imgHeight, true, isCrop);
			}
			else
			{
				tempBM = new Bitmap(_manager.nativeCamPanel.tempBitmapData);
			}
			
			LogUtil.log("Type: " + type + " | Asset Width: " + tempBM.bitmapData.width + " | Asset Height: " + tempBM.bitmapData.height);
			*/
			
			var tempBM:Bitmap;
			if (type == "thumb") tempBM = new Bitmap(_manager.nativeCamPanel.tempBitmapData);
			else tempBM = new Bitmap(_manager.nativeCamPanel.tempThumbBitmapData);
			
			var bdEncode:BitmapData;
			
			var rect:Rectangle = new Rectangle(0, 0, tempBM.bitmapData.width, tempBM.bitmapData.height);
			// var imageByteArray:ByteArray = tempBM.bitmapData.encode(rect, new PNGEncoderOptions(true));
			var imageByteArray:ByteArray = tempBM.bitmapData.encode(rect, new JPEGEncoderOptions(50));
			
			var file:File = File.applicationStorageDirectory.resolvePath(imageFilename);
			
			_stream = new FileStream();
			_stream.addEventListener(Event.CLOSE, onSaveImageClose);
			_stream.addEventListener(IOErrorEvent.IO_ERROR, onSaveImageError);
			_stream.openAsync(file, FileMode.WRITE);
			_stream.writeBytes( imageByteArray, 0, imageByteArray.length );         
			_stream.close();
			
			function onSaveImageClose(e:Event):void 
			{
				removeSaveStreamEvents();
				onSaveComplete({ status:"image-saved" });
			}
			
			function onSaveImageError(e:Event):void 
			{
				removeSaveStreamEvents();
				onSaveComplete({ status:"image-save-error" });
			}			
			
			function removeSaveStreamEvents():void 
			{
				_stream.removeEventListener(Event.CLOSE, onSaveImageClose);
				_stream.removeEventListener(IOErrorEvent.IO_ERROR, onSaveImageError);
				_stream = null;
				imageByteArray = null;
				tempBM.bitmapData.dispose();
				tempBM.bitmapData = null;
				tempBM = null;
			}		
		}		
		
		private function onBack():void 
		{
			switch (_navigator.activeScreenID)
			{
				case SAVECOMPLETE:
					_navigator.popScreen();
					break;
				
			}
		}
		
		private function onSaveComplete():void 
		{
			_navigator.pushScreen(SAVECOMPLETE);
		}
		
		private function onClosePanel(info:Object):void 
		{
			if (info["data"] != null)
			{
				if (info["data"]["status"] == "onCreateProjectComplete")
				{
					// Coming from SaveHome Screen
					this.dispatchEventWith("onCreateProjectComplete");
				}
			}
			else this.dispatchEventWith(Event.CLOSE);
		}
	}

}