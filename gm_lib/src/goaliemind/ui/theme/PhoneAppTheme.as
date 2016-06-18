package goaliemind.ui.theme 
{
	import starling.events.Event;
	import starling.textures.TextureAtlas;
	
	import goaliemind.ui.base.CreateUserPanelScreen;
	import goaliemind.ui.base.NoHeaderPanelScreen;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class PhoneAppTheme extends BasePhoneAppTheme 
	{
		public function PhoneAppTheme(atlas:TextureAtlas) 
		{
			super(atlas);
			this.initialize();
			this.dispatchEventWith(Event.COMPLETE);
		}
		
		override protected function initializeStyleProviders():void
		{
			super.initializeStyleProviders();
			
			this.getStyleProviderForClass(NoHeaderPanelScreen).defaultStyleFunction = this.setLoginScreenStyles;
			this.getStyleProviderForClass(CreateUserPanelScreen).defaultStyleFunction = this.setCreateUserScreenStyles;
		}
		
		protected function setLoginScreenStyles(screen:NoHeaderPanelScreen):void
		{
			this.setPanelScreenStyles(screen);
			
			screen.paddingTop = this.screenTopPaddingSize;
			screen.paddingLeft = this.screenLeftPaddingSize;
			screen.paddingBottom = this.screenBottomPaddingSize;
			screen.paddingRight = this.screenRightPaddingSize;
		}
		
		protected function setCreateUserScreenStyles(screen:CreateUserPanelScreen):void
		{
			this.setPanelScreenStyles(screen);
			
			screen.paddingTop = this.screenTopPaddingSize;
			screen.paddingLeft = this.screenLeftPaddingSize;
			screen.paddingBottom = this.screenBottomPaddingSize;
			screen.paddingRight = this.screenRightPaddingSize;
		}		
	}

}