
<?php
if (count($resultsLEAPComplianceInvestigation) > 0) {
?>

<div>
<table class="table table-hover table-striped table-responsive">
<thead>
	<th>Case No</th>
	<th>Licence No</th>
	<th>Issue</th>
	<th>Response Level</th>
	<th>Status</th>
	<th>Closed Date</th>
	<th>Description</th>
</thead>
<?php
    foreach ($resultsLEAPComplianceInvestigation as $result) {
        $searchResult = $result['_source'];
?>
<tr>
	<td><a href="/viewdatadictionary.php?id=<?php echo $result['_id']; ?>">
		<?php echo $searchResult['epa_casenumber']; ?></a></td>
	<td><?php echo $searchResult['lema_licenseregnumber']; ?></td>
	<td><?php echo $searchResult['epa_complianceinvestigationissuename']; ?></td>
	<td><?php echo $searchResult['epa_responselevelname']; ?></td>
	<td><?php echo $searchResult['statuscodename']; ?></td>
	<td><?php $dateLEAPNonComplianceInvestigationClosedDate = date_create($searchResult['epa_closedate']);
			  echo date_format($dateLEAPNonComplianceInvestigationClosedDate,"d-M-Y H:i A");  ?></td>
	<td width="60%"><?php echo $searchResult['epa_description']; ?></td>
</tr>
<?php
    } // END foreach loop over results
?>
</table>
</div>
<?php
} // END if there are search results

else {
?>
<!--<p>Sorry, nothing found :( Would you like to <a href="/add.php">add</a> a record?</p>-->
  <div class="well">Sorry, nothing found</div>
<?php
} // END elsif there are no search results
?>
