package goaliemind.view.home.screens 
{
	import feathers.controls.Header;
	import feathers.controls.ImageLoader;
	import feathers.controls.Label;
	import feathers.controls.TextInput;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;
	
	import goaliemind.system.DeviceResolution;
	import goaliemind.ui.base.HomePanelScreen;
	import goaliemind.view.home.panels.SearchSubHeader;
	
	import starling.display.DisplayObject;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class SearchScreen extends HomePanelScreen
	{
		private var _searchSubHeader:SearchSubHeader;
		private var _searchInput:TextInput;
		
		public function SearchScreen() 
		{
			super();
		}
		
		override public function dispose():void
		{
			// never forget to dispose textures!
			super.dispose();
		}	
		
		override protected function initialize():void
		{
			super.initialize();

			this.title = "Search";
			
			this.layout = new AnchorLayout();

			this.headerFactory = this.customHeaderFactory;

			createSubHeader();
		}
		
		private function customHeaderFactory():Header
		{
			var header:Header = new Header();

			header.styleNameList.add(GlobalSettings.HOME_MAINHEADER);
			
			_searchInput = new TextInput();
			_searchInput.styleNameList.add(GlobalSettings.INPUTSEARCH);
			_searchInput.prompt = "Search";
			_searchInput.width = DeviceResolution.deviceWidth - (_manager.appCore.theme.regularPaddingSize * 2);
			
			header.centerItems = new <DisplayObject> [ _searchInput ]; 
			
			return header;
		}		
		
		private function createSubHeader():void
		{
			var subHeaderData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0);
			
			_searchSubHeader = new SearchSubHeader();
			_searchSubHeader.layoutData = subHeaderData;
			_searchSubHeader.styleNameList.add(GlobalSettings.HOME_SUBHEADER);
			// _subHeader.addEventListener("onSelectedItem", onSelectedItem);
			
			this.addChild(_searchSubHeader);
		}
		
		private var _iconNoResults:ImageLoader;
		private var _noResultsTitleMessage:Label;
		private var _noResultsMessage:Label;
		
		private function showNoResults():void
		{
			var iconLayoutData:AnchorLayoutData = new AnchorLayoutData(NaN, NaN, NaN, NaN, 0, -30);
			_iconNoResults = _manager.appCore.assets.imageLoaderFactory("subheaders/zoom_large_icon", _manager.scale)
			_iconNoResults.layoutData = iconLayoutData;
			_iconNoResults.color = 0xacacac;
			this.addChild(_iconNoResults);
			
			var titleLayoutData:AnchorLayoutData = new AnchorLayoutData(8, NaN, NaN, NaN, 0);
			titleLayoutData.topAnchorDisplayObject = _iconNoResults;
			var fontSize:int = _manager.appCore.theme.smallMediumFontSize;
			var fontColor:int = GlobalSettings.DARK_TEXT_COLOR;
			_noResultsTitleMessage = new Label();
			_noResultsTitleMessage.textRendererProperties.elementFormat = _manager.appCore.theme.getElementFormat("bold", fontSize, fontColor); 
			
			var textCon:String = "Define your search\n ";
			
			_noResultsTitleMessage.textRendererProperties.wordWrap = false;
			_noResultsTitleMessage.textRendererProperties.textAlign = "left";
			_noResultsTitleMessage.textRendererProperties.leading = _manager.appCore.theme.fontLeading * _manager.appCore.theme.scale;
			_noResultsTitleMessage.layoutData = titleLayoutData;
			_noResultsTitleMessage.text = textCon;
			
			var descLayoutData:AnchorLayoutData = new AnchorLayoutData(0, NaN, NaN, NaN, 0);
			descLayoutData.topAnchorDisplayObject = _noResultsTitleMessage;
			fontSize = _manager.appCore.theme.smallFontSize;
			_noResultsMessage = new Label();
			_noResultsMessage.textRendererProperties.elementFormat = _manager.appCore.theme.getElementFormat("regular", fontSize, fontColor);
			
			textCon = "Select Search by Username, to find other users\n";
			textCon += "OR\n";
			textCon += "Select Search by Tags, to find projects\n";
			
			_noResultsMessage.textRendererProperties.wordWrap = true;
			_noResultsMessage.textRendererProperties.textAlign = "center";
			_noResultsMessage.textRendererProperties.leading = _manager.appCore.theme.fontLeading * _manager.appCore.theme.scale;
			_noResultsMessage.layoutData = descLayoutData;
			_noResultsMessage.text = textCon;
			
			this.addChild(_noResultsTitleMessage);
			this.addChild(_noResultsMessage);
		}
		
		override public function show():void 
		{
			super.show();
			
			showNoResults();
		}
	}

}