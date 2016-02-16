<?php
//application details
$apps[$x]['name'] = "CDR API";
$apps[$x]['uuid'] = "4580b6da-3c91-4537-ab67-626ae4716e95";
$apps[$x]['category'] = "App";
$apps[$x]['subcategory'] = "";
$apps[$x]['version'] = "0.1";
$apps[$x]['license'] = "GNU General Public License v3";
$apps[$x]['url'] = "https://git.callpipe.com/fusiobpbx/cdr-api";
$apps[$x]['description']['en-us'] = "A module to emulate expose the CDR via API";
$apps[$x]['description']['es-cl'] = "";
$apps[$x]['description']['de-de'] = "";
$apps[$x]['description']['de-ch'] = "";
$apps[$x]['description']['de-at'] = "";
$apps[$x]['description']['fr-fr'] = "";
$apps[$x]['description']['fr-ca'] = "";
$apps[$x]['description']['fr-ch'] = "";
$apps[$x]['description']['pt-pt'] = "";
$apps[$x]['description']['pt-br'] = "";

$y = 0;
$z = 0;
$apps[$x]['db'][$y]['table'] = "cdr_api_keys";
$apps[$x]['db'][$y]['fields'][$z]['name']['text'] = 'token_uuid';
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = 'uuid';
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = 'text';
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = 'char(36)';
$apps[$x]['db'][$y]['fields'][$z]['description'] = '';

$z++;

$apps[$x]['db'][$y]['fields'][$z]['name']['text'] = 'key';
$apps[$x]['db'][$y]['fields'][$z]['type'] = 'text';
$apps[$x]['db'][$y]['fields'][$z]['description'] = '';
$z++;

$apps[$x]['db'][$y]['fields'][$z]['name']['text'] = 'enabled';
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = 'boolean';
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = 'boolean';
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = 'tinyint(1)';
$apps[$x]['db'][$y]['fields'][$z]['description'] = '';
$z++;

$apps[$x]['db'][$y]['fields'][$z]['name'] = 'domain_uuid';
$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = 'uuid';
$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = 'text';
$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = 'char(36)';
$apps[$x]['db'][$y]['fields'][$z]['key']['type'] = 'foreign';
$apps[$x]['db'][$y]['fields'][$z]['key']['reference']['table'] = 'v_domains';
$apps[$x]['db'][$y]['fields'][$z]['key']['reference']['field'] = 'domain_uuid';
$apps[$x]['db'][$y]['fields'][$z]['description'] = '';
