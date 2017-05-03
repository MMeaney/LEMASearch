<?php 

$param['index']  = ['docs'];
$param['type']  = ['doc', 'people', 'rivers', 'radon', 'datadictionary'];

$paramMainTest['index']  = ['docs'];
$paramMainTest['type']  = ['doc', 'people', 'rivers', 'radon', 'datadictionary'];

$paramfileshare['index'] =  ['docs'];
$paramfileshare['type']  = ['doc'];

$paramopendata['index'] = "gismetadatatest";
$paramopendata['type'] = ['rivers', 'radon'];

$paramdatadictionary['index'] = "epametadatatest";
$paramdatadictionary['type'] = "datadictionary";

$paramPeople['index'] = "docs";
$paramPeople['type'] = "people";

$paramSnippetMain['index'] = "epasystems";
$paramSnippetMain['type'] = "system";

$paramResearch['index'] = "safer";
$paramResearch['type'] = "saferdata";


$paramLEAP['index'] = "leap";
//$paramLEAP['type'] = "saferdata";

$param['size'] = 10;
$param['from'] = 0;

$paramfileshare['size'] = 10;
$paramfileshare['from'] = 0;

$paramopendata['size'] = 10;

$paramdatadictionary['size'] = 1000;

$paramPeople['size'] = 20;

//$paramWeb['index'] = "epasitecrawl";
//$paramWeb['type']  = "allpages";

$paramWeb['index'] = "epawebcrawl3";
$paramWeb['type']  = "pages";



//$param['index'] = "gismetadatatest,epametadatatest,food";
//$param['index']  = "nyc_restaurants";
//$param['index']  = "docs";
//$param['type']  = Constants::ES_TYPE;
//$param['type']  = "doc";
//$param['index'] = Constants::ES_INDEX;

//$paramfileshare['index'] =  ['docs'];
//$paramfileshare['index'] =  ['nyc_restaurants'];
//$paramfileshare['type']  = "doc";
//$paramfileshare['body'] = $json_query_fileshare;
//$paramopendata['index'] = "nyc_restaurants";

//$paramdatadictionary['index'] = "nyc_restaurants";
//$paramdatadictionary['index'] = "nyc_restaurants";

////$searchParams['index'] = $index;
////$searchParams['type']  = $type;

?>