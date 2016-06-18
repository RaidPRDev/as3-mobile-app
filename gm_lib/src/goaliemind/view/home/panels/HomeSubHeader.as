package goaliemind.view.home.panels 
{
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.TabBar;
	import feathers.data.ListCollection;
	
	import starling.display.DisplayObject;
	import starling.events.Event;
	import goaliemind.core.AppManager;
	

	[Event(name="onSelectedItem",type="starling.events.Event")]
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class HomeSubHeader extends Header 
	{
		private var _tabGroup:TabBar;
		private var _manager:AppManager;
		private var _fundsButton:Button;
		private var _moreButton:Button;
		
		public function HomeSubHeader() 
		{
			super();
			
			_manager = AppManager.GetInstance();
		}
		
		override protected function initialize():void 
		{
			super.initialize();
			
			this._tabGroup = new TabBar();
			this._tabGroup.tabProperties.minTouchWidth = 100 * _manager.scale;
			this._tabGroup.tabProperties.minTouchHeight = 80 * _manager.scale;
			this._tabGroup.gap = 30 * _manager.appCore.theme.scale;
			this._tabGroup.dataProvider = new ListCollection(
			[
				{ 
					label:"", 
					isEnabled:true, 
					defaultIcon:_manager.appCore.assets.imageLoaderFactory("header/grid_header_normal_icon", _manager.appCore.theme.scale), 
					defaultSelectedIcon:_manager.appCore.assets.imageLoaderFactory("header/grid_header_active_icon", _manager.appCore.theme.scale), 
					eventName:"gridview" 
				},
				{ 
					label:"", 
					isEnabled:true, 
					isSelected:true,
					defaultIcon:_manager.appCore.assets.imageLoaderFactory("header/singlescroll_header_normal_icon", _manager.appCore.theme.scale), 
					defaultSelectedIcon:_manager.appCore.assets.imageLoaderFactory("header/singlescroll_header_active_icon", _manager.appCore.theme.scale), 
					eventName:"singleview" 
				}
			]);
			
			this._tabGroup.selectedIndex = 1;
			this._tabGroup.addEventListener(Event.CHANGE, headerButton_changedHandler);
			
			this._fundsButton = new Button();
			this._fundsButton.label = "0 Stars";
			this._fundsButton.styleNameList.add(GlobalSettings.HOMEITEMSTARBUTTON);
			this.addChild(this._fundsButton);
		
			/*this._moreButton = new Button();
			this._moreButton.styleNameList.add(GlobalSettings.MOREBUTTON);
			this.addChild(this._moreButton);*/
			
			this.leftItems = new <DisplayObject> [ this._fundsButton ]; 
			this.centerItems = new <DisplayObject> [ this._tabGroup ]; 
			// this.rightItems = new <DisplayObject> [ this._moreButton ]; 
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