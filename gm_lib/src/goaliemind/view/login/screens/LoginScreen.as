package goaliemind.view.login.screens 
{
	import flash.text.ReturnKeyLabel;
	import flash.text.SoftKeyboardType;
	
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.ImageLoader;
	import feathers.controls.LayoutGroup;
	import feathers.controls.TextInput;
	import feathers.controls.ToggleButton;
	import feathers.events.FeathersEventType;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;
	import feathers.layout.VerticalLayout;
	
	import goaliemind.system.LogUtil;
	import goaliemind.ui.base.NoHeaderPanelScreen;
	
	import starling.display.DisplayObject;
	import starling.display.Image;
	import starling.events.Event;
	import starling.textures.Texture;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class LoginScreen extends NoHeaderPanelScreen
	{
		private var _usernameInput:TextInput;
		private var _passwordInput:TextInput;
		private var _showPasswordBtn:ToggleButton;
		private var _forgotPasswordBtn:Button;
		private var _signInButton:Button;
		private var _facebookButton:Button;
		private var _registerButton:Button;
		
		public var status:Boolean;
		
		public function LoginScreen() 
		{
			super();
		}
		
		override public function dispose():void
		{
			_usernameInput.removeEventListener(FeathersEventType.ENTER, onUsernameEnter);
			_passwordInput.removeEventListener(FeathersEventType.ENTER, onPasswordEnter);
			_registerButton.removeEventListener(Event.TRIGGERED, onButton);
			_facebookButton.removeEventListener(Event.TRIGGERED, onButton);
			_forgotPasswordBtn.removeEventListener(Event.TRIGGERED, onButton);
			
			removeChild(DisplayObject(_usernameInput));
			removeChild(DisplayObject(_passwordInput));
			removeChild(DisplayObject(_signInButton));	
			removeChild(DisplayObject(_forgotPasswordBtn));	
			
			removeChild(DisplayObject(_facebookButton));
			removeChild(DisplayObject(_registerButton));	
			
			_showPasswordBtn.removeEventListener(Event.CHANGE, onShowPassword);
			removeChild(DisplayObject(_showPasswordBtn));	
			
			super.dispose();
		}		
	
		override protected function initialize():void
		{
			super.initialize();

			this.title = "Test";
			
			this.layout = new AnchorLayout();

			var userLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0, 0, NaN);
			
			this._usernameInput = new TextInput();
			this._usernameInput.prompt = "Email or Username";
			this._usernameInput.defaultIcon = loadIcon("icons/username_icon");
			this._usernameInput.layoutData = userLayoutData;
			this._usernameInput.restrict = GlobalSettings.FIELD_RESTRICT;
			this._usernameInput.textEditorProperties.softKeyboardType = SoftKeyboardType.EMAIL;
			this._usernameInput.textEditorProperties.returnKeyLabel = ReturnKeyLabel.DONE;
			// this._usernameInput.text = "fania@raidpr.com";
			
			var passLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0, 0, NaN);
			passLayoutData.topAnchorDisplayObject = this._usernameInput;
			
			this._passwordInput = new TextInput();
			this._passwordInput.prompt = "Password";
			this._passwordInput.styleNameList.add(GlobalSettings.INPUTPASSWORD);
			this._passwordInput.defaultIcon = loadIcon("icons/password_icon");
			this._passwordInput.layoutData = passLayoutData;
			this._passwordInput.restrict = GlobalSettings.FIELD_RESTRICT;
			this._passwordInput.displayAsPassword = true;
			this._passwordInput.maxChars = 28;
			this._passwordInput.textEditorProperties.returnKeyLabel = ReturnKeyLabel.DONE;
			
			var showPassLayoutData:AnchorLayoutData = new AnchorLayoutData(-66 * _manager.scale, NaN, NaN, -60 * _manager.scale, NaN, NaN);
			showPassLayoutData.topAnchorDisplayObject = this._passwordInput;
			showPassLayoutData.leftAnchorDisplayObject = this._passwordInput;
			this._showPasswordBtn = new ToggleButton();
			this._showPasswordBtn.layoutData = showPassLayoutData;
			this._showPasswordBtn.styleNameList.add(GlobalSettings.SHOWPASSWORDBUTTON);
			this._showPasswordBtn.addEventListener(Event.CHANGE, onShowPassword);
			
			var signInLayoutData:AnchorLayoutData = new AnchorLayoutData(_manager.appCore.theme.regularPaddingSize, NaN, NaN, NaN, 0, NaN);
			signInLayoutData.topAnchorDisplayObject = _passwordInput;
			signInLayoutData.percentWidth = GlobalSettings.BUTTON_LOGIN_PERCENT_WIDTH;
			
			this._signInButton = new Button();
			this._signInButton.styleNameList.add(GlobalSettings.BUTTON_LOGIN);
			this._signInButton.label = "Sign In";
			this._signInButton.layoutData = signInLayoutData;
			this._signInButton.defaultIcon = loadIcon("icons/signin_icon");
			this._signInButton.name = "signin";
			this._signInButton.addEventListener(Event.TRIGGERED, onButton);
			
			var forgotPassLayoutData:AnchorLayoutData = new AnchorLayoutData(8 * _manager.scale, NaN, NaN, NaN, 0, NaN);
			forgotPassLayoutData.topAnchorDisplayObject = this._signInButton;
			this._forgotPasswordBtn = new Button();
			this._forgotPasswordBtn.name = "forgotpassword";
			this._forgotPasswordBtn.label = "Forgot your password?";
			this._forgotPasswordBtn.layoutData = forgotPassLayoutData;
			this._forgotPasswordBtn.styleNameList.add(GlobalSettings.FORGOTPASSWORDBUTTON);
			
			this.addChild(DisplayObject(this._usernameInput));
			this.addChild(DisplayObject(this._passwordInput));
			this.addChild(DisplayObject(this._signInButton));
			this.addChild(DisplayObject(this._showPasswordBtn));
			
			this.addChild(DisplayObject(this._forgotPasswordBtn));
			this._forgotPasswordBtn.addEventListener(Event.TRIGGERED, onButton);
			
			this._usernameInput.addEventListener(FeathersEventType.ENTER, onUsernameEnter);
			this._passwordInput.addEventListener(FeathersEventType.ENTER, onPasswordEnter);
			
			this.headerFactory = this.customHeaderFactory;
			
			this.createFooter();
		}
		
		private function onShowPassword(e:Event):void
		{
			this._passwordInput.displayAsPassword = !this._showPasswordBtn.isSelected;
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
				this.signIn();
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
			_facebookButton.name = "facebook";
			this.addChild(DisplayObject(_facebookButton));
			
			var registerLayoutData:AnchorLayoutData = new AnchorLayoutData(NaN, NaN, _manager.appCore.theme.mediumPaddingSize, NaN, 0, NaN);
			registerLayoutData.horizontalCenter = 0;
			registerLayoutData.bottomAnchorDisplayObject = _facebookButton;
			registerLayoutData.percentWidth = GlobalSettings.BUTTON_LOGIN_PERCENT_WIDTH;
			
			_registerButton = new Button();
			_registerButton.styleNameList.add(GlobalSettings.BUTTON_LOGIN);
			_registerButton.label = "Register";
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
				_manager.createNewUserData(); 
				this.dispatchEventWith(btn.name);
			}
			else if (btn.name == "signin") 
			{
				signIn(); 
			}
			else if (btn.name == "forgotpassword")
			{
				this.dispatchEventWith(btn.name);
			}
		}
		
		private function customHeaderFactory():Header
		{
			var header:Header = new Header();

			var logoTexture:Texture = _manager.appCore.assets.applicationAssets.getTexture("logo_medium");
			var logo:Image = new Image(logoTexture);
			logo.scaleX = logo.scaleY = _manager.appCore.theme.scale;
			
			var logoSubTexture:Texture = _manager.appCore.assets.applicationAssets.getTexture("logo_message");
			var logoMessage:Image = new Image(logoSubTexture);
			logoMessage.scaleX = logoMessage.scaleY = _manager.appCore.theme.scale;
			
			var logoGroup:LayoutGroup = new LayoutGroup();
			var layout:VerticalLayout = new VerticalLayout();
			layout.horizontalAlign = VerticalLayout.HORIZONTAL_ALIGN_CENTER;
			layout.gap = 26 * _manager.appCore.theme.scale;
			layout.paddingTop = 10 * _manager.appCore.theme.scale;
			layout.paddingBottom = 18 * _manager.appCore.theme.scale;

			logoGroup.layout = layout;
			
			logoGroup.addChild(DisplayObject(logo));
			logoGroup.addChild(DisplayObject(logoMessage));
			
			header.styleNameList.add(GlobalSettings.LOGIN_HEADER);
			
			header.centerItems = new <DisplayObject>
			[
				logoGroup
			];

			return header;
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
				
			}

			//never forget to call super.draw()
			super.draw();
		}
		
		////////////////////////////////////////////////////////////////
		// SIGN-IN
		//////////////////////////////////////////////////////////////
		
		private function signIn():void 
		{
			showAlert("Signing into account...", "Authenticating", null);
			
			_usernameInput.clearFocus();
			_passwordInput.clearFocus();
			
			if (this._usernameInput.text == "" || this._passwordInput.text == "")
			{
				removeAlert();
				// Show Message
				showAlert("You must specify both a username and a password", "Missing Fields", [{ label: "" }, { label: "OK" }, { label: "" }]);
			}
			else
			{
				_manager.serverHandler.signIn(this._usernameInput.text, this._passwordInput.text, this._usernameInput.text, onCheckComplete);
			}
			
			function onCheckComplete(data:Object):void
			{
				trace("LoginScreen.signIn.onCheckComplete() " + data);
				
				if (data["ERROR"] != undefined) 
				{
					removeAlert();
					// Show Message
					showAlert(data["ERROR"][0], "Authentication Failed", [{ label: "" }, { label: "OK" }, { label: "" }]);
				}
				else if (data["OK"] != undefined)
				{
					// User Authenticated, we will save the Authenticated Parent ID and link it to this MindGoalie Personal Account.
					LogUtil.log("LoginScreen.signIn.user_session: " + data["user_session"]);
					
					/*$_SESSION['user_session'] = $userRow['id'];
					$_SESSION['username'] = $userRow['username'];
					$_SESSION['useremail'] = $userRow['useremail'];
					$_SESSION['accountType'] = $userRow['accountType'];
					$_SESSION['parentid'] = $userRow['parentid'];
					$_SESSION['gender'] = $userRow['gender'];
					$_SESSION['birthDate'] = $userRow['birthDate'];
					$_SESSION['isAuthorized'] = $userRow['isAuthorized'];
					$_SESSION['authorizeKey'] = $userRow['authorizeKey'];
					$_SESSION['registerDate'] = $userRow['registerDate'];
					$_SESSION['signedInDate'] = $userRow['signedInDate'];
					$_SESSION['loginToken'] = $userRow['loginToken'];*/					
					
					_manager.userData.userid = data["user_session"];
					_manager.userData.username = data["username"];
					_manager.userData.password = _passwordInput.text;
					_manager.userData.email = data["useremail"];
					_manager.userData.accountType = data["accountType"];
					_manager.userData.parentUserid = data["parentid"];
					_manager.userData.gender = data["gender"];
					_manager.userData.birthdate = data["birthDate"];
					_manager.userData.isAuthorized = data["isAuthorized"];
					_manager.userData.authorizeKey = data["authorizeKey"];
					_manager.userData.registerDate = data["registerDate"];
					_manager.userData.signedInDate = data["signedInDate"];
					_manager.userData.login_token = data["loginToken"];
					
					GlobalSettings.currentAccount = data["accountType"];
					
					_manager.userData.signedIn = true;
					
					_manager.dataHandler.saveUserDataFile(_manager.userData, onSaveUserData);
					
					function onSaveUserData():void
					{
						removeAlert();
						showAlert("We have successfully authenticated the account.", "Success!", [ { label: "" }, { label: "OK" }, { label: "" } ]);
						_alert.addEventListener(Event.CLOSE, onAlertSuccessClose);
					}
				}
			}
		}		
		
		private function onAlertSuccessClose(e:Event):void 
		{
			_alert.removeEventListener(Event.CLOSE, onAlertSuccessClose);
			
			this.dispatchEventWith("signin");
			// dispatchEventWith(CreateUserPanelScreen.HEADER_NEXT, false, { usernameTxt:_usernameInput.text, passwordTxt:_passwordInput.text });
		}		
		
	}

}