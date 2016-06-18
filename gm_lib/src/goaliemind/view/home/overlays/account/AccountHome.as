package goaliemind.view.home.overlays.account 
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
	import goaliemind.core.AppNavigator;
	import goaliemind.system.LogUtil;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class AccountHome extends HomePanelScreen
	{
		private var _list:List;
		
		public function AccountHome() 
		{
			super();
		}
		
		override protected function initialize():void 
		{
			super.initialize();
			
			this.layout = new AnchorLayout();
			
			this.title = "Account";
			
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
			
			header.leftItems = new <DisplayObject> [ backButton ];
			
			backButton.addEventListener(Event.TRIGGERED, onBackButton);
			
			return header;
		}		
		
		private function onBackButton(e:Event):void 
		{
			LogUtil.log("AccountHome.onBackButton()");
			
			this.dispatchEventWith(AppNavigator.CLOSEPANEL);
		}		
		
		private function createList():void
		{
			var items:Array = [];
			items[items.length] = { label:"Edit Profile", isEnabled:true, icon:_manager.appCore.assets.imageLoaderFactory("icons/username_icon", _manager.scale), eventName:"editProfileScreen" };
			items[items.length] = { label:"Change Password", isEnabled:true, icon:_manager.appCore.assets.imageLoaderFactory("icons/password_icon", _manager.scale), eventName:"changePasswordScreen" };
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
			LogUtil.log("AccountHome.onListChange() " + _list.selectedIndex);
			
			if (_list.selectedIndex == -1) return;
			
			switch (_list.selectedItem.eventName)
			{
				case "editProfileScreen":
					this.dispatchEventWith("editProfileScreen");
					break;
				
				case "changePasswordScreen":
					this.dispatchEventWith("changePasswordScreen");
					break;
				
			}
			
			_list.selectedIndex = -1;
		}		
		
	}

}