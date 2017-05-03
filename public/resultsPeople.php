<!-- Display PEOPLE Results -->

<?php
if (count($resultsPeople) > 0) {
?>

<div><!-- BEGIN loop to display NETWORK FILE Results -->
				
<ul class="list-group" id="results">
				
<?php
	foreach ($resultsPeople as $result) {
		$searchResult = $result['_source'];
?>			
				
<div><!-- BEGIN display PEOPLE Results -->
<br />
<li class="list-group-item" id="li-results"<?php //echo $filetype; ?>">
				
<?php
	if (!empty($searchResult['Outlook_Full_Name'])) { ?>
	<span id="spanResultsFilename">
	<?php
	$strOutlook_Full_Name = $searchResult['Outlook_Full_Name'];
	echo $strOutlook_Full_Name = preg_replace("/\w*?$searchText\w*/i", "<i>$0</i>", $strOutlook_Full_Name)
	?></span>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['Outlook_E-mail'])) {
	?>
	<br /><b>Email: </b><a href="mailto:<?php echo $searchResult['Outlook_E-mail']?>">
	<?php 
		$strOutlook_E_mail = $searchResult['Outlook_E-mail'];
		echo $strOutlook_E_mail = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_E_mail)
	?></a>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['Outlook_Account'])) {
		$emailDomain = substr($searchResult['Outlook_E-mail'], -6);
		if ($emailDomain == "epa.ie")
		{
	?>
	<br /><b>Account Username: </b>
	<?php 
		$strOutlook_Account = $searchResult['Outlook_Account'];
		echo $strOutlook_Account = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_Account)
	?>
	<?php
		} // End check Email domain
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['Outlook_Business_Phone'])) {
	?>
	<br /><b>Ext: </b>
	<?php 
		$strOutlook_Outlook_Business_Phone = $searchResult['Outlook_Business_Phone'];
		echo $strOutlook_Outlook_Business_Phone = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_Outlook_Business_Phone)
	?>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['Outlook_Company']) && !empty($searchResult['CRM_Organisation'])) {
	?>
	<br /><b>Organisation: </b>
	<?php 
		$strOutlook_Company = $searchResult['Outlook_Company'];
		echo $strOutlook_Company = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_Company)
	?>,
	<?php 
		$strCRM_Organisation = $searchResult['CRM_Organisation'];
		echo $strCRM_Organisation = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strCRM_Organisation)
	?>
	<?php
	} // END check if empty
?>
				
<?php
	if (!empty($searchResult['Outlook_Company']) && empty($searchResult['CRM_Organisation'])) {
	?>
	<br /><b>Organisation: </b>
	<?php 
		$strOutlook_Company = $searchResult['Outlook_Company'];
		echo $strOutlook_Company = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strOutlook_Company)
	?>
	<?php
	} // END check if empty
?>
				
<?php
	if (empty($searchResult['Outlook_Company']) && !empty($searchResult['CRM_Organisation'])) {
	?>
	<br /><b>Organisation: </b>
	<?php 
		$strCRM_Organisation = $searchResult['CRM_Organisation'];
		echo $strCRM_Organisation = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strCRM_Organisation)
	?>
	<?php
	} // END check if empty
?>

<br /><b>Location: </b>					

<?php
	if (empty($searchResult['Outlook_Office_Location']) 
		and empty($searchResult['CRM_City']) 
		and empty($searchResult['CRM_Country']) 
		and empty($searchResult['CRM_County']) 
		and empty($searchResult['CRM_Street_1']) 
		and empty($searchResult['CRM_Street_2']) 
		and empty($searchResult['CRM_Street_3']) 
		and empty($searchResult['CRM_Postal_Code'])) {
	?>
<i>No location details available</i>
	<?php
	} // END check if empty
?>
			
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
	if (!empty($searchResult['Outlook_Account'])) {
		$outlookAccount = $searchResult['Outlook_Account'];
		$emailDomain = substr($searchResult['Outlook_E-mail'], -6);
		if ($emailDomain == "epa.ie")
		{
			?>
	<br /><b>SharePoint Profile: </b>
	<a target="_blank" href="http://epamysites/Person.aspx?accountname=EPA%5C<?php echo $outlookAccount; ?>">
		http://epamysites/Person.aspx?accountname=EPA%5C<?php echo $outlookAccount; ?></a>
		<br />
		<br />
		
		<iframe width=1100 height=850 src="http://epamysites/Person.aspx?accountname=EPA%5C<?php echo $outlookAccount; ?>#ms-profilepageheader"></iframe>
	<?php 
			//} // END Check if SharePoint profile exists
		} // END check Email domain
	} // END check if empty
?>

</li>
				
<br />
				
</div><!-- END display USER Results -->
<?php
	} // END foreach loop over results
?>
</ul>
</div><!-- END loop to display USER Results -->
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
