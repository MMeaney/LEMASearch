<?php /*
if (count($resultsLEAPLicence) > 0) { //*/
?>

<nav class="navbar navbar-default" role="navigation">
<ul class="nav nav-pills" role="tablist">

<li class="active"><a data-toggle="pill" href="#webResultsPrint">
Web Results (Table)
<?php if($resultsWeb > 0){ ?>
<span class="badge"><?php echo $resultsCountWeb; ?></span>
<?php } ?>
</a>
</li>


<li><a data-toggle="pill" href="#webResultsView">
Web Results
<?php if($resultsWeb > 0){ ?>
<span class="badge"><?php echo $resultsCountWeb; ?></span>
<?php } ?>
</a>
</li>


</ul>
</nav><!-- ./nav-pills -->
<?php
/*
} // END elsif there are no search results
//*/
?>

<div class="tab-content">

<!-- ************************************************************************************************* -->
<!-- Web Results Print -->
<!-- ************************************************************************************************* -->


<div id="webResultsPrint" class="tab-pane active">

<?php

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/webResultsPrint.php";
}

?>
</div><!-- ./webResultsPrint -->

<!-- ************************************************************************************************* -->
<!-- Web Results View -->
<!-- ************************************************************************************************* -->


<div id="webResultsView" class="tab-pane">

<?php

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/webResultsView.php";
}

?>
</div><!-- ./webResultsView -->

</div><!-- ./tab-content -->




<!-- ************************************************************************************************* -->
<!-- Display Query JSON -->
<!-- ************************************************************************************************* -->

<br />
<div class="panel panel-default">
<div class="panel-heading">Query JSON (Displayed for Testing Only)

<span class="pull-right">
	<button type="button" id="btnFileshareQueryJSONHide" class="btn btn-info btn-xs">Hide</button>
	<button type="button" id="btnFileshareQueryJSONShow" class="btn btn-info btn-xs">Show</button>
</span><!-- ./pull-right -->

</div><!-- ./panel-heading -->

<div id="divFileshareQueryJSONHideShow">
<div class="panel-body" id="divRefinePanelBody">
<div id="divPanelRefineResultsSpacer"></div>
<pre>
<code class="language-json">
<?php 
echo json_encode($paramWeb['body'], JSON_PRETTY_PRINT);
?>
</code></pre>
<div id="divPanelRefineResultsSpacer"></div>
</div><!-- ./panel-body (#divRefinePanelBody) -->
</div><!-- ./divFileshareQueryJSONHideShow --> 

</div><!-- /.panel-default --> 

<!-- ------------------ ./ Display Query JSON ----------------- -->


