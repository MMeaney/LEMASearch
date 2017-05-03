<!-- Display MAIN Results -->

<?php if (count($results) > 0) { ?>
				
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

 

<?php
foreach ($results as $result) {
$searchResult = $result['_source'];
?>

<div><!-- BEGIN display MAIN SEARCH Results -->


<div class="panel panel-default" id="divPanelResultsMain">
<div class="panel-heading" id="divPanelResultsMainHeading">
<?php
	if (!empty($searchResult['file']['_name'])) {
	?>	
	<span id="resultsMainTitle"><a href="/view.php?id=<?php echo $result['_id']; ?>" id="resultsMainTitle">
	<?php
	$strfilename1 = $searchResult['file']['_name'];
	echo $strfilename1 = preg_replace("/\w*?$searchText\w*/i", "<i>$0</i>", $strfilename1)
	?>
	</a><br />
	</span><!-- ./resultsMainTitle -->
	<?php
	} // END check if empty
?>

<?php
	if (!empty($searchResult['file']['filename'])) {
	?>	
	<span id="resultsMainTitle"><a href="/view.php?id=<?php echo $result['_id']; ?>" id="resultsMainTitle">
	<?php
	$strfilename2 = $searchResult['file']['filename'];
	echo $strfilename2 = preg_replace("/\w*?$searchText\w*/i", "<i>$0</i>", $strfilename2)
	?>
	</a><br />
	</span><!-- ./resultsMainTitle -->
	<?php
	} // END check if empty
?>

<?php
	if (!empty($searchResult['title'])) {
	?>	
	<span id="resultsMainTitle"><a href="/view.php?id=<?php echo $result['_id']; ?>" id="resultsMainTitle">
	<?php
	$strfilename3 = $searchResult['title'];
	echo $strfilename3 = preg_replace("/\w*?$searchText\w*/i", "<i>$0</i>", $strfilename3)
	?>
	</a><br />
	</span><!-- ./resultsMainTitle -->
	
	<?php
	} // END check if empty
?>

<?php
	if (!empty($searchResult['Dba'])) {
	?>	
	<span id="resultsMainTitle"><a href="/view.php?id=<?php echo $result['_id']; ?>" id="resultsMainTitle">
	<?php
	$strfilename4 = $searchResult['Dba'];
	echo $strfilename4 = preg_replace("/\w*?$searchText\w*/i", "<i>$0</i>", $strfilename4)
	?>
	</a><br />
	</span><!-- ./resultsMainTitle -->	
	<?php
	} // END check if empty
?>

<!-- TITLE in Array -->
<?php
	if (!empty($searchResult['title'])) {
	?>
	<span id="resultsMainTitle"><a href="/view.php?id=<?php echo $result['_id']; ?>" id="resultsMainTitle">
	<?php
		foreach($searchResult['title'] as $row) {
	echo $row, ', ';
	} 
	?>
	</a><br />
	</span><!-- ./resultsMainTitle -->
	<?php
	} // END check if empty
?>

<!-- Display folder name value if FOLDER returned -->
<?php
	if (!empty($searchResult['real'])) {
	?>	
	<span id="resultsMainTitle">Folder: <a href="/view.php?id=<?php echo $result['_id']; ?>" id="resultsMainTitle">
	<?php
	$strFilenameFolder = $searchResult['real'];
	echo $strFilenameFolder = preg_replace("/\w*?$searchText\w*/i", "<b>$0</b>", $strFilenameFolder)
	?>
	</a><br />
	</span><!-- ./resultsMainTitle -->
	<?php
	} // END check if empty
?>

<?php
	if (!empty($searchResult['file']['url'])) {
	?>
	<a class="linkfile" href="<?php echo $searchResult['file']['url']; ?>">
	<?php 
		$strurl1 = $searchResult['file']['url'];
		echo $strurl1 = preg_replace("/\w*?$searchText\w*/i", "<b>$0</b>", $strurl1)
	?></a><br />
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['url'])) {
	?>
	<a class="linkfile" href="<?php echo $searchResult['url']; ?>">
	<?php 
		$strurl2 = $searchResult['url'];
		echo $strurl2 = preg_replace("/\w*?$searchText\w*/i", "<b>$0</b>", $strurl2)
	?></a><br />
	<?php
	} // END check if empty
?>

</div><!-- ./panel-heading -->	

<div class="panel-body">
				
<?php
	if (!empty($searchResult['Outlook_Full_Name'])) {
	?><a href="#searchPeople"><font size=4><b><?php
	$strfilename5 = $searchResult['Outlook_Full_Name'];
	echo $strfilename5 = preg_replace("/\w*?$searchText\w*/i", "<i>$0</i>", $strfilename5)
	?></b></font></a><br />
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
				
<!-- Type/format if open data dataset -->				
<?php
	if (!empty($searchResult['format'])) {
	?>
	<i>
	<?php echo $searchResult['format']; ?>
	</i><br />
	<?php
	} // END check if empty
?>

<!-- 
	<i>Highlight URL: 
	<?php 
	/* 
		echo $searchResult['highlight']['file']['url'];
		echo $searchResult['highlight'][0]; 
	*/
	?>
	</i><br />
 -->

				
<?php
	if (!empty($searchResult['description'])) {
		$strdescription = $searchResult['description'];
		echo $strdescription = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strdescription)
	?>
	<br />
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['file']['filename'])) {
	?>
	<b>File type: </b>
	<?php 
		$filetypestring = $searchResult['file']['filename'];    
		$filetype = substr($filetypestring, strrpos($filetypestring, ".") + 1);    
		echo $filetype; ?>
	<br />
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['file']['content_type'])) {
	?>
	<b>Content type: </b>
	<?php echo $searchResult['file']['content_type']; ?>
	<br />
	<?php
	} // END check if empty
?>

<?php
	if (!empty($searchResult['file']['last_modified'])) {
	?>
  	<b>Last Modified: </b><?php 
  	$date=date_create($searchResult['file']['last_modified']); 
  	echo date_format($date,"Y-m-d H:i:s"); ?>
	<br />
	<?php
	} // END check if empty
?>

<?php
	if (!empty($searchResult['lastModified'])) {
	?>
	<b>Last Modified: </b><?php echo $searchResult['lastModified']; ?>
	<br />
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['file']['filesize'])) {
	?>
	<b>Size: </b>
					
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
		} ?>
	<br />
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['meta']['author'])) {
	?>
	<b>Author: </b>
	<?php 
		$strauthor = $searchResult['meta']['author'];
		echo $strauthor = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strauthor)
	?>
	<br />
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['Outlook_E-mail'])) {
	?>
	<b>Email: </b><a href="mailto:<?php echo $searchResult['Outlook_E-mail']?>">
	<?php 
		$strOutlook_E_mail = $searchResult['Outlook_E-mail'];
		echo $strOutlook_E_mail = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_E_mail)
	?></a><br />
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['Outlook_Account'])) {
		$emailDomain = substr($searchResult['Outlook_E-mail'], -6);
		if ($emailDomain == "epa.ie")
		{
	?>
	<b>Account Username: </b>
	<?php 
		$strOutlook_Account = $searchResult['Outlook_Account'];
		echo $strOutlook_Account = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_Account)
	?>
	<?php
		} // End check Email domain
	?>
	<br />
	<?php 
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['Outlook_Business_Phone'])) {
	?>
	<b>Ext: </b>
	<?php 
		$strOutlook_Outlook_Business_Phone = $searchResult['Outlook_Business_Phone'];
		echo $strOutlook_Outlook_Business_Phone = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_Outlook_Business_Phone)
	?>
	<br />
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['Outlook_Company']) && !empty($searchResult['CRM_Organisation'])) {
	?>
	<b>Organisation: </b>
	<?php 
		$strOutlook_Company = $searchResult['Outlook_Company'];
		echo $strOutlook_Company = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_Company)
	?>,
	<?php 
		$strCRM_Organisation = $searchResult['CRM_Organisation'];
		echo $strCRM_Organisation = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strCRM_Organisation)
	?>
	<br />
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['Outlook_Company']) && empty($searchResult['CRM_Organisation'])) {
	?>
	<b>Organisation: </b>
	<?php 
		$strOutlook_Company = $searchResult['Outlook_Company'];
		echo $strOutlook_Company = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_Company)
	?>
	<br />
	<?php
	} // END check if empty
?>
				
<?php
	if (empty($searchResult['Outlook_Company']) && !empty($searchResult['CRM_Organisation'])) {
	?>
	<b>Organisation: </b>
	<?php 
		$strCRM_Organisation = $searchResult['CRM_Organisation'];
		echo $strCRM_Organisation = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strCRM_Organisation)
	?>
	<br />
	<?php
	} // END check if empty
?>


<!-- Comment out for People in Main Search tab -->
<!-- <b>Location: </b> -->			

<?php /*
	if (empty($searchResult['Outlook_Office_Location']) 
		and empty($searchResult['CRM_City']) 
		and empty($searchResult['CRM_Country']) 
		and empty($searchResult['CRM_County']) 
		and empty($searchResult['CRM_Street_1']) 
		and empty($searchResult['CRM_Street_2']) 
		and empty($searchResult['CRM_Street_3']) 
		and empty($searchResult['CRM_Postal_Code'])) { */
	?>
<!-- <i>No location details available</i><br /> -->
	<?php /*
	} */ // END check if empty
?>

<?php 
	if (!empty($searchResult['Outlook_Office_Location']) 
		or !empty($searchResult['CRM_City']) 
		or !empty($searchResult['CRM_Country']) 
		or !empty($searchResult['CRM_County']) 
		or !empty($searchResult['CRM_Street_1']) 
		or !empty($searchResult['CRM_Street_2']) 
		or !empty($searchResult['CRM_Street_3']) 
		or !empty($searchResult['CRM_Postal_Code'])) { 
	?>
<b>Location: </b>	
<?php
	if (!empty($searchResult['Outlook_Office_Location'])) {
		$strOutlook_Office_Location = $searchResult['Outlook_Office_Location'];
		echo $strOutlook_Office_Location = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_Office_Location);
	} // END check if empty
?>
			
<?php
	if (!empty($searchResult['CRM_City'])) {
		$strCRM_City = $searchResult['CRM_City'];
		echo $strCRM_City = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strCRM_City);
	} // END check if empty
?>
			
<?php
	if (!empty($searchResult['CRM_Street_1'])) {
		$strCRM_Street_1 = $searchResult['CRM_Street_1'];
		echo $strCRM_Street_1 = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strCRM_Street_1);
	} // END check if empty
?>
			
<?php
	if (!empty($searchResult['CRM_Street_2'])) {
		$strCRM_Street_2 = $searchResult['CRM_Street_2'];
		echo $strCRM_Street_2 = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strCRM_Street_2);
	} // END check if empty
?>
			
<?php
	if (!empty($searchResult['CRM_Street_3'])) {
		$strCRM_Street_3 = $searchResult['CRM_Street_3'];
		echo $strCRM_Street_3 = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strCRM_Street_3);
	} // END check if empty
?>
			
<?php
	if (!empty($searchResult['CRM_Postal_Code'])) {
		$strCRM_Postal_Code = $searchResult['CRM_Postal_Code'];
		echo $strCRM_Postal_Code = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strCRM_Postal_Code);
	} // END check if empty
?>
			
<?php
	if (!empty($searchResult['CRM_County'])) {
		$strCRM_County = $searchResult['CRM_County'];
		echo $strCRM_County = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strCRM_County);
	} // END check if empty
?>
			
<?php
	if (!empty($searchResult['CRM_Country'])) {
		$strCRM_Country = $searchResult['CRM_Country'];
		echo $strCRM_Country = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strCRM_Country);
	} // END check if empty
?>
<br />
<?php
} // END check if empty
?>
				
<?php
	if (!empty($searchResult['CRM_Mobile_Phone']) && !empty($searchResult['Outlook_Mobile_Phone'])) {
	?>
	<br /><b>Mobile: </b>
	<?php 
		$strCRM_Mobile_Phone = $searchResult['CRM_Mobile_Phone'];
		echo $strCRM_Mobile_Phone = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strCRM_Mobile_Phone)
	?>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['Outlook_Mobile_Phone']) && empty($searchResult['CRM_Mobile_Phone'])) {
	?>
	<br /><b>Mobile: </b>
	<?php 
		$strOutlook_Mobile_Phone = $searchResult['Outlook_Mobile_Phone'];
		echo $strOutlook_Mobile_Phone = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_Mobile_Phone)
	?>
	<?php
	} // END check if empty
?>
				
<?php
	if (empty($searchResult['Outlook_Mobile_Phone']) && !empty($searchResult['CRM_Mobile_Phone'])) {
	?>
	<br /><b>Mobile: </b>
	<?php 
		$strCRM_Mobile_Phone = $searchResult['CRM_Mobile_Phone'];
		echo $strCRM_Mobile_Phone = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strCRM_Mobile_Phone)
	?>
	<?php
	} // END check if empty
?>
			
<?php
	if (!empty($searchResult['content'])) {
	?>
	<!--<p class="divcontent"><i>-->
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

		$pre_start = ($start > 0) ? "... ":"";
		$post_end  = ($pos + ($numWordsToWrap + 1) < count($words)) ? " ...":"";

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
	<!--</i></p>-->
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
				
<span style=font-size:10pt>
	<a href="/view.php?id=<?php echo $result['_id']; ?>">More information</a>&nbsp;|
	<?php
		$filelocationstring = $searchResult['path']['real'];
		$filelocationreplace = str_replace('\\', '/', $filelocationstring);
		$filelocation = substr($filelocationreplace, 0, strrpos( $filelocationreplace, '/') );
	?>
	<a href="<?php echo $filelocation; ?>" target="_blank">Open file location</a>&nbsp;|
	<a href="<?php echo $filelocationstring; ?>" target="_blank">Open file</a>
</span>

</div><!-- ./panel-body -->
	
</div><!-- ./divPanelResultsMain -->

<!-- Add space between results panels -->
<br />
										
</div><!-- END display MAIN SEARCH Results -->
<?php
	} // END foreach loop over results
?>
<br />
<br />
			
<!-- Pagination Bottom -->
<?php if($resultscount>$page_rows){ ?>
<nav><ul class="pagination"><?php echo $paginationCtrls; ?></ul></nav>
<?php } ?>
<span class="text-muted" style=font-size:10pt><?php echo $textlinepagenum; ?></span>
<br />
<br />
<!-- ./Pagination Bottom -->
				
<?php
} // END if there are search results
else {
?>
	<div class="well">Sorry, nothing found</div><!-- ./well -->
<?php
} // END elseif there are no search results
?>

