package goaliemind.data 
{
	import flash.events.Event;
	import flash.events.IOErrorEvent;
	import flash.filesystem.File;
	import flash.filesystem.FileMode;
	import flash.filesystem.FileStream;
	import flash.utils.ByteArray;
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class DataHandler 
	{
		public static const DATA_SAVED_COMPLETE:String = "data-saved";
		public static const DATA_LOADED_COMPLETE:String = "data-loaded";
		public static const IO_ERROR:String = "data-ioerror";
		
		private var _userDataFilename:String = "userData.gm";
		private var _saveCloseFunc:Function;
		private var _loadCloseFunc:Function;
		private var _stream:FileStream;
		
		public function DataHandler() 
		{
			
		}
		
		// SAVE USER DATA SETTINGS **********************************************************
		// UserData is saved once user has been logged in.  Purpose is to auto login when user re-launches app in the future
		public function saveUserDataFile(userData:UserData, saveCloseFunc:Function):void {
			
			_saveCloseFunc = saveCloseFunc;
			
			var userFile:File = File.applicationStorageDirectory;
			userFile.preventBackup = false;
			userFile = userFile.resolvePath("data/" + _userDataFilename);
			
			_stream = new FileStream();
			
			var copier:ByteArray = new ByteArray();
			
			var loadedUserData:Object = { };
			loadedUserData.userid = userData.userid;
			loadedUserData.username = userData.username;
			loadedUserData.password = userData.password;
			loadedUserData.email = userData.email;
			loadedUserData.accountType = userData.accountType;
			loadedUserData.parentUserid = userData.parentUserid;
			loadedUserData.gender = userData.gender;
			loadedUserData.birthdate = userData.birthdate;
			loadedUserData.isAuthorized = userData.isAuthorized;
			loadedUserData.authorizeKey = userData.authorizeKey;
			loadedUserData.registerDate = userData.registerDate;
			loadedUserData.signedInDate = userData.signedInDate;
			loadedUserData.signedIn = userData.signedIn;
			loadedUserData.login_token = userData.login_token;
			
			copier.writeObject(loadedUserData);
			copier.position = 0;
			
			_stream.addEventListener(Event.CLOSE, onSaveUserDataClose);
			_stream.addEventListener(IOErrorEvent.IO_ERROR, onSaveUserDataError);
			_stream.openAsync(userFile, FileMode.WRITE);
			_stream.writeBytes(copier);
			_stream.close();
			
			function onSaveUserDataClose(e:Event):void 
			{
				removeSaveStreamEvents();
				_saveCloseFunc({ status: DATA_SAVED_COMPLETE });
			}
			
			function onSaveUserDataError(e:Event):void 
			{
				removeSaveStreamEvents();
				_saveCloseFunc({ status: IO_ERROR });
			}			
			
			function removeSaveStreamEvents():void 
			{
				_stream.removeEventListener(Event.CLOSE, onSaveUserDataClose);
				_stream.removeEventListener(IOErrorEvent.IO_ERROR, onSaveUserDataError);
			}		
		}
		
		// LOAD USER DATA SETTINGS *******************************************************
		// We will attempt to load the file, if it does not exist lets create one
		public function loadUserDataFile(userData:UserData, loadCloseFunc:Function):void {
			
			_loadCloseFunc = loadCloseFunc;
			
			var userFile:File = File.applicationStorageDirectory;
			userFile = userFile.resolvePath("data/" + _userDataFilename);
			
			_stream = new FileStream();
			
			_stream.addEventListener(Event.CLOSE, onLoadUserDataClosed);
			_stream.addEventListener(Event.COMPLETE, onLoadUserDataComplete);
			_stream.addEventListener(IOErrorEvent.IO_ERROR, onLoadUserDataError);
			
			_stream.openAsync(userFile, FileMode.READ);
			
			function onLoadUserDataComplete(e:Event):void 
			{
				var copier:ByteArray = new ByteArray();
				copier.position = 0;
				_stream.readBytes(copier, 0);
				
				var loadedUserData:Object = copier.readObject();
				userData.userid = loadedUserData.userid;
				userData.username = loadedUserData.username;
				userData.password = loadedUserData.password;
				userData.email = loadedUserData.email;
				userData.accountType = loadedUserData.accountType;
				userData.parentUserid = loadedUserData.parentUserid;
				userData.gender = loadedUserData.gender;
				userData.birthdate = loadedUserData.birthdate;
				userData.isAuthorized = loadedUserData.isAuthorized;
				userData.authorizeKey = loadedUserData.authorizeKey;
				userData.registerDate = loadedUserData.registerDate;
				userData.signedInDate = loadedUserData.signedInDate;
				userData.signedIn = loadedUserData.signedIn;
				userData.login_token = loadedUserData.login_token;
				
				_stream.close();
				copier.clear();
			}
			
			function onLoadUserDataClosed(e:Event):void 
			{
				removeLoadStreamEvents();
				_loadCloseFunc({ status: DATA_LOADED_COMPLETE });
			}
			
			function onLoadUserDataError(e:Event):void 
			{
				// If UserData file has never been saved, we will catch it here
				// And create one.
				removeLoadStreamEvents();
				_stream.close();
				_loadCloseFunc({ status: IO_ERROR });
			}	
				
			function removeLoadStreamEvents():void 
			{
				_stream.removeEventListener(Event.COMPLETE, onLoadUserDataComplete);
				_stream.removeEventListener(Event.CLOSE, onLoadUserDataClosed);
				_stream.removeEventListener(IOErrorEvent.IO_ERROR, onLoadUserDataError);
			}
		}		
	
		// REMOVE USER DATA SETTINGS *******************************************************
		// We will remove UserData when logout is triggered
		public function removeUserDataFile(loadCloseFunc:Function):void {
			
			_loadCloseFunc = loadCloseFunc;
			
			var userFile:File = File.applicationStorageDirectory;
			userFile = userFile.resolvePath("data/" + _userDataFilename);
			
			userFile.addEventListener(Event.COMPLETE, onRemoveUserDataComplete);
			userFile.addEventListener(IOErrorEvent.IO_ERROR, onRemoveUserDataError);
			userFile.deleteFileAsync();
			
			function onRemoveUserDataComplete(e:Event):void 
			{	
				removeFileEvents();
				_loadCloseFunc({ status: DATA_LOADED_COMPLETE });
			}
			
			function onRemoveUserDataError(e:Event):void 
			{
				removeFileEvents();
				_loadCloseFunc({ status: IO_ERROR });
			}	
				
			function removeFileEvents():void 
			{
				userFile.addEventListener(Event.COMPLETE, onRemoveUserDataComplete);
			}
		}		
	
		///////////////////////////////////////////////////////////////////////////////
		// PROJECT DATA Handling
		////////////////////////////////////////////////////////////////////////////////
		

		



		
		
	}

}