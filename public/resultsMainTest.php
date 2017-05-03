<!-- Display MAIN Results -->

<?php if (count($resultsMainTest) > 0) { ?>
				
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
/*
$wordApi = new WordApi($clientWordnik);
$example = $wordApi->getTopExample('irony');
print $example->text;
*/?>


<?php
foreach ($resultsMainTest as $result) {
$searchResultMainTest = $result['_source'];
?>
				
<div><!-- BEGIN display MAIN SEARCH Results -->

<?php
	if (!empty($searchResultMainTest['file']['_name'])) {
	?>
	<br /><a href="/view.php?id=<?php echo $result['_id']; ?>"><font size=4><b><?php
	$strfilename1 = $searchResultMainTest['file']['_name'];
	echo $strfilename1 = preg_replace("/\w*?$searchText\w*/i", "<i>$0</i>", $strfilename1)
	?></b></font></a>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResultMainTest['file']['filename'])) {
	?>
	<br /><a href="/view.php?id=<?php echo $result['_id']; ?>"><font size=4><b><?php
	echo $searchResultMainTest['file']['filename'];
	?></b></font></a>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResultMainTest['title'])) {
	?>
	<br /><a href="/view.php?id=<?php echo $result['_id']; ?>"><font size=4><b><?php
	$strfilename3 = $searchResultMainTest['title'];
	echo $strfilename3 = preg_replace("/\w*?$searchText\w*/i", "<i>$0</i>", $strfilename3)
	?></b></font></a>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResultMainTest['Dba'])) {
	?>
	<br /><a href="/view.php?id=<?php echo $result['_id']; ?>"><font size=4><b><?php
	$strfilename4 = $searchResultMainTest['Dba'];
	echo $strfilename4 = preg_replace("/\w*?$searchText\w*/i", "<i>$0</i>", $strfilename4)
	?></b></font></a>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResultMainTest['Outlook_Full_Name'])) {
	?>
	<br /><a href="#searchPeople"><font size=4><b><?php
	$strfilename5 = $searchResultMainTest['Outlook_Full_Name'];
	echo $strfilename5 = preg_replace("/\w*?$searchText\w*/i", "<i>$0</i>", $strfilename5)
	?></b></font></a>
	<?php
	} // END check if empty
?>
				
<!-- TITLE in Array -->
<?php
	if (!empty($searchResultMainTest['title'])) {
	?>
	<br /><a href="/view.php?id=<?php echo $result['_id']; ?>"><font size=4><b><?php
	foreach($searchResultMainTest['title'] as $row) {
	echo $row, ', ';
	} ?>
	</b></font></a>
	<?php
	} // END check if empty
?>

<!-- Display folder name value if FOLDER returned -->
<?php
	if (!empty($searchResultMainTest['real'])) {
	?>
	<br />Folder: <a href="/view.php?id=<?php echo $result['_id']; ?>"><font size=4><?php
	$strfilenamefolder = $searchResultMainTest['real'];
	echo $strfilenamefolder = preg_replace("/\w*?$searchText\w*/i", "<b>$0</b>", $strfilenamefolder)
	?></font></a>
	<?php
	} // END check if empty
?>


<?php
	if (!empty($searchResultMainTest['file']['url'])) {
	?>
	<br /><a class="linkfile" href="<?php echo $searchResultMainTest['file']['url']; ?>">
	<?php 
		echo $searchResultMainTest['file']['url'];
	?></a>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResultMainTest['url'])) {
	?>
	<br /><a class="linkfile" href="<?php echo $searchResultMainTest['url']; ?>">
	<?php 
		$strurl2 = $searchResultMainTest['url'];
		echo $strurl2 = preg_replace("/\w*?$searchText\w*/i", "<b>$0</b>", $strurl2)
	?></a><br />
	<?php
	} // END check if empty
?>
				
<!-- Display folder value if FOLDER returned -->
<?php
	if (!empty($searchResultMainTest['virtual'])) {
	?>
	<br /><a class="linkfile" href="<?php echo $searchResultMainTest['virtual']; ?>">
	<?php 
		$strurlfolder = $searchResultMainTest['virtual'];
		echo $strurlfolder = preg_replace("/\w*?$searchText\w*/i", "<b>$0</b>", $strurlfolder)
	?></a><br />
	<?php
	} // END check if empty
?>
				
<!-- Type/format if open data dataset -->				
<?php
	if (!empty($searchResultMainTest['format'])) {
	?>
	<br /><i>
	<?php echo $searchResultMainTest['format']; ?>
	</i>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResultMainTest['description'])) {
	?>
	<br />
	<?php 
		$strdescription = $searchResultMainTest['description'];
		echo $strdescription = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strdescription)
	?>
	<br />
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResultMainTest['file']['filename'])) {
	?>
	<br /><b>File type: </b>
	<?php 
		$filetypestring = $searchResultMainTest['file']['filename'];    
		$filetype = substr($filetypestring, strrpos($filetypestring, ".") + 1);    
		echo $filetype; ?>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResultMainTest['file']['content_type'])) {
	?>
	<br /><b>Content type: </b>
	<?php echo $searchResultMainTest['file']['content_type']; ?>
	<?php
	} // END check if empty
?>

<?php
	if (!empty($searchResultMainTest['file']['last_modified'])) {
	?>
  	<br /><b>Last Modified: </b><?php 
  	$date=date_create($searchResultMainTest['file']['last_modified']); 
  	echo date_format($date,"Y-m-d H:i:s"); ?>
	<?php
	} // END check if empty
?>

<?php
	if (!empty($searchResultMainTest['lastModified'])) {
	?>
	  <br /><b>Last Modified: </b><?php echo $searchResultMainTest['lastModified']; ?>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResultMainTest['file']['filesize'])) {
	?>
	<br /><b>Size: </b>
					
	<?php 						
		$bytes = $searchResultMainTest['file']['filesize'];
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
		} 
	} // END check if empty
?>
				
<?php
	if (!empty($searchResultMainTest['meta']['author'])) {
	?>
	<br /><b>Author: </b>
	<?php 
		$strauthor = $searchResultMainTest['meta']['author'];
		echo $strauthor = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strauthor)
	?>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResultMainTest['Outlook_E-mail'])) {
	?>
	<br /><b>Email: </b><a href="mailto:<?php echo $searchResultMainTest['Outlook_E-mail']?>">
	<?php 
		$strOutlook_E_mail = $searchResultMainTest['Outlook_E-mail'];
		echo $strOutlook_E_mail = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_E_mail)
	?></a>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResultMainTest['Outlook_Account'])) {
		$emailDomain = substr($searchResultMainTest['Outlook_E-mail'], -6);
		if ($emailDomain == "epa.ie")
		{
	?>
	<br /><b>Account Username: </b>
	<?php 
		$strOutlook_Account = $searchResultMainTest['Outlook_Account'];
		echo $strOutlook_Account = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_Account)
	?>
	<?php
		} // End check Email domain
	} // END check if empty
?>
				
<?php
	if (!empty($searchResultMainTest['Outlook_Business_Phone'])) {
	?>
	<br /><b>Ext: </b>
	<?php 
		$strOutlook_Outlook_Business_Phone = $searchResultMainTest['Outlook_Business_Phone'];
		echo $strOutlook_Outlook_Business_Phone = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_Outlook_Business_Phone)
	?>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResultMainTest['Outlook_Company']) && !empty($searchResultMainTest['CRM_Organisation'])) {
	?>
	<br /><b>Organisation: </b>
	<?php 
		$strOutlook_Company = $searchResultMainTest['Outlook_Company'];
		echo $strOutlook_Company = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_Company)
	?>,
	<?php 
		$strCRM_Organisation = $searchResultMainTest['CRM_Organisation'];
		echo $strCRM_Organisation = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strCRM_Organisation)
	?>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResultMainTest['Outlook_Company']) && empty($searchResultMainTest['CRM_Organisation'])) {
	?>
	<br /><b>Organisation: </b>
	<?php 
		$strOutlook_Company = $searchResultMainTest['Outlook_Company'];
		echo $strOutlook_Company = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_Company)
	?>
	<?php
	} // END check if empty
?>
				
<?php
	if (empty($searchResultMainTest['Outlook_Company']) && !empty($searchResultMainTest['CRM_Organisation'])) {
	?>
	<br /><b>Organisation: </b>
	<?php 
		$strCRM_Organisation = $searchResultMainTest['CRM_Organisation'];
		echo $strCRM_Organisation = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strCRM_Organisation)
	?>
	<?php
	} // END check if empty
?>


		
<?php
	if (!empty($searchResultMainTest['content'])) {
	?>
	<p class="parContent"><i>
					
					
	<?php 
	$in = $searchResultMainTest['content'];
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
		$strcontentlimit = substr($searchResultMainTest['content'],0,400)." ..."; 
		$strcontent = $strcontentlimit; 
		echo $strcontent = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strcontent);
						
	//$strcontentlimit = substr($searchResultMainTest['content'],0,300)."...";
	//$strcontent = $strcontentlimit;
	//echo $strcontent = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strcontent);
					
	?>					
	</i></p>
	<?php
	} // END check if empty
?>

<?php
	if (empty($searchResultMainTest['content'])) {
	?>
	<br />
	<?php
	} // END check if empty
?>
				
<span style=font-size:10pt>
	<a href="/view.php?id=<?php echo $result['_id']; ?>">More information</a>&nbsp;|
	<?php
		$filelocationstring = $searchResultMainTest['path']['real'];
		$filelocationreplaceExtLink = str_replace('\\', '/', $filelocationstring);
		$filelocationExtLink = substr($filelocationreplaceExtLink, 0, strrpos( $filelocationreplaceExtLink, '/') );
		$filelocationfolder = str_replace('///', '//', $filelocationreplacefull);
	?>
	<a href="<?php echo $filelocationExtLink; ?>" target="_blank">Open file location</a>&nbsp;|
	<a href="<?php echo $filelocationstring; ?>" target="_blank">Open file</a>
</span>
											
</div><!-- END display MAIN SEARCH Results -->
<?php
	} // END foreach loop over results
?>
<br />
				
<!-- Pagination Bottom -->
<?php if($resultsCountMainTest>$page_rows){ ?>
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



<?php /* echo $paramSize; ?> ParamSize
<br />
<?php echo $paramFrom; ?> ParamFrom
<br />
<?php echo $paramLimit; ?> ParamLimit
<br />
<?php echo $pageNum; ?> PageNum
<br />

<p id="message"></p>


<script type="text/javascript">
//JQuery
var $sel = $("#selNumResults");
var value = $sel.val();
var text = $("option:selected",$sel).text(); 
$("#message").html(text);

$('#selNumResults').change(function(){
	var $sel = $("#selNumResults");
	var value = $sel.val();
	var text = $("option:selected",$sel).text(); 
	$("#message").html("your text here");
	$("#message").html("<br />");
	$("#message").html(text);
	//document.write(text);
	//window.location.href = "myphpfile.php?name=" + javascriptVariable;
		
	<?php 
	//$variable ='text';
	$param['size'] = $_GET['selNumResults'];
	$variable = $_GET['selNumResults'];
	//echo $_GET['selNumResults'];
	//echo $variable;
	?>
});
</script>


	<?php 
	//$variable = $_GET['text'];
	echo $variable;
	//echo $_GET['selNumResults'];
	?>
	aaa
	<?php 
	//$variable = $_GET['text'];
	//echo $variable;
	//echo $param['size'];
	echo $_GET['selNumResults'];
*/	?>

<?php //echo $resultscount; ?>