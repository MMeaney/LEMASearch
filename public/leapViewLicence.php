<?php

error_reporting(0);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require __DIR__ . '/../vendor/autoload.php';

//use RecipeSearch\Constants;
use Elasticsearch\Common\Exceptions\Missing404Exception;

//$message = $_REQUEST['message'];

// Check if ID was provided
if (empty($_REQUEST['id'])) {
    //$message = 'Nothing requested! Please provide an ID.';
	'Nothing requested! Please provide an ID.';
} else {
    // Connect to Elasticsearch (1-node cluster)
    $esPort = getenv('APP_ES_PORT') ?: 9200;
    $client = new Elasticsearch\Client([
        'hosts' => [ 'localhost:' . $esPort ]
    ]);

    // Try to get result from Elasticsearch
    try {
        $searchResult = $client->get([
            'id'    => $_REQUEST['id'],
            'index' => "leap2",
            'type'  => "leap_licence"
        ]);
        $searchResult = $searchResult['_source'];
    } catch (Missing404Exception $e) {
        //$message = 'Requested record not found';
        'Requested record not found';
    }
}
?>

<!DOCTYPE html>
<html itemscope itemtype="https://schema.org/SearchResultsPage">
<head>

<meta charset="utf-8">
<meta name="viewport" 	content="width=device-width, height=device-height, initial-scale=1" />  
<meta name="DC.Title" 	content="Data Catalogue - Environmental Protection Agency (EPA)" /> 
<meta name="DC.title" 	content="Data Catalogue - Environmental Protection Agency (EPA)" />
<meta name="DC.Creator" 	content="Environmental Protection Agency (EPA)" />
<meta name="DC.Publisher"	content="Environmental Protection Agency (EPA)" /> 
<meta name="DC.Format" 	content="text/xhtml" /> 
<meta name="DC.Copyright"	content="All material (c) copyright 2016 Environmental Protection Agency (EPA)" /> 
<meta name="DC.Source" 	content="Environmental Protection Agency (EPA)" /> 
<meta name="DC.Language"	content="en" />
<meta name="DC.Author" 	content="Environmental Protection Agency (EPA)" />
<!-- <meta name="viewport" 	content="width=device-width, shrink-to-fit=no, initial-scale=1"> -->

<link rel="icon" href="http://epa.ie/media/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="http://epa.ie/media/favicon.ico" />

<link rel="stylesheet" type="text/css" href="./dist/bootstrap-3.3.7/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="./css/Site.css" /> 

<!-- 
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.0-beta.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.1.135/jspdf.min.js"></script>
<script type="text/javascript" src="http://cdn.uriit.ru/jsPDF/libs/adler32cs.js/adler32cs.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2014-11-29/FileSaver.min.js"></script>
<script type="text/javascript" src="./js/BlobBuilder.js"></script>
<script type="text/javascript" src="http://cdn.immex1.com/js/jspdf/plugins/jspdf.plugin.addimage.js"></script>
<script type="text/javascript" src="http://cdn.immex1.com/js/jspdf/plugins/jspdf.plugin.standard_fonts_metrics.js"></script>
<script type="text/javascript" src="http://cdn.immex1.com/js/jspdf/plugins/jspdf.plugin.split_text_to_size.js"></script>
<script type="text/javascript" src="http://cdn.immex1.com/js/jspdf/plugins/jspdf.plugin.from_html.js"></script>
 -->


<title>LEAP Licence <?php echo $searchResult['epa_regno'] ?></title>

</head>

<body id="target">
<div id="content">


<div class="container-fluid">

<div class="container">
<span id="spanLeapViewerTitle">
	Licence Details Report</span>
<span class="pull-right"><img id="imgLeapLogo" src=".\img\EPA_logo.gif"></span>

<br />
<br />

<div class="row">
	<div class="col-sm-2"><font size="3"><b>Licence Number:</b></font></div>
	<div class="col-sm-6"><font size="3"><?php echo $searchResult['epa_regno']; ?></font></div>
</div>

<br />

<h4 id="leapReportDelimiter">Licence Details</h4>


<div class="row">
	<div class="col-sm-2"><b>Licensee Name:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_accountidname']; ?></div>
	<div class="col-sm-2"><b>Licence Reg No:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_regno']; ?></div>
</div>

<div id="divSpacer" ></div>

<div class="row">
	<div class="col-sm-2"><b>Licence Name:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_name']; ?></div>
	<div class="col-sm-2"><b>Licence Type:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_licencetypename']; ?></div>
</div>

<div id="divSpacer" ></div>


<div class="row">
	<div class="col-sm-2"><b>Licence Status:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_licencestatusname']; ?></div>
	<div class="col-sm-2"><b>Licence Type:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['lema_sectorname']; ?></div>
</div>

<br />


<h4 id="leapReportDelimiter">Address Details</h4>

<div id="divSpacer" ></div>

<div class="row">
	<div class="col-sm-2"><b>Licencee Address:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_address1']; ?></div>
	<div class="col-sm-2"><b>Telephone No:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_complainanttelephonenumber']; ?></div>
</div>

<div id="divSpacer" ></div>

<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_address2']; ?></div>
	<div class="col-sm-2"><b>Mobile No:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_complainantmobilephoneno']; ?></div>
</div>

<div id="divSpacer" ></div>

<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_address3']; ?></div>
	<div class="col-sm-2"><b>Mobile No:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_complainantmobilephoneno']; ?></div>
</div>

<div id="divSpacer" ></div>

<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-4"><?php 
	if(!empty($searchResult['epa_countyname'])){
		echo $searchResult['epa_countyname'];
	}
	else {
		echo $searchResult['epa_complainanttown'];
	}	
	?></div>
	<div class="col-sm-2"><b>Email Address:</b></div>
	<div class="col-sm-4"><a href="mailto:"<?php echo $searchResult['epa_complainantemailaddress']; ?>"><?php echo $searchResult['epa_complainantemailaddress']; ?></a></div>
</div>

<div id="divSpacer" ></div>

<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-4"><?php 
	if(!empty($searchResult['epa_complainantaddress3'])){
		echo $searchResult['epa_complainanttown'];
	} ?></div>
</div>


<h4 id="leapReportDelimiter">Define...</h4>


<div class="row">
	<div class="col-sm-2"><b>OCLR Inspector:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_oclrinspectorname']; ?></div>
	<div class="col-sm-2"><b>Complaint Date:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_confidentialname']; ?></div>
</div>

<div id="divSpacer" ></div>

<div class="row">
	<div class="col-sm-2"><b>Issue / Theme:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_complaintissuename']; ?></div>
	<div class="col-sm-2"><b>Affects Complainant's:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_areaofaffectoncomplainantname']; ?></div>
</div>

<div id="divSpacer" ></div>

<div class="row">
	<div class="col-sm-2"><b>Issue Experienced:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_complianceinvestigationidname']; ?></div>
	<div class="col-sm-2"><b>Odour Intensity:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_odourextentname']; echo $searchResult['epa_odourintensity']?></div>
</div>

<div id="divSpacer" ></div>

<div class="row">
	<div class="col-sm-2"><b>Precipitation:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_odourprecipitationname']; ?></div>
	<div class="col-sm-2"><b>Wind Strength:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_odourwindstrengthname']; ?></div>
</div>

<div id="divSpacer" ></div>

<div class="row">
	<div class="col-sm-2"><b>Date of Occurrence:</b></div>
	<div class="col-sm-4"><?php $dateLEAPComplaintDateOfOccurrence = date_create($searchResult['epa_dateofoccurance']);
			  echo date_format($dateLEAPComplaintDateOfOccurrence,"d-M-Y H:i A"); ?></div>
	<div class="col-sm-2"><b>Duration:</b></div>
	<div class="col-sm-4"><?php echo $searchResult['epa_durationofoccurance']; ?></div>
</div>

<br/>
<br/>

<div class="row">
	<div class="col-sm-2"><b>Complaint Detail:</b></div>
	<div class="col-sm-10"><?php echo $searchResult['epa_domplaintdetail']; ?></div>
</div>


<div id="divSpacer" ></div>
<div id="divSpacer" ></div>

<div class="row">
	<div class="col-sm-2"><b>Complaint Location:</b></div>
	<div class="col-sm-10"><?php echo $searchResult['epa_location']; ?></div>
</div>

<div id="divSpacer" ></div>
<div id="divSpacer" ></div>

<div class="row">
	<div class="col-sm-2"><b>Reported to Licensee:</b></div>
	<div class="col-sm-10"><?php echo $searchResult['epa_reportedtolicenseename']; ?></div>
</div>

<div id="divSpacer" ></div>
<div id="divSpacer" ></div>

<div class="row">
	<div class="col-sm-2"><b>Contact with Licensee:</b></div>
	<div class="col-sm-10"><?php echo $searchResult['epa_detailofcontactwithlicensee']; ?></div>
</div>

<div id="divSpacer" ></div>
<div id="divSpacer" ></div>

<div class="row">
	<div class="col-sm-2"><b>Inspector's Update:</b></div>
	<div class="col-sm-10"><?php echo $searchResult['lema_inspectorsupdate']; ?></div>
</div>

<br />
<br />

<button id="cmd" class="btn btn-info btn-xs">Export to PDF</button>
<br />
<br />
<span style=font-size:10pt><a href="javascript:history.back()">[Back to results]</a></span>
<br />

</div><!-- ./container-fluid -->
</div><!-- ./container -->
</div><!-- ./content -->


<script type="text/javascript">
$(function () {

    var specialElementHandlers = {
        '#editor': function (element,renderer) {
            return true;
        }
    };
 $('#cmd').click(function () {
        var doc = new jsPDF();
        doc.fromHTML($('#target').html(), 15, 15, {
            'width': 170,'elementHandlers': specialElementHandlers
        });
        doc.save('sample-file.pdf');
    });  
});
</script>


</body>
</html>
