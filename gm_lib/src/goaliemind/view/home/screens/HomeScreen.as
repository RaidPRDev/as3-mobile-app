package goaliemind.view.home.screens 
{
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.ImageLoader;
	import feathers.controls.Label;
	import feathers.controls.List;
	import feathers.controls.Scroller;
	import feathers.controls.renderers.IListItemRenderer;
	import feathers.data.ListCollection;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;
	import feathers.layout.TiledRowsLayout;
	import feathers.layout.VerticalLayout;
	
	import goaliemind.system.DateTools;
	import goaliemind.ui.base.HomePanelScreen;
	import goaliemind.ui.controls.renderer.SingleViewItemRenderer;
	import goaliemind.view.home.panels.CamUploadPanel;
	import goaliemind.view.home.panels.HomeSubHeader;
	
	import starling.animation.Transitions;
	import starling.core.Starling;
	import starling.display.DisplayObject;
	import starling.events.Event;
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class HomeScreen extends HomePanelScreen
	{
		private static const LAYOUT_SINGLEVIEW:String = "singleview";
		private static const LAYOUT_GRIDVIEW:String = "gridview";
		private static const LAYOUT_GRIDVIEW_ITEMWIDTH:int = 216;
		private static const LAYOUT_GRIDVIEW_ITEMHEIGHT:int = 264;
		
		private var _list:List;
		private var _currentListView:String;
		private var _currentListRendererTheme:String;
		private var _skip:int;
		private var _max:int;
		private var _totalProjects:int

		private var _camUploadPanel:CamUploadPanel;
		
		public function HomeScreen() 
		{
			super();
		}
		
		override public function dispose():void
		{
			if (_subHeader)
			{
				_subHeader.removeEventListener("onSelectedItem", onSelectedItem);
				this.removeChild(_subHeader);
			}
			
			// never forget to dispose textures!
			super.dispose();
		}	
		
		override protected function initialize():void
		{
			super.initialize();

			_currentListView = LAYOUT_SINGLEVIEW;
			_currentListRendererTheme = GlobalSettings.HOMESINGLEITEMLIST;
			
			this.title = "Home";
			
			this.layout = new AnchorLayout();

			this.headerFactory = this.customHeaderFactory;
			
			this.createSubHeader();
			
			this.initializeCamUploadPanel();
		}
		
		private function customHeaderFactory():Header
		{
			var header:Header = new Header();

			header.styleNameList.add(GlobalSettings.HOME_MAINHEADER);
			
			var sideButton:Button = new Button();
			sideButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
			sideButton.name = "sidemenu";
			sideButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("header/sidemenu_header_icon", _manager.scale);
			
			var actionButton:Button = new Button();
			actionButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
			actionButton.name = "upload";
			actionButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("header/upload_icon", _manager.scale)
			
			var logo:ImageLoader = _manager.appCore.assets.imageLoaderFactory("header/goaliemind_header_text", _manager.scale)
			
			header.leftItems = new <DisplayObject> [ sideButton ];
			header.centerItems = new <DisplayObject> [ logo ];
			header.rightItems = new <DisplayObject> [ actionButton ];
			
			sideButton.addEventListener(Event.TRIGGERED, onButton);
			actionButton.addEventListener(Event.TRIGGERED, onButton);
			
			return header;
		}
		
		private function onButton(e:Event):void 
		{
			var btn:Button = e.target as Button;
			if (!btn) return;
			
			switch (btn.name)
			{
				case "upload":
					showCamUploadPanel();
					break;
					
				case "sidemenu":
					break;			
			}
		}		
		
		private function initializeCamUploadPanel():void
		{
			_camUploadPanel = new CamUploadPanel();
			_camUploadPanel.width = Starling.current.stage.stageWidth;
			_camUploadPanel.height = Starling.current.stage.stageHeight;
			_camUploadPanel.x = Starling.current.stage.stageWidth;
			_manager.appCore.addChild(_camUploadPanel);
			
			_camUploadPanel.visible = false;
		}
		
		private function showCamUploadPanel():void
		{
			_camUploadPanel.visible = true;
			
			Starling.juggler.tween(_camUploadPanel, 0.35, 
			{
				transition: Transitions.EASE_OUT,
				onComplete:onCamUploadPanelFadeInComplete,
				x:0
			});
			
			function onCamUploadPanelFadeInComplete():void
			{
				_manager.hideHomeScreen();
				
				_camUploadPanel.addEventListener("onClosePanel", onCloseCamUploadPanel);
				_camUploadPanel.addEventListener("onProjectComplete", onProjectComplete);
				
				Starling.juggler.delayCall(_camUploadPanel.onCamUploadPanelFadeInComplete, 0.01);
			}
		}
		
		/////////////////////////////////////////////////////////////
		// PROJECT COMPLETE
		//////////////////////////////////////////////////////////////
		
		private function onProjectComplete(e:Event):void
		{
			_manager.showHomeScreen();
			
			_manager.nativeCamPanel.disposeAll(onDisposeAllComplete);
			
			function onDisposeAllComplete():void
			{
				_camUploadPanel.visible = false;
				Starling.juggler.delayCall(resetHomeListItems, 0.5);
			}
		}
		
		private function resetHomeListItems():void
		{
			removeList();
			initializeList();
		}
		
		private function onCloseCamUploadPanel(e:Event):void 
		{
			_manager.showHomeScreen();
			
			_manager.nativeCamPanel.disposeAll(onDisposeAllComplete);
			
			function onDisposeAllComplete():void
			{
				Starling.juggler.delayCall(startTransition, 0.25);
			}
			
			function startTransition():void
			{
				Starling.juggler.tween(_camUploadPanel, 0.45, 
				{
					transition: Transitions.EASE_OUT,
					onComplete:onCamUploadPanelFadeOutComplete,
					x:Starling.current.stage.stageWidth,
					delay:0.5
				});
			}
			
			function onCamUploadPanelFadeOutComplete():void
			{
				// Starling.juggler.delayCall(removeCamUploadPanel, 0.25);
				_camUploadPanel.visible = false;
				removeCamUploadPanel();
			}
		}
		
		private function removeCamUploadPanel():void
		{
			_camUploadPanel.removeEventListener("onProjectComplete", onProjectComplete);
			_camUploadPanel.removeEventListener("onClosePanel", onCloseCamUploadPanel);
		}
		
		override public function show():void 
		{
			super.show();
			
			initializeList();
		}
		
		private function createSubHeader():void
		{
			var subHeaderData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0);
			
			_subHeader = new HomeSubHeader();
			_subHeader.layoutData = subHeaderData;
			_subHeader.styleNameList.add(GlobalSettings.HOME_SUBHEADER);
			_subHeader.addEventListener("onSelectedItem", onSelectedItem);
			
			this.addChild(_subHeader);
		}
		
		private function onSelectedItem(e:Event):void 
		{
			trace(e.data.selectedItem.eventName);
			
			_currentListView = e.data.selectedItem.eventName;
			
			removeList();
			initializeList();
		}
		
		private function initializeList():void
		{
			_manager.serverHandler.getProjectList(_manager.userData, 0, 20, onGetProjectListComplete);
			
			function onGetProjectListComplete(data:Array):void
			{
				createList(data);
			}
		}
		
		private var _iconNoResults:ImageLoader;
		private var _noResultsMessage:Label;
		
		private function removeNoResults():void
		{
			if (_iconNoResults)
			{
				this.removeChild(_iconNoResults, true);
				this.removeChild(_noResultsMessage, true);
			}
		}
		
		private function showNoResults():void
		{
			if (_iconNoResults) return;
			
			var iconLayoutData:AnchorLayoutData = new AnchorLayoutData(NaN, NaN, NaN, NaN, 0, -30);
			
			_iconNoResults = _manager.appCore.assets.imageLoaderFactory("header/upload_icon", _manager.scale)
			_iconNoResults.layoutData = iconLayoutData;
			_iconNoResults.color = 0xacacac;
			this.addChild(_iconNoResults);
			
			var descLayoutData:AnchorLayoutData = new AnchorLayoutData(8, NaN, NaN, NaN, 0);
			descLayoutData.topAnchorDisplayObject = _iconNoResults;
			_noResultsMessage = new Label();
			_noResultsMessage.styleNameList.add(GlobalSettings.LABEL_22SIZE_DARKGREY);
			
			var textCon:String = "Tap on the GoalieMind icon\n ";
			textCon += "to share your first creation!";
			
			_noResultsMessage.textRendererProperties.wordWrap = true;
			_noResultsMessage.textRendererProperties.textAlign = "left";
			_noResultsMessage.textRendererProperties.leading = _manager.appCore.theme.fontLeading * _manager.appCore.theme.scale;
			_noResultsMessage.layoutData = descLayoutData;
			_noResultsMessage.text = textCon;
			
			this.addChild(_noResultsMessage);
		}
		
		private function createList(dataList:Array):void
		{
			if (dataList.length == 0) 
			{
				showNoResults();
				
				return;
			}
			else removeNoResults();
			
			var items:Array = [];
			var path:String = GlobalSettings.SERVER_ROOT + GlobalSettings.IMAGES_PATH;
			var isSingleView:Boolean = (_currentListView == LAYOUT_SINGLEVIEW);
			
			for(var i:int = 0; i < dataList.length; i++)
			{
				var itemDate:String = DateTools.convertASDateTimeToDateFormatStyle(dataList[i]["timestamp"]);
				var image:String = (_currentListView == LAYOUT_SINGLEVIEW) ? dataList[i]["image"] : dataList[i]["thumb"];
				var iconPath:String = path + dataList[i]["month"] + "_" + dataList[i]["year"] + "/" + image;
				var item:Object = {
					label:dataList[i]["description"],
					icon:iconPath,
					date:itemDate,
					funded:"0"
				};
				items[i] = item;
			}
			items.fixed = true;
			
			var listLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, 0, 0);
			listLayoutData.topAnchorDisplayObject = _subHeader;
			
			_list = new List();
			_list.layoutData = listLayoutData;
			_list.dataProvider = new ListCollection(items);
			_list.snapScrollPositionsToPixels = false;
			_list.clipContent = false;
			_list.horizontalScrollPolicy = Scroller.SCROLL_POLICY_OFF;
			_list.useFixedThrowDuration = true;
			
			if (_currentListView == LAYOUT_GRIDVIEW) setHomeListGridStyles();
			else if (_currentListView == LAYOUT_SINGLEVIEW) setHomeListSingleViewStyles();
			
			this._list.itemRendererFactory = function():IListItemRenderer
			{
				var renderer:SingleViewItemRenderer = new SingleViewItemRenderer();
				renderer.styleNameList.add(_currentListRendererTheme);
				
				if (_currentListView == LAYOUT_SINGLEVIEW)
				{
					renderer.isSingleView = true;
					renderer.minWidth = renderer.maxWidth = 640 * _manager.scale;
					renderer.minHeight = renderer.maxHeight = 780 * _manager.scale;
				}
				else
				{
					renderer.isSingleView = false;
					renderer.minWidth = renderer.maxWidth = 202 * _manager.scale;
					renderer.minHeight = renderer.maxHeight = 202 * _manager.scale;
				}
				
				return renderer;
			}
			
			this.addChildAt(_list, 0);
		}
		
		private function onListFadeInComplete():void 
		{
			
		}
		
		private function removeList():void
		{
			this.removeChild(_list);
		}
		
		private function setHomeListGridStyles():void 
		{
			_currentListRendererTheme = GlobalSettings.HOMESINGLEITEMLIST;
			
			var tileLayout:TiledRowsLayout = new TiledRowsLayout();
			tileLayout.horizontalAlign = TiledRowsLayout.HORIZONTAL_ALIGN_CENTER;
			tileLayout.verticalAlign = TiledRowsLayout.VERTICAL_ALIGN_TOP;
			tileLayout.paging = TiledRowsLayout.PAGING_NONE;
			tileLayout.tileHorizontalAlign = TiledRowsLayout.TILE_HORIZONTAL_ALIGN_JUSTIFY;
			tileLayout.tileVerticalAlign = TiledRowsLayout.TILE_VERTICAL_ALIGN_TOP;
			tileLayout.gap = 8 * _manager.scale;
			tileLayout.padding = 8 * _manager.scale;
			tileLayout.useVirtualLayout = true;
			tileLayout.resetTypicalItemDimensionsOnMeasure = true;
			tileLayout.typicalItemWidth = 202 * _manager.scale;
			tileLayout.typicalItemHeight = 202 * _manager.scale;
			tileLayout.useSquareTiles = false;
			tileLayout.requestedColumnCount = 3;
			_list.layout = tileLayout;
		}

		private function setHomeListSingleViewStyles():void 
		{
			_currentListRendererTheme = GlobalSettings.HOMESINGLEITEMLIST;
		
			var verticalLayout:VerticalLayout = new VerticalLayout();
			verticalLayout.horizontalAlign = VerticalLayout.HORIZONTAL_ALIGN_JUSTIFY;
			verticalLayout.verticalAlign = VerticalLayout.VERTICAL_ALIGN_TOP;
			verticalLayout.gap = 8 * _manager.appCore.theme.scale;
			verticalLayout.paddingTop = 0;
			verticalLayout.paddingBottom = 8 * _manager.scale;
			verticalLayout.useVirtualLayout = true;
			verticalLayout.typicalItemWidth = 640 * _manager.scale;
			verticalLayout.typicalItemHeight = 780 * _manager.scale;
			verticalLayout.resetTypicalItemDimensionsOnMeasure = true;
			_list.layout = verticalLayout;		
		}		
		
	}

}