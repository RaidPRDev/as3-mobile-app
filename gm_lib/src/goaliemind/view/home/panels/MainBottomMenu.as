package goaliemind.view.home.panels 
{
	import feathers.controls.ImageLoader;
	import feathers.controls.Panel;
	import feathers.controls.TabBar;
	import feathers.data.ListCollection;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;
	
	import starling.events.Event;
	import goaliemind.system.LogUtil;
	import goaliemind.core.AppManager;


	[Event(name="onSelectedItem",type="starling.events.Event")]
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class MainBottomMenu extends Panel
	{
		private var _tabGroup:TabBar;
		private var _manager:AppManager;
		
		public function MainBottomMenu() 
		{
			_manager = AppManager.GetInstance();
		}
		
		override protected function initialize():void 
		{
			super.initialize();
			
			this.layout = new AnchorLayout();
			
			this.styleNameList.add(GlobalSettings.HOME_MAINBOTTOMHEADER);
			
			var tabGroupLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, 0, 0);
			
			this._tabGroup = new TabBar();
			this._tabGroup.styleNameList.add(GlobalSettings.HOME_MAINBOTTOMTAB);
			this._tabGroup.layoutData = tabGroupLayoutData;
			this._tabGroup.dataProvider = new ListCollection(
			[
				{ label:"", isEnabled:true, defaultIcon:imageLoaderFactory("icons/home_icon"), eventName:"home" },
				{ label:"", isEnabled:true, defaultIcon:imageLoaderFactory("icons/search_icon"), eventName:"search" },
				{ label:"", isEnabled:true, defaultIcon:imageLoaderFactory("icons/store_icon"), eventName:"shop" },
				{ label:"", isEnabled:true, defaultIcon:imageLoaderFactory("icons/activity_icon"), eventName:"activity" }
			]);
			
			this._tabGroup.addEventListener(Event.CHANGE, headerButton_changedHandler);
			
			this.addChild(this._tabGroup);
		}
		
		private function imageLoaderFactory(textureName:String):ImageLoader
		{
			var image:ImageLoader = new ImageLoader();
			image.textureScale = _manager.appCore.theme.scale;
			image.source = _manager.appCore.assets.applicationAssets.getTexture(textureName);
			return image;
		}
		
		private function headerButton_changedHandler(event:Event):void
		{
			LogUtil.log("MainBottomMenu.headerButton_changedHandler() eventName: " + this._tabGroup.selectedItem.eventName);
			// this.dispatchEventWith("onSelectedItem", false, this._tabGroup.selectedItem.eventName );
			this.dispatchEventWith(this._tabGroup.selectedItem.eventName);
		}			
		
	}

}