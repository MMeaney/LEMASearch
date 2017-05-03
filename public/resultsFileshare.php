<!-- ************************************************************************************************* -->
		
<!-- <ul bind="facets" class="facets-container ui-sortable" style="height: 462px;"></ul> -->

<!-- Display FILESHARE Results -->
<?php
if (count($resultsfileshare) > 0) {
?>

<div class="divright">

<div id="divPaginationandToggle">
<!-- Pagination Top -->
<?php if($resultscountfileshare>$page_rowsfileshare){ ?>
<nav><ul class="pagination"><?php echo $paginationCtrlsfileshare; ?></ul></nav>
<?php }
else { ?>

<div class="pagination">
<span class="text-muted" style=font-size:10pt><?php echo $textlinepagenumfileshare; ?></span>
</div>
<?php }
?>
<!-- ./Pagination Top -->

</div><!-- ./#divPaginationandToggle -->

<a href="#menu-toggle" class="btn btn-xs btn-success" id="menu-toggle">Toggle Menu</a>
&nbsp;

<span id="show" class="btn btn-info btn-xs">+ Expand All Results</span>
<span id="expand_delimiter">|</span>
<span id="hide" class="btn btn-info btn-xs">- Collapse All Results</span>
				
<!-- 
<span id="show" class="expand">+ Expand all</span>
<span id="expand_delimiter" class="expand">|</span>
<span id="hide" class="expand">- Collapse all</span>
 -->

<!-- 
<br /> -->

 
<div><!-- BEGIN loop to display NETWORK FILE Results -->

<script type="text/javascript">

function UrlExists(url, cb){
    jQuery.ajax({
        url:      url,
        dataType: 'text',
        type:     'GET',
        complete:  function(xhr){
            if(typeof cb === 'function')
               cb.apply(this, [xhr.status]);
        }
    });
}

</script>

<script type="text/javascript">
UrlExists('<?php $fileIconURL32 ?>', function(status){
    if(status === 404){
    	$("#imgIconURL32").attr("src", "http://localhost:8000/img/file-icons/32px/xls.png") 
    }
});

</script>
	

				
<ul class="list-group" id="results">
				
<?php
	foreach ($resultsfileshare as $result) {
		$searchResult = $result['_source'];
?>				

<?php
// Create links to files and folders
	$fileLocationString = $searchResult['path']['real'];
	$fileLocationReplace = 'file:/'.str_replace('\\', '/', $fileLocationString);
	$fileLocationReplaceFull = substr($fileLocationReplace, 0, strrpos( $fileLocationReplace, '/') );
	$fileLocation = $searchResult['file']['url'];
	$fileLocationFolder = str_replace('///', '//', $fileLocationReplaceFull); 
	
	$fileLocationReplaceExtLink = str_replace('\\', '/', $fileLocationString);
	$fileLocationExtLinkFolder = substr($fileLocationReplaceExtLink, 0, strrpos( $fileLocationReplaceExtLink, '/') );
	

	//Check 
	$fileLocationReplace = str_replace('\\', '/', $fileLocationString);
	$fileLocationWeb = substr($fileLocationReplace, 0, strrpos( $fileLocationReplace, '/') );
	
	$fileExtString = $searchResult['file']['filename'];
	$fileExt = substr($fileExtString, strrpos($fileExtString, ".") + 1);
	//$fileIconURL32 = ".\\\\img\\\\file-icons\\\\32px\\\\" .$fileExt. ".png";
	//$fileIconURL512 = ".\\\\img\\\\file-icons\\\\512px\\\\" .$fileExt. ".png";
	$fileIconURL32  = "http://" . $_SERVER['SERVER_NAME']  . ":" . $_SERVER['SERVER_PORT'] . "/img/file-icons/32px/" . $fileExt . ".png";
	$fileIconURL512 = "http://" . $_SERVER['SERVER_NAME']  . ":" . $_SERVER['SERVER_PORT'] . "/img/file-icons/512px/" . $fileExt . ".png";
?>

				
<div><!-- BEGIN display NETWORK FILE Results -->
<br />


<li class="list-group-item" id="liResults_<?php echo $result['_id']; ?>">
<div itemscope itemtype="http://schema.org/ImageObject" title="<?php echo $fileExt; ?>"> 
<div id="divFileShareBackgroundImage" style="
	background-image:url(<?php echo $fileIconURL512; ?>);
	background-repeat: no-repeat;
	background-size: 200px;
	background-position: right bottom;">

<!-- 
<div class="divFileShareResultsMain" style="background: radial-gradient(at top right, green, white);">
 -->	
<div class="divFileShareResultsMain" id="divFileShareResultsMain_<?php echo $result['_id']; ?>">

<div class="divFileShareBackgroundLayer">
<?php
	if (!empty($searchResult['file']['filename'])) { ?>
	
	

	
	
	
		<img height="24" width="24" align="top" id="imgIconURL32" alt="<?php echo $fileExt ?>" src="<?php echo $fileIconURL32; ?>" onerror="this.src=''" />
		
	<span id="spanResultsFilename" vertical-align="bottom"><a href="/view.php?id=<?php echo $result['_id']; ?>">
	<?php
	$strfilename = $searchResult['file']['filename'];
	echo $strfilename = preg_replace("/\w*?$searchText\w*/i", "<i>$0</i>", $strfilename)
	?>
	</a></span>		
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['file']['_name'])) { ?>
	<span id="spanResultsFilename"><a href="/view.php?id=<?php echo $result['_id']; ?>">
	<?php
	$strfilename1 = $searchResult['file']['_name'];
	echo $strfilename1 = preg_replace("/\w*?$searchText\w*/i", "<i>$0</i>", $strfilename1)
	?>
	</a></span>
	<?php
	} // END check if empty
?>
<!-- 
<div class="pull-right">
<!-- <form action="searchMain.php" method="POST"> -->
<!-- 
<span id="spanRelevantDocument">

<?php

/*

echo('
	<input type="checkbox" class="checkRelevantDocument" name="inputRelevantDocument[]" id="inputRelevantDocument-'.$result['_id'].'" value="'.$result['_id'].'" />
	<label id="labelRelevantDocument" for="inputRelevantDocument-'.$result['_id'].'">Mark as Relevant</label>
');

*/
/* Version below REALLY commented
/*
echo('
	<input type="checkbox" name="inputRelevantDocument'. $result['_id'].'" id="inputRelevantDocument'. $result['_id'].'" value="inputRelevantDocument'. $result['_id'].'" />
	<label id="labelRelevantDocument'. $result['_id'].'" >Mark as Relevant</label>
');
//*/
?>
</span><!-- ./spanRelevantDocument -->
<!-- </form> -->

<!-- 
</div><!-- ./pull-right -->

<!-- Display folder name value if FOLDER returned -->
<?php
	if (!empty($searchResult['real'])) {
	?>
	Folder: <a href="/view.php?id=<?php echo $result['_id']; ?>"><font size=4><?php
	$strfilenamefolder = $searchResult['real'];
	echo $strfilenamefolder = preg_replace("/\w*?$searchText\w*/i", "<b>$0</b>", $strfilenamefolder)
	?></font></a>
	<?php
	} // END check if empty
?>
				
<!-- Display folder value if FOLDER returned -->
<?php
	if (!empty($searchResult['virtual'])) {
	?>
	<a class="linkfile" href="<?php echo $searchResult['virtual']; ?>">
	<?php 
		$strurlfolder = $searchResult['virtual'];
		echo $strurlfolder = preg_replace("/\w*?$searchText\w*/i", "<b>$0</b>", $strurlfolder)
	?></a><br />
	<?php
	} // END check if empty
?>
				
<?php /*
	if (!empty($searchResult['file']['url'])) {
	?>
	<br /><a class="linkfile" href="<?php echo $searchResult['file']['url']; ?>">
	<?php 
		$strurl_fileurl = $searchResult['file']['url'];
		echo $strurl_fileurl = preg_replace("/\w*?$searchText\w(NOTE this should be *)/i", "<b>$0</b>", $strurl_fileurl);
	?></a>
	<?php
	} // END check if empty
*/?>
				
<?php
	if (!empty($searchResult['path']['real'])) {
	?>
	<br /><a class="linkfile" href="<?php echo $searchResult['path']['real']; ?>">
	<?php 
		$strurl_pathreal = $searchResult['path']['real'];
		echo $strurl_pathreal = preg_replace("/\w*?$searchText\w*/i", "<b>$0</b>", $strurl_pathreal);
	?></a>
	<?php
	} // END check if empty
?>

<div id="divSpacer"></div>

<table id="tableResults">
<tbody>

<?php
	if (!empty($searchResult['file']['filename'])) {
	?>
	<tr><td><span id="spanResultsBodyTitle">File type: </span></td>
		<td id="tdTableResults"><span id="spanResultsBodyText">
	<?php echo $fileExt; ?></span></td></tr>
	<?php
	} // END check if empty
?>			
				
<?php
	if (!empty($searchResult['file']['last_modified'])) {
	?>
	<tr><td><span id="spanResultsBodyTitle">Date modified: </span></td>
		<td id="tdTableResults"><span id="spanResultsBodyText">
	<?php
		$date_file_last_modified = date_create($searchResult['file']['last_modified']);
		echo date_format($date_file_last_modified,"Y-m-d H:i:s"); ?></span></td></tr>
						
	<?php
	} // END check if empty
?>	
<?php
	if (!empty($searchResult['meta']['raw']['meta:creation-date'])) {
	?>
	<tr><td><span id="spanResultsBodyTitle">Date created: </span></td>
		<td id="tdTableResults"><span id="spanResultsBodyText">
	<?php
		$date_meta_raw_description = date_create($searchResult['meta']['raw']['meta:creation-date']);
		echo date_format($date_meta_raw_description,"Y-m-d H:i:s"); ?></span></td></tr>
	<?php
	} // END check if empty
?>

	
<?php ///*
	if (!empty($searchResult['file']['indexing_date'])) {
	?>
	
	<tr><td>
	 <span id="spanMetaTypeBold">
	<b>Date Indexed:</b></td><td id="tdResultsPadLeft">
	 </span>
	<span id="spanMetaContent">
	 
	<?php
		$date_file_indexing_date = date_create($searchResult['file']['indexing_date']);
		echo date_format($date_file_indexing_date,"Y-m-d H:i:s"); ?></td></tr>
	</span>			
	<?php
	} // END check if empty
//*/?>
	
<?php
	if (!empty($searchResult['meta']['raw']['Application-Name'])) {
	?>
	<!-- 
	<tr><td> -->
	<!-- <span id="spanMetaTypeBold"> -->
	<!-- 
	<b>Application:</b></td><td id="tdResultsPadLeft">
	 -->
	<tr><td><span id="spanResultsBodyTitle">Application:</span></td>
	<!-- </span> -->
	<!-- <span id="spanMetaContent"> -->
		<td id="tdTableResults"><span id="spanResultsBodyText">
	<?php echo $searchResult['meta']['raw']['Application-Name']; ?></span></td></tr>
	<!-- </span> -->
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['file']['filesize'])) {
	?>
	<!-- <tr><td><b>Size: </b></td><td id="tdResultsPadLeft"> -->
	<tr><td><span id="spanResultsBodyTitle">Size: </span></td>
		<td id="tdTableResults"><span id="spanResultsBodyText">
					
	<?php 						
		$bytes = $searchResult['file']['filesize'];
		settype($bytes, "integer"); 
		if($bytes<1024){
			echo $bytes." bytes";
		}
		else if($bytes>=1024 && $bytes<1048576){
			$kilobytes = $bytes/1024;
			echo number_format((float)$kilobytes, 0, '.', ',')." Kb";
		}
 		else if($bytes>=1048576){
			$megabytes = $bytes/1048576;
			echo number_format((float)$megabytes, 1, '.', ',')." Mb";
		} ?></span></td></tr>
	<?php 
	} // END check if empty
?>

<?php
	if (!empty($searchResult['meta']['author'])) {
	?>
	<!--<tr><td><b>Author: </b></td><td id="tdResultsPadLeft">-->
	<tr><td><span id="spanResultsBodyTitle">Author: </span></td>
		<td id="tdTableResults"><span id="spanResultsBodyText">
	<?php 
		$strauthor = $searchResult['meta']['author'];
		echo $strauthor = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strauthor)
	?></span></td></tr>
	<?php
	} // END check if empty
?>		
				
<?php
	if (!empty($searchResult['meta']['raw']['description'])) {
	?>
	<!--<tr><td><b>Description: </b></td><td id="tdResultsPadLeft">-->
	<tr><td><span id="spanResultsBodyTitle">Description: </span></td>
		<td id="tdTableResults"><span id="spanResultsBodyText">
	<?php 
		$str_meta_raw_description = $searchResult['meta']['raw']['description'];
		echo $str_meta_raw_description = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $str_meta_raw_description)
	?></span></td></tr>
	<?php
	} // END check if empty
?>


</tbody>
</table><!-- ./tableResults -->

<?php if (!empty($searchResult['meta']['raw']['Copyright Flag'])) { ?> 

	<?php if (($searchResult['meta']['raw']['Copyright Flag']) == 'Yes') { ?>
	<br /> <span class="btn btn-warning btn-xs">Copyright:&nbsp;<?php echo $searchResult['meta']['raw']['Copyright Flag'];?></span>
	<?php if (!empty($searchResult['meta']['raw']['Copyright Notice'])) { ?> <br /> <span id="spanMetaTypeBold">Copyright Notice:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Copyright Notice'];?></span><?php } // END check if empty ?>
	
<?php } 
	else { ?>
	<br /> <span class="btn btn-success btn-xs">Copyright:&nbsp;<?php echo $searchResult['meta']['raw']['Copyright Flag'];?></span>
	
<?php } // END check if NO 
} // END check if empty ?>


<div id="divSpacer"></div>
				
<!-- If image display image thumbnail -->
<?php
	if (!empty($searchResult['file']['content_type'])) {
		if (strpos($searchResult['file']['content_type'], 'image') !== false) {
?>				
	<div class="parContent">
	<?php
			echo "<a href=\"{$searchResult['file']['url']}\">";
		?>
		<!-- <img class="imgfull" src="data:image/png;base64,<? /* php echo $searchResult['attachment']; */ ?> "/>	 -->
		<img class="imgThumb" src="<?php echo $fileLocation; ?> "/>
	</a>
	</div>		

	<?php
		}
	}
?>
				
<?php
	if (!empty($searchResult['content'])) {
	?>
	<!--<p class="parContent"><i>-->
	<div class="well well-sm" id="wellResultContent">						
					
	<?php 
	$in = $searchResult['content'];
	$wordToFind = $searchText;
	$numWordsToWrap = 10;
	//echo $searchText;
	//echo $in;

	$words = preg_split('/\s+/', $in);

	$found_words    =   preg_grep("/^".$wordToFind.".*/", $words);
	$found_pos      =   array_keys($found_words);
	if(count($found_pos>0))
	{
		$pos = $found_pos[0];
	}

	if (isset($pos)) 
	{
		$start = ($pos - $numWordsToWrap > 0) ? $pos - $numWordsToWrap : 0;
		$length = (($pos + ($numWordsToWrap + 1) < count($words)) ? $pos + ($numWordsToWrap + 1) : count($words)) - $start;
		$slice = array_slice($words, $start, $length);
		//array_push($slice, " xcv ");

		$pre_start  =   ($start > 0) ? "... ":"";
		$post_end   =   ($pos + ($numWordsToWrap + 1) < count($words)) ? " ...":"";

		$out = $pre_start.implode(' ', $slice).$post_end;
		echo $out = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $out);
	} 
	else 
		$strcontentlimit = substr($searchResult['content'],0,400)." ..."; 
		$strcontent = $strcontentlimit; 
		echo $strcontent = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strcontent);
						
	//$strcontentlimit = substr($searchResult['content'],0,300)."...";
	//$strcontent = $strcontentlimit;
	//echo $strcontent = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strcontent);
					
	?>	
	</div><!-- ./well well-sm -->				
	<!--</i></p><!-- ./parContent -->
	<?php
	} // END check if empty
?>

<?php
	if (empty($searchResult['content'])) {
	?>
	<br />
	<?php
	} // END check if empty
?>


<div class="togglecontent">
<span id="hide<?php echo $result['_id']; ?>" class="expand_hide">- Show less</span>
<span id="show<?php echo $result['_id']; ?>" class="expand_show">+ Show more</span>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $("#hide<?php echo $result['_id']; ?>").hide();
    // Display details
    $("#show<?php echo $result['_id']; ?>").click(function(){
        $("#contentfull<?php echo $result['_id']; ?>").show();
        $("#hide<?php echo $result['_id']; ?>").show();
        $("#show<?php echo $result['_id']; ?>").hide();
        $("#hide").show();
        $("#expand_delimiter").show();
    });
    // Hide details
    $("#hide<?php echo $result['_id']; ?>").click(function(){
        $("#contentfull<?php echo $result['_id']; ?>").hide();
        $("#hide<?php echo $result['_id']; ?>").hide();
        $("#show<?php echo $result['_id']; ?>").show();
        $("#show").show();
        $("#expand_delimiter").show();
    });
});

$("#contentfull<?php echo $result['_id']; ?>").each(function() {
    if ($(this).css("visibility") == "hidden") {
        $("#hide<?php echo $result['_id']; ?>").hide();
        $("#show<?php echo $result['_id']; ?>").show();
    } else {
        $("#hide<?php echo $result['_id']; ?>").show();
        $("#show<?php echo $result['_id']; ?>").hide();
    }
});



$('.checkRelevantDocument').click(function() {
	if ($(this).is(':checked')) {
		$(this).siblings('label').html('Relevant');
		$(this).siblings('label').css("color", '#008000');
		//$(this).siblings('label').css("font-style", 'italic');
		$(this).siblings('label').css("background-color", '#D9F2D9');
		$('#liResults_<?php echo $result['_id']; ?>').css("background", 'radial-gradient(at top right, #D9F2D9, white)');
		//$(this).siblings('label').css("background", 'radial-gradient(at top right, #D9F2D9, white)');
		//$('.divFileShareResultsMain').css("background", 'radial-gradient(at top right, green, white)');
		//$('#divFileShareResultsMain_<?php /*echo $result['_id'];*/ ?>').css("background", 'none');
	} else {
		$(this).siblings('label').html('Mark as Relevant');
		$(this).siblings('label').css("color", '#1990B8');
		$(this).siblings('label').css("font-style", 'normal');
		$(this).siblings('label').css("background-color", '');
		$('#liResults_<?php echo $result['_id']; ?>').css("background", 'none');
		//$('.divFileShareResultsMain').css("background", 'none');
		//$('#divFileShareResultsMain_<?php /*echo $result['_id'];*/ ?>').css("background", 'none');
	}
});



</script>

<div class="contentfull" id="contentfull<?php echo $result['_id']; ?>">
<script type="text/javascript">
$("#contentfull<?php echo $result['_id']; ?>").load("view.php?id=<?php echo $result['_id']; ?> #div_view_detail");
</script>
</div><!-- ./contentfull -->

<span id="spanNavigation">
<?php /*
	echo $searchResult['path']['real']; 
	echo nl2br ("\n");
	echo $searchResult['file']['url'];
	echo nl2br ("\n");
	echo $fileLocation;
	echo nl2br ("\n");
	echo $fileLocationFolder;
	echo nl2br ("\n");
	echo $fileLocationWeb; 
	echo nl2br ("\n");
*/ ?>

<a href="/view.php?id=<?php echo $result['_id']; ?>" target="_blank">Open details page</a>&nbsp;|
	<a href="<?php echo $fileLocationFolder; ?>" target="_blank">Open folder</a>&nbsp;|
	<a href="<?php echo $fileLocationWeb; ?>" target="_blank">Open Web link</a>&nbsp;|
	<a href="<?php echo $fileLocation; ?>" target="_blank">Open file</a>
</span>





</div><!-- ./divFileShareBackgroundLayer -->
</div><!-- ./divFileShareBackgroundImage -->
</div><!-- ./divFileShareResultsMain -->
</div><!-- ./itemscope --> 
				
</li>
				
<br />
				
</div><!-- END display NETWORK FILE Results -->
<?php
	} // END foreach loop over results
?>
</ul>
</div><!-- END loop to display NETWORK FILE Results -->
<br />
				
<!-- Pagination Bottom -->
<?php if($resultscountfileshare>$page_rowsfileshare){ ?>
<nav><ul class="pagination"><?php echo $paginationCtrlsfileshare; ?></ul></nav>
<?php } ?>
<span class="text-muted" style=font-size:10pt><?php echo $textlinepagenumfileshare; ?></span>
<!-- ./Pagination Bottom -->		

</div><!-- ./divright -->

		

<?php
} // END if there are search results
else {
?>
						
	<div class="well">Sorry, nothing found</div><!-- ./well -->
<?php
} // END elseif there are no search results
?>

<!-- ------------------- Display Query JSON ------------------- -->

<br />
<div class="panel panel-default">
<div class="panel-heading">Query JSON

<span class="pull-right">
	<button type="button" id="btnFileshareQueryJSONHide" class="btn btn-info btn-xs">Hide</button>
	<button type="button" id="btnFileshareQueryJSONShow" class="btn btn-info btn-xs">Show</button>
</span><!-- ./pull-right -->

</div><!-- ./panel-heading -->

<div id="divFileshareQueryJSONHideShow">
<div class="panel-body" id="divRefinePanelBody">
<div id="divPanelRefineResultsSpacer"></div>
<pre>
<!-- <code data-language="json"> -->
<code class="language-json">
<?php 
//$json_query_fileshare_print = json_decode($paramfileshare['body']);
//echo json_encode($json_query_fileshare_print, JSON_PRETTY_PRINT);
echo json_encode($paramfileshare['body'], JSON_PRETTY_PRINT);
?>
</code></pre>
<div id="divPanelRefineResultsSpacer"></div>
</div><!-- ./panel-body (#divRefinePanelBody) -->
<!-- <div class="panel-footer"></div> --><!-- ./panel-footer -->
</div><!-- ./divFileshareQueryJSONHideShow --> 
</div><!-- /.panel-default --> 

<!-- ------------------ ./ Display Query JSON ----------------- -->

