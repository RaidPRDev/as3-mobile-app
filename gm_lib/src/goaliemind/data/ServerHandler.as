package goaliemind.data 
{
	import flash.display.BitmapData;
	import flash.display.JPEGEncoderOptions;
	import flash.events.Event;
	import flash.events.IOErrorEvent;
	import flash.events.SecurityErrorEvent;
	import flash.net.URLLoader;
	import flash.net.URLLoaderDataFormat;
	import flash.net.URLRequest;
	import flash.net.URLRequestHeader;
	import flash.net.URLRequestMethod;
	import flash.net.URLVariables;
	import flash.utils.ByteArray;
	import goaliemind.system.LogUtil;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class ServerHandler 
	{
		
		public function ServerHandler() 
		{
			
		}
		
		public function checkUsername(uname:String, onComplete:Function):void
		{
			var urlreq:URLRequest = new URLRequest (GlobalSettings.SERVER_ROOT + GlobalSettings.CHECKUSERNAME);
			urlreq.method = URLRequestMethod.GET; 

			var urlvars:URLVariables = new URLVariables(); 
			urlvars.checkUsername = true;
			urlvars.form_uname = uname;
			urlreq.data = urlvars;          

			var loader:URLLoader = new URLLoader(urlreq); 
			loader.addEventListener(Event.COMPLETE, onCheckComplete); 
			loader.load(urlreq); 

			function onCheckComplete(e:Event):void
			{
				loader.removeEventListener(Event.COMPLETE, onCheckComplete); 
				
				var result:Object = JSON.parse(loader.data);
				
				trace("ServerHandler.checkUsername.onCheckComplete()" + result);
				
				onComplete( result );
			}
		}
		
		public function checkEmail(umail:String, onComplete:Function):void
		{
			var urlreq:URLRequest = new URLRequest (GlobalSettings.SERVER_ROOT + GlobalSettings.CHECKEMAIL);
			urlreq.method = URLRequestMethod.GET; 

			var urlvars:URLVariables = new URLVariables(); 
			urlvars.checkUsermail = true;
			urlvars.form_umail = umail;
			urlreq.data = urlvars;          

			var loader:URLLoader = new URLLoader(urlreq); 
			loader.addEventListener(Event.COMPLETE, onCheckComplete); 
			loader.load(urlreq); 

			function onCheckComplete(e:Event):void
			{
				loader.removeEventListener(Event.COMPLETE, onCheckComplete); 
				
				var result:Object = JSON.parse(loader.data);
				
				trace("ServerHandler.checkEmail.onCheckComplete()" + result);
				
				onComplete( result );
			}
		}		
		
		public function checkAuthorization(uname:String, upassword:String, umail:String, onComplete:Function):void
		{
			var urlreq:URLRequest = new URLRequest (GlobalSettings.SERVER_ROOT + GlobalSettings.CHECKAUTHORIZATION);
			urlreq.method = URLRequestMethod.GET; 

			// check if username is an email
			if (!isValidEmail(umail)) umail = "";
			
			var urlvars:URLVariables = new URLVariables(); 
			urlvars.login = true;
			urlvars.form_uname = uname;
			urlvars.form_upass = upassword;
			urlvars.form_umail = umail;
			urlreq.data = urlvars;          

			var loader:URLLoader = new URLLoader(urlreq); 
			loader.addEventListener(Event.COMPLETE, onCheckComplete); 
			loader.load(urlreq); 

			function onCheckComplete(e:Event):void
			{
				loader.removeEventListener(Event.COMPLETE, onCheckComplete); 
				
				var result:Object = JSON.parse(loader.data);
				
				trace("ServerHandler.checkAuthorization.onCheckComplete()" + result);
				
				onComplete( result );
			}
		}	
		
		public function signIn(uname:String, upassword:String, umail:String, onComplete:Function):void
		{
			var urlreq:URLRequest = new URLRequest (GlobalSettings.SERVER_ROOT + GlobalSettings.SIGNIN);
			urlreq.useCache = false;
			urlreq.method = URLRequestMethod.GET; 

			// check if username is an email
			if (!isValidEmail(umail)) umail = "";
			
			var urlvars:URLVariables = new URLVariables(); 
			urlvars.login = true;
			urlvars.form_uname = uname;
			urlvars.form_upass = upassword;
			urlvars.form_umail = umail;
			urlreq.data = urlvars;          

			var loader:URLLoader = new URLLoader(urlreq); 
			loader.addEventListener(Event.COMPLETE, onCheckComplete); 
			loader.load(urlreq); 

			function onCheckComplete(e:Event):void
			{
				loader.removeEventListener(Event.COMPLETE, onCheckComplete); 
				
				var result:Object = JSON.parse(loader.data);
				
				trace("ServerHandler.signIn.onCheckComplete()" + result);
				
				onComplete( result );
			}
		}			
		
		public function sendRegistration(userData:UserData, onComplete:Function):void
		{
			var urlreq:URLRequest = new URLRequest (GlobalSettings.SERVER_ROOT + GlobalSettings.SENDREGISTRATION);
			urlreq.method = URLRequestMethod.GET; 

			var date:Date = new Date();
			
			userData.registerDate = date.time.toString();
			userData.signedInDate = date.time.toString();
			
			var urlvars:URLVariables = new URLVariables(); 
			urlvars.signup = true;
			urlvars.form_uname = userData.username;
			urlvars.form_upass = userData.password;
			urlvars.form_umail = userData.email;
			urlvars.form_accountType = userData.accountType;
			urlvars.form_gender = userData.gender;
			urlvars.form_birthdate = userData.birthdate;
			urlvars.form_registerDate = userData.registerDate;
			urlvars.form_signedInDate = userData.signedInDate;
			urlvars.form_parentId = userData.parentUserid;
			urlreq.data = urlvars;          

			var loader:URLLoader = new URLLoader(urlreq); 
			loader.addEventListener(Event.COMPLETE, onSendComplete); 
			loader.load(urlreq); 

			function onSendComplete(e:Event):void
			{
				loader.removeEventListener(Event.COMPLETE, onSendComplete); 
				
				var result:Object = JSON.parse(loader.data);
				
				trace("ServerHandler.sendRegistration.onSendComplete()" + result);
				
				onComplete( result );
			}
		}	
		
		public function logOut(userData:UserData, onComplete:Function):void
		{
			var urlreq:URLRequest = new URLRequest (GlobalSettings.SERVER_ROOT + GlobalSettings.LOGOUT);
			urlreq.useCache = false;
			urlreq.method = URLRequestMethod.GET; 

			var urlvars:URLVariables = new URLVariables(); 
			urlvars.logout = true;
			urlvars.userid = userData.userid;
			urlvars.uname = userData.username;
			urlvars.upass = userData.password;
			urlvars.umail = userData.email;
			urlreq.data = urlvars;          

			var loader:URLLoader = new URLLoader(urlreq); 
			loader.addEventListener(Event.COMPLETE, onCheckComplete); 
			loader.load(urlreq); 

			function onCheckComplete(e:Event):void
			{
				loader.removeEventListener(Event.COMPLETE, onCheckComplete);
				
				var result:Object = JSON.parse(loader.data);
				
				trace("ServerHandler.logOut.onCheckComplete()" + result);
				
				onComplete( result );
			}
		}			
		
		private function isValidEmail(email:String):Boolean 
		{
			var emailExpression:RegExp = /([a-z0-9._-]+?)@([a-z0-9.-]+)\.([a-z]{2,4})/;
			return emailExpression.test(email);
		}
		
		///////////////////////////////////////////////////////////////////////////////
		// PROJECT Handling
		////////////////////////////////////////////////////////////////////////////////		
		
		public function createNewProject(userData:UserData, projectInfo:Object, onComplete:Function):void
		{
			var urlreq:URLRequest = new URLRequest (GlobalSettings.SERVER_ROOT + GlobalSettings.CREATEPROJECT);
			urlreq.method = URLRequestMethod.GET; 

			var now:Date = new Date();
			
			var urlvars:URLVariables = new URLVariables(); 
			urlvars.newproject = true;
			urlvars.uname = userData.username;
			urlvars.upass = userData.password;
			urlvars.umail = userData.email;
			urlvars.atype = userData.accountType;
			urlvars.parentid = userData.parentUserid;
			urlvars.description = projectInfo.description;
			urlvars.tags = projectInfo.tags;
			urlvars.time = now.time.toString();
			urlvars.month = now.month;
			urlvars.year = now.fullYear;
			
			urlreq.data = urlvars;          

			var loader:URLLoader = new URLLoader(urlreq); 
			loader.addEventListener(Event.COMPLETE, onSendComplete); 
			loader.load(urlreq); 

			function onSendComplete(e:Event):void
			{
				loader.removeEventListener(Event.COMPLETE, onSendComplete); 
				
				var result:Object = JSON.parse(loader.data);
				
				trace("ServerHandler.createNewProject.onSendComplete()" + result);
				
				onComplete( result );
			}
		}			
		
		///////////////////////////////////////////////////////////////////////////////
		// PROJECT IMAGE Handling
		////////////////////////////////////////////////////////////////////////////////		
		
		public function uploadUserImageToServer(userData:UserData, imageName:String, resultData:Object, image:BitmapData, onComplete:Function):void
        {
			/* resultData
			$response["userid"] = $userid;
			$response["projectid"] = $newprojectid;
			$response["month"] = $month;
			$response["year"] = $year;
			$response["imageName"] = $imageName;
			*/
			
			// var imageFile:ByteArray = image.encode(image.rect, new PNGEncoderOptions(true));
			var imageFile:ByteArray = image.encode(image.rect, new JPEGEncoderOptions(50));
            
            var loader:URLLoader = new URLLoader();
            var url:String = GlobalSettings.SERVER_ROOT + GlobalSettings.UPLOADUSERPHOTO;
			url += "?i=" + imageName;
			url += "&uid=" + resultData["userid"];
			url += "&upass=" + userData.password;
			url += "&uname=" + userData.username;
			url += "&umail=" + userData.email;
			url += "&month=" + resultData["month"];
			url += "&year=" + resultData["year"];
			
			trace("uploadUserImageToServer() " + url);
			
            var req:URLRequest = new URLRequest(url);
            // req.requestHeaders =  new Array(new URLRequestHeader("Content-Type", "image/png"));
            req.requestHeaders =  new Array(new URLRequestHeader("Content-Type", "image/jpeg"));
            loader.dataFormat = URLLoaderDataFormat.BINARY;
            // req.contentType = "image/png";
            req.contentType = "image/jpeg";
            req.method = URLRequestMethod.POST;
            req.data = imageFile;
            loader.addEventListener(Event.COMPLETE, onUploadComplete);
			loader.addEventListener(IOErrorEvent.IO_ERROR, onIOErrorEvent);
			loader.addEventListener(SecurityErrorEvent.SECURITY_ERROR, onSecurityError);
            loader.load(req);			
			
			function onUploadComplete(e:Event):void
			{
				removeEvents();
				
				LogUtil.log("ServerHandler.uploadUserImageToServer.onUploadComplete()");
				
				onComplete();
			}
			
			function onIOErrorEvent(e:Event):void
			{
				removeEvents();
				LogUtil.log("ServerHandler.uploadUserImageToServer.onIOErrorEvent()");
				
				onComplete();
			}
			
			function onSecurityError(e:Event):void
			{
				removeEvents();
				LogUtil.log("ServerHandler.uploadUserImageToServer.onIOErrorEvent()");
				
				onComplete();
			}
			
			function removeEvents():void
			{
				loader.removeEventListener(Event.COMPLETE, onUploadComplete);
				loader.removeEventListener(IOErrorEvent.IO_ERROR, onIOErrorEvent);
				loader.removeEventListener(SecurityErrorEvent.SECURITY_ERROR, onSecurityError);
			}
			
		}	
		
		public function getProjectList(userData:UserData, skip:int, max:int, onComplete:Function):void
		{
			var urlreq:URLRequest = new URLRequest (GlobalSettings.SERVER_ROOT + GlobalSettings.PROJECTLIST);
			urlreq.method = URLRequestMethod.GET; 

			var now:Date = new Date();
			
			var urlvars:URLVariables = new URLVariables(); 
			urlvars.projectlist = true;
			urlvars.uname = userData.username;
			urlvars.upass = userData.password;
			urlvars.umail = userData.email;
			urlvars.atype = userData.accountType;
			urlvars.parentid = userData.parentUserid;
			urlvars.skip = skip;
			urlvars.max = max;
			
			urlreq.data = urlvars;          

			var loader:URLLoader = new URLLoader(urlreq); 
			loader.addEventListener(Event.COMPLETE, onSendComplete); 
			loader.load(urlreq); 

			function onSendComplete(e:Event):void
			{
				loader.removeEventListener(Event.COMPLETE, onSendComplete); 
				
				var result:Object = JSON.parse(loader.data);
				
				trace("ServerHandler.getProjectList.onSendComplete()" + result);
				
				onComplete( result as Array );
			}
		}			
		
	}
	

}