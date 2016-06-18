package goaliemind.core 
{
	import flash.filesystem.File;
	
	import feathers.controls.ImageLoader;
	
	import starling.events.EventDispatcher;
	import starling.events.Event;
	import starling.utils.AssetManager;
	
	[Event(name="complete", type="starling.events.Event")]
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class AppAssets extends EventDispatcher 
	{
		private var _applicationAssets:AssetManager;
		private var _shopAssets:AssetManager;
		private var _createUserAssets:AssetManager;
		
		public function get applicationAssets():AssetManager { return _applicationAssets; }
		public function get createUserAssets():AssetManager { return _createUserAssets; }
		
		public static var _instance:AppAssets;
		public static function GetInstance():AppAssets { return _instance;	}
		
		public function AppAssets() 
		{
			_instance = this;
		}
		
		public function loadApplicationAssets():void 
		{
			_applicationAssets = new AssetManager();
			
			var appDir:File = File.applicationDirectory;
			
			_applicationAssets.verbose = true;
			
			_applicationAssets.enqueue(appDir.resolvePath("assets/goaliemind_ui.png"));
			_applicationAssets.enqueue(appDir.resolvePath("assets/goaliemind_ui.xml"));
			
			_applicationAssets.loadQueue(function(ratio:Number):void
			{
				if (ratio == 1.0) assetsLoaded();
			});
		}	
		
		public function loadCreateUserAssets():void 
		{
			_createUserAssets = new AssetManager();
			
			var appDir:File = File.applicationDirectory;
			
			_createUserAssets.verbose = true;
			
			_createUserAssets.enqueue(appDir.resolvePath("assets/goaliemind_ui_create_user.png"));
			_createUserAssets.enqueue(appDir.resolvePath("assets/goaliemind_ui_create_user.xml"));
			
			_createUserAssets.loadQueue(function(ratio:Number):void
			{
				if (ratio == 1.0) assetsLoaded();
			});
		}	
		
		public function removeCreateUserAssets():void 
		{
			_createUserAssets.purge();
		}
		
		public function loadShopAssets():void 
		{
			_shopAssets = new AssetManager();
			
			var appDir:File = File.applicationDirectory;
			
			_shopAssets.verbose = true;
			
			_shopAssets.enqueue(appDir.resolvePath("assets/goaliemind_ui.png"));
			_shopAssets.enqueue(appDir.resolvePath("assets/goaliemind_ui.xml"));
			
			_shopAssets.loadQueue(function(ratio:Number):void
			{
				if (ratio == 1.0) assetsLoaded();
			});
		}				
		
		private function assetsLoaded():void
		{
			this.dispatchEventWith(Event.COMPLETE);
			// this.dispatchEvent(new starling.events.Event(Event.COMPLETE));
		}
		
		public function imageLoaderFactory(textureName:String, scale:Number):ImageLoader
		{
			var image:ImageLoader = new ImageLoader();
			image.textureScale = scale;
			image.source = _applicationAssets.getTexture(textureName);
			return image;
		}	
		
		public function createUserImageLoaderFactory(textureName:String, scale:Number):ImageLoader
		{
			var image:ImageLoader = new ImageLoader();
			image.textureScale = scale;
			image.source = _createUserAssets.getTexture(textureName);
			return image;
		}	
		
	}

}