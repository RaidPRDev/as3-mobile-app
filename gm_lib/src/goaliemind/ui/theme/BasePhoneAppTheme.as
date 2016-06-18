package goaliemind.ui.theme
{
	import flash.geom.Rectangle;
	import flash.text.engine.CFFHinting;
	import flash.text.engine.ElementFormat;
	import flash.text.engine.FontDescription;
	import flash.text.engine.FontLookup;
	import flash.text.engine.FontPosture;
	import flash.text.engine.FontWeight;
	import flash.text.engine.RenderingMode;
	
	import feathers.controls.Alert;
	import feathers.controls.Button;
	import feathers.controls.ButtonGroup;
	import feathers.controls.Callout;
	import feathers.controls.Header;
	import feathers.controls.ImageLoader;
	import feathers.controls.Label;
	import feathers.controls.List;
	import feathers.controls.Panel;
	import feathers.controls.PanelScreen;
	import feathers.controls.Scroller;
	import feathers.controls.SimpleScrollBar;
	import feathers.controls.TabBar;
	import feathers.controls.TextInput;
	import feathers.controls.ToggleButton;
	import feathers.controls.renderers.BaseDefaultItemRenderer;
	import feathers.controls.renderers.DefaultListItemRenderer;
	import feathers.controls.renderers.LayoutGroupListItemRenderer;
	import feathers.controls.text.StageTextTextEditor;
	import feathers.controls.text.TextBlockTextRenderer;
	import feathers.core.FeathersControl;
	import feathers.core.PopUpManager;
	import feathers.display.Scale3Image;
	import feathers.display.Scale9Image;
	import feathers.layout.TiledRowsLayout;
	import feathers.layout.VerticalLayout;
	import feathers.skins.SmartDisplayObjectStateValueSelector;
	import feathers.system.DeviceCapabilities;
	import feathers.textures.Scale3Textures;
	import feathers.textures.Scale9Textures;
	import feathers.themes.StyleNameFunctionTheme;
	import feathers.utils.textures.TextureCache;
	
	import goaliemind.system.DeviceResolution;
	import goaliemind.ui.controls.renderer.SingleViewItemRenderer;
	
	import starling.core.Starling;
	import starling.display.DisplayObject;
	import starling.display.Image;
	import starling.display.Quad;
	import starling.textures.Texture;
	import starling.textures.TextureAtlas;
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class BasePhoneAppTheme extends StyleNameFunctionTheme
	{
		/*[Embed(source = "/../lib/assets/fonts/SourceSansPro-Regular.ttf", fontFamily = "SourceSansPro", fontWeight = "normal", mimeType = "application/x-font", embedAsCFF = "true")]
		protected static const SOURCE_SANS_PRO_REGULAR:Class;
		
		[Embed(source = "/../lib/assets/fonts/SourceSansPro-Semibold.ttf", fontFamily = "SourceSansPro", fontWeight = "bold", mimeType = "application/x-font", embedAsCFF = "true")]
		protected static const SOURCE_SANS_PRO_SEMIBOLD:Class;
		
		public static const FONT_NAME:String = "SourceSansPro";
		*/
		[Embed(source = "/../lib/assets/fonts/ProximaNova-Regular.ttf", fontFamily = "Proxima Nova Rg", fontWeight = "normal", mimeType = "application/x-font", embedAsCFF = "true")]
		protected static const SOURCE_SANS_PRO_REGULAR:Class;
		
		[Embed(source = "/../lib/assets/fonts/ProximaNova-Bold.ttf", fontFamily = "Proxima Nova Rg", fontWeight = "bold", mimeType = "application/x-font", embedAsCFF = "true")]
		protected static const SOURCE_SANS_PRO_SEMIBOLD:Class;
		
		public static const FONT_NAME:String = "Proxima Nova Rg";
		
		protected static const TEXTINPUT_SCALE9_GRID:Rectangle = new Rectangle(4, 4, 4, 4);
		protected static const TEXTINPUT_SCALE3_REGION:Rectangle = new Rectangle(4, 0, 6, 0);
		protected static const BUTTONLOGIN_SCALE3_GRID:Rectangle = new Rectangle(72, 0, 4, 0);
		protected static const ORIGINAL_DPI_IPHONE_RETINA:int = 326;
		
		protected var _scaleToDPI:Boolean;
		public function get scaleToDPI():Boolean  { return this._scaleToDPI; }
		
		protected var _originalDPI:int;
		public function get originalDPI():int  { return this._originalDPI; }
		
		protected var _scale:Number = 1;
		public function get scale():Number  { return this._scale; }
		
		protected var _stageTextScale:Number = 1;
		public function get stageTextScale():Number  { return this._stageTextScale; }
		
		protected var _fontLeading:int;
		protected var _xSmallFontSize:int;
		protected var _smallFontSize:int;
		protected var _smallMediumFontSize:int;
		protected var _mediumFontSize:int;
		protected var _regularFontSize:int;
		protected var _largeFontSize:int;
		protected var _xLargeFontSize:int;
		protected var _inputFontSize:int;
		
		public function get fontLeading():int  { return this._fontLeading; }
		public function get xSmallFontSize():int  { return this._xSmallFontSize; }
		public function get smallFontSize():int  { return this._smallFontSize; }
		public function get smallMediumFontSize():int  { return this._smallMediumFontSize; }
		public function get mediumFontSize():int  { return this._mediumFontSize; }
		public function get regularFontSize():int  { return this._regularFontSize; }
		public function get largeFontSize():int  { return this._largeFontSize; }
		public function get xLargeFontSize():int  { return this._xLargeFontSize; }
		public function get inputFontSize():int  { return this._inputFontSize; }
		
		protected var buttonLoginWidth:int;
		protected var buttonLoginHeight:int;
		protected var standardTextInputWidth:int;
		protected var standardTextInputHeight:int;
		protected var standardHeaderHeight:int;
		protected var standardSubHeaderHeight:int;
		protected var bottomHeaderHeight:int;
		public function get headerHeight():int  { return this.standardHeaderHeight; }
		public function get subHeaderHeight():int  { return this.standardSubHeaderHeight; }
		public function get bottomHeight():int  { return this.bottomHeaderHeight; }
		
		protected var _touchSize:int;
		public function get touchSize():int  { return this._touchSize; }
		
		public var xSmallPaddingSize:int;
		public var smallPaddingSize:int;
		public var mediumPaddingSize:int;
		public var regularPaddingSize:int;
		public var largePaddingSize:int;
		public var screenTopPaddingSize:int;
		public var screenLeftPaddingSize:int;
		public var screenBottomPaddingSize:int;
		public var screenRightPaddingSize:int;
		
		protected var _regularFontDescription:FontDescription;
		protected var _boldFontDescription:FontDescription;
		public function get regularFontDescription():FontDescription  { return _regularFontDescription; }
		public function get boldFontDescription():FontDescription  { return _boldFontDescription; }
		
		protected var inputUIElementFormat:ElementFormat;
		protected var inputLightUIElementFormat:ElementFormat;
		protected var lightUIElementFormat:ElementFormat;
		protected var buttonLoginUIElementFormat:ElementFormat;
		protected var headerLightElementFormat:ElementFormat;
		protected var headerLightBoldElementFormat:ElementFormat;
		protected var headerDisabledLightElementFormat:ElementFormat;
		protected var mediumLightElementFormat:ElementFormat;
		protected var lightElementFormat:ElementFormat;
		protected var smallDarkElementFormat:ElementFormat;
		protected var regularBoldDarkElementFormat:ElementFormat;
		
		protected var _atlas:TextureAtlas;
		
		public function get textInputSkin():Scale9Textures  { return textInputSkinTextures; }
		protected var textInputSkinTextures:Scale9Textures;
		protected var textActiveInputSkinTextures:Scale9Textures;
		protected var buttonLoginSkinTextures:Scale3Textures;
		protected var buttonFBLoginSkinTextures:Scale3Textures;
		
		protected var greyHeaderBackButtonTexture:Texture;
		protected var greyHeaderNextButtonTexture:Texture;
		protected var greyHeaderNextDisabledButtonTexture:Texture;
		
		private var alertSingleButtonTextures:Scale3Textures;
		private var alertSingleButtonActiveTextures:Scale3Textures;
		private var alertFirstButtonTextures:Scale3Textures;
		private var alertMiddleButtonTextures:Scale3Textures;
		private var alertLastButtonTextures:Scale3Textures;
		private var alertBackgroundWhiteTextures:Scale9Textures;
		
		private var headerBottomBackgroundTextures:Scale3Textures;
		private var headerBackgroundRedTextures:Scale3Textures;
		private var headerSubBackgroundGreyTextures:Scale3Textures;
		
		private var subTabButtonNormalSkinTextures:Scale3Textures;
		private var subTabButtonActiveSkinTextures:Scale3Textures;
			
		private var bottomTabButtonNormalSkinTextures:Scale3Textures;
		private var bottomTabButtonActiveSkinTextures:Scale3Textures;
		private var listGridItemNormalSkinTextures:Scale3Textures;
		
		private var listLightGreySkin:Scale3Textures;
		private var listDarkGreySkin:Scale3Textures;
		private var sideMenuBackgroundSkin:Scale9Textures;
		private var whiteBackgroundSkin:Scale9Textures;
		private var sideMenuListItemBackgroundSkin:Scale9Textures;
		
		// icons
		
		private var itemStarFilledIconTexture:Texture;
		private var itemStarBorderIconTexture:Texture;
		private var itemDateIconTexture:Texture;
		private var itemUserSmallIconTexture:Texture;
		private var moreButtonIconTexture:Texture;
		private var _whiteBgAlpha:Scale3Textures;
		
		protected static function textRendererFactory():TextBlockTextRenderer  { return new TextBlockTextRenderer(); }
		protected static function textEditorFactory():StageTextTextEditor  { return new StageTextTextEditor(); }
		
		public function BasePhoneAppTheme(atlas:TextureAtlas)
		{
			this._atlas = atlas;
			
			this._scaleToDPI = true;
		}
		
		public function get atlas():TextureAtlas 
		{
			return _atlas;
		}
		
		override public function dispose():void
		{
			if (this._atlas)
			{
				this._atlas.texture.root.onRestore = null;
				
				this._atlas.dispose();
				this._atlas = null;
			}
			
			super.dispose();
		}
		
		protected function initialize():void
		{
			this.initializeScale();
			this.initializeDimensions();
			this.initializeFonts();
			this.initializeTextures();
			this.initializeGlobals();
			this.initializeStage();
			this.initializeStyleProviders();
		}
		
		protected function initializeStage():void
		{
			Starling.current.stage.color = GlobalSettings.PRIMARY_BACKGROUND_COLOR;
			Starling.current.nativeStage.color = GlobalSettings.PRIMARY_BACKGROUND_COLOR;
		}
		
		protected function initializeGlobals():void
		{
			FeathersControl.defaultTextRendererFactory = textRendererFactory;
			FeathersControl.defaultTextEditorFactory = textEditorFactory;
			
			PopUpManager.overlayFactory = popUpOverlayFactory;
			Callout.stagePadding = this.smallPaddingSize;
		}
		
		protected static function popUpOverlayFactory():DisplayObject
		{
			var quad:Quad = new Quad(100, 100, 0x000000);
			quad.alpha = 0.65;
			return quad;
		}		
		
		protected function initializeScale():void
		{
			var starling:Starling = Starling.current;
			
			var nativeScaleFactor:Number = 1;
			
			if (starling.supportHighResolutions) nativeScaleFactor = starling.nativeStage.contentsScaleFactor;
			
			var scaledDPI:int = DeviceCapabilities.dpi / (starling.contentScaleFactor / nativeScaleFactor);
			
			this._originalDPI = scaledDPI;
			
			if (this._scaleToDPI) this._originalDPI = ORIGINAL_DPI_IPHONE_RETINA;
			
			if (DeviceResolution.scaleByWidth()) 
				this._scale = DeviceResolution.getDeviceScale();
			else
				this._scale = scaledDPI / this._originalDPI;
			
			this._stageTextScale = this._scale / nativeScaleFactor;
		}
		
		protected function initializeDimensions():void
		{
			this.buttonLoginWidth = Math.round(490 * this._scale);
			this.buttonLoginHeight = Math.round(78 * this._scale);
			
			this.standardTextInputWidth = Math.round(544 * this._scale);
			this.standardTextInputHeight = Math.round(88 * this._scale);
			this.xSmallPaddingSize = Math.round(6 * this._scale);
			this.smallPaddingSize = Math.round(12 * this._scale);
			this.mediumPaddingSize = Math.round(18 * this._scale);
			this.regularPaddingSize = Math.round(30 * this._scale);
			this.largePaddingSize = Math.round(50 * this._scale);
			
			this.screenTopPaddingSize = Math.round(4 * this._scale);
			this.screenLeftPaddingSize = Math.round(40 * this._scale);
			this.screenBottomPaddingSize = Math.round(40 * this._scale);
			this.screenRightPaddingSize = Math.round(40 * this._scale);
			
			this.standardHeaderHeight = Math.round(88 * this._scale);
			this.standardSubHeaderHeight = Math.round(74 * this._scale);
			this.bottomHeaderHeight = Math.round(94 * this._scale);
		}
		
		protected function initializeFonts():void
		{
			this._fontLeading = Math.round(6 * this._scale);
			this._xSmallFontSize = Math.round(14 * this._scale);
			this._smallFontSize = Math.round(18 * this._scale);
			this._smallMediumFontSize = Math.round(22 * this._scale);
			this._mediumFontSize = Math.round(24 * this._scale);
			this._regularFontSize = Math.round(28 * this._scale);
			this._largeFontSize = Math.round(35 * this._scale);
			this._xLargeFontSize = Math.round(56 * this._scale);
			this._inputFontSize = Math.round(28 * this._stageTextScale);
			this._touchSize = Math.round(88 * this._scale);
			
			this._regularFontDescription = new FontDescription(FONT_NAME, FontWeight.NORMAL, FontPosture.NORMAL, FontLookup.EMBEDDED_CFF, RenderingMode.CFF, CFFHinting.NONE);
			this._boldFontDescription = new FontDescription(FONT_NAME, FontWeight.BOLD, FontPosture.NORMAL, FontLookup.EMBEDDED_CFF, RenderingMode.CFF, CFFHinting.NONE);
			
			this.inputUIElementFormat = new ElementFormat(this._regularFontDescription, this._regularFontSize, GlobalSettings.DARK_TEXT_COLOR);
			this.inputLightUIElementFormat = new ElementFormat(this._regularFontDescription, this._regularFontSize, GlobalSettings.LIGHT_GREY_TEXT_COLOR);
			this.lightUIElementFormat = new ElementFormat(this._regularFontDescription, this._mediumFontSize, GlobalSettings.LIGHT_GREY_TEXT_COLOR);
			this.buttonLoginUIElementFormat = new ElementFormat(this._boldFontDescription, this._largeFontSize, GlobalSettings.DARK_TEXT_COLOR);
			this.headerLightElementFormat = new ElementFormat(this._regularFontDescription, this._regularFontSize, GlobalSettings.WHITE_TEXT_COLOR);
			this.headerLightBoldElementFormat = new ElementFormat(this._boldFontDescription, this._regularFontSize, GlobalSettings.WHITE_TEXT_COLOR);
			this.headerDisabledLightElementFormat = new ElementFormat(this._regularFontDescription, this._mediumFontSize, 0x2b7978);
			this.mediumLightElementFormat = new ElementFormat(this._regularFontDescription, this._mediumFontSize, GlobalSettings.WHITE_TEXT_COLOR);
			this.lightElementFormat = new ElementFormat(this._regularFontDescription, this._regularFontSize, GlobalSettings.WHITE_TEXT_COLOR);
			this.regularBoldDarkElementFormat = new ElementFormat(this._boldFontDescription, this._regularFontSize, GlobalSettings.DARK_TEXT_COLOR);
			this.smallDarkElementFormat = new ElementFormat(this._regularFontDescription, this._smallMediumFontSize, GlobalSettings.DARK_TEXT_COLOR);
		}
		
		public function getElementFormat(fontWeight:String = "regular", fontSize:int = 18, fontColor:uint = 0x000000):ElementFormat
		{
			var fontDesc:FontDescription = (fontWeight == "bold") ? _boldFontDescription : _regularFontDescription;
			
			var elementFormat:ElementFormat = new ElementFormat(fontDesc, fontSize, fontColor);
			
			return elementFormat;
		}
		
		protected function initializeTextures():void
		{
			// this._loadingTexture = this.getTexture("singleview_place_holder");
			
			this.headerBackgroundRedTextures = new Scale3Textures(this._atlas.getTexture("header/header_background"), 2, 4);
			this.headerSubBackgroundGreyTextures = new Scale3Textures(this._atlas.getTexture("subheaders/subheader_background"), 2, 4);
			this.headerBottomBackgroundTextures = new Scale3Textures(this._atlas.getTexture("bottommenu/bottommenu_grey_first"), 2, 4);
			
			var textInputSkinTexture:Texture = this._atlas.getTexture("inputfield_linev2");
			this.textInputSkinTextures = new Scale9Textures(textInputSkinTexture, TEXTINPUT_SCALE9_GRID);

			var textActiveInputSkinTexture:Texture = this._atlas.getTexture("inputfield_active_linev2");
			this.textActiveInputSkinTextures = new Scale9Textures(textActiveInputSkinTexture, TEXTINPUT_SCALE9_GRID);
			
			var buttonLoginSkinTexture:Texture = this._atlas.getTexture("buttons/red_circle_button");
			this.buttonLoginSkinTextures = new Scale3Textures(buttonLoginSkinTexture, BUTTONLOGIN_SCALE3_GRID.x, BUTTONLOGIN_SCALE3_GRID.width);
			
			var buttonFBLoginSkinTexture:Texture = this._atlas.getTexture("buttons/facebook_button");
			this.buttonFBLoginSkinTextures = new Scale3Textures(buttonFBLoginSkinTexture, BUTTONLOGIN_SCALE3_GRID.x, BUTTONLOGIN_SCALE3_GRID.width);
			
			this.greyHeaderBackButtonTexture = this._atlas.getTexture("back_grey_button");
			this.greyHeaderNextButtonTexture = this._atlas.getTexture("next_grey_button");
			this.greyHeaderNextDisabledButtonTexture = this._atlas.getTexture("next_grey_disabled_button");
			
			this.alertSingleButtonTextures = new Scale3Textures(this._atlas.getTexture("alert/alert_single_button_normal"), 40, 8);
			this.alertSingleButtonActiveTextures = new Scale3Textures(this._atlas.getTexture("alert/alert_single_button_active"), 40, 8);
			this.alertFirstButtonTextures = new Scale3Textures(this._atlas.getTexture("alert/alert_first_button_normal"), 46, 52);
			this.alertMiddleButtonTextures = new Scale3Textures(this._atlas.getTexture("alert/alert_middle_button_normal"), 20, 20);
			this.alertLastButtonTextures = new Scale3Textures(this._atlas.getTexture("alert/alert_last_button_normal"), 20, 46);
			this.alertBackgroundWhiteTextures = new Scale9Textures(this._atlas.getTexture("alert/alert_background_white"), new Rectangle(40, 40, 66, 46));

			var subTabButtonNormalSkinTexture:Texture = this._atlas.getTexture("subheaders/tab_active_button");
			this.subTabButtonNormalSkinTextures = new Scale3Textures(subTabButtonNormalSkinTexture, 2, 4);
			
			var subTabButtonActiveSkinTexture:Texture = this._atlas.getTexture("subheaders/tab_normal_button");
			this.subTabButtonActiveSkinTextures = new Scale3Textures(subTabButtonActiveSkinTexture, 2, 4);
			
			var bottomTabButtonNormalSkinTexture:Texture = this._atlas.getTexture("bottommenu/bottommenu_grey_middle");
			this.bottomTabButtonNormalSkinTextures = new Scale3Textures(bottomTabButtonNormalSkinTexture, 2, 4);
			
			var bottomTabButtonActiveSkinTexture:Texture = this._atlas.getTexture("bottommenu/bottommenu_blue_active");
			this.bottomTabButtonActiveSkinTextures = new Scale3Textures(bottomTabButtonActiveSkinTexture, 2, 4);
			
			var listGridItemNormalSkinTexture:Texture = this._atlas.getTexture("list/list_grid_background");
			this.listGridItemNormalSkinTextures = new Scale3Textures(listGridItemNormalSkinTexture, 4, 8);
			
			var listLightGreyBackgroundTexture:Texture = _atlas.getTexture("list/list_sidemenu_lightgrey_background");
			this.listLightGreySkin = new Scale3Textures(listLightGreyBackgroundTexture, 2, 4);
			
			var listDarkGreyBackgroundTexture:Texture = _atlas.getTexture("list/list_sidemenu_grey_background");
			this.listDarkGreySkin = new Scale3Textures(listDarkGreyBackgroundTexture, 2, 4);
			
			var sideMenuBackgroundTexture:Texture = this._atlas.getTexture("list/sidemenu_background");
			this.sideMenuBackgroundSkin = new Scale9Textures(sideMenuBackgroundTexture, new Rectangle(4, 4, 12, 12));
			
			var whiteBackgroundTexture:Texture = this._atlas.getTexture("list/white_background");
			this.whiteBackgroundSkin = new Scale9Textures(whiteBackgroundTexture, new Rectangle(4, 4, 12, 12));
			
			var sideMenuListBackgroundTexture:Texture = this._atlas.getTexture("list/list_sidemenu_grey_background");
			this.sideMenuListItemBackgroundSkin = new Scale9Textures(sideMenuListBackgroundTexture, new Rectangle(4, 4, 4, 100));
			
			// icons
			
			itemStarFilledIconTexture = this._atlas.getTexture("header/star_blue_header_icon");
			itemDateIconTexture = this._atlas.getTexture("icons/item_date_icon");
			itemUserSmallIconTexture = this._atlas.getTexture("icons/user_xsmall_icon");
			moreButtonIconTexture = this._atlas.getTexture("header/more_header_normal_icon");
			
			_whiteBgAlpha = new Scale3Textures(this._atlas.getTexture("list/sidemenu_background"), 2, 2);
		}
		
		protected function initializeStyleProviders():void
		{
			// callout
			// this.getStyleProviderForClass(Callout).defaultStyleFunction = this.setCalloutStyles;
			
			// alert Alert.DEFAULT_CHILD_STYLE_NAME_BUTTON_GROUP
			this.getStyleProviderForClass(Alert).defaultStyleFunction = this.setAlertStyles;
			this.getStyleProviderForClass(ButtonGroup).setFunctionForStyleName(GlobalSettings.ALERT_BUTTONGROUP, this.setAlertButtonGroupStyles);
			this.getStyleProviderForClass(Alert).setFunctionForStyleName(GlobalSettings.ALERT_SINGLE, this.setAlertSingleButtonStyles);
			this.getStyleProviderForClass(ButtonGroup).setFunctionForStyleName(GlobalSettings.ALERT_SINGLEBUTTONGROUP, this.setAlertSingleButtonGroupStyles);
			this.getStyleProviderForClass(Button).setFunctionForStyleName(GlobalSettings.ALERT_SINGLECUSTOMBUTTON, this.setAlertSingleCustomButtonStyles);
			this.getStyleProviderForClass(Button).setFunctionForStyleName(GlobalSettings.ALERT_FIRSTBUTTONGROUP, this.setAlertFirstButtonGroupButtonStyles);
			this.getStyleProviderForClass(Button).setFunctionForStyleName(GlobalSettings.ALERT_MIDDLEBUTTONGROUP, this.setAlertMiddleButtonGroupButtonStyles);
			this.getStyleProviderForClass(Button).setFunctionForStyleName(GlobalSettings.ALERT_LASTBUTTONGROUP, this.setAlertLastButtonGroupButtonStyles);
			this.getStyleProviderForClass(Header).setFunctionForStyleName(Alert.DEFAULT_CHILD_STYLE_NAME_HEADER, this.setHeaderWithoutBackgroundStyles);
			this.getStyleProviderForClass(TextBlockTextRenderer).setFunctionForStyleName(Alert.DEFAULT_CHILD_STYLE_NAME_MESSAGE, this.setAlertMessageTextRendererStyles);
			
			// text input
			this.getStyleProviderForClass(TextInput).defaultStyleFunction = this.setTextInputStyles;
			this.getStyleProviderForClass(TextInput).setFunctionForStyleName(GlobalSettings.INPUTPASSWORD, this.setPasswordTextInputStyles);
			this.getStyleProviderForClass(TextInput).setFunctionForStyleName(GlobalSettings.INPUTCOMMENTS, this.setTextInputMultilineStyles);
			this.getStyleProviderForClass(TextInput).setFunctionForStyleName(GlobalSettings.INPUTSEARCH, this.setSearchTextInputStyles);
			this.getStyleProviderForClass(Button).setFunctionForStyleName(GlobalSettings.SHOWPASSWORDBUTTON, this.setShowPasswordToggleButton);
			this.getStyleProviderForClass(Button).setFunctionForStyleName(GlobalSettings.FORGOTPASSWORDBUTTON, this.setForgotPasswordButton);
			
			// panel screen
			this.getStyleProviderForClass(PanelScreen).defaultStyleFunction = this.setPanelScreenStyles;
			this.getStyleProviderForClass(Panel).setFunctionForStyleName(GlobalSettings.NOSCROLL_PANEL, this.setNoScrollPanelStyles);
			this.getStyleProviderForClass(Panel).setFunctionForStyleName(GlobalSettings.HOME_PANEL, this.setHomePanelScreenStyles);
			this.getStyleProviderForClass(Header).setFunctionForStyleName(GlobalSettings.HOME_MAINHEADER, this.setHomeHeaderStyles);
			this.getStyleProviderForClass(Panel).setFunctionForStyleName(GlobalSettings.HOME_MAINBOTTOMHEADER, this.setHomeBottomPanelStyles);
			this.getStyleProviderForClass(Panel).setFunctionForStyleName(GlobalSettings.SIDEMENUPANEL, this.setSideMenuPanelStyles);
			this.getStyleProviderForClass(Panel).setFunctionForStyleName(GlobalSettings.SIDEUSERPICPANEL, this.setSideUserPicPanelStyles);
			this.getStyleProviderForClass(Panel).setFunctionForStyleName(GlobalSettings.CAMUPLOADPANEL, this.setCamUploadPanelStyles);
			
			// header
			this.getStyleProviderForClass(Header).setFunctionForStyleName(GlobalSettings.LOGIN_HEADER, this.setLoginHeaderStyles);
			this.getStyleProviderForClass(Header).setFunctionForStyleName(GlobalSettings.LOGIN_FOOTER, this.setLoginFooterStyles);
			this.getStyleProviderForClass(Header).setFunctionForStyleName(GlobalSettings.CREATEUSER_HEADER, this.setCreateUserHeaderStyles);
			this.getStyleProviderForClass(Header).setFunctionForStyleName(GlobalSettings.HOME_SUBHEADER, this.setHomeSubHeaderStyles);
			this.getStyleProviderForClass(Header).setFunctionForStyleName(GlobalSettings.SIDEUSERSUBHEADERPANEL, this.setSideMenuSubHeaderStyles);
			this.getStyleProviderForClass(Header).setFunctionForStyleName(GlobalSettings.CAMUPLOADPANELHEADER, this.setCamUploadTopHeaderStyles);
			this.getStyleProviderForClass(Header).setFunctionForStyleName(GlobalSettings.CAMUPLOADPANELBOTTOM, this.setCamUploadBottomHeaderStyles);
			
			// button
			this.getStyleProviderForClass(Button).setFunctionForStyleName(GlobalSettings.BUTTON_LOGIN, this.setLoginButtonStyles);
			this.getStyleProviderForClass(Button).setFunctionForStyleName(GlobalSettings.BUTTON_FACEBOOKLOGIN, this.setLoginFacebookButtonStyles);
			this.getStyleProviderForClass(Button).setFunctionForStyleName(GlobalSettings.BUTTON_GREY_BACK_HEADER, this.setBackGreyHeaderButtonStyles);
			this.getStyleProviderForClass(Button).setFunctionForStyleName(GlobalSettings.BUTTON_GREY_NEXT_HEADER, this.setNextGreyHeaderButtonStyles);
			this.getStyleProviderForClass(Button).setFunctionForStyleName(GlobalSettings.MAINHEADER_BUTTON, this.setMainHeaderButtonStyles);

			// tab bar 
			this.getStyleProviderForClass(TabBar).setFunctionForStyleName( GlobalSettings.HOME_MAINBOTTOMTAB, setBottomTabBarStyles );			
			this.getStyleProviderForClass(TabBar).setFunctionForStyleName( GlobalSettings.SUB_HEADERTAB, setSubTabBarStyles );			
			
			// lists
			// this.getStyleProviderForClass(List).defaultStyleFunction = this.setHomeListGridStyles;
			// the picker list has a custom item renderer name defined by the theme
			this.getStyleProviderForClass(List).setFunctionForStyleName(GlobalSettings.HOMEGRIDLIST, this.setHomeListGridStyles);
			this.getStyleProviderForClass(List).setFunctionForStyleName(GlobalSettings.HOMESINGLEVIEWLIST, this.setHomeListSingleViewStyles);
			this.getStyleProviderForClass(DefaultListItemRenderer).setFunctionForStyleName(GlobalSettings.HOMEGRIDITEMLIST, this.setHomeListItemRendererStyles);
			this.getStyleProviderForClass(LayoutGroupListItemRenderer).setFunctionForStyleName(GlobalSettings.HOMESINGLEITEMLIST, this.setSingleViewListItemRendererStyles);
			
			// home list item button
			this.getStyleProviderForClass(Button).setFunctionForStyleName(GlobalSettings.MOREBUTTON, this.setMoreButtonStyles);
			this.getStyleProviderForClass(Button).setFunctionForStyleName(GlobalSettings.HOMEITEMSTARBUTTON, this.setSingleViewListItemStarButtonStyles);
			this.getStyleProviderForClass(Button).setFunctionForStyleName(GlobalSettings.HOMEITEMDATEBUTTON, this.setSingleViewListItemDateButtonStyles);
			this.getStyleProviderForClass(Label).setFunctionForStyleName(GlobalSettings.HOMEITEMCOMMENTS, this.setSingleViewListItemCommentsStyles);
			this.getStyleProviderForClass(Panel).setFunctionForStyleName(GlobalSettings.HOMEITEMBOTTOMPANEL, this.setSingleViewListItemAccessoryBackgroundStyles);
			
			this.getStyleProviderForClass(List).setFunctionForStyleName(GlobalSettings.SIDEMENULIST, this.setSideMenuListStyles);
			this.getStyleProviderForClass(DefaultListItemRenderer).setFunctionForStyleName(GlobalSettings.SIDEMENULISTITEM, this.setSideMenuListItemRendererStyles);
			this.getStyleProviderForClass(DefaultListItemRenderer).setFunctionForStyleName(GlobalSettings.SETTINGSLISTITEM, this.setSettingsListItemRendererStyles);
			
			this.getStyleProviderForClass(List).setFunctionForStyleName(GlobalSettings.OVERLAYPANELLIST, this.setOverlayPanelStyles);
			this.getStyleProviderForClass(Panel).setFunctionForStyleName(GlobalSettings.OVERLAYPLAINPANEL, this.setOverlayPlainPanelStyles);
			
			// labels
			this.getStyleProviderForClass(Label).setFunctionForStyleName(GlobalSettings.LABEL_30SIZE_WHITE, this.setLabel30WhiteStyles);
			this.getStyleProviderForClass(Label).setFunctionForStyleName(GlobalSettings.LABEL_22SIZE_WHITE, this.setLabel22WhiteStyles);
			this.getStyleProviderForClass(Label).setFunctionForStyleName(GlobalSettings.LABEL_22SIZE_DARKGREY, this.setLabel22DarkGreyStyles);
			this.getStyleProviderForClass(Label).setFunctionForStyleName(GlobalSettings.LABEL_18SIZE_DARKGREY, this.setLabel18DarkGreyStyles);
			
		}	
		
		//-------------------------
		// Label
		//-------------------------
		
		
		protected function setLabel18DarkGreyStyles(label:Label):void
		{
			label.textRendererProperties.elementFormat = this.smallDarkElementFormat;
		}
		
		protected function setLabel22DarkGreyStyles(label:Label):void
		{
			label.textRendererProperties.elementFormat = this.smallDarkElementFormat;
		}
		
		protected function setLabel30WhiteStyles(label:Label):void
		{
			label.textRendererProperties.elementFormat = this.headerLightElementFormat;
		}
		
		protected function setLabel22WhiteStyles(label:Label):void
		{
			label.textRendererProperties.elementFormat = this.mediumLightElementFormat;
		}		
		
		//-------------------------
		// List
		//-------------------------
		
		private function setHomeListGridStyles(list:List):void 
		{
			var tileLayout:TiledRowsLayout = new TiledRowsLayout();
			tileLayout.horizontalAlign = TiledRowsLayout.TILE_HORIZONTAL_ALIGN_JUSTIFY;
			tileLayout.verticalAlign = TiledRowsLayout.VERTICAL_ALIGN_TOP;
			tileLayout.paging = TiledRowsLayout.PAGING_NONE;
			
			// list.layout = tileLayout;
		}

		private function setHomeListSingleViewStyles(list:List):void 
		{
			var tileLayout:TiledRowsLayout = new TiledRowsLayout();
			tileLayout.horizontalAlign = TiledRowsLayout.TILE_HORIZONTAL_ALIGN_JUSTIFY;
			tileLayout.verticalAlign = TiledRowsLayout.VERTICAL_ALIGN_TOP;
			tileLayout.paging = TiledRowsLayout.PAGING_NONE;
			
			list.layout = tileLayout;			
		}
			
		private function setListVerticalLayout(list:List):void
		{
			var verticalLayout:VerticalLayout = new VerticalLayout();
			verticalLayout.horizontalAlign = VerticalLayout.HORIZONTAL_ALIGN_JUSTIFY;
			verticalLayout.verticalAlign = VerticalLayout.VERTICAL_ALIGN_TOP;
			verticalLayout.gap = 8 * _scale;
			verticalLayout.padding = 8 * _scale;
			verticalLayout.useVirtualLayout = true;
			
			list.layout = verticalLayout;		
		}
		
		protected function setSideMenuListStyles(list:List):void
		{
			this.setListVerticalLayout(list);
		}
		
		protected function setOverlayPanelStyles(list:List):void
		{
			this.setListVerticalLayout(list);
			
			list.backgroundSkin = new Scale9Image(this.whiteBackgroundSkin, _scale);
		}

		protected function setOverlayPlainPanelStyles(panel:Panel):void
		{
			panel.backgroundSkin = new Scale9Image(this.whiteBackgroundSkin, _scale);
		}
		
		
		protected function setSettingsListItemRendererStyles(renderer:BaseDefaultItemRenderer):void
		{
			var skinSelector:SmartDisplayObjectStateValueSelector = new SmartDisplayObjectStateValueSelector();
			skinSelector.defaultValue = this.listLightGreySkin;
			skinSelector.defaultSelectedValue = this.listLightGreySkin;
			skinSelector.setValueForState(this.listLightGreySkin, Button.STATE_DOWN, false);
			skinSelector.displayObjectProperties =
			{
				width: 10 * this.scale,
				height: 108 * this.scale,
				textureScale: this.scale
			};
			renderer.stateToSkinFunction = skinSelector.updateValue;
			
			// renderer.defaultSkin = new Scale3Image(this.listLightGreySkin, _scale);
			renderer.defaultLabelProperties.elementFormat = this.inputLightUIElementFormat;
			renderer.horizontalAlign = Button.HORIZONTAL_ALIGN_LEFT;
			renderer.paddingTop = this.smallPaddingSize;
			renderer.paddingBottom = this.smallPaddingSize;
			renderer.paddingLeft = this.smallPaddingSize;
			renderer.paddingRight = this.smallPaddingSize;
			renderer.gap = this.largePaddingSize;
			renderer.minGap = 0;
			renderer.iconPosition = Button.ICON_POSITION_LEFT;
			renderer.accessoryGap = Number.POSITIVE_INFINITY;
			renderer.minAccessoryGap = this.smallPaddingSize;
			renderer.accessoryPosition = BaseDefaultItemRenderer.ACCESSORY_POSITION_RIGHT;
			
			renderer.defaultLabelProperties.elementFormat = this.inputUIElementFormat;
			renderer.disabledLabelProperties.elementFormat = this.inputLightUIElementFormat;
			
			renderer.accessoryLoaderFactory = this.imageLoaderFactory;
			renderer.iconLoaderFactory = this.imageLoaderFactory;
		}
		
		protected function setSideMenuListItemRendererStyles(renderer:BaseDefaultItemRenderer):void
		{
			var skinSelector:SmartDisplayObjectStateValueSelector = new SmartDisplayObjectStateValueSelector();
			skinSelector.defaultValue = this.listDarkGreySkin;
			skinSelector.defaultSelectedValue = this.listDarkGreySkin;
			skinSelector.setValueForState(this.listDarkGreySkin, Button.STATE_DOWN, false);
			skinSelector.displayObjectProperties =
			{
				width: 10 * this.scale,
				height: 108 * this.scale,
				textureScale: this.scale
			};
			renderer.stateToSkinFunction = skinSelector.updateValue;
			
			// renderer.defaultSkin = new Scale3Image(this.listDarkGreySkin, _scale);
			renderer.defaultLabelProperties.elementFormat = this.inputLightUIElementFormat;
			renderer.horizontalAlign = Button.HORIZONTAL_ALIGN_LEFT;
			renderer.paddingTop = this.smallPaddingSize;
			renderer.paddingBottom = this.smallPaddingSize;
			renderer.paddingLeft = this.smallPaddingSize;
			renderer.paddingRight = this.smallPaddingSize;
			renderer.gap = this.largePaddingSize;
			renderer.minGap = 0;
			renderer.iconPosition = Button.ICON_POSITION_LEFT;
			renderer.accessoryGap = Number.POSITIVE_INFINITY;
			renderer.minAccessoryGap = this.smallPaddingSize;
			renderer.accessoryPosition = BaseDefaultItemRenderer.ACCESSORY_POSITION_RIGHT;
			
			renderer.defaultLabelProperties.elementFormat = this.headerLightElementFormat;
			renderer.disabledLabelProperties.elementFormat = this.headerDisabledLightElementFormat;
			
			renderer.accessoryLoaderFactory = this.imageLoaderFactory;
			renderer.iconLoaderFactory = this.imageLoaderFactory;
		}
		
		protected function setHomeListItemRendererStyles(renderer:BaseDefaultItemRenderer):void
		{
			/*var skinSelector:SmartDisplayObjectStateValueSelector = new SmartDisplayObjectStateValueSelector();
			skinSelector.defaultValue = this.listGridItemNormalSkinTextures;
			skinSelector.defaultSelectedValue = this.listGridItemNormalSkinTextures;
			skinSelector.setValueForState(this.listGridItemNormalSkinTextures, Button.STATE_DOWN, false);
			skinSelector.displayObjectProperties =
			{
				width: 16 * this.scale,
				height: 266 * this.scale,
				textureScale: this.scale
			};
			renderer.stateToSkinFunction = skinSelector.updateValue;*/

			renderer.defaultLabelProperties.elementFormat = this.regularBoldDarkElementFormat;
			renderer.downLabelProperties.elementFormat = this.regularBoldDarkElementFormat;
			renderer.defaultSelectedLabelProperties.elementFormat = this.regularBoldDarkElementFormat;
			renderer.disabledLabelProperties.elementFormat = this.regularBoldDarkElementFormat;

			// renderer.minWidth = 216 * _scale;
			// renderer.minHeight = 640 * _scale;
			renderer.verticalAlign = Button.VERTICAL_ALIGN_BOTTOM;
			renderer.horizontalAlign = Button.HORIZONTAL_ALIGN_LEFT;
			renderer.paddingTop = this.smallPaddingSize;
			renderer.paddingBottom = this.smallPaddingSize;
			//renderer.paddingLeft = this.smallPaddingSize;
			//renderer.paddingRight = this.smallPaddingSize;
			renderer.gap = 0;
			//renderer.minGap = 0;
			renderer.iconPosition = BaseDefaultItemRenderer.ICON_POSITION_MANUAL;
			renderer.accessoryGap = 0;// Number.POSITIVE_INFINITY;
			renderer.minAccessoryGap = this.smallPaddingSize;
			renderer.accessoryPosition = BaseDefaultItemRenderer.ACCESSORY_POSITION_RIGHT;

			renderer.accessoryLoaderFactory = this.imageLoaderFactory;
			renderer.iconLoaderFactory = this.imageLoaderFactory;
		}

		protected function setSingleViewListItemRendererStyles(renderer:SingleViewItemRenderer):void
		{
			renderer.paddingTop = this.smallPaddingSize;
			renderer.paddingRight = this.mediumPaddingSize;
			renderer.paddingLeft = this.mediumPaddingSize;
			renderer.paddingBottom = this.smallPaddingSize;
			renderer.gap = this.xSmallPaddingSize;
		}	
		
		private function setSingleViewListItemAccessoryBackgroundStyles(panel:Panel):void
		{
			panel.verticalScrollPolicy = Scroller.SCROLL_POLICY_OFF;
			panel.horizontalScrollPolicy = Scroller.SCROLL_POLICY_OFF;
			panel.height = 2 * _scale;
			panel.backgroundSkin = new Scale3Image(this._whiteBgAlpha, _scale);
			
		}
		
		private function setMoreButtonStyles(button:Button):void
		{
			var icon:Image = new Image(moreButtonIconTexture);
			icon.scaleX = icon.scaleY = _scale;

			button.defaultIcon = icon;
			button.horizontalAlign = Button.HORIZONTAL_ALIGN_CENTER;
			button.minTouchWidth = this.touchSize;
			button.minTouchHeight = this.touchSize;
		}			
		
		private function setSingleViewListItemStarButtonStyles(button:Button):void
		{
			button.horizontalAlign = Button.HORIZONTAL_ALIGN_LEFT;
			button.gap = this.smallPaddingSize * this._scale;
			
			var icon:Image = new Image(itemStarFilledIconTexture);
			icon.scaleX = icon.scaleY = _scale;

			button.defaultIcon = icon;
			button.iconPosition = Button.ICON_POSITION_LEFT;
			// button.paddingLeft = this.mediumPaddingSize * this._scale;
			button.minTouchWidth = this.touchSize;
			button.minTouchHeight = this.touchSize;
			button.defaultLabelProperties.elementFormat = this.smallDarkElementFormat;
		}		
		
		protected function setSingleViewListItemCommentsStyles(label:Label):void
		{
			label.textRendererProperties.elementFormat = this.smallDarkElementFormat;
		}
		
		private function setSingleViewListItemDateButtonStyles(button:Button):void
		{
			button.horizontalAlign = Button.HORIZONTAL_ALIGN_LEFT;
			button.gap = this.smallPaddingSize * this._scale;
			
			var icon:Image = new Image(itemDateIconTexture);
			icon.scaleX = icon.scaleY = _scale;

			button.defaultIcon = icon;
			button.iconPosition = Button.ICON_POSITION_LEFT;
			//button.paddingLeft = this.mediumPaddingSize * this._scale;
			//button.paddingRight = this.mediumPaddingSize * this._scale;
			button.minTouchWidth = this.touchSize;
			button.minTouchHeight = this.touchSize;
			button.defaultLabelProperties.elementFormat = this.smallDarkElementFormat;
		}
	
		protected function iconLoaderFactory():ImageLoader
		{
			var image:ImageLoader = new ImageLoader();
			image.textureScale = this.scale;
			image.textureCache = new TextureCache(50);
			return image;
		}		
		
		protected function imageLoaderFactory():ImageLoader
		{
			var image:ImageLoader = new ImageLoader();
			image.textureScale = this.scale;
			return image;
		}
		
		protected function setItemRendererAccessoryLabelRendererStyles(renderer:TextBlockTextRenderer):void
		{
			renderer.elementFormat = this.lightElementFormat;
		}

		protected function setItemRendererIconLabelStyles(renderer:TextBlockTextRenderer):void
		{
			renderer.elementFormat = this.lightElementFormat;
		}		
		
		//-------------------------
		// TabBar
		//-------------------------

		private function setSubTabBarStyles( tabBar:TabBar ):void
		{
			tabBar.distributeTabSizes = true;
			tabBar.direction = TabBar.DIRECTION_HORIZONTAL;
			tabBar.tabFactory = toggleSubTabGroupFactory;
			tabBar.lastTabFactory = toggleSubTabLastFactory;
			tabBar.horizontalAlign = TabBar.HORIZONTAL_ALIGN_CENTER;
			tabBar.verticalAlign = TabBar.VERTICAL_ALIGN_MIDDLE;
			tabBar.gap = 0;
		}

		private function toggleSubTabLastFactory():ToggleButton
		{
			var toggleButton:ToggleButton = new ToggleButton();
			
			var normalSkinTexture:Texture = this._atlas.getTexture("subheaders/tab_last_active_button");
			var normalSkinTextures:Scale3Textures = new Scale3Textures(normalSkinTexture, 2, 4);
			toggleButton.defaultSkin = new Scale3Image(normalSkinTextures, _scale);

			var activeSkinTexture:Texture = this._atlas.getTexture("subheaders/tab_last_normal_button");
			var activeSkinTextures:Scale3Textures = new Scale3Textures(activeSkinTexture, 2, 4);
			toggleButton.downSkin = new Scale3Image(activeSkinTextures, _scale);
			toggleButton.defaultSelectedSkin = new Scale3Image(activeSkinTextures, _scale);;

			toggleButton.padding = 0;
			toggleButton.gap = 0;
			toggleButton.minGap = 0;
			toggleButton.minTouchWidth = toggleButton.minTouchHeight = Math.round(74 * this._scale);	
			toggleButton.defaultLabelProperties.elementFormat = new ElementFormat(this._regularFontDescription, this._smallMediumFontSize, GlobalSettings.LIGHT_GREY_TEXT_COLOR);
			toggleButton.defaultSelectedLabelProperties.elementFormat = new ElementFormat(this._regularFontDescription, this._smallMediumFontSize, GlobalSettings.DARK_TEXT_COLOR);
			return toggleButton;
		}
		
		private function toggleSubTabGroupFactory():ToggleButton
		{
			var toggleButton:ToggleButton = new ToggleButton();
			var defaultSkin:Scale3Image = new Scale3Image(this.subTabButtonNormalSkinTextures, _scale);
			toggleButton.defaultSkin = defaultSkin;
			
			var downSkin:Scale3Image = new Scale3Image(this.subTabButtonActiveSkinTextures, _scale);
			toggleButton.downSkin = downSkin;
			
			var defaultSelectedSkin:Scale3Image = new Scale3Image(this.subTabButtonActiveSkinTextures, this.scale);
			toggleButton.defaultSelectedSkin = defaultSelectedSkin;
			
			toggleButton.padding = 0;
			toggleButton.gap = 0;
			toggleButton.minGap = 0;
			toggleButton.minTouchWidth = toggleButton.minTouchHeight = Math.round(74 * this._scale);		
			toggleButton.defaultLabelProperties.elementFormat = new ElementFormat(this._regularFontDescription, this._smallMediumFontSize, GlobalSettings.LIGHT_GREY_TEXT_COLOR);
			toggleButton.defaultSelectedLabelProperties.elementFormat = new ElementFormat(this._regularFontDescription, this._smallMediumFontSize, GlobalSettings.DARK_TEXT_COLOR);

			return toggleButton;
		}
		
		private function setBottomTabBarStyles( tabBar:TabBar ):void
		{
			tabBar.distributeTabSizes = true;
			tabBar.direction = TabBar.DIRECTION_HORIZONTAL;
			tabBar.tabFactory = toggleTabGroupFactory;
			tabBar.horizontalAlign = TabBar.HORIZONTAL_ALIGN_CENTER;
			tabBar.verticalAlign = TabBar.VERTICAL_ALIGN_MIDDLE;
			tabBar.gap = 0;
		}

		private function toggleTabGroupFactory():ToggleButton
		{
			var toggleButton:ToggleButton = new ToggleButton();
			this.toggleBottomTabButton( toggleButton );
			
			return toggleButton;
		}		
		
		private function toggleBottomTabButton(tab:ToggleButton):void
		{
			var defaultSkin:Scale3Image = new Scale3Image(this.bottomTabButtonNormalSkinTextures, _scale);
			tab.defaultSkin = defaultSkin;

			var downSkin:Scale3Image = new Scale3Image(this.bottomTabButtonActiveSkinTextures, _scale);
			tab.downSkin = downSkin;

			var defaultSelectedSkin:Scale3Image = new Scale3Image(this.bottomTabButtonActiveSkinTextures, this.scale);
			tab.defaultSelectedSkin = defaultSelectedSkin;

			var disabledSkin:Scale3Image = new Scale3Image(this.bottomTabButtonNormalSkinTextures, this.scale);
			tab.disabledSkin = disabledSkin;

			tab.padding = 0;
			tab.gap = 0;
			tab.minGap = 0;
			tab.minTouchWidth = tab.minTouchHeight = Math.round(94 * this._scale);			
		}
		
		//-------------------------
		// Alert
		//-------------------------

		protected function setAlertStyles(alert:Alert):void
		{
			this.setScrollerStyles(alert);

			var backgroundSkin:Scale9Image = new Scale9Image(this.alertBackgroundWhiteTextures, this.scale);
			alert.backgroundSkin = backgroundSkin;
			
			alert.paddingTop = this.regularPaddingSize;
			alert.paddingRight = this.mediumPaddingSize;
			alert.paddingBottom = this.regularPaddingSize;
			alert.paddingLeft = this.mediumPaddingSize;
			alert.gap = this.smallPaddingSize;
			alert.minWidth = alert.maxWidth = 458 * this.scale;
			alert.maxHeight = 350 * this.scale;
			alert.customButtonGroupStyleName = GlobalSettings.ALERT_BUTTONGROUP;
		}		
		
		protected function setAlertSingleButtonStyles(alert:Alert):void
		{
			this.setScrollerStyles(alert);

			var backgroundSkin:Scale9Image = new Scale9Image(this.alertBackgroundWhiteTextures, this.scale);
			alert.backgroundSkin = backgroundSkin;
			
			alert.paddingTop = this.regularPaddingSize;
			alert.paddingRight = this.mediumPaddingSize;
			alert.paddingBottom = this.regularPaddingSize;
			alert.paddingLeft = this.mediumPaddingSize;
			alert.gap = this.smallPaddingSize;
			alert.minWidth = alert.maxWidth = 458 * this.scale;
			alert.maxHeight = 330 * this.scale;
			alert.customButtonGroupStyleName = GlobalSettings.ALERT_SINGLEBUTTONGROUP;
		}			
		
		protected function setAlertSingleButtonGroupStyles(group:ButtonGroup):void
		{
			group.direction = ButtonGroup.DIRECTION_HORIZONTAL;
			group.horizontalAlign = ButtonGroup.HORIZONTAL_ALIGN_CENTER;
			group.verticalAlign = ButtonGroup.VERTICAL_ALIGN_JUSTIFY;
			group.distributeButtonSizes = true;
			group.gap = 2 * this._scale;
			group.padding = 2 * this._scale;
			group.customButtonStyleName = GlobalSettings.ALERT_SINGLECUSTOMBUTTON
		}			
		
		protected function setAlertButtonGroupStyles(group:ButtonGroup):void
		{
			group.direction = ButtonGroup.DIRECTION_HORIZONTAL;
			group.horizontalAlign = ButtonGroup.HORIZONTAL_ALIGN_CENTER;
			group.verticalAlign = ButtonGroup.VERTICAL_ALIGN_JUSTIFY;
			group.distributeButtonSizes = true;
			group.gap = 2 * this._scale;
			group.padding = 2 * this._scale;
			group.customFirstButtonStyleName = GlobalSettings.ALERT_FIRSTBUTTONGROUP
			group.customButtonStyleName = GlobalSettings.ALERT_MIDDLEBUTTONGROUP
			group.customLastButtonStyleName = GlobalSettings.ALERT_LASTBUTTONGROUP
		}		
		
		private function setBaseAlertButtonStyles(button:Button):void
		{
			button.defaultLabelProperties.elementFormat = this.lightElementFormat;
			button.paddingTop = this.smallPaddingSize;
			button.paddingBottom = this.smallPaddingSize;
			button.paddingLeft = this.smallPaddingSize;
			button.paddingRight = this.smallPaddingSize;
			button.gap = this.smallPaddingSize;
			button.minGap = this.smallPaddingSize;
			button.minTouchWidth = button.minHeight;
			button.minTouchHeight = button.minHeight;		
		
		}
		
		protected function setAlertSingleCustomButtonStyles(button:Button):void
		{
			var defaultSkin:Scale3Image = new Scale3Image(this.alertSingleButtonTextures, this.scale);
			
			button.defaultSkin = defaultSkin;
			
			var downSkin:Scale3Image = new Scale3Image(this.alertSingleButtonActiveTextures, this.scale);
			
			button.downSkin = downSkin;
			
			this.setBaseAlertButtonStyles(button);
		}		
			
		protected function setAlertFirstButtonGroupButtonStyles(button:Button):void
		{
			var backgroundSkin:Scale3Image = new Scale3Image(this.alertFirstButtonTextures, this.scale);
			
			button.defaultSkin = backgroundSkin;
			
			this.setBaseAlertButtonStyles(button);
		}
		
		protected function setAlertMiddleButtonGroupButtonStyles(button:Button):void
		{
			var backgroundSkin:Scale3Image = new Scale3Image(this.alertMiddleButtonTextures, this.scale);
			
			button.defaultSkin = backgroundSkin;
			
			this.setBaseAlertButtonStyles(button);
		}
		
		protected function setAlertLastButtonGroupButtonStyles(button:Button):void
		{
			var backgroundSkin:Scale3Image = new Scale3Image(this.alertLastButtonTextures, this.scale);
			
			button.defaultSkin = backgroundSkin;
			
			this.setBaseAlertButtonStyles(button);
		}		

		protected function setAlertMessageTextRendererStyles(renderer:TextBlockTextRenderer):void
		{
			renderer.textAlign = TextBlockTextRenderer.TEXT_ALIGN_CENTER;
			renderer.wordWrap = true;
			renderer.elementFormat = this.lightUIElementFormat;
		}		
		
		protected function setHeaderWithoutBackgroundStyles(header:Header):void
		{
			header.minWidth = this.regularPaddingSize;
			header.maxHeight = 50 * _scale;
			header.padding = this.regularPaddingSize;
			header.gap = this.smallPaddingSize;
			header.titleGap = this.smallPaddingSize;
			header.titleAlign = Button.VERTICAL_ALIGN_MIDDLE;
			
			header.titleProperties.elementFormat = this.regularBoldDarkElementFormat;
		}		
		
		//--------------------------
		// Text Input
		//--------------------------
		
		protected function setShowPasswordToggleButton(toggle:ToggleButton):void
		{
			var icon:Image = new Image(this._atlas.getTexture("icons/show_normal_pass_icon"));
			icon.scaleX = icon.scaleY = _scale;
			toggle.defaultIcon = icon;
			
			var iconActive:Image = new Image(this._atlas.getTexture("icons/show_active_pass_icon"));
			iconActive.scaleX = iconActive.scaleY = _scale;
			toggle.defaultSelectedIcon = iconActive;

			toggle.padding = 8 * _scale;
			toggle.width = 60 * _scale;
			toggle.height = 50 * _scale;
		}
		
		protected function setForgotPasswordButton(button:Button):void
		{
			button.defaultLabelProperties.elementFormat = new ElementFormat(this._boldFontDescription, this._smallMediumFontSize, GlobalSettings.BLUE_TEXT_COLOR);
			button.downLabelProperties.elementFormat = new ElementFormat(this._boldFontDescription, this._smallMediumFontSize, GlobalSettings.RED_TEXT_COLOR);
		
			button.height = 44 * _scale;
			button.minTouchHeight = 64 * _scale;
		}
		
		protected function setBaseTextInputStyles(input:TextInput):void
		{
			var skinSelector:SmartDisplayObjectStateValueSelector = new SmartDisplayObjectStateValueSelector();
			skinSelector.defaultValue = this.textInputSkinTextures;
			skinSelector.setValueForState(this.textInputSkinTextures, TextInput.STATE_DISABLED);
			skinSelector.setValueForState(this.textActiveInputSkinTextures, TextInput.STATE_FOCUSED);
			skinSelector.displayObjectProperties = {width: this.standardTextInputWidth, height: this.standardTextInputHeight, textureScale: this._scale};
			input.stateToSkinFunction = skinSelector.updateValue;
			
			input.minWidth = this.standardTextInputHeight;
			input.minHeight = this.standardTextInputHeight;
			input.minTouchWidth = this.touchSize;
			input.minTouchHeight = this.touchSize;
			input.gap = this.regularPaddingSize;
			input.padding = this.smallPaddingSize;
			
			input.textEditorProperties.fontFamily = "Helvetica";
			input.textEditorProperties.fontSize = this.inputFontSize;
			input.textEditorProperties.color = GlobalSettings.DARK_TEXT_COLOR;
			input.textEditorProperties.disabledColor = GlobalSettings.DARK_TEXT_COLOR;
			
			input.promptProperties.elementFormat = this.inputLightUIElementFormat;
		}
		
		protected function setTextInputStyles(input:TextInput):void
		{
			this.setBaseTextInputStyles(input);
		}
		
		protected function setPasswordTextInputStyles(input:TextInput):void
		{
			this.setBaseTextInputStyles(input);
			
			input.paddingRight = 66 * _scale;
		}
		
		private function setTextInputMultilineStyles( input:TextInput ):void
		{
			this.setBaseTextInputStyles( input );
			
			input.textEditorProperties.multiline = true;
			input.verticalAlign = TextInput.VERTICAL_ALIGN_TOP;
			input.paddingTop = 8;
			input.paddingBottom = 8;
		}		
		
		protected function setSearchTextInputStyles(input:TextInput):void
		{
			var normalSkinTexture:Texture = this._atlas.getTexture("inputfield_white_line");
			var normalSkinTextures:Scale3Textures = new Scale3Textures(normalSkinTexture, 2, 1);
			input.backgroundSkin = new Scale3Image(normalSkinTextures, _scale);

			var icon:Image = new Image(this._atlas.getTexture("icons/search_small_white_icon"));
			icon.scaleX = icon.scaleY = _scale;
			input.defaultIcon = icon;
			
			input.minWidth = Math.round(60 * this._scale);
			input.minHeight = Math.round(60 * this._scale);
			input.minTouchWidth = this.touchSize;
			input.minTouchHeight = this.touchSize;
			input.maxChars = 20;
			input.gap = this.regularPaddingSize;
			input.padding = this.smallPaddingSize;
			// input.paddingLeft = this.smallPaddingSize;
			// input.paddingRight = this.smallPaddingSize;
			
			input.textEditorProperties.fontFamily = "Helvetica";
			input.textEditorProperties.fontSize = this.inputFontSize;
			input.textEditorProperties.color = GlobalSettings.WHITE_TEXT_COLOR;
			input.textEditorProperties.textAlign = "left";
			
			input.promptProperties.textAlign = "left";
			input.promptProperties.elementFormat = headerLightBoldElementFormat;
		}
		
		//-------------------------
		// Shared
		//-------------------------
		
		protected function setScrollerStyles(scroller:Scroller):void
		{
			// scroller.horizontalScrollBarFactory = scrollBarFactory;
			// scroller.verticalScrollBarFactory = scrollBarFactory;
		}
		
		//-------------------------
		// PanelScreen
		//-------------------------
		
		protected function setPanelScreenStyles(screen:Panel):void
		{
			this.setScrollerStyles(screen);
		}
		
		protected function setNoScrollPanelStyles(panel:Panel):void
		{
			panel.verticalScrollPolicy = Scroller.SCROLL_POLICY_OFF;
			panel.horizontalScrollPolicy = Scroller.SCROLL_POLICY_OFF;
		}
		
		protected function setHomePanelScreenStyles(screen:Panel):void
		{
			
		}
		
		protected function setPanelScreenHeaderStyles(header:Header):void
		{
			this.setHeaderStyles(header);
			header.useExtraPaddingForOSStatusBar = true;
		}
		
		public function scrollBarFactory():SimpleScrollBar
		{
			return new SimpleScrollBar();
		}
		
		//-------------------------
		// Header
		//-------------------------
		
		protected function setHeaderStyles(header:Header):void
		{
			header.useExtraPaddingForOSStatusBar = true;
			header.minWidth = this.regularPaddingSize;
			header.minHeight = this.regularPaddingSize;
			header.padding = this.regularPaddingSize;
			header.gap = this.regularPaddingSize;
			header.titleGap = this.regularPaddingSize;
		}
		
		protected function setHomeHeaderStyles(header:Header):void
		{
			header.useExtraPaddingForOSStatusBar = true;
			
			header.paddingTop = this.xSmallPaddingSize;
			header.paddingBottom = this.xSmallPaddingSize;
			header.paddingLeft = this.regularPaddingSize;
			header.paddingRight = this.regularPaddingSize;
			header.gap = this.regularPaddingSize;
			header.titleGap = this.regularPaddingSize;
			// this.setHeaderStyles(header);
			
			var bgSkin:Scale3Image = new Scale3Image(this.headerBackgroundRedTextures, this.scale);
			//bgSkin.width = this.regularPaddingSize;
			//bgSkin.height = this.standardHeaderHeight;
			header.backgroundSkin = bgSkin;
			header.minHeight = this.standardHeaderHeight;
			header.verticalAlign = Header.VERTICAL_ALIGN_MIDDLE;
			header.titleProperties.elementFormat = this.headerLightBoldElementFormat;
		}
		
		protected function setHomeBottomPanelStyles(panel:Panel):void
		{
			panel.minWidth = this.regularPaddingSize;
			panel.minHeight = this.bottomHeaderHeight;
			panel.padding = 0;
			
			var bgSkin:Scale3Image = new Scale3Image(this.headerBottomBackgroundTextures, this.scale);
			bgSkin.width = this.regularPaddingSize;
			// bgSkin.height = this.bottomHeaderHeight;
			panel.backgroundSkin = bgSkin;
			panel.height = this.bottomHeaderHeight;
		}				
		
		protected function setSideUserPicPanelStyles(panel:Panel):void
		{
			var bg:Image = new Image(this._atlas.getTexture("userprofile_background"));
			bg.scaleX = bg.scaleY = _scale;
			
			panel.backgroundSkin = bg;
		}
		
		protected function setCamUploadPanelStyles(panel:Panel):void
		{
			panel.paddingTop = this.regularPaddingSize;
			panel.paddingLeft = this.smallPaddingSize;
			panel.paddingRight = this.smallPaddingSize;
			panel.paddingBottom = 0;
			
			panel.backgroundSkin = new Quad(10, 10, 0x333333);
		}
		
		protected function setSideMenuPanelStyles(panel:Panel):void
		{
			panel.paddingTop = this.regularPaddingSize;
			panel.paddingLeft = this.smallPaddingSize;
			panel.paddingRight = this.smallPaddingSize;
			panel.paddingBottom = 0;
			
			panel.backgroundSkin = new Scale9Image(sideMenuBackgroundSkin, _scale);
		}
		
		protected function setCamUploadHeaderStyles(header:Header):void
		{
			header.paddingTop = this.smallPaddingSize;
			header.paddingLeft = this.regularPaddingSize;
			header.paddingRight = this.regularPaddingSize;
			header.paddingBottom = this.smallPaddingSize;

			header.minHeight = this.standardHeaderHeight;
			header.verticalAlign = Header.VERTICAL_ALIGN_MIDDLE;
		}
		
		protected function setCamUploadTopHeaderStyles(header:Header):void
		{
			this.setCamUploadHeaderStyles(header);
			
			header.useExtraPaddingForOSStatusBar = true;
			header.titleProperties.elementFormat = this.headerLightBoldElementFormat;
		}
		
		protected function setCamUploadBottomHeaderStyles(header:Header):void
		{
			this.setCamUploadHeaderStyles(header);
		}		
		
		protected function setSideMenuSubHeaderStyles(header:Header):void
		{
			header.backgroundSkin = new Scale9Image(this.sideMenuListItemBackgroundSkin, _scale);
			header.paddingTop = this.regularPaddingSize;
			header.paddingLeft = this.smallPaddingSize;
			header.paddingRight = this.smallPaddingSize;
			header.paddingBottom = this.regularPaddingSize;
			
			header.height = 108 * _scale;
		}
		
		protected function setLoginHeaderStyles(header:Header):void
		{
			this.setHeaderStyles(header);
			
			var backgroundSkin:Quad = new Quad(this.regularPaddingSize, this.regularPaddingSize, GlobalSettings.LOGIN_HEADER_BACKGROUND_COLOR);
			backgroundSkin.width = this.regularPaddingSize;
			backgroundSkin.height = this.regularPaddingSize;
			header.backgroundSkin = backgroundSkin;
		}
		
		protected function setLoginFooterStyles(header:Header):void
		{
			this.setHeaderStyles(header);
		}
		
		protected function setCreateUserHeaderStyles(header:Header):void
		{
			this.setHeaderStyles(header);
			
			var backgroundSkin:Quad = new Quad(this.regularPaddingSize, this.standardHeaderHeight, GlobalSettings.CREATEUSER_HEADER_BACKGROUND_COLOR);
			backgroundSkin.width = this.regularPaddingSize;
			backgroundSkin.height = this.standardHeaderHeight;
			header.backgroundSkin = backgroundSkin;
			
			header.titleProperties.elementFormat = this.buttonLoginUIElementFormat;
		}
		
		protected function setHomeSubHeaderStyles(header:Header):void
		{
			var backgroundSkin:Scale3Image = new Scale3Image(headerSubBackgroundGreyTextures, _scale);
			backgroundSkin.width = this.regularPaddingSize;
			backgroundSkin.height = this.standardSubHeaderHeight;
			header.backgroundSkin = backgroundSkin;
			header.paddingLeft = this.mediumPaddingSize;
			header.paddingRight = this.mediumPaddingSize;
			header.titleProperties.elementFormat = this.buttonLoginUIElementFormat;
		}		
		
		//-------------------------
		// Button
		//-------------------------
		
		protected function setMainHeaderButtonStyles(button:Button):void
		{
			button.minTouchWidth = this.standardHeaderHeight;
			button.minTouchHeight = this.standardHeaderHeight;			
		}
		
		protected function setLoginButtonStyles(button:Button):void
		{
			var skinSelector:SmartDisplayObjectStateValueSelector = new SmartDisplayObjectStateValueSelector();
			skinSelector.defaultValue = this.buttonLoginSkinTextures;
			skinSelector.setValueForState(this.buttonLoginSkinTextures, Button.STATE_DOWN, false);
			skinSelector.setValueForState(this.buttonLoginSkinTextures, Button.STATE_DISABLED, false);
			skinSelector.displayObjectProperties =
			{
				width: this.buttonLoginWidth,
				height: this.buttonLoginHeight,
				textureScale: this._scale
			};
			button.stateToSkinFunction = skinSelector.updateValue;
			button.horizontalAlign = Button.HORIZONTAL_ALIGN_CENTER;
			button.gap = 30 * this._scale;
			button.iconPosition = Button.ICON_POSITION_MANUAL;
			button.iconOffsetY = 18 * this._scale;
			button.paddingLeft = 18 * this._scale;
			button.minTouchWidth = this.touchSize;
			button.minTouchHeight = this.touchSize;
			button.defaultLabelProperties.elementFormat = this.buttonLoginUIElementFormat;
		}
		
		protected function setLoginFacebookButtonStyles(button:Button):void
		{
			var skinSelector:SmartDisplayObjectStateValueSelector = new SmartDisplayObjectStateValueSelector();
			skinSelector.defaultValue = this.buttonFBLoginSkinTextures;
			skinSelector.setValueForState(this.buttonFBLoginSkinTextures, Button.STATE_DOWN, false);
			skinSelector.setValueForState(this.buttonFBLoginSkinTextures, Button.STATE_DISABLED, false);
			skinSelector.displayObjectProperties =
			{
				width: this.buttonLoginWidth,
				height: this.buttonLoginHeight,
				textureScale: this._scale
			};
			button.stateToSkinFunction = skinSelector.updateValue;
			button.horizontalAlign = Button.HORIZONTAL_ALIGN_CENTER;
			button.minTouchWidth = this.touchSize;
			button.minTouchHeight = this.touchSize;
			button.defaultLabelProperties.elementFormat = this.buttonLoginUIElementFormat;
		}	
		
		protected function setGreyHeaderButtonStyles(button:Button):void
		{
			button.horizontalAlign = Button.HORIZONTAL_ALIGN_CENTER;
			button.minTouchWidth = this.touchSize;
			button.minTouchHeight = this.touchSize;
		}
		
		protected function setNextGreyHeaderButtonStyles(button:Button):void
		{
			this.setGreyHeaderButtonStyles(button);
			
			var iconSelector:SmartDisplayObjectStateValueSelector = new SmartDisplayObjectStateValueSelector();
			iconSelector.defaultValue = this.greyHeaderNextButtonTexture;
			iconSelector.setValueForState(this.greyHeaderNextDisabledButtonTexture, Button.STATE_DISABLED, false);
			iconSelector.displayObjectProperties =
			{
				scaleX: this._scale,
				scaleY: this._scale
			};
			button.stateToIconFunction = iconSelector.updateValue;
		}		
	
		protected function setBackGreyHeaderButtonStyles(button:Button):void
		{
			this.setGreyHeaderButtonStyles(button);
			
			var iconSelector:SmartDisplayObjectStateValueSelector = new SmartDisplayObjectStateValueSelector();
			iconSelector.defaultValue = this.greyHeaderBackButtonTexture;
			iconSelector.displayObjectProperties =
			{
				scaleX: this._scale,
				scaleY: this._scale
			};
			button.stateToIconFunction = iconSelector.updateValue;
		}		
		
		//-------------------------
		// Label
		//-------------------------

		protected function setDefaultLabelStyles(label:Label):void
		{
			//label.textRendererProperties.elementFormat = this.whiteHeadingFormat;
		}

		//-----------------------------------------------------------------------------------------------------------------------------
		// CreateUserTheme
		//-----------------------------------------------------------------------------------------------------------------------------
		
		private var _createUserTheme:CreateUserTheme;
		public function get createUserTheme():CreateUserTheme  { return _createUserTheme; }
		
		public function initializeCreateUserTheme():void
		{
			_createUserTheme = new CreateUserTheme(this);
			_createUserTheme.initialize();
		}
		
		public function disposeCreateUserTheme():void
		{
			_createUserTheme.dispose();
			_createUserTheme = null;
		}
	}

}