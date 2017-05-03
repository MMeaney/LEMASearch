<?php

error_reporting(0);

require __DIR__ . '/../vendor/autoload.php';

//use RecipeSearch\Constants;
//use RecipeSearch\Util;

// Get search results from Elasticsearch if the user searched for something
$results = [];
if (!empty($_REQUEST['submitted'])) {

    // Connect to Elasticsearch (1-node cluster)
    $esPort = getenv('APP_ES_PORT') ?: 9200;
    $client = new Elasticsearch\Client([
        'hosts' => [ 'localhost:' . $esPort ]
    ]);

    // Setup search query
    $params['index'] = "epametadatatest"; // which index to search
    //$params['type']  = "datadictionary";  // which type within the index to search
    $params['body'] = [];

    // First, setup full text search bits
    $fullTextClauses = [];

    if ($_REQUEST['databasename']) {
      $fullTextClauses[] = [ 'match' => [ 'databasename' => $_REQUEST['databasename'] ] ];
    }
	
    if ($_REQUEST['title']) {
      $fullTextClauses[] = [ 'match' => [ 'title' => $_REQUEST['title'] ] ];
    }

    if ($_REQUEST['columnname']) {
      $fullTextClauses[] = [ 'match' => [ 'columnname' => $_REQUEST['columnname'] ] ];
    }

    if ($_REQUEST['description']) {
      $fullTextClauses[] = [ 'match' => [ 'description' => $_REQUEST['description'] ] ];
    }

    if ($_REQUEST['datatype']) {
      $fullTextClauses[] = [ 'match' => [ 'datatype' => $_REQUEST['datatype'] ] ];
    }

    if ($_REQUEST['system']) {
      $fullTextClauses[] = [ 'match' => [ 'system' => $_REQUEST['system'] ] ];
    }

    if ($_REQUEST['fkdependencies']) {
      $fullTextClauses[] = [ 'match' => [ 'fkdependencies' => $_REQUEST['fkdependencies'] ] ];
    }

    if ($_REQUEST['servername']) {
      $fullTextClauses[] = [ 'match' => [ 'servername' => $_REQUEST['servername'] ] ];
    }

    if ($_REQUEST['tags']) {
      $tags = Util::recipeTagsToArray($_REQUEST['tags']);
      $fullTextClauses[] = [ 'terms' => [
        'tags' => $tags,
        'minimum_should_match' => count($tags)
      ] ];
    }

    if (count($fullTextClauses) > 0) {
      $query = [ 'bool' => [ 'must' => $fullTextClauses ] ];
    } else {
      $query = [ 'match_all' => (object) [] ];
      //$query = [ 'match' => (object) [] ];
    }

    // Then setup exact match bits
    $filterClauses = [];

    if ($_REQUEST['field_size_low'] || $_REQUEST['field_size_high']) {
      $rangeFilter = [];
      if ($_REQUEST['field_size_low']) {
        $rangeFilter['gte'] = (int) $_REQUEST['field_size_low'];
      }
      if ($_REQUEST['field_size_high']) {
        $rangeFilter['lte'] = (int) $_REQUEST['field_size_high'];
      }
      $filterClauses[] = [ 'range' => [ 'size' => $rangeFilter ] ];
    }

    if ($_REQUEST['servings']) {
      $filterClauses[] = [ 'term' => [ 'servings' => $_REQUEST['servings'] ] ];
    }

    if (count($filterClauses) > 0) {
      $filter = [ 'bool' => [ 'must' => $filterClauses ] ];
    }

    // Build complete search request body
    if (count($filterClauses) > 0) {
      $params['body'] = [ 'query' =>
        [ 'filtered' =>
          [ 'query' => $query, 'filter' => $filter ]
        ]
      ];
    } else {
      $params['body'] = [ 'query' => $query ];
    }

    // Send search query to Elasticsearch and get results
    $result = $client->search($params);
    $results = $result['hits']['hits'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>EPA Data Catalogue - Data Dictionary Search</title>
  <!--<link rel="stylesheet" href="/css/bootstrap.min.css" />-->
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
</head>
<body>
<div class="container">
  <!--<div class="jumbotron">-->
  <!--<div class="page-header">-->
  <div class="well">
    <h2>EPA Data Catalogue <small>Data Dictionary Search - Advanced</small></h2>
  </div>
 
<span style=font-size:10pt><a href="javascript:history.back()">[Back to results]</a></span>
<br />
<br />
 
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

  <!-- Basic information about the data dictionary -->
  <div class="container">
    <div class="form-group">
      <div class="row">
        <div class="col-xs-4">
          <label for="databasename">Database name contains...</label>
          <input name="databasename" value="<?php echo $_REQUEST['databasename']; ?>"  placeholder="Database name" class="form-control" />
        </div>
        <div class="col-xs-3">
          <label for="title">Table name contains...</label>
          <input name="title" value="<?php echo $_REQUEST['title']; ?>"  placeholder="Table name" class="form-control" />
        </div>
        <div class="col-xs-2">
          <label for="datatype">Data type contains...  </label>
          <input name="datatype" value="<?php echo $_REQUEST['datatype']; ?>"  placeholder="Data type" class="form-control"/>
        </div>
        <div class="col-xs-2">
          <label for="field_size_low">Field size is between...</label>
          <input name="field_size_low" value="<?php echo $_REQUEST['field_size_low']; ?>" type="number" placeholder="size" class="form-control"/>
          <label for="field_size_high">and</label>
          <input name="field_size_high" value="<?php echo $_REQUEST['field_size_high']; ?>" type="number" placeholder="size" class="form-control"/>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-xs-4">
          <label for="columnname">Column name contains...  </label>
          <input name="columnname" value="<?php echo $_REQUEST['columnname']; ?>"  placeholder="Column name"  class="form-control"/>
        </div>
        <div class="col-xs-3">
          <label for="description">Column description contains...  </label>
          <input name="description" value="<?php echo $_REQUEST['description']; ?>"  placeholder="Column description" class="form-control"/>
        </div>
        <div class="col-xs-3">
          <label for="servername">Server name contains...</label>
          <input name="servername" value="<?php echo $_REQUEST['servername']; ?>"  placeholder="Server name" class="form-control" />
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="row">
        <div class="col-xs-10">
          <label for="tags">Tags contain...</label>
          <input name="tags" value="<?php echo $_REQUEST['tags']; ?>" placeholder="Comma-separated" class="form-control"/>
        </div>
      </div>
    </div>
	<div class="form-group">
	  <div class="row">
        <div class="col-xs-10">
          <label for="debug">Show request JSON?</label>
          <input type="checkbox" name="debug" value="true"<?php echo ($_REQUEST['debug'] ? " checked" : ""); ?> />
        </div>
      </div>
    </div>
  </div>

  <input type="hidden" name="submitted" value="true" />
  <input type="submit" value="Search" class="btn btn-default" />
  <span>&nbsp;<a href="/index.php">Back to Main Search</a></span>
</form>

<br />
<?php

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/resultsdatadictionary.php";

  // Print out request JSON if debug flag is set
  if ($_REQUEST['debug']) {
?>


<h3>Request JSON</h3>
<pre>
<?php echo json_encode($params['body'], JSON_PRETTY_PRINT); ?>
</pre>
<?php
  }
}

?>
</div>
</body>
</html>
