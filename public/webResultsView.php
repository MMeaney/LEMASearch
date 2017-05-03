<?php
if (count($resultsWeb) > 0) {
?>

<?php
foreach ($resultsWeb as $result) {
$searchResult = $result['_source'];
?>

<div id="container70"><!-- BEGIN display WEB SEARCH Results -->

<?php
if (!empty($searchResult['url'])) 
{
?>

<div class="panel panel-default" id="divPanelResultsMain">

<div class="panel-heading" id="divPanelResultsMainHeading">
<?php 
$goodURL = str_replace('\r', '', $searchResult['url']); // Parsed to remove erroneous '\r' suffix from some URLs
?>
<script type="text/javascript">	
	var jsGoodURL = "<?php echo $goodURL; ?>";
	var jsDomain = extractDomain(jsGoodURL);
	document.write('<img src="http://www.google.com/s2/favicons?domain=' + jsDomain + '" title = "' + jsDomain + '"> &nbsp;');
</script>
<span id="resultsMainTitle"><a href="<?php echo $goodURL; ?>" id="resultsMainTitle" target="_blank">
<?php
$strPageTitle = $searchResult['title'];
if(!empty($strPageTitle))
{
	echo $strPageTitle = preg_replace("/\w*?$searchText\w*/i", "<i>$0</i>", $strPageTitle);
}
else 
{
	echo $goodURL;
}
		
?>
</a><br />
</span><!-- ./resultsMainTitle -->
	
<?php
	if (!empty($goodURL)) {
	?>
	<a class="linkfile" href="<?php echo $goodURL ?>" target="_blank">
	<?php 
		$strURL = $goodURL;
		echo $strURL = preg_replace("/\w*?$searchText\w*/i", "<b>$0</b>", $strURL)
	?></a><br />
	<?php
	} // END check if empty
?>
	
</div><!-- ./panel-heading -->
	
<div class="panel-body" id="divPanelResultsMainBody">

<?php
	if (!empty($searchResult['body'])) {
	?>
	<span id="spanResultsBodyText">					
					
	<?php 
	$in = $searchResult['body'];
	$wordToFind = $searchText;
	$numWordsToWrap = 10;

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
		$strcontentlimit = substr($searchResult['body'],0,400)." ..."; 
		$strcontent = $strcontentlimit; 
		echo $strcontent = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strcontent);
					
	?>	
	</span><!-- ./spanResultsBodyText -->
	<?php
	} // END check if empty
?>


<?php
	if (!empty($searchResult['body1'])) {
	?>
	<span id="spanResultsBodyText">					
					
	<?php 
	$in = $searchResult['body1'];
	$wordToFind = $searchText;
	$numWordsToWrap = 10;

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
		$strcontentlimit = substr($searchResult['body1'],0,400)." ..."; 
		$strcontent = $strcontentlimit; 
		echo $strcontent = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strcontent);
					
	?>	
	</span><!-- ./spanResultsBodyText -->
	<?php
	} // END check if empty
?>
				

</div><!-- ./panel-body -->
	
</div><!-- ./divPanelResultsMain -->

<!-- Add space between results panels -->
<br />
								
<?php
} // END check if empty
?>
										
</div><!-- END display WEB SEARCH Results -->
<?php
} // END foreach loop over results
?>
<br />
<br />
<?php
} // END if there are search results

else {
?>
  <div class="well">Sorry, nothing found</div>
<?php
} // END elseif there are no search results
?>
