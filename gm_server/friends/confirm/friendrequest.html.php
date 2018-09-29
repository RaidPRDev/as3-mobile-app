<style>
	@import url(http://fonts.googleapis.com/css?family=Open+Sans:700,600,400);
</style>

<div style="background-color: #F4F4F4 ; padding: 30px ; font-family: Open Sans, Arial, Verdana ; ">

    <div style="width: 600px ; background-color: #FFFFFF ; margin: 0 auto ; border: 1px solid #d0d5d8 ; 
    border-radius: 3px ;-webkit-border-radius: 3px ;
     -moz-border-radius: 3px ; text-align:center; ">
                                                     
        <img
         src="http://www.raidpr.com/email/img/goaliegroupimg.png" width="431" height="201" border="0" alt="Goalie Groups" 
         style="padding: 60px 0 14px 0; margin:0 auto; ">
        
        <div style=" clear: both ; ">
        
            <span style="background-color: #CACFD3 ; border-radius: 50% ;
             -moz-border-radius: 50% ; -webkit-border-radius: 50% ; color: #959BA1 ; display:
             block ; font-size: 18px ; font-weight: 600 ; height: 40px ; line-height: 40px ;
             text-align: center ; width: 40px ; margin:0 auto;">
				<?php 
					$len = strlen($emailInfo['friendUserName']);
					$firstLetter = substr($emailInfo['friendUserName'], 0, -$len + 1);
					echo strtoupper($firstLetter); 
				?>
			</span>
            
            <div style="color: #323a45 ; font-size: 17px ; font-weight: bold ;
             text-align: center ; font-family: 'Open Sans',
             sans-serif;text-transform:uppercase;padding-top:10px;"><?php echo ($emailInfo['friendUserName']) ?></div>
            
            <p style="margin-top: 10px ; color: #747c83 ; font-size: 13px ;
             font-weight: 600 ; line-height: 18px ; text-align: center ; text-transform:
             uppercase; font-family: 'Open Sans', sans-serif;">you're now friends with <b><?php echo ($emailInfo['uname']) ?></b>.<br>Send a message to say hello!</p>
                
			<?php 
				if ($emailInfo["hasBeenValidated"] == true) {?>
				
					<div style="text-align: center ; font-size: 13px ; font-weight: bold ;
					margin: 67px  0 80px 0; "><a href="#" style="border-radius:3px; color: #ffffff; background:#329a99; padding: 16px 46px; 
					text-decoration:none;">ALREADY VALIDATED!</a></div>		
			<?php } ?>
		</div>	
			
	</div>

    <table style="width: 600px ; margin: 0 auto ; ">
        <tr><td style="padding-top:30px" width="600" align="center">
    
        <a href="http://www.invisionapp.com/?utm_medium=virality&utm_source=Gifting%20Emails&utm_campaign=app_email">
        <img src="http://www.raidpr.com/email/img/goaliemind_footer_logo.png" width="79" height="58" border="0" 
        alt="GoalieMind logo" /></a>
        
        <div style="width: 250px ; font-size: 9px; color: #329a99; padding-top:10px;">
            <span style="text-transform:uppercase;">CURIOSITY INVENTED THE LIGHT BULB</span><br><br>
            <i style="font-size:10px;padding-top:10px;color: #bbbec0;">&copy; <?php echo date("Y"); ?> GoalieMind, LLC | Mobile Application</i><br><br>
            <span style="font-size:10px;padding-top:10px;color: #bbbec0;">This email was sent from a notification-only address 
            that cannot accept incoming email. Please do not reply to this message.</span>
        </div>
    
        </td></tr>
    </table>
		
</div>