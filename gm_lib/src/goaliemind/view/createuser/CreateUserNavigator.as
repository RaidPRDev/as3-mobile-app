package goaliemind.view.createuser 
{
	import feathers.controls.StackScreenNavigator;
	import feathers.controls.StackScreenNavigatorItem;
	import feathers.motion.Flip;
	import feathers.motion.Slide;
	
	import goaliemind.core.AppNavigator;
	import goaliemind.system.LogUtil;
	import goaliemind.ui.base.CreateUserPanelScreen;
	import goaliemind.view.createuser.screens.AuthorizeAccessScreen;
	import goaliemind.view.createuser.screens.CreateAvatar;
	import goaliemind.view.createuser.screens.EnterEmailScreen;
	import goaliemind.view.createuser.screens.EnterPasswordScreen;
	import goaliemind.view.createuser.screens.EnterUsernameScreen;
	import goaliemind.view.createuser.screens.ForgotPasswordScreen;
	import goaliemind.view.createuser.screens.SelectGenderScreen;
	import goaliemind.view.createuser.screens.SelectGoalieMindScreen;
	import goaliemind.view.createuser.screens.SelectMindGoalieScreen;
	import goaliemind.view.createuser.screens.TermsScreen;
	import goaliemind.view.createuser.screens.terms.PrivacyPolicyScreen;
	import goaliemind.view.createuser.screens.terms.TermsOfUseScreen;
	import goaliemind.view.login.screens.LoginScreen;
	
	import starling.events.Event;
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class CreateUserNavigator extends AppNavigator
	{
		private static const LOGIN:String = "loginScreen";
		private static const FORGOT_PASSWORD:String = "forgotPasswordScreen";
		private static const SELECT_GOALIE:String = "selectGoalieAccountScreen";
		private static const MINDGOALIE:String = "selectMindGoalieScreen";
		private static const GENDER:String = "genderScreen";
		private static const AUTHORIZE:String = "authorizeAccessScreen";
		private static const EMAIL:String = "emailScreen";
		private static const USERNAME:String = "usernameScreen";
		private static const PASSWORD:String = "passwordScreen";
		private static const AVATAR:String = "avatarScreen";
		private static const TERMS:String = "termsScreen";
		private static const TERMSOFUSE:String = "termsOfUseScreen";
		private static const PRIVACY:String = "privacyScreen";
		
		public function CreateUserNavigator() 
		{
			super();
		}
		
		override protected function initialize():void 
		{
			super.initialize();
			
			_currentScreen = _previousScreen = LOGIN;
			_currentScreenIndex = _previousScreenIndex = 0;
			
			initializeLoginNavigator();
		}
		
		private function initializeLoginNavigator():void
		{
			_navigator = new StackScreenNavigator();
			
			var termsOfUseScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(TermsOfUseScreen);
			_navigator.addScreen(TERMSOFUSE, termsOfUseScreen);		
			termsOfUseScreen.addPopEvent(CreateUserPanelScreen.HEADER_BACK);

			var privacyScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(PrivacyPolicyScreen);
			_navigator.addScreen(PRIVACY, privacyScreen);		
			privacyScreen.addPopEvent(CreateUserPanelScreen.HEADER_BACK);
			
			var termsScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(TermsScreen);
			_navigator.addScreen(TERMS, termsScreen);		
			termsScreen.addPopEvent(CreateUserPanelScreen.HEADER_BACK);
			termsScreen.setScreenIDForPushEvent(GlobalSettings.TERMSOFUSE, TERMSOFUSE);
			termsScreen.setScreenIDForPushEvent(GlobalSettings.PRIVACYPOLICY, PRIVACY);
			termsScreen.setFunctionForPushEvent( "registrationComplete", onRegistrationComplete );
			
			var avatarScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(CreateAvatar);
			_navigator.addScreen(AVATAR, avatarScreen);		
			avatarScreen.addPopEvent(CreateUserPanelScreen.HEADER_BACK);
			avatarScreen.setScreenIDForPushEvent(CreateUserPanelScreen.HEADER_NEXT, TERMS);
			
			var genderScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(SelectGenderScreen);
			_navigator.addScreen(GENDER, genderScreen);
			genderScreen.addPopEvent(CreateUserPanelScreen.HEADER_BACK);
			genderScreen.setScreenIDForPushEvent(CreateUserPanelScreen.HEADER_NEXT, AVATAR);
			
			var passwordScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(EnterPasswordScreen);
			_navigator.addScreen(PASSWORD, passwordScreen);
			passwordScreen.addPopEvent(CreateUserPanelScreen.HEADER_BACK);
			passwordScreen.setScreenIDForPushEvent(CreateUserPanelScreen.HEADER_NEXT, GENDER);
			
			var usernameScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(EnterUsernameScreen);
			_navigator.addScreen(USERNAME, usernameScreen);
			usernameScreen.addPopEvent(CreateUserPanelScreen.HEADER_BACK);
			usernameScreen.setScreenIDForPushEvent(CreateUserPanelScreen.HEADER_NEXT, PASSWORD);
			
			var emailScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(EnterEmailScreen);
			_navigator.addScreen(EMAIL, emailScreen);
			emailScreen.addPopEvent(CreateUserPanelScreen.HEADER_BACK);
			emailScreen.setScreenIDForPushEvent(CreateUserPanelScreen.HEADER_NEXT, USERNAME);
			
			var authAccessScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(AuthorizeAccessScreen);
			_navigator.addScreen(AUTHORIZE, authAccessScreen);
			authAccessScreen.addPopEvent(CreateUserPanelScreen.HEADER_BACK);
			authAccessScreen.setScreenIDForPushEvent(CreateUserPanelScreen.HEADER_NEXT, EMAIL);
			
			var mindGoalieScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(SelectMindGoalieScreen);
			_navigator.addScreen(MINDGOALIE, mindGoalieScreen);
			mindGoalieScreen.addPopEvent(CreateUserPanelScreen.HEADER_BACK);
			mindGoalieScreen.setFunctionForPushEvent( "selectedMindGoalieAccount", onSelectedMindGoalieAccount );

			var goalieMindScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(SelectGoalieMindScreen);
			_navigator.addScreen(SELECT_GOALIE, goalieMindScreen);
			goalieMindScreen.addPopEvent(CreateUserPanelScreen.HEADER_BACK);
			goalieMindScreen.setFunctionForPushEvent( "selectedGoalieAccount", onSelectedGoalieAccount );
			goalieMindScreen.popTransition = Flip.createFlipRightTransition();
			goalieMindScreen.pushTransition = Flip.createFlipLeftTransition();
			
			var forgotPassScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(ForgotPasswordScreen);
			_navigator.addScreen(FORGOT_PASSWORD, forgotPassScreen);
			forgotPassScreen.addPopEvent(CreateUserPanelScreen.HEADER_BACK);
			
			var loginScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(LoginScreen);
			_navigator.addScreen(LOGIN, loginScreen);
			loginScreen.setScreenIDForPushEvent( "forgotpassword", FORGOT_PASSWORD );
			loginScreen.setScreenIDForPushEvent( "register", SELECT_GOALIE );
			loginScreen.setFunctionForPushEvent( "signin", onSignIn );
			
			_navigator.pushTransition = Slide.createSlideLeftTransition(); 
			_navigator.popTransition = Slide.createSlideRightTransition();
			
			// Check if we are redirected from a GoalieMind account to register a MindGoalie
			if (GlobalSettings.parentUserData != null)
			{
				_manager.autoPopulateUserDataForRegistration();		// Sets AccountType to MindGoalie Personal
				_navigator.rootScreenID = EMAIL;
			}
			else _navigator.rootScreenID = LOGIN;
			
			this.addChild(_navigator);
		}		
		
		private function onRegistrationComplete():void 
		{
			LogUtil.log("CreateUserNavigator.onRegistrationComplete()");
			
			_manager.saveUserData(onSaveUserDataComplete);
			
			function onSaveUserDataComplete():void
			{
				GlobalSettings.currentAccount = _manager.userData.accountType;
				
				_manager.createHome();
			}
		}

		// We use _navigator.getScreen(this._navigator.activeScreenID).properties to save user data of current screen
		// If we go back to a screen, the fields will automaticall self populate.
		
		private function onSelectedGoalieAccount(e:Event):void 
		{
			trace("onSelectedGoalieAccount() " + e.data.status);
			
			if (e.data.status) // TRUE == Mind Goalie Selected 
			{
				_navigator.getScreen(this._navigator.activeScreenID).properties = { isMindGoalie:true, isGoalieMind:false };
				this._navigator.pushScreen(MINDGOALIE);
			}
			else // FALSE == Goalie Mind Selected 
			{
				_navigator.getScreen(this._navigator.activeScreenID).properties = { isGoalieMind:true, isMindGoalie:false };
				this._navigator.pushScreen(EMAIL);
			}
		}
		
		private function onSelectedMindGoalieAccount(e:Event):void 
		{
			trace("onSelectedMindGoalieAccount() " + e.data.status);
			
			if (e.data.status) // TRUE == Mind Goalie Personal Selected 
			{
				_navigator.getScreen(this._navigator.activeScreenID).properties = { isMindGoaliePersonal:true, isMindGoalieOrg:false };
				this._navigator.pushScreen(AUTHORIZE);
			}
			else // FALSE == Mind Goalie Organization Selected 
			{
				_navigator.getScreen(this._navigator.activeScreenID).properties = { isMindGoaliePersonal:false, isMindGoalieOrg:true };
				this._navigator.pushScreen(EMAIL);
			}
		}		
		
		private function onSignIn(e:Event):void 
		{
			trace("onSignIn() " + e);
			
			_manager.createHome();
		}
		
		private function onScreenCompleted(e:Event):void 
		{
			trace("onScreenCompleted" + e.data);
		}
		
		override public function dispose():void 
		{
			super.dispose();
		}
		
	}

}