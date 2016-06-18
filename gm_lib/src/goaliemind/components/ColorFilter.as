package goaliemind.components 
{
	import starling.filters.ColorMatrixFilter;
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class ColorFilter extends ColorMatrixFilter
	{
		private var _brightness:Number;
		private var _contrast:Number;
		private var _tintAlpha:Number;
 
		public function ColorFilter()
		{
			_brightness = 0;
			_contrast 	= 0;
		}
 
		public function get brightness():Number { return _brightness; }
		public function set brightness(value:Number):void
		{
			_brightness = value;
			reset();
			adjustBrightness(value);
		}
 
		public function get contrast():Number { return _contrast; }
		public function set contrast(value:Number):void
		{
			_contrast = value;
			reset();
			adjustBrightness(value);
		}
		
	}

}