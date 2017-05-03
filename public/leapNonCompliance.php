
<?php
if (count($resultsLEAPNonCompliance) > 0) {
?>

<div>
<table class="table table-hover table-striped table-responsive">
<thead>
	<th>Case No</th>
	<th>Licence No</th>
	<th>Type</th>
	<th>Non-Compliance</th>
	<th>Condition</th>
	<th>Non-Compliance Date</th>
	<th>Status</th>
</thead>
<?php
    foreach ($resultsLEAPNonCompliance as $result) {
        $searchResult = $result['_source'];
?>
<tr>
	<td><a href="/viewdatadictionary.php?id=<?php echo $result['_id']; ?>">
		<?php echo $searchResult['lema_casenumber']; ?></a></td>
	<td><?php echo $searchResult['epa_licenceidname']; ?></td>
	<td><?php echo $searchResult['epa_noncompliancetypename']; ?></td>
	<td><?php echo $searchResult['epa_incidentidname']; ?></td>	
	<td><?php echo $searchResult['epa_condition']; ?></td>
	<td><?php $dateLEAPNonComplianceDateOfOccurrence = date_create($searchResult['epa_dateofnoncompliance']);
			  echo date_format($dateLEAPNonComplianceDateOfOccurrence,"d-M-Y H:i A");  ?></td>
	<td><?php echo $searchResult['statecodename']; ?></td>
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
