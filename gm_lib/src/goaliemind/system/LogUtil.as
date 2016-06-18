package goaliemind.system  
{
	/**
	 * ...
	 * @author Rafael Emmanuelli
	 */
	public class LogUtil 
	{
		public static var isTraceEnabled:Boolean = false;
		
		public function LogUtil() 
		{
			
		}
		
		public static function log(...args):void {
			
			if ( isTraceEnabled ) {
				
				var _trace:String = "";
				for each (var msg:String in args)
					_trace += " " + msg;
				
				trace(_trace);
				
			}
			
		}
		
	}

}