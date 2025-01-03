<?php
/*session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/


$contact_card = fopen("alerts_log","w");
$today = new DateTime() ;
$dateString = $today->format('Y-m-d H:i:s');
fprintf($contact_card,"Started $dateString\n ");
fclose($contact_card);

?>