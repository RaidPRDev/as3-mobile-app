package goaliemind.view.home.overlays.banks.accounts 
{
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.List;
	import feathers.controls.Panel;
	import feathers.controls.TextInput;
	import feathers.layout.AnchorLayoutData;
	import feathers.layout.HorizontalLayout;
	import feathers.layout.VerticalLayout;
	
	import goaliemind.system.LogUtil;
	import goaliemind.ui.base.HomePanelScreen;
	
	import starling.display.DisplayObject;
	import starling.display.Quad;
	import starling.events.Event;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class AddCreditAccount extends HomePanelScreen
	{
		private var _subPanel:Panel;
		private var _subPanelContent:Button;
		private var _list:List;
		private var _inputList:Vector.<TextInput>;
		
		public function AddCreditAccount() 
		{
			super();
		}
		
		override protected function initialize():void 
		{
			super.initialize();
			
			var vLayout:VerticalLayout = new VerticalLayout();
			vLayout.gap = 12 * _manager.scale;
			vLayout.paddingTop = 12 * _manager.scale;
			vLayout.paddingLeft = 12 * _manager.scale;
			vLayout.paddingRight = 12 * _manager.scale;
			vLayout.paddingBottom = 12 * _manager.scale;
			vLayout.horizontalAlign = VerticalLayout.HORIZONTAL_ALIGN_JUSTIFY;
			
			this.layout = vLayout;
			
			this.title = "Add Credit Card";
			
			this.backgroundSkin = new Quad(10, 10, 0xFFFFFF);
			
			this.headerFactory = this.customHeaderFactory;
			
			createSubHeader();
			createList();
		}
		
		private function customHeaderFactory():Header
		{
			var header:Header = new Header();

			header.styleNameList.add(GlobalSettings.HOME_MAINHEADER);
			
			var backButton:Button = new Button();
			backButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
			backButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("header/back_header_icon", _manager.appCore.theme.scale);
			
			var actionButton:Button = new Button();
			actionButton.styleNameList.add(GlobalSettings.MAINHEADER_BUTTON);
			actionButton.defaultIcon = _manager.appCore.assets.imageLoaderFactory("header/check_header_icon", _manager.appCore.theme.scale);

			header.leftItems = new <DisplayObject> [ backButton ];
			header.rightItems = new <DisplayObject> [ actionButton ];
			
			backButton.addEventListener(Event.TRIGGERED, onBackButton);
			actionButton.addEventListener(Event.TRIGGERED, onActionButton);
			
			return header;
		}		
		
		private function onBackButton(e:Event):void 
		{
			LogUtil.log("AddCreditAccount.onBackButton()");
			
			this.dispatchEventWith("onBack");
		}			
		
		
		private function onActionButton(e:Event):void
		{
			LogUtil.log("AddCreditAccount.onActionButton()");
			
			for (var i:int = 0; i < _inputList.length; i++)
			{
				if (_inputList[i].text.length == 0)
				{
					LogUtil.log("Input Field is Empty - ID: " + _inputList[i].name + " prompt: " + _inputList[i].prompt);
					showAlert("Input Field is Empty " + _inputList[i].prompt, "Minimum Requirements", [ { label:"OK" } ]);
					_alert.addEventListener(Event.CLOSE, onAlertMessage);
					break;
				}
			}
			
			function onAlertMessage(e:Event):void 
			{
				removeAlert();
			}
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
			_subPanelContent.defaultIcon = _manager.appCore.assets.createUserImageLoaderFactory("large_icons/banks_large_icon", _manager.appCore.theme.scale);
			_subPanelContent.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			_subPanelContent.styleNameList.add(GlobalSettings.BUTTON_SUBHEADER);
			
			addChild(DisplayObject(_subPanel));
			
			_subPanel.addChild(DisplayObject(_subPanelContent));
			
			_subPanel.validate();
		}
		
		private function createList():void
		{
			_inputList = new Vector.<TextInput>();
			
			var items:Array = [];
			items[items.length] = { label:"Credit Card #", maxChars:100, icon:null };
			items[items.length] = { label:"Expiration Date (mm/yy)", maxChars:100, icon:null };
			items[items.length] = { label:"Security Code (CVV)", maxChars:100, icon:null };
			items[items.length] = { label:"Zip Code", maxChars:100, icon:null };
			items.fixed = true;
			
			for (var i:int = 0; i < items.length; i++)
			{
				var inputField:TextInput = new TextInput();
				inputField.name = i.toString();
				inputField.prompt = items[i].label;
				inputField.maxChars = items[i].maxChars;
				inputField.height = 80 * _manager.scale;
				inputField.textEditorProperties.textAlign = "left";
				inputField.promptProperties.textAlign = "left";
				
				addChild(inputField);
				
				_inputList.push(inputField);
			}
		}	
		
		override public function dispose():void 
		{
			for (var i:int = _inputList.length; i--;)
			{
				_inputList.splice(i, 1);
			}
			
			super.dispose();
		}
	}

}