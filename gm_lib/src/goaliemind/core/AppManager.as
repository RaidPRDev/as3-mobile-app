package goaliemind.core 
{
	import feathers.controls.Drawers;
	import feathers.controls.Panel;
	import feathers.core.PopUpManager;
	import feathers.display.TiledImage;
	import feathers.events.FeathersEventType;
	
	import goaliemind.components.camera.NativeCameraUI;
	import goaliemind.data.DataHandler;
	import goaliemind.data.ServerHandler;
	import goaliemind.data.UserData;
	import goaliemind.data.support.AccountType;
	import goaliemind.system.LogUtil;
	import goaliemind.system.NetworkTools;
	import goaliemind.view.createuser.CreateUserNavigator;
	import goaliemind.view.home.HomeNavigator;
	import goaliemind.view.home.overlays.AccountNavigator;
	import goaliemind.view.home.overlays.BanksNavigator;
	import goaliemind.view.home.overlays.ProfileNavigator;
	import goaliemind.view.home.overlays.SettingsNavigator;
	import goaliemind.view.home.panels.SideListMenu;
	
	import starling.animation.Transitions;
	import starling.core.Starling;
	import starling.display.DisplayObject;
	import starling.events.EnterFrameEvent;
	import starling.events.Event;
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 * 
	 * 	1) Check network connection
	 *  2) Check for saved User Data on device. If available no need to get username and password and continue steps, else no data, go to #5
	 *  3) Send user and pass to server, get authentication
	 *  4) Load Home Page
	 *  5) Load in Login Screen and pre-load Create User Screens 
	 * 
	 */
	public class AppManager
	{
		public static const INITIALIZING_STATE:String = "initializing-state";
		public static const CREATEUSER_STATE:String = "create-user-state";
		public static const APPLICATION_HOME_STATE:String = "application-home-state";
		
		private var _state:String;
		
		private var _app:AppCore;
		private var _dataHandler:DataHandler;
		private var _serverHandler:ServerHandler;
		private var _userData:UserData;
		private var _createUserNavigator:CreateUserNavigator;
		private var _homeNavigator:HomeNavigator;
		private var _nativeCamPanel:NativeCameraUI;
		
		private var _appDrawers:Drawers;
		private var _sideListMenu:SideListMenu;
		private var _panelShadow:Panel;
		private var _shadowTileTexture:TiledImage;
		
		private var _popUpDisplayObject:DisplayObject;
		
		public function get appCore():AppCore { return _app; }
		public function get nativeCamPanel():NativeCameraUI { return _nativeCamPanel; }
		public function get dataHandler():DataHandler { return _dataHandler; }
		public function get serverHandler():ServerHandler { return _serverHandler; }
		public function get userData():UserData { return _userData; }
		
		public function get scale():Number { return _app.theme.scale; }
		
		public static var _instance:AppManager;
		public static function GetInstance():AppManager { return _instance; }
		
		private function initializeNativeCameraUI():void
		{
			LogUtil.log("Manager.initializeNativeCameraUI()");
			
			_nativeCamPanel = new NativeCameraUI(scale);
			_nativeCamPanel.styleNameList.add(GlobalSettings.NOSCROLL_PANEL);
			_nativeCamPanel.initializeNativeExtension();
		}
		
		public function AppManager(app:AppCore) 
		{
			super();
			
			_instance = this;
			
			_state = INITIALIZING_STATE;
			
			_app = app;
			
			_dataHandler = new DataHandler();
			
			_serverHandler = new ServerHandler();
			
			initializeNativeCameraUI();
			
			NetworkTools.checkInternetConnection(netorkStatusComplete);
		}
		
		private function netorkStatusComplete(data:Object):void 
		{
			LogUtil.log("Manager.netorkStatusComplete() status: " + data["status"]);
			
			_app.isInternet = (data["status"] == NetworkTools.INTERNET_AVAILABLE) ? true : false;
			
			loadUserData();
		}
		
		private function loadUserData():void 
		{
			// Try to load user data file
			
			createNewUserData();
			
			_dataHandler.loadUserDataFile(_userData, onLoadUserDataComplete);
			
			function onLoadUserDataComplete(data:Object):void
			{
				LogUtil.log("Manager.DataHandler.onLoadUserDataComplete() status: " + data["status"]);
			
				switch (data["status"])
				{
					case DataHandler.DATA_LOADED_COMPLETE:
						// UserData found, attemp to auto sign in
						if (_app.isInternet && _userData.signedIn) startAutoSignIn(); else showLogin();
						break;
						
					case DataHandler.IO_ERROR:
						// No UserData file found, load Login Page.
						
						_userData = new UserData();
						
						showLogin();
						break;
				}
			}
		}
		
		public function saveUserData(onComplete:Function):void
		{
			LogUtil.log("Manager.saveUserData()");
			
			_userData.signedIn = true;
			
			_dataHandler.saveUserDataFile(_userData, onSaveUserData);
					
			function onSaveUserData(data:Object):void
			{
				if (data["status"] != undefined)
				{
					if (data["status"] == DataHandler.DATA_SAVED_COMPLETE)
					{
						onComplete();
					}
					else if (data["status"] == DataHandler.IO_ERROR)
					{
						LogUtil.log("Manager.saveUserData() data-ioerror");
					}
				}
			}
		}
		
		private function removeUserData(onComplete:Function):void 
		{
			// Try to load user data file
			
			_dataHandler.removeUserDataFile(onRemoveUserDataComplete);
			
			function onRemoveUserDataComplete(data:Object):void
			{
				LogUtil.log("Manager.DataHandler.onRemoveUserDataComplete() status: " + data["status"]);
			
				switch (data["status"])
				{
					case DataHandler.DATA_LOADED_COMPLETE:
					case DataHandler.IO_ERROR:

					default:
						onComplete();
				}
			}
		}		
		
		private function startAutoSignIn():void 
		{
			LogUtil.log("Manager.startAutoSignIn()");
			
			_serverHandler.signIn(_userData.username, _userData.password, _userData.email, onAutoSignInComplete);
			
			function onAutoSignInComplete(data:Object):void
			{
				if (data["ERROR"] != undefined) 
				{
					_app.removeAlert();
					// Show Message
					_app.showAlert(data["ERROR"][0], "Authentication Failed", [{ label: "" }, { label: "OK" }, { label: "" }]);
				}
				else if (data["OK"] != undefined)
				{
					// User Authenticated, we will save the Authenticated Parent ID and link it to this MindGoalie Personal Account.
					LogUtil.log("Manager.startAutoSignIn.onAutoSignInComplete.user_session: " + data["user_session"]);
					
					if (data["accountType"] != undefined) 
					{
						GlobalSettings.currentAccount = data["accountType"];
						createHome();
					}
					else 
					{
						// SHOW ALERT NO ACCOUNT TYPE FOUND
						_app.showAlert("No account type found.", "Account Error", ["OK"]);
					}
				}
			}
		}
		
		private function showLogin(isFadeIn:Boolean = false):void 
		{
			_createUserNavigator = new CreateUserNavigator();
			
			_createUserNavigator.alpha = (isFadeIn) ? 0 : 1;
			
			_app.addChild(_createUserNavigator);
			
			if (isFadeIn)
			{
				Starling.juggler.tween(_createUserNavigator, 0.85, 
				{
					transition: Transitions.LINEAR,
					alpha: 1
				});
			}
		}
		
		private function removeCreateUserAssets():void 
		{
			LogUtil.log("Manager.removeCreateUserAssets()");
			
			_app.removeChild(_createUserNavigator, true);
			// _app.removeCreateUserTheme();
		}		

		public function createHome():void
		{
			LogUtil.log("Manager.createHome()");
			
			// removeCreateUserAssets();
			
			_appDrawers = new Drawers();
			_app.addChild( _appDrawers );
			
			_appDrawers.openGesture = Drawers.OPEN_GESTURE_DRAG_CONTENT_EDGE;
			_appDrawers.openGestureEdgeSize = 0.5;
			
			_appDrawers.addEventListener(FeathersEventType.BEGIN_INTERACTION, onBeginDrawersDrag); 
			_appDrawers.addEventListener(FeathersEventType.END_INTERACTION, onEndDrawersDrag); 
			_appDrawers.addEventListener(Event.CLOSE, onCloseDrawers); 
			
			_homeNavigator = new HomeNavigator();
			_homeNavigator.alpha = 0;
			_homeNavigator.addEventListener("navigatorLoadComplete", onNavigatorLoadComplete);
			_homeNavigator.width = Starling.current.stage.stageWidth;
			_homeNavigator.height = Starling.current.stage.stageHeight;	
			
			_appDrawers.content = _homeNavigator;
			
			_sideListMenu = new SideListMenu();
			_sideListMenu.width = Starling.current.stage.stageWidth - (92 * _app.theme.scale);
			
			this._appDrawers.leftDrawer = _sideListMenu;
			
			_app.addEventListener(EnterFrameEvent.ENTER_FRAME, onEnterFrame);
		}
		
		public function updateHomeScreen():void
		{
			_homeNavigator.onShow();
		}

		public function showHomeScreen():void 
		{
			// _homeNavigator.unflatten();
			_homeNavigator.visible = true;
		}
		
		public function hideHomeScreen():void 
		{
			_homeNavigator.visible = false;
			// _homeNavigator.flatten();
		}
		
		private function removeHome():void
		{
			_appDrawers.removeEventListener(FeathersEventType.BEGIN_INTERACTION, onBeginDrawersDrag); 
			_appDrawers.removeEventListener(FeathersEventType.END_INTERACTION, onEndDrawersDrag); 
			_appDrawers.removeEventListener(Event.CLOSE, onCloseDrawers); 
			
			_app.removeChild(_appDrawers, true);
			
			_appDrawers = null;
			
			_homeNavigator.dispose();
			_sideListMenu.dispose();
			
			if (_panelShadow) _app.removeChild(_panelShadow, true);
			if (_shadowTileTexture) _shadowTileTexture.dispose();
		}
		
		private function onEnterFrame(e:EnterFrameEvent):void 
		{
			if (_panelShadow) _panelShadow.x = _homeNavigator.x - _panelShadow.width;
		}
		
		private function addShadowEdge():void
		{
			_app.addEventListener(EnterFrameEvent.ENTER_FRAME, onEnterFrame);
			
			if (!_shadowTileTexture) 
			{
				_shadowTileTexture = new TiledImage(_app.assets.applicationAssets.getTexture("list/sidemenu_shadow"));
				
				_panelShadow = new Panel();
				_panelShadow.backgroundSkin = _shadowTileTexture;
				_panelShadow.width = 54;	
				_panelShadow.height = _homeNavigator.height;
			}
			
			if (_panelShadow) 
			{
				_app.addChild(_panelShadow);
				_panelShadow.validate();
				_panelShadow.x = _panelShadow.x = _homeNavigator.x - _panelShadow.width;
				return;
			}
		}		
		
		private function removeShadowEdge():void
		{
			_app.removeEventListener(EnterFrameEvent.ENTER_FRAME, onEnterFrame);
			
			if (_panelShadow) _app.removeChild(_panelShadow);
		}			
		
		private function onCloseDrawers(e:Event):void 
		{
			_app.removeEventListener(EnterFrameEvent.ENTER_FRAME, onEnterFrame);
			
			removeShadowEdge();
		}
		
		private function onBeginDrawersDrag(e:Event):void 
		{
			if (!_appDrawers.isLeftDrawerOpen) addShadowEdge();
		}
		
		private function onEndDrawersDrag(e:Event):void 
		{
			if (!_appDrawers.isLeftDrawerOpen) removeShadowEdge();
		}
		
		private function onNavigatorLoadComplete(e:Event):void 
		{
			LogUtil.log("Manager.onNavigatorLoadComplete()");
			
			_homeNavigator.removeEventListener("navigatorLoadComplete", onNavigatorLoadComplete);
			
			Starling.juggler.tween(_homeNavigator, 0.5, 
			{
				transition: Transitions.LINEAR,
				onComplete:onNavigatorFadeInComplete,
				alpha: 1
			});
			
			function onNavigatorFadeInComplete():void
			{
				LogUtil.log("Manager.onNavigatorLoadComplete.onNavigatorFadeInComplete()");
				
				_homeNavigator.onShow();
			}
		}
		
		public function createNewUserData():void
		{
			_userData = new UserData();
		}
		
		public function onSideMenuSelection(eventName:String):void
		{
			_popUpDisplayObject = null;
			
			switch (eventName)
			{
				case "accountScreen":
					_popUpDisplayObject = new AccountNavigator();
					break;
					
				case "profilesScreen":
					_popUpDisplayObject = new ProfileNavigator();
					break;
					
				case "banksScreen":
					_popUpDisplayObject = new BanksNavigator();
					break;
					
				case "settingsScreen":
					_popUpDisplayObject = new SettingsNavigator();
					break;
			}
			
			_popUpDisplayObject.width = Starling.current.stage.stageWidth;
			_popUpDisplayObject.height = Starling.current.stage.stageHeight;
			
			// PopUpManager.addPopUp(_popUpDisplayObject, false);
			
			_app.addChild(_popUpDisplayObject);
		}
		
		public function logOut(onComplete:Function = null):void
		{
			LogUtil.log("Manager.logOut()");
			
			_app.showAlert("Please wait...", "Signing Off", null);
			
			_serverHandler.logOut(_userData, onLogOutComplete);
			
			function onLogOutComplete(data:Object):void
			{
				if (data["ERROR"] != undefined) 
				{
					_app.removeAlert();
					
					// Show Message
					_app.showAlert(data["ERROR"][0], "LogOut Failed", [{ label: "" }, { label: "OK" }, { label: "" }]);
				}
				else if (data["OK"] != undefined)
				{
					LogUtil.log("Manager.logOut.onLogOutComplete.user_session: " + data["user_session"]);
					
					if (GlobalSettings.parentUserData != null) onRemoveUserLogOutComplete();
					else removeUserData(onRemoveUserLogOutComplete);
				}
			}
			
			function onRemoveUserLogOutComplete():void
			{
				LogUtil.log("Manager.logOut.onRemoveUserLogOutComplete()");
				
				GlobalSettings.currentAccount = -1;
					
				_userData = new UserData();
				
				if (PopUpManager.isPopUp(_popUpDisplayObject)) PopUpManager.removePopUp(_popUpDisplayObject);
				
				removeHome();
				
				Starling.current.juggler.delayCall(appCore.addCreateUserTheme, 0.5, onLogoutCreateUserThemeLoadedComplete);
			}
		}
		
		private function onLogoutCreateUserThemeLoadedComplete():void
		{
			LogUtil.log("Manager.logOut.onLogoutCreateUserThemeLoadedComplete()");
			
			_app.removeAlert();
			
			Starling.current.juggler.delayCall(showLogin, 0.25, true);
		}
		
		public function setParentUserData():void
		{
			LogUtil.log("Manager.setParentUserData()");
			
			GlobalSettings.parentUserData = new UserData();
			
			GlobalSettings.parentUserData.userid = userData.userid;
			GlobalSettings.parentUserData.username = userData.username;
			GlobalSettings.parentUserData.password = userData.password;
			GlobalSettings.parentUserData.email = userData.email;
			GlobalSettings.parentUserData.accountType = userData.accountType;
			GlobalSettings.parentUserData.parentUserid = userData.parentUserid;
			GlobalSettings.parentUserData.gender = userData.gender;
			GlobalSettings.parentUserData.birthdate = userData.birthdate;
			GlobalSettings.parentUserData.isAuthorized = userData.isAuthorized;
			GlobalSettings.parentUserData.authorizeKey = userData.authorizeKey;
			GlobalSettings.parentUserData.registerDate = userData.registerDate;
			GlobalSettings.parentUserData.signedInDate = userData.signedInDate;
			GlobalSettings.parentUserData.signedIn = userData.signedIn;
			GlobalSettings.parentUserData.login_token = userData.login_token;
		}
		
		public function revertParentUserData():void
		{
			LogUtil.log("Manager.revertParentUserData()");
			
			_userData.userid  = GlobalSettings.parentUserData.userid;
			_userData.username = GlobalSettings.parentUserData.username;
			_userData.password = GlobalSettings.parentUserData.password;
			_userData.email = GlobalSettings.parentUserData.email;
			_userData.accountType = GlobalSettings.parentUserData.accountType;
			_userData.parentUserid = GlobalSettings.parentUserData.parentUserid;
			_userData.gender = GlobalSettings.parentUserData.gender;
			_userData.birthdate = GlobalSettings.parentUserData.birthdate;
			_userData.isAuthorized = GlobalSettings.parentUserData.isAuthorized;
			_userData.authorizeKey = GlobalSettings.parentUserData.authorizeKey;
			_userData.registerDate = GlobalSettings.parentUserData.registerDate;
			_userData.signedInDate = GlobalSettings.parentUserData.signedInDate;
			_userData.signedIn = GlobalSettings.parentUserData.signedIn;
			_userData.login_token = GlobalSettings.parentUserData.login_token;
			
			this.clearParentUserData();
			
			this.startAutoSignIn();
		}
		
		public function clearParentUserData():void
		{
			LogUtil.log("Manager.clearParentUserData()");
			
			GlobalSettings.parentUserData = null;
		}
		
		// This is used when adding a new MindGoalie from a GoalieMind account in Manager Profiles
		public function autoPopulateUserDataForRegistration():void
		{
			LogUtil.log("Manager.autoPopulateUserDataForRegistration()");
			
			_userData.accountType = AccountType.PERSONAL_MINDGOALIE;
			_userData.parentUserid = Number(GlobalSettings.parentUserData.userid);
		}
		
	}
}