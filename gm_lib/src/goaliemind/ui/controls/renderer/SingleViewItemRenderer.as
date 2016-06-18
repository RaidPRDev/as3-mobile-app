package goaliemind.ui.controls.renderer 
{
	import feathers.controls.Button;
	import feathers.controls.ImageLoader;
	import feathers.controls.Label;
	import feathers.controls.Panel;
	import feathers.controls.renderers.LayoutGroupListItemRenderer;
	import feathers.events.FeathersEventType;
	import feathers.layout.AnchorLayout;
	import feathers.layout.AnchorLayoutData;
	import feathers.utils.textures.TextureCache;

	import starling.animation.Transitions;
	import starling.core.Starling;
	import starling.display.DisplayObject;
	import starling.events.Event;


	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class SingleViewItemRenderer extends LayoutGroupListItemRenderer
	{
		private var _iconLoader:ImageLoader;
		private var _image:Panel;
		private var _addFundsButton:Button;
		private var _dateButtonLabel:Button;
		private var _comments:Label;
		private var _bottomPanel:Panel;
		
		private var _firstTimeLoading:Boolean = true;
		
		private var _isSingleView:Boolean;
		public function set isSingleView(value:Boolean):void { _isSingleView = value; }

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
		
		public function SingleViewItemRenderer() 
		{
			
		}
		
		override protected function initialize():void 
		{
			this.layout = new AnchorLayout();
			
			var imageLayoutData:AnchorLayoutData = new AnchorLayoutData(0, 0, NaN, 0);
			this._iconLoader = new ImageLoader();
			this._iconLoader.delayTextureCreation = true;
			this._iconLoader.textureQueueDuration = 0.1;
			this._iconLoader.minHeight = this._iconLoader.maxHeight = this.minWidth;
			this._iconLoader.textureCache = new TextureCache(30);
			this._iconLoader.layoutData = imageLayoutData;
			this._iconLoader.touchable = false;
			this.addChild(this._iconLoader);
			
			if (_isSingleView) this.createElements();
		}
		
		private function createElements():void
		{
			var addFundsLayoutData:AnchorLayoutData = new AnchorLayoutData(this._paddingTop, NaN, NaN, this._paddingLeft);
			addFundsLayoutData.topAnchorDisplayObject = this._iconLoader;
			this._addFundsButton = new Button();
			this._addFundsButton.label = "";
			this._addFundsButton.styleNameList.add(GlobalSettings.HOMEITEMSTARBUTTON);
			this._addFundsButton.layoutData = addFundsLayoutData;
			this.addChild(this._addFundsButton);
			
			var dateLayoutData:AnchorLayoutData = new AnchorLayoutData(this._paddingTop, this._paddingRight, NaN, NaN);
			dateLayoutData.topAnchorDisplayObject = this._iconLoader;
			this._dateButtonLabel = new Button();
			this._dateButtonLabel.label = "";
			this._dateButtonLabel.styleNameList.add(GlobalSettings.HOMEITEMDATEBUTTON);
			this._dateButtonLabel.layoutData = dateLayoutData;
			addChild(this._dateButtonLabel);
			
			var commentsLayoutData:AnchorLayoutData = new AnchorLayoutData(Math.round(this._paddingTop * 1.5), 0, NaN, 0);
			commentsLayoutData.topAnchorDisplayObject = this._addFundsButton;
			this._comments = new Label();
			this._comments.touchable = false;
			this._comments.text = "";
			this._comments.styleNameList.add(GlobalSettings.HOMEITEMCOMMENTS);
			this._comments.layoutData = commentsLayoutData;
			addChild(this._comments);
			
			var bottomPanelLayoutData:AnchorLayoutData = new AnchorLayoutData(this._paddingTop * 2, this._paddingLeft, NaN, this._paddingRight);
			bottomPanelLayoutData.topAnchorDisplayObject = this._comments;
			this._bottomPanel = new Panel();
			this._bottomPanel.styleNameList.add(GlobalSettings.HOMEITEMBOTTOMPANEL);
			this._bottomPanel.touchable = false;
			this._bottomPanel.layoutData = bottomPanelLayoutData;
			this.addChild(this._bottomPanel);				
		}
		
		override protected function preLayout():void
		{
			var labelLayoutData:AnchorLayoutData = AnchorLayoutData(this._iconLoader.layoutData);
			labelLayoutData.top = 0;
			labelLayoutData.right = 0;
			labelLayoutData.bottom = NaN;
			labelLayoutData.left = 0;
			
			if (_isSingleView)
			{
				var fundsLabelLayoutData:AnchorLayoutData = AnchorLayoutData(this._addFundsButton.layoutData);
				fundsLabelLayoutData.topAnchorDisplayObject = this._iconLoader;
				fundsLabelLayoutData.top = this._paddingTop;
				fundsLabelLayoutData.right = NaN;
				fundsLabelLayoutData.bottom = NaN;
				fundsLabelLayoutData.left = this._paddingLeft;
				
				var dateLabelLayoutData:AnchorLayoutData = AnchorLayoutData(this._dateButtonLabel.layoutData);
				dateLabelLayoutData.topAnchorDisplayObject = this._iconLoader;
				dateLabelLayoutData.top = this._paddingTop;
				dateLabelLayoutData.right = this._paddingRight;
				dateLabelLayoutData.bottom = NaN;
				dateLabelLayoutData.left = NaN;
				
				var commentsLabelLayoutData:AnchorLayoutData = AnchorLayoutData(this._comments.layoutData);
				commentsLabelLayoutData.topAnchorDisplayObject = this._addFundsButton;
				commentsLabelLayoutData.top = Math.round(this._paddingTop * 1.5);
				commentsLabelLayoutData.right = NaN;
				commentsLabelLayoutData.bottom = NaN;
				commentsLabelLayoutData.left = this._paddingLeft;
				
				var bottomLayoutData:AnchorLayoutData = AnchorLayoutData(this._bottomPanel.layoutData);
				bottomLayoutData.topAnchorDisplayObject = this._comments;
				bottomLayoutData.top = this._paddingTop * 2;
				bottomLayoutData.right = this._paddingRight;
				bottomLayoutData.bottom = NaN;
				bottomLayoutData.left = this._paddingLeft;				
			}
		}	
		
		override protected function commitData():void
		{
			if(this._data)
			{
				var newIcon:DisplayObject = this.itemToIcon(this._data);
				this.replaceIcon(newIcon);
				
				if (_isSingleView)
				{
					this._addFundsButton.label = this._data.funded;
					this._dateButtonLabel.label = this._data.date;
					this._comments.text = "Description: " + this._data.label;
				}
			}
			else
			{
				if (_isSingleView) 
				{
					this._addFundsButton.label = null;
					this._dateButtonLabel.label = null;
					this._comments.text = null;
				}

				this._iconLoader.source = null;
			}
		}		
		
		protected function itemToIcon(item:Object):DisplayObject
		{
			var source:Object = item["icon"];
			this.refreshIconSource(source);
			return this._iconLoader;		
		}
		
		private function refreshIconSource(source:Object):void
		{
			if(!this._iconLoader.hasEventListener(Event.COMPLETE))
			{
				this._iconLoader.addEventListener(Event.COMPLETE, loader_completeOrErrorHandler);
				this._iconLoader.addEventListener(FeathersEventType.ERROR, loader_completeOrErrorHandler);
			}

			this._iconLoader.source = source;
		}
		
		private function replaceIcon(newIcon:DisplayObject):void
		{
			if(this._iconLoader && this._iconLoader != newIcon)
			{
				this._iconLoader.dispose();
			}
		}	
		
		private function loader_completeOrErrorHandler(event:Event):void
		{
			if (_firstTimeLoading) 
			{
				_firstTimeLoading = false;
				_iconLoader.alpha = 0;
				Starling.juggler.tween(_iconLoader, 0.5, { alpha: 1, transition:Transitions.EASE_IN_OUT, onComplete:resetSize } );
			}
			
			function resetSize():void
			{
				_iconLoader.alpha = 1;
				_iconLoader.validate();
				invalidate(INVALIDATION_FLAG_SIZE);
			}
		}	
		
		private var _iconLoaderFactory:Function = defaultLoaderFactory;
		
		private static function defaultLoaderFactory():ImageLoader
		{
			// return new ImageLoader();
			var image:ImageLoader = new ImageLoader();
			// image.textureScale = this.scale;
			image.textureCache = new TextureCache(50);
			return image;
		}	
				
		public function get iconLoaderFactory():Function
		{
			return this._iconLoaderFactory;
		}		
		
		public function set iconLoaderFactory(value:Function):void
		{
			if(this._iconLoaderFactory == value)
			{
				return;
			}
			this._iconLoaderFactory = value;
			this.replaceIcon(null);
			this.invalidate(INVALIDATION_FLAG_DATA);
		}	
		
		override public function dispose():void
		{
			this.replaceIcon(null);

			this._iconLoader = null;
			
			super.dispose();
		}		
	}

}