<!-- Display Research Results -->

<?php
if (count($resultsResearch) > 0) {
?>

<div><!-- BEGIN loop to display NETWORK FILE Results -->
				
<ul class="list-group" id="results">
				
<?php
	foreach ($resultsResearch as $result) {
		$searchResult = $result['_source'];
?>			
				
<div><!-- BEGIN display Research Results -->
<br />
<li class="list-group-item" id="li-results"<?php //echo $filetype; ?>">
		
<table>	
<thead>
<?php if (!empty($searchResult['ResourceTitle'])) { ?> 

<a href="/view.php?id=<?php echo $result['_id']; ?>"><span id="spanResultsFilename"><?php echo $searchResult['ResourceTitle'];?></span></a><?php } // END check if empty ?>
</thead>
<tbody>	
<?php if (!empty($searchResult['Generator'])) { ?> <tr><td> <span id="spanMetaTypeBold">Generator:&nbsp; </span></td><td><span id="spanMetaContent"><?php echo $searchResult['Generator'];?></span></td></tr><?php } // END check if empty ?>
<?php if (!empty($searchResult['Organisation'])) { ?> <tr><td> <span id="spanMetaTypeBold">Organisation:&nbsp; </span></td><td><span id="spanMetaContent"><?php echo $searchResult['Organisation'];?></span></td></tr><?php } // END check if empty ?>
<?php if (!empty($searchResult['GenerationDate'])) { ?> <tr><td> <span id="spanMetaTypeBold">GenerationDate:&nbsp; </span></td><td><span id="spanMetaContent"><?php echo $searchResult['GenerationDate'];?></span></td></tr><?php } // END check if empty ?>
<?php if (!empty($searchResult['License'])) { ?> <tr><td> <span id="spanMetaTypeBold">License:&nbsp; </span></td><td><span id="spanMetaContent"><?php echo $searchResult['License'];?></span></td></tr><?php } // END check if empty ?>
<?php if (!empty($searchResult['ResourceID'])) { ?> <tr><td> <span id="spanMetaTypeBold">ResourceID:&nbsp; </span></td><td><span id="spanMetaContent"><?php echo $searchResult['ResourceID'];?></span></td></tr><?php } // END check if empty ?>
<?php if (!empty($searchResult['ResourceCode'])) { ?> <tr><td> <span id="spanMetaTypeBold">ResourceCode:&nbsp; </span></td><td><span id="spanMetaContent"><?php echo $searchResult['ResourceCode'];?></span></td></tr><?php } // END check if empty ?>
<?php if (!empty($searchResult['ResourceCreated'])) { ?> <tr><td> <span id="spanMetaTypeBold">ResourceCreated:&nbsp; </span></td><td><span id="spanMetaContent"><?php echo $searchResult['ResourceCreated'];?></span></td></tr><?php } // END check if empty ?>
<?php if (!empty($searchResult['ResourceLastUpdated'])) { ?> <tr><td> <span id="spanMetaTypeBold">ResourceLastUpdated:&nbsp; </span></td><td><span id="spanMetaContent"><?php echo $searchResult['ResourceLastUpdated'];?></span></td></tr><?php } // END check if empty ?>
<?php if (!empty($searchResult['ResourceKeywords'])) { ?> <tr><td> <span id="spanMetaTypeBold">ResourceKeywords:&nbsp; </span></td><td><span id="spanMetaContent"><?php echo $searchResult['ResourceKeywords'];?></span></td></tr><?php } // END check if empty ?>
<?php if (!empty($searchResult['ResourceLinks'])) { ?> <tr><td> <span id="spanMetaTypeBold">ResourceLinks:&nbsp; </span></td><td><span id="spanMetaContent"><?php echo $searchResult['ResourceLinks'];?></span></td></tr><?php } // END check if empty ?>
<?php if (!empty($searchResult['TotalNumberOfAttachments'])) { ?> <tr><td> <span id="spanMetaTypeBold">TotalNumberOfAttachments:&nbsp; </span></td><td><span id="spanMetaContent"><?php echo $searchResult['TotalNumberOfAttachments'];?></span></td></tr><?php } // END check if empty ?>
<?php if (!empty($searchResult['TotalNumberOfPublicAttachments'])) { ?> <tr><td> <span id="spanMetaTypeBold">TotalNumberOfPublicAttachments:&nbsp; </span></td><td><span id="spanMetaContent"><?php echo $searchResult['TotalNumberOfPublicAttachments'];?></span></td></tr><?php } // END check if empty ?>
<?php if (!empty($searchResult['TotalNumberOfPDFAttachments'])) { ?> <tr><td> <span id="spanMetaTypeBold">TotalNumberOfPDFAttachments:&nbsp; </span></td><td><span id="spanMetaContent"><?php echo $searchResult['TotalNumberOfPDFAttachments'];?></span></td></tr><?php } // END check if empty ?>

</tbody>
</table>


</li>
				
<br />
				
</div><!-- END display NETWORK FILE Results -->
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
<?php echo json_encode($paramResearch['body'], JSON_PRETTY_PRINT);
?>
</code>
</pre>
