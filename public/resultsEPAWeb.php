<!-- Display EPA Web Results -->

<?php
if (count($resultsEPAWeb) > 0) {
?>

<div><!-- BEGIN loop to display NETWORK FILE Results -->
				
<ul class="list-group" id="results">
				
<?php
	foreach ($resultsEPAWeb as $result) {
		$searchResult = $result['_source'];
?>			
				
<div><!-- BEGIN display EPA Web Results -->
<br />
<?php
	if (!empty($searchResult['head-title'])) { ?>
	
<li class="list-group-item" id="li-results"<?php //echo $filetype; ?>">

<?php
	if (!empty($searchResult['head-title'])) { ?>
	<span id="spanResultsFilename"><a target="_blank" href="/view.php?id=<?php echo $result['_id']; ?>">
	<?php
	$strfilename1 = $searchResult['head-title'];
	echo $strfilename1 = preg_replace("/\w*?$searchText\w*/i", "<i>$0</i>", $strfilename1)
	?>
	</a></span><br />
	<?php
	} // END check if empty
?>


<?php
	if (!empty($searchResult['url'])) {
	?>
	<a class="linkfile" target="_blank" href="<?php echo $searchResult['url']; ?>">
	<?php 
		$strurlfolder = $searchResult['url'];
		echo $strurlfolder = preg_replace("/\w*?$searchText\w*/i", "<b>$0</b>", $strurlfolder)
	?></a><br />
	<?php
	} // END check if empty
?>

<div id="divSpacer"></div>

<?php
	if (!empty($searchResult['message'])) {
	?>
	<!--<p class="parContent"><i>-->
	<div class="well well-sm" id="wellResultContent">						
					
	<?php 
	$in = $searchResult['message'];
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
		$strcontentlimit = substr($searchResult['message'],0,400)." ..."; 
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
	if (empty($searchResult['message'])) {
	?>
	<br />
	<?php
	} // END check if empty
?>


</li>
				
<br />

<?php
	} // END IF No Head-Title
?>
			
</div><!-- END display EPA Web Results -->
<?php
	} // END foreach loop over results
?>
</ul>
</div><!-- END loop to display EPA Web Results -->
<br />

				
<?php
} // END if there are search results
else {
?>
	<div class="well">Sorry, nothing found</div><!-- ./well -->
<?php
} // END elseif there are no search results
?>

<h4>Query JSON</h4>
<pre>
<code class="language-json">
<?php echo json_encode($paramPeople['body'], JSON_PRETTY_PRINT);
?>
</code>
</pre>