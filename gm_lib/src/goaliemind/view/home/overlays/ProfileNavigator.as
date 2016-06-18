package goaliemind.view.home.overlays 
{
	import feathers.controls.StackScreenNavigator;
	import feathers.controls.StackScreenNavigatorItem;
	import feathers.layout.AnchorLayoutData;
	import feathers.motion.Slide;
	
	import goaliemind.core.AppNavigator;
	import goaliemind.data.support.NavigatorTransitionType;
	import goaliemind.view.home.overlays.profile.ProfileAddAccount;
	import goaliemind.view.home.overlays.profile.ProfileEditAccount;
	import goaliemind.view.home.overlays.profile.ProfileHome;
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class ProfileNavigator extends AppNavigator 
	{
		private static const HOME:String = "profileHomeScreen";
		private static const EDITACCOUNT:String = "editProfileScreen";
		private static const ADDACCOUNT:String = "addProfileScreen";
		
		public function ProfileNavigator() 
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
			_navigator.layoutData = navigatorLayoutData;
			
			var homeScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(ProfileHome);
			homeScreen.setFunctionForPushEvent(AppNavigator.CLOSEPANEL, onClosePanel);
			homeScreen.setFunctionForPushEvent(EDITACCOUNT, onEditAccount);
			homeScreen.setFunctionForPushEvent(ADDACCOUNT, onAddAccount);
			homeScreen.setFunctionForPushEvent("onBack", onBack);
			_navigator.addScreen(HOME, homeScreen);
			
			var editAccountScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(ProfileEditAccount);
			editAccountScreen.setFunctionForPushEvent("onBack", onBack);
			_navigator.addScreen(EDITACCOUNT, editAccountScreen);

			var addAccountScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(ProfileAddAccount);
			addAccountScreen.setFunctionForPushEvent("onBack", onBack);
			_navigator.addScreen(ADDACCOUNT, addAccountScreen);
			
			_navigator.pushTransition = Slide.createSlideLeftTransition(); 
			_navigator.popTransition = Slide.createSlideRightTransition();
			
			_navigator.rootScreenID = HOME;
			
			addChild(this._navigator);
		}
		
		private function onBack():void 
		{
			switch (_navigator.activeScreenID)
			{
				case EDITACCOUNT:
				case ADDACCOUNT:
					_navigator.popScreen();
					break;
				
			}
		}
		
		private function onEditAccount():void 
		{
			_navigator.pushScreen(EDITACCOUNT);
		}
		
		private function onAddAccount():void 
		{
			_navigator.pushScreen(ADDACCOUNT);
		}
		
		/*private function onClosePanel():void 
		{
			this.removeFromParent(true);
		}*/
	}

}