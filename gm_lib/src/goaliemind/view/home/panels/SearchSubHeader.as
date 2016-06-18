package goaliemind.view.home.panels 
{
	import feathers.controls.Header;
	import feathers.controls.TabBar;
	import feathers.data.ListCollection;

	import starling.events.Event;

	import goaliemind.core.AppManager;
	import goaliemind.system.DeviceResolution;
	
	[Event(name="onSelectedItem",type="starling.events.Event")]
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class SearchSubHeader extends Header 
	{
		private var _tabGroup:TabBar;
		private var _manager:AppManager;
		
		public function SearchSubHeader() 
		{
			super();
			
			_manager = AppManager.GetInstance();
		}
		
		override protected function initialize():void 
		{
			super.initialize();
			
			this._tabGroup = new TabBar();
			this._tabGroup.width = DeviceResolution.deviceWidth;
			this._tabGroup.styleNameList.add(GlobalSettings.SUB_HEADERTAB);
			this._tabGroup.tabProperties.minTouchWidth = 100 * _manager.scale;
			this._tabGroup.tabProperties.minTouchHeight = 80 * _manager.scale;
			this._tabGroup.gap = 30 * _manager.appCore.theme.scale;
			this._tabGroup.dataProvider = new ListCollection(
			[
				{ 
					label:"Search by username", 
					isEnabled:true, 
					eventName:"userSearch" 
				},
				{ 
					label:"Search by tag", 
					isEnabled:true, 
					eventName:"tagSearch" 
				}
			]);
			
			// this._tabGroup.selectedIndex = 1;
			this._tabGroup.addEventListener(Event.CHANGE, headerButton_changedHandler);
			this.addChild(this._tabGroup);
		}
		
		private function headerButton_changedHandler(e:Event):void 
		{
			trace("_tabGroup.selectedIndex: " + _tabGroup.selectedIndex);
			
			if (_tabGroup.selectedItem != null)
			{
				this.dispatchEventWith("onSelectedItem", false, { selectedItem:_tabGroup.selectedItem } );
			}
		}
		
		override public function dispose():void 
		{
			_manager = null;
			
			this._tabGroup.removeEventListener(Event.CHANGE, headerButton_changedHandler);
			
			super.dispose();
		}
	}

}