package goaliemind.system 
{
	import flash.events.Event;
	import flash.events.IOErrorEvent;
	import flash.events.ProgressEvent;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class NetworkTools 
	{
		public static const INTERNET_AVAILABLE:String = "internet-available";
		public static const INTERNET_UNAVAILABLE:String = "internet-unavailable";
			
		public static var isInternetAvailable:Boolean;
		
		private static var _configLoader:URLLoader;
		private static var _callBackFunction:Function;
		
		public function NetworkTools() 
		{
			
		}
		
		public static function checkInternetConnection(callback:Function):void
		{
			_callBackFunction = callback;
			
			_configLoader = new URLLoader();
			_configLoader.addEventListener(flash.events.Event.COMPLETE, onLoadDataComplete);
			_configLoader.addEventListener(flash.events.ProgressEvent.PROGRESS, onLoadDataProgressHandler);
			_configLoader.addEventListener(flash.events.IOErrorEvent.IO_ERROR, onLoadDataIOError);
			
			_configLoader.load(new URLRequest("http://www.google.com"));
			
			function onLoadDataComplete(e:Event):void 
			{
				removeLoaderEvents();
				
				isInternetAvailable = true;
				
				_callBackFunction( { status:INTERNET_AVAILABLE } );
				_callBackFunction = null;
			}
			
			function onLoadDataProgressHandler(e:Event):void 
			{
				removeLoaderEvents();
				
				isInternetAvailable = true;
				
				_callBackFunction( { status:INTERNET_AVAILABLE } );
				_callBackFunction = null;
			}
			
			function onLoadDataIOError(e:Event):void 
			{
				removeLoaderEvents();
				
				isInternetAvailable = false;
				
				_callBackFunction( { status:INTERNET_UNAVAILABLE } );
				_callBackFunction = null;
			}
			
			function removeLoaderEvents():void
			{
				_configLoader.removeEventListener(flash.events.Event.COMPLETE, onLoadDataComplete);
				_configLoader.removeEventListener(flash.events.ProgressEvent.PROGRESS, onLoadDataProgressHandler);
				_configLoader.removeEventListener(flash.events.IOErrorEvent.IO_ERROR, onLoadDataIOError);
				_configLoader = null;
			}

		}
		

		
	}

}