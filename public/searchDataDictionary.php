<?php

require __DIR__ . '/../vendor/autoload.php';

//use RecipeSearch\Constants;

// Get search results from Elasticsearch if the user searched for something
$results = [];
if (!empty($_REQUEST['submitted'])) {

    // Connect to Elasticsearch (1-node cluster)
    $esPort = getenv('APP_ES_PORT') ?: 9200;
    $client = new Elasticsearch\Client([
        'hosts' => [ 'localhost:' . $esPort ]
    ]);

    // Setup search query
	$param['index'] = "epametadatatest"; // which index to search
    //$param['type']  = Constants::ES_TYPE;  // which type within the index to search
    //$param['body']['query']['match']['_all'] = $_REQUEST['q']; // what to search for
    $param['body'] = array(
		'query' => array(
			'match' => array(
				'_all' => $_REQUEST['q']
        )
    ),
    'highlight' => array(
        'fields' => array(
            '_all' => new \stdClass() 
        )
    )
);
    //$param = $_REQUEST['q']; // what to search for

    // Send search query to Elasticsearch and get results
    $result = $client->search($param);
    $results = $result['hits']['hits'];
    //$results = $client->search($param);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Data Dictionary Search - EPA Data Catalogue</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--<link rel="stylesheet" href="/css/bootstrap.min.css" />-->
  <!--<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>-->
  <!--<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js"></script>-->
  <!--<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css">-->
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>-->
  <!--<script src="//code.jquery.com/jquery-1.10.2.js"></script>-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js"></script>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
  <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Open+Sans'>
  <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Droid+Sans'>
  <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=IM+Fell+English:400,400italic|Product+Sans'>
  <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=PT+Sans'>
  <script> 
  $(function(){
    $("#header").load("header.html"); 
    $("#footernav").load("footernav.html"); 
    $("#footercopyright").load("footercopyright.html"); 
  });
  </script> 
  <!--<style>body {font-family: 'Product Sans', sans-serif;}</style>-->
  <!--<style>body {font-family: 'Open Sans', sans-serif;}</style>-->
  <!--<style>body {font-family: 'Droid Sans', sans-serif;}</style>-->
  <!--<style>body {font-family: 'PT Sans', sans-serif;}</style>-->
</head>
<body>
<div class="container">
  <!--<div class="jumbotron">-->
  <!--<div class="page-header">-->
  <div class="well">
    <h2>EPA Data Catalogue <small>Data Dictionary Search</small></h2>
	  <ul class="nav nav-tabs">		
        <li><a href="/index.php">All</a></li>		
        <li><a href="/searchfileshare.php">Network Files</a></li>
        <li><a href="/searchopendata.php">Open Data</a></li>
        <li class="active"><a href="#">Data Dictionaries</a></li>
        <li><a href="/searchtest.php">Test</a></li>
      </ul>
  </div>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-inline">
	<input name="q" value="<?php echo $_REQUEST['q']; ?>" type="text" placeholder="Search data dictionaries..." class="form-control input-md" size="40" />
	<input type="hidden" name="submitted" value="true" />
	<input type="submit" value="Search" class="btn btn-default" />
	<!--<span>&nbsp;<a href="/advanced.php">Switch to advanced search</a></span>-->
</form>
<br />
<br />
<span><a href="/advanceddatadictionary.php">Data Dictionary Advanced Search</a></span>
<!--| <span><a href="/index.php">Back to Main Search</a></span>-->
<br />

<?php

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/resultsdatadictionary.php";
}

?>
<div id="footernav"></div>
<div id="footercopyright"></div>
</div>
</body>
</html>
