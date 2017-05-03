
<?php
if (count($resultsLEAPLicence) > 0) {
?>

<div>
<table class="table table-hover table-striped table-responsive">
<thead>
	<th>Licence No</th>
	<th>Organisation</th>
	<th>Licence Name</th>
	<th>Licence Status</th>
	<th>Licence Type</th>
	<th>Licence Date</th>
</thead>
<?php
    foreach ($resultsLEAPLicence as $result) {
        $searchResult = $result['_source'];
?>
<tr>
	<td><a href="/leapViewLicence.php?id=<?php echo $result['_id']; ?>">
		<?php echo $searchResult['epa_regno']; ?></a></td>
	<td><?php echo $searchResult['epa_waterservicesauthorityname']; ?></td>
	<td><?php echo $searchResult['epa_name']; ?></td>
	<td><?php echo $searchResult['epa_licencestatusname']; ?></td>
	<td><?php echo $searchResult['epa_licencetypename']; ?></td>
	<td><?php $dateLEAPDateOfLicence = date_create($searchResult['epa_dateoflicence']);
			  echo date_format($dateLEAPDateOfLicence,"d-M-Y H:i A");  ?></td>
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
