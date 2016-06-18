package goaliemind.ui.controls.renderer 
{
	import feathers.controls.TextInput;
	import feathers.controls.renderers.LayoutGroupListItemRenderer;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;
	import starling.display.DisplayObject;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class TextInputViewItemRenderer extends LayoutGroupListItemRenderer
	{
		private var _input:TextInput;
		
		private var _paddingTop:int = 0;
		public function set paddingTop(value:Number):void { this._paddingTop = value; }
		
		private var _paddingLeft:int = 0;
		public function set paddingLeft(value:Number):void { this._paddingLeft = value; }	
		
		private var _paddingRight:int = 0;
		public function set paddingRight(value:Number):void { this._paddingRight = value; }
		
		private var _paddingBottom:int = 0;
		public function set paddingBottom(value:Number):void { this._paddingBottom = value; }
				
		private var _gap:int = 0;
		public function set gap(value:Number):void { this._gap = value; }
		
		public function TextInputViewItemRenderer() 
		{
			
		}
		
		override protected function initialize():void 
		{
			this.layout = new AnchorLayout();
			
			createInput();
		}
		
		private function createInput():void
		{
			var inputLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0);
			
			this._input = new TextInput();
			this._input.prompt = "";
			this._input.layoutData = inputLayoutData;
			this._input.textEditorProperties.textAlign = "left";
			this._input.promptProperties.textAlign = "left";
			this._input.restrict = GlobalSettings.FIELD_RESTRICT;
			
			this.addChild(DisplayObject(this._input));
			
			this._input.text = "";  
			
			// this._input.addEventListener(FeathersEventType.ENTER, onInputEnter);
			// this._input.addEventListener(Event.CHANGE, onInputChange);
		}
		
		override protected function preLayout():void
		{
			var inputLayoutData:AnchorLayoutData = AnchorLayoutData(this._input.layoutData);
			inputLayoutData.top = 0;
			inputLayoutData.right = 0;
			inputLayoutData.bottom = NaN;
			inputLayoutData.left = 0;
		}	
		
		override protected function commitData():void
		{
			if(this._data)
			{
				this._input.prompt = this._data.prompt;
				this._input.text = this._data.field;
				this._input.isEnabled = this._input.isEditable = this._data.isEnabled;	
			}
			else
			{
				this._input = null;
			}
		}		
		
		override public function dispose():void
		{
			this._input = null;
			
			super.dispose();
		}		
	}

}