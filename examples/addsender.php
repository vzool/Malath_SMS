<?php

	include('sms.class.php');

	// Malath_SMS(UserName,Password,PHP File Encode)

	$DTT_SMS = new Malath_SMS("", "", 'UTF-8');
	include('formsender.php');
	if (isset($_POST['Go'])) {
    $Name = $_POST['Name'];
    
		if ($_POST['Name']) {
			$Send = $DTT_SMS->AddSender($Name);
			echo '<br /><br /><b style="color:#003300">Send Result : </b>';
			echo '<pre>';
			print_r($Send);
			echo '</pre>';
			
		}
	}

?>