<?php


error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE);
 
/*
define( 'ABSPATH', '../../../' );

if ( file_exists( ABSPATH . 'wp-config.php') ) {

require_once( ABSPATH . 'wp-config.php' );}
*/
	
if ( file_exists( '../../../wp-config.php') ) {

require_once( '../../../wp-config.php'); 
	
	
global $wpdb;

$curdate = date("Ymd");
$curtime = date("His");
//initiate connection to wordpress database.
global $wpdb;


//$event_id = $_REQUEST['event_id'];
(is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
$sql = "SELECT * FROM ". get_option('evr_event') ." WHERE id=".$event_id;
                    		$result = mysql_query ($sql);
                            
                    		while ($row = mysql_fetch_assoc ($result)){  
                         
                            $event_id       = $row['id'];
            				$event_name     = html_entity_decode(stripslashes($row['event_name']),ENT_NOQUOTES, 'UTF-8');
        					$event_identifier = stripslashes($row['event_identifier']);
            				$event_location = html_entity_decode(stripslashes($row['event_location']),ENT_NOQUOTES, 'UTF-8');
                            $event_desc     = html_entity_decode(stripslashes($row['event_desc']),ENT_NOQUOTES, 'UTF-8');
                            $event_address  = $row['event_address'];
                            $event_city     = $row['event_city'];
                            $event_state     = $row['event_state'];
                            $event_postal   = $row['event_postal'];
                            $reg_limit      = $row['reg_limit'];
                    		$start_time     = $row['start_time'];
                    		$end_time       = $row['end_time'];
                    		$conf_mail      = $row['conf_mail'];
                            //$custom_mail    = $row['custom_mail'];
                    		$start_date     = $row['start_date'];
                    		$end_date       = $row['end_date'];

}
/*
$data = "";
$data .= __('BEGIN:VCALENDAR\n');
$data .= __('BEGIN:VEVENT\n');
$data .= __('DTSTART:');
$date .= date("Ymd",$start_date);
$data .= __('T');
$data .=  date("His",$start_time)."\n";
$data .= __('DTEND:');
$data .=  date("Ymd",$end_date);
$data .= __('T');
$data .= date("His",$end_time)."\n";
$data .= __('LOCATION;ENCODING=QUOTED-PRINTABLE:');
$data .=  $event_location.", ".$event_address.", ".$event_city.", ".$event_state.", ".$event_postal."\n";
$data .= __('SUMMARY;ENCODING=QUOTED-PRINTABLE:');
$data .= $start_date." - ".$event_name."\n";
$data .= __('URL:');
$data .= $registration_link."\n";
$data .= __('DESCRIPTION:');
$data .= $event_desc."\n";
$data .= __('END:VEVENT\n');
$data .= __('END:VCALENDAR\n');


*/


//This is the most important coding.
header("Content-Type: text/Caledar");
header("Content-Disposition: inline; filename=".rawurlencode($event_name).".ics");


echo "BEGIN:VCALENDAR\n";
//echo "PRODID:-//Microsoft Corporation//Outlook 12.0 MIMEDIR//EN\n";
//echo "VERSION:2.0\n";
//echo "METHOD:PUBLISH\n";
//echo "X-MS-OLK-FORCEINSPECTOROPEN:TRUE\n";
echo "BEGIN:VEVENT\n";
echo "CLASS:PUBLIC\n";
//echo "CREATED:20091109T101015Z\n";
echo "CREATED:".$curdate."T".$curtime."\n";
//echo "DESCRIPTION:How 2 Guru Event\\n\\n\\nEvent Page\\n\\nhttp://www.myhow2guru.com\n";
echo "DESCRIPTION:".$event_desc."\n";
echo "DTEND:".date("Ymd",strtotime($end_date))."T".date("His",strtotime($end_time))."\n";
echo "DTSTAMP:".$curdate."T".$curtime."\n";;
echo "DTSTART:".date("Ymd",strtotime($start_date))."T".date("His",strtotime($start_time))."\n";
echo "LAST-MODIFIED:20091109T101015Z\n";
//echo "LOCATION:Anywhere have internet\n";
echo "LOCATION:".$event_location.", ".$event_address.", ".$event_city.", ".$event_state.", ".$event_postal."\n";;
//echo "PRIORITY:5\n";
//echo "SEQUENCE:0\n";
echo "SUMMARY;LANGUAGE=en-us:".$event_name."\n";
//echo "TRANSP:OPAQUE\n";
//echo "UID:040000008200E00074C5B7101A82E008000000008062306C6261CA01000000000000000\n";
//echo "X-MICROSOFT-CDO-BUSYSTATUS:BUSY\n";
//echo "X-MICROSOFT-CDO-IMPORTANCE:1\n";
//echo "X-MICROSOFT-DISALLOW-COUNTER:FALSE\n";
//echo "X-MS-OLK-ALLOWEXTERNCHECK:TRUE\n";
//echo "X-MS-OLK-AUTOFILLLOCATION:FALSE\n";
//echo "X-MS-OLK-CONFTYPE:0\n";
//Here is to set the reminder for the event.
echo "BEGIN:VALARM\n";
echo "TRIGGER:-PT1440M\n";
echo "ACTION:DISPLAY\n";
echo "DESCRIPTION:Reminder\n";
echo "END:VALARM\n";
echo "END:VEVENT\n";
echo "END:VCALENDAR\n";

} else echo "Bad Directory!";
?>