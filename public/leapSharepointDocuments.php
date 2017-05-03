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
if (count($resultsLEAPSharepointDocuments) > 0) {
?>

<div>


<table class="table table-hover table-striped table-bordered table-responsive" id="tableLEAPSharepointDocs">
<!-- 
<table class="display" id="tableLEAPSharepointDocs">
-->
<thead id="THeadSearch">
<tr>
	<th>Licence</th>
	<th>Document Type</th>
	<th>SHA / Ref No</th>
	<th>File Name</th>
	<th>Organisation</th>
	<th>Classification</th>
	<th>Category</th>
	<th>Sub-Category</th>	
	<th>Date Modified</th>
</tr>
</thead>
<tfoot id="TFootSearch">
<tr>
	<th>Licence</th>
	<th>Document Type</th>
	<th>SHA / Ref No</th>
	<th>File Name</th>
	<th>Organisation</th>
	<th>Classification</th>
	<th>Category</th>
	<th>Sub-Category</th>	
	<th>Date Modified</th>
</tr>
</thead>
<tbody>
<?php
    foreach ($resultsLEAPSharepointDocuments as $result) {
        $searchResult = $result['_source'];
        $searchResultLEAPDocURL = $searchResult['document url']
?>
<tr>
	<td id="tdLEAPRegNo"><?php 
		$searchResult['server relative url'] = ' ' . $searchResult['server relative url'];
		$ini = strpos($searchResult['server relative url'], 'Licences/');
		if ($ini == 0) return '';
		$ini += strlen('Licences/');
		$len = strpos($searchResult['server relative url'], '/', $ini) - $ini;
		echo substr($searchResult['server relative url'], $ini, $len);
	?>
	</td>
	<td id="tdLEAPFileType"><?php		
		switch ($searchResultLEAPDocURL){
			case (strpos($searchResultLEAPDocURL, 'Licensee Returns')!==false): $displayDocumentType = "Licensee Returns"; break;
			case (strpos($searchResultLEAPDocURL, 'SiteVisit')!==false): $displayDocumentType = "Site Visit"; break;
			case (strpos($searchResultLEAPDocURL, 'Complaints')!==false): $displayDocumentType = "Complaints"; break;
			case (strpos($searchResultLEAPDocURL, 'IED')!==false): $displayDocumentType = "IED"; break;
			case (strpos($searchResultLEAPDocURL, 'Compliance Investigation')!==false): $displayDocumentType = "Compliance Investigation"; break;
			case (strpos($searchResultLEAPDocURL, 'Water Monitoring Report')!==false): $displayDocumentType = "Water Monitoring Report"; break;
			case (strpos($searchResultLEAPDocURL, 'Odour Assessment')!==false): $displayDocumentType = "Odour Assessment"; break;
			case (strpos($searchResultLEAPDocURL, 'Incident')!==false): $displayDocumentType = "Incident"; break;
			case (strpos($searchResultLEAPDocURL, 'Water Analysis')!==false): $displayDocumentType = "Water Analysis"; break;
			case (strpos($searchResultLEAPDocURL, 'Water analysis')!==false): $displayDocumentType = "Water analysis"; break;
			case (strpos($searchResultLEAPDocURL, 'SHA')!==false): $displayDocumentType = "SHA"; break;
			case (strpos($searchResultLEAPDocURL, 'Decision')!==false): $displayDocumentType = "Decision"; break;
			case (strpos($searchResultLEAPDocURL, 'DraftLicenceXml')!==false): $displayDocumentType = "Draft Licence XML"; break;
			case (strpos($searchResultLEAPDocURL, 'Submissions')!==false): $displayDocumentType = "Submissions"; break;
			case (strpos($searchResultLEAPDocURL, 'xxx')!==false): $displayDocumentType = "xxx"; break;
			case (strpos($searchResultLEAPDocURL, 'xxx')!==false): $displayDocumentType = "xxx"; break;
			
		}
		echo $displayDocumentType;
		?>
	</td>
	<td id="tdLEAPFileType"><?php		
	if (strpos($searchResultLEAPDocURL, 'SHA')!==false){
		switch ($searchResultLEAPDocURL){
			case (strpos($searchResultLEAPDocURL, 'Screening Determination')!==false): $displaySHAType = "Screening Determination"; break;
			case (strpos($searchResultLEAPDocURL, 'Recommended Decision to Director')!==false): $displaySHAType = "Recommended Decision to Director"; break;
			case (strpos($searchResultLEAPDocURL, 'Recommended Decision')!==false): $displaySHAType = "Recommended Decision"; break;
			case (strpos($searchResultLEAPDocURL, 'Final Decision')!==false): $displaySHAType = "Final Decision"; break;
			case (strpos($searchResultLEAPDocURL, 'RD and IR')!==false): $displaySHAType = "RD and IR"; break;
			case (strpos($searchResultLEAPDocURL, 'Unsolicited Correspondence')!==false): $displaySHAType = "Unsolicited Correspondence"; break;
			case (strpos($searchResultLEAPDocURL, 'Reg')!==false): $displaySHAType = "Regulation"; break;
			case (strpos($searchResultLEAPDocURL, 'xxx')!==false): $displaySHAType = "xxx"; break;
			case (strpos($searchResultLEAPDocURL, 'xxx')!==false): $displaySHAType = "xxx"; break;
			
		}
		echo $displaySHAType;
    } // END Check If SHA
    
    
	if (strpos($searchResultLEAPDocURL, 'Complaints')!==false){
		$from = "Complaints/";
		$to	  = "/";
		$leapDocComplaintSub = substr($searchResultLEAPDocURL, strpos($searchResultLEAPDocURL, $from) + strlen($from),strlen($searchResultLEAPDocURL));
		$leapDocComplaint 	 = substr($leapDocComplaintSub,0,strpos($leapDocComplaintSub, $to));
		echo $leapDocComplaint;
    } // END Check If Complaint
    
    
	if (strpos($searchResultLEAPDocURL, 'Compliance Investigation')!==false){
		$from = "Investigations/";
		$to	  = "/";
		$leapDocCISub = substr($searchResultLEAPDocURL, strpos($searchResultLEAPDocURL, $from) + strlen($from),strlen($searchResultLEAPDocURL));
		$leapDocCI 	  = substr($leapDocCISub,0,strpos($leapDocCISub, $to));
		echo $leapDocCI;
    } // END Check If Compliance Investigation
    
    
	if (strpos($searchResultLEAPDocURL, 'SiteVisit')!==false){
		$from = "SiteVisit/";
		$to	  = "/";
		$leapDocSVSub = substr($searchResultLEAPDocURL, strpos($searchResultLEAPDocURL, $from) + strlen($from),strlen($searchResultLEAPDocURL));
		$leapDocSV 	  = substr($leapDocSVSub,0,strpos($leapDocSVSub, $to));
		echo $leapDocSV;
    } // END Check If SiteVisit
    
    
	if (strpos($searchResultLEAPDocURL, 'Incident')!==false){
		$from = "Incidents/";
		$to	  = "/";
		$leapDocINCISub = substr($searchResultLEAPDocURL, strpos($searchResultLEAPDocURL, $from) + strlen($from),strlen($searchResultLEAPDocURL));
		$leapDocINCI	= substr($leapDocINCISub,0,strpos($leapDocINCISub, $to));
		echo $leapDocINCI;
    } // END Check If Incident
    
    
	if (strpos($searchResultLEAPDocURL, 'Licensee Returns')!==false){
		$from = "Licensee Returns/";
		$to	  = "/";
		$leapDocLRSub = substr($searchResultLEAPDocURL, strpos($searchResultLEAPDocURL, $from) + strlen($from),strlen($searchResultLEAPDocURL));
		$leapDocLR	= substr($leapDocLRSub,0,strpos($leapDocLRSub, $to));
		echo $leapDocLR;
    } // END Check If Licensee Returns
    
    
    
	?>
	</td>
	
	
	
	
	<td id="tdLEAPFilename"><a target="_blank" href="<?php echo $searchResult['document url']; ?>"><?php echo $searchResult['name (linkfilenamenomenu)'/*'document url'*/]; ?></a></td>
	<td id="tdLEAPOrganisation"><?php echo $searchResult['local authority']; ?></td>
	<td id="tdLEAPDocClassification"><?php echo $searchResult['fileclassification']; ?></td>
	<td id="tdLEAPDocCategory"><?php echo $searchResult['category']; ?></td>
	<td id="tdLEAPDocSubCategory"><?php echo $searchResult['sub-category']; ?></td>
	<td id="tdLEAPDate"><?php $dateLEAPSharepointDocumentsDateModified = date_create($searchResult['modified (modified)']);
			  echo date_format($dateLEAPSharepointDocumentsDateModified,"d-M-Y H:i");  ?></td>
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
  <div class="well">Sorry, nothing found</div>
<?php
} // END elseif there are no search results
?>

<br />

<script type="text/javascript">
//<!--

/*
$(document).ready( function() {
	$('#tableLEAPSharepointDocs').dataTable( {
		"iDisplayLength": 50
	} );
} );
//*/

/*

$(document).ready(function() {
	$('#tableLEAPSharepointDocs').DataTable( {
		//"dom": '<"top"i>rt<"bottom"flp><"clear">',
		"iDisplayLength": 25,
		//buttons: ['copy', 'excel', 'pdf']
		
	} );
} );
//*/



$(document).ready(function() {
    $('#tableLEAPSharepointDocs tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input id="inputTFootSearch" type="text" placeholder="' + title + '" />' );
    } );
    
    var table = $('#tableLEAPSharepointDocs').DataTable( {
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
	table.buttons().container()
		//.appendTo( '#tableLEAPSharepointDocs_wrapper .col-sm-6:eq(0)' );
    	.appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );

    //table
    	//"iDisplayLength": 50 ;

//*/
/*

} );


//*/



/*

$(document).ready(function (){
    var table = $('#tableLEAPSharepointDocs').DataTable({
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
	$('#tableLEAPSharepointDocs').dataTable( {
		
	} );
} );

//*/

/*
$(document).ready(function() {

	$('#tableLEAPSharepointDocs')
		.on( 'order.dt',  function () { console.log('Order' ); } )
		.on( 'search.dt', function () { console.log('Search' ); } )
		.on( 'page.dt',   function () { console.log('Page' ); } )
		.dataTable();
} );


//*/







</script>
