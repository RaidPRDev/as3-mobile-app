package  
{
	import goaliemind.data.UserData;

	/**
	 * ...
	 * @author Rafael Alvarado Emmanuelli
	 * RAID - Rafael Alvarado Indie Development
	 * (c) 2015
	 */
	public class GlobalSettings 
	{
		// com.tester.goaliemind
		public static const EXTENSION_KEY:String = "b108828c05bca3cba10b11902646d73c1188fda8WJlS0d0nBwWwcfmO73ia0ta4GQrSM2xCrRsSujzaJVq8xteQt4Aq98maue0cL0dBv1yjsc4z9esB/h9Nl/7UemuoNwFcGwyH1dWjkSUzX2boIwh1+XFwnQe+xySacIaYAzABrZOzx4a23ZbN5wXlvnp0su+JwNK0tb33ufvdEo2+wL7AAzsRRabmrT/GbsVC1XUBmCedT9z+k5aAphXidRtr34JqWT8dtdiz0BkTHvAaanBpw3YV5a9jpxHjTv94uSnQ2O5W3gnXWU2SPDc6J/xIhmALyUmjEgjCjK+sKxi32O8PBgmrnlPni6rB7l0tXMCjLuNl/OozNTjCNQJwPg==";
		
		// goaliemind.app
		// public static const EXTENSION_KEY:String = "7693296ebe67743dacdc57773220a51aad53dc86N12MOHpV9v/6mWxxUVrQKENv3ISblY+T8HcDvcoXgufMcazJ4rItjCmvg1ESG0Wwhl0v/hf6wa+4ToADTb9XFjrAMb18f03n6ka2fdiyCBv8PLq68XEwPUk98H+i1rtecvdciI4yYWB0SU3IyBhOfqLwBwGs9iaXOKx6eAtOpgjHXyB74rd1Hs32LXkABvm1w78jfe16WddtJWIsNnlfvAtYomugdul3fIlrAz4WhBxPRJ+kJJbtMFJj+k+pj3X8cjZUe7N5BxxkKvPrvR5/pjKucyf+DbaMNf5RdfjmM9N0OSyx+fOvz+6qO3T3PE+IyEaIKEdsrKdBBeDavcZ8AA==";
		
		// public static const SERVER_ROOT:String = "http://www.raidpr.com/clients/goaliemind/";
		public static const SERVER_ROOT:String = "http://localhost:8080/goaliemind/";
		// public static const SERVER_ROOT:String = "http://10.0.0.200:8080/goaliemind/";
		// public static const SERVER_ROOT:String = "http://ec2-52-91-212-191.compute-1.amazonaws.com/glib/";
		public static const IMAGES_PATH:String = "media/images/";
		public static const CHECKUSERNAME:String = "registration/checkUsername.php";
		public static const CHECKEMAIL:String = "registration/checkUsermail.php";
		public static const SIGNIN:String = "signIn.php";
		public static const LOGOUT:String = "logout.php";
		public static const CHECKAUTHORIZATION:String = "registration/checkAuthorization.php";
		public static const SENDREGISTRATION:String = "registration/sendRegistration.php";
		public static const CREATEPROJECT:String = "project/createProject.php";
		public static const UPLOADUSERPHOTO:String = "media/uploadUserPhoto.php";
		public static const PROJECTLIST:String = "project/getProjects.php";
		
		public static const PRIVACYPOLICY:String = "privacypolicy";
		public static const TERMSOFUSE:String = "termsofuse";
		
		public static const FIELD_RESTRICT:String = "A-Za-z0-9%$#@!*&_.";
		
		public static const APPORIGINALWIDTH:int = 640;
		public static const APPORIGINALHEIGHT:int = 960;
		
		// public static const MEDIA_IMAGE_EXT:String = ".png";
		public static const MEDIA_IMAGE_EXT:String = ".jpg";
		
		public static const PRIMARY_BACKGROUND_COLOR:uint = 0xffffff;
		public static const LOGIN_HEADER_BACKGROUND_COLOR:uint = 0xe6f5f7;
		public static const CREATEUSER_HEADER_BACKGROUND_COLOR:uint = 0xffffff;
		public static const WHITE_TEXT_COLOR:uint = 0xffffff;
		public static const BLUE_TEXT_COLOR:uint = 0x359997;
		public static const RED_TEXT_COLOR:uint = 0xe93237;
		public static const LIGHT_GREY_TEXT_COLOR:uint = 0xc2c2c2;
		public static const DARK_TEXT_COLOR:uint = 0x4e4e4e;
		public static const BLACK_TEXT_COLOR:uint = 0x000000;
		
		public static const LOGIN_HEADER:String = "login-screen-header";
		public static const LOGIN_FOOTER:String = "login-screen-footer";
		public static const BUTTON_LOGIN:String = "button-login";
		public static const BUTTON_FACEBOOKLOGIN:String = "button-login-facebook";
		public static const BUTTON_LOGIN_PERCENT_WIDTH:int = 86;
		
		public static const CREATEUSER_HEADER:String = "createuser-screen-header";
		public static const CREATEUSER_FOOTER:String = "createuser-screen-footer";
		
		public static const BUTTON_GREY_BACK_HEADER:String = "button-gray-back-header";
		public static const BUTTON_GREY_NEXT_HEADER:String = "button-gray-next-header";
		
		public static const HOME_PANEL:String = "main-panel-home";
		public static const NOSCROLL_PANEL:String = "no-scroll-panel";
		public static const HOME_MAINHEADER:String = "main-header-home";
		public static const HOME_SUBHEADER:String = "sub-header-home";
		public static const HOME_MAINBOTTOMHEADER:String = "bottomheader-home";
		public static const HOME_MAINBOTTOMTAB:String = "bottomtab-home";
		public static const SUB_HEADERTAB:String = "sub-headertab";
		public static const HOMEGRIDITEMLIST:String = "home-grid-item-list";
		public static const HOMESINGLEITEMLIST:String = "home-single-item-list";
		public static const HOMEITEMDATEBUTTON:String = "home-single-item-date";
		public static const HOMEITEMSTARBUTTON:String = "home-single-item-star";
		public static const HOMEITEMCOMMENTS:String = "home-single-item-comments";
		public static const HOMEITEMBOTTOMPANEL:String = "home-single-item-bottom-panel";
		public static const MOREBUTTON:String = "more-button";
		
		
		public static const HOMEGRIDLIST:String = "home-grid-list";
		public static const HOMESINGLEVIEWLIST:String = "home-singleview-list";
		public static const SIDEMENULIST:String = "sidemenu-list";
		public static const SIDEMENULISTITEM:String = "sidemenu-list-item";
		public static const SIDEMENUPANEL:String = "sidemenu-panel";
		public static const SIDEUSERSUBHEADERPANEL:String = "sidemenu-subheader-panel";
		public static const SIDEUSERPICPANEL:String = "sidemenu-userpic-panel";
		public static const SETTINGSLISTITEM:String = "settings-list-item";
		public static const OVERLAYPANELLIST:String = "overlay-list";
		public static const OVERLAYPLAINPANEL:String = "overlay-plain-panel";
		public static const CAMUPLOADPANEL:String = "camupload-panel";
		public static const CAMUPLOADPANELHEADER:String = "camupload-panel-header";
		public static const CAMUPLOADPANELBOTTOM:String = "camupload-panel-bottom";
		public static const MAINHEADER_BUTTON:String = "main-panel-header-button";
		public static const INPUTCOMMENTS:String = "input-project-comments";
		public static const INPUTSEARCH:String = "input-search";
		
		// Main Home Labels
		public static const LABEL_30SIZE_WHITE:String = "label-30size-white";
		public static const LABEL_22SIZE_WHITE:String = "label-22size-white";
		public static const LABEL_22SIZE_DARKGREY:String = "label-22size-dark-grey";
		public static const LABEL_18SIZE_DARKGREY:String = "label-18size-dark-grey";
		
		
		// For Create User Theme
		public static const LABEL_XSMALL_BLACK:String = "label-xsmall-black";
		public static const LABEL_MEDIUM_DARKGREY:String = "label-medium-darkgrey";
		public static const LABEL_BLUE_HEADING:String = "label-blue-heading";
		public static const LABEL_BLUE_DETAILS:String = "label-blue-details";
		public static const LABEL_RED_HEADING:String = "label-red-heading";
		public static const LABEL_RED_DETAILS:String = "label-red-details";
		public static const LABEL_REGISTER_STATUS:String = "label-register-status";
		public static const LABEL_TERMS:String = "label-terms";
		
		public static const BUTTON_TOGGLE:String = "button-toggle";
		public static const BUTTON_RED_TOGGLE:String = "button-red-toggle";
		public static const BUTTON_MINDGOALIE_TOGGLE:String = "button-mindgoalie-toggle";
		public static const BUTTON_GOALIEMIND_TOGGLE:String = "button-goaliemind-toggle";
		public static const BUTTON_PERSONAL_TOGGLE:String = "button-personal-toggle";
		public static const BUTTON_ORGANIZATION_TOGGLE:String = "button-organization-toggle";
		
		public static const BUTTON_GIRL_TOGGLE:String = "button-girl-toggle";
		public static const BUTTON_BOY_TOGGLE:String = "button-boy-toggle";
		
		public static const BUTTON_SUBHEADER:String = "button-subheader";
		public static const SUBHEADER_HEIGHT:int = 208;
		public static const SUBHEADER_INPUT_WIDTH_PERCENT:int = 92;
		
		public static const SPINNER_HEADER:String = "spinner-header";
		public static const SPINNER_BIRTHDAY:String = "spinner-birthday";
		public static const SPINNER_ITEMRENDERER:String = "spinner-item";
		public static const SPINNER_INPUT_LABEL:String = "spinner-input-label";
		public static const SPINNER_FADE_PANEL:String = "spinner-fade-overlay-panel";
		
		public static const INPUTPASSWORD:String = "input_password";
		public static const SHOWPASSWORDBUTTON:String = "button-showpassword";
		public static const FORGOTPASSWORDBUTTON:String = "button-forgotpassword";
		public static const BUTTON_DARK_TITLE_ICON:String = "button-dark-title-icon";
		public static const BUTTON_RED_TITLE_ICON:String = "button-red-title-icon";
		public static const BUTTON_TERMS:String = "button-terms-text";
		public static const ALERT_BUTTONGROUP:String = "alert-button-group";
		public static const ALERT_SINGLE:String = "alert-single-button";
		public static const ALERT_SINGLECUSTOMBUTTON:String = "alert-single-custom-button";
		public static const ALERT_SINGLEBUTTONGROUP:String = "alert-singlebutton-group";
		public static const ALERT_FIRSTBUTTONGROUP:String = "alert-button-first-group";
		public static const ALERT_MIDDLEBUTTONGROUP:String = "alert-button-middle-group";
		public static const ALERT_LASTBUTTONGROUP:String = "alert-button-last-group";
		
		public static const SIMPLESCROLL:String = "simple-scrollbar";
		
		
		// MAIN ACCOUNTS
		
		// public static const GOALIEMIND_ACCOUNT:int = 1;
		// public static const MINDGOALIE_ACCOUNT:int = 2;
		// public static const ORGANIZATION_ACCOUNT:int = 3;
		public static var currentAccount:int = -1;
		
		// Used for MindGoalie registration from within a GoalieMind Account
		// We will use this as a temporary reference
		public static var parentAccount:int = -1;
		public static var parentUserData:UserData;
	}

}