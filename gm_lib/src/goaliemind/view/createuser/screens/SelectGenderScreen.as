package goaliemind.view.createuser.screens 
{
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.Label;
	import feathers.controls.Panel;
	import feathers.controls.renderers.DefaultListItemRenderer;
	import feathers.controls.renderers.IListItemRenderer;
	import feathers.controls.SpinnerList;
	import feathers.controls.TextInput;
	import feathers.controls.ToggleButton;
	import feathers.data.ListCollection;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;
	
	import starling.animation.Transitions;
	import starling.core.Starling;
	import starling.display.DisplayObject;
	import starling.display.Quad;
	import starling.events.Event;

	import goaliemind.ui.base.CreateUserPanelScreen;
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class SelectGenderScreen extends CreateUserPanelScreen
	{
		private const TOGGLE_GIRL:String = "toggle-girl";
		private const TOGGLE_BOY:String = "toggle-boy";
		private const SPINNER_CANCEL:String = "spinner-cancel";
		private const SPINNER_DONE:String = "spinner-done";
		
		private var _girlPanel:Panel;
		private var _girlTogglePanel:Panel;
		private var _girlImageButton:ToggleButton;
		private var _girlHeading:Label;
		private var _girlToggleBtn:ToggleButton;
		
		private var _boyPanel:Panel;
		private var _boyTogglePanel:Panel;
		private var _boyImageButton:ToggleButton;
		private var _boyHeading:Label;
		private var _boyToggleBtn:ToggleButton;
		
		private var _birthdayTitle:Button;
		private var _birthdayInput:TextInput;
		private var _birthdayInputButton:Button;
		
		private var _spinnerModalOverlay:Quad;
		private var _spinnerPanel:Panel;
		private var _spinnerCancel:Button;
		private var _spinnerDone:Button;
		private var _spinnerFadeOverlay:Panel;

		private var _listMonth:SpinnerList;
		private var _listDay:SpinnerList;
		private var _listYear:SpinnerList;
				
		public var monthTxt:String, dayTxt:String, yearTxt:String;
		public var monthIndex:int, dayIndex:int, yearIndex:int;
		public var isBoySelected:Boolean;
		public var isGirlSelected:Boolean;
		
		public function SelectGenderScreen() 
		{
			super();
		}
		
		override public function dispose():void
		{
			// never forget to dispose textures!
			
			_girlToggleBtn.removeEventListener(Event.CHANGE, toggleButton_changeHandler);
			_girlPanel.removeChild(_girlTogglePanel);
			_girlPanel.removeChild(_girlImageButton);
			_girlPanel.removeChild(_girlHeading);
			_girlPanel.removeChild(_girlToggleBtn);
			this.removeChild(_girlPanel);			
			
			_boyToggleBtn.removeEventListener(Event.CHANGE, toggleButton_changeHandler);
			_boyPanel.removeChild(_boyTogglePanel);
			_boyPanel.removeChild(_boyImageButton);
			_boyPanel.removeChild(_boyHeading);
			_boyPanel.removeChild(_boyToggleBtn);
			this.removeChild(_boyPanel);				
			
			super.dispose();
		}
		
		override protected function initialize():void
		{
			super.initialize();

			this.title = "Personal Details";
			
			this.layout = new AnchorLayout();

			this.headerFactory = this.customHeaderFactory;
			
			this.createPersonalToggle();
			this.createOrgToggle();
			
			this.createBirthdayTitle();
			this.createBirthdayInput();
		}
		
		private function createPersonalToggle():void
		{
			var personalPanelLayoutData:AnchorLayoutData = new AnchorLayoutData(0, NaN, NaN, 0, NaN, NaN);
			personalPanelLayoutData.percentWidth = 50;
			personalPanelLayoutData.percentHeight = 50;
			
			_girlPanel = new Panel();
			_girlPanel.backgroundSkin = new Quad(20, 20, GlobalSettings.WHITE_TEXT_COLOR);
			_girlPanel.layoutData = personalPanelLayoutData;
			_girlPanel.layout = new AnchorLayout();
			
			_girlTogglePanel = new Panel();
			_girlTogglePanel.backgroundSkin = new Quad(20, 20, GlobalSettings.BLUE_TEXT_COLOR);
			_girlTogglePanel.backgroundDisabledSkin = new Quad(20, 20, GlobalSettings.WHITE_TEXT_COLOR);
			_girlTogglePanel.layoutData = new AnchorLayoutData(0, 0, 0, 0, 0, 0);
			_girlTogglePanel.isEnabled = isGirlSelected;
			
			var personalImageLayoutData:AnchorLayoutData = new AnchorLayoutData(40 * _manager.appCore.theme.scale, NaN, NaN, NaN, 0, NaN);
			_girlImageButton = new ToggleButton();
			_girlImageButton.touchable = false;
			_girlImageButton.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_girlImageButton.styleNameList.add(GlobalSettings.BUTTON_GIRL_TOGGLE);
			_girlImageButton.layoutData = personalImageLayoutData;
			_girlImageButton.isSelected = isGirlSelected;
			
			var personalHeadingLayoutData:AnchorLayoutData = new AnchorLayoutData(NaN, NaN, 50 * _manager.appCore.theme.scale, NaN, 0, NaN);
			_girlHeading = new Label();
			_girlHeading.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_girlHeading.styleNameList.add(GlobalSettings.LABEL_BLUE_HEADING);
			_girlHeading.layoutData = personalHeadingLayoutData;
			_girlHeading.text = "Girl";
			_girlHeading.isEnabled = isGirlSelected;
			
			var personalToggleLayoutData:AnchorLayoutData = new AnchorLayoutData(NaN, NaN, 143 * _manager.appCore.theme.scale, NaN, 0, NaN);
			personalToggleLayoutData.topAnchorDisplayObject = _girlImageButton;
			_girlToggleBtn = new ToggleButton();
			_girlToggleBtn.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_girlToggleBtn.styleNameList.add(GlobalSettings.BUTTON_TOGGLE);
			_girlToggleBtn.layoutData = personalToggleLayoutData;
			_girlToggleBtn.isSelected = isGirlSelected;
			_girlToggleBtn.name = TOGGLE_GIRL;
			_girlToggleBtn.addEventListener(Event.CHANGE, toggleButton_changeHandler);
			
			_girlPanel.addChild(DisplayObject(_girlTogglePanel));
			_girlPanel.addChild(DisplayObject(_girlImageButton));
			_girlPanel.addChild(DisplayObject(_girlHeading));
			_girlPanel.addChild(DisplayObject(_girlToggleBtn));
			
			this.addChild(_girlPanel);
			_girlPanel.validate();
		}
		
		private function createOrgToggle():void
		{
			var orgPanelLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, NaN, NaN, NaN);
			orgPanelLayoutData.percentWidth = 50;
			orgPanelLayoutData.percentHeight = 50;
			orgPanelLayoutData.leftAnchorDisplayObject = _girlPanel;
			_boyPanel = new Panel();
			_boyPanel.backgroundSkin = new Quad(20, 20, GlobalSettings.WHITE_TEXT_COLOR);
			_boyPanel.layoutData = orgPanelLayoutData;
			_boyPanel.layout = new AnchorLayout();
			
			_boyTogglePanel = new Panel();
			_boyTogglePanel.backgroundSkin = new Quad(20, 20, GlobalSettings.RED_TEXT_COLOR);
			_boyTogglePanel.backgroundDisabledSkin = new Quad(20, 20, GlobalSettings.WHITE_TEXT_COLOR);
			_boyTogglePanel.layoutData = new AnchorLayoutData(0, 0, 0, 0, 0, 0);
			_boyTogglePanel.isEnabled = isBoySelected;
			
			var orgImageLayoutData:AnchorLayoutData = new AnchorLayoutData(40 * _manager.appCore.theme.scale, NaN, NaN, NaN, 0, NaN);
			_boyImageButton = new ToggleButton();
			_boyImageButton.touchable = false;
			_boyImageButton.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_boyImageButton.styleNameList.add(GlobalSettings.BUTTON_BOY_TOGGLE);
			_boyImageButton.layoutData = orgImageLayoutData;
			_boyImageButton.isSelected = isBoySelected;
			
			var orgHeadingLayoutData:AnchorLayoutData = new AnchorLayoutData(NaN, NaN, 50 * _manager.appCore.theme.scale, NaN, 0, NaN);
			_boyHeading = new Label();
			_boyHeading.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_boyHeading.styleNameList.add(GlobalSettings.LABEL_RED_HEADING);
			_boyHeading.layoutData = orgHeadingLayoutData;
			_boyHeading.text = "Boy";
			_boyHeading.isEnabled = isBoySelected;
			
			var orgToggleLayoutData:AnchorLayoutData = new AnchorLayoutData(NaN, NaN, 143 * _manager.appCore.theme.scale, NaN, 0, NaN);
			orgToggleLayoutData.topAnchorDisplayObject = _boyImageButton;
			_boyToggleBtn = new ToggleButton();
			_boyToggleBtn.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_boyToggleBtn.styleNameList.add(GlobalSettings.BUTTON_TOGGLE);
			_boyToggleBtn.layoutData = orgToggleLayoutData;
			_boyToggleBtn.isSelected = isBoySelected;
			_boyToggleBtn.name = TOGGLE_BOY;
			_boyToggleBtn.addEventListener(Event.CHANGE, toggleButton_changeHandler);
			
			_boyPanel.addChild(DisplayObject(_boyTogglePanel));
			_boyPanel.addChild(DisplayObject(_boyImageButton));
			_boyPanel.addChild(DisplayObject(_boyHeading));
			_boyPanel.addChild(DisplayObject(_boyToggleBtn));
			
			this.addChild(_boyPanel);
			_boyPanel.validate();
		}
		
		private function toggleButton_changeHandler(event:Event):void
		{
			var toggle:ToggleButton = ToggleButton( event.currentTarget );
			
			_girlTogglePanel.alignPivot("right", "center");
			_boyTogglePanel.alignPivot("left", "center");
				
			if (toggle.name == TOGGLE_GIRL) 
			{
				this.isEnabled = false;
				
				if (toggle.isSelected == true)
				{
					_boyToggleBtn.isSelected = false;
					_boyToggleBtn.isEnabled = true;
					_girlToggleBtn.isEnabled = false;
					
					_girlTogglePanel.scaleX = _girlTogglePanel.scaleY = 0;
					_girlTogglePanel.alpha = 1;
					_girlTogglePanel.isEnabled = toggle.isSelected;
					
					_girlImageButton.isSelected = toggle.isSelected;
					_girlHeading.isEnabled = toggle.isSelected;
							
					Starling.juggler.tween(_girlTogglePanel, 0.5, {
						transition: Transitions.EASE_OUT,
						onComplete: function():void 
						{ 
							_girlTogglePanel.isEnabled = toggle.isSelected;
							isEnabled = true;
						},
						scaleX:1,
						scaleY:1
					});
				}
				else
				{
					_girlImageButton.isSelected = toggle.isSelected;
					_girlHeading.isEnabled = toggle.isSelected;
					
					Starling.juggler.tween(_girlTogglePanel, 0.5, {
						transition: Transitions.EASE_OUT,
						onComplete: function():void 
						{ 
							_girlTogglePanel.isEnabled = toggle.isSelected;
							isEnabled = true;
						},
						alpha:0
					});
				}
			} 
			else if (toggle.name == TOGGLE_BOY) 
			{
				this.isEnabled = false;
				
				if (toggle.isSelected == true)
				{
					_girlToggleBtn.isSelected = false;
					_girlToggleBtn.isEnabled = true;
					_boyToggleBtn.isEnabled = false;
					
					_boyTogglePanel.scaleX = _boyTogglePanel.scaleY = 0;
					_boyTogglePanel.alpha = 1;
					_boyTogglePanel.isEnabled = toggle.isSelected;
					
					_boyImageButton.isSelected = toggle.isSelected;
					_boyHeading.isEnabled = toggle.isSelected;
							
					Starling.juggler.tween(_boyTogglePanel, 0.5, {
						transition: Transitions.EASE_OUT,
						onComplete: function():void 
						{ 
							_boyTogglePanel.isEnabled = toggle.isSelected;
							isEnabled = true;
						},
						scaleX:1,
						scaleY:1
					});
				}
				else
				{
					_boyImageButton.isSelected = toggle.isSelected;
					_boyHeading.isEnabled = toggle.isSelected;
					
					Starling.juggler.tween(_boyTogglePanel, 0.5, {
						transition: Transitions.EASE_OUT,
						onComplete: function():void 
						{ 
							_boyTogglePanel.isEnabled = toggle.isSelected;
							isEnabled = true;
						},
						alpha:0
					});
				}
				
			}
			
			isBoySelected = _boyToggleBtn.isSelected;
			isGirlSelected = _girlToggleBtn.isSelected;
			
			this.activateNextButton();
		}		
		
		private function createBirthdayTitle():void 
		{
			var birthdayTitleLayoutData:AnchorLayoutData = new AnchorLayoutData(26 * _manager.appCore.theme.scale, NaN, NaN, NaN, 0, NaN);
			birthdayTitleLayoutData.topAnchorDisplayObject = _girlPanel;
			
			_birthdayTitle = new Button();
			_birthdayTitle.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_birthdayTitle.styleNameList.add(GlobalSettings.BUTTON_DARK_TITLE_ICON);
			_birthdayTitle.defaultIcon = loadImageFactory(_manager.appCore.theme.createUserTheme.birthdaySmallIcon);
			_birthdayTitle.layoutData = birthdayTitleLayoutData;
			_birthdayTitle.touchable = false;
			_birthdayTitle.label = "Birthday";
			
			this.addChild(_birthdayTitle);
		}

		private function createBirthdayInput():void
		{
			var inputLayoutData:AnchorLayoutData = new AnchorLayoutData(0 * _manager.appCore.theme.scale, NaN, NaN, NaN, 0, NaN);
			inputLayoutData.topAnchorDisplayObject = _birthdayTitle;
			inputLayoutData.percentWidth = GlobalSettings.SUBHEADER_INPUT_WIDTH_PERCENT;
			
			this._birthdayInput = new TextInput();
			this._birthdayInput.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			this._birthdayInput.styleNameList.add(GlobalSettings.SPINNER_INPUT_LABEL);
			this._birthdayInput.prompt = "Tap to select date";
			this._birthdayInput.layoutData = inputLayoutData;
			//this._birthdayInput.promptProperties.elementFormat = _manager.appCore.theme.createUserTheme.darkGreyXLargeFormat;
			this._birthdayInput.textEditorProperties.textAlign = "center";
			this._birthdayInput.promptProperties.textAlign = "center";
			this._birthdayInput.touchable = false;
			// this._birthdayInput.text = "01 / 01 / 1970";
			this.addChild(DisplayObject(this._birthdayInput));
			
			var bSkin:Quad = new Quad(20, this._birthdayInput.minHeight, 0xCCCCCC);
			bSkin.alpha = 0;
			_birthdayInputButton = new Button();
			_birthdayInputButton.defaultSkin = bSkin;
			_birthdayInputButton.layoutData = inputLayoutData;
			this.addChild(DisplayObject(this._birthdayInputButton));
			
			this._birthdayInputButton.addEventListener(Event.TRIGGERED, onBirthdayInputTriggered);
		}
		
		private function onBirthdayInputTriggered(e:Event):void 
		{
			_birthdayInputButton.isEnabled = false;
			
			_spinnerModalOverlay = new Quad(this.actualWidth, this.actualHeight, 0x000000);
			_spinnerModalOverlay.alpha = 0.5;
			// _spinnerModalOverlay.touchable = false;
			this.addChild(DisplayObject(_spinnerModalOverlay));
			
			createdSpinnerPanel();
			
			createBirthMonthSpinnerList();
			createBirthDaySpinnerList();
			createBirthYearSpinnerList();
			
			createSpinnerFadeOverlay();
		}
		
		private function createdSpinnerPanel():void
		{
			var spinnerPanelLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, 0, 0, NaN, NaN);
			spinnerPanelLayoutData.topAnchorDisplayObject = _girlPanel;
			
			_spinnerPanel = new Panel();
			_spinnerPanel.backgroundSkin = new Quad(20, 20, GlobalSettings.WHITE_TEXT_COLOR);
			_spinnerPanel.layoutData = spinnerPanelLayoutData;
			_spinnerPanel.layout = new AnchorLayout();
			
			_spinnerPanel.headerFactory = customSpinnerHeaderFactory;
			
			function customSpinnerHeaderFactory():Header
			{
				var header:Header = new Header();

				header.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
				header.styleNameList.add(GlobalSettings.SPINNER_HEADER);
				
				header.title = "Select a Date";
				
				_spinnerCancel = new Button();
				_spinnerCancel.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
				_spinnerCancel.styleNameList.add(GlobalSettings.BUTTON_RED_TITLE_ICON);
				_spinnerCancel.name = SPINNER_CANCEL;
				_spinnerCancel.label = "Cancel";
				_spinnerCancel.addEventListener(Event.TRIGGERED, spinnerHeaderButton_triggeredHandler); 

				_spinnerDone = new Button();
				_spinnerDone.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
				_spinnerDone.styleNameList.add(GlobalSettings.BUTTON_RED_TITLE_ICON);
				_spinnerDone.name = SPINNER_DONE;
				_spinnerDone.label = "Done";
				_spinnerDone.addEventListener(Event.TRIGGERED, spinnerHeaderButton_triggeredHandler); 
				
				header.leftItems = new <DisplayObject> [ _spinnerCancel ];
				header.rightItems = new <DisplayObject> [ _spinnerDone ];
				
				return header;
			}
			
			this.addChild(DisplayObject(this._spinnerPanel));
		}
		
		private function spinnerHeaderButton_triggeredHandler(event:Event):void
		{
			var button:Button = Button( event.currentTarget );
			
			if (button.name == SPINNER_CANCEL)
			{
				this.removeSpinnerPanel();
				
				monthTxt = dayTxt = yearTxt = "";
				this._birthdayInput.text = "";
			}
			else if (button.name == SPINNER_DONE)
			{
				this.removeSpinnerPanel();
				
				monthTxt = this._listMonth.selectedItem.text;
				dayTxt = this._listDay.selectedItem.text;
				yearTxt = this._listYear.selectedItem.text;
				
				this.updateDateLabel();
			}
		}
		
		private function createSpinnerFadeOverlay():void
		{
			var spinnerPanelLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, 0, 0, NaN, NaN);
			
			_spinnerFadeOverlay = new Panel();
			_spinnerFadeOverlay.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_spinnerFadeOverlay.styleNameList.add(GlobalSettings.SPINNER_FADE_PANEL);
			_spinnerFadeOverlay.layoutData = spinnerPanelLayoutData;
			_spinnerFadeOverlay.touchable = false;
			
			_spinnerPanel.addChild(DisplayObject(this._spinnerFadeOverlay));
		}
		
		private function createBirthMonthSpinnerList():void
		{
			this._listMonth = new SpinnerList();
			this._listMonth.name = "BirthMonth";
			this._listMonth.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			this._listMonth.styleNameList.add(GlobalSettings.SPINNER_BIRTHDAY);
			this._listMonth.dataProvider = new ListCollection(
			[
				{ text: "January" },
				{ text: "February" },
				{ text: "March" },
				{ text: "April" },
				{ text: "May" },
				{ text: "June" },
				{ text: "July" },
				{ text: "August" },
				{ text: "September" },
				{ text: "October" },
				{ text: "November" },
				{ text: "December" }
			]);
			this._listMonth.typicalItem = {text: "Item 1000"};
			this._listMonth.itemRendererFactory = function():IListItemRenderer
			{
				var renderer:DefaultListItemRenderer = new DefaultListItemRenderer();
				renderer.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
				renderer.styleNameList.add(GlobalSettings.SPINNER_ITEMRENDERER);
				//enable the quick hit area to optimize hit tests when an item
				//is only selectable and doesn't have interactive children.
				renderer.isQuickHitAreaEnabled = true;

				renderer.labelField = "text";
				return renderer;
			};
			
			this._listMonth.selectedIndex = monthIndex;
			this._listMonth.addEventListener(Event.CHANGE, list_changeHandler);

			var listLayoutData:AnchorLayoutData = new AnchorLayoutData();
			listLayoutData.left = 0;
			listLayoutData.top = 0;
			listLayoutData.bottom = 0;
			listLayoutData.percentWidth = 40;
			this._listMonth.layoutData = listLayoutData;

			_spinnerPanel.addChild(DisplayObject(this._listMonth));
		}
		
		private function createBirthDaySpinnerList():void
		{
			this._listDay = new SpinnerList();
			this._listDay.name = "BirthDay";
			this._listDay.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			this._listDay.styleNameList.add(GlobalSettings.SPINNER_BIRTHDAY);
			
			var items:Array = [];
			for(var i:int = 0; i < 31; i++)
			{
				var item:Object = {text: (i + 1).toString()};
				items[i] = item;
			}
			items.fixed = true;
			this._listDay.dataProvider = new ListCollection(items);
			this._listDay.typicalItem = {text: "Item 1000"};
			this._listDay.itemRendererFactory = function():IListItemRenderer
			{
				var renderer:DefaultListItemRenderer = new DefaultListItemRenderer();
				renderer.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
				renderer.styleNameList.add(GlobalSettings.SPINNER_ITEMRENDERER);
				//enable the quick hit area to optimize hit tests when an item
				//is only selectable and doesn't have interactive children.
				renderer.isQuickHitAreaEnabled = true;

				renderer.labelField = "text";
				return renderer;
			};
			
			this._listDay.selectedIndex = dayIndex;
			this._listDay.addEventListener(Event.CHANGE, list_changeHandler);

			var listLayoutData:AnchorLayoutData = new AnchorLayoutData();
			listLayoutData.left = 0;
			listLayoutData.top = 0;
			listLayoutData.percentWidth = 20;
			listLayoutData.bottom = 0;
			listLayoutData.leftAnchorDisplayObject = _listMonth;
			this._listDay.layoutData = listLayoutData;

			_spinnerPanel.addChild(DisplayObject(this._listDay));
		}	
		
		private function createBirthYearSpinnerList():void
		{
			this._listYear = new SpinnerList();
			this._listYear.name = "BirthYear";
			this._listYear.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			this._listYear.styleNameList.add(GlobalSettings.SPINNER_BIRTHDAY);
			this._listYear.dataProvider = new ListCollection(
			[
				{ text: "2006" },
				{ text: "2005" },
				{ text: "2004" },
				{ text: "2003" },
				{ text: "2002" },
				{ text: "2001" },
				{ text: "2000" },
				{ text: "1999" },
				{ text: "1998" },
				{ text: "1997" },
				{ text: "1995" },
				{ text: "1994" },
				{ text: "1993" },
				{ text: "1992" },
				{ text: "1991" },
				{ text: "1990" },
				{ text: "1989" },
				{ text: "1988" },
				{ text: "1987" },
				{ text: "1986" },
				{ text: "1985" },
				{ text: "1984" },
				{ text: "1983" },
				{ text: "1982" },
				{ text: "1981" },
				{ text: "1980" },
				{ text: "1979" },
				{ text: "1978" },
				{ text: "1977" },
				{ text: "1976" },
				{ text: "1975" },
				{ text: "1974" },
				{ text: "1973" },
				{ text: "1972" },
				{ text: "1971" },
				{ text: "1970" }
			]);
			this._listYear.typicalItem = {text: "Item 1000"};
			this._listYear.itemRendererFactory = function():IListItemRenderer
			{
				var renderer:DefaultListItemRenderer = new DefaultListItemRenderer();
				renderer.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
				renderer.styleNameList.add(GlobalSettings.SPINNER_ITEMRENDERER);
				//enable the quick hit area to optimize hit tests when an item
				//is only selectable and doesn't have interactive children.
				renderer.isQuickHitAreaEnabled = true;

				renderer.labelField = "text";
				return renderer;
			};
			
			this._listYear.selectedIndex = yearIndex;
			this._listYear.addEventListener(Event.CHANGE, list_changeHandler);

			var listLayoutData:AnchorLayoutData = new AnchorLayoutData();
			listLayoutData.left = 0;
			listLayoutData.top = 0;
			listLayoutData.right = 0;
			listLayoutData.percentWidth = 40;
			listLayoutData.bottom = 0;
			listLayoutData.leftAnchorDisplayObject = _listDay;
			this._listYear.layoutData = listLayoutData;

			_spinnerPanel.addChild(DisplayObject(this._listYear));
		}	
		
		private function list_changeHandler(event:Event):void
		{
			var list:SpinnerList = SpinnerList( event.currentTarget );
			if (list == null) return;
			
			if (list.name == "BirthMonth")
			{
				trace("BirthMonth change:", this._listMonth.selectedIndex);
				monthIndex = this._listMonth.selectedIndex;
				monthTxt = this._listMonth.selectedItem.text;
			}
			else if (list.name == "BirthDay")
			{
				trace("BirthDay change:", this._listDay.selectedIndex);
				dayIndex = this._listDay.selectedIndex;
				dayTxt = this._listDay.selectedItem.text;
			}
			else if (list.name == "BirthYear")
			{
				trace("BirthYear change:", this._listYear.selectedIndex);
				yearIndex = this._listYear.selectedIndex;
				yearTxt = this._listYear.selectedItem.text;
			}
			
			this.updateDateLabel();
		}
		
		private function updateDateLabel():void
		{
			if (monthTxt && dayTxt && yearTxt)
				this._birthdayInput.text = monthTxt + " " + dayTxt + ", " + yearTxt;
				
			activateNextButton();
		}
		
		private function removeSpinnerPanel():void
		{	
			if (_spinnerPanel == null) return;
			
			_spinnerCancel.removeEventListener(Event.TRIGGERED, spinnerHeaderButton_triggeredHandler); 
			_spinnerDone.removeEventListener(Event.TRIGGERED, spinnerHeaderButton_triggeredHandler); 
			
			_listMonth.removeEventListener(Event.CHANGE, list_changeHandler);
			_listDay.removeEventListener(Event.CHANGE, list_changeHandler);
			_listYear.removeEventListener(Event.CHANGE, list_changeHandler);
			
			_spinnerPanel.removeChild(_listMonth);
			_spinnerPanel.removeChild(_listDay);
			_spinnerPanel.removeChild(_listYear);
			
			_spinnerPanel.removeChild(_spinnerFadeOverlay);
			
			removeChild(_spinnerModalOverlay);
			
			removeChild(_spinnerPanel);
			
			_birthdayInputButton.isEnabled = true;
		}
		
		private function customHeaderFactory():Header
		{
			var header:Header = new Header();

			header.styleNameList.add(GlobalSettings.CREATEUSER_HEADER);
			
			_backHeaderButton = new Button();
			_backHeaderButton.styleNameList.add(GlobalSettings.BUTTON_GREY_BACK_HEADER);
			_backHeaderButton.name = CreateUserPanelScreen.HEADER_BACK;
			_backHeaderButton.addEventListener(Event.TRIGGERED, headerButton_triggeredHandler); 

			_nextHeaderButton = new Button();
			_nextHeaderButton.styleNameList.add(GlobalSettings.BUTTON_GREY_NEXT_HEADER);
			_nextHeaderButton.name = CreateUserPanelScreen.HEADER_NEXT;
			_nextHeaderButton.addEventListener(Event.TRIGGERED, headerButton_triggeredHandler); 
			// _nextHeaderButton.isEnabled = false;
			
			if (isBoySelected != null && dayTxt && monthTxt && yearTxt) _nextHeaderButton.isEnabled = true;
			else _nextHeaderButton.isEnabled = false;
			
			header.leftItems = new <DisplayObject> [ _backHeaderButton ];
			header.rightItems = new <DisplayObject> [ _nextHeaderButton ];
			
			this.updateDateLabel();
			
			return header;
		}
		
		override protected function headerButton_triggeredHandler(event:Event):void
		{
			var button:Button = Button( event.currentTarget );
			
			if (button.name == HEADER_BACK)
			{
				this.dispatchEventWith(CreateUserPanelScreen.HEADER_BACK);
			}
			else if (button.name == HEADER_NEXT)
			{
				trace("Next Button pressed!");
				button.isEnabled = false;
				
				_manager.userData.gender = (isBoySelected) ? "boy" : "girl";
				_manager.userData.birthdate = monthTxt + "," + dayTxt + "," + yearTxt;
				dispatchEventWith(CreateUserPanelScreen.HEADER_NEXT, false, 
				{ 
					isBoySelected:isBoySelected, 
					isGirlSelected:isGirlSelected, 
					monthTxt:monthTxt,
					dayTxt:dayTxt, 
					yearTxt:yearTxt,
					monthIndex:monthIndex,
					dayIndex:dayIndex,
					yearIndex:yearIndex
				});
			}
		}	
		
		private function activateNextButton():void
		{
			if ((isBoySelected || isGirlSelected) && (dayTxt && monthTxt && yearTxt)) _nextHeaderButton.isEnabled = true;
			else _nextHeaderButton.isEnabled = false;			
		}
		
		override protected function draw():void
		{
			var layoutInvalid:Boolean = this.isInvalid(INVALIDATION_FLAG_LAYOUT);

			if(layoutInvalid)
			{
				// Reset Padding for this screen only
				this.paddingLeft = 0;
				this.paddingRight = 0;
				this.paddingTop = 0;
				this.paddingBottom = 0;
			}

			// never forget to call super.draw()
			super.draw();
		}
	}

}