package goaliemind.system 
{
	import flash.display.Bitmap;
	import flash.display.BitmapData;
	import flash.display.PixelSnapping;
	import flash.geom.Matrix;
	import flash.geom.Rectangle;
	import flash.utils.ByteArray;
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class BitmapSerialize 
	{
		public function BitmapSerialize() 
		{
			
		}
		
		/**
         * BitmapData to ByteArray
         */
        public static function encodeBitmapDataToByteArray(bitmapData:BitmapData):ByteArray 
		{
			var bytes:ByteArray = new ByteArray();
            bytes.writeUnsignedInt(bitmapData.width);
            bytes.writeBytes(bitmapData.getPixels(bitmapData.rect));
            bytes.compress();
            return bytes;
        }
        
        /**
         * ByteArray to Bitmap
         */
        public static function decodeByteArrayToBitmap(bytes:ByteArray):Bitmap 
		{
            var bitmap:Bitmap = null;
            try 
			{
                bytes.uncompress();
                var width:int = bytes.readUnsignedInt();
                var height:int = ((bytes.length - 4) / 4) / width;
                var bmd:BitmapData = new BitmapData(width, height, true, 0);
                bmd.setPixels(bmd.rect, bytes);
                bitmap = new Bitmap(bmd);
            } 
			catch (e:Error) 
			{
                trace('BitmapSerialize error uncompressing bytes');                
            }
            return bitmap;
        }	
		
        /**
         * Scales BitmapData to specified size non proportionally
         */		
		public static function drawBitmapDataScaled(srcBitmapData:BitmapData, newWidth:Number, newHeight:Number):BitmapData 
		{
			var mat:Matrix = new Matrix();
			mat.scale(newWidth/srcBitmapData.width, newHeight/srcBitmapData.height);
			var bmpd_draw:BitmapData = new BitmapData(newWidth, newHeight, true, 0x000000);
			bmpd_draw.draw(srcBitmapData, mat, null, null, null, true);
			return bmpd_draw;
		}			
		
		/**
		* fitImage
		* @ARG_object   the display object to work with
		* @ARG_width    width of the box to fit the image into
		* @ARG_height   height of the box to fit the image into
		* @ARG_center   should it offset to center the result in the box
		* @ARG_fillBox  should it fill the box, may crop the image (true), or fit the whole image within the bounds (false)
		**/
		
		public static function fitImageProportionally( srcBitmapData:BitmapData, newWidth:Number, newHeight:Number, ARG_center:Boolean = true, ARG_fillBox:Boolean = true ):Bitmap
		{
		 
			var bitmap:Bitmap = new Bitmap(srcBitmapData);
			
			var tempW:Number = bitmap.width;
			var tempH:Number = bitmap.height;
		 
			bitmap.width = newWidth;
			bitmap.height = newHeight;
		 
			var scale:Number = (ARG_fillBox) ? Math.max(bitmap.scaleX, bitmap.scaleY) : Math.min(bitmap.scaleX, bitmap.scaleY);
		 
			bitmap.width = tempW;
			bitmap.height = tempH;
		 
			var scaleBmpd:BitmapData = new BitmapData(bitmap.width * scale, bitmap.height * scale);
			var scaledBitmap:Bitmap = new Bitmap(scaleBmpd, PixelSnapping.ALWAYS, true);
			var scaleMatrix:Matrix = new Matrix();
			scaleMatrix.scale(scale, scale);
			scaleBmpd.draw( bitmap, scaleMatrix );
		 
			if (scaledBitmap.width > newWidth || scaledBitmap.height > newHeight) {
		 
				var cropMatrix:Matrix = new Matrix();
				var cropArea:Rectangle = new Rectangle(0, 0, newWidth, newHeight);
		 
				var croppedBmpd:BitmapData = new BitmapData(newWidth, newHeight);
				var croppedBitmap:Bitmap = new Bitmap(croppedBmpd, PixelSnapping.ALWAYS, true);
		 
				if (ARG_center) {
					var offsetX:Number = Math.abs((newWidth -scaleBmpd.width) / 2);
					var offsetY:Number = Math.abs((newHeight - scaleBmpd.height) / 2);
		 
					cropMatrix.translate(-offsetX, -offsetY);
				}
		 
				croppedBmpd.draw( scaledBitmap, cropMatrix, null, null, cropArea, true );
				return croppedBitmap;
			} 
			else 
			{
				return scaledBitmap;
			}
		 
		}
	}

}