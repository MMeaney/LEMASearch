<!-- 
<div>
<table class="table">
        <thead>
            <tr>
                <th class="dropdown">
                   <a href="#" data-toggle="dropdown" class="dropdown-toggle">Messages <b class="caret"></b></a>
                   <ul class="dropdown-menu">
                      <li><a href="#">Inbox</a></li>
                      <li><a href="#">Drafts</a></li>
                      <li><a href="#">Sent Items</a></li>
                      <li class="divider"></li>
                      <li><a href="#">Trash</a></li>
                   </ul>
                </th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
            </tr>
        </thead>
</table>
</div>
 -->



<?php
if (count($resultsPRTRMeasurements) > 0) {
?>

<div>


<table class="table table-hover table-striped table-bordered table-responsive filterable" id="tablePRTRMeasurements">
<!-- 
<table class="display" id="tablePRTRMeasurements">
-->
<thead id="inputTHeadSearch">
<tr>
	<th>Licence</th>
	<th>Location</th>
	<th>Sample Code</th>
	<th>Parameter</th>
	<th>Result</th>
	<th>Verified</th>
	<th>Measurement Date</th>	
</tr>
</thead>
<tfoot id="TFootSearch">
<tr>
	<th>Licence</th>
	<th>Location</th>
	<th>Sample Code</th>
	<th>Parameter</th>
	<th>Result</th>
	<th>Verified</th>
	<th>Measurement Date</th>	
</tr>
</tfoot>
<tbody>
<?php
    foreach ($resultsPRTRMeasurements as $result) {
        $searchResult = $result['_source'];
?>
<tr>
	<td style="width:55px; "><?php echo $searchResult['monitoredentitycode']; ?></td>
	<td style="width:230px;"><?php echo $searchResult['monitoredlocationcode']; ?></td>
	<td style="width:300px;"><?php echo $searchResult['sampleid']; ?></td>
	<td style="width:330px;"><?php echo $searchResult['parameterdescription']; ?></td>
	<td style="width:60px; "><?php echo $searchResult['numericresult']; ?></td>
	<td style="width:60px; "><?php 
	switch ($searchResult['verifiedstatus']){
		case (strpos($searchResult['verifiedstatus'], '1')!==false): $verifiedstatus = "Yes"; break;
		case (strpos($searchResult['verifiedstatus'], '0')!==false): $verifiedstatus = "No"; break;
	}
	echo $verifiedstatus;
	?>
	</td>
	<td style="width:120px;"><?php $datePRTRMeasurementDate = date_create($searchResult['measurementdate']);
			  echo date_format($datePRTRMeasurementDate,"d-M-Y H:i A");  ?></td>
</tr>
<?php
    } // END foreach loop over results
?>
</tbody>
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

<br />

<script type="text/javascript">
//<!--

/*
$(document).ready( function() {
	$('#tablePRTRMeasurements').dataTable( {
		"iDisplayLength": 50
	} );
} );
//*/

/*

$(document).ready(function() {
	$('#tablePRTRMeasurements').DataTable( {
		//"dom": '<"top"i>rt<"bottom"flp><"clear">',
		"iDisplayLength": 25,
		//buttons: ['copy', 'excel', 'pdf']
		
	} );
} );
//*/


///*
$(document).ready(function() {
    $('#tablePRTRMeasurements tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input id="inputTFootSearch" type="text" placeholder="' + title + '" />' );
    } );

    var table = $('#tablePRTRMeasurements').DataTable( {
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

//*/


/*

$(document).ready(function() {
    // Setup - add a text input to each footer cell
    $('#tablePRTRMeasurements tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="' + title + '" />' );
    } );

    // DataTable
    var table = $('#tablePRTRMeasurements').DataTable(); 

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
} );
//*/


/*
	table.buttons().container()
		//.appendTo( '#tablePRTRMeasurements_wrapper .col-sm-6:eq(0)' );
    	.appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );

    //table
    	//"iDisplayLength": 50 ;

//*/
/*

} );


//*/



/*

$(document).ready(function (){
    var table = $('#tablePRTRMeasurements').DataTable({
	    dom:
			"<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>", 
        buttons: [
            {
                text: 'Btn1',
                action: function ( e, dt, node, config ) {
                    alert( 'Button activated' );
                }
            },
            {
                text: 'Btn2',
                action: function ( e, dt, node, config ) {
                    alert( 'Button activated' );
                }
            }
            
        ]
    });
});


//*/


/*
$(document).ready( function() {
	$('#tablePRTRMeasurements').dataTable( {
		
	} );
} );

//*/

/*
$(document).ready(function() {

	$('#tablePRTRMeasurements')
		.on( 'order.dt',  function () { console.log('Order' ); } )
		.on( 'search.dt', function () { console.log('Search' ); } )
		.on( 'page.dt',   function () { console.log('Page' ); } )
		.dataTable();
} );


//*/







</script>
