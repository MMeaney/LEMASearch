<?php 

//////////////////////////////////////////////////////////////////////////////////////
// Pagination - MAIN SEARCH
//////////////////////////////////////////////////////////////////////////////////////
$rows = $resultscount;
$page_rows = $param['size'];
$last = ceil ($rows/$page_rows);
if($last < 1){
	$last = 1;
}
$pageNum = 1;
if(isset($_GET['pn'])){
	$pageNum = preg_replace('#[^0-9]#', '', $_GET['pn']);
}
if($pageNum < 1){
	$pageNum = 1;
}
else if($pageNum > $last){
	$pageNum = $last;
}
// Set the range of rows to query for the chosen $pageNum
// How to apply limit to elasticsearch
// $limit = 'LIMIT ' .($pageNum - 1) * $page_rows .',' .$page_rows; // To append to SQL
$textlinenumresults = "Results: $resultscount";

if($resultscount < $page_rows){
	$textlinepagenum = "Page <b>$pageNum</b> of <b>$last</b>";
}
else{
	$textlinepagenum = "Page <b>$pageNum</b> of <b>$last</b>";// <i>(Max results for testing: ".$page_rows.")</i>";
}

$paginationCtrls = '';
// Pagination if there is more than one page of results
if($last != 1){
	// Check if on first page, then link to previous not required. If not generate links to page 1 and 'Previous'
	if($pageNum > 1){
		$previous = $pageNum - 1;
		$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
		// Render numbers for multiple pages to appear on left of target page
		for($i = $pageNum-4; $i < $pageNum; $i++){
			if($i > 0){
				$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';
			}
		}
	}
	// Render the target page number (inactive - no link)
	$paginationCtrls .= '<li class="active"><a>'.$pageNum.'<span class="sr-only">(current)</span></a></li>';

	// Render numbers for multiple pages to appear on right of target page
	for($i = $pageNum + 1; $i <= $last; $i++){
		//$param['from'] = $param['from'] + 10;
		$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?q='.$_REQUEST['q'].'&pn='.$i.'">'.$i.'</a></li>';
		if($i >= $pageNum + 4){
			break;
		}
	}

	if($last > 5){
		// Render ellipsis and last page number if pagenumbers greater than 5
		$paginationCtrls .= '<li><a>...</a></li><li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$last.'">'.$last.'</a></li>';
	}


	// Render ellipsis and last page number if pagenumbers greater than 5
		
	//$paginationCtrls .= '<li><a>...</a></li><li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$last.'">'.$last.'</a></li>';
	// Check if on last page, if not generate link to 'Next' page
	if($pageNum != $last){
		$next = $pageNum + 1;
		$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
	}
}
// Pagination if there is a single page of results
else if($last = 1){
	// Render the target page number (inactive - no link)
	$paginationCtrls .= '<li class="active"><a>'.$pageNum.'<span class="sr-only">(current)</span></a></li>';
}
// End Pagination - MAIN SEARCH
//////////////////////////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////////////////////
// Pagination - NETWORK FILE SEARCH
//////////////////////////////////////////////////////////////////////////////////////
$rowsfileshare = $resultscountfileshare;
$page_rowsfileshare = $paramfileshare['size'];
$lastfileshare = ceil ($rowsfileshare/$page_rowsfileshare);
if($lastfileshare < 1){
	$lastfileshare = 1;
}
$pageNumfileshare = 1;
if(isset($_GET['pn'])){
	$pageNumfileshare = preg_replace('#[^0-9]#', '', $_GET['pn']);
}
if($pageNumfileshare < 1){
	$pageNumfileshare = 1;
}
else if($pageNumfileshare > $lastfileshare){
	$pageNumfileshare = $lastfileshare;
}
// Set the range of rows to query for the chosen $pageNum
// How to apply limit to elasticsearch
// $limit = 'LIMIT ' .($pageNum - 1) * $page_rows .',' .$page_rows; // To append to SQL
$textlinenumresultsfileshare = "Results: $resultscountfileshare";

if($resultscountfileshare < $page_rowsfileshare){
	$textlinepagenumfileshare = "Page <b>$pageNumfileshare</b> of <b>$lastfileshare</b>";
}
else{
	$textlinepagenumfileshare = "Page <b>$pageNumfileshare</b> of <b>$lastfileshare</b> <i>(Max results for testing: ".$page_rowsfileshare.")</i>";
}

$paginationCtrlsfileshare = '';
// Pagination if there is more than one page of results
if($lastfileshare != 1){
	// Check if on first page, then link to previous not required. If not generate links to page 1 and 'Previous'
	if($pageNumfileshare > 1){
		$previousfileshare = $pageNumfileshare - 1;
		$paginationCtrlsfileshare .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$previousfileshare.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
		// Render numbers for multiple pages to appear on left of target page
		for($i = $pageNumfileshare-4; $i < $pageNumfileshare; $i++){
			if($i > 0){
				/*$paginationCtrlsfileshare .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';*/
				$paginationCtrlsfileshare .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';
				//$paramfileshare['from'] = $paramfileshare['from'] + 10;
			}
		}
	}
	// Render the target page number (inactive - no link)
	$paginationCtrlsfileshare .= '<li class="active"><a>'.$pageNumfileshare.'<span class="sr-only">(current)</span></a></li>';

	// Render numbers for multiple pages to appear on right of target page
	for($i = $pageNumfileshare + 1; $i <= $lastfileshare; $i++){
		//$paramfileshare['from'] = $paramfileshare['from'] + 10;
		$paginationCtrlsfileshare .= '<li><a href="'.$_SERVER['PHP_SELF'].'?q='.$searchText.'?pn='.$i.'">'.$i.'</a></li>';
		if($i >= $pageNumfileshare + 4){
			break;
		}
	}

	if($lastfileshare > 5){
		// Render ellipsis and last page number if pagenumbers greater than 5
		$paginationCtrlsfileshare .= '<li><a>...</a></li><li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$lastfileshare.'">'.$lastfileshare.'</a></li>';
	}

	// Check if on last page, if not generate link to 'Next' page
	if($pageNumfileshare != $lastfileshare){
		$nextfileshare = $pageNumfileshare + 1;
		$paginationCtrlsfileshare .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$nextfileshare.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
	}
}
// Pagination if there is a single page of results
else if($lastfileshare = 1){
	// Render the target page number (inactive - no link)
	$paginationCtrlsfileshare .= '<li class="active"><a>'.$pageNumfileshare.'<span class="sr-only">(current)</span></a></li>';
}
// End Pagination - NETWORK FILE SEARCH
//////////////////////////////////////////////////////////////////////////////////////

?>