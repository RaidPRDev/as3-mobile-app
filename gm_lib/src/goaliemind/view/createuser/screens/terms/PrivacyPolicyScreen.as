package goaliemind.view.createuser.screens.terms 
{
	import feathers.controls.Button;
	import feathers.controls.Header;
	import feathers.controls.Label;
	import feathers.controls.Panel;
	import feathers.controls.Scroller;
	import feathers.events.FeathersEventType;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;
	
	import starling.animation.Transitions;
	import starling.core.Starling;
	import starling.display.DisplayObject;
	import starling.events.Event;

	import goaliemind.data.info.InfoLoader;
	import goaliemind.ui.base.CreateUserPanelScreen;
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class PrivacyPolicyScreen extends CreateUserPanelScreen
	{
		private var _subPanel:Panel;
		private var _subPanelContent:Button;
		private var _subHeaderLabel:Label;
		private var _infoLoader:InfoLoader;
		
		public function PrivacyPolicyScreen() 
		{
			super();
		}
		
		override public function dispose():void
		{
			// never forget to dispose textures!
			super.dispose();
		}	
		
		override protected function initialize():void
		{
			super.initialize();

			this.title = "Privacy Policy";
			
			this.layout = new AnchorLayout();

			this.headerFactory = this.customHeaderFactory;
			
			this.verticalScrollPolicy = Scroller.SCROLL_POLICY_ON;
			
			this.addEventListener(FeathersEventType.TRANSITION_IN_COMPLETE, onTransitionInComplete);
		}
		
		private function onTransitionInComplete(e:Event):void 
		{
			this.removeEventListener(FeathersEventType.TRANSITION_IN_COMPLETE, onTransitionInComplete);
			
			this._infoLoader = new InfoLoader(GlobalSettings.PRIVACYPOLICY + ".dat");
			this._infoLoader.addEventListener(Event.COMPLETE, onInfoLoadingComplete);
		}
		
		private function onInfoLoadingComplete(e:Event):void 
		{
			this._infoLoader.removeEventListener(Event.COMPLETE, onInfoLoadingComplete);
			
			var textLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0);
			
			var textInfo:Label = new Label();
			textInfo.layoutData = textLayoutData;
			textInfo.styleProvider = _manager.appCore.theme.createUserTheme.userStyleProvider;
			textInfo.styleNameList.add(GlobalSettings.LABEL_TERMS);
			textInfo.textRendererProperties.wordWrap = true;
			textInfo.text = e.data as String;
			textInfo.alpha = 0;
			this.addChild(textInfo);
			
			Starling.juggler.tween(textInfo, 0.5, 
			{
				transition: Transitions.EASE_OUT,
				alpha:1,
				delay:0.01
			});
		}		
		
		private function customHeaderFactory():Header
		{
			var header:Header = new Header();

			header.styleNameList.add(GlobalSettings.CREATEUSER_HEADER);
			
			_backHeaderButton = new Button();
			_backHeaderButton.styleNameList.add(GlobalSettings.BUTTON_GREY_BACK_HEADER);
			_backHeaderButton.name = CreateUserPanelScreen.HEADER_BACK;
			_backHeaderButton.addEventListener(Event.TRIGGERED, headerButton_triggeredHandler); 

			header.leftItems = new <DisplayObject> [ _backHeaderButton ];
			
			return header;
		}
		
		override protected function headerButton_triggeredHandler(event:Event):void
		{
			var button:Button = Button( event.currentTarget );
			
			if (button.name == HEADER_BACK)
			{
				this.dispatchEventWith(CreateUserPanelScreen.HEADER_BACK);
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