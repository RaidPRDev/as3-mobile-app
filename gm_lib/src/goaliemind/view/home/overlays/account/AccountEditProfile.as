package goaliemind.view.home.overlays.account 
{
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.List;
	import feathers.controls.Panel;
	import feathers.controls.renderers.IListItemRenderer;
	import feathers.data.ListCollection;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;
	import feathers.layout.HorizontalLayout;
	
	import goaliemind.system.LogUtil;
	import goaliemind.ui.base.HomePanelScreen;
	import goaliemind.ui.controls.renderer.TextInputViewItemRenderer;
	
	import starling.display.DisplayObject;
	import starling.display.Quad;
	import starling.events.Event;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class AccountEditProfile extends HomePanelScreen
	{
		private var _subPanel:Panel;
		private var _subPanelContent:Button;
		private var _list:List;
		
		public function AccountEditProfile() 
		{
			super();
		}
		
		override protected function initialize():void 
		{
			super.initialize();
			
			this.layout = new AnchorLayout();
			
			this.title = "Edit Profile";
			
			this.headerFactory = this.customHeaderFactory;
			
			createSubHeader();
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
			LogUtil.log("AccountEditProfile.onBackButton()");
			
			this.dispatchEventWith("onBack");
		}			
		
		private function createSubHeader():void
		{
			var panelLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0, 0, NaN);
			
			var verticalLayout:HorizontalLayout = new HorizontalLayout();
			verticalLayout.horizontalAlign = HorizontalLayout.HORIZONTAL_ALIGN_CENTER;
			verticalLayout.verticalAlign = HorizontalLayout.VERTICAL_ALIGN_MIDDLE;
			verticalLayout.gap = _manager.appCore.theme.regularPaddingSize;
			
			_subPanel = new Panel();
			_subPanel.backgroundSkin = new Quad(20, GlobalSettings.SUBHEADER_HEIGHT * _manager.appCore.theme.scale, GlobalSettings.BLUE_TEXT_COLOR);
			_subPanel.layoutData = panelLayoutData;
			_subPanel.layout = verticalLayout;
			
			_subPanelContent = new Button();
			_subPanelContent.defaultIcon = this.loadImageFactory(_manager.appCore.theme.createUserTheme.largeUserIconTexture);
			_subPanelContent.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_subPanelContent.styleNameList.add(GlobalSettings.BUTTON_SUBHEADER);
			
			addChild(DisplayObject(_subPanel));
			
			_subPanel.addChild(DisplayObject(_subPanelContent));
			
			_subPanel.validate();
		}		
		
		private function createList():void
		{
			var items:Array = [];
			items[items.length] = { field:"", prompt:"Username", isEnabled:false };
			items[items.length] = { field:"", prompt:"First Name (optional)", isEnabled:true };
			items[items.length] = { field:"", prompt:"Last Name (optional)", isEnabled:true };
			items[items.length] = { field:"", prompt:"Email Address", isEnabled:true };
			items[items.length] = { field:"", prompt:"Birthday", isEnabled:true };
			items[items.length] = { field:"", prompt:"Gender", isEnabled:true };
			items.fixed = true;

			var listLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, 0, 0);
			listLayoutData.topAnchorDisplayObject = _subPanel;
			
			_list = new List();
			_list.layoutData = listLayoutData;
			_list.dataProvider = new ListCollection(items);
			_list.styleNameList.add(GlobalSettings.OVERLAYPANELLIST);

			this._list.itemRendererFactory = function():IListItemRenderer
			{
				var renderer:IListItemRenderer = new TextInputViewItemRenderer();
				renderer.styleNameList.add(GlobalSettings.SETTINGSLISTITEM);
				
				return renderer;
			}
			
			this.addChild(_list);
			
			// _list.addEventListener(Event.CHANGE, onListChange);
		}		
		
		private function onListChange(e:Event):void 
		{
			LogUtil.log("AccountEditProfile.onListChange() " + _list.selectedIndex);
			
			if (_list.selectedIndex == -1) return;
			
			_list.selectedIndex = -1;
		}		
		
	}

}