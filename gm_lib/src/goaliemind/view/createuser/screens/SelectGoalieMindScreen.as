package goaliemind.view.createuser.screens 
{
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.Label;
	import feathers.controls.Panel;
	import feathers.controls.text.TextBlockTextRenderer;
	import feathers.controls.ToggleButton;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;

	import starling.animation.Transitions;
	import starling.core.Starling;
	import starling.display.DisplayObject;
	import starling.display.Quad;
	import starling.events.Event;
	
	import goaliemind.ui.base.CreateUserPanelScreen;
	import goaliemind.data.support.AccountType;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class SelectGoalieMindScreen extends CreateUserPanelScreen
	{
		private const TOGGLE_GOALIEMIND:String = "goalieMind";
		private const TOGGLE_MINDGOALIE:String = "mindGoalie";
		
		private var _mindGoaliePanel:Panel;
		private var _mindGoalieTogglePanel:Panel;
		private var _mindGoalieImageButton:ToggleButton;
		private var _mindGoalieHeading:Label;
		private var _mindGoalieDetails:Label;
		private var _mindGoalieSmallText:Label;
		private var _mindGoalieToggleBtn:ToggleButton;
		
		private var _goalieMindPanel:Panel;
		private var _goalieMindTogglePanel:Panel;
		private var _goalieMindImageButton:ToggleButton;
		private var _goalieMindHeading:Label;
		private var _goalieMindDetails:Label;
		private var _goalieMindToggleBtn:ToggleButton;
		
		public var isMindGoalie:Boolean, isGoalieMind:Boolean;
		
		public function SelectGoalieMindScreen() 
		{
			super();
		}
		
		override public function dispose():void
		{
			// never forget to dispose textures!
			
			_mindGoalieToggleBtn.removeEventListener(Event.CHANGE, toggleButton_changeHandler);
			_mindGoaliePanel.removeChild(_mindGoalieTogglePanel);
			_mindGoaliePanel.removeChild(_mindGoalieImageButton);
			_mindGoaliePanel.removeChild(_mindGoalieHeading);
			_mindGoaliePanel.removeChild(_mindGoalieDetails);
			_mindGoaliePanel.removeChild(_mindGoalieToggleBtn);
			_mindGoaliePanel.removeChild(_mindGoalieSmallText);
			this.removeChild(_mindGoaliePanel);			
			
			_goalieMindToggleBtn.removeEventListener(Event.CHANGE, toggleButton_changeHandler);
			_goalieMindPanel.removeChild(_goalieMindTogglePanel);
			_goalieMindPanel.removeChild(_goalieMindImageButton);
			_goalieMindPanel.removeChild(_goalieMindHeading);
			_goalieMindPanel.removeChild(_goalieMindDetails);
			_goalieMindPanel.removeChild(_goalieMindToggleBtn);
			this.removeChild(_goalieMindPanel);				
			
			super.dispose();
		}
		
		override protected function initialize():void
		{
			super.initialize();

			if (_manager.userData.accountType == -1) 
			{
				isMindGoalie = isGoalieMind = false;
			}
			
			this.title = "Choose Account Type";
			
			this.layout = new AnchorLayout();

			this.headerFactory = this.customHeaderFactory;
			
			this.createPersonalToggle();
			
			this.createOrgToggle();
		}
		
		private function activateNextButton():void
		{
			if (isMindGoalie || isGoalieMind) _nextHeaderButton.isEnabled = true;
			else _nextHeaderButton.isEnabled = false;			
		}			
		
		private function createPersonalToggle():void
		{
			var personalPanelLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0, 0, NaN);
			personalPanelLayoutData.percentHeight = 50;
			_mindGoaliePanel = new Panel();
			_mindGoaliePanel.backgroundSkin = new Quad(20, 20, GlobalSettings.WHITE_TEXT_COLOR);
			_mindGoaliePanel.layoutData = personalPanelLayoutData;
			_mindGoaliePanel.layout = new AnchorLayout();
			
			_mindGoalieTogglePanel = new Panel();
			_mindGoalieTogglePanel.backgroundSkin = new Quad(20, 20, GlobalSettings.BLUE_TEXT_COLOR);
			_mindGoalieTogglePanel.backgroundDisabledSkin = new Quad(20, 20, GlobalSettings.WHITE_TEXT_COLOR);
			_mindGoalieTogglePanel.layoutData = new AnchorLayoutData(0, 0, 0, 0, 0, 0);
			_mindGoalieTogglePanel.isEnabled = isMindGoalie;
			
			var personalHeadingLayoutData:AnchorLayoutData = new AnchorLayoutData(44 * _manager.appCore.theme.scale, NaN, NaN, NaN, 0, NaN);
			_mindGoalieHeading = new Label();
			_mindGoalieHeading.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_mindGoalieHeading.styleNameList.add(GlobalSettings.LABEL_BLUE_HEADING);
			_mindGoalieHeading.layoutData = personalHeadingLayoutData;
			_mindGoalieHeading.text = "MindGoalies";
			_mindGoalieHeading.isEnabled = isMindGoalie;
			
			var personalDetailsLayoutData:AnchorLayoutData = new AnchorLayoutData(40 * _manager.appCore.theme.scale, NaN, NaN, NaN, 0, NaN);
			personalDetailsLayoutData.topAnchorDisplayObject = _mindGoalieHeading;
			// personalDetailsLayoutData.percentWidth = 90;
			// personalDetailsLayoutData.percentHeight = 25;
			_mindGoalieDetails = new Label();
			_mindGoalieDetails.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_mindGoalieDetails.styleNameList.add(GlobalSettings.LABEL_BLUE_DETAILS);
			_mindGoalieDetails.layoutData = personalDetailsLayoutData;
			_mindGoalieDetails.textRendererProperties.wordWrap = true;
			_mindGoalieDetails.text = "Share work you are proud off with friends and family";
			_mindGoalieDetails.isEnabled = isMindGoalie;
			
			var personalImageLayoutData:AnchorLayoutData = new AnchorLayoutData(60 * _manager.appCore.theme.scale, NaN, NaN, 140 * _manager.appCore.theme.scale, NaN, NaN);
			personalImageLayoutData.topAnchorDisplayObject = _mindGoalieDetails;
			_mindGoalieImageButton = new ToggleButton();
			_mindGoalieImageButton.touchable = false;
			_mindGoalieImageButton.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_mindGoalieImageButton.styleNameList.add(GlobalSettings.BUTTON_MINDGOALIE_TOGGLE);
			_mindGoalieImageButton.layoutData = personalImageLayoutData;
			_mindGoalieImageButton.isSelected = isMindGoalie;
			
			var personalToggleLayoutData:AnchorLayoutData = new AnchorLayoutData(80 * _manager.appCore.theme.scale, 210 * _manager.appCore.theme.scale, NaN, NaN, NaN, NaN);
			personalToggleLayoutData.topAnchorDisplayObject = _mindGoalieDetails;
			_mindGoalieToggleBtn = new ToggleButton();
			_mindGoalieToggleBtn.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_mindGoalieToggleBtn.styleNameList.add(GlobalSettings.BUTTON_TOGGLE);
			_mindGoalieToggleBtn.layoutData = personalToggleLayoutData;
			_mindGoalieToggleBtn.isSelected = isMindGoalie;
			_mindGoalieToggleBtn.name = TOGGLE_MINDGOALIE;
			_mindGoalieToggleBtn.addEventListener(Event.CHANGE, toggleButton_changeHandler);
			
			_mindGoaliePanel.addChild(DisplayObject(_mindGoalieTogglePanel));
			_mindGoaliePanel.addChild(DisplayObject(_mindGoalieImageButton));
			_mindGoaliePanel.addChild(DisplayObject(_mindGoalieHeading));
			_mindGoaliePanel.addChild(DisplayObject(_mindGoalieDetails));
			_mindGoaliePanel.addChild(DisplayObject(_mindGoalieToggleBtn));
			
			this.addChild(_mindGoaliePanel);
			_mindGoaliePanel.validate();
		}
		
		private function createOrgToggle():void
		{
			var orgPanelLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0, 0, NaN);
			orgPanelLayoutData.percentHeight = 50;
			orgPanelLayoutData.topAnchorDisplayObject = _mindGoaliePanel;
			_goalieMindPanel = new Panel();
			_goalieMindPanel.backgroundSkin = new Quad(20, 20, GlobalSettings.WHITE_TEXT_COLOR);
			_goalieMindPanel.layoutData = orgPanelLayoutData;
			_goalieMindPanel.layout = new AnchorLayout();
			
			_goalieMindTogglePanel = new Panel();
			_goalieMindTogglePanel.backgroundSkin = new Quad(20, 20, GlobalSettings.RED_TEXT_COLOR);
			_goalieMindTogglePanel.backgroundDisabledSkin = new Quad(20, 20, GlobalSettings.WHITE_TEXT_COLOR);
			_goalieMindTogglePanel.layoutData = new AnchorLayoutData(0, 0, 0, 0, 0, 0);
			_goalieMindTogglePanel.isEnabled = isGoalieMind;
			
			var orgHeadingLayoutData:AnchorLayoutData = new AnchorLayoutData(44 * _manager.appCore.theme.scale, NaN, NaN, NaN, 0, NaN);
			_goalieMindHeading = new Label();
			_goalieMindHeading.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_goalieMindHeading.styleNameList.add(GlobalSettings.LABEL_RED_HEADING);
			_goalieMindHeading.layoutData = orgHeadingLayoutData;
			_goalieMindHeading.text = "GoalieMinds";
			_goalieMindHeading.isEnabled = isGoalieMind;
			
			var orgDetailsLayoutData:AnchorLayoutData = new AnchorLayoutData(40 * _manager.appCore.theme.scale, NaN, NaN, NaN, 0, NaN);
			orgDetailsLayoutData.topAnchorDisplayObject = _goalieMindHeading;
			orgDetailsLayoutData.percentWidth = 96;
			_goalieMindDetails = new Label();
			_goalieMindDetails.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_goalieMindDetails.styleNameList.add(GlobalSettings.LABEL_RED_DETAILS);
			_goalieMindDetails.layoutData = orgDetailsLayoutData;
			_goalieMindDetails.textRendererProperties.textAlign = TextBlockTextRenderer.TEXT_ALIGN_CENTER;;
			_goalieMindDetails.textRendererProperties.wordWrap = true;
			_goalieMindDetails.text = "You encourage MindGoalies to reach their dreams by investing in them";
			_goalieMindDetails.isEnabled = isGoalieMind;
			
			var orgImageLayoutData:AnchorLayoutData = new AnchorLayoutData(50 * _manager.appCore.theme.scale, NaN, NaN, 140 * _manager.appCore.theme.scale, NaN, NaN);
			orgImageLayoutData.topAnchorDisplayObject = _goalieMindDetails;
			_goalieMindImageButton = new ToggleButton();
			_goalieMindImageButton.touchable = false;
			_goalieMindImageButton.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_goalieMindImageButton.styleNameList.add(GlobalSettings.BUTTON_GOALIEMIND_TOGGLE);
			_goalieMindImageButton.layoutData = orgImageLayoutData;
			_goalieMindImageButton.isSelected = isGoalieMind;
			
			var orgToggleLayoutData:AnchorLayoutData = new AnchorLayoutData(70 * _manager.appCore.theme.scale, 210 * _manager.appCore.theme.scale, NaN, NaN, NaN, NaN);
			orgToggleLayoutData.topAnchorDisplayObject = _goalieMindDetails;
			_goalieMindToggleBtn = new ToggleButton();
			_goalieMindToggleBtn.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_goalieMindToggleBtn.styleNameList.add(GlobalSettings.BUTTON_TOGGLE);
			_goalieMindToggleBtn.layoutData = orgToggleLayoutData;
			_goalieMindToggleBtn.isSelected = isGoalieMind;
			_goalieMindToggleBtn.name = TOGGLE_GOALIEMIND;
			_goalieMindToggleBtn.addEventListener(Event.CHANGE, toggleButton_changeHandler);
			
			_goalieMindPanel.addChild(DisplayObject(_goalieMindTogglePanel));
			_goalieMindPanel.addChild(DisplayObject(_goalieMindImageButton));
			_goalieMindPanel.addChild(DisplayObject(_goalieMindHeading));
			_goalieMindPanel.addChild(DisplayObject(_goalieMindDetails));
			_goalieMindPanel.addChild(DisplayObject(_goalieMindToggleBtn));
			
			this.addChild(_goalieMindPanel);
			_goalieMindPanel.validate();
		}
		
		private function toggleButton_changeHandler(event:Event):void
		{
			var toggle:ToggleButton = ToggleButton( event.currentTarget );
			
			_mindGoalieTogglePanel.alignPivot();
			_goalieMindTogglePanel.alignPivot();
				
			if (toggle.name == TOGGLE_MINDGOALIE) 
			{
				this.isEnabled = false;
				
				if (toggle.isSelected == true)
				{
					_goalieMindToggleBtn.isSelected = false;
					_goalieMindToggleBtn.isEnabled = true;
					_mindGoalieToggleBtn.isEnabled = false;
					
					_mindGoalieTogglePanel.scaleX = _mindGoalieTogglePanel.scaleY = 0;
					_mindGoalieTogglePanel.alpha = 1;
					_mindGoalieTogglePanel.isEnabled = toggle.isSelected;
					
					_mindGoalieImageButton.isSelected = toggle.isSelected;
					_mindGoalieHeading.isEnabled = toggle.isSelected;
					_mindGoalieDetails.isEnabled = toggle.isSelected;
							
					Starling.juggler.tween(_mindGoalieTogglePanel, 0.5, {
						transition: Transitions.EASE_OUT,
						onComplete: function():void 
						{ 
							_mindGoalieTogglePanel.isEnabled = toggle.isSelected;
							isEnabled = true;
							_nextHeaderButton.isEnabled = true;
						},
						scaleX:1,
						scaleY:1
					});
				}
				else
				{
					_mindGoalieImageButton.isSelected = toggle.isSelected;
					_mindGoalieHeading.isEnabled = toggle.isSelected;
					_mindGoalieDetails.isEnabled = toggle.isSelected;
					
					Starling.juggler.tween(_mindGoalieTogglePanel, 0.5, {
						transition: Transitions.EASE_OUT,
						onComplete: function():void 
						{ 
							_mindGoalieTogglePanel.isEnabled = toggle.isSelected;
							isEnabled = true;
							_nextHeaderButton.isEnabled = true;
						},
						alpha:0
					});
				}
			} 
			else if (toggle.name == TOGGLE_GOALIEMIND) 
			{
				this.isEnabled = false;
				
				if (toggle.isSelected == true)
				{
					_mindGoalieToggleBtn.isSelected = false;
					_mindGoalieToggleBtn.isEnabled = true;
					_goalieMindToggleBtn.isEnabled = false;
					
					_goalieMindTogglePanel.scaleX = _goalieMindTogglePanel.scaleY = 0;
					_goalieMindTogglePanel.alpha = 1;
					_goalieMindTogglePanel.isEnabled = toggle.isSelected;
					
					_goalieMindImageButton.isSelected = toggle.isSelected;
					_goalieMindHeading.isEnabled = toggle.isSelected;
					_goalieMindDetails.isEnabled = toggle.isSelected;
							
					Starling.juggler.tween(_goalieMindTogglePanel, 0.5, {
						transition: Transitions.EASE_OUT,
						onComplete: function():void 
						{ 
							_goalieMindTogglePanel.isEnabled = toggle.isSelected;
							isEnabled = true;
							_nextHeaderButton.isEnabled = true;
						},
						scaleX:1,
						scaleY:1
					});
				}
				else
				{
					_goalieMindImageButton.isSelected = toggle.isSelected;
					_goalieMindHeading.isEnabled = toggle.isSelected;
					_goalieMindDetails.isEnabled = toggle.isSelected;
					
					Starling.juggler.tween(_goalieMindTogglePanel, 0.5, {
						transition: Transitions.EASE_OUT,
						onComplete: function():void 
						{ 
							_goalieMindTogglePanel.isEnabled = toggle.isSelected;
							isEnabled = true;
							_nextHeaderButton.isEnabled = true;
						},
						alpha:0
					});
				}
				
			}
			
			isGoalieMind = _goalieMindToggleBtn.isSelected;
			isMindGoalie = _mindGoalieToggleBtn.isSelected;
			
			this.activateNextButton();
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
			_nextHeaderButton.isEnabled = false;
			
			header.leftItems = new <DisplayObject> [ _backHeaderButton ];
			header.rightItems = new <DisplayObject> [ _nextHeaderButton ];
			
			activateNextButton();
			
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
				if (!_mindGoalieToggleBtn.isSelected) _manager.userData.accountType = AccountType.GOALIEMIND;
				else
				{
					// this will be determinded in the next screen
				}
				this.dispatchEventWith("selectedGoalieAccount", false, 
				{ 
					status:_mindGoalieToggleBtn.isSelected,
					isGoalieMind:isGoalieMind, 
					isMindGoalie:isMindGoalie
				});
			}
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