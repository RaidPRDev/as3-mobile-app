package goaliemind.view.home.overlays 
{
	import feathers.controls.StackScreenNavigator;
	import feathers.controls.StackScreenNavigatorItem;
	import feathers.layout.AnchorLayoutData;
	import feathers.motion.Slide;
	
	import goaliemind.core.AppNavigator;
	import goaliemind.data.support.NavigatorTransitionType;
	import goaliemind.view.home.overlays.settings.SettingsHome;
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class SettingsNavigator extends AppNavigator 
	{
		private static const HOME:String = "settingsHomeScreen";
		
		public function SettingsNavigator() 
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
			
			var homeScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(SettingsHome);
			homeScreen.setFunctionForPushEvent(AppNavigator.CLOSEPANEL, onClosePanel);
			_navigator.addScreen(HOME, homeScreen);
			
			_navigator.pushTransition = Slide.createSlideLeftTransition(); 
			_navigator.popTransition = Slide.createSlideRightTransition();
			
			_navigator.rootScreenID = HOME;
			
			addChild(this._navigator);
		}
	}

}