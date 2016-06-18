package goaliemind.view.home.overlays.profile 
{
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.ImageLoader;
	import feathers.controls.Label;
	import feathers.controls.Panel;
	import feathers.controls.Scroller;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;

	import starling.display.DisplayObject;
	import starling.events.Event;
	
	import goaliemind.ui.base.HomePanelScreen;
	import goaliemind.system.LogUtil;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class ProfileAddAccount extends HomePanelScreen
	{
		private var _background:Panel;
		private var _description:Label;
		private var _registerButton:Button;
		
		public function ProfileAddAccount() 
		{
			super();
		}
		
		override protected function initialize():void 
		{
			super.initialize();
			
			this.layout = new AnchorLayout();
			
			this.title = "Add Profile";
			
			this.headerFactory = this.customHeaderFactory;
			
			createPanelBackground();
			createDescription();
			createRegisterButton();
		}
		
		private function customHeaderFactory():Header
		{
			var header:Header = new Header();

			header.styleNameList.add(GlobalSettings.HOME_MAINHEADER);
			
			var backButton:Button = new Button();
			backButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
			backButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("header/back_header_icon", _manager.appCore.theme.scale);
			
			header.leftItems = new <DisplayObject> [ backButton ];
			
			backButton.addEventListener(Event.TRIGGERED, onBackButton);
			
			return header;
		}		
		
		private function onBackButton(e:Event):void 
		{
			LogUtil.log("ProfileAddAccount.onBackButton()");
			
			this.dispatchEventWith("onBack");
		}			
		
		private function createPanelBackground():void
		{
			var bgLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, 0, 0);
			
			_background = new Panel();
			_background.styleNameList.add(GlobalSettings.OVERLAYPLAINPANEL);
			_background.horizontalScrollPolicy = _background.verticalScrollPolicy = Scroller.SCROLL_POLICY_OFF;
			_background.touchable = false;
			_background.layoutData = bgLayoutData;
			
			this.addChild(_background);
		}
		
		private function createDescription():void
		{
			var descLayoutData:AnchorLayoutData = new AnchorLayoutData(20, 20, NaN, 20);
			
			_description = new Label();
			_description.styleNameList.add(GlobalSettings.LABEL_22SIZE_DARKGREY);
			
			var textCon:String = "A GoalieMind account is considered a master account. This account will ";
			textCon += "allow you to create MindGoalie sub-accounts, that will help you monitor, limit ";
			textCon += "and control MindGoalie users.\n\n";
			textCon += "Please note that you will be logged out of your main account and redirected ";
			textCon += "to the registration wizard.  Please tap on Register to continue.";
			
			_description.textRendererProperties.wordWrap = true;
			_description.textRendererProperties.textAlign = "left";
			_description.textRendererProperties.leading = _manager.appCore.theme.fontLeading * _manager.appCore.theme.scale;
			_description.layoutData = descLayoutData;
			_description.text = textCon;
			
			this.addChild(_description);
		}
		
		private function createRegisterButton():void
		{
			var registerLayoutData:AnchorLayoutData = new AnchorLayoutData(40 * _manager.appCore.theme.scale, NaN, NaN, NaN, 0, NaN);
			registerLayoutData.horizontalCenter = 0;
			registerLayoutData.topAnchorDisplayObject = _description;
			registerLayoutData.percentWidth = GlobalSettings.BUTTON_LOGIN_PERCENT_WIDTH;
			
			_registerButton = new Button();
			_registerButton.styleNameList.add(GlobalSettings.BUTTON_LOGIN);
			_registerButton.label = "Register MindGoalie";
			_registerButton.layoutData = registerLayoutData;
			_registerButton.defaultIcon = loadIcon("icons/register_icon");
			_registerButton.name = "register";
			this.addChild(DisplayObject(_registerButton));
			
			_registerButton.addEventListener(Event.TRIGGERED, onButton);			
		}

		private function onButton(e:Event):void 
		{
			var btn:Button = e.target as Button;
			
			if (!btn) return;

			if (btn.name == "register") 
			{
				_manager.setParentUserData(); 
				_manager.logOut();
			}
		}		
		
		private function loadIcon(assetName:String):ImageLoader
		{
			var icon:ImageLoader = new ImageLoader();
			icon.source = _manager.appCore.assets.applicationAssets.getTexture(assetName);
			//the icon will be blurry if it's not on a whole pixel. ImageLoader
			//can snap to pixels to fix that issue.
			icon.snapToPixels = true;
			icon.textureScale = _manager.appCore.theme.scale;
			
			return icon;
		}		
	}

}