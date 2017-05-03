
<?php
if (count($resultsLEAPComplaint) > 0) {
?>


<table class="table table-hover table-striped table-bordered table-responsive" id="tableLEAPComplaints">
<thead id="THeadSearch">
<tr>
<!-- 
	<th><input type="checkbox" class="chkToggleRefineFileType" id="chkToggleRefineFileType" value="1" onclick="checkCheckboxes(this.id, 'divLEAPDisplayComplaints');" /></th>
	<th><input type="text" class="form-control" placeholder="Case Number" enabled></th>
	<th><input type="text" class="form-control" placeholder="Licence No" enabled></th>
	<th><input type="text" class="form-control" placeholder="Licence Name" enabled></th>
	<th><input type="text" class="form-control" placeholder="Complaint Issue" enabled></th>
	<th><input type="text" class="form-control" placeholder="Date of Occurance" enabled></th>
	<th><input type="text" class="form-control" placeholder="Duration of Occurance" enabled></th>
	<th><input type="text" class="form-control" placeholder="Status" enabled></th>
-->
	<th><input type="checkbox" class="chkToggleRefineFileType" id="chkToggleRefineFileType" value="1" onclick="checkCheckboxes(this.id, 'divLEAPDisplayComplaints');" /></th>
	<th>Case Number</th>
	<th>Licence No</th>
	<th>Licence Name</th>
	<th>Complaint Issue</th>
	<th>Date of Occurance</th>
	<th>Duration of Occurance</th>
	<th>Status</th>
</tr>
</thead>
<tfoot id="TFootSearch">
<tr>
	<th></th>
	<th>Case Number</th>
	<th>Licence No</th>
	<th>Licence Name</th>
	<th>Complaint Issue</th>
	<th>Date of Occurance</th>
	<th>Duration of Occurance</th>
	<th>Status</th>
</tr>
</thead>
<tbody>
<?php
	foreach ($resultsLEAPComplaint as $result) {
		$searchResult = $result['_source'];
	?>
	<tr>
		<td style="width:50px;"><input type="checkbox" id="<?php echo $result['_id']; ?>" /></td>
		<td><a href="/leapViewComplaint.php?id=<?php echo $result['_id']; ?>">
			<?php echo $searchResult['epa_casenumber']; ?></a></td>
		<td><?php echo $searchResult['lema_licenseregnumber']; ?></td>
		<td><?php echo $searchResult['epa_licencename']; ?></td>
		<td><?php echo $searchResult['epa_complaintissuename']; ?></td>
		<td><?php $dateLEAPComplaintDateOfOccurrence = date_create($searchResult['epa_dateofoccurance']);
				  echo date_format($dateLEAPComplaintDateOfOccurrence,"d-M-Y H:i A");  ?></td>
		<td style="width:120px;"><?php echo $searchResult['epa_durationofoccurance']; ?></td>
		<td style="width:120px;"><?php echo $searchResult['statuscodename']; ?></td>
	</tr>
<?php
    } // END foreach loop over results
?>
</tbody>
</table>

<br />
<br />

<button id="cmd" class="btn btn-info btn-xs">Export to Single PDF</button>
&nbsp;
<button id="cmd" class="btn btn-info btn-xs">Export to Individual PDFs</button>

<br />
<br />

<?php
} // END if there are search results

else {
?>
<!--<p>Sorry, nothing found :( Would you like to <a href="/add.php">add</a> a record?</p>-->
  <div class="well">Sorry, nothing found</div>
<?php
} // END elsif there are no search results
?>

			
<!-- Toggle Check Boxes -->	


<script type="text/javascript">
function checkCheckboxes( id, pID ) {
	$('#'+pID).find(':checkbox').each(function() {
		jQuery(this).prop('checked', $('#' + id).is(':checked'));
    });     
}
</script>

<!-- Data Tables scripts -->
<script type="text/javascript">

$(document).ready(function() {
    $('#tableLEAPComplaints tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input id="inputTFootSearch" type="text" placeholder="' + title + '" />' );
    } );
    
    var table = $('#tableLEAPComplaints').DataTable( {
        //lengthChange: false,
        //dom: 'lBrtip',
        //dom: 'Bfrtip',
        dom:
			"<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>",        
        "iDisplayLength": 25,
    	//"stateSave": true,
		language: {
			searchPlaceholder: "Type to filter..."
		},
        
        buttons: [ 
			{ extend: 'colvis', text: 'Show / Hide Columns' }
		],

	} );

    // Apply the search
    table.columns().every( function () {
        var that = this;

        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );

	new $.fn.dataTable.Buttons( table, {
		buttons: [
    		'copyHtml5',
    		'csvHtml5',
    		{
    			text: 'JSON',
    			action: function ( e, dt, button, config ) {
    				var data = dt.buttons.exportData();
       					$.fn.dataTable.fileSave(
    					new Blob( [ JSON.stringify( data ) ] ),
    					'Export.json'
    				);
    			}
    		},
    		'excelHtml5',
    		'pdfHtml5',
    		'print'
		]
	} );

	table.buttons( 1, null ).container().appendTo(
		table.table().container()
	);

} );


</script>


