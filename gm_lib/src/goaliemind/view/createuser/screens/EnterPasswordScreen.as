package goaliemind.view.createuser.screens 
{
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.Label;
	import feathers.controls.Panel;
	import feathers.controls.TextInput;
	import feathers.events.FeathersEventType;
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
	public class EnterPasswordScreen extends CreateUserPanelScreen
	{
		private var _subPanel:Panel;
		private var _subPanelContent:Button;
		private var _subHeaderLabel:Label;
		
		private var _input:TextInput;
		private var _inputAgain:TextInput;
		
		public function EnterPasswordScreen() 
		{
			super();
		}
		
		override public function dispose():void
		{
			_subPanel.removeChild(DisplayObject(_subPanelContent));
			_subPanel.removeChild(DisplayObject(_subHeaderLabel));
			removeChild(DisplayObject(_subPanel));
			
			_input.removeEventListener(FeathersEventType.ENTER, onInputEnter);
			_inputAgain.removeEventListener(FeathersEventType.ENTER, onInputEnter);
			_input.removeEventListener(FeathersEventType.ENTER, onInputChange);
			_inputAgain.removeEventListener(FeathersEventType.ENTER, onInputChange);						
			
			removeChild(DisplayObject(_input));
			removeChild(DisplayObject(_inputAgain));
			
			// never forget to dispose textures!
			super.dispose();
		}	
		
		override protected function initialize():void
		{
			super.initialize();

			this.title = "Password";
			
			this.layout = new AnchorLayout();

			this.headerFactory = this.customHeaderFactory;
			
			this.createSubHeader();
			this.createInput();
			this.createStatusLabel();
		}
		
		private function createSubHeader():void
		{
			var panelLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0, 0, NaN);
			
			var verticalLayout:HorizontalLayout = new HorizontalLayout();
			verticalLayout.horizontalAlign = HorizontalLayout.HORIZONTAL_ALIGN_CENTER;
			verticalLayout.verticalAlign = HorizontalLayout.VERTICAL_ALIGN_MIDDLE;
			verticalLayout.gap = _manager.appCore.theme.regularPaddingSize;
			
			_subPanel = new Panel();
			_subPanel.backgroundSkin = new Quad(20, GlobalSettings.SUBHEADER_HEIGHT * _manager.appCore.theme.scale, GlobalSettings.RED_TEXT_COLOR);
			_subPanel.layoutData = panelLayoutData;
			_subPanel.layout = verticalLayout;
			
			_subPanelContent = new Button();
			_subPanelContent.defaultIcon = this.loadImageFactory(_manager.appCore.theme.createUserTheme.largePasswordIconTexture);
			_subPanelContent.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_subPanelContent.styleNameList.add(GlobalSettings.BUTTON_SUBHEADER);
			
			_subHeaderLabel = new Label();
			_subHeaderLabel.width = 260 * _manager.appCore.theme.scale;
			_subHeaderLabel.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_subHeaderLabel.styleNameList.add(GlobalSettings.LABEL_RED_DETAILS);
			_subHeaderLabel.textRendererProperties.wordWrap = true;
			_subHeaderLabel.textRendererProperties.textAlign = "center";
			_subHeaderLabel.textRendererProperties.leading = _manager.appCore.theme.fontLeading * _manager.appCore.theme.scale;
			_subHeaderLabel.text = "Create your own personalized experience";
			
			addChild(DisplayObject(_subPanel));

			_subPanel.addChild(DisplayObject(_subPanelContent));
			_subPanel.addChild(DisplayObject(_subHeaderLabel));
			
			_subPanel.validate();
		}
		
		private function createInput():void
		{
			var inputLayoutData:AnchorLayoutData = new AnchorLayoutData(0, NaN, NaN, NaN, 0, NaN);
			inputLayoutData.topAnchorDisplayObject = _subPanel;
			inputLayoutData.percentWidth = GlobalSettings.SUBHEADER_INPUT_WIDTH_PERCENT;
			
			this._input = new TextInput();
			this._input.prompt = "Choose a password";
			this._input.layoutData = inputLayoutData;
			this._input.textEditorProperties.textAlign = "center";
			this._input.promptProperties.textAlign = "center";
			this._input.name = "password01";
			this._input.restrict = GlobalSettings.FIELD_RESTRICT;
			
			var inputAgainLayoutData:AnchorLayoutData = new AnchorLayoutData(0, NaN, NaN, NaN, 0, NaN);
			inputAgainLayoutData.topAnchorDisplayObject = _input;
			inputAgainLayoutData.percentWidth = GlobalSettings.SUBHEADER_INPUT_WIDTH_PERCENT;
			
			this._inputAgain = new TextInput();
			this._inputAgain.prompt = "Enter password again";
			this._inputAgain.layoutData = inputAgainLayoutData;
			this._inputAgain.textEditorProperties.textAlign = "center";
			this._inputAgain.promptProperties.textAlign = "center";
			this._inputAgain.name = "password02";
			this._inputAgain.restrict = GlobalSettings.FIELD_RESTRICT;
			
			this.addChild(DisplayObject(this._input));
			this.addChild(DisplayObject(this._inputAgain));
			
			if (password1) this._input.text = password1;
			if (password2) this._inputAgain.text = password2;
			
			_input.addEventListener(FeathersEventType.ENTER, onInputEnter);
			_inputAgain.addEventListener(FeathersEventType.ENTER, onInputEnter);
			
			_input.addEventListener(Event.CHANGE, onInputChange);
			_inputAgain.addEventListener(Event.CHANGE, onInputChange);			
		}
		
		private function onInputChange(e:Event):void 
		{
			var pass1:String = _input.text;
			var pass2:String = _inputAgain.text;
			
			_nextHeaderButton.isEnabled = (pass1.length > 5 && pass2.length > 5);
		}		
		
		private function onInputEnter(e:Event):void 
		{
			var input:TextInput = e.target as TextInput;
			
			if (!input) return;
			
			var pass1:String = _input.text;
			var pass2:String = _inputAgain.text;
			
			if (pass1.length > 5 && pass2.length > 5)
			{
				if (pass1 == pass2)	this.checkPassword();
				else
				{
					// Wrong Password
					_status.text = "Password does not match";
				}
			}
			else
			{
				if (input.name == "password01")
				{
					if (pass1.length < 5) _status.text = "Password must be more than 6 characters";
					else _inputAgain.setFocus();
				}
			}
		}
		
		private function createStatusLabel():void
		{
			var statusLayoutData:AnchorLayoutData = new AnchorLayoutData(8 * _manager.appCore.theme.scale, NaN, NaN, NaN, 0, NaN);
			statusLayoutData.topAnchorDisplayObject = this._inputAgain;
			statusLayoutData.percentWidth = 90;
			
			_status = new Label();
			_status.layoutData = statusLayoutData;
			_status.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_status.styleNameList.add(GlobalSettings.LABEL_REGISTER_STATUS);
			
			this.addChild(_status);
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
			_nextHeaderButton.isEnabled = (password1 && password2) ? true : false;
			
			header.leftItems = new <DisplayObject> [ _backHeaderButton ];
			header.rightItems = new <DisplayObject> [ _nextHeaderButton ];
			
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
				checkPassword();
			}
		}	
		
		public var password1:String, password2:String;
		
		private function checkPassword():void 
		{
			_nextHeaderButton.isEnabled = true;
			
			// Password saved, continue
			
			_manager.userData.password = _input.text;
			
			dispatchEventWith(CreateUserPanelScreen.HEADER_NEXT, false, { password1:_input.text, password2:_inputAgain.text });
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