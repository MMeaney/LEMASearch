<?php
if (count($resultsWeb) > 0) {
?>

<div>


<table class="table table-hover table-striped table-bordered table-responsive" id="tableWebResults">
<thead id="THeadSearch">
<tr>
	<!-- <th>title</th> -->
	<th>url</th>
	<th>domain</th>
	<th>body</th>
</tr>
</thead>
<tfoot id="TFootSearch">
<tr>
	<!-- <th>title</th> -->
	<th>url</th>
	<th>domain</th>
	<th>body</th>
</tr>
</thead>
<tbody>

<?php
	foreach ($resultsWeb as $result) {
		$searchResult = $result['_source'];
	?>
<?php
	if (!empty($searchResult['url'])) {
	?>
		
	<?php $goodURL = str_replace('\r', '', $searchResult['url']); // Parsed to remove erroeneous '\r' suffix from some URLs
	?>
	
	<tr>
		<td style="width:150px; word-wrap:break-word;"><a href ="<?php echo $goodURL; ?>" target="_blank"><?php echo $goodURL; ?></a></td>
		<td style="width:90px; word-wrap:break-word;">
		<script type="text/javascript">		
			var phpGoodURL = "<?php echo $goodURL; ?>";			
			document.write(extractDomain(phpGoodURL));
		</script>
		</td>
		<td style="width:800px;">
		<?php /*foreach($searchResult['body'] as $row) {*/
		   	$in = $searchResult['body'];
		   	$wordToFind = $searchText;
		   	$numWordsToWrap = 10;
		   	
		   	$words = preg_split('/\s+/', $in);
		   	
		   	$found_words    =   preg_grep("/^".$wordToFind.".*/", $words);
		   	$found_pos      =   array_keys($found_words);
		   	if(count($found_pos>0))
		   	{
		   		$pos = $found_pos[0];
		   	}
		   	
		   	if (isset($pos))
		   	{
		   		$start = ($pos - $numWordsToWrap > 0) ? $pos - $numWordsToWrap : 0;
		   		$length = (($pos + ($numWordsToWrap + 1) < count($words)) ? $pos + ($numWordsToWrap + 1) : count($words)) - $start;
		   		$slice = array_slice($words, $start, $length);
		   		//array_push($slice, " xcv ");
		   	
		   		$pre_start  =   ($start > 0) ? "... ":"";
		   		$post_end   =   ($pos + ($numWordsToWrap + 1) < count($words)) ? " ...":"";
		   	
		   		$out = $pre_start.implode(' ', $slice).$post_end;
		   		echo $out = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $out);
		   	}
		   	else
		   		$strcontentlimit = substr($searchResult['body'],0,400)." ...";
		   		$strcontent = $strcontentlimit;
		   		echo $strcontent = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $strcontent);		   	
				//echo $row, '<br />';
			//} // End Foreach
			?>
	</tr>
<?php
		} // END if URL exists
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

$(document).ready(function() {
    $('#tableWebResults tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input id="inputTFootSearch" type="text" placeholder="' + title + '" />' );
    } );
    
    var table = $('#tableWebResults').DataTable( {
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
