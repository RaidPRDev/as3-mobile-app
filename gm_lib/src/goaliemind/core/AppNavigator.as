package goaliemind.core 
{
	import flash.geom.Rectangle;
	
	import feathers.controls.Panel;
	import feathers.controls.StackScreenNavigator;
	
	import goaliemind.data.support.NavigatorTransitionType;
	
	import starling.animation.Transitions;
	import starling.core.Starling;
	
	[Event(name="navigatorLoadComplete",type="starling.events.Event")]
	
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class AppNavigator extends Panel 
	{
		public static const CLOSEPANEL:String = "closePanel";
		
		protected var _manager:AppManager;
		
		protected var _navigator:StackScreenNavigator;
		public function get navigator():StackScreenNavigator { return _navigator; }
		
		protected var _doNotInitializeNavigator:Boolean = false;
		
		protected var _previousScreen:String;
		protected var _currentScreen:String;
		protected var _currentScreenIndex:int;
		protected var _previousScreenIndex:int;
		
		public function AppNavigator() 
		{
			super();
		}
		
		override protected function initialize():void
		{
			super.initialize();
			
			_manager = AppManager.GetInstance();
			
			if (_doNotInitializeNavigator) return;
		}
		
		public function onShow():void
		{
			
		}
		
		override public function dispose():void 
		{
			_manager = null;
			
			removeChild(_navigator, true);
			
			_navigator = null;
			
			super.dispose();
		}
		
		public var isTransitionIn:Boolean = false;
		
		public function set transitionInType(value:String):void { _transitionInType = value; }
		protected var _transitionInType:String = NavigatorTransitionType.WIPE_NORMAL_LEFT;
		
		public function transitionIn():void
		{
			if (_transitionInType == NavigatorTransitionType.WIPE_SLIDE_LEFT) 
			{
				this.clipRect = new Rectangle(this.width, 0, this.width, this.height);
				
				Starling.juggler.tween(this.clipRect, 0.7, 
				{
					transition: Transitions.EASE_OUT_BACK,
					onComplete:this.transitionInComplete,
					x:0,
					delay:0
				});						
				
			}
			else if (_transitionInType == NavigatorTransitionType.WIPE_BOUNCE_LEFT) 
			{
				this.clipRect = new Rectangle(this.width, 0, this.width, this.height);
				
				Starling.juggler.tween(this.clipRect, 0.95, 
				{
					transition: Transitions.EASE_OUT_BOUNCE,
					onComplete:this.transitionInComplete,
					x:0,
					delay:0
				});						
				
			}
			else if (_transitionInType == NavigatorTransitionType.WIPE_NORMAL_LEFT) 
			{
				this.clipRect = new Rectangle(this.width, 0, this.width, this.height);
				
				Starling.juggler.tween(this.clipRect, 0.65, 
				{
					transition: Transitions.EASE_OUT,
					onComplete:this.transitionInComplete,
					x:0,
					delay:0
				});						
				
			}
			else if (_transitionInType == NavigatorTransitionType.SLIDE_NORMAL_LEFT) 
			{
				this.x = this.width;
				
				Starling.juggler.tween(this, 0.5, 
					{
						transition: Transitions.EASE_OUT,
						onComplete:this.transitionInComplete,
						x:0,
						delay:0
					});
			}
			else if (_transitionInType == NavigatorTransitionType.SLIDE_NORMAL_RIGHT) 
			{
				this.x = -this.width;
				
				Starling.juggler.tween(this, 0.5, 
					{
						transition: Transitions.EASE_OUT,
						onComplete:this.transitionInComplete,
						x:0,
						delay:0
					});
			}
		}
		
		public function transitionOut(onTransitionComplete:Function):void
		{
			if (_transitionInType == NavigatorTransitionType.WIPE_SLIDE_LEFT) 
			{
				Starling.juggler.tween(this.clipRect, 0.7, 
					{
						transition: Transitions.EASE_OUT_BACK,
						onComplete:onTransitionComplete,
						x:this.width,
						delay:0
					});						
				
			}
			else if (_transitionInType == NavigatorTransitionType.WIPE_BOUNCE_LEFT) 
			{
				Starling.juggler.tween(this.clipRect, 0.95, 
					{
						transition: Transitions.EASE_OUT_BOUNCE,
						onComplete:onTransitionComplete,
						x:this.width,
						delay:0
					});						
				
			}
			else if (_transitionInType == NavigatorTransitionType.WIPE_NORMAL_LEFT) 
			{
				Starling.juggler.tween(this.clipRect, 0.65, 
					{
						transition: Transitions.EASE_OUT,
						onComplete:onTransitionComplete,
						x:this.width,
						delay:0
					});						
				
			}
			else if (_transitionInType == NavigatorTransitionType.SLIDE_NORMAL_LEFT) 
			{
				Starling.juggler.tween(this, 0.5, 
					{
						transition: Transitions.EASE_OUT,
						onComplete:onTransitionComplete,
						x:this.width,
						delay:0
					});
			}
			else if (_transitionInType == NavigatorTransitionType.SLIDE_NORMAL_RIGHT) 
			{
				Starling.juggler.tween(this, 0.5, 
					{
						transition: Transitions.EASE_OUT,
						onComplete:onTransitionComplete,
						x:-this.width,
						delay:0
					});
			}
		}		
		
		public function transitionInComplete():void
		{
			
		}
		
		public function onClosePanel():void 
		{
			if (isTransitionIn) this.transitionOut(onTransitionComplete);
			else onTransitionComplete();
			
			function onTransitionComplete():void
			{
				removeFromParent(true);
			}
		}
		
	}

}