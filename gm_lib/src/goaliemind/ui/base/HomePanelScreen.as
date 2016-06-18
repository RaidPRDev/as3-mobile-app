package goaliemind.ui.base 
{
	import feathers.controls.Alert;
	import feathers.controls.Button;
	import feathers.controls.ImageLoader;
	import feathers.controls.Label;
	import feathers.controls.Panel;
	import feathers.data.ListCollection;
	import feathers.skins.IStyleProvider;
	
	import starling.display.DisplayObject;
	import starling.events.Event;
	import starling.textures.Texture;
	
	import goaliemind.view.home.panels.HomeSubHeader;
	import goaliemind.core.AppManager;
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class HomePanelScreen extends Panel
	{
		public static const HEADER_BACK:String = "back-button";
		public static const HEADER_NEXT:String = "next-button";

		protected var _manager:AppManager;
		protected function get manager():AppManager { return _manager; }
		
		protected var _backHeaderButton:Button;
		protected var _nextHeaderButton:Button;
		
		protected var _status:Label;
		protected var _alert:Alert;
		protected var _subHeader:HomeSubHeader;
		
		public var isShow:Boolean = false;
		
		public static var globalStyleProvider:IStyleProvider;
		
		override protected function get defaultStyleProvider():IStyleProvider
		{
			return HomePanelScreen.globalStyleProvider;
		}
		
		public function HomePanelScreen() 
		{
			super();
			
			_manager = AppManager.GetInstance();
			
			this.horizontalScrollPolicy = SCROLL_POLICY_OFF;
			this.verticalScrollPolicy = SCROLL_POLICY_OFF;
		}
		
		protected function headerButton_triggeredHandler(event:Event):void
		{
			
		}			
		
		protected function loadImageFactory(texture:Texture):DisplayObject
		{
			var img:ImageLoader = new ImageLoader();
			img.source = texture;
			img.textureScale = _manager.appCore.theme.scale;
			return img;
		}
		
		public function show():void
		{
			
			
		}
		
		protected function showAlert(message:String, title:String, buttons:Array):void
		{
			if (buttons)
			{
				if (buttons.length == 1)
				{
					_alert = Alert.show(message, title, new ListCollection(buttons), null, true, true, customAlertFactory);
				}
				else _alert = Alert.show(message, title, new ListCollection(buttons));
			}
			else _alert = Alert.show(message, title);
			
			function customAlertFactory():Alert
			{
				var alert:Alert = new Alert();
				alert.styleNameList.add(GlobalSettings.ALERT_SINGLE);
				
				return alert;
			}
		}
		
		protected function removeAlert():void
		{
			if (_alert) _alert.removeFromParent( true );
		}
		
		override public function dispose():void 
		{
			if (_backHeaderButton)
			{
				_backHeaderButton.removeEventListener(Event.TRIGGERED, headerButton_triggeredHandler); 
				_backHeaderButton.dispose();
			}
			
			if (_nextHeaderButton)
			{
				_nextHeaderButton.removeEventListener(Event.TRIGGERED, headerButton_triggeredHandler); 
				_nextHeaderButton.dispose();
			}
			
			if (_subHeader) this.removeChild(_subHeader);
			if (_status) this.removeChild(_status);
			if (_alert) _alert = null;
			
			_manager = null;
			
			super.dispose();
		}
		
	}

}