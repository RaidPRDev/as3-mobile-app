package goaliemind.view.createuser.screens 
{
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.Label;
	import feathers.controls.Panel;
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
	public class SelectMindGoalieScreen extends CreateUserPanelScreen
	{
		private const TOGGLE_PERSONAL:String = "personal";
		private const TOGGLE_ORG:String = "organization";
		
		private var _personalPanel:Panel;
		private var _personalTogglePanel:Panel;
		private var _personalImageButton:ToggleButton;
		private var _personalHeading:Label;
		private var _personalDetails:Label;
		private var _personalSmallText:Label;
		private var _personalToggleBtn:ToggleButton;
		
		private var _orgPanel:Panel;
		private var _orgTogglePanel:Panel;
		private var _orgImageButton:ToggleButton;
		private var _orgHeading:Label;
		private var _orgDetails:Label;
		private var _orgToggleBtn:ToggleButton;
		
		public var isMindGoaliePersonal:Boolean, isMindGoalieOrg:Boolean;
		
		public function SelectMindGoalieScreen() 
		{
			super();
		}
		
		override public function dispose():void
		{
			// never forget to dispose textures!
			
			_personalToggleBtn.removeEventListener(Event.CHANGE, toggleButton_changeHandler);
			_personalPanel.removeChild(_personalTogglePanel);
			_personalPanel.removeChild(_personalImageButton);
			_personalPanel.removeChild(_personalHeading);
			_personalPanel.removeChild(_personalDetails);
			_personalPanel.removeChild(_personalToggleBtn);
			_personalPanel.removeChild(_personalSmallText);
			this.removeChild(_personalPanel);			
			
			_orgToggleBtn.removeEventListener(Event.CHANGE, toggleButton_changeHandler);
			_orgPanel.removeChild(_orgTogglePanel);
			_orgPanel.removeChild(_orgImageButton);
			_orgPanel.removeChild(_orgHeading);
			_orgPanel.removeChild(_orgDetails);
			_orgPanel.removeChild(_orgToggleBtn);
			this.removeChild(_orgPanel);				
			
			super.dispose();
		}
		
		override protected function initialize():void
		{
			super.initialize();

			if (_manager.userData.accountType == -1) 
			{
				isMindGoaliePersonal = isMindGoalieOrg = false;
			}
			
			this.title = "MindGoalies";
			
			this.layout = new AnchorLayout();

			this.headerFactory = this.customHeaderFactory;
			
			this.createPersonalToggle();
			
			this.createOrgToggle();
		}
		
		private function activateNextButton():void
		{
			if (isMindGoaliePersonal || isMindGoalieOrg) _nextHeaderButton.isEnabled = true;
			else _nextHeaderButton.isEnabled = false;			
		}		
		
		private function createPersonalToggle():void
		{
			var personalPanelLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0, 0, NaN);
			personalPanelLayoutData.percentHeight = 50;
			_personalPanel = new Panel();
			_personalPanel.backgroundSkin = new Quad(20, 20, GlobalSettings.WHITE_TEXT_COLOR);
			_personalPanel.layoutData = personalPanelLayoutData;
			_personalPanel.layout = new AnchorLayout();
			
			_personalTogglePanel = new Panel();
			_personalTogglePanel.backgroundSkin = new Quad(20, 20, GlobalSettings.BLUE_TEXT_COLOR);
			_personalTogglePanel.backgroundDisabledSkin = new Quad(20, 20, GlobalSettings.WHITE_TEXT_COLOR);
			_personalTogglePanel.layoutData = new AnchorLayoutData(0, 0, 0, 0, 0, 0);
			_personalTogglePanel.isEnabled = isMindGoaliePersonal;
			
			var personalImageLayoutData:AnchorLayoutData = new AnchorLayoutData(13 * _manager.appCore.theme.scale, NaN, NaN, 27 * _manager.appCore.theme.scale, NaN, NaN);
			_personalImageButton = new ToggleButton();
			_personalImageButton.touchable = false;
			_personalImageButton.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_personalImageButton.styleNameList.add(GlobalSettings.BUTTON_PERSONAL_TOGGLE);
			_personalImageButton.layoutData = personalImageLayoutData;
			_personalImageButton.isSelected = isMindGoaliePersonal;
			
			var personalHeadingLayoutData:AnchorLayoutData = new AnchorLayoutData(44 * _manager.appCore.theme.scale, NaN, NaN, 252 * _manager.appCore.theme.scale, NaN, NaN);
			_personalHeading = new Label();
			_personalHeading.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_personalHeading.styleNameList.add(GlobalSettings.LABEL_BLUE_HEADING);
			_personalHeading.layoutData = personalHeadingLayoutData;
			_personalHeading.text = "Personal";
			_personalHeading.isEnabled = isMindGoaliePersonal;
			
			var personalDetailsLayoutData:AnchorLayoutData = new AnchorLayoutData(20 * _manager.appCore.theme.scale, 18 * _manager.appCore.theme.scale, NaN, 252 * _manager.appCore.theme.scale, NaN, NaN);
			personalDetailsLayoutData.topAnchorDisplayObject = _personalHeading;
			personalDetailsLayoutData.percentWidth = 25;
			personalDetailsLayoutData.percentHeight = 25;
			_personalDetails = new Label();
			_personalDetails.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_personalDetails.styleNameList.add(GlobalSettings.LABEL_BLUE_DETAILS);
			_personalDetails.layoutData = personalDetailsLayoutData;
			_personalDetails.textRendererProperties.wordWrap = true;
			_personalDetails.text = "Can only be created by an adult, parent or guardian, it will allow kids to upload their favorite projects.";
			_personalDetails.isEnabled = isMindGoaliePersonal;
			
			var personalToggleLayoutData:AnchorLayoutData = new AnchorLayoutData(40 * _manager.appCore.theme.scale, 144 * _manager.appCore.theme.scale, NaN, NaN, NaN, NaN);
			personalToggleLayoutData.topAnchorDisplayObject = _personalDetails;
			_personalToggleBtn = new ToggleButton();
			_personalToggleBtn.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_personalToggleBtn.styleNameList.add(GlobalSettings.BUTTON_TOGGLE);
			_personalToggleBtn.layoutData = personalToggleLayoutData;
			_personalToggleBtn.isSelected = isMindGoaliePersonal;
			_personalToggleBtn.name = TOGGLE_PERSONAL;
			_personalToggleBtn.addEventListener(Event.CHANGE, toggleButton_changeHandler);
			
			var personalSmallTextLayoutData:AnchorLayoutData = new AnchorLayoutData(20 * _manager.appCore.theme.scale, NaN, NaN, 20 * _manager.appCore.theme.scale, NaN, NaN);
			personalSmallTextLayoutData.topAnchorDisplayObject = _personalImageButton;
			_personalSmallText = new Label();
			_personalSmallText.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_personalSmallText.styleNameList.add(GlobalSettings.LABEL_XSMALL_BLACK);
			_personalSmallText.layoutData = personalSmallTextLayoutData;
			_personalSmallText.text = "* GoalieMind account required";
			
			_personalPanel.addChild(DisplayObject(_personalTogglePanel));
			_personalPanel.addChild(DisplayObject(_personalImageButton));
			_personalPanel.addChild(DisplayObject(_personalHeading));
			_personalPanel.addChild(DisplayObject(_personalDetails));
			_personalPanel.addChild(DisplayObject(_personalToggleBtn));
			_personalPanel.addChild(DisplayObject(_personalSmallText));
			
			this.addChild(_personalPanel);
			_personalPanel.validate();
		}
		
		private function createOrgToggle():void
		{
			var orgPanelLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0, 0, NaN);
			orgPanelLayoutData.percentHeight = 50;
			orgPanelLayoutData.topAnchorDisplayObject = _personalPanel;
			_orgPanel = new Panel();
			_orgPanel.backgroundSkin = new Quad(20, 20, GlobalSettings.WHITE_TEXT_COLOR);
			_orgPanel.layoutData = orgPanelLayoutData;
			_orgPanel.layout = new AnchorLayout();
			
			_orgTogglePanel = new Panel();
			_orgTogglePanel.backgroundSkin = new Quad(20, 20, GlobalSettings.RED_TEXT_COLOR);
			_orgTogglePanel.backgroundDisabledSkin = new Quad(20, 20, GlobalSettings.WHITE_TEXT_COLOR);
			_orgTogglePanel.layoutData = new AnchorLayoutData(0, 0, 0, 0, 0, 0);
			_orgTogglePanel.isEnabled = isMindGoalieOrg;
			
			var orgImageLayoutData:AnchorLayoutData = new AnchorLayoutData(NaN, NaN, 0, 27 * _manager.appCore.theme.scale, NaN, NaN);
			_orgImageButton = new ToggleButton();
			_orgImageButton.touchable = false;
			_orgImageButton.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_orgImageButton.styleNameList.add(GlobalSettings.BUTTON_ORGANIZATION_TOGGLE);
			_orgImageButton.layoutData = orgImageLayoutData;
			_orgImageButton.isSelected = isMindGoalieOrg;
			
			var orgHeadingLayoutData:AnchorLayoutData = new AnchorLayoutData(44 * _manager.appCore.theme.scale, NaN, NaN, 252 * _manager.appCore.theme.scale, NaN, NaN);
			_orgHeading = new Label();
			_orgHeading.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_orgHeading.styleNameList.add(GlobalSettings.LABEL_RED_HEADING);
			_orgHeading.layoutData = orgHeadingLayoutData;
			_orgHeading.text = "Teachers/Organizations";
			_orgHeading.isEnabled = isMindGoalieOrg;
			
			var orgDetailsLayoutData:AnchorLayoutData = new AnchorLayoutData(20 * _manager.appCore.theme.scale, 18 * _manager.appCore.theme.scale, NaN, 252 * _manager.appCore.theme.scale, NaN, NaN);
			orgDetailsLayoutData.topAnchorDisplayObject = _orgHeading;
			orgDetailsLayoutData.percentWidth = 25;
			orgDetailsLayoutData.percentHeight = 25;
			_orgDetails = new Label();
			_orgDetails.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_orgDetails.styleNameList.add(GlobalSettings.LABEL_RED_DETAILS);
			_orgDetails.layoutData = orgDetailsLayoutData;
			_orgDetails.textRendererProperties.wordWrap = true;
			_orgDetails.text = "Will be presented with a variety of pre-defined or custom assets that can uploaded as class projects or events to raise money.";
			_orgDetails.isEnabled = isMindGoalieOrg;
			
			var orgToggleLayoutData:AnchorLayoutData = new AnchorLayoutData(40 * _manager.appCore.theme.scale, 144 * _manager.appCore.theme.scale, NaN, NaN, NaN, NaN);
			orgToggleLayoutData.topAnchorDisplayObject = _orgDetails;
			_orgToggleBtn = new ToggleButton();
			_orgToggleBtn.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_orgToggleBtn.styleNameList.add(GlobalSettings.BUTTON_TOGGLE);
			_orgToggleBtn.layoutData = orgToggleLayoutData;
			_orgToggleBtn.isSelected = isMindGoalieOrg;
			_orgToggleBtn.name = TOGGLE_ORG;
			_orgToggleBtn.addEventListener(Event.CHANGE, toggleButton_changeHandler);
			
			_orgPanel.addChild(DisplayObject(_orgTogglePanel));
			_orgPanel.addChild(DisplayObject(_orgImageButton));
			_orgPanel.addChild(DisplayObject(_orgHeading));
			_orgPanel.addChild(DisplayObject(_orgDetails));
			_orgPanel.addChild(DisplayObject(_orgToggleBtn));
			
			this.addChild(_orgPanel);
			_orgPanel.validate();
		}
		
		private function toggleButton_changeHandler(event:Event):void
		{
			var toggle:ToggleButton = ToggleButton( event.currentTarget );
			
			_personalTogglePanel.alignPivot();
			_orgTogglePanel.alignPivot();
				
			if (toggle.name == TOGGLE_PERSONAL) 
			{
				this.isEnabled = false;
				
				if (toggle.isSelected == true)
				{
					_orgToggleBtn.isSelected = false;
					_orgToggleBtn.isEnabled = true;
					_personalToggleBtn.isEnabled = false;
					
					_personalTogglePanel.scaleX = _personalTogglePanel.scaleY = 0;
					_personalTogglePanel.alpha = 1;
					_personalTogglePanel.isEnabled = toggle.isSelected;
					
					_personalImageButton.isSelected = toggle.isSelected;
					_personalHeading.isEnabled = toggle.isSelected;
					_personalDetails.isEnabled = toggle.isSelected;
							
					Starling.juggler.tween(_personalTogglePanel, 0.5, {
						transition: Transitions.EASE_OUT,
						onComplete: function():void 
						{ 
							_personalTogglePanel.isEnabled = toggle.isSelected;
							isEnabled = true;
							_nextHeaderButton.isEnabled = true;
						},
						scaleX:1,
						scaleY:1
					});
				}
				else
				{
					_personalImageButton.isSelected = toggle.isSelected;
					_personalHeading.isEnabled = toggle.isSelected;
					_personalDetails.isEnabled = toggle.isSelected;
					
					Starling.juggler.tween(_personalTogglePanel, 0.5, {
						transition: Transitions.EASE_OUT,
						onComplete: function():void 
						{ 
							_personalTogglePanel.isEnabled = toggle.isSelected;
							isEnabled = true;
							_nextHeaderButton.isEnabled = true;
						},
						alpha:0
					});
				}
			} 
			else if (toggle.name == TOGGLE_ORG) 
			{
				this.isEnabled = false;
				
				if (toggle.isSelected == true)
				{
					_personalToggleBtn.isSelected = false;
					_personalToggleBtn.isEnabled = true;
					_orgToggleBtn.isEnabled = false;
					
					_orgTogglePanel.scaleX = _orgTogglePanel.scaleY = 0;
					_orgTogglePanel.alpha = 1;
					_orgTogglePanel.isEnabled = toggle.isSelected;
					
					_orgImageButton.isSelected = toggle.isSelected;
					_orgHeading.isEnabled = toggle.isSelected;
					_orgDetails.isEnabled = toggle.isSelected;
							
					Starling.juggler.tween(_orgTogglePanel, 0.5, {
						transition: Transitions.EASE_OUT,
						onComplete: function():void 
						{ 
							_orgTogglePanel.isEnabled = toggle.isSelected;
							isEnabled = true;
							_nextHeaderButton.isEnabled = true;
						},
						scaleX:1,
						scaleY:1
					});
				}
				else
				{
					_orgImageButton.isSelected = toggle.isSelected;
					_orgHeading.isEnabled = toggle.isSelected;
					_orgDetails.isEnabled = toggle.isSelected;
					
					Starling.juggler.tween(_orgTogglePanel, 0.5, {
						transition: Transitions.EASE_OUT,
						onComplete: function():void 
						{ 
							_orgTogglePanel.isEnabled = toggle.isSelected;
							isEnabled = true;
							_nextHeaderButton.isEnabled = true;
						},
						alpha:0
					});
				}
				
			}
			
			isMindGoaliePersonal = _personalToggleBtn.isSelected;
			isMindGoalieOrg = _orgToggleBtn.isSelected;
			
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
				if (!_personalToggleBtn.isSelected) _manager.userData.accountType = AccountType.ORGANIZATION_MINDGOALIE;
				else _manager.userData.accountType = AccountType.PERSONAL_MINDGOALIE;
				
				this.dispatchEventWith("selectedMindGoalieAccount", false, 
				{ 
					status:_personalToggleBtn.isSelected,
					isMindGoaliePersonal:isMindGoaliePersonal, 
					isMindGoalieOrg:isMindGoalieOrg
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