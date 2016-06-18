package goaliemind.view.home.panels
{
	import flash.desktop.NativeApplication;
	import flash.desktop.SystemIdleMode;
	import flash.events.Event;

	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.Panel;
	import feathers.core.PopUpManager;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;

	import starling.animation.Transitions;
	import starling.core.Starling;
	import starling.display.DisplayObject;
	import starling.display.Quad;
	import starling.events.Event;

	import goaliemind.components.ColorFilter;
	import goaliemind.ui.base.HomePanelScreen;
	import goaliemind.view.home.overlays.UploadMediaNavigator;
	import goaliemind.system.LogUtil;
	
	[Event(name="onClosePanel", type="starling.events.Event")]
	[Event(name="onProjectComplete", type="starling.events.Event")]
	
	/**
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class CamUploadPanel extends HomePanelScreen
	{
		private static const PHOTO_MODE:uint = 1;
		private static const VIDEO_MODE:uint = 2;
		private static const FRIEND_MODE:uint = 3;
		
		private static const CREATE_SECTION_MODE:uint = 1;
		private static const EDIT_SECTION_MODE:uint = 2;
		private static const SAVE_SECTION_MODE:uint = 3;
		
		private static const CAMERA_FRONT:uint = 1;
		private static const CAMERA_BACK:uint = 2;
		
		private var _currentMode:uint;
		private var _currentSectionMode:uint;
		private var _currentCameraPosition:uint;
		private var _previousMode:uint;
		private var _previousSectionMode:uint;
		private var _previousCameraPosition:uint;
		
		private var _nativeCamBackgroundPanel:Panel;
		private var _camMenuOptions:Header;
		
		private var _uploadMediaNavigator:UploadMediaNavigator;
		private var _backButton:Button;
		private var _saveButton:Button;
		private var _buttonFilterColor:ColorFilter;
		
		public function CamUploadPanel()
		{
			super();
		}
		
		public function disposeNativeCamUI():void
		{
			NativeApplication.nativeApplication.systemIdleMode = SystemIdleMode.NORMAL;
			NativeApplication.nativeApplication.removeEventListener(flash.events.Event.DEACTIVATE, deactivateHandler);
			NativeApplication.nativeApplication.removeEventListener(flash.events.Event.ACTIVATE, activateHandler);
			
			if (_manager)
			{
				_manager.nativeCamPanel.removeEventListener("onCaptureImageAlert", onCaptureImageAlert);
				_manager.nativeCamPanel.removeEventListener("onSaveImageAlert", onSaveImageAlert);
				_manager.nativeCamPanel.removeFromParent();
			}
			
			resetScreenParameters();
		}
		
		override public function dispose():void
		{
			// never forget to dispose textures!
			super.dispose();
		}
		
		override protected function initialize():void
		{
			super.initialize();
			
			this._currentMode = this._previousMode = PHOTO_MODE;
			this._currentCameraPosition = this._previousCameraPosition = CAMERA_BACK;
			this._currentSectionMode = this._previousSectionMode = CREATE_SECTION_MODE;
			
			this.title = "Take Photo";
			this.layout = new AnchorLayout();
			this.backgroundSkin = new Quad(10, 10, 0x333333);
			this.headerFactory = this.customHeaderFactory;
			
			this.updateScreen();
		}
		
		private function resetScreenParameters():void
		{
			this._currentMode = this._previousMode = PHOTO_MODE;
			this._currentCameraPosition = this._previousCameraPosition = CAMERA_BACK;
			this._currentSectionMode = this._previousSectionMode = CREATE_SECTION_MODE;
			
			navigateTo(_currentSectionMode);
		}
		
		private function addNativeCamEvents():void
		{
			LogUtil.log("CamUploadPanel.addNativeCamEvents()");
			
			_manager.nativeCamPanel.addEventListener("onCaptureImageAlert", onCaptureImageAlert);
			_manager.nativeCamPanel.addEventListener("onSaveImageAlert", onSaveImageAlert);
			
			NativeApplication.nativeApplication.addEventListener(flash.events.Event.DEACTIVATE, deactivateHandler);
			NativeApplication.nativeApplication.addEventListener(flash.events.Event.ACTIVATE, activateHandler);
		}
		
		private function deactivateHandler(event:flash.events.Event):void
		{
			_manager.nativeCamPanel.disableVideoStream();
		}
		
		private function activateHandler(event:flash.events.Event):void
		{
			if (_currentSectionMode == CREATE_SECTION_MODE) 
			{
				_manager.nativeCamPanel.enableVideoStream();
			}
		}		
		
		private function updateScreen():void 
		{
			if (!_nativeCamBackgroundPanel)
			{
				var nativeCamBackgroundPanelLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0);
				_nativeCamBackgroundPanel = new Panel();
				_nativeCamBackgroundPanel.backgroundSkin = new Quad(10, 10, 0x003663);
				_nativeCamBackgroundPanel.layoutData = nativeCamBackgroundPanelLayoutData;
				_nativeCamBackgroundPanel.height = 640 * _manager.scale;
				this.addChild(_nativeCamBackgroundPanel);
				
				this.createCamMenuOptions();	
				return;
			}
			
			switch (_currentSectionMode)
			{
				case CREATE_SECTION_MODE:
					Starling.juggler.tween(_camMenuOptions, 0.5, 
					{
						transition: Transitions.EASE_OUT,
						onComplete:onCreateSectionFadeInComplete,
						alpha:1
					});
					break;
					
				case EDIT_SECTION_MODE:
					_camMenuOptions.touchable = false;
					Starling.juggler.tween(_camMenuOptions, 0.5, 
					{
						transition: Transitions.EASE_OUT,
						onComplete:onEditSectionFadeInComplete,
						alpha:0.25
					});
					break;
					
				case SAVE_SECTION_MODE:
					_uploadMediaNavigator = new UploadMediaNavigator();
					_uploadMediaNavigator.width = Starling.current.stage.stageWidth;
					_uploadMediaNavigator.height = Starling.current.stage.stageHeight;
					
					PopUpManager.addPopUp(_uploadMediaNavigator, false);
					
					_uploadMediaNavigator.addEventListener(starling.events.Event.CLOSE, onUploadMediaClose);
					_uploadMediaNavigator.addEventListener("onCreateProjectComplete", onCreateProjectComplete);
					break;
				
			}
			
			function onCreateProjectComplete():void
			{
				onUploadMediaClose();
				
				dispatchEventWith("onProjectComplete");
			}
			
			function onCreateSectionFadeInComplete():void
			{
				_camMenuOptions.touchable = true;
				
				if (_manager.nativeCamPanel)
				{
					if (_manager.nativeCamPanel.tempBitmapData) _manager.nativeCamPanel.tempBitmapData.dispose();
					
					_manager.nativeCamPanel.enableVideoStream();
				}
			}
			
			function onEditSectionFadeInComplete():void 
			{
				
			}
			
			function onUploadMediaClose():void
			{
				LogUtil.log("UploadMediaNavigator.onUploadMediaClose");
				_uploadMediaNavigator.removeEventListener(starling.events.Event.CLOSE, onUploadMediaClose);
				PopUpManager.removePopUp(_uploadMediaNavigator, true);
				navigateBackTo();
			}
		}
		
		private function onCaptureImageAlert(e:starling.events.Event):void 
		{
			navigateTo(EDIT_SECTION_MODE);
		}
		
		private function switchCameraMode():void
		{
			_previousMode = _currentMode;
			
			if (_currentMode == PHOTO_MODE) _currentMode = VIDEO_MODE;
			else if (_currentMode == VIDEO_MODE) _currentMode = PHOTO_MODE;
			
			updateHeader();
			updateScreen();
		}
		
		private function navigateBackTo():void
		{
			if (_currentSectionMode == CREATE_SECTION_MODE)
			{
				this.dispatchEventWith("onClosePanel");
			}
			else if (_currentSectionMode == EDIT_SECTION_MODE)
			{
				navigateTo(CREATE_SECTION_MODE);
			}
			else if (_currentSectionMode == SAVE_SECTION_MODE)
			{
				navigateTo(EDIT_SECTION_MODE);
				// Save and Close... back to Home?
			}
		}		
		
		private function navigateTo(sectionMode:uint):void
		{
			if (sectionMode == CREATE_SECTION_MODE)
			{
				_previousSectionMode = _currentSectionMode;
				_currentSectionMode = CREATE_SECTION_MODE;
			}
			else if (sectionMode == EDIT_SECTION_MODE)
			{
				_previousSectionMode = _currentSectionMode;
				_currentSectionMode = EDIT_SECTION_MODE;
			}
			else if (sectionMode == SAVE_SECTION_MODE)
			{
				_previousSectionMode = _currentSectionMode;
				_currentSectionMode = SAVE_SECTION_MODE;
			}
			
			updateHeader();
			updateScreen();
		}
		
		private function onSaveImageAlert(e:starling.events.Event):void 
		{
			
		}
		
		public function onCamUploadPanelFadeInComplete():void
		{
			NativeApplication.nativeApplication.systemIdleMode = SystemIdleMode.KEEP_AWAKE;
			
			var camPanelLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, 0, 0);
			camPanelLayoutData.bottomAnchorDisplayObject = _camMenuOptions;
			
			this._camMenuOptions.validate();
			_manager.nativeCamPanel.bottomHeight = this._camMenuOptions.height;
			
			_manager.nativeCamPanel.layoutData = camPanelLayoutData;
			this.addChild(_manager.nativeCamPanel);
			
			Starling.juggler.delayCall(addNativeCamEvents, 0.5);
			
			_manager.nativeCamPanel.startCam();
		}
		
		private function customHeaderFactory():Header
		{
			var header:Header = new Header();
			
			header.styleNameList.add(GlobalSettings.CAMUPLOADPANELHEADER);
			
			updateHeader(header);
			
			return header;
		}
		
		private function clearHeaderContent():void
		{
			if (_backButton)
			{
				_backButton.removeEventListener(starling.events.Event.TRIGGERED, onButton);
				_backButton.dispose();
				_backButton = null;
			}
			
			if (_saveButton)
			{
				_saveButton.removeEventListener(starling.events.Event.TRIGGERED, onButton);
				_saveButton.dispose();
				_saveButton = null;
			}
			
			if (header == null) return;
			
			(header as Header).leftItems = null;
			(header as Header).centerItems = null;
			(header as Header).rightItems = null;
			(header as Header).validate();
		}
		
		private function updateHeader(_header:Header = null):void
		{
			var updateHeader:Header = (_header) ? _header : this.header as Header;
			updateHeader.alpha = 0;
			
			switch (_currentMode)
			{
				case PHOTO_MODE:
					
					clearHeaderContent();
					
					_backButton = new Button();
					_backButton.name = "back";
					_backButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
					_backButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("header/back_header_icon", _manager.scale);
					_backButton.addEventListener(starling.events.Event.TRIGGERED, onButton);
					
					updateHeader.leftItems = new <DisplayObject>[_backButton]; 
					
					if (this._currentSectionMode == EDIT_SECTION_MODE)
					{
						updateHeader.title = "Save Photo";
						
						_buttonFilterColor = new ColorFilter();
						 _buttonFilterColor.tint(0xFF0000, 1);

						_saveButton = new Button();
						_saveButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
						_saveButton.name = "savePhoto";
						_saveButton.filter = _buttonFilterColor;
						_saveButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("header/next_header_icon", _manager.appCore.theme.scale);
						_saveButton.addEventListener(starling.events.Event.TRIGGERED, onButton);
						
						updateHeader.rightItems = new <DisplayObject>[_saveButton]; 
					}
					else if (this._currentSectionMode == SAVE_SECTION_MODE)
					{
						
					}
					else 
					{
						updateHeader.title = "Take Photo";
					}

					break;
				
				case VIDEO_MODE:
					
					_backButton = new Button();
					_backButton.name = "back";
					_backButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
					_backButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("header/back_header_icon", _manager.appCore.theme.scale);
					_backButton.addEventListener(starling.events.Event.TRIGGERED, onButton);
					
					updateHeader.leftItems = new <DisplayObject>[_backButton]; 
					
					if (this._currentSectionMode == EDIT_SECTION_MODE)
					{
						updateHeader.title = "Save Video";
						
						_saveButton = new Button();
						_saveButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
						_saveButton.name = "saveVideo";
						_saveButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("header/next_header_icon", _manager.appCore.theme.scale);
						_saveButton.addEventListener(starling.events.Event.TRIGGERED, onButton);
						
						updateHeader.rightItems = new <DisplayObject>[_saveButton]; 
					}
					else if (this._currentSectionMode == SAVE_SECTION_MODE)
					{
						 _buttonFilterColor = new ColorFilter();
						 _buttonFilterColor.tint(0xFF0000, 1);
						 
						_saveButton = new Button();
						_saveButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
						_saveButton.name = "save";
						_saveButton.filter = _buttonFilterColor;
						_saveButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("header/next_header_icon", _manager.appCore.theme.scale);
						_saveButton.addEventListener(starling.events.Event.TRIGGERED, onButton);
						
						updateHeader.rightItems = new <DisplayObject>[_saveButton]; 
					}
					else 
					{
						updateHeader.title = "Record Video";
					}
					break;
				
				case FRIEND_MODE:
					
					clearHeaderContent();
					
					_backButton = new Button();
					_backButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
					_backButton.name = "back";
					_backButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("header/back_header_icon", _manager.appCore.theme.scale);
					_backButton.addEventListener(starling.events.Event.TRIGGERED, onButton);
					updateHeader.leftItems = new <DisplayObject>[_backButton];
					updateHeader.title = "Add a User";
					break;
				
			}
		
			Starling.juggler.tween(updateHeader, 0.75, { transition:Transitions.EASE_OUT, alpha:1 } );
		}
		
		private function createCamMenuOptions():void
		{
			var _camMenuLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, 0, 0);
			_camMenuLayoutData.topAnchorDisplayObject = _nativeCamBackgroundPanel;
			
			_camMenuOptions = new Header();
			_camMenuOptions.layoutData = _camMenuLayoutData;
			
			_camMenuOptions.styleNameList.add(GlobalSettings.CAMUPLOADPANELBOTTOM);
			
			var camSwitchButton:Button = new Button();
			camSwitchButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
			camSwitchButton.name = "switch";
			camSwitchButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("upload/switch_camera_icon", _manager.appCore.theme.scale);
			
			var uploadButton:Button = new Button();
			uploadButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
			uploadButton.name = "upload";
			uploadButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("upload/upload_button", _manager.appCore.theme.scale);
			
			var camButton:Button = new Button();
			camButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
			camButton.name = "camButton";
			camButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("upload/camera_icon", _manager.appCore.theme.scale);
			camButton.alpha = 0.15;
			camButton.touchable = false;
			
			_camMenuOptions.leftItems = new <DisplayObject>[camSwitchButton];
			_camMenuOptions.centerItems = new <DisplayObject>[uploadButton];
			_camMenuOptions.rightItems = new <DisplayObject>[camButton];
			
			camSwitchButton.addEventListener(starling.events.Event.TRIGGERED, onButton);
			uploadButton.addEventListener(starling.events.Event.TRIGGERED, onButton);
			camButton.addEventListener(starling.events.Event.TRIGGERED, onButton);
			
			this.addChild(_camMenuOptions);
		}
		
		private function onButton(e:starling.events.Event):void
		{
			var btn:Button = e.target as Button;
			if (!btn) return;
			
			switch (btn.name)
			{
				case "savePhoto":
					savePhoto();
					break;
					
				case "saveVideo":
					break;
					
				case "addFriend":
					break;
				
				case "switch": 
					_camMenuOptions.touchable = false;
					if (_currentCameraPosition == CAMERA_FRONT) _currentCameraPosition = CAMERA_BACK;
					else _currentCameraPosition = CAMERA_FRONT;
					_manager.nativeCamPanel.switchCameraPosition(_currentCameraPosition, onCamSwitchPositionComplete);
					break;
				case "upload": 
					_manager.nativeCamPanel.captureImage();
					break;
				case "camButton": 
					// switchCameraMode();
					break;
				case "back": 
					navigateBackTo();
					break;
			}
		}
		
		private function onCamSwitchPositionComplete():void
		{
			_camMenuOptions.touchable = true;
		}
		
		private function savePhoto():void
		{
			showAlert("Processing...", "Converting Image", null);
			Starling.juggler.delayCall(processDelay, 1);
			
			function processDelay():void
			{
				removeAlert();
				navigateTo(SAVE_SECTION_MODE);
			}
		}
		
		override public function show():void
		{
			super.show();
		}
	}

}