package goaliemind.view.createuser.screens 
{
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.ImageLoader;
	import feathers.controls.Label;
	import feathers.controls.Panel;
	import feathers.controls.TextInput;
	import feathers.events.FeathersEventType;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;
	import feathers.layout.HorizontalLayout;
	
	import starling.display.DisplayObject;
	import starling.display.Quad;
	import starling.events.Event;

	import goaliemind.ui.base.CreateUserPanelScreen;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class AuthorizeAccessScreen extends CreateUserPanelScreen
	{
		private var _subPanel:Panel;
		private var _subPanelContent:Button;
		private var _subHeaderLabel:Label;
		
		private var _usernameInput:TextInput;
		private var _passwordInput:TextInput;
		private var _signInButton:Button;
		
		private var _facebookButton:Button;
		private var _registerButton:Button;
		
		public var usernameTxt:String = ""
		public var passwordTxt:String = "";
		
		public function AuthorizeAccessScreen() 
		{
			super();
		}
		
		override public function dispose():void
		{
			_subPanel.removeChild(DisplayObject(_subPanelContent));
			_subPanel.removeChild(DisplayObject(_subHeaderLabel));
			removeChild(DisplayObject(_subPanel));
			
			this._usernameInput.removeEventListener(FeathersEventType.ENTER, onUsernameEnter);
			this._passwordInput.removeEventListener(FeathersEventType.ENTER, onPasswordEnter);
			this._usernameInput.removeEventListener(Event.CHANGE, onInputChange);
			this._passwordInput.removeEventListener(Event.CHANGE, onInputChange);
			
			removeChild(DisplayObject(_usernameInput));
			removeChild(DisplayObject(_passwordInput));
			removeChild(DisplayObject(_signInButton));	
			
			removeChild(DisplayObject(_facebookButton));
			removeChild(DisplayObject(_registerButton));		
			
			// never forget to dispose textures!
			super.dispose();
		}	
		
		override protected function initialize():void
		{
			super.initialize();

			this.title = "Authorize Access";
			
			this.layout = new AnchorLayout();

			this.headerFactory = this.customHeaderFactory;
			
			this.createSubHeader();
			this.createLogin();
			this.createFooter();
		}
		
		private function createSubHeader():void
		{
			var panelLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0, 0, NaN);
			
			var verticalLayout:HorizontalLayout = new HorizontalLayout();
			verticalLayout.horizontalAlign = HorizontalLayout.HORIZONTAL_ALIGN_CENTER;
			verticalLayout.verticalAlign = HorizontalLayout.VERTICAL_ALIGN_MIDDLE;
			verticalLayout.gap = _manager.appCore.theme.regularPaddingSize;
			
			_subPanel = new Panel();
			_subPanel.backgroundSkin = new Quad(20, GlobalSettings.SUBHEADER_HEIGHT * _manager.appCore.theme.scale, GlobalSettings.BLUE_TEXT_COLOR);
			_subPanel.layoutData = panelLayoutData;
			_subPanel.layout = verticalLayout;
			
			_subPanelContent = new Button();
			_subPanelContent.defaultIcon = this.loadImageFactory(_manager.appCore.theme.createUserTheme.largeUsersIconTexture);
			_subPanelContent.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_subPanelContent.styleNameList.add(GlobalSettings.BUTTON_SUBHEADER);
			
			_subHeaderLabel = new Label();
			_subHeaderLabel.width = 335 * _manager.appCore.theme.scale;
			_subHeaderLabel.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_subHeaderLabel.styleNameList.add(GlobalSettings.LABEL_RED_DETAILS);
			_subHeaderLabel.textRendererProperties.wordWrap = true;
			_subHeaderLabel.text = "A personal account can only be created by an adult, parent or guardian.  Please enter your GoalieMind login information for authorization.";
			
			addChild(DisplayObject(_subPanel));

			_subPanel.addChild(DisplayObject(_subPanelContent));
			_subPanel.addChild(DisplayObject(_subHeaderLabel));
			
			_subPanel.validate();
		}
		
		private function createLogin():void
		{
			var userLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0, 0, NaN);
			userLayoutData.topAnchorDisplayObject = _subPanel;
			
			this._usernameInput = new TextInput();
			this._usernameInput.prompt = "Email or Username";
			this._usernameInput.defaultIcon = loadIcon("icons/username_icon");
			this._usernameInput.layoutData = userLayoutData;
			this._usernameInput.text = usernameTxt;
			this._usernameInput.restrict = GlobalSettings.FIELD_RESTRICT;
			
			var passLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0, 0, NaN);
			passLayoutData.topAnchorDisplayObject = this._usernameInput;
			
			this._passwordInput = new TextInput();
			this._passwordInput.prompt = "Password";
			this._passwordInput.defaultIcon = loadIcon("icons/password_icon");
			this._passwordInput.layoutData = passLayoutData;
			this._passwordInput.restrict = GlobalSettings.FIELD_RESTRICT;
			this._passwordInput.text = passwordTxt;
			
			var signInLayoutData:AnchorLayoutData = new AnchorLayoutData(_manager.appCore.theme.regularPaddingSize, NaN, NaN, NaN, 0, NaN);
			signInLayoutData.topAnchorDisplayObject = _passwordInput;
			signInLayoutData.percentWidth = GlobalSettings.BUTTON_LOGIN_PERCENT_WIDTH;
			
			this._signInButton = new Button();
			this._signInButton.styleNameList.add(GlobalSettings.BUTTON_LOGIN);
			this._signInButton.label = "Sign In";
			this._signInButton.layoutData = signInLayoutData;
			this._signInButton.defaultIcon = loadIcon("icons/signin_icon");
			
			this._usernameInput.addEventListener(FeathersEventType.ENTER, onUsernameEnter);
			this._passwordInput.addEventListener(FeathersEventType.ENTER, onPasswordEnter);
			
			this._usernameInput.addEventListener(Event.CHANGE, onInputChange);
			this._passwordInput.addEventListener(Event.CHANGE, onInputChange);
			
			this.addChild(DisplayObject(this._usernameInput));
			this.addChild(DisplayObject(this._passwordInput));
			this.addChild(DisplayObject(this._signInButton));			
		}
		
		private function onInputChange(e:Event):void 
		{
			_nextHeaderButton.isEnabled = (this._usernameInput.text.length > 0 && this._passwordInput.text.length > 5);
		}		
		
		private function onUsernameEnter(e:Event):void 
		{
			if (this._usernameInput.text.length > 0)
			{
				this._passwordInput.setFocus();
			}
		}

		private function onPasswordEnter(e:Event):void 
		{
			if (this._usernameInput.text.length > 0 && this._passwordInput.text.length > 5)
			{
				this.checkAuthorization();
			}
		}
		
		private function createFooter():void 
		{
			var fbLayoutData:AnchorLayoutData = new AnchorLayoutData(NaN, NaN, 0, NaN, 0, NaN);
			fbLayoutData.percentWidth = GlobalSettings.BUTTON_LOGIN_PERCENT_WIDTH;

			_facebookButton = new Button();
			_facebookButton.styleNameList.add(GlobalSettings.BUTTON_FACEBOOKLOGIN);
			_facebookButton.layoutData = fbLayoutData;
			_facebookButton.label = "   Facebook";
			
			var registerLayoutData:AnchorLayoutData = new AnchorLayoutData(NaN, NaN, _manager.appCore.theme.mediumPaddingSize, NaN, 0, NaN);
			registerLayoutData.horizontalCenter = 0;
			registerLayoutData.bottomAnchorDisplayObject = _facebookButton;
			registerLayoutData.percentWidth = GlobalSettings.BUTTON_LOGIN_PERCENT_WIDTH;
			
			_registerButton = new Button();
			_registerButton.styleNameList.add(GlobalSettings.BUTTON_LOGIN);
			_registerButton.label = "Register";
			_registerButton.layoutData = registerLayoutData;
			_registerButton.defaultIcon = loadIcon("icons/register_icon");
			
			this.addChild(DisplayObject(_facebookButton));
			this.addChild(DisplayObject(_registerButton));			
		}
		
		private function customHeaderFactory():Header
		{
			var header:Header = new Header();

			header.styleNameList.add(GlobalSettings.CREATEUSER_HEADER);
			
			_backHeaderButton = new Button();
			_backHeaderButton.styleNameList.add(GlobalSettings.BUTTON_GREY_BACK_HEADER);
			_backHeaderButton.name = HEADER_BACK;
			_backHeaderButton.addEventListener(Event.TRIGGERED, headerButton_triggeredHandler); 

			_nextHeaderButton = new Button();
			_nextHeaderButton.styleNameList.add(GlobalSettings.BUTTON_GREY_NEXT_HEADER);
			_nextHeaderButton.name = HEADER_NEXT;
			_nextHeaderButton.addEventListener(Event.TRIGGERED, headerButton_triggeredHandler); 
			_nextHeaderButton.isEnabled = (usernameTxt.length > 0 && passwordTxt.length > 5);
			
			header.leftItems = new <DisplayObject> [ _backHeaderButton ];
			header.rightItems = new <DisplayObject> [ _nextHeaderButton ];
			
			return header;
		}
		
		override protected function headerButton_triggeredHandler(event:Event):void
		{
			var button:Button = Button( event.currentTarget );
			
			if (button.name == HEADER_BACK)
			{
				this.dispatchEventWith(CreateUserPanelScreen.HEADER_BACK);
			}
			else if (button.name == HEADER_NEXT)
			{
				trace("Next Button pressed!");
				
				this.checkAuthorization();
			}
		}	
		
		private function checkAuthorization():void 
		{
			showAlert("Checking account...", "Authenticating", null);
			
			_usernameInput.clearFocus();
			_passwordInput.clearFocus();
			
			_manager.serverHandler.checkAuthorization(this._usernameInput.text, this._passwordInput.text, this._usernameInput.text, onCheckComplete);
			
			function onCheckComplete(data:Object):void
			{
				trace("AuthorizeAccessScreen.checkUsername.onCheckComplete() " + data);
				
				if (data["ERROR"] != undefined) 
				{
					removeAlert();
					// Show Message
					showAlert(data["ERROR"][0], "Authentication Failed", [{ label: "" }, { label: "OK" }, { label: "" }]);
				}
				else if (data["OK"] != undefined)
				{
					// User Authenticated, we will save the Authenticated Parent ID and link it to this MindGoalie Personal Account.
					_manager.userData.parentUserid = parseInt(data["PARENTID"]);
					
					removeAlert();
					showAlert("We have successfully authenticated the account.", "Success!", [ { label: "" }, { label: "OK" }, { label: "" } ]);
					_alert.addEventListener(Event.CLOSE, onAlertSuccessClose);
				}
				
				_nextHeaderButton.isEnabled = true;
			}
		}		
		
		private function onAlertSuccessClose(e:Event):void 
		{
			_alert.removeEventListener(Event.CLOSE, onAlertSuccessClose);
			
			dispatchEventWith(CreateUserPanelScreen.HEADER_NEXT, false, { usernameTxt:_usernameInput.text, passwordTxt:_passwordInput.text });
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
		
		override protected function draw():void
		{
			var layoutInvalid:Boolean = this.isInvalid(INVALIDATION_FLAG_LAYOUT);

			if(layoutInvalid)
			{
				// Reset Padding for this screen only
				this.paddingLeft = _manager.appCore.theme.regularPaddingSize;
				this.paddingRight = _manager.appCore.theme.regularPaddingSize;
				this.paddingTop = 0;
				this.paddingBottom = _manager.appCore.theme.regularPaddingSize;
			}

			// never forget to call super.draw()
			super.draw();
		}
		
	}

}