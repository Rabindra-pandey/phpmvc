<?php

date_default_timezone_set("Asia/Calcutta");

//CONSTANTS START HERE
define("DB_HOST","localhost");
define("DB_USER","root");
define("DB_PASS","");
define("DB_NAME","assesmenttesting");
define("DB_NAME_SAT","assesmenttesting");
define("DB_NAME_LAMS","pli");
define("DB_NAME_CALC","pli_calculator");

define("SITE_ENV","DEV");
define('APPS_URL', 'http://localhost:8080/rabindra/PLI_Calculator/');
define("default_controller","home");


$status = array('Open', 'WIP', 'Approved', 'Not Approved', 'Recheck');
define ("JOB_STATUS", $status);

$jobTypes = array('Deeplink', 'New Amends', 'Veeva', 'Interactive Pdf', 'Static PDF', 'Video', 'Adobe campaign', 'Generic', 'HTML', 'Interactive', 'Veeva Emailer', 'Generic Emailer', 'Adobe', 'Banner', 'Detailaid', 'AEM', 'Expire', 'Static Video', 'Content Authoring', 'Leave Piece', 'Web Banner', 'Print-Banner', 'Newsletter', 'Global', 'Interactive navigational HTML', 'Textual/New Page Creation', 'Leaflet', 'Poster', 'Webinar', 'VIIV', 'Followup Meetin', 'Drafts/Push To Live/Deeplinks', 'Push to Live', 'PIs', 'cobrowse');
define ("JOB_TYPES", $jobTypes);

$draftNo = array();
for($i=1; $i<=15; $i++){
	$draftNo[] = $i;
}
array_push($draftNo, 'Signoff', 'Recheck', 'Expire');
define ("DRAFT_NO", $draftNo);

$complexity = array('C1 - Scratch', 'C2 - Localization/Adaptation', 'C3 - Drafts', 'C1 - Interactive/Resources/Filters', 'C2 - Video/Events/IHS Hosting', 'C3 - Textual/New Page Creation/Deeplinks', 'C4 - Drafts/Push To Live', 'C5 - CRM Tagging/Tag Creation/Data Layer', 'C1 - New Creation', 'C2 - Localization/Adaptation', 'C3 - Drafts/GSK Vault/Sign-Off', 'C4 - Expire Content', 'C1 - Adaptation', 'C2 - Localization', 'C3 - Int.PDF/Drafts/Sign-Off', 'C4 - Static PDF/Video', 'C5 - Agency QC', 'C6 - Expire Content');
define ("COMPLEXITY", $complexity);

?>