package goaliemind.view.home.overlays 
{
	import feathers.controls.StackScreenNavigator;
	import feathers.controls.StackScreenNavigatorItem;
	import feathers.layout.AnchorLayoutData;
	import feathers.motion.Slide;
	
	import goaliemind.core.AppNavigator;
	import goaliemind.data.support.NavigatorTransitionType;
	import goaliemind.view.home.overlays.banks.BanksAddAccount;
	import goaliemind.view.home.overlays.banks.BanksEditAccount;
	import goaliemind.view.home.overlays.banks.BanksHome;
	import goaliemind.view.home.overlays.banks.accounts.AddBankAccount;
	import goaliemind.view.home.overlays.banks.accounts.AddCreditAccount;
	import goaliemind.view.home.overlays.banks.accounts.AddPayPalAccount;
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class BanksNavigator extends AppNavigator 
	{
		private static const HOME:String = "bankHomeScreen";
		private static const EDITACCOUNT:String = "editAccountScreen";
		private static const ADDACCOUNT:String = "addAccountScreen";
		private static const ADDBANKACCOUNT:String = "addBankAccountScreen";
		private static const ADDCREDITACCOUNT:String = "addCreditCardScreen";
		private static const ADDPAYPALACCOUNT:String = "addPayPalAccountScreen";
		
		public function BanksNavigator() 
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
			
			var homeScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(BanksHome);
			homeScreen.setFunctionForPushEvent(AppNavigator.CLOSEPANEL, onClosePanel);
			homeScreen.setFunctionForPushEvent(EDITACCOUNT, onEditAccount);
			homeScreen.setFunctionForPushEvent(ADDACCOUNT, onAddAccount);
			homeScreen.setFunctionForPushEvent("onBack", onBack);
			_navigator.addScreen(HOME, homeScreen);
			
			var editAccountScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(BanksEditAccount);
			editAccountScreen.setFunctionForPushEvent("onBack", onBack);
			_navigator.addScreen(EDITACCOUNT, editAccountScreen);

			var addAccountScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(BanksAddAccount);
			addAccountScreen.setFunctionForPushEvent(ADDBANKACCOUNT, onAddBankAccount);
			addAccountScreen.setFunctionForPushEvent(ADDCREDITACCOUNT, onAddCreditAccount);
			addAccountScreen.setFunctionForPushEvent(ADDPAYPALACCOUNT, onAddPayPalAccount);
			addAccountScreen.setFunctionForPushEvent("onBack", onBack);
			_navigator.addScreen(ADDACCOUNT, addAccountScreen);

			var addBankAccountScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(AddBankAccount);
			addBankAccountScreen.setFunctionForPushEvent("onBack", onBack);
			_navigator.addScreen(ADDBANKACCOUNT, addBankAccountScreen);

			var addCreditAccountScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(AddCreditAccount);
			addCreditAccountScreen.setFunctionForPushEvent("onBack", onBack);
			_navigator.addScreen(ADDCREDITACCOUNT, addCreditAccountScreen);

			var addPayPalAccountScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(AddPayPalAccount);
			addPayPalAccountScreen.setFunctionForPushEvent("onBack", onBack);
			_navigator.addScreen(ADDPAYPALACCOUNT, addPayPalAccountScreen);
			
			_navigator.pushTransition = Slide.createSlideLeftTransition(); 
			_navigator.popTransition = Slide.createSlideRightTransition();
			
			_navigator.rootScreenID = ADDACCOUNT; // HOME;
			
			addChild(this._navigator);
		}

		private function onBack():void 
		{
			switch (_navigator.activeScreenID)
			{
				case ADDACCOUNT:
					onClosePanel();
					break;
				case EDITACCOUNT:
				case ADDBANKACCOUNT:
				case ADDCREDITACCOUNT:
				case ADDPAYPALACCOUNT:
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

		private function onAddBankAccount():void 
		{
			_navigator.pushScreen(ADDBANKACCOUNT);
		}

		private function onAddCreditAccount():void 
		{
			_navigator.pushScreen(ADDCREDITACCOUNT);
		}

		private function onAddPayPalAccount():void 
		{
			_navigator.pushScreen(ADDPAYPALACCOUNT);
		}
		
		/*private function onClosePanel():void 
		{
			this.removeFromParent(true);
		}*/
	}

}