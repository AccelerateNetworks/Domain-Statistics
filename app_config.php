<?php
//application details
$apps[$x]['name'] = "Domain Statistics";
$apps[$x]['uuid'] = "ab583432-192d-4287-a4b1-7aa5daa580a0";
$apps[$x]['category'] = "App";
$apps[$x]['subcategory'] = "";
$apps[$x]['version'] = "0.1";
$apps[$x]['license'] = "GNU General Public License v3";
$apps[$x]['url'] = "https://git.callpipe.com/fusiobpbx/domain-statistics";
$apps[$x]['description']['en-us'] = "A module to get call statistics for each domain";
$apps[$x]['description']['es-cl'] = "";
$apps[$x]['description']['de-de'] = "";
$apps[$x]['description']['de-ch'] = "";
$apps[$x]['description']['de-at'] = "";
$apps[$x]['description']['fr-fr'] = "";
$apps[$x]['description']['fr-ca'] = "";
$apps[$x]['description']['fr-ch'] = "";
$apps[$x]['description']['pt-pt'] = "";
$apps[$x]['description']['pt-br'] = "";

//permission details
$y=0;
$apps[$x]['permissions'][$y]['name'] = "domain_statistics";
$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
