package goaliemind.view.home.overlays.profile 
{
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.List;
	import feathers.controls.renderers.DefaultListItemRenderer;
	import feathers.controls.renderers.IListItemRenderer;
	import feathers.data.ListCollection;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;

	import starling.display.DisplayObject;
	import starling.events.Event;
	
	import goaliemind.ui.base.HomePanelScreen;
	import goaliemind.system.LogUtil;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class ProfileEditAccount extends HomePanelScreen
	{
		private var _list:List;
		
		public function ProfileEditAccount() 
		{
			super();
		}
		
		override protected function initialize():void 
		{
			super.initialize();
			
			this.layout = new AnchorLayout();
			
			this.title = "Edit Profile";
			
			this.headerFactory = this.customHeaderFactory;
			
			createList();
		}
		
		private function customHeaderFactory():Header
		{
			var header:Header = new Header();

			header.styleNameList.add(GlobalSettings.HOME_MAINHEADER);
			
			var backButton:Button = new Button();
			backButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
			backButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("header/back_header_icon", _manager.appCore.theme.scale);
			
			var actionButton:Button = new Button();
			actionButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
			actionButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("header/check_header_icon", _manager.appCore.theme.scale);

			header.leftItems = new <DisplayObject> [ backButton ];
			header.rightItems = new <DisplayObject> [ actionButton ];
			
			backButton.addEventListener(Event.TRIGGERED, onBackButton);
			
			return header;
		}		
		
		private function onBackButton(e:Event):void 
		{
			LogUtil.log("ProfileEditAccount.onBackButton()");
			
			this.dispatchEventWith("onBack");
		}			
		
		private function createList():void
		{
			var items:Array = [];
			items[items.length] = { label:"Username", isEnabled:true, icon:null, eventName:"" };
			items[items.length] = { label:"Password", isEnabled:true, icon:null, eventName:"" };
			items.fixed = true;

			var listLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, 0, 0);
			
			_list = new List();
			_list.layoutData = listLayoutData;
			_list.dataProvider = new ListCollection(items);
			_list.styleNameList.add(GlobalSettings.OVERLAYPANELLIST);
			
			this._list.itemRendererFactory = function():IListItemRenderer
			{
				var renderer:DefaultListItemRenderer = new DefaultListItemRenderer();
				renderer.styleNameList.add(GlobalSettings.SETTINGSLISTITEM);
				renderer.itemHasEnabled = true;
				
				renderer.enabledFunction = function( item:Object ):Boolean
				{
					return item.isEnabled;
				};
				return renderer;
			}
			
			this.addChild(_list);
			
			_list.addEventListener(Event.CHANGE, onListChange);
		}		
		
		private function onListChange(e:Event):void 
		{
			LogUtil.log("ProfileEditAccount.onListChange() " + _list.selectedIndex);
			
			if (_list.selectedIndex == -1) return;
			
			_list.selectedIndex = -1;
		}		
		
	}

}