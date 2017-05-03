<?php
///*
error_reporting(0);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//*/

require __DIR__ . '/../vendor/autoload.php';
//use Search\Constants;

// Get search results from Elasticsearch if the user searched for something
$results = [];

if (!empty($_REQUEST['submitted'])) {	
	
	include __DIR__ . "/defineClient.php";
	include __DIR__ . "/defineIndex.php";
	include __DIR__ . "/defineQuery.php";	
	include __DIR__ . "/definePagination.php";
	
}
	
$searchText = $_REQUEST['q']; 

$paramSize = $param['size'];
$paramFrom = $param['from'];
$paramLimit = $paramFrom * $pageNum;

include("layout/head.php");
include("layout/headDataTables.php"); ?>

<title>EPA Web Search
<?php 
if(!empty($_REQUEST['q']))
{
	echo ' - '. $_REQUEST['q']; 
}
?>

</title>

</head>

<body>

<div class="wrapper">

<?php 
include __DIR__ . "/searchNavSearchBar.php";
?>

<div class="container">
	

<?php

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/searchWebSOMain.php";
}

?>


</div><!-- ./container -->

<?php 
include("layout/footer.php");
?>