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
	public class EnterUsernameScreen extends CreateUserPanelScreen
	{
		private var _subPanel:Panel;
		private var _subPanelContent:Button;
		private var _subHeaderLabel:Label;
		
		private var _input:TextInput;
		
		public function EnterUsernameScreen() 
		{
			super();
		}
		
		override public function dispose():void
		{
			this._input.removeEventListener(Event.CHANGE, onInputChange);
			this._input.removeEventListener(FeathersEventType.ENTER, onInputEnter);
			
			_subPanel.removeChild(DisplayObject(_subPanelContent));
			_subPanel.removeChild(DisplayObject(_subHeaderLabel));
			removeChild(DisplayObject(_subPanel));
			
			removeChild(DisplayObject(_input));
			
			// never forget to dispose textures!
			super.dispose();
		}	
		
		override protected function initialize():void
		{
			super.initialize();

			this.title = "Username";
			
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
			_subPanel.backgroundSkin = new Quad(20, GlobalSettings.SUBHEADER_HEIGHT * _manager.appCore.theme.scale, GlobalSettings.BLUE_TEXT_COLOR);
			_subPanel.layoutData = panelLayoutData;
			_subPanel.layout = verticalLayout;
			
			_subPanelContent = new Button();
			_subPanelContent.defaultIcon = this.loadImageFactory(_manager.appCore.theme.createUserTheme.largeUserIconTexture);
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
		
		public var usernameTxt:String;
		
		private function createInput():void
		{
			var inputLayoutData:AnchorLayoutData = new AnchorLayoutData(0, NaN, NaN, NaN, 0, NaN);
			inputLayoutData.topAnchorDisplayObject = _subPanel;
			inputLayoutData.percentWidth = GlobalSettings.SUBHEADER_INPUT_WIDTH_PERCENT;
			
			this._input = new TextInput();
			this._input.prompt = "Choose a username";
			this._input.layoutData = inputLayoutData;
			this._input.textEditorProperties.textAlign = "center";
			this._input.promptProperties.textAlign = "center";
			this._input.restrict = GlobalSettings.FIELD_RESTRICT;
			this.addChild(DisplayObject(this._input));
			
			if (usernameTxt) this._input.text = usernameTxt;
			
			this._input.addEventListener(Event.CHANGE, onInputChange);
			this._input.addEventListener(FeathersEventType.ENTER, onInputEnter);
		}
		
		private function onInputEnter(e:Event):void 
		{
			this._input.clearFocus();
			
			if (this._input.text.length == 0)
			{
				_status.text = "Enter username";
			}
			else this.checkUsername();
		}
		
		private function checkUsername():void 
		{
			_manager.serverHandler.checkUsername(this._input.text, onCheckComplete);
			
			function onCheckComplete(data:Object):void
			{
				trace("EnterUsernameScreen.checkUsername.onCheckComplete() " + data);
				
				if (data["ERROR"] != undefined) 
				{
					// Show Message
					_status.text = data["ERROR"][0];
				}
				else if (data["OK"] != undefined)
				{
					// Username valid, continue
					_manager.userData.username = _input.text;
					dispatchEventWith(CreateUserPanelScreen.HEADER_NEXT, false, { usernameTxt:_input.text });
				}
				
				_nextHeaderButton.isEnabled = true;
			}
		}
		
		private function onInputChange(e:Event):void 
		{
			var uname:String = this._input.text;
			
			_nextHeaderButton.isEnabled = (uname.length > 0);
		}
		
		private function createStatusLabel():void
		{
			var statusLayoutData:AnchorLayoutData = new AnchorLayoutData(8 * _manager.appCore.theme.scale, NaN, NaN, NaN, 0, NaN);
			statusLayoutData.topAnchorDisplayObject = this._input;
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
			_nextHeaderButton.isEnabled = (usernameTxt) ? true : false;
			
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
				checkUsername();
			}
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