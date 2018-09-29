      <style>
			@import url(http://fonts.googleapis.com/css?family=Open+Sans:700,600,400);
		</style>

		<div style="background-color: #F4F4F4 ; padding: 30px ; font-family: Open Sans, Arial, Verdana ; ">

			<div style="width: 600px ; background-color: #FFFFFF ; margin: 0 auto ; border: 1px solid #d0d5d8 ; 
			border-radius: 3px ;-webkit-border-radius: 3px ;
			 -moz-border-radius: 3px ; text-align:center; ">
															 
				<img
				 src="<?php echo ($emailInfo["serverPath"] . 'mailer/images/goaliegroupimg.png') ?>" width="431" height="201" border="0" alt="GoalieMind" 
				 style="padding: 60px 0 14px 0; margin:0 auto; ">
				
				<div style=" clear: both ; ">
				
					<span style="background-color: #CACFD3 ; border-radius: 50% ;
					 -moz-border-radius: 50% ; -webkit-border-radius: 50% ; color: #959BA1 ; display:
					 block ; font-size: 14px ; font-weight: 600 ; height: 40px ; line-height: 40px ;
					 text-align: center ; width: 40px ; margin:0 auto;"><?php echo ($emailInfo['userInitials']) ?></span>
					
					<div style="color: #323a45 ; font-size: 17px ; font-weight: bold ;
					 text-align: center ; font-family: 'Open Sans',
					 sans-serif;text-transform:uppercase;padding-top:10px;">Hello <?php echo ($emailInfo['uname']) ?>!</div>
					
					<p style="margin-top: 10px ; color: #747c83 ; font-size: 13px ;
					 font-weight: 600 ; line-height: 18px ; text-align: center ; text-transform:
					 uppercase; font-family: 'Open Sans', sans-serif;">You have sent funds to <b><?php echo ($emailInfo['projectUsername']) ?></b>!</p>
					 
					<p style="margin-top: 10px ; color: #747c83 ; font-size: 20px ;
					 font-weight: 600 ; line-height: 18px ; text-align: center ; text-transform:
					 uppercase; font-family: 'Open Sans', sans-serif;">$<?php echo ($emailInfo['fundAmount']) ?> USD!</p>
					 
					<p style="margin-top: 10px ; color: #747c83 ; font-size: 13px ;
					 font-weight: 600 ; line-height: 18px ; text-align: center ; margin-bottom:40px; text-transform:
					 uppercase; font-family: 'Open Sans', sans-serif;">For the project: <b><?php echo ($emailInfo['projectTitle']) ?></b></p>
					 
					<hr> 
					 
					<p style="margin-top: 10px ; color: #747c83 ; font-size: 10px ;
					 font-weight: 600 ; line-height: 18px ; text-align: center ; margin-top:20px; margin-bottom:20px; text-transform:
					 uppercase; font-family: 'Open Sans', sans-serif;">
					 Transaction ID: <b><?php echo ($emailInfo['balance_transaction']) ?></b><br>
					 Invoice ID: <b><?php echo ($emailInfo['invoice']) ?></b><br>
					 Created: <b><?php echo date('m/d/Y H:i:s', $emailInfo['created']); ?></b>
					</p>
					
				</div>	
					
			</div>

			<table style="width: 600px ; margin: 0 auto ; ">
				<tr><td style="padding-top:30px" width="600" align="center">
			
				<a href="http://www.goaliemind.com">
				<img src="<?php echo ($emailInfo["serverPath"] . 'mailer/images/goaliemind_footer_logo.png') ?>" width="79" height="58" border="0" 
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