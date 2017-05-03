<?php
if (count($resultsfileshare) > 0) {
?>
		
<div class="tags">
<!-- Options to Refine/Filter results -->
<div class="divleftrefine">

<div id="divFileShareFiltersAllHideShow">
	<button type="button" id="btnFileShareFiltersAllHide" class="btn btn-info btn-xs">- Collapse All Filters</button>
	<button type="button" id="btnFileShareFiltersAllShow" class="btn btn-info btn-xs">+ Expand All Filters</button>
</div><!-- ./divFileShareFiltersAllHideShow -->
<br />

<?php if (!empty($_REQUEST['Author']) || !empty($_REQUEST['Application']) || !empty($_REQUEST['From']) || !empty($_REQUEST['To'])) { ?>
<div id="divRefineShowFilters"><span id="spanRefine">Filters:</span><!-- ./spanRefine -->



<?php if (!empty($_REQUEST['Author'])){ ?>
<span class="tag label label-success">
  <span>
	<?php echo $_REQUEST['Author']; ?>
  </span>
<a><i class="remove glyphicon glyphicon-remove-sign glyphicon-white"></i></a>
</span><!-- ./tag label label-info -->
&nbsp;
<?php } // End IF $_REQUEST['Application'] ?>

<?php if (!empty($_REQUEST['Application'])){ ?>
<span class="tag label label-success">
  <span>
	<?php echo $_REQUEST['Application']; ?>
  </span>
<a><i class="remove glyphicon glyphicon-remove-sign glyphicon-white"></i></a>
</span><!-- ./tag label label-info -->
&nbsp;
<?php } // End IF $_REQUEST['Application'] ?>



<?php if (!empty($_REQUEST['From'])){ ?>
<span class="tag label label-info">
  <span>From: 
	<?php echo $_REQUEST['From']; ?>
  </span>
<a><i class="remove glyphicon glyphicon-remove-sign glyphicon-white"></i></a>
</span><!-- ./tag label label-info -->
&nbsp;
<?php } // End IF $_REQUEST['From'] ?>

<?php if (!empty($_REQUEST['To'])){ ?>
<span class="tag label label-info">
  <span>To: 
	<?php echo $_REQUEST['To']; ?>
  </span>
<a><i class="remove glyphicon glyphicon-remove-sign glyphicon-white"></i></a>
</span><!-- ./tag label label-info -->
&nbsp;
<?php } // End IF $_REQUEST['From'] ?>


</div><!-- ./divRefineShowFilters -->
<br /> 
<?php } // End check If $_REQUEST['Author'] 
?>

<!-- -------------------- Div Panel For "Created By" ------------------- -->

<div class="panel panel-default" id="divPanelRefineFileshareAuthor">
<div class="panel-heading" id="divRefinePanelHeading">Created by:

<span class="pull-right">
	<button type="button" id="btnRefineFileshareAuthorHide" class="btn btn-info btn-xs">Hide</button>
	<button type="button" id="btnRefineFileshareAuthorShow" class="btn btn-info btn-xs">Show</button>
	<button type="button" id="btnRefineFileshareAuthorMore" class="btn btn-info btn-xs">More</button>
	<button type="button" id="btnRefineFileshareAuthorLess" class="btn btn-info btn-xs">Less</button>
</span><!-- ./pull-right -->

</div><!-- ./panel-heading -->	

<div id="divRefineFileshareAuthorHideShow">
<div class="panel-body" id="divRefinePanelBody">
<div id="divRefineFileshareAuthor">
<div id="divPanelRefineResultsSpacer"></div>
<?php

for ($i = 0; $i < count($aggAuthor); ++$i) {
	$aggAuthorResult = $aggAuthor[$i];
	echo('<dd>
			<div class="item" id="item-'.$aggAuthorResult['key']. '">
				<input type="checkbox" name="Author"'. $_REQUEST['Author'].' id="'.$aggAuthorResult['key']. '" value="'.$aggAuthorResult['key']. '" />
				<label id="labelRefine" for="'.$aggAuthorResult['key']. '" >'.$aggAuthorResult['key'] . '</label>
				<span id="spanRefine">(' . $aggAuthorResult['doc_count']. ')</span>
			</div><!-- ./item-'.$aggAuthorResult['key']. '-->
			</dd>');

} // End FOR loop check
?>

<div id="divPanelRefineResultsSpacer"></div>
</div><!-- ./divRefineFileshareAuthor -->  
</div><!-- ./panel-body (#divRefinePanelBody) -->


<!--<hr style="margin-bottom:7px !important; margin-top:7px !important; " />-->

<div class="panel-footer" id="divRefinePanelFooter">
<input type="checkbox" class="chkToggleRefineAuthor" id="chkToggleRefineAuthor" value="1" onclick="checkCheckboxes(this.id, 'divRefineFileshareAuthor');" />
	<label id="labelToggleRefineAuthor" for="chkToggleRefineAuthor">
		<span id="spanToggleRefine">(Select All)</span>
	</label>
</div><!-- ./panel-footer -->

</div><!-- ./divRefineFileshareAuthorHideShow -->  
</div><!-- ./panel -->

<!-- -------------------- ./Div Panel For "Created By" ----------------- -->




<!-- -------------------- Div Panel For "File Type" -------------------- -->


<div class="panel panel-info" id="divPanelRefineFileshareFileType">

<div class="panel-heading" id="divRefinePanelHeading">File Type:

<span class="pull-right">
	<button type="button" id="btnRefineFileshareFileTypeHide" class="btn btn-info btn-xs">Hide</button>
	<button type="button" id="btnRefineFileshareFileTypeShow" class="btn btn-info btn-xs">Show</button>
	<button type="button" id="btnRefineFileshareFileTypeMore" class="btn btn-info btn-xs">More</button>
	<button type="button" id="btnRefineFileshareFileTypeLess" class="btn btn-info btn-xs">Less</button>
</span><!-- ./pull-right -->

</div><!-- ./panel-heading -->

<div id="divRefineFileshareFileTypeHideShow">
<div class="panel-body" id="divRefinePanelBody">
<div id="divRefineFileshareFileType">
<div id="divPanelRefineResultsSpacer"></div>

<?php

for ($i = 0; $i < count($aggContentType); ++$i) {
	$aggContentTypeResult = $aggContentType[$i];
	
	//if($aggContentTypeResult['key'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {$displayFileShareMimeType = "Microsoft Office Spreadsheet";}
	

	if (isset($_REQUEST['submitted'])) {
	  include __DIR__ . "/switchLookUp.php";
	}

	
	echo('<dd><input type="checkbox" 
			name="FileType"'. $_REQUEST['FileType'].' 
			id="'.$aggContentTypeResult['key']. '" 
			title="'.$aggContentTypeResult['key']. '" 
			value="'.$aggContentTypeResult['key']. '" />
		<label 
			id="labelRefine" 
			title="'.$aggContentTypeResult['key']. '" 
			for="'.$aggContentTypeResult['key']. '" >'. $displayFileShareMimeType . '
		</label>
		<span id="spanRefine">(' . $aggContentTypeResult['doc_count']. ')</span></dd>');

} // End FOR loop check

/*for ($i = 0; $i < count($aggFileType); ++$i) {
	$aggFileTypeResult = $aggFileType[$i];
	echo('<dd><input type="checkbox" name="FileType"'. $_REQUEST['FileType'].' id="'.$aggFileTypeResult['key']. '" id="'.$aggFileTypeResult['key']. '" value="'.$aggFileTypeResult['key']. '" />
		<label id="labelRefine" for="'.$aggFileTypeResult['key']. '" >'.$aggFileTypeResult['key'] . '</label>
		<span id="spanRefine">(' . $aggFileTypeResult['doc_count']. ')</span></dd>');

}*/ // End FOR loop check
?> 

<div id="divPanelRefineResultsSpacer"></div>
</div><!-- ./divRefineFileshareFileType -->  
</div><!-- ./panel-body (#divRefinePanelBody) --> 

<!--<hr style="margin-bottom:7px !important; margin-top:7px !important; " />-->

<div class="panel-footer" id="divRefinePanelFooterInfo">
<input type="checkbox" class="chkToggleRefineFileType" id="chkToggleRefineFileType" value="1" onclick="checkCheckboxes(this.id, 'divRefineFileshareFileType');" />
	<label id="labelToggleRefineFileType" for="chkToggleRefineFileType">
		<span id="spanToggleRefine">(Select All)</span>
	</label>
</div><!-- ./panel-footer -->

</div><!-- ./divRefineFileshareFileTypeHideShow --> 
</div><!-- ./panel -->

<!-- -------------------- ./Div Panel For "File Type" ------------------- -->


<!-- ---------------------- Div Panel For "File Size Range" ------------------- -->

<div class="panel panel-success" id="divPanelRefineFileshareFileSize">
<div class="panel-heading" id="divRefinePanelHeading">File Size:

<span class="pull-right">
	<button type="button" id="btnRefineFileshareFileSizeHide" class="btn btn-info btn-xs">Hide</button>
	<button type="button" id="btnRefineFileshareFileSizeShow" class="btn btn-info btn-xs">Show</button>
</span><!-- ./pull-right -->

</div><!-- ./panel-heading -->

<div id="divRefineFileshareFileSizeHideShow">
<div class="panel-body" id="divRefinePanelBodyNarrowLeftRight">
<div id="divRefineFileshareFileCreated">
<div id="divPanelRefineResultsSpacer"></div>

<div class="input-group" id="datepicker">
    <input 
    	type="number"
    	class="input-sm form-control" 
    	id="inputFileSizeLow"  
    	name="FileSizeLow" 
    	value="<?php echo $_REQUEST['FileSizeLow']; ?>"
		placeholder="bytes"  />
    <span class="input-group-addon">to</span>
    <input 
    	type="number" 
    	class="input-sm form-control" 
    	id="inputFileSizeHigh"  
    	name="FileSizeHigh"
    	value="<?php echo $_REQUEST['FileSizeHigh']; ?>"
		placeholder="bytes" />
</div>

<div id="divPanelRefineResultsSpacer"></div>

</div><!-- ./divRefineFileshareFileCreated -->  
</div><!-- ./panel-body (#divRefinePanelBodyNarrowLeftRight) -->

</div><!-- ./divRefineFileshareFileCreatedHideShow -->  
</div><!-- ./panel -->

<!-- -------------------- ./Div Panel For "File Size Range" ------------------- -->


<!-- ---------------------- Form Group For "File Created Date Range Range" ------------------- -->

<div class="panel panel-default" id="divPanelRefineFileshareFileCreated">
<div class="panel-heading" id="divRefinePanelHeading">File Creation Date:

<span class="pull-right">
	<button type="button" id="btnRefineFileshareFileCreatedHide" class="btn btn-info btn-xs">Hide</button>
	<button type="button" id="btnRefineFileshareFileCreatedShow" class="btn btn-info btn-xs">Show</button>
</span><!-- ./pull-right -->

</div><!-- ./panel-heading -->	

<div id="divRefineFileshareFileCreatedHideShow">
<div class="panel-body" id="divRefinePanelBodyNarrowLeftRight">
<div id="divRefineFileshareFileCreated">
<div id="divPanelRefineResultsSpacer"></div>

<div class="input-daterange input-group" id="datepicker">
    <input 
    	type="text" 
    	class="input-sm form-control" 
    	id="inputDateFileCreateFrom"  
    	name="FileCreateFrom" 
    	value="<?php echo $_REQUEST['FileCreateFrom']; ?>"
		placeholder="yyyy-mm-dd"  />
    <span class="input-group-addon">to</span>
    <input 
    	type="text" 
    	class="input-sm form-control" 
    	id="inputDateFileCreateTo"  
    	name="FileCreateTo" 
    	value="<?php echo $_REQUEST['FileCreateTo']; ?>"
		placeholder="yyyy-mm-dd"  />
</div>

<div id="divPanelRefineResultsSpacer"></div>
</div><!-- ./divRefineFileshareFileCreated -->  
</div><!-- ./panel-body (#divRefinePanelBodyDateRange) -->

</div><!-- ./divRefineFileshareFileCreatedHideShow -->  
</div><!-- ./panel -->




<!-- -------------------- ./Form Group For "File Created Date Range Range" ------------------- -->


<!-- ---------------------- Div Panel For "Match Phrase" ------------------- -->

<div class="panel panel-info" id="divPanelRefineFileshareMatchPhrase">
<div class="panel-heading" id="divRefinePanelHeading">Test:

<span class="pull-right">
	<button type="button" id="btnRefineFileshareMatchPhraseHide" class="btn btn-info btn-xs">Hide</button>
	<button type="button" id="btnRefineFileshareMatchPhraseShow" class="btn btn-info btn-xs">Show</button>
</span><!-- ./pull-right -->

</div><!-- ./panel-heading -->

<div id="divRefineFileshareMatchPhraseHideShow">
<div class="panel-body" id="divRefinePanelBodyNarrowLeftRight">
<div id="divRefineFileshareFileCreated">
<div id="divPanelRefineResultsSpacer"></div>

	<input name="MatchPhrase" value="<?php echo $_REQUEST['MatchPhrase']; ?>" type="text" placeholder="Match Phrase" class="form-control" />
	<br />
	<input name="pageFrom" value="<?php echo $_REQUEST['pageFrom'];  ?>" type="number" placeholder="pageFrom" class="form-control" />

<div id="divPanelRefineResultsSpacer"></div>

</div><!-- ./divRefineFileshareMatchPhrase -->  

</div><!-- ./panel-body (#divRefinePanelBodyNarrowLeftRight) -->

</div><!-- ./divRefineFileshareMatchPhraseHideShow -->  

</div><!-- ./panel -->

<!-- -------------------- ./Div Panel For "Match Phrase" ------------------- -->


<!-- 

<div class="row">
<form class="form-inline">

	<div class="col-xs-6">
	
	<label id="spanToggleRefine" for="From">From</label>	
	<div class="form-group">
		<input name="From" value="<?php /*echo $_REQUEST['From']; */?>" type="number" placeholder="From" class="form-control" />
	</div><!-- ./form-group -->
<!-- 	
	</div><!-- ./col-xs-6 -->
	

<!-- </form><!-- ./form-inline -->
<!-- </div><!-- ./div-row -->





</div><!-- ./divleftrefine -->
</div><!-- ./tags -->
		
				
<?php
	} // END foreach loop over results
?>

<!---------------------------------------------------------------------------------------------------------------------------------------------------------------
--- *** SCRIPTS ***
---------------------------------------------------------------------------------------------------------------------------------------------------------------->



<script type="text/javascript">

$('.input-daterange').datepicker({
	format: 'yyyy-mm-dd',
});


</script>



<!-- Assign PHP Aggregation Counts to Javascript variables -->

<script type="text/javascript">
<?php if(!empty($aggAuthorCount)){ ?> var aggAuthorCountJS = <?php echo $aggAuthorCount; } // End check if empty $aggAuthorCount ?>;
var aggFileTypeCountJS 		= <?php echo $aggFileTypeCount; ?>;
var aggContentTypeCountJS 	= <?php echo $aggContentTypeCount; ?>;
var aggApplicationCountJS 	= <?php echo $aggApplicationCount; ?>;
</script>
			
<!-- Toggle Check Boxes -->	


<script type="text/javascript">
function checkCheckboxes( id, pID ) {
	$('#'+pID).find(':checkbox').each(function() {
		jQuery(this).prop('checked', $('#' + id).is(':checked'));
    });     
}
</script>
