package goaliemind.view.createuser.screens 
{
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.layout.AnchorLayout;
	
	import starling.display.DisplayObject;
	import starling.events.Event;

	import goaliemind.ui.base.CreateUserPanelScreen;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class CreateAvatar extends CreateUserPanelScreen
	{
		
		public function CreateAvatar() 
		{
			
		}
		
		override protected function initialize():void
		{
			super.initialize();

			this.title = "Avatar";
			
			this.layout = new AnchorLayout();

			this.headerFactory = this.customHeaderFactory;
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
			_nextHeaderButton.isEnabled = true;
			
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
				this.dispatchEventWith(CreateUserPanelScreen.HEADER_NEXT);
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