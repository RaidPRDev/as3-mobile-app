package goaliemind.view.home.panels 
{
	import flash.filesystem.File;
	
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.ImageLoader;
	import feathers.controls.Label;
	import feathers.controls.List;
	import feathers.controls.Panel;
	import feathers.controls.renderers.DefaultListItemRenderer;
	import feathers.controls.renderers.IListItemRenderer;
	import feathers.data.ListCollection;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;
	
	import goaliemind.core.AppManager;
	import goaliemind.data.support.AccountType;
	
	import starling.display.DisplayObject;
	import starling.events.Event;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class SideListMenu extends Panel 
	{
		private var _userPicPanel:Panel;
		private var _userPic:ImageLoader;
		private var _userName:Label;
		private var _subHeader:Header;
		private var _switchProfileButton:Button;
		private var _list:List;
		
		private var _manager:AppManager;
		
		public function SideListMenu() 
		{
			super();
			
			_manager = AppManager.GetInstance();
		}
		
		override protected function initialize():void 
		{
			super.initialize();
			
			this.layout = new AnchorLayout();
			
			this.styleNameList.add(GlobalSettings.SIDEMENUPANEL);
			
			createUserProfile();
			createUserSubHeader();
			createSwitchProfile();
			createList();
		}
		
		private function createUserProfile():void
		{
			var userPicLayoutData:AnchorLayoutData = new AnchorLayoutData(0, NaN, NaN, NaN, 0);
			
			_userPicPanel = new Panel();
			_userPicPanel.styleNameList.add(GlobalSettings.SIDEUSERPICPANEL);
			_userPicPanel.layoutData = userPicLayoutData;
			_userPicPanel.layout = new AnchorLayout();
			this.addChild(_userPicPanel);
			
			var userProfilePicLayoutData:AnchorLayoutData = new AnchorLayoutData(0, NaN, NaN, NaN, 0);
			var picFile:File = File.applicationDirectory.resolvePath("assets/profile_pic.png");
			_userPic = new ImageLoader();
			_userPic.layoutData = userProfilePicLayoutData;
			_userPic.source = picFile.nativePath;
			_userPic.x = 10;
			_userPicPanel.addChild(_userPic);
			_userPicPanel.validate();
			
			var userNameLayoutData:AnchorLayoutData = new AnchorLayoutData(NaN, NaN, 0, NaN, 0);
			userNameLayoutData.topAnchorDisplayObject = _userPicPanel;
			_userName = new Label();
			_userName.styleNameList.add(GlobalSettings.LABEL_30SIZE_WHITE);
			_userName.layoutData = userNameLayoutData;
			_userName.text = _manager.userData.username.toUpperCase();
			
			// _userPicPanel.addChild(_userName);
			
			// this.addChild(_userName);
		}
		
		private function createSwitchProfile():void
		{
			var switchProfileLayoutData:AnchorLayoutData = new AnchorLayoutData(0, NaN, NaN, 0);
			
			_switchProfileButton = new Button();
			_switchProfileButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("sidemenu/switchprofile_icon", _manager.appCore.theme.scale);
			_switchProfileButton.layoutData = switchProfileLayoutData;
			
			this.addChild(_switchProfileButton);
		}
		
		private function createUserSubHeader():void
		{
			var subHeaderLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0);
			subHeaderLayoutData.topAnchorDisplayObject = _userPicPanel;
			
			_subHeader = new Header();
			_subHeader.styleNameList.add(GlobalSettings.SIDEUSERSUBHEADERPANEL);
			_subHeader.layoutData = subHeaderLayoutData;
			
			var starIcon:DisplayObject = _manager.appCore.assets.imageLoaderFactory("sidemenu/star_side_white_icon", _manager.appCore.theme.scale);
			
			_subHeader.leftItems = new <DisplayObject> [ starIcon ];
			
			this.addChild(_subHeader);
		}		
		
		private function imageLoaderFactory(textureName:String):ImageLoader
		{
			var image:ImageLoader = new ImageLoader();
			image.textureScale = _manager.appCore.theme.scale;
			image.source = _manager.appCore.assets.applicationAssets.getTexture(textureName);
			return image;
		}
		
		private function createList():void
		{
			var _scale:Number = _manager.appCore.theme.scale;
			
			var items:Array = [];
			
			if (GlobalSettings.currentAccount == AccountType.GOALIEMIND)
			{
				items[items.length] = { label:"Account", isEnabled:true, icon:_manager.appCore.assets.imageLoaderFactory("sidemenu/account_icon", _scale), eventName:"accountScreen" }
				items[items.length] = { label:"Manage Profiles", isEnabled:true, icon:_manager.appCore.assets.imageLoaderFactory("sidemenu/users_side_icon", _scale), eventName:"profilesScreen" }
				items[items.length] = { label:"Banks and Cards", isEnabled:true, icon:_manager.appCore.assets.imageLoaderFactory("sidemenu/banks_icon", _scale), eventName:"banksScreen" }
				items[items.length] = { label:"Settings", isEnabled:true, icon:_manager.appCore.assets.imageLoaderFactory("sidemenu/settings_icon", _scale), eventName:"settingsScreen" }
			}
			else if (GlobalSettings.currentAccount == AccountType.PERSONAL_MINDGOALIE)
			{
				items[items.length] = { label:"Profile", isEnabled:true, icon:_manager.appCore.assets.imageLoaderFactory("sidemenu/account_icon", _scale), eventName:"accountScreen" }
				items[items.length] = { label:"Ideas", isEnabled:true, icon:_manager.appCore.assets.imageLoaderFactory("sidemenu/users_side_icon", _scale), eventName:"profilesScreen" }
				items[items.length] = { label:"Connect", isEnabled:true, icon:_manager.appCore.assets.imageLoaderFactory("sidemenu/banks_icon", _scale), eventName:"banksScreen" }
				items[items.length] = { label:"Settings", isEnabled:true, icon:_manager.appCore.assets.imageLoaderFactory("sidemenu/settings_icon", _scale), eventName:"settingsScreen" }
			}
			else if (GlobalSettings.currentAccount == AccountType.ORGANIZATION_MINDGOALIE)
			{
				items[items.length] = { label:"Account", isEnabled:true, icon:_manager.appCore.assets.imageLoaderFactory("sidemenu/account_icon", _scale), eventName:"accountScreen" }
				items[items.length] = { label:"Manage Profiles", isEnabled:true, icon:_manager.appCore.assets.imageLoaderFactory("sidemenu/users_side_icon", _scale), eventName:"profilesScreen" }
				items[items.length] = { label:"Banks and Cards", isEnabled:true, icon:_manager.appCore.assets.imageLoaderFactory("sidemenu/banks_icon", _scale), eventName:"banksScreen" }
				items[items.length] = { label:"Settings", isEnabled:true, icon:_manager.appCore.assets.imageLoaderFactory("sidemenu/settings_icon", _scale), eventName:"settingsScreen" }
			}

			items[items.length] = { label:"	    (c) 2015 | GoalieMind LLC | v1.02", isEnabled:false, icon:null, eventName:"version" };
			items.fixed = true;	
			
			var listLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, 0, 0);
			listLayoutData.topAnchorDisplayObject = _subHeader;
			
			_list = new List();
			_list.layoutData = listLayoutData;
			_list.dataProvider = new ListCollection(items);
			_list.styleNameList.add(GlobalSettings.SIDEMENULIST);
			
			this._list.itemRendererFactory = function():IListItemRenderer
			{
				var renderer:DefaultListItemRenderer = new DefaultListItemRenderer();
				renderer.styleNameList.add(GlobalSettings.SIDEMENULISTITEM);
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
			if (_list.selectedIndex == -1) return;
			
			_manager.onSideMenuSelection(_list.selectedItem.eventName);
			
			_list.selectedIndex = -1;
		}
		
		private function removeList():void
		{
			this.removeChild(_list);
		}		
		
		override public function dispose():void 
		{
			super.dispose();
		}
	}

}