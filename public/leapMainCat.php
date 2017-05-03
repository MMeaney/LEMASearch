<?php
if (count($resultsLEAPLicence) > 0) {
?>

<nav class="navbar navbar-default" role="navigation">
<ul class="nav nav-pills" role="tablist">

<li class="active"><a data-toggle="tab" href="#LEAPLicence">
Licences
<span class="badge"><?php echo $resultsCountLEAPLicence; ?></span></a>
</li>

<li><a data-toggle="pill" href="#LEAPComplaint">
Complaints
<?php if($resultsLEAPComplaint > 0){ ?>
<span class="badge"><?php echo $resultsCountLEAPComplaint; ?></span>
<?php } ?>
</a>
</li>

<li><a data-toggle="pill" href="#LEAPNonCompliance">
Non-Compliance
<?php if($resultsLEAPNonCompliance > 0){ ?>
<span class="badge"><?php echo $resultsCountLEAPNonCompliance; ?></span>
<?php } ?>
</a>
</li>

<li><a data-toggle="pill" href="#LEAPComplianceInvestigation">
Compliance Investigation
<?php if($resultsLEAPComplianceInvestigation > 0){ ?>
<span class="badge"><?php echo $resultsCountLEAPComplianceInvestigation; ?></span>
<?php } ?>
</a>
</li>

<li><a data-toggle="pill" href="#LEAPDocuments">
Documents
<?php if($resultsLEAPSharepointDocuments > 0){ ?>
<span class="badge"><?php echo $resultsCountLEAPSharepointDocuments; ?></span>
<?php } ?>
</a>
</li>

</ul>
</nav><!-- ./nav-pills -->
<?php

} // END elsif there are no search results

?>

<div class="tab-content">

<!-- ************************************************************************************************* -->
<!-- LEAP LICENCE -->
<!-- ************************************************************************************************* -->

<div id="LEAPLicence" class="tab-pane active">

<?php

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/leapLicence.php";
}

?>
</div><!-- ./LEAPLicence -->

<!-- ************************************************************************************************* -->
<!-- LEAP COMPLAINT -->
<!-- ************************************************************************************************* -->

<div id="LEAPComplaint" class="tab-pane">

<?php

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/leapComplaint.php";
}

?>
</div><!-- ./LEAPComplaint -->

<!-- ************************************************************************************************* -->
<!-- LEAP NON-COMPLIANCE -->
<!-- ************************************************************************************************* -->

<div id="LEAPNonCompliance" class="tab-pane">

<?php

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/leapNonCompliance.php";
}

?>
</div><!-- ./LEAPNonCompliance -->

<!-- ************************************************************************************************* -->
<!-- LEAP COMPLIANCE INVESTIGATION -->
<!-- ************************************************************************************************* -->

<div id="LEAPComplianceInvestigation" class="tab-pane">

<?php

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/leapComplianceInvestigation.php";
}

?>
</div><!-- ./LEAPComplianceInvestigation -->

<!-- ************************************************************************************************* -->
<!-- LEAP SHAREPOINT DOCUMENTS -->
<!-- ************************************************************************************************* -->

<div id="LEAPDocuments" class="tab-pane">

<?php

if (isset($_REQUEST['submitted'])) {
  include __DIR__ . "/leapSharepointDocuments.php";
}

?>
</div><!-- ./LEAPComplianceInvestigation -->

</div><!-- ./tab-content -->

