package goaliemind.data 
{
	import goaliemind.data.interfaces.IUserAccount;
	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class UserData implements IUserAccount
	{
		public var userid:String;
		public var username:String;
		public var password:String;
		public var email:String;
		public var accountType:int = -1;	// 1 = GoalieMind	2 = MindGoalie Org	3 = MindGoalie Personal
		public var parentUserid:int = -1;	// If AccountType is a Personal Mind Goalie, we will need auth from a Parent User Id
		public var gender:String;	
		public var birthdate:String;
		public var signedIn:Boolean;
		public var isAuthorized:Boolean;
		public var authorizeKey:String;
		public var registerDate:String;
		public var signedInDate:String;
		public var login_token:String;
		
		public var friends:Array;
		
		public function UserData() 
		{
			
		}
		
	}

}