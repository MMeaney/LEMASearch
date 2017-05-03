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
include("layout/headFonts.php");
include("layout/headDataTables.php");
include("layout/headScripts.php"); ?>

<title>EPA Data Catalogue Search <?php echo $searchText; ?></title>
</head>

<body>
<div class="wrapper">

<?php 
include __DIR__ . "/searchNavSearchBar.php";
?>

<div class="container">
<nav class="navbar navbar-default" role="navigation">
<ul class="nav nav-pills" role="tablist">

<!-- 
<li class="active"><a data-toggle="tab" href="#searchMain"
All
<span class="badge">

<?php /*echo $resultsCountMainTest;*/ ?></span></a>
</li>
 -->


<li><a data-toggle="pill" href="#searchMainTest">
Test
<?php if($resultscount > 0){ ?>
<span class="badge"><?php echo $resultscount; ?></span>
<?php } ?>
</a>
</li>

<li><a data-toggle="pill" href="#searchFileshare">
Network Files
<?php if($resultscountfileshare > 0){ ?>
<span class="badge"><?php echo $resultscountfileshare; ?></span>
<?php } ?>
</a>
</li>

<li><a data-toggle="pill" href="#searchEPAWeb">
EPA.ie
<?php if($resultsCountEPAWeb > 0){ ?>
<span class="badge"><?php echo $resultsCountEPAWeb; ?></span>
<?php } ?>
</a>
</li>

<li><a data-toggle="pill" href="#searchPeople">
People
<?php if($resultsCountPeople > 0){ ?>
<span class="badge"><?php echo $resultsCountPeople; ?></span>
<?php } ?>
</a>
</li>

<li><a data-toggle="pill" href="#searchOpenData">
Open Data
<?php if($resultscountopendata > 0){ ?>
<span class="badge"><?php echo $resultscountopendata; ?></span>
<?php } ?>
</a>
</li>

<!-- 
<li><a data-toggle="pill" href="#searchDataDictionary">
Data Dictionaries
<?php /* if($resultscountdatadictionary > 0){ ?>
<span class="badge"><?php echo $resultscountdatadictionary; ?></span>
<?php } */?>
</a>
</li>
 -->

<li><a data-toggle="pill" href="#CRMDataDictionary">
CRM Data Dict
<?php if($resultsCRMDataDictionary > 0){ ?>
<span class="badge"><?php echo $resultsCountCRMDataDictionary; ?></span>
<?php } ?>
</a>
</li>


<!-- 

<li><a data-toggle="pill" href="#LEAP">
LEAP
<?php /* if($resultsLEAPLicence > 0){ ?>
<span class="badge"><?php echo $resultsCountLEAPLicence; ?></span>
<?php } */?>
</a>
</li>
 -->

<!-- 
<li><a data-toggle="pill" href="#searchResearch">
Research
<?php /*if($resultsCountResearch>0){ ?>
<span class="badge">
<?php echo $resultsCountResearch; */?></span>
<?php /*} */?>
</a>
</li>

<!-- 
<li><a href="/searchTest.php"><i class="fa fa-inbox"></i>&nbsp;Test</a></li>
 -->
</ul>
</nav><!-- ./nav-pills -->


<!-----------------------------------------------------------------------------------------------------------
--- *** NAVIGATION TABS *** 
------------------------------------------------------------------------------------------------------------>	
	
<div class="tab-content">

<!------------------------------- *** MAIN SEARCH Tab *** ------------------------------->
<!-- 
<div id="searchMain" class="tab-pane active">

<!-- ************************************************************************************************* -->
<!-- Display MAIN Results PHP File -->
<!-- ************************************************************************************************* -->		

<?php
/*
if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/resultsMainTest.php";
}

*/
?>
<!-- 
<h4>Query JSON</h4>
<pre><code class="language-json">
<?php 
//$json_query_main_print = json_decode($param['body']);
//echo json_encode($json_query_main_print, JSON_PRETTY_PRINT);
/*
echo json_encode($paramMainTest['body'], JSON_PRETTY_PRINT);
*/
?>
</code></pre>
 -->
 <!-- 
</div>
 -->
<!-- /.searchMainTest -->



<!------------------------------- *** MAIN SEARCH TEST Tab *** -------------------------->

<!-- <div id="searchMainTest" class="tab-pane"> -->
<div id="searchMainTest" class="tab-pane active">

<!-- ************************************************************************************************* -->
<!-- Display MAIN TEST Results PHP File -->
<!-- ************************************************************************************************* -->		


<div class="divFlexLeftRight">

<div class="divLeftFlex">

<?php

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/resultsMain.php";
}

?>
</div><!-- ./divLeftFlex -->

<div class="divRightSnippet">

<?php

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/snippetsMain.php";
}

?>

</div><!-- ./divRightSnippet -->
				
</div><!-- ./divFlexLeftRight -->


<h4>Query JSON</h4>
<pre><code class="language-json">
<?php 
//$json_query_main_print = json_decode($param['body']);
//echo json_encode($json_query_main_print, JSON_PRETTY_PRINT);

echo json_encode($param['body'], JSON_PRETTY_PRINT);
?>
</code></pre>

</div><!-- /.searchMain -->


<!------------------------------- *** NETWORK FILE SHARE Tab *** ------------------------>

<div id="searchFileshare" class="tab-pane">
	
<div id="wrapper">

<div class="divcontainleftright">

<div id="sidebar-wrapper">

<?php

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/refineFileshare.php";
}
?>

</div><!-- /#sidebar-wrapper -->

<!-- ************************************************************************************************* -->
<!-- Display FILESHARE Results PHP File -->
<!-- ************************************************************************************************* -->		

<div id="page-content-wrapper">

<?php

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/resultsFileshare.php";
}

?>

</div><!-- /#page-content-wrapper -->
				
</div><!-- ./divcontainleftright -->

</div><!-- /#wrapper -->

<!-- Menu Toggle Script -->
<script>
$("#menu-toggle").click(function(e) {
	e.preventDefault();
	$("#wrapper").toggleClass("toggled", 10000);
});
</script>

</div><!-- /.searchFileshare -->

<!------------------------------- *** EPA Web Tab *** ----------------------------->

<div id="searchEPAWeb" class="tab-pane">

<!-- ************************************************************************************************* -->
<!-- Display EPA WEB Results PHP File -->
<!-- ************************************************************************************************* -->		

<?php

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/resultsEPAWeb.php";
}

?>
</div><!-- ./searchEPAWeb -->



<!------------------------------- *** PEOPLE SEARCH Tab *** ----------------------------->

<div id="searchPeople" class="tab-pane">

<!-- ************************************************************************************************* -->
<!-- Display PEOPLE Results PHP File -->
<!-- ************************************************************************************************* -->		

<?php

function get_title($url){
  $str = file_get_contents($url);
  if(strlen($str)>0){
    $str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
    preg_match("/\<title\>(.*)\<\/title\>/i",$str,$title); // ignore case
    return $title[1];
  }
}

echo get_title("http://extranet.edenireland.ie/Person.aspx?accountname=EPA%5Cmeaneym");

$doc = new DOMDocument();
@$doc->loadHTMLFile('http://epamysites/Person.aspx?accountname=EPA%5Cmeaneym');
$xpath = new DOMXPath($doc);
echo $xpath->query('//title')->item(0)->nodeValue."\n";

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/resultsPeople.php";
}

?>

</div><!-- ./searchPeople -->

<!------------------------------- *** RESEARCH SEARCH Tab *** ----------------------------->
<!-- 
<div id="searchResearch" class="tab-pane">
 -->
<!-- ************************************************************************************************* -->
<!-- Display PEOPLE Results PHP File -->
<!-- ************************************************************************************************* -->		

<?php
/*
if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/resultsResearch.php";
}
//*/
?>
<!-- 
</div>
 -->
<!-- ./searchResearch -->

<!------------------------------- *** LEAP Tab *** ----------------------------->

<!-- 
<div id="LEAP" class="tab-pane">
 -->
<!-- ************************************************************************************************* -->
<!-- Display LEAP Results PHP File -->
<!-- ************************************************************************************************* -->		

<?php
/*
if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/leapMainCat.php";
}
*/
?>
<!--  
</div>
-->
<!-- ./LEAP -->

<!------------------------------- *** OPEN DATA Tab *** --------------------------------->

<div id="searchOpenData" class="tab-pane">
<?php
if (count($resultsopendata) > 0) {
?>
<table class="table table-hover table-striped table-responsive">
<thead>
<tr>
	<th>Title</th>
	<th>Description</th>
	<th>Format</th>
	<th>Type</th>
	<th>Date</th>
</tr>
</thead>
<?php
	foreach ($resultsopendata as $result) {
		$searchresult = $result['_source'];
?>
<tr>
	<td><a href="/viewOpenData.php?id=<?php echo $result['_id']; ?>">
		<?php echo $searchresult['title']; ?>
		<img src="<?php echo $searchresult['identificationInfo']['MD_DataIdentification']['graphicOverview']['MD_BrowseGraphic']['fileName']['CharacterString']; ?>">
		</a>
	</td>
	<td>
		<?php echo $searchresult['description']; ?>
		<?php echo $searchresult['identificationInfo']['MD_DataIdentification']['abstract']['CharacterString']; ?><br />
		<?php echo $searchresult['identificationInfo']['MD_DataIdentification']['purpose']['CharacterString']; ?>
	</td>
	<td>
		<?php echo $searchresult['format']; ?>
		<?php echo $searchresult['distributionInfo']['MD_Distribution']['distributionFormat']['MD_Format']['name']['CharacterString']; ?>
		<?php echo $searchresult['distributionInfo']['MD_Distribution']['distributionFormat']['MD_Format']['version']['CharacterString']; ?>
	</td>
	<td>
		<?php echo $searchresult['type']; ?>
		<?php echo $searchresult['metadataStandardName']['CharacterString']?>
		<?php echo $searchresult['metadataStandardVersion']['CharacterString']?>
	</td>
	<td>
		<?php echo $searchresult['Creation-Date']; ?>
		<?php echo $searchresult['dateStamp']['DateTime']; ?>
	</td>
</tr>
<?php
	} // END foreach loop over results
?>
</table>
<?php
} // END if there are search results
else {
?>
	<div class="well">Sorry, nothing found</div><!-- ./well -->
<?php
} // END elseif there are no search results
?>
</div><!-- /.searchOpenData -->


<!-- ************************************************************************************************* -->
<!-- CRM DATA DICTIONARY -->
<!-- ************************************************************************************************* -->

<div id="CRMDataDictionary" class="tab-pane">

<?php

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/crmDataDictionary.php";
}

?>

</div><!-- /.CRMDataDictionary -->

<!------------------------------- *** DATA DICTIONARIES Tab *** ------------------------->
<!-- 
<div id="searchDataDictionary" class="tab-pane">

<br />
<span><a href="/advancedDataDictionary.php">Advanced Search</a></span>
<br />
<br />

<?php
/*
if (count($resultsdatadictionary) > 0) {


?>
<table class="table table-hover table-striped table-responsive">
<thead>
<tr>
	<th>Database</th>
	<th>Table</th>
	<th>Column</th>
	<th>Column Description</th>
	<th>Data Type</th>
	<th>Size</th>
	<th>System</th>
	<th>Server</th>
</tr>
</thead>
<?php
	foreach ($resultsdatadictionary as $result) {
		$searchresult = $result['_source'];
?>
<tr>
	<td><a href="/viewdatadictionary.php?id=<?php echo $result['_id']; ?>">
		<?php echo $searchresult['databasename']; ?></a></td>
	<td><?php echo $searchresult['title']; ?></td>
	<td><?php echo $searchresult['columnname']; ?></td>
	<td><?php echo $searchresult['description']; ?></td>
	<td><?php echo $searchresult['datatype']; ?></td>
	<td><?php echo $searchresult['size']; ?></td>
	<td><?php echo $searchresult['system']; ?></td>
	<td><?php echo $searchresult['servername']; ?></td>
</tr>
<?php
	} // END foreach loop over results
?>
</table>
<?php
} // END if there are search results
else {
?>
	<div class="well">Sorry, nothing found</div><!-- ./well -->
<?php
} */// END elseif there are no search results
?>
<!--</div><!-- /.searchDataDictionary -->


</div><!-- ./tab-content -->


</div><!-- ./container -->

<?php 
include("layout/footer.php");
?>