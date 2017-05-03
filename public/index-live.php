<?php

error_reporting(0);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require __DIR__ . '/../vendor/autoload.php';
use Search\Constants;

// Get search results from Elasticsearch if the user searched for something
$results = [];
if (!empty($_REQUEST['submitted'])) {
	
	include __DIR__ . "/defineClient.php";	
	include __DIR__ . "/defineIndex.php";

	//$param['body']['query']['match']['_all'] = $_REQUEST['q']; // what to search for
    $param['body']  = [
		'query' => [
			'match' => [
				'_all' => $_REQUEST['q']
        ]
    ],
    'highlight' => [
        'fields' => [
            '_all' => new \stdClass() 
			]
		]
	];
	$param['size'] = 10000;
	//$param['from'] = 10;
    //$param = $_REQUEST['q']; // what to search for

    // Send search query to Elasticsearch and get results
    $result = $client->search($param);
    $results = $result['hits']['hits'];
    //$results = $client->search($param);
    
    //$GLOBALS['searched_term'] = $_REQUEST['q'];
}

?>


<?php include("layout/head.php"); ?>
<?php include("layout/headFonts.php"); ?>

<title>EPA Data Catalogue Search</title>
</head>

<body>
<div class="wrapper" id="wrapper-index">
<div class="center">
<br />

<?php include("layout/brandingIndexLogo.php"); ?>
<!-- 
<img src=".\img\epa-logo-small-trns-data-cat.jpg">
 -->
<!--<h3>EPA Data Catalogue</h3>-->

<form method="get" action="searchMain.php" class="form-inline" role="search" autocomplete="on">
  <div class="input-group">
  <div class="form-group has-feedback has-clear">
  
  <input id="inputSearchString" 
  		name="q" 
  		value="<?php echo $_REQUEST['q']; ?>" 
  		type="text" 
  		placeholder="What would you like to search for?" 
  		class="form-control input-md" 
  		size="50" 
  		autofocus 
  		onfocus="this.value = this.value;" />
  		
  <span class="form-control-clear glyphicon glyphicon-remove-circle form-control-feedback hidden" id="searchClear"></span>
  </div><!-- ./form-group -->
    <input type="hidden" name="submitted" value="true" />
    <span class="input-group-btn">
    <button class="btn btn-primary btn-md" type="submit">Search</button>
	</span><!-- ./input-group-btn -->
  </div><!-- ./input-group -->
</form>

<!-- 
<div>
<iframe src="http://webchat.freenode.net?channels=epaireland&uio=d4" width="647" height="400"></iframe>
</div>
 -->
 
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


<!-- Google Analytics -->

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-80866076-2', 'auto');
  ga('require', 'linkid');
  //ga('send', 'pageview');
</script>


</div><!-- ./center-->
<div class="push"></div>
</div><!-- ./wrapper-->
<div id="footerCopyright"></div>
</body>
</html>
