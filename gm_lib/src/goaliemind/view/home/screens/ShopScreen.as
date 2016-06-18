package goaliemind.view.home.screens 
{
	import feathers.controls.Header;
	import feathers.layout.AnchorLayout;

	import goaliemind.ui.base.HomePanelScreen;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class ShopScreen extends HomePanelScreen
	{
		public function ShopScreen() 
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

			this.title = "Shop";
			
			this.layout = new AnchorLayout();

			this.headerFactory = this.customHeaderFactory;
		}
		
		private function customHeaderFactory():Header
		{
			var header:Header = new Header();

			header.styleNameList.add(GlobalSettings.HOME_MAINHEADER);
			
			return header;
		}		
	}

}