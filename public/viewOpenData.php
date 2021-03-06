<?php

error_reporting(0);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require __DIR__ . '/../vendor/autoload.php';

//use RecipeSearch\Constants;
use Elasticsearch\Common\Exceptions\Missing404Exception;

$message = $_REQUEST['message'];

// Check if ID was provided
if (empty($_REQUEST['id'])) {
    $message = 'Nothing requested! Please provide an ID.';
} else {
    // Connect to Elasticsearch (1-node cluster)
    $esPort = getenv('APP_ES_PORT') ?: 9200;
    $client = new Elasticsearch\Client([
        'hosts' => [ 'localhost:' . $esPort ]
    ]);

    // Try to get result from Elasticsearch
    try {
        $searchresult = $client->get([
            'id'    => $_REQUEST['id'],
            'index' => "gismetadatatest",
            'type'  => "rivers", "radon"
        ]);
        $searchresult = $searchresult['_source'];
    } catch (Missing404Exception $e) {
        $message = 'Requested record not found';
    }
}
?>
<html>
<head>
  <title>EPA Open Data Search - Results</title>
  <!--<link rel="stylesheet" href="/css/bootstrap.min.css" />-->
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
</head>
<body>
<div class="container bg-danger" id="message">
<?php
if (!empty($message)) {
?>
<h1><?php echo $message; ?></h1>
<?php
}
?>
</div>

<?php
if (!empty($searchresult['file']['_name'])) {
?>
<div class="container">
  <h3><?php echo $searchresult['file']['_name']; ?></h3>
  <br />
</div>
<?php
}
?>

<?php
if (!empty($searchresult['databasename'])) {
?>
<div class="container">
  <h3><?php echo $searchresult['databasename']; ?></h3>
  <br />
</div>
<?php
}
?>

<?php
if (!empty($searchresult['title'])) {
?>
<div class="container">
  <h3><?php echo $searchresult['title']; ?></h3>
  <br />
</div>
<?php
}
?>

<?php
if (!empty($searchresult['columnname'])) {
?>
<div class="container">
  <p><b>Column Name: </b><?php echo $searchresult['columnname']; ?></p>
</div>
<?php
}
?>

<?php
if (!empty($searchresult['file']['_content_type'])) {
?>
<div class="container">
  <p><b>Type: </b><?php echo $searchresult['file']['_content_type']; ?></p>
</div>
<?php
}
?>

<?php
if (!empty($searchresult['lastModified'])) {
?>
<div class="container">
  <p><b>Last Modified: </b><?php echo $searchresult['lastModified']; ?></p>
</div>
<?php
}
?>

<?php
if (!empty($searchresult['file']['_content'])) {
?>
<div class="container">
  <p><b>Content: </b><?php echo $searchresult['file']['_content']; ?></p>
</div>
<?php
}
?>

<?php
if (!empty($searchresult['description'])) {
?>
<div class="container">
  <p><b>Description: </b><?php echo $searchresult['description']; ?></p>
</div>
<?php
}
?>

<?php
if (!empty($searchresult['subject'])) {
?>
<div class="container">
  <p><b>Keywords: </b>
   <?php foreach($searchresult['subject'] as $row) {
    echo $row, ', ';
}?>
  </p>
  
</div>
<?php
}
?>

<?php
if (!empty($searchresult['rights'])) {
?>
<div class="container">
  <p><b>Rights: </b>

  <?php 
	$regex = "((https?|ftp)\:\/\/)?"; // SCHEME 
    $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass 
    $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP 
    $regex .= "(\:[0-9]{2,5})?"; // Port 
    $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path 
    $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query 
    $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor 

    if(preg_match("/^$regex$/", $searchresult['rights'])) 
    { ?>
      <a href="<?php echo $searchresult['rights']; ?>"><?php echo $searchresult['rights']; ?></a>
  <?php
  }
  else
    echo $searchresult['rights']; ?>
  </p>
</div>
<?php
}
?>

<?php
if (!empty($searchresult['coverage'])) {
?>
<div class="container">
  <p><b>Coverage: </b><?php echo $searchresult['coverage']; ?></p>
</div>
<?php
}
?>

<?php
if (!empty($searchresult['@schemaLocation'])) {
?>
<div class="container">
  <p><b>Schema Location: </b>
    <?php 
	$schemaString = $searchresult['@schemaLocation'];
	$schemaArray = explode(" ", $schemaString);	
	
	foreach($schemaArray as $schemaArrayItem)
	{  
?> 
      <a href="<?php echo $schemaArrayItem; ?>"><?php echo $schemaArrayItem; ?></a> 
<?php
}
?>
</div>
<?php
}
?>

<?php
if (!empty($searchresult['identifier'])) {
?>
<div class="container">
  <p><b>Identifier: </b><?php echo $searchresult['identifier']; ?></p>
</div>
<?php
}
?>

<?php
if (!empty($searchresult['type'])) {
?>
<div class="container">
  <p><b>Type: </b><?php echo $searchresult['type']; ?></p>
</div>
<?php
}
?>

<?php
if (!empty($searchresult['format'])) {
?>
<div class="container">
  <p><b>Format: </b><?php echo $searchresult['format']; ?></p>
</div>
<?php
}
?>


<?php
if (!empty($searchresult['Creation-Date'])) {
?>
<div class="container">
  <p><b>Date: </b><?php echo $searchresult['Creation-Date']; ?></p>
</div>
<?php
}
?>


<div class="container">	
  <br />
  <p>
    <a href="/index.php">Link to source file (define later)</a>
  </p>
  <p>
    <span><a href="javascript:history.back()">Back to results</a></span>
  </p>
</div>



</body>
</html>
