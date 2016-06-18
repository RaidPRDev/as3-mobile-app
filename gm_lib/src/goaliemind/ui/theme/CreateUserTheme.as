package goaliemind.ui.theme 
{
	import flash.geom.Rectangle;
	import flash.text.engine.ElementFormat;

	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.ImageLoader;
	import feathers.controls.Label;
	import feathers.controls.List;
	import feathers.controls.Panel;
	import feathers.controls.renderers.DefaultListItemRenderer;
	import feathers.controls.Scroller;
	import feathers.controls.SimpleScrollBar;
	import feathers.controls.SpinnerList;
	import feathers.controls.text.TextBlockTextRenderer;
	import feathers.controls.TextInput;
	import feathers.controls.ToggleButton;
	import feathers.display.Scale3Image;
	import feathers.display.Scale9Image;
	import feathers.skins.SmartDisplayObjectStateValueSelector;
	import feathers.skins.StyleNameFunctionStyleProvider;
	import feathers.textures.Scale3Textures;
	import feathers.textures.Scale9Textures;

	import starling.display.DisplayObject;
	import starling.display.Quad;
	import starling.textures.Texture;

	import goaliemind.core.AppAssets;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class CreateUserTheme 
	{
		private var _baseTheme:BasePhoneAppTheme;
		private var _appAssets:AppAssets;
		
		private var _greyHeaderBackButtonTexture:Texture;
		private var _greyHeaderNextButtonTexture:Texture;

		private var _toggleButtonNormalTexture:Texture;
		private var _toggleButtonActiveTexture:Texture;
		private var _toggleButtonRedActiveTexture:Texture;
		
		private var _mindGoalieToggleNormalTexture:Texture; // art/mindgoalie_account_toggle_pic_normal
		private var _mindGoalieToggleActiveTexture:Texture; // art/mindgoalie_account_toggle_pic_active

		private var _goalieMindToggleNormalTexture:Texture; // art/goaliemind_account_toggle_pic_normal
		private var _goalieMindToggleActiveTexture:Texture; // art/goaliemind_account_toggle_pic_active
		
		private var _personalToggleNormalTexture:Texture; // art/mindgoalie_toggle_pic_normal
		private var _personalToggleActiveTexture:Texture; // art/mindgoalie_toggle_pic_active
		private var _organizationToggleNormalTexture:Texture; // art/mindgoalie_teacher_assets/mindgoalie_teacher_toggle_pic_normal
		private var _organizationToggleActiveTexture:Texture; // art/mindgoalie_teacher_assets/mindgoalie_teacher_toggle_pic_active
		private var _girlToggleNormalTexture:Texture; // gender/gender_girl_large_normal_icon
		private var _girlToggleActiveTexture:Texture; // gender/gender_girl_large_active_icon
		private var _boyToggleNormalTexture:Texture; // gender/gender_boy_large_normal_icon
		private var _boyToggleActiveTexture:Texture; // gender/gender_boy_large_active_icon
		
		public var largeUsersIconTexture:Texture; // large_icons/users_large_icon
		public var largeEmailIconTexture:Texture; // large_icons/email_large_icon
		public var largeUserIconTexture:Texture; // large_icons/user_cicle_large_icon
		public var largePasswordIconTexture:Texture; // large_icons/password_large_icon
		public var largePrivacyIconTexture:Texture; // large_icons/privacy_large_icon
		public var birthdaySmallIcon:Texture; // small_icons/birthday_small_icon
		public var calendarSmallIcon:Texture; // small_icons/calendar_small_icon
		
		private var _verticalScrollBarTextures:Scale3Textures;
		
		private var _spinnerListFadeSmallOverlaySkinTextures:Scale9Textures;
		private var _spinnerListOverlaySkinTextures:Scale9Textures;
		private static const SPINNER_LIST_SELECTION_OVERLAY_SCALE9_GRID:Rectangle = new Rectangle(3, 9, 1, 70);
		private static const SPINNER_LIST_FADE_SMALL_OVERLAY_SCALE9_GRID:Rectangle = new Rectangle(2, 2, 3, 66);
		
		private var _whiteHeadingFormat:ElementFormat;
		private var _redHeadingFormat:ElementFormat;
		private var _blueHeadingFormat:ElementFormat;
		private var _whiteDetailsFormat:ElementFormat;
		private var _redDetailsFormat:ElementFormat;
		private var _blueDetailsFormat:ElementFormat;
		private var _blackXSmallFormat:ElementFormat;
		private var _darkGreyMediumFormat:ElementFormat;
		
		public var darkGreyXLargeFormat:ElementFormat;
		
		public function CreateUserTheme(baseTheme:BasePhoneAppTheme) 
		{
			_baseTheme = baseTheme;
			_appAssets = AppAssets.GetInstance();
		}
		
		public function dispose():void
		{
			trace("CreateUserTheme.dispose()");
			
			_whiteHeadingFormat = null;
			_redHeadingFormat = null;
			_blueHeadingFormat = null;
			_whiteDetailsFormat = null;
			_redDetailsFormat = null;
			_blueDetailsFormat = null;
			_blackXSmallFormat = null;	
			_darkGreyMediumFormat = null;	
			
			_userStyleProvider = null;
			
			_toggleButtonNormalTexture.dispose();
			_toggleButtonActiveTexture.dispose();
			_toggleButtonRedActiveTexture.dispose();
			_toggleButtonNormalTexture = null;
			_toggleButtonActiveTexture = null;
			_toggleButtonRedActiveTexture = null;

			_personalToggleNormalTexture.dispose();
			_personalToggleActiveTexture.dispose();
			_personalToggleNormalTexture = null;
			_personalToggleActiveTexture = null;

			_organizationToggleNormalTexture.dispose();
			_organizationToggleActiveTexture.dispose();
			_organizationToggleNormalTexture = null;
			_organizationToggleActiveTexture = null;
			
			largeUsersIconTexture.dispose();
			largeUsersIconTexture = null;
			
			largeEmailIconTexture.dispose();
			largeEmailIconTexture = null;
			
			largeUserIconTexture.dispose();
			largeUserIconTexture = null;
			
			largePasswordIconTexture.dispose();
			largePasswordIconTexture = null;
			
			largePrivacyIconTexture.dispose();
			largePrivacyIconTexture = null;
			
			_baseTheme = null;
			
			// Remove/Purge CreateUserAssets in _appAssets
			_appAssets.removeCreateUserAssets();
			_appAssets = null;
		}
		
		public function initialize():void
		{
			initializeFonts();
			initializeTextures();
			initializeStyles();
		}
		
		private function initializeFonts():void
		{
			_whiteHeadingFormat = new ElementFormat(_baseTheme.boldFontDescription, _baseTheme.largeFontSize, GlobalSettings.WHITE_TEXT_COLOR);
			_redHeadingFormat = new ElementFormat(_baseTheme.boldFontDescription, _baseTheme.largeFontSize, GlobalSettings.RED_TEXT_COLOR);
			_blueHeadingFormat = new ElementFormat(_baseTheme.boldFontDescription, _baseTheme.largeFontSize, GlobalSettings.BLUE_TEXT_COLOR);
			_whiteDetailsFormat = new ElementFormat(_baseTheme.regularFontDescription, _baseTheme.mediumFontSize, GlobalSettings.WHITE_TEXT_COLOR);
			_redDetailsFormat = new ElementFormat(_baseTheme.regularFontDescription, _baseTheme.mediumFontSize, GlobalSettings.RED_TEXT_COLOR);
			_blueDetailsFormat = new ElementFormat(_baseTheme.regularFontDescription, _baseTheme.mediumFontSize, GlobalSettings.BLUE_TEXT_COLOR);
			_blackXSmallFormat = new ElementFormat(_baseTheme.regularFontDescription, _baseTheme.xSmallFontSize, GlobalSettings.BLACK_TEXT_COLOR);			
			_darkGreyMediumFormat = new ElementFormat(_baseTheme.regularFontDescription, _baseTheme.mediumFontSize, GlobalSettings.DARK_TEXT_COLOR);			
			darkGreyXLargeFormat = new ElementFormat(_baseTheme.regularFontDescription, _baseTheme.xLargeFontSize, GlobalSettings.DARK_TEXT_COLOR);			
		}
		
		private function initializeTextures():void
		{
			_verticalScrollBarTextures = new Scale3Textures(this._appAssets.createUserAssets.getTexture("vscroll_bar"), 5, 5, Scale3Textures.DIRECTION_VERTICAL);
			
			_toggleButtonNormalTexture = this._appAssets.createUserAssets.getTexture("toggle_normal_button");
			_toggleButtonActiveTexture = this._appAssets.createUserAssets.getTexture("toggle_active_button");
			_toggleButtonRedActiveTexture = this._appAssets.createUserAssets.getTexture("toggle_active_red_button");
			
			_mindGoalieToggleNormalTexture = this._appAssets.createUserAssets.getTexture("mindgoalie_select/mindgoalie_personal_icon");
			_mindGoalieToggleActiveTexture = this._appAssets.createUserAssets.getTexture("mindgoalie_select/mindgoalie_personal_active_icon");

			_goalieMindToggleNormalTexture = this._appAssets.createUserAssets.getTexture("mindgoalie_select/mindgoalie_teacher_icon");
			_goalieMindToggleActiveTexture = this._appAssets.createUserAssets.getTexture("mindgoalie_select/mindgoalie_teacher_active_icon");		
			
			_personalToggleNormalTexture = this._appAssets.createUserAssets.getTexture("art/mindgoalie_toggle_pic_normal");
			_personalToggleActiveTexture = this._appAssets.createUserAssets.getTexture("art/mindgoalie_toggle_pic_active");
			
			_organizationToggleNormalTexture = this._appAssets.createUserAssets.getTexture("art/mindgoalie_teacher_assets/mindgoalie_teacher_toggle_pic_normal");
			_organizationToggleActiveTexture = this._appAssets.createUserAssets.getTexture("art/mindgoalie_teacher_assets/mindgoalie_teacher_toggle_pic_active");
			
			_girlToggleNormalTexture = this._appAssets.createUserAssets.getTexture("gender/gender_girl_large_normal_icon");
			_girlToggleActiveTexture = this._appAssets.createUserAssets.getTexture("gender/gender_girl_large_active_icon");
			
			_boyToggleNormalTexture = this._appAssets.createUserAssets.getTexture("gender/gender_boy_large_normal_icon");
			_boyToggleActiveTexture = this._appAssets.createUserAssets.getTexture("gender/gender_boy_large_active_icon");
			
			largeUsersIconTexture = this._appAssets.createUserAssets.getTexture("large_icons/users_large_icon");
			largeEmailIconTexture = this._appAssets.createUserAssets.getTexture("large_icons/email_large_icon");
			largeUserIconTexture = this._appAssets.createUserAssets.getTexture("large_icons/user_cicle_large_icon");
			largePasswordIconTexture = this._appAssets.createUserAssets.getTexture("large_icons/password_large_icon");
			largePrivacyIconTexture = this._appAssets.createUserAssets.getTexture("large_icons/privacy_large_icon");
			
			birthdaySmallIcon = this._appAssets.createUserAssets.getTexture("small_icons/birthday_small_icon");
			calendarSmallIcon = this._appAssets.createUserAssets.getTexture("small_icons/calendar_small_icon");
			
			_spinnerListFadeSmallOverlaySkinTextures = new Scale9Textures(this._appAssets.createUserAssets.getTexture("spinner-list-fade-small-overlay-skin"), SPINNER_LIST_FADE_SMALL_OVERLAY_SCALE9_GRID);
			_spinnerListOverlaySkinTextures = new Scale9Textures(this._appAssets.createUserAssets.getTexture("spinner-list-overlay-skin"), SPINNER_LIST_SELECTION_OVERLAY_SCALE9_GRID);
		}
		
		private var _userStyleProvider:StyleNameFunctionStyleProvider;
		public function get userStyleProvider():StyleNameFunctionStyleProvider  { return _userStyleProvider; }
		
		private function initializeStyles():void
		{
			_userStyleProvider = new StyleNameFunctionStyleProvider();
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.SIMPLESCROLL, setSimpleScrollBar);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.BUTTON_TOGGLE, setToggleButtonStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.BUTTON_RED_TOGGLE, setToggleRedButtonStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.BUTTON_MINDGOALIE_TOGGLE, setToggleMindGoalieButtonStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.BUTTON_GOALIEMIND_TOGGLE, setToggleGoalieMindButtonStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.BUTTON_PERSONAL_TOGGLE, setTogglePersonalButtonStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.BUTTON_ORGANIZATION_TOGGLE, setToggleOrganizationButtonStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.BUTTON_GIRL_TOGGLE, setToggleGirlButtonStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.BUTTON_BOY_TOGGLE, setToggleBoyButtonStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.BUTTON_SUBHEADER, setSubHeaderStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.BUTTON_DARK_TITLE_ICON, setButtonDarkTitleStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.BUTTON_RED_TITLE_ICON, setButtonRedTitleStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.BUTTON_TERMS, setButtonTermsStyles);
			
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.LABEL_XSMALL_BLACK, setXSmallBlackLabelStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.LABEL_BLUE_HEADING, setBlueHeadingLabelStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.LABEL_BLUE_DETAILS, setBlueDetailLabelStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.LABEL_RED_HEADING, setRedHeadingLabelStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.LABEL_RED_DETAILS, setRedDetailLabelStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.LABEL_TERMS, setTermsLabelStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.LABEL_REGISTER_STATUS, setRegisterStatusLabelStyles);
			
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.SPINNER_BIRTHDAY, setBirthDaySpinnerStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.SPINNER_ITEMRENDERER, this.setSpinnerListItemRendererStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.SPINNER_INPUT_LABEL, this.setBirthdayTextInputStyles);
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.SPINNER_FADE_PANEL, this.setSpinnerFadeOverlayPanelStyles);
			
			_userStyleProvider.setFunctionForStyleName(GlobalSettings.SPINNER_HEADER, this.setSpinnerHeaderStyles);
		}

		//-------------------------
		// Header
		//-------------------------

		protected function setSpinnerHeaderStyles(header:Header):void
		{
			header.padding = _baseTheme.regularPaddingSize;
			header.gap = _baseTheme.regularPaddingSize;
			header.titleGap = _baseTheme.regularPaddingSize;
			
			var backgroundSkin:Quad = new Quad(_baseTheme.regularPaddingSize, _baseTheme.headerHeight, GlobalSettings.CREATEUSER_HEADER_BACKGROUND_COLOR);
			backgroundSkin.width = _baseTheme.regularPaddingSize;
			backgroundSkin.height = _baseTheme.headerHeight;
			header.backgroundSkin = backgroundSkin;
			
			header.titleProperties.elementFormat = this._blueDetailsFormat;
		}
		
		//-------------------------
		// Label
		//-------------------------

		protected function setDarkGreyMediumLabelStyles(label:Label):void
		{
			label.textRendererProperties.elementFormat = _darkGreyMediumFormat;
		}
		
		protected function setBirthdayTextInputStyles(input:TextInput):void
		{
			var inputWidth:Number = 544 * _baseTheme.scale;
			var inputHeight:Number = 88 * _baseTheme.scale;
			
			var skinSelector:SmartDisplayObjectStateValueSelector = new SmartDisplayObjectStateValueSelector();
			skinSelector.defaultValue = _baseTheme.textInputSkin;
			skinSelector.setValueForState(_baseTheme.textInputSkin, TextInput.STATE_DISABLED);
			skinSelector.setValueForState(_baseTheme.textInputSkin, TextInput.STATE_FOCUSED);
			skinSelector.displayObjectProperties = {width: inputWidth, height:inputHeight, textureScale: _baseTheme.scale};
			input.stateToSkinFunction = skinSelector.updateValue;
			
			input.minWidth = inputWidth;
			input.minHeight = inputHeight;
			input.minTouchWidth = _baseTheme.touchSize;
			input.minTouchHeight = _baseTheme.touchSize;
			input.gap = _baseTheme.regularPaddingSize;
			input.padding = _baseTheme.smallPaddingSize;
			
			input.textEditorProperties.fontFamily = "Helvetica";
			input.textEditorProperties.fontSize = _baseTheme.largeFontSize;
			input.textEditorProperties.color = GlobalSettings.DARK_TEXT_COLOR;
			
			input.promptProperties.elementFormat = new ElementFormat(_baseTheme.regularFontDescription, _baseTheme.largeFontSize, GlobalSettings.DARK_TEXT_COLOR);	
			// darkGreyXLargeFormat;
		}		
		
		//-------------------------
		// SpinnerList
		//--------------------------
		
		protected function setSpinnerFadeOverlayPanelStyles(panel:Panel):void
		{
			panel.backgroundSkin = new Scale9Image(_spinnerListFadeSmallOverlaySkinTextures, _baseTheme.scale);
		}
		
		//-------------------------
		// SpinnerList
		//-------------------------

		protected function setBirthDaySpinnerStyles(list:SpinnerList):void
		{
			this.setListStyles(list);
			list.customItemRendererStyleName = GlobalSettings.SPINNER_ITEMRENDERER;
			list.selectionOverlaySkin = new Scale9Image(_spinnerListOverlaySkinTextures, _baseTheme.scale);
		}
		
		protected function setListStyles(list:List):void
		{
			this.setScrollerStyles(list);
			var backgroundSkin:Quad = new Quad(20, 20, 0x359997);
			list.backgroundSkin = backgroundSkin;
		}
		
		protected function setScrollerStyles(scroller:Scroller):void
		{
			// scroller.horizontalScrollBarFactory = scrollBarFactory;
			scroller.verticalScrollBarFactory = scrollBarFactory;
		}
		
		protected function setSimpleScrollBar(simpleScrollbar:SimpleScrollBar):void
		{
			var defaultSkin:Scale3Image = new Scale3Image(_verticalScrollBarTextures, _baseTheme.scale);
			
			simpleScrollbar.thumbProperties.defaultSkin = defaultSkin;
			simpleScrollbar.paddingTop = 4;
			simpleScrollbar.paddingRight = 4;
			simpleScrollbar.paddingBottom = 4;
		}
		
		protected function scrollBarFactory():SimpleScrollBar
		{
			return new SimpleScrollBar();;
		}
		
		protected function setSpinnerListItemRendererStyles(renderer:DefaultListItemRenderer):void
		{
			// renderer.defaultSkin = new Quad(20 * _baseTheme.scale, 90 * _baseTheme.scale, 0x359997);
			renderer.defaultLabelProperties.elementFormat = _whiteHeadingFormat;

			renderer.horizontalAlign = Button.HORIZONTAL_ALIGN_CENTER;
			renderer.paddingTop = _baseTheme.mediumPaddingSize;
			renderer.paddingBottom = _baseTheme.mediumPaddingSize;
			renderer.paddingLeft = _baseTheme.mediumPaddingSize;
			renderer.paddingRight = _baseTheme.mediumPaddingSize;
			renderer.minTouchWidth = _baseTheme.touchSize;
			renderer.minTouchHeight = _baseTheme.touchSize;
		}
		
		//-------------------------
		// Toggle Button - CreateUserAtlas
		//-------------------------
		
		private function setToggleButtonStyles(button:ToggleButton):void
		{
			button.defaultSkin = loadImageFactory(_toggleButtonActiveTexture);
			button.defaultSelectedSkin = loadImageFactory(_toggleButtonNormalTexture);
			
			button.minTouchWidth = _baseTheme.touchSize;
			button.minTouchHeight = _baseTheme.touchSize;
		}

		private function setToggleRedButtonStyles(button:ToggleButton):void
		{
			button.defaultSkin = loadImageFactory(_toggleButtonActiveTexture);
			button.defaultSelectedSkin = loadImageFactory(_toggleButtonRedActiveTexture);
			
			button.minTouchWidth = _baseTheme.touchSize;
			button.minTouchHeight = _baseTheme.touchSize;
		}
		
		private function setToggleMindGoalieButtonStyles(button:ToggleButton):void
		{
			button.defaultSkin = loadImageFactory(_mindGoalieToggleNormalTexture);
			button.defaultSelectedSkin = loadImageFactory(_mindGoalieToggleActiveTexture);
		}	

		private function setToggleGoalieMindButtonStyles(button:ToggleButton):void
		{
			button.defaultSkin = loadImageFactory(_goalieMindToggleNormalTexture);
			button.defaultSelectedSkin = loadImageFactory(_goalieMindToggleActiveTexture);
		}	
		
		private function setTogglePersonalButtonStyles(button:ToggleButton):void
		{
			button.defaultSkin = loadImageFactory(_personalToggleNormalTexture);
			button.defaultSelectedSkin = loadImageFactory(_personalToggleActiveTexture);
		}	
		
		private function setToggleOrganizationButtonStyles(button:ToggleButton):void
		{
			button.defaultSkin = loadImageFactory(_organizationToggleNormalTexture);
			button.defaultSelectedSkin = loadImageFactory(_organizationToggleActiveTexture);
		}	

		private function setToggleGirlButtonStyles(button:ToggleButton):void
		{
			button.defaultSkin = loadImageFactory(_girlToggleNormalTexture);
			button.defaultSelectedSkin = loadImageFactory(_girlToggleActiveTexture);
		}	
		
		private function setToggleBoyButtonStyles(button:ToggleButton):void
		{
			button.defaultSkin = loadImageFactory(_boyToggleNormalTexture);
			button.defaultSelectedSkin = loadImageFactory(_boyToggleActiveTexture);
		}	
		
		private function setSubHeaderStyles(button:Button):void
		{
			button.touchable = false;
		}
		
		private function setButtonDarkTitleStyles(button:Button):void
		{
			button.defaultLabelProperties.elementFormat = _darkGreyMediumFormat;
			button.horizontalAlign = Button.HORIZONTAL_ALIGN_LEFT;
			button.verticalAlign = Button.VERTICAL_ALIGN_MIDDLE;
			button.gap = _baseTheme.regularPaddingSize;
			button.paddingTop = _baseTheme.smallPaddingSize;
			button.paddingBottom = _baseTheme.smallPaddingSize;
			button.paddingLeft = _baseTheme.smallPaddingSize;
			button.paddingRight = _baseTheme.smallPaddingSize;
			button.minTouchWidth = _baseTheme.touchSize;
			button.minTouchHeight = _baseTheme.touchSize;
		}
		
		private function setButtonRedTitleStyles(button:Button):void
		{
			button.defaultLabelProperties.elementFormat = _redDetailsFormat;
			//button.defaultLabelProperties.elementFormat.fontSize = 28;
			button.horizontalAlign = Button.HORIZONTAL_ALIGN_LEFT;
			button.gap = _baseTheme.regularPaddingSize;
			//button.paddingTop = _baseTheme.smallPaddingSize;
			//button.paddingBottom = _baseTheme.smallPaddingSize;
			//button.paddingLeft = _baseTheme.smallPaddingSize;
			//button.paddingRight = _baseTheme.smallPaddingSize;
			button.minTouchWidth = _baseTheme.touchSize;
			button.minTouchHeight = _baseTheme.touchSize;
		}	
		
		private function setButtonTermsStyles(button:Button):void
		{
			var fSize:Number = 20 * _baseTheme.scale;
			button.defaultLabelProperties.elementFormat = new ElementFormat(_baseTheme.regularFontDescription, fSize, GlobalSettings.RED_TEXT_COLOR);;
			button.horizontalAlign = Button.HORIZONTAL_ALIGN_LEFT;
			button.minTouchWidth = _baseTheme.touchSize;
			button.minTouchHeight = _baseTheme.touchSize;
		}		
		
		protected function setTermsLabelStyles(label:Label):void
		{
			var fSize:Number = 20 * _baseTheme.scale;
			label.textRendererProperties.elementFormat = new ElementFormat(_baseTheme.regularFontDescription, fSize, GlobalSettings.DARK_TEXT_COLOR);
		}			
		
		protected function loadImageFactory(texture:Texture):DisplayObject
		{
			var img:ImageLoader = new ImageLoader();
			img.source = texture;
			img.textureScale = _baseTheme.scale;
			return img;
		}
		
		//-------------------------
		// Labels - CreateUserAtlas
		//-------------------------
		
		protected function setBlueHeadingLabelStyles(label:Label):void
		{
			label.textRendererProperties.elementFormat = _whiteHeadingFormat;
			label.textRendererProperties.disabledElementFormat = _blueHeadingFormat;
		}

		protected function setBlueDetailLabelStyles(label:Label):void
		{
			label.textRendererProperties.elementFormat = _whiteDetailsFormat;
			label.textRendererProperties.disabledElementFormat = _blueDetailsFormat;
		}	
		
		protected function setRedHeadingLabelStyles(label:Label):void
		{
			label.textRendererProperties.elementFormat = _whiteHeadingFormat;
			label.textRendererProperties.disabledElementFormat = _redHeadingFormat;
		}

		protected function setRedDetailLabelStyles(label:Label):void
		{
			label.textRendererProperties.elementFormat = _whiteDetailsFormat;
			label.textRendererProperties.disabledElementFormat = _redDetailsFormat;
		}	
		
		protected function setRegisterStatusLabelStyles(label:Label):void
		{
			label.textRendererProperties.elementFormat = _redDetailsFormat;
			label.textRendererProperties.disabledElementFormat = _redDetailsFormat;
			label.textRendererProperties.textAlign = TextBlockTextRenderer.TEXT_ALIGN_CENTER;
		}		
		
		protected function setXSmallBlackLabelStyles(label:Label):void
		{
			label.textRendererProperties.elementFormat = _blackXSmallFormat;
		}	
	}

}