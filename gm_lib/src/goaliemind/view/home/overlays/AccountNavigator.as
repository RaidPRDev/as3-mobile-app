package goaliemind.view.home.overlays 
{
	import feathers.controls.StackScreenNavigator;
	import feathers.controls.StackScreenNavigatorItem;
	import feathers.layout.AnchorLayoutData;
	import feathers.motion.Flip;
	import feathers.motion.Slide;
	
	import goaliemind.core.AppNavigator;
	import goaliemind.data.support.NavigatorTransitionType;
	import goaliemind.system.DeviceResolution;
	import goaliemind.view.home.overlays.account.AccountChangePassword;
	import goaliemind.view.home.overlays.account.AccountEditProfile;
	import goaliemind.view.home.overlays.account.AccountHome;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class AccountNavigator extends AppNavigator 
	{
		private static const HOME:String = "accountHomeScreen";
		private static const EDITPROFILE:String = "editProfileScreen";
		private static const CHANGEPASSWORD:String = "changePasswordScreen";
		
		public function AccountNavigator() 
		{
			super();
			
			isTransitionIn = true;
			_transitionInType = NavigatorTransitionType.SLIDE_NORMAL_RIGHT;
		}
		
		override public function dispose():void 
		{
			super.dispose();
		}
		
		override protected function initialize():void 
		{
			super.initialize();
			
			initializeNavigator();
			
			if (isTransitionIn) this.transitionIn();
		}
		
		private function initializeNavigator():void
		{
			var navigatorLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, 0, 0);
			
			_navigator = new StackScreenNavigator();
			// _navigator.layoutData = navigatorLayoutData;
			
			_navigator.width = DeviceResolution.deviceWidth;
			_navigator.height = DeviceResolution.deviceHeight;
			
			var homeScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(AccountHome);
			homeScreen.setFunctionForPushEvent(AppNavigator.CLOSEPANEL, onClosePanel);
			homeScreen.setFunctionForPushEvent(EDITPROFILE, onEditProfile);
			homeScreen.setFunctionForPushEvent(CHANGEPASSWORD, onChangePassword);
			homeScreen.setFunctionForPushEvent("onBack", onBack);
			_navigator.addScreen(HOME, homeScreen);
			
			var editProfileScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(AccountEditProfile);
			editProfileScreen.setFunctionForPushEvent("onBack", onBack);
			_navigator.addScreen(EDITPROFILE, editProfileScreen);

			var passwordScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(AccountChangePassword);
			passwordScreen.setFunctionForPushEvent("onBack", onBack);
			_navigator.addScreen(CHANGEPASSWORD, passwordScreen);
			
			_navigator.pushTransition = Slide.createSlideLeftTransition(); 
			_navigator.popTransition = Slide.createSlideRightTransition();
	
			_navigator.rootScreenID = HOME;
			
			addChild(this._navigator);
		}
		
		override public function transitionInComplete():void 
		{
			super.transitionInComplete();
		}
		
		private function onBack():void 
		{
			switch (_navigator.activeScreenID)
			{
				case EDITPROFILE:
				case CHANGEPASSWORD:
					_navigator.popScreen();
					break;
				
			}
		}
		
		private function onEditProfile():void 
		{
			_navigator.pushScreen(EDITPROFILE);
		}
		
		private function onChangePassword():void 
		{
			_navigator.pushScreen(CHANGEPASSWORD);
		}
		
		/*private function onClosePanel():void 
		{
			if (isTransitionIn) this.transitionOut(onTransitionComplete);
			else onTransitionComplete();
			
			function onTransitionComplete():void
			{
				removeFromParent(true);
			}
		}*/
	}

}