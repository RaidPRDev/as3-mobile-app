package goaliemind.view.home 
{
	import feathers.controls.Panel;
	import feathers.controls.StackScreenNavigator;
	import feathers.controls.StackScreenNavigatorItem;
	import feathers.display.TiledImage;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;
	import feathers.motion.Cover;
	import feathers.motion.Reveal;
	
	import starling.events.Event;

	import goaliemind.ui.base.HomePanelScreen;
	import goaliemind.view.home.panels.MainBottomMenu;
	import goaliemind.view.home.screens.ActivityScreen;
	import goaliemind.view.home.screens.HomeScreen;
	import goaliemind.view.home.screens.SearchScreen;
	import goaliemind.view.home.screens.ShopScreen;
	import goaliemind.core.AppNavigator;
	import goaliemind.system.LogUtil;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class HomeNavigator extends AppNavigator
	{
		private static const HOME:String = "homeScreen";
		private static const SEARCH:String = "searchScreen";
		private static const SHOP:String = "shopScreen";
		private static const ACTIVITY:String = "activityScreen";
		
		private static const TOTAL_SCREENS:int = 4;
		
		private const MENU_LIST:Object =
		{
			home: { id:0, screenid:HOME },
			search: { id:1, screenid:SEARCH },
			shop: { id:2, screenid:SHOP },
			activity: { id:3, screenid:ACTIVITY }
		};		
		
		private var _bottomMenu:MainBottomMenu;
		
		private var _totalScreens:int;
		
		public function HomeNavigator() 
		{
			super();
		}
		
		override protected function draw():void 
		{
			super.draw();
			
			if (this.isInvalid(INVALIDATION_FLAG_ALL))
			{
				
			}
		}
		
		override protected function initialize():void 
		{
			super.initialize();
			
			this.layout = new AnchorLayout();
			
			_currentScreen = _previousScreen = HOME;
			_currentScreenIndex = _previousScreenIndex = 0;
			
			initializeHomeNavigator();

			createBottomMenu();
			
			this.dispatchEventWith("navigatorLoadComplete");
		}
		
		private function initializeHomeNavigator():void
		{
			var navigatorLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, _manager.appCore.theme.bottomHeight, 0);
			
			_navigator = new StackScreenNavigator();
			_navigator.layoutData = navigatorLayoutData;
			
			var homeScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(HomeScreen);
			_navigator.addScreen(HOME, homeScreen);
			
			var searchScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(SearchScreen);
			_navigator.addScreen(SEARCH, searchScreen);

			var shopScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(ShopScreen);
			_navigator.addScreen(SHOP, shopScreen);

			var activityScreen:StackScreenNavigatorItem = new StackScreenNavigatorItem(ActivityScreen);
			_navigator.addScreen(ACTIVITY, activityScreen);
			
			_navigator.pushTransition = Reveal.createRevealLeftTransition();
			_navigator.popTransition = Cover.createCoverRightTransition();

			_navigator.rootScreenID = HOME;
			
			addChild(this._navigator);
			
			_navigator.addEventListener("transitionComplete", onTransitionComplete);
		}	
		
		private function onTransitionComplete(e:Event):void 
		{
			trace("e.data: " + e.data);
			trace("e.type: " + e.type);
			trace("e.target: " + e.target);
			trace("e.currentTarget: " + e.currentTarget);
			trace("e.currentTarget: " + e.currentTarget);
			onShow();
		}
		
		private function createBottomMenu():void
		{
			var bottomMenuLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, 0, 0);
			bottomMenuLayoutData.topAnchorDisplayObject = _navigator;
			
			_bottomMenu = new MainBottomMenu();
			_bottomMenu.layoutData = bottomMenuLayoutData;
			
			this.addChild(_bottomMenu);
			
			for (var key:String in MENU_LIST) 
			{
				_bottomMenu.addEventListener(key, screenChangeHandler);
			}			
		}
		
		public function screenChangeHandler(event:Event):void
		{
			LogUtil.log("HomeNavigator.screenChangeHandler() event.type: " + event.type);
			
			var newScreenId:String = event.type;
			
			_previousScreen = _currentScreen;
			_currentScreen = MENU_LIST[newScreenId].screenid;
			
			_previousScreenIndex = _currentScreenIndex;
			_currentScreenIndex = MENU_LIST[newScreenId].id;

			if (MENU_LIST[newScreenId].id < _previousScreenIndex) this._navigator.popToRootScreenAndReplace(_currentScreen);
			else this._navigator.pushScreen(_currentScreen);
			
			LogUtil.log("HomeNavigator.ID: " + MENU_LIST[newScreenId].id);
			LogUtil.log("HomeNavigator.SCREENID: " + MENU_LIST[newScreenId].screenid);
			LogUtil.log("HomeNavigator._previousScreenIndex: " + _previousScreenIndex);
			LogUtil.log("HomeNavigator._currentScreenIndex: " + _currentScreenIndex);
		}		
		
		private var _panelShadow:Panel;
		private var _shadowTileTexture:TiledImage;
		private function addShadowEdge():void
		{
			if (!_shadowTileTexture) 
			{
				_shadowTileTexture = new TiledImage(_manager.appCore.assets.applicationAssets.getTexture("list/sidemenu_shadow"));
				
				var panelShadowLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, 0, NaN);
				panelShadowLayoutData.rightAnchorDisplayObject = _navigator;
				
				_panelShadow = new Panel();
				_panelShadow.layoutData = panelShadowLayoutData;
				_panelShadow.backgroundSkin = _shadowTileTexture;
				_panelShadow.width = 54;				
			}
			
			AnchorLayoutData(_navigator.layoutData).left = 54;
			AnchorLayoutData(_bottomMenu.layoutData).left = 54;
			
			this.addChild(_panelShadow);
		}
		
		private function removeShadowEdge():void
		{
			this.removeChild(_panelShadow);
			
			AnchorLayoutData(_navigator.layoutData).left = 0;
			AnchorLayoutData(_bottomMenu.layoutData).left = 0;
		}
		
		public function sideMenuIsOpening():void
		{
			addShadowEdge();
		}

		public function sideMenuIsClosing():void
		{
			removeShadowEdge();
		}
		
		override public function onShow():void 
		{
			(_navigator.activeScreen as HomePanelScreen).show();
		}
		
		override public function dispose():void 
		{
			if (_panelShadow) 
			{
				this.removeChild(_panelShadow);
				_panelShadow = null;
			}
			
			if (_shadowTileTexture) 
			{
				_shadowTileTexture.dispose(); 
				_shadowTileTexture = null;
			}
			
			if (_navigator) _navigator.removeEventListener("transitionComplete", onTransitionComplete);
			
			super.dispose();
		}
		
	}
}