package
{
	import flash.desktop.NativeApplication;
	import flash.display3D.Context3DProfile;
	import flash.display3D.Context3DRenderMode;
	import flash.events.Event;
	import flash.display.Sprite;
	import flash.display.StageAlign;
	import flash.display.StageScaleMode;
	import flash.geom.Rectangle;
	
	import starling.core.Starling;
	
	import feathers.FEATHERS_VERSION;
	
	import goaliemind.core.AppCore;
	import goaliemind.system.LogUtil;
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	[SWF(width="960",height="640",frameRate="60",backgroundColor="#ffffff")]
	public class GoalieMindStore extends Sprite 
	{
		private var _starlingApp:Starling;
		
		public function GoalieMindStore() 
		{
			stage.scaleMode = StageScaleMode.NO_SCALE;
			stage.align = StageAlign.TOP_LEFT;
			
			LogUtil.isTraceEnabled = true;
			LogUtil.log("[Adobe AIR] Version: " + NativeApplication.nativeApplication.runtimeVersion);
			LogUtil.log("[Starling] Version: " + Starling.VERSION);
			LogUtil.log("[Feathers] Version: " + FEATHERS_VERSION);
			
			initializeStarling();
		}
		
		private function initializeStarling():void
		{
			Starling.handleLostContext = true;// (DeviceResolution.isAndroid) ? true : false;
			Starling.multitouchEnabled = true;
			this._starlingApp = new Starling(AppCore, this.stage, null, null, Context3DRenderMode.AUTO, Context3DProfile.BASELINE_CONSTRAINED);
			this._starlingApp.supportHighResolutions = true;
			this._starlingApp.showStatsAt("right", "bottom");
			this._starlingApp.start();
			
			this.stage.addEventListener(Event.RESIZE, stage_resizeHandler, false, int.MAX_VALUE, true);
			this.stage.addEventListener(Event.ACTIVATE, stage_activateHandler, false, 0, true);
			this.stage.addEventListener(Event.DEACTIVATE, stage_deactivateHandler, false, 0, true);
		}
		
		private function stage_resizeHandler(event:Event):void
		{
			this._starlingApp.stage.stageWidth = this.stage.stageWidth;
			this._starlingApp.stage.stageHeight = this.stage.stageHeight;
			
			var viewPort:Rectangle = this._starlingApp.viewPort;
			viewPort.width = this.stage.stageWidth;
			viewPort.height = this.stage.stageHeight;
			try
			{
				this._starlingApp.viewPort = viewPort;
			}
			catch(error:Error) {}
		}
		
		private function stage_deactivateHandler(event:Event):void
		{
			LogUtil.log("stage_deactivateHandler");
			
			this._starlingApp.stop(true);
			// make sure the app behaves well (or exits) when in background
			// NativeApplication.nativeApplication.exit();
		}		
		
		private function stage_activateHandler(event:Event):void
		{
			LogUtil.log("stage_activateHandler");
			this._starlingApp.start();
		}	
		
		
	}
	
}