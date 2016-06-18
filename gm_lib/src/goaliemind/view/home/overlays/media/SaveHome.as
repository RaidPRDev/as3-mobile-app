package goaliemind.view.home.overlays.media 
{
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.Label;
	import feathers.controls.Panel;
	import feathers.controls.TextInput;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;
	import feathers.layout.HorizontalLayout;

	import starling.display.DisplayObject;
	import starling.display.Image;
	import starling.display.Quad;
	import starling.events.Event;
	import starling.textures.Texture;
	
	import goaliemind.ui.base.HomePanelScreen;
	import goaliemind.core.AppNavigator;
	import goaliemind.system.LogUtil;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class SaveHome extends HomePanelScreen
	{
		private static var MINCHARS:int = 10;
		private static var MAXCHARS:int = 500;
		private var _currentCharCount:int = 0;
		
		private var _inputComments:TextInput;
		private var _subPanel:Panel;
		private var _subPanelContent:Button;
		private var _subHeaderLabel:Label;
		private var _inputTags:TextInput;
		
		private var _subHeaderPadding:Number;
		private var _contentPadding:Number;
		
		public function SaveHome() 
		{
			super();
		}
		
		override public function dispose():void 
		{
			_subPanel.dispose();
			_subPanelContent.dispose();
			
			super.dispose();
		}
		
		override protected function initialize():void 
		{
			super.initialize();
			
			_subHeaderPadding = 20 * _manager.scale;
			_contentPadding = 40 * _manager.scale;
			
			this.layout = new AnchorLayout();
			
			this.title = "Share";
			
			this.headerFactory = this.customHeaderFactory;
			
			createSubHeader();
			createInputComments();
			createInputTag();
		}
		
		private function customHeaderFactory():Header
		{
			var header:Header = new Header();

			header.styleNameList.add(GlobalSettings.HOME_MAINHEADER);
			
			var backButton:Button = new Button();
			backButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
			backButton.name = "back";
			backButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("header/back_header_icon", _manager.appCore.theme.scale);

			var uploadButton:Button = new Button();
			uploadButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
			uploadButton.name = "upload";
			uploadButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("header/check_header_icon", _manager.appCore.theme.scale);
			
			header.leftItems = new <DisplayObject> [ backButton ];
			header.rightItems = new <DisplayObject> [ uploadButton ];
			
			backButton.addEventListener(Event.TRIGGERED, onHeaderButton);
			uploadButton.addEventListener(Event.TRIGGERED, onHeaderButton);
			
			return header;
		}		
		
		private function onHeaderButton(e:Event):void 
		{
			LogUtil.log("SaveHome.onHeaderButton()");
			
			if ((e.target as Button).name == "upload")
			{
				LogUtil.log("SaveHome.onHeaderButton.Upload()");
				
				createNewProject();
			}
			else if ((e.target as Button).name == "back")
			{
				this.dispatchEventWith(AppNavigator.CLOSEPANEL);
			}
		}		
		
		private function createSubHeader():void
		{
			var panelLayoutData:AnchorLayoutData = new AnchorLayoutData(_subHeaderPadding, _subHeaderPadding, NaN, _subHeaderPadding, 0, NaN);
			
			var verticalLayout:HorizontalLayout = new HorizontalLayout();
			verticalLayout.horizontalAlign = HorizontalLayout.HORIZONTAL_ALIGN_CENTER;
			verticalLayout.verticalAlign = HorizontalLayout.VERTICAL_ALIGN_MIDDLE;
			verticalLayout.gap = _manager.appCore.theme.regularPaddingSize;
			
			_subPanel = new Panel();
			_subPanel.backgroundSkin = new Quad(20, GlobalSettings.SUBHEADER_HEIGHT * _manager.appCore.theme.scale, GlobalSettings.BLUE_TEXT_COLOR);
			_subPanel.layoutData = panelLayoutData;
			_subPanel.layout = verticalLayout;
			
			var _bitmap:Texture = Texture.fromBitmapData(_manager.nativeCamPanel.tempThumbBitmapData);
			var _tempImage:Image = new Image(_bitmap);
			_tempImage.scaleX = _tempImage.scaleY = _manager.scale;
			
			_subPanelContent = new Button();
			_subPanelContent.defaultIcon = _tempImage;
			_subPanelContent.touchable = false;
			
			_subHeaderLabel = new Label();
			_subHeaderLabel.width = 260 * _manager.appCore.theme.scale;
			_subHeaderLabel.styleNameList.add(GlobalSettings.LABEL_22SIZE_WHITE);
			_subHeaderLabel.textRendererProperties.wordWrap = true;
			_subHeaderLabel.textRendererProperties.textAlign = "center";
			_subHeaderLabel.textRendererProperties.leading = _manager.appCore.theme.fontLeading * _manager.appCore.theme.scale;
			_subHeaderLabel.text = "Add a brief description about your project.";
			
			addChild(DisplayObject(_subPanel));

			_subPanel.addChild(DisplayObject(_subPanelContent));
			_subPanel.addChild(DisplayObject(_subHeaderLabel));
			
			_subPanel.validate();
		}		
		
		private function createInputComments():void 
		{
			var inputCommentsLayoutData:AnchorLayoutData = new AnchorLayoutData(_contentPadding, _contentPadding, NaN, _contentPadding);
			inputCommentsLayoutData.topAnchorDisplayObject = _subPanel;
			
			_inputComments = new TextInput();
			_inputComments.styleNameList.add( GlobalSettings.INPUTCOMMENTS );
			_inputComments.layoutData = inputCommentsLayoutData;
			_inputComments.prompt = "Description";
			_inputComments.maxChars = 500;
			_inputComments.height = 160 * _manager.scale;
			_inputComments.textEditorProperties.textAlign = "center";
			_inputComments.promptProperties.textAlign = "center";
			
			this.addChild(_inputComments);
		}
		
		private function createInputTag():void 
		{
			var inputTagLayoutData:AnchorLayoutData = new AnchorLayoutData(_contentPadding, _contentPadding, NaN, _contentPadding);
			inputTagLayoutData.topAnchorDisplayObject = _inputComments;
			
			_inputTags = new TextInput();
			_inputTags.styleNameList.add( GlobalSettings.INPUTCOMMENTS );
			_inputTags.layoutData = inputTagLayoutData;
			_inputTags.prompt = "Tags (optional)";
			_inputTags.textEditorProperties.textAlign = "center";
			_inputTags.promptProperties.textAlign = "center";
			
			this.addChild(_inputTags);
		}		
		
		private function createNewProject():void
		{
			showAlert("Creating new project", "Updating", null);
			
			// Check if user added Comments
			if (_inputComments.text.length < MINCHARS) 
			{
				removeAlert();
				showAlert("Need at least " + MINCHARS + " characters entered", "Minimum Requirements", [ { label:"OK" } ]);
				_alert.addEventListener(Event.CLOSE, onAlertMessage);
				return;
			}
			
			// Create and Save Project Information

			var projectInfo:Object = 
			{
				description:_inputComments.text,
				tags:_inputTags.text
			}
			
			_manager.serverHandler.createNewProject(_manager.userData, projectInfo, onNewProjectComplete);
			
			// Get Project ID from Server DB
			
			function onNewProjectComplete(resultData:Object):void
			{
				LogUtil.log("SaveHome.createNewProject.onNewProjectComplete()");
				
				sendUserPhoto(resultData);
			}
			
			function onAlertMessage(e:Event):void 
			{
				removeAlert();
			}
		}
		
		
		
		private function sendUserPhoto(resultData:Object):void
		{
			// Save User Photo Images and send the Project ID associated
			
			_manager.serverHandler.uploadUserImageToServer(_manager.userData, resultData["imageName"], resultData, _manager.nativeCamPanel.tempBitmapData, onUploadComplete);
			
			function onUploadComplete():void
			{
				LogUtil.log("SaveHome.sendUserPhoto.onUploadComplete()");
				
				sendUserPhotoThumbnail(resultData);
			}			
		}
		
		private function sendUserPhotoThumbnail(resultData:Object):void
		{
			// Save User Photo Images and send the Project ID associated
			
			_manager.serverHandler.uploadUserImageToServer(_manager.userData, resultData["thumbName"], resultData, _manager.nativeCamPanel.tempThumbBitmapData, onUploadThumbComplete);
			
			function onUploadThumbComplete():void
			{
				LogUtil.log("SaveHome.saveUserPhoto.onUploadComplete()");
				
				removeAlert();
				
				dispatchEventWith(AppNavigator.CLOSEPANEL, false, { status:"onCreateProjectComplete" });
			}			
		}		
		
	}

}