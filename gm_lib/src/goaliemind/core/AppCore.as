package goaliemind.core 
{
	import feathers.controls.Alert;
	import feathers.core.FeathersControl;
	import feathers.data.ListCollection;
	
	import starling.events.Event;

	import goaliemind.ui.theme.BasePhoneAppTheme;
	import goaliemind.ui.theme.PhoneAppTheme;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class AppCore extends FeathersControl 
	{
		private var _theme:BasePhoneAppTheme;
		private var _appAssets:AppAssets;
		private var _manager:AppManager;
		
		public var isInternet:Boolean;
		
		public function get theme():BasePhoneAppTheme { return _theme; }
		public function get assets():AppAssets { return _appAssets; }

		private var _alert:Alert;
		public function get alert():Alert { return _alert; }

		public function AppCore() 
		{
			super();
			
			// this.addEventListener(Event.ADDED_TO_STAGE, addedToStageHandler);
		}
		
		override protected function initialize():void 
		{
			super.initialize();
			
			_appAssets = new AppAssets();
			_appAssets.addEventListener(Event.COMPLETE, onAppAssetsComplete);
			_appAssets.loadApplicationAssets();
			
		}
		
		/*private function addedToStageHandler(event:Event):void
		{
			this.removeEventListener(Event.ADDED_TO_STAGE, addedToStageHandler);
			
			_appAssets = new AppAssets();
			_appAssets.addEventListener(Event.COMPLETE, onAppAssetsComplete);
			_appAssets.loadApplicationAssets();
		}*/
		
		private function onAppAssetsComplete(e:Event):void 
		{
			_appAssets.removeEventListener(Event.COMPLETE, onAppAssetsComplete);
			
			_theme = new PhoneAppTheme(_appAssets.applicationAssets.getTextureAtlas("goaliemind_ui"));
			
			loadCreateUserAssets();
		}
		
		private function loadCreateUserAssets():void
		{
			_appAssets.addEventListener(Event.COMPLETE, onLoadCreateUserAssetsComplete);
			_appAssets.loadCreateUserAssets();
		}
		
		private function onLoadCreateUserAssetsComplete(e:Event):void 
		{
			_appAssets.removeEventListener(Event.COMPLETE, onLoadCreateUserAssetsComplete);
			
			_theme.initializeCreateUserTheme();
			
			createManager();
		}
		
		private function createManager():void
		{
			_manager = new AppManager(this);
		}

		public function addCreateUserTheme(onCreateComplete:Function):void
		{
			_appAssets.addEventListener(Event.COMPLETE, onCreateUserAssetsComplete);
			_appAssets.loadCreateUserAssets();
			
			function onCreateUserAssetsComplete():void
			{
				_appAssets.removeEventListener(Event.COMPLETE, onCreateUserAssetsComplete);
				
				_theme.initializeCreateUserTheme();
				
				onCreateComplete();
			}
		}

		public function removeCreateUserTheme():void
		{
			_theme.disposeCreateUserTheme();
		}
		
		public function showAlert(message:String, title:String, buttons:Array):void
		{
			if (buttons)
			{
				_alert = Alert.show(message, title, new ListCollection(buttons));
			}
			else _alert = Alert.show(message, title);
		}
		
		public function removeAlert():void
		{
			if (_alert) _alert.removeFromParent( true );
		}
		
	}

}