<?php
include('sms.class.php');

// Malath_SMS(Username,Password,PHP File Encode)

$DTT_SMS    = new Vzool/Malath/Malath_SMS("", "", 'UTF-8');
$Credits    = $DTT_SMS->GetCredits();
$SenderName = $DTT_SMS->GetSenders();
$CheckUser  = $DTT_SMS->CheckUserPassword();
include('form.php');
if (isset($_POST['check'])) {
    $Send = $DTT_SMS->CheckUserPassword($CheckUser);
    echo '<br /><br /><b style="color:#003300">Send Result : </b>';
    echo '<pre>';
    print_r($Send);
    echo '</pre>';
}
if (isset($_POST['Go'])) {
    $SmS_Msg    = $_POST['Text'];
    $Mobiles    = $_POST['Mobile'];
    $Originator = $_POST['Originator'];
    
    if ($_POST['Text']) {
        $Send = $DTT_SMS->Send_SMS($Mobiles, $Originator, $SmS_Msg);
        echo '<br /><br /><b style="color:#fff">Send Result : </b>';
        echo '<pre>';
        print_r($Send);
        echo '</pre>';
        
    }
}

?>