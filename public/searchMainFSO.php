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
//include __DIR__ . "/defineQuery.php";	
	
$fullTextClauses = [];
$fullTextClauses[] = 

/*
[
		'bool' => [
			'should' => [
				'match' => [
					'_all' => [
						'query' => $_REQUEST['q']
							//, 'fuzziness' => '1'
							//, 'prefix_length' =>  1
					]
				]
			]
		]
	
];
//*/

///*
	[

	//'search_type' => 'scan',    // use search_type=scan
	//'scroll' => '30s',          // how long between scroll requests. should be small!
	//'size' => 50,
	
	///*
	'multi_match' => [
		'query' => $_REQUEST['q'],
		'type' => 'cross_fields', // Possible values 'best_fields', 'most_fields', 'phrase', 'prase_prefix', 'cross_fields'
		'fields' => [ 'file.filename^99'
					, 'file.url^50'
					, 'meta.author'
					//, 'path.real'
					, 'file.content_type'
					, 'meta.raw.Application-Name'
					, 'content'
				
		],
		//'fuzziness' => '1',
		//'prefix_length' => 2,
		'operator' => 'and'//'or'
		]
			
	//*/	
	];

//*/

/*
    if ($_REQUEST['title']) {
      $fullTextClauses[] = [ 'match' => [ 'title' => $_REQUEST['title'] ] ];
    }

    if ($_REQUEST['description']) {
      $fullTextClauses[] = [ 'match' => [ 'description' => $_REQUEST['description'] ] ];
    }

    if ($_REQUEST['ingredients']) {
      $fullTextClauses[] = [ 'match' => [ 'ingredients' => $_REQUEST['ingredients'] ] ];
    }

    if ($_REQUEST['directions']) {
      $fullTextClauses[] = [ 'match' => [ 'directions' => $_REQUEST['directions'] ] ];
    }

    if ($_REQUEST['tags']) {
      $tags = Util::recipeTagsToArray($_REQUEST['tags']);
      $fullTextClauses[] = [ 'terms' => [
        'tags' => $tags,
        'minimum_should_match' => count($tags)
      ] ];
    }
*/

    if (count($fullTextClauses) > 0) {
      $query = [ 'bool' => [ 'must' => $fullTextClauses ] ];
    } else {
      $query = [ 'match_all' => (object) [] ];
    }

    // Then setup exact match bits
    $filterClauses = [];
/*
    if ($_REQUEST['prep_time_min_low'] || $_REQUEST['prep_time_min_high']) {
      $rangeFilter = [];
      if ($_REQUEST['prep_time_min_low']) {
        $rangeFilter['gte'] = (int) $_REQUEST['prep_time_min_low'];
      }
      if ($_REQUEST['prep_time_min_high']) {
        $rangeFilter['lte'] = (int) $_REQUEST['prep_time_min_high'];
      }
      $filterClauses[] = [ 'range' => [ 'prep_time_min' => $rangeFilter ] ];
    }
*/
    if ($_REQUEST['FileSizeLow'] || $_REQUEST['FileSizeHigh']) {
      $rangeFilter = [];
      if ($_REQUEST['FileSizeLow']) {
        $rangeFilter['gte'] = (int) $_REQUEST['FileSizeLow'];
      }
      if ($_REQUEST['FileSizeHigh']) {
        $rangeFilter['lte'] = (int) $_REQUEST['FileSizeHigh'];
      }
      $filterClauses[] = [ 'range' => [ 'file.filesize' => $rangeFilter ] ];
    }
    
    if ($_REQUEST['FileCreateFrom'] || $_REQUEST['FileCreateTo']) {
      $rangeFilter = [];
      if ($_REQUEST['FileCreateFrom']) {
        $rangeFilter['gte'] = $_REQUEST['FileCreateFrom'];
      }
      if ($_REQUEST['FileCreateTo']) {
        $rangeFilter['lte'] = $_REQUEST['FileCreateTo'];
      }
      $filterClauses[] = [ 'range' => [ 'meta.raw.meta:creation-date' => $rangeFilter ] ];
    }
    
    //if ($_REQUEST['From']) {
    //  $fromPagination = [ 'From' => $_REQUEST['From'] ];
    //}
    
    if ($_REQUEST['Author']) {
      $filterClauses[] = [ 'term' => [ 'meta.author.raw' => $_REQUEST['Author'] ] ];
    }

    if ($_REQUEST['Application']) {
      $filterClauses[] = [ 'term' => [ 'meta.raw.Application-Name.raw' => $_REQUEST['Application'] ] ];
    }

    if ($_REQUEST['FileType']) {
      //$filterClauses[] = [ 'term' => [ 'file.filename' => $_REQUEST['FileType'] ] ];
      $filterClauses[] = [ 'term' => [ 'file.content_type.raw' => $_REQUEST['FileType'] ] ];
    }
    
   
    if ($_REQUEST['MatchPhrase']) {
    	$MatchPhraseClauses[] = [ 'content' => $_REQUEST['MatchPhrase'] ];
    }

    if ($_REQUEST['pageFrom']) {
    	//$filterClauses[] = [ 'term' => [ 'file.filename' => $_REQUEST['FileType'] ] ];
    	$FromClauses[] = [ 'from' =>  $_REQUEST['pageFrom'] ];
    }


    if (count($filterClauses) > 0) {
    	$filter = [ 'bool' => [ 'must' => $filterClauses ] ];
    }
    
    if (count($FromClauses) > 0) {
    	$from = [ 'bool' => [ 'must' => $FromClauses ] ];
    }
    
    if (count($MatchPhraseClauses) > 0) {
      	$MatchPhrase = [ $MatchPhraseClauses ];
    }

    // Build complete search request body
    if (count($filterClauses) > 0) {
		$paramfileshare['body'] = 
		[ 'query' =>
			[ 'filtered' =>
				[
					'query' => $query
					, 'filter' => $filter
					, 'from' => 10
					//, 'match_phrase' => ['content' => 'to whom it may concern'] //$MatchPhrase  
				]
    		]
		];
    } else {
		$paramfileshare['body'] = [ 
			'from' => 20, 'size' => 20,
			'query' => $query 
		];
    }



/*
$json_query_fileshare = '
{
	"query": {
		"multi_match": {
			"query": "'.$_REQUEST['q'].'",
			"fields": ["file.filename", "file.url"],
			"type": "best_fields"
		}
	},

	"aggs": {
		"agg_author": {
			"terms": {
				"field": "meta.author",
				"size": 0
			}
		}
	},
	"highlight": {
		"fields": {
			"_all": {}
		}
	}
}';
//*/

	
////get the search query
////$query = $_REQUEST['q'];
	
////get filter of agg/facet
$aggFilterValue = $_GET['agg'];
$aggFilterField = $_GET['agg_field'];

	
////$paramfileshare['body']['query']['filtered']['query']['match']['title'] = $query;
	
if ($aggFilterValue) {
	$paramfileshare['body']['query']['filtered']['filter']['term'][$aggFilterField] = $aggFilterValue;
}



$paramfileshare['body']['aggregations']['aggAuthor']['terms']['field'] = "meta.author.raw";
$paramfileshare['body']['aggregations']['aggAuthor']['terms']['size'] = 0;
$paramfileshare['body']['aggregations']['aggAuthor']['terms']['missing'] = "(Blank)";
//$paramfileshare['body']['aggregations']['aggAuthor']['terms']['order']['_term'] = "asc";
//$paramfileshare['body']['aggregations']['aggAuthor']['terms']['min_doc_count'] = 0;


$paramfileshare['body']['aggregations']['aggApplication']['terms']['field'] = "meta.raw.Application-Name.raw";
$paramfileshare['body']['aggregations']['aggApplication']['terms']['size'] = 0;
$paramfileshare['body']['aggregations']['aggApplication']['terms']['missing'] = "(Blank)";
//$paramfileshare['body']['aggregations']['aggApplication']['terms']['order']['_term'] = "asc";


/*
//$paramfileshare['body']['aggregations']['aggFileType']['terms']['field'] = "file.filename";
$paramfileshare['body']['aggregations']['aggFileType']['terms']['script'] = "doc['file.filename'].getValue().substring(0,3)";
$paramfileshare['body']['aggregations']['aggFileType']['terms']['size'] = 0;
//$paramfileshare['body']['aggregations']['aggFileType']['terms']['order']['_count'] = "asc";
//$paramfileshare['body']['aggregations']['aggFileType']['terms']['script'] = "doc['file.filename'].getValue().substring(0, lastIndexOf('.'))";
*/

//"script": "doc['my_field'].getValue().substring(0,6)",
//$filetype = substr($filetypestring, strrpos($filetypestring, ".") + 1);
//names.substring(0, lastIndexOf('.'))


$paramfileshare['body']['aggregations']['aggContentType']['terms']['field'] = "file.content_type.raw";
$paramfileshare['body']['aggregations']['aggContentType']['terms']['size'] = 0;
$paramfileshare['body']['aggregations']['aggContentType']['terms']['missing'] = "";
//$paramfileshare['body']['aggregations']['aggContentType']['terms']['order']['_term'] = "asc";



$resultfileshare = $client->search($paramfileshare);
$resultsfileshare = $resultfileshare['hits']['hits'];
$resultscountfileshare = $resultfileshare['hits']['total'];
$aggsfileshareauthor = $resultfileshare['aggs']['aggAuthor'];
$aggsFileShareFileType = $resultfileshare['aggs']['aggFileType'];
$aggsFileShareContentType = $resultfileshare['aggs']['aggContentType'];

////get filter of agg/facet
$aggFilterValue = $_GET['agg'];
$aggFilterField = $_GET['agg_field'];

$aggAuthor 		= $resultfileshare['aggregations']['aggAuthor']['buckets'];
$aggFileType 	= $resultfileshare['aggregations']['aggFileType']['buckets'];
$aggContentType	= $resultfileshare['aggregations']['aggContentType']['buckets'];
$aggApplication = $resultfileshare['aggregations']['aggApplication']['buckets'];


$aggAuthorCount  	 = count($aggAuthor);
$aggFileTypeCount 	 = count($aggFileType);
$aggContentTypeCount = count($aggContentType);
$aggApplicationCount = count($aggApplication);
	
	
	
include __DIR__ . "/definePagination.php";
	
}
	
$searchText = $_REQUEST['q']; 

//$paramSize = $param['size'];
//$paramFrom = $param['from'];
$paramLimit = $paramFrom * $pageNum;

include("layout/head.php");
include("layout/headDataTables.php");
include("layout/headScripts.php"); ?>

<title>EPA Data Catalogue Search <?php echo $searchText; ?></title>
</head>

<body>
<div class="wrapper">
<nav class="navbar navbar-default navbar-static-top">

<div class="container-fluid">
<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a href="/index.php" class="navbar-left">
			<img src=".\img\epa-logo-small-trns.gif" alt="Environmental Protection Agency (EPA) Ireland" 
				style="width:83px;height:50px;">
		</a><!-- ./navbar-left img -->	
		
	</div><!-- ./navbar-header -->
		
		
	<div class="navbar-header pull-left">
		<div class="col-sm-8 col-md-8">
		<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="navbar-form navbar-center" role="search" id="formSearchDataCatalogue">
			
			<div class="input-group">
				<div class="form-group has-feedback has-clear">
					<input id="inputSearchString" name="q" value="<?php echo $_REQUEST['q']; ?>" type="text" placeholder="What would you like to search for?" class="form-control input-md" size="50" autofocus onfocus="this.value = this.value;" />
					<span class="form-control-clear glyphicon glyphicon-remove-circle form-control-feedback hidden" id="searchClear"></span>
				</div><!-- ./form-group -->
				<input type="hidden" name="submitted" value="true" />
					<span class="input-group-btn">
					<button class="btn btn-primary btn-md" type="submit">
						<i class="glyphicon glyphicon-search"></i> <!-- Bootstrap Search Glyphicon -->
						<!-- <i class="fa fa-search-plus" aria-hidden="true"></i> --> <!-- Font Awesome Search Plus Icon -->
						<!-- <i class="fa fa-search" aria-hidden="true"></i> --> <!-- Font Awesome Search Icon -->
					</button>
					</span><!-- ./input-group-btn -->
			</div><!-- ./input-group -->	
			
			<!-- &nbsp; &nbsp; -->
			<!-- 
				<span id="show" class="btn btn-info btn-xs">+ Expand All Results</span>
				<span id="expand_delimiter">|</span>
				<span id="hide" class="btn btn-info btn-xs">- Collapse All Results</span>
			 -->
			
				<!-- <span id="show" class="expand">+ Expand all</span> -->
				<!-- <span id="expand_delimiter" class="expand">|</span> -->
				<!-- <span id="hide" class="expand">- Collapse all</span> -->	
			<!-- </form> -->
		</div><!-- ./col-sm-8 col-md-8 -->
	</div><!-- ./navbar-header pull-left -->
	
	<div id="navbar" class="navbar-collapse collapse">
		<ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul><!-- ./dropdown-menu -->
            </li><!-- ./dropdown -->
            <li class="active"><a href="./">Fixed top <span class="sr-only">(current)</span></a></li>
            <li>
				<ul class="nav pull-left">
	            <li class="nav navbar-text" id="navSignedInUser">User Name</li>	            
		            <li class="dropdown pull-right">
						<a href="#" data-toggle="dropdown" style="color:#777; margin-top: 5px;" class="dropdown-toggle">
							<span class="glyphicon glyphicon-user"></span>
							<b class="caret"></b>
						</a>
					<ul class="dropdown-menu">
						<li><a href="/users/id" title="Profile">Profile</a></li>
		            	<li><a href="/logout" title="Logout">Logout</a></li>
					</ul><!-- ./dropdown-menu -->
					</li><!-- ./dropdown pull-right -->
				</ul><!-- ./nav pull-left -->
			</li>
		</ul><!-- ./navbar-right -->
	</div><!--/.nav-collapse -->		

</div><!-- /.container-fluid -->
</nav>

<div class="container">
<nav class="navbar navbar-default" role="navigation">
<ul class="nav nav-pills" role="tablist">

<li class="active"><a data-toggle="tab" href="#searchFileshare">
Network Files
<?php if($resultscountfileshare > 0){ ?>
<span class="badge"><?php echo $resultscountfileshare; ?></span>
<?php } ?>
</a>
</li>

</ul>
</nav><!-- ./nav-pills -->


<!-----------------------------------------------------------------------------------------------------------
--- *** NAVIGATION TABS *** 
------------------------------------------------------------------------------------------------------------>	
	
<div class="tab-content">

<!------------------------------- *** MAIN SEARCH Tab *** ------------------------------->

<div id="searchMain" class="tab-pane active">




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
	$("#wrapper").toggleClass("toggled");
});
</script>

</div><!-- /.searchFileshare -->



</div><!-- ./tab-content -->

</div><!-- ./container -->
</div><!-- ./container -->


<!-----------------------------------------------------------------------------------------------------------
--- *** SCRIPTS *** 
------------------------------------------------------------------------------------------------------------>	
		
<!-- Clear search box -->

<script type="text/javascript">
$('.has-clear input[type="text"]').on('input propertychange', function() {
  var $this = $(this);
  var visible = Boolean($this.val());
  $this.siblings('.form-control-clear').toggleClass('hidden', !visible);
}).trigger('propertychange');

$('.form-control-clear').click(function() {
  $(this).siblings('input[type="text"]').val('')
    .trigger('propertychange').focus();
});
</script>


<!-- IE - search box - prevent cursor jumping to beginning of text -->

<script type="text/javascript">
var el = $("#inputSearchString");
el[0].onfocus = el[0].onblur = null;
$(el).on("focus blur", function(e) {
    this.value = $.trim(this.value);    
    if ((e.type === "focus") && this.createTextRange) {
        var r = this.createTextRange();
        r.moveStart("character", this.value.length);
        r.select();
    }
});
</script>


<!-- Search Box Autocomplete (powered by Wikipedia) -->

<script type="text/javascript">
 $("#inputSearchString").autocomplete({ 
	source: function(request, response) { 
		$.ajax({ 
			url: "http://en.wikipedia.org/w/api.php", 
			dataType: "jsonp",
			data: { 
				'action': "opensearch", 
				'format': "json", 
				'search': request.term  
			}, 

			success: function(data) { 
				response(data[1]); 
			} 
		}); 
	} 
}); 
</script>




<div class="push"></div>
</div><!-- ./wrapper-->
<div id="footerCopyright"></div>
</body>
</html>