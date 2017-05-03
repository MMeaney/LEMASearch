<?php
if (count($results) > 0) {
?>
<table class="table table-hover table-striped table-responsive">
<thead>
	<th>Database</th>
	<th>Table</th>
	<th>Column</th>
	<th>Column Description</th>
	<th>Data Type</th>
	<th>Size</th>
	<th>FK Dependencies</th>
	<th>System</th>
	<th>Server</th>
</thead>
<?php
    foreach ($results as $result) {
        $searchresult = $result['_source'];
?>
<tr>
	<td><a href="/viewdatadictionary.php?id=<?php echo $result['_id']; ?>">
		<?php echo $searchresult['databasename']; ?></a></td>
	<td><?php echo $searchresult['title']; ?></td>
	<td><?php echo $searchresult['columnname']; ?></td>
	<td><?php echo $searchresult['description']; ?></td>
	<td><?php echo $searchresult['datatype']; ?></td>
	<td><?php echo $searchresult['size']; ?></td>
	<td><?php echo $searchresult['fkdependencies']; ?></td>
	<td><?php echo $searchresult['system']; ?></td>
	<td><?php echo $searchresult['servername']; ?></td>
</tr>
<?php
    } // END foreach loop over results
?>
</table>
<?php
} // END if there are search results

else {
?>
<!--<p>Sorry, nothing found :( Would you like to <a href="/add.php">add</a> a record?</p>-->
  <div class="well">Sorry, nothing found</div>
<?php

} // END elsif there are no search results

?>
