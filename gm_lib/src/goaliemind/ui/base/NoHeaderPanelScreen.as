package goaliemind.ui.base 
{
	import feathers.controls.Alert;
	import feathers.controls.Label;
	import feathers.controls.Panel;
	import feathers.data.ListCollection;
	import feathers.skins.IStyleProvider;
	import goaliemind.core.AppManager;
	
	/**
	 * ...
	 * @author Rafael Emmanuelli
	 */
	public class NoHeaderPanelScreen extends Panel 
	{
		protected var _manager:AppManager;
		
		protected var _status:Label;
		protected var _alert:Alert;
		
		public static var globalStyleProvider:IStyleProvider;
		
		override protected function get defaultStyleProvider():IStyleProvider
		{
			return NoHeaderPanelScreen.globalStyleProvider;
		}
		
		public function NoHeaderPanelScreen() 
		{
			super();
			
			_manager = AppManager.GetInstance();
			
			this.horizontalScrollPolicy = SCROLL_POLICY_OFF;
			this.verticalScrollPolicy = SCROLL_POLICY_OFF;
		}
		
		protected function showAlert(message:String, title:String, buttons:Array):void
		{
			if (buttons)
			{
				_alert = Alert.show(message, title, new ListCollection(buttons));
			}
			else _alert = Alert.show(message, title);
		}
		
		protected function removeAlert():void
		{
			if (_alert) _alert.removeFromParent( true );
		}		
		
		override public function dispose():void 
		{
			_manager = null;
			
			if (_status) this.removeChild(_status);
			if (_alert) _alert = null;
			
			super.dispose();
		}
		
		
	}
}