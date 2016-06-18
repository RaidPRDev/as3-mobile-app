package goaliemind.view.createuser.screens 
{
	import feathers.controls.Alert;
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.Label;
	import feathers.controls.Panel;
	import feathers.controls.ToggleButton;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;
	import feathers.layout.HorizontalLayout;

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
	public class TermsScreen extends CreateUserPanelScreen
	{
		private static const PRIVACYPOLICY:String = "privacypolicy";
		private static const TERMSOFUSE:String = "termsofuse";
		
		private var _subPanel:Panel;
		private var _subPanelContent:Button;
		private var _subHeaderLabel:Label;
		
		private var _toggleBtn:ToggleButton;
		private var _agreePart1:Label;
		private var _agreePart2:Label;
		private var _privacyBtn:Button;
		private var _termsOfUseBtn:Button;
		
		public var isToggleSelected:Boolean;
		
		public function TermsScreen() 
		{
			super();
		}
		
		override public function dispose():void
		{
			_subPanel.removeChild(DisplayObject(_subPanelContent));
			_subPanel.removeChild(DisplayObject(_subHeaderLabel));
			removeChild(DisplayObject(_subPanel));
			
			_toggleBtn.removeEventListener(Event.CHANGE, toggleButton_changeHandler);
			_privacyBtn.removeEventListener(Event.TRIGGERED, onButton);
			_termsOfUseBtn.removeEventListener(Event.TRIGGERED, onButton);
			
			// never forget to dispose textures!
			super.dispose();
		}	
		
		override protected function initialize():void
		{
			super.initialize();

			this.title = "Terms and Conditions";
			
			this.layout = new AnchorLayout();

			this.headerFactory = this.customHeaderFactory;
			
			this.createSubHeader();
			this.createToggle();
		}
		
		private function createToggle():void 
		{
			var topToggleMargin:Number = 250 * _manager.appCore.theme.scale;
			var topMargin:Number = 270 * _manager.appCore.theme.scale;
			
			var toggleLayoutData:AnchorLayoutData = new AnchorLayoutData();
			toggleLayoutData.top = topToggleMargin;
			toggleLayoutData.left = _manager.appCore.theme.regularPaddingSize;
			
			_toggleBtn = new ToggleButton();
			_toggleBtn.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_toggleBtn.styleNameList.add(GlobalSettings.BUTTON_RED_TOGGLE);
			_toggleBtn.layoutData = toggleLayoutData;
			_toggleBtn.isSelected = isToggleSelected;
			_toggleBtn.addEventListener(Event.CHANGE, toggleButton_changeHandler);
			
			this.addChild(DisplayObject(_toggleBtn));
			
			var agree1LayoutData:AnchorLayoutData = new AnchorLayoutData();
			agree1LayoutData.leftAnchorDisplayObject = _toggleBtn;
			agree1LayoutData.left = _manager.appCore.theme.smallPaddingSize;
			agree1LayoutData.top = topMargin;
			_agreePart1 = new Label();
			_agreePart1.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_agreePart1.styleNameList.add(GlobalSettings.LABEL_TERMS);
			_agreePart1.layoutData = agree1LayoutData;
			_agreePart1.text = "I agree to the";
			
			var privacyLayoutData:AnchorLayoutData = new AnchorLayoutData();
			privacyLayoutData.leftAnchorDisplayObject = _agreePart1;
			privacyLayoutData.left = 5 * _manager.appCore.theme.scale;
			privacyLayoutData.top = topMargin;
			_privacyBtn = new Button();
			_privacyBtn.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_privacyBtn.styleNameList.add(GlobalSettings.BUTTON_TERMS);
			_privacyBtn.layoutData = privacyLayoutData;
			_privacyBtn.label = "Privacy Policy";
			_privacyBtn.name = PRIVACYPOLICY;
			
			var agree2LayoutData:AnchorLayoutData = new AnchorLayoutData();
			agree2LayoutData.leftAnchorDisplayObject = _privacyBtn;
			agree2LayoutData.left = 5 * _manager.appCore.theme.scale;
			agree2LayoutData.top = topMargin;
			_agreePart2 = new Label();
			_agreePart2.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_agreePart2.styleNameList.add(GlobalSettings.LABEL_TERMS);
			_agreePart2.layoutData = agree2LayoutData;
			_agreePart2.text = "and";

			var termsLayoutData:AnchorLayoutData = new AnchorLayoutData();
			termsLayoutData.leftAnchorDisplayObject = _agreePart2;
			termsLayoutData.left = 5 * _manager.appCore.theme.scale;
			termsLayoutData.top = topMargin;
			_termsOfUseBtn = new Button();
			_termsOfUseBtn.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_termsOfUseBtn.styleNameList.add(GlobalSettings.BUTTON_TERMS);
			_termsOfUseBtn.layoutData = termsLayoutData;
			_termsOfUseBtn.label = "Terms of Use";
			_termsOfUseBtn.name = TERMSOFUSE;
			
			this.addChild(DisplayObject(_agreePart1));
			this.addChild(DisplayObject(_privacyBtn));
			this.addChild(DisplayObject(_agreePart2));
			this.addChild(DisplayObject(_termsOfUseBtn));
			
			_privacyBtn.addEventListener(Event.TRIGGERED, onButton);
			_termsOfUseBtn.addEventListener(Event.TRIGGERED, onButton);
		}
		
		private function onButton(e:Event):void 
		{
			var btn:Button = e.target as Button;
			if (!btn) return;
			
			isToggleSelected = _toggleBtn.isSelected;
			
			if (btn.name == PRIVACYPOLICY)
			{
				this.dispatchEventWith(GlobalSettings.PRIVACYPOLICY, false, { isToggleSelected:isToggleSelected } );
			}
			else if (btn.name == TERMSOFUSE)
			{
				this.dispatchEventWith(GlobalSettings.TERMSOFUSE, false, { isToggleSelected:isToggleSelected } );
			}
		}
		
		private function toggleButton_changeHandler(e:Event):void 
		{
			isToggleSelected = _toggleBtn.isSelected;
			_nextHeaderButton.isEnabled = isToggleSelected;
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
			_subPanelContent.defaultIcon = this.loadImageFactory(_manager.appCore.theme.createUserTheme.largePrivacyIconTexture);
			_subPanelContent.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_subPanelContent.styleNameList.add(GlobalSettings.BUTTON_SUBHEADER);
			
			_subHeaderLabel = new Label();
			_subHeaderLabel.width = 260 * _manager.appCore.theme.scale;
			_subHeaderLabel.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_subHeaderLabel.styleNameList.add(GlobalSettings.LABEL_RED_DETAILS);
			_subHeaderLabel.textRendererProperties.wordWrap = true;
			_subHeaderLabel.textRendererProperties.textAlign = "center";
			_subHeaderLabel.textRendererProperties.leading = _manager.appCore.theme.fontLeading * _manager.appCore.theme.scale;
			_subHeaderLabel.text = "We are committed to\nprotecting your privacy.";
			
			addChild(DisplayObject(_subPanel));

			_subPanel.addChild(DisplayObject(_subPanelContent));
			_subPanel.addChild(DisplayObject(_subHeaderLabel));
			
			_subPanel.validate();
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
			_nextHeaderButton.isEnabled = isToggleSelected;
			
			header.leftItems = new <DisplayObject> [ _backHeaderButton ];
			header.rightItems = new <DisplayObject> [ _nextHeaderButton ];
			
			return header;
		}
		
		private var _registrationAlert:Alert;
		
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
				
				this.sendRegistration();
			}
		}	
		
		private function sendRegistration():void 
		{
			showAlert("Sending...", "Registration", null);
			
			_manager.serverHandler.sendRegistration(_manager.userData, onRegistrationComplete);
			
			function onRegistrationComplete(data:Object):void
			{
				trace("sendRegistration.sendRegistration.onRegistrationComplete() " + data);
				
				if (data["ERROR"] != undefined) 
				{
					removeAlert();
					showAlert(data["ERROR"][0], "Registration Failed", [ { label: "OK" } ]);
				}
				else if (data["OK"] != undefined)
				{
					removeAlert();
					showAlert("You have successfully registered.", "Success!", [ { label: "OK" } ]);
					_alert.addEventListener(Event.CLOSE, onAlertSuccessClose);
				}
				
				_nextHeaderButton.isEnabled = true;
			}
		}			
		
		private function onAlertSuccessClose(e:Event):void 
		{
			_alert.removeEventListener(Event.CLOSE, onAlertSuccessClose);
			
			// registrationComplete, continue
			dispatchEventWith("registrationComplete");
		}
		
		override protected function draw():void
		{
			var layoutInvalid:Boolean = this.isInvalid(INVALIDATION_FLAG_LAYOUT);

			if(layoutInvalid)
			{
				// Reset Padding for this screen only
				this.paddingLeft = _manager.appCore.theme.regularPaddingSize;
				this.paddingRight = _manager.appCore.theme.regularPaddingSize;
				this.paddingTop = 0;
				this.paddingBottom = _manager.appCore.theme.regularPaddingSize;
			}

			// never forget to call super.draw()
			super.draw();
		}

	}

}