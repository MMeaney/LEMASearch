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
if (count($resultsCRMDataDictionary) > 0) {
?>

<div>


<table class="table table-hover table-striped table-bordered table-responsive filterable" id="tableCRMDataDictionary">
<!-- 
<table class="display" id="tableCRMDataDictionary">
-->
<thead id="inputTHeadSearch">
<tr>
	<th>schemaname</th>
	<th>logicalname</th>
	<th>displayname</th>
	<!-- <th>pluraldisplayname</th> -->
	<th>system</th>
	<th>description</th>
	<th>metadatalastmodified</th>	
</tr>
</thead>
<tfoot id="TFootSearch">
<tr>
	<th>schemaname</th>
	<th>logicalname</th>
	<th>displayname</th>
	<!-- <th>pluraldisplayname</th> -->
	<th>system</th>
	<th>description</th>
	<th>metadatalastmodified</th>		
</tr>
</tfoot>
<tbody>
<?php
    foreach ($resultsCRMDataDictionary as $result) {
        $searchResult = $result['_source'];
?>
<tr>
	<td style="width:155px;"><?php echo $searchResult['schemaname']; ?></td>
	<td style="width:130px;"><?php echo $searchResult['logicalname']; ?></td>
	<td style="width:130px;"><?php echo $searchResult['displayname']; ?></td>
	<!-- <td style="width:130px;"><? /*php echo $searchResult['pluraldisplayname']; */ ?></td> -->
	<td style="width:120px;"><?php echo $searchResult['systemdescription']; ?></td>
	<td style="width:400px;"><?php echo $searchResult['description']; ?></td>
	<td style="width:100px;"><?php $dateCRMMetaDataLastModified = date_create($searchResult['metadatalastmodified']);
			  echo date_format($dateCRMMetaDataLastModified,"d-M-Y H:i A");  ?></td>
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
	$('#tableCRMDataDictionary').dataTable( {
		"iDisplayLength": 50
	} );
} );
//*/

/*

$(document).ready(function() {
	$('#tableCRMDataDictionary').DataTable( {
		//"dom": '<"top"i>rt<"bottom"flp><"clear">',
		"iDisplayLength": 25,
		//buttons: ['copy', 'excel', 'pdf']
		
	} );
} );
//*/


///*
$(document).ready(function() {
    $('#tableCRMDataDictionary tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input id="inputTFootSearch" type="text" placeholder="' + title + '" />' );
    } );

    var table = $('#tableCRMDataDictionary').DataTable( {
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
    $('#tableCRMDataDictionary tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="' + title + '" />' );
    } );

    // DataTable
    var table = $('#tableCRMDataDictionary').DataTable(); 

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
		//.appendTo( '#tableCRMDataDictionary_wrapper .col-sm-6:eq(0)' );
    	.appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );

    //table
    	//"iDisplayLength": 50 ;

//*/
/*

} );


//*/



/*

$(document).ready(function (){
    var table = $('#tableCRMDataDictionary').DataTable({
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
	$('#tableCRMDataDictionary').dataTable( {
		
	} );
} );

//*/

/*
$(document).ready(function() {

	$('#tableCRMDataDictionary')
		.on( 'order.dt',  function () { console.log('Order' ); } )
		.on( 'search.dt', function () { console.log('Search' ); } )
		.on( 'page.dt',   function () { console.log('Page' ); } )
		.dataTable();
} );


//*/







</script>
