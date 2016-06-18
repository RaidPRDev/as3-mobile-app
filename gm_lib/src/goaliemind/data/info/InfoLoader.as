package goaliemind.data.info 
{
	import flash.events.Event;
	import flash.filesystem.File;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	
	import starling.events.EventDispatcher;
	import starling.events.Event;
	
    /** Dispatched when the user triggers the button. Bubbles. */
    [Event(name="complete", type="starling.events.Event")]
	
	/**
	 * ...
	 * @author Rafael Emmanuelli
	 */
	public class InfoLoader extends EventDispatcher
	{
		private var myTextLoader:URLLoader;
		private var file:File;
		
		public function InfoLoader(filenameDat:String) 
		{
			this.myTextLoader = new URLLoader();

			this.myTextLoader.addEventListener(flash.events.Event.COMPLETE, onLoaded);

			var appDir:String = "data/";
			this.file = File.applicationDirectory.resolvePath(appDir + filenameDat);	
			
			myTextLoader.load(new URLRequest(file.url));			
		}
		
		private function onLoaded(e:flash.events.Event):void 
		{
			myTextLoader.removeEventListener(flash.events.Event.COMPLETE, onLoaded);
			
			this.dispatchEventWith("complete", false, Object(e.target.data));
			
			myTextLoader = null;
		}
		
	}

}