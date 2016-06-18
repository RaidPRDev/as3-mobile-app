package goaliemind.system 
{
	import flash.system.Capabilities;
	
	
	import starling.core.Starling;
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class DeviceResolution 
	{
		public static const IPHONE6WIDTH:int = 750;
		public static const IPHONE6HEIGHT:int = 1334;
		public static const IPHONE6PLUSWIDTH:int = 1242;
		public static const IPHONE6PLUSHEIGHT:int = 2208;
				
		public static var deviceWidth:int;
		public static var deviceHeight:int;
		public static var deviceScale:Number;
		
		public function DeviceResolution() 
		{
			
		}
		
		public static function isIOS():Boolean
		{
			if (Capabilities.isDebugger) return false;
			
			var check:int = Capabilities.version.indexOf("IOS");
			var label:String = Capabilities.version;
				
			return (Capabilities.version.indexOf("IOS") > -1);
		}
		
		public static function isAndroid():Boolean
		{
			if (Capabilities.isDebugger) return false;
			
			var check:int = Capabilities.version.indexOf("AND");
			var label:String = Capabilities.version;
			
			return (Capabilities.version.indexOf("AND") > -1);
		}		
		
		public static function scaleByWidth():Boolean
		{
			trace("Capabilities.isDebugger: " + Capabilities.isDebugger);
			
			if (Capabilities.isDebugger)
			{
				deviceWidth = Starling.current.nativeStage.stageWidth;
				deviceHeight = Starling.current.nativeStage.stageHeight;
			}
			else 
			{
				deviceWidth = Capabilities.screenResolutionX;
				deviceHeight = Capabilities.screenResolutionY;
			}
			
			trace("Capabilities.deviceWidth: " + deviceWidth);
			trace("Capabilities.deviceHeight: " + deviceHeight);
			
			if (deviceWidth == IPHONE6PLUSWIDTH && deviceHeight == IPHONE6PLUSHEIGHT) 
				return true;
				
			if (deviceWidth == IPHONE6WIDTH && deviceHeight == IPHONE6HEIGHT) 
				return true;
				
			if (deviceWidth == 1080 && deviceHeight == 1845) 
				return true;
			
			return true; // false
		}
		
		public static function getDeviceScale():Number
		{
			deviceScale = (deviceWidth / GlobalSettings.APPORIGINALWIDTH);
			
			trace("Capabilities.deviceScale: " + deviceScale);
			
			return deviceScale;
		}
	}
}