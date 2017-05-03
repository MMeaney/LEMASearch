<?php

error_reporting(0);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require __DIR__ . '/../vendor/autoload.php';

//use Search\Constants;
use Elasticsearch\Common\Exceptions\Missing404Exception;

$message = $_REQUEST['message'];

// Check if ID was provided
if (empty($_REQUEST['id'])) {
    $message = 'Nothing requested! Please provide an ID.';
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
            'index' => ["docs"],
        	'type'  => ["doc"]
            //'index' =>  ['docs', 'epametadatatest', 'fileshare', 'gismetadatatest', 'dmoz', 'image'],
        	//'index' => array('docs', 'docs_backup_20160815', 'epametadatatest', 'fileshare', 'gismetadatatest', 'dmoz');
        	//'type' => ['doc', 'images']
        ]);
        $searchResult = $searchResult['_source'];
    } catch (Missing404Exception $e) {
        $message = 'Requested record not found';
    }
}
?>

<?php include("layout/head.php"); ?>

<body>

<div class="container bg-danger" id="message">

<?php
if (!empty($message)) {
?>
<h1><?php echo $message; ?></h1>
<?php
}
?>
</div><!-- ./container bg-danger -->

<div class="wrapper">

<div class="container" id="container70">

<?php
if (!empty($searchResult['file']['_name'])) {
?>
  <h3><?php echo $searchResult['file']['_name']; ?></h3>
<?php
} // END check if ['file']['_name'] empty
?>

<?php
if (!empty($searchResult['file']['filename'])) {
?>
  <h3>
  <?php 
  	$strfilename = $searchResult['file']['filename'];
	echo $strfilename = preg_replace("/\w*?$searchText\w*/i", "<i>$0</i>", $strfilename)
  ?>
  </h3>
<?php
} // END check if empty
?>

<span style=font-size:10pt><a href="javascript:history.back()">[Back to results]</a></span>
<br />

 <?php echo $GLOBALS['searched_term']; ?>
 <?php echo $_REQUEST['q']; ?>
 <?php echo $q; ?>
 
<?php
if (!empty($searchResult['path']['real'])) {
?>
  <br /><b>Link to file: </b>
  <?php  
  /*$filelocationstring = $searchResult['path']['real'];
  $filelocationreplace = 'file:/'.str_replace('\\', '/', $filelocationstring);
  $filelocationreplacefull = substr($filelocationreplace, 0, strrpos( $filelocationreplace, '/') );
  $filelocationfolder = str_replace('///', '//', $filelocationreplacefull);
  $filelocation = $searchResult['file']['url'];*/
  
  $filelocationstring = $searchResult['path']['real'];
  $filelocationreplace = 'file:/'.str_replace('\\', '/', $filelocationstring);
  $filelocationreplacefull = substr($filelocationreplace, 0, strrpos( $filelocationreplace, '/') );
  $filelocation = $searchResult['file']['url'];
  $filelocationfolder = str_replace('///', '//', $filelocationreplacefull);
  ?>  
  <?php echo "<a href=\"{$filelocationstring}\" target =\"_blank\">"; echo $filelocationstring; ?></a>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['path']['real'])) {
?>
  <br /><b>File location: </b>
  <?php  
  /*$filelocationstring = $searchResult['path']['real'];
  $filelocationreplace = 'file:/'.str_replace('\\', '/', $filelocationstring);
  $filelocationreplacefull = substr($filelocationreplace, 0, strrpos( $filelocationreplace, '/') );
  $filelocationfolder = str_replace('///', '//', $filelocationreplacefull);
  $filelocation = $searchResult['file']['url'];*/
  
  $filelocationstring = $searchResult['path']['real'];
  $filelocationreplace = 'file:/'.str_replace('\\', '/', $filelocationstring);
  $filelocationreplacefull = substr($filelocationreplace, 0, strrpos( $filelocationreplace, '/') );
  $filelocation = $searchResult['file']['url'];
  $filelocationfolder = str_replace('///', '//', $filelocationreplacefull);
  ?>  
  <?php echo "<a href=\"{$filelocationfolder}\" target =\"_blank\">"; echo $filelocationfolder; ?></a>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['title'])) {
?>
  <h3><?php echo $searchResult['title']; ?></h3>
  <br />
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['meta']['title'])) {
?>
  <br /><b>Title: </b><?php echo $searchResult['meta']['title']; ?>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['meta']['author'])) {
?>
  <br /><b>Author: </b><?php echo $searchResult['meta']['author']; ?>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['file']['filename'])) {
?>
	<br /><b>File type: </b>
	<?php 
		$fileExtString = $searchResult['file']['filename'];    
		$fileExt = substr($fileExtString, strrpos($fileExtString, ".") + 1);    
		echo $fileExt; ?>
	<?php
	} // END check if empty
?>

<?php
if (!empty($searchResult['file']['last_modified'])) {
?>
  <br /><b>Last Modified: </b><?php 
  $date=date_create($searchResult['file']['last_modified']); 
  echo date_format($date,"Y-m-d H:i:s"); ?>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['lastModified'])) {
?>
  <br /><b>Last Modified: </b><?php echo $searchResult['lastModified']; ?>
<?php
} // END check if empty
?>

<div id="div_view_detail">


<?php
if (!empty($searchResult['meta']['raw']['description'])) {
?>
<br /><b>Description: </b><?php 
$str_meta_raw_description = $searchResult['meta']['raw']['description'];
//echo $str_meta_raw_description = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $str_meta_raw_description);
echo $str_meta_raw_description;
?>
<?php
} // END check if empty
?>
<?php
if (!empty($searchResult['meta']['raw']['subject'])) {
?>
<br /><b>Subject: </b><?php 
$str_meta_raw_subject = $searchResult['meta']['raw']['subject'];
//echo $str_meta_raw_subject = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $str_meta_raw_subject);
echo $str_meta_raw_subject;
?>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['meta']['raw']['Message-To'])) {
?>
<br /><b>Message-To: </b><?php 
$str_meta_raw_Message_To = $searchResult['meta']['raw']['Message-To'];
//echo $str_meta_raw_Message_To = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $str_meta_raw_Message_To);
echo $str_meta_raw_Message_To;
?>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['meta']['raw']['Message-Recipient-Address'])) {
?>
<br /><b>Message-Recipient-Address: </b><?php 
$str_meta_raw_Message_Recipient_Address = $searchResult['meta']['raw']['Message-Recipient-Address'];
//echo $str_meta_raw_Message_Recipient_Address = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $str_meta_raw_Message_Recipient_Address);
echo $str_meta_raw_Message_Recipient_Address;
?>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['meta']['raw']['Message-Cc'])) {
?>
<br /><b>Message-Cc: </b><?php 
$str_meta_raw_Message_Cc = $searchResult['meta']['raw']['Message-Cc'];
//echo $str_meta_raw_Message_Cc = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $str_meta_raw_Message_Cc);
echo $str_meta_raw_Message_Cc;
?>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['meta']['raw']['Message-Bcc'])) {
?>
<br /><b>Message-Bcc: </b><?php 
$str_meta_raw_Message_Bcc = $searchResult['meta']['raw']['Message-Bcc'];
//echo $str_meta_raw_Message_Bcc = preg_replace("/\w*?$searchText\w*/i", "<i><b>$0</b></i>", $str_meta_raw_Message_Bcc);
echo $str_meta_raw_Message_Bcc;
?>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['content'])) 
{
?>
<br />
<?php
if ($fileExt=='sql') 
{
?>
<p><b>Content: </b><br />
<pre><code class="language-sql">
<?php echo $searchResult['content'];	?>
</code></pre><br /></p>
<?php 	
	//} // END check if empty
} // END IF 'sql'

else if ($fileExt=='js') 	
{ ?>
<p><b>Content: </b><br /><pre><pre><code class="language-js">
<?php echo $searchResult['content'];	?>
</code></pre><br /></p>
<?php 
} // END IF 'js'

else if ($fileExt=='py') 	
{ ?>
<p><b>Content: </b><br /><pre><pre><code class="language-python">
<?php echo $searchResult['content'];	?>
</code></pre><br /></p>
<?php 
} // END IF 'js'

else if ($fileExt=='java') 	
{ ?>
<p><b>Content: </b><br /><pre><code class="language-java">
<?php echo $searchResult['content'];	?>
</code></pre><br /></p>
<?php 
} // END IF 'java'

else if ($fileExt=='html')
{ ?>
<p><b>Content: </b><br /><pre><code class="language-html">
<?php echo $searchResult['content'];	?>
</code></pre><br /></p>
<?php 
} // END IF 'html'

else if ($fileExt=='htm')
{ ?>
<p><b>Content: </b><br /><pre><code class="language-html">
<?php echo $searchResult['content'];	?>
</code></pre><br /></p>
<?php 
} // END IF 'htm'

else if ($fileExt=='xml') 	
{ ?>
<p><b>Content: </b><br /><pre><code class="language-xml">
<?php echo $searchResult['content'];	?>
</code></pre><br /></p>
<?php 
} // END IF 'xml'

else if ($fileExt=='xml') 	
{ ?>
<p><b>Content: </b><br /><pre><code class="language-xml">
<?php echo $searchResult['content'];	?>
</code></pre><br /></p>
<?php 
} // END IF 'xml'

else if ($fileExt=='css') 	
{ ?>
<p><b>Content: </b><br /><pre><code class="language-css">
<?php echo $searchResult['content'];	?>
</code></pre><br /></p>
<?php 
} // END IF 'css'

else if ($fileExt=='json') 	
{ ?>
<p><b>Content: </b><br /><pre><code class="language-json">
<?php echo $searchResult['content'];	?>
</code></pre><br /></p>
<?php 
} // END IF 'json'

else if ($fileExt=='json5') 	
{ ?>
<p><b>Content: </b><br /><pre><code class="language-json">
<?php echo $searchResult['content'];	?>
</code></pre><br /></p>
<?php 
} // END IF 'json5'

else if ($fileExt=='ts') 	
{ ?>
<b>Content: </b><br /><pre><code class="language-typescript">
<?php echo $searchResult['content'];	?>
</code></pre><br />
<?php 
} // END IF 'ts'

else if ($fileExt=='BAS') 	
{ ?>
<b>Content: </b><br /><pre><code class="language-basic">
<?php echo $searchResult['content'];	?>
</code></pre><br />
<?php 
} // END IF 'BAS'

else if ($fileExt=='cs') 	
{ ?>
<b>Content: </b><br /><pre><code class="language-csharp">
<?php echo $searchResult['content'];	?>
</code></pre><br />
<?php 
} // END IF 'cs'

else if ($fileExt=='ascx') 	
{ ?>
<b>Content: </b><br /><pre><code class="language-aspnet">
<?php echo $searchResult['content'];	?>
</code></pre><br />
<?php 
} // END IF 'ascx'

else if ($fileExt=='asax') 	
{ ?>
<b>Content: </b><br /><pre><code class="language-aspnet">
<?php echo $searchResult['content'];	?>
</code></pre><br />
<?php 
} // END IF 'asax'

else if ($fileExt=='ini') 	
{ ?>
<b>Content: </b><br /><pre><code class="language-ini">
<?php echo $searchResult['content'];	?>
</code></pre><br />
<?php 
} // END IF 'ini'






else
{	
//<?php
//if (!empty($searchResult['content'])) {
?>
<p><b>Content: </b>
	
<pre id="preNonCode" class="pretty-pre">
<?php 
function highlight($text, $words) {
	preg_match_all('~\w+~', $words, $m);
	if(!$m)
		return $text;
		$re = '~\\b(' . implode('|', $m[0]) . ')\\b~';
		return preg_replace($re, '<i><b>$0</b></i>', $text);
}

$text = $searchResult['content'];
$words = "select"; 
print highlight($text, $words);
?>
</pre>
</p>
<?php
} // END Else
} // END check if empty
?>


<!-- If image display image thumbnail -->
<?php
if (!empty($searchResult['file']['content_type'])) {
	if (strpos($searchResult['file']['content_type'], 'image') !== false) {
?>
<br /><b>Image: </b>
<br />
<div>
<?php
echo "<a href=\"{$searchResult['file']['url']}\">";
?>
<!-- <img class="imgfull" src="data:image/png;base64,<? /* php echo $searchResult['attachment']; */ ?> "/>	 -->
<img class="imgFull" src="<?php echo $filelocation; ?> "/>
</a>
</div>
<?php
	} // END check file type 
} // END check if empty
?>


<?php
if (!empty($searchResult['description'])) {
?>
  <br /><b>Description: </b><?php echo $searchResult['description']; ?>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['subject'])) {
?>
  <br /><b>Keywords: </b>
   <?php foreach($searchResult['subject'] as $row) {
    echo $row, ', ';
}?>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['rights'])) {
?>
  <br /><b>Rights: </b><?php echo $searchResult['rights']; ?>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['coverage'])) {
?>
  <br /><b>Coverage: </b><?php echo $searchResult['coverage']; ?>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['@schemaLocation'])) {
?>
  <br /><b>Schema Location: </b><a href="<?php echo $searchResult['@schemaLocation']; ?>"><?php echo $searchResult['@schemaLocation']; ?></a>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['identifier'])) {
?>
  <br /><b>Identifier: </b><?php echo $searchResult['identifier']; ?>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['type'])) {
?>
  <br /><b>Type: </b><?php echo $searchResult['type']; ?>
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['format'])) {
?>
  <br /><b>Format: </b><?php echo $searchResult['format']; ?>
<?php
} // END check if empty
?>


<?php
if (!empty($searchResult['Creation-Date'])) {
?>
  <br /><b>Date: </b><?php echo $searchResult['Creation-Date']; ?>
<?php
} // END check if empty
?>

<?php if (!empty($searchResult['meta']['raw']['AE Bracket Compensation'])) { ?> <br /> <span id="spanMetaTypeBold">AE Bracket Compensation:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AE Bracket Compensation'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AE Warning'])) { ?> <br /> <span id="spanMetaTypeBold">AE Warning:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AE Warning'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AEB Bracket Value'])) { ?> <br /> <span id="spanMetaTypeBold">AEB Bracket Value:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AEB Bracket Value'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AF Area Height'])) { ?> <br /> <span id="spanMetaTypeBold">AF Area Height:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AF Area Height'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AF Area Width'])) { ?> <br /> <span id="spanMetaTypeBold">AF Area Width:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AF Area Width'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AF Area X Positions'])) { ?> <br /> <span id="spanMetaTypeBold">AF Area X Positions:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AF Area X Positions'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AF Area Y Positions'])) { ?> <br /> <span id="spanMetaTypeBold">AF Area Y Positions:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AF Area Y Positions'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AF Image Height'])) { ?> <br /> <span id="spanMetaTypeBold">AF Image Height:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AF Image Height'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AF Image Width'])) { ?> <br /> <span id="spanMetaTypeBold">AF Image Width:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AF Image Width'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AF Info 2'])) { ?> <br /> <span id="spanMetaTypeBold">AF Info 2:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AF Info 2'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AF Info Array 2'])) { ?> <br /> <span id="spanMetaTypeBold">AF Info Array 2:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AF Info Array 2'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AF Point Count'])) { ?> <br /> <span id="spanMetaTypeBold">AF Point Count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AF Point Count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AF Point Selected'])) { ?> <br /> <span id="spanMetaTypeBold">AF Point Selected:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AF Point Selected'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AF Points in Focus'])) { ?> <br /> <span id="spanMetaTypeBold">AF Points in Focus:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AF Points in Focus'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AF Points in Focus Count'])) { ?> <br /> <span id="spanMetaTypeBold">AF Points in Focus Count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AF Points in Focus Count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AF Tune'])) { ?> <br /> <span id="spanMetaTypeBold">AF Tune:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AF Tune'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AF Type'])) { ?> <br /> <span id="spanMetaTypeBold">AF Type:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AF Type'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AToB 0'])) { ?> <br /> <span id="spanMetaTypeBold">AToB 0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AToB 0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AToB 1'])) { ?> <br /> <span id="spanMetaTypeBold">AToB 1:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AToB 1'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['AToB 2'])) { ?> <br /> <span id="spanMetaTypeBold">AToB 2:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['AToB 2'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Active D-Lighting'])) { ?> <br /> <span id="spanMetaTypeBold">Active D-Lighting:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Active D-Lighting'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Aperture Value'])) { ?> <br /> <span id="spanMetaTypeBold">Aperture Value:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Aperture Value'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Application Record Version'])) { ?> <br /> <span id="spanMetaTypeBold">Application Record Version:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Application Record Version'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Application-Name'])) { ?> <br /> <span id="spanMetaTypeBold">Application-Name:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Application-Name'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Application-Version'])) { ?> <br /> <span id="spanMetaTypeBold">Application-Version:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Application-Version'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['ApplicationExtensions ApplicationExtension'])) { ?> <br /> <span id="spanMetaTypeBold">ApplicationExtensions ApplicationExtension:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['ApplicationExtensions ApplicationExtension'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Artist'])) { ?> <br /> <span id="spanMetaTypeBold">Artist:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Artist'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Aspect Information Array'])) { ?> <br /> <span id="spanMetaTypeBold">Aspect Information Array:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Aspect Information Array'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Author'])) { ?> <br /> <span id="spanMetaTypeBold">Author:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Author'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Auto Bracketing'])) { ?> <br /> <span id="spanMetaTypeBold">Auto Bracketing:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Auto Bracketing'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Auto Exposure Bracketing'])) { ?> <br /> <span id="spanMetaTypeBold">Auto Exposure Bracketing:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Auto Exposure Bracketing'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Auto Flash Compensation'])) { ?> <br /> <span id="spanMetaTypeBold">Auto Flash Compensation:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Auto Flash Compensation'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Auto Flash Mode'])) { ?> <br /> <span id="spanMetaTypeBold">Auto Flash Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Auto Flash Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Auto ISO'])) { ?> <br /> <span id="spanMetaTypeBold">Auto ISO:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Auto ISO'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Auto Rotate'])) { ?> <br /> <span id="spanMetaTypeBold">Auto Rotate:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Auto Rotate'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['BToA 0'])) { ?> <br /> <span id="spanMetaTypeBold">BToA 0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['BToA 0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['BToA 1'])) { ?> <br /> <span id="spanMetaTypeBold">BToA 1:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['BToA 1'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['BToA 2'])) { ?> <br /> <span id="spanMetaTypeBold">BToA 2:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['BToA 2'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['BW Mode'])) { ?> <br /> <span id="spanMetaTypeBold">BW Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['BW Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Background Color'])) { ?> <br /> <span id="spanMetaTypeBold">Background Color:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Background Color'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Base ISO'])) { ?> <br /> <span id="spanMetaTypeBold">Base ISO:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Base ISO'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Bits Per Sample'])) { ?> <br /> <span id="spanMetaTypeBold">Bits Per Sample:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Bits Per Sample'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Black Level'])) { ?> <br /> <span id="spanMetaTypeBold">Black Level:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Black Level'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Blue Colorant'])) { ?> <br /> <span id="spanMetaTypeBold">Blue Colorant:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Blue Colorant'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Blue TRC'])) { ?> <br /> <span id="spanMetaTypeBold">Blue TRC:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Blue TRC'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Blur Warning'])) { ?> <br /> <span id="spanMetaTypeBold">Blur Warning:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Blur Warning'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Body Serial Number'])) { ?> <br /> <span id="spanMetaTypeBold">Body Serial Number:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Body Serial Number'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Brightness Value'])) { ?> <br /> <span id="spanMetaTypeBold">Brightness Value:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Brightness Value'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Bulb Duration'])) { ?> <br /> <span id="spanMetaTypeBold">Bulb Duration:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Bulb Duration'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['By-line'])) { ?> <br /> <span id="spanMetaTypeBold">By-line:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['By-line'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['By-line Title'])) { ?> <br /> <span id="spanMetaTypeBold">By-line Title:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['By-line Title'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['CFA Pattern'])) { ?> <br /> <span id="spanMetaTypeBold">CFA Pattern:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['CFA Pattern'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['CMM Flags'])) { ?> <br /> <span id="spanMetaTypeBold">CMM Flags:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['CMM Flags'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['CMM Type'])) { ?> <br /> <span id="spanMetaTypeBold">CMM Type:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['CMM Type'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['CRW Parameters'])) { ?> <br /> <span id="spanMetaTypeBold">CRW Parameters:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['CRW Parameters'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Camera Id'])) { ?> <br /> <span id="spanMetaTypeBold">Camera Id:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Camera Id'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Camera Info Array'])) { ?> <br /> <span id="spanMetaTypeBold">Camera Info Array:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Camera Info Array'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Camera Owner Name'])) { ?> <br /> <span id="spanMetaTypeBold">Camera Owner Name:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Camera Owner Name'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Camera Serial Number'])) { ?> <br /> <span id="spanMetaTypeBold">Camera Serial Number:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Camera Serial Number'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Camera Temperature'])) { ?> <br /> <span id="spanMetaTypeBold">Camera Temperature:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Camera Temperature'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Camera Type'])) { ?> <br /> <span id="spanMetaTypeBold">Camera Type:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Camera Type'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Canon Model ID'])) { ?> <br /> <span id="spanMetaTypeBold">Canon Model ID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Canon Model ID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Caption Digest'])) { ?> <br /> <span id="spanMetaTypeBold">Caption Digest:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Caption Digest'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Caption Writer/Editor'])) { ?> <br /> <span id="spanMetaTypeBold">Caption Writer/Editor:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Caption Writer/Editor'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Caption/Abstract'])) { ?> <br /> <span id="spanMetaTypeBold">Caption/Abstract:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Caption/Abstract'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Categories'])) { ?> <br /> <span id="spanMetaTypeBold">Categories:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Categories'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Category'])) { ?> <br /> <span id="spanMetaTypeBold">Category:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Category'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Character Count'])) { ?> <br /> <span id="spanMetaTypeBold">Character Count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Character Count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Character-Count-With-Spaces'])) { ?> <br /> <span id="spanMetaTypeBold">Character-Count-With-Spaces:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Character-Count-With-Spaces'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Chroma BlackIsZero'])) { ?> <br /> <span id="spanMetaTypeBold">Chroma BlackIsZero:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Chroma BlackIsZero'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Chroma ColorSpaceType'])) { ?> <br /> <span id="spanMetaTypeBold">Chroma ColorSpaceType:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Chroma ColorSpaceType'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Chroma Gamma'])) { ?> <br /> <span id="spanMetaTypeBold">Chroma Gamma:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Chroma Gamma'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Chroma NumChannels'])) { ?> <br /> <span id="spanMetaTypeBold">Chroma NumChannels:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Chroma NumChannels'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Chroma Palette'])) { ?> <br /> <span id="spanMetaTypeBold">Chroma Palette:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Chroma Palette'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Chroma Palette PaletteEntry'])) { ?> <br /> <span id="spanMetaTypeBold">Chroma Palette PaletteEntry:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Chroma Palette PaletteEntry'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['City'])) { ?> <br /> <span id="spanMetaTypeBold">City:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['City'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Class'])) { ?> <br /> <span id="spanMetaTypeBold">Class:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Class'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Coded Character Set'])) { ?> <br /> <span id="spanMetaTypeBold">Coded Character Set:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Coded Character Set'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Color Balance'])) { ?> <br /> <span id="spanMetaTypeBold">Color Balance:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Color Balance'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Color Data Array 1'])) { ?> <br /> <span id="spanMetaTypeBold">Color Data Array 1:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Color Data Array 1'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Color Data Array 2'])) { ?> <br /> <span id="spanMetaTypeBold">Color Data Array 2:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Color Data Array 2'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Color Halftoning Information'])) { ?> <br /> <span id="spanMetaTypeBold">Color Halftoning Information:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Color Halftoning Information'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Color Saturation'])) { ?> <br /> <span id="spanMetaTypeBold">Color Saturation:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Color Saturation'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Color Space'])) { ?> <br /> <span id="spanMetaTypeBold">Color Space:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Color Space'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Color Transfer Functions'])) { ?> <br /> <span id="spanMetaTypeBold">Color Transfer Functions:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Color Transfer Functions'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Color Transform'])) { ?> <br /> <span id="spanMetaTypeBold">Color Transform:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Color Transform'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Color space'])) { ?> <br /> <span id="spanMetaTypeBold">Color space:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Color space'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Comments'])) { ?> <br /> <span id="spanMetaTypeBold">Comments:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Comments'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Company'])) { ?> <br /> <span id="spanMetaTypeBold">Company:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Company'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Component 1'])) { ?> <br /> <span id="spanMetaTypeBold">Component 1:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Component 1'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Component 2'])) { ?> <br /> <span id="spanMetaTypeBold">Component 2:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Component 2'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Component 3'])) { ?> <br /> <span id="spanMetaTypeBold">Component 3:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Component 3'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Component 4'])) { ?> <br /> <span id="spanMetaTypeBold">Component 4:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Component 4'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Components Configuration'])) { ?> <br /> <span id="spanMetaTypeBold">Components Configuration:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Components Configuration'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Compressed Bits Per Pixel'])) { ?> <br /> <span id="spanMetaTypeBold">Compressed Bits Per Pixel:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Compressed Bits Per Pixel'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Compression'])) { ?> <br /> <span id="spanMetaTypeBold">Compression:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Compression'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Compression CompressionTypeName'])) { ?> <br /> <span id="spanMetaTypeBold">Compression CompressionTypeName:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Compression CompressionTypeName'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Compression Lossless'])) { ?> <br /> <span id="spanMetaTypeBold">Compression Lossless:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Compression Lossless'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Compression NumProgressiveScans'])) { ?> <br /> <span id="spanMetaTypeBold">Compression NumProgressiveScans:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Compression NumProgressiveScans'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Compression Type'])) { ?> <br /> <span id="spanMetaTypeBold">Compression Type:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Compression Type'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Content-Encoding'])) { ?> <br /> <span id="spanMetaTypeBold">Content-Encoding:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Content-Encoding'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Content-Type'])) { ?> <br /> <span id="spanMetaTypeBold">Content-Type:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Content-Type'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Content-Type-Hint'])) { ?> <br /> <span id="spanMetaTypeBold">Content-Type-Hint:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Content-Type-Hint'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Continuous Drive Mode'])) { ?> <br /> <span id="spanMetaTypeBold">Continuous Drive Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Continuous Drive Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Contrast'])) { ?> <br /> <span id="spanMetaTypeBold">Contrast:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Contrast'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Control Mode'])) { ?> <br /> <span id="spanMetaTypeBold">Control Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Control Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Copyright'])) { ?> <br /> <span id="spanMetaTypeBold">Copyright:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Copyright'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Copyright Flag'])) { ?> <br /> <span id="spanMetaTypeBold">Copyright Flag:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Copyright Flag'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Copyright Notice'])) { ?> <br /> <span id="spanMetaTypeBold">Copyright Notice:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Copyright Notice'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Country/Primary Location Name'])) { ?> <br /> <span id="spanMetaTypeBold">Country/Primary Location Name:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Country/Primary Location Name'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Creation-Date'])) { ?> <br /> <span id="spanMetaTypeBold">Creation-Date:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Creation-Date'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['CreationDate--Text'])) { ?> <br /> <span id="spanMetaTypeBold">CreationDate--Text:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['CreationDate--Text'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['CreatorDate'])) { ?> <br /> <span id="spanMetaTypeBold">CreatorDate:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['CreatorDate'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Credit'])) { ?> <br /> <span id="spanMetaTypeBold">Credit:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Credit'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Crop High Speed'])) { ?> <br /> <span id="spanMetaTypeBold">Crop High Speed:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Crop High Speed'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Crop Info'])) { ?> <br /> <span id="spanMetaTypeBold">Crop Info:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Crop Info'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Custom Functions'])) { ?> <br /> <span id="spanMetaTypeBold">Custom Functions:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Custom Functions'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Custom Functions Array 2'])) { ?> <br /> <span id="spanMetaTypeBold">Custom Functions Array 2:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Custom Functions Array 2'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Custom Picture Style File Name'])) { ?> <br /> <span id="spanMetaTypeBold">Custom Picture Style File Name:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Custom Picture Style File Name'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Custom Rendered'])) { ?> <br /> <span id="spanMetaTypeBold">Custom Rendered:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Custom Rendered'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['DCT Encode Version'])) { ?> <br /> <span id="spanMetaTypeBold">DCT Encode Version:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['DCT Encode Version'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['DLI'])) { ?> <br /> <span id="spanMetaTypeBold">DLI:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['DLI'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['DLI_Copyright'])) { ?> <br /> <span id="spanMetaTypeBold">DLI_Copyright:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['DLI_Copyright'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['DOCSUMMARY'])) { ?> <br /> <span id="spanMetaTypeBold">DOCSUMMARY:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['DOCSUMMARY'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Data BitsPerSample'])) { ?> <br /> <span id="spanMetaTypeBold">Data BitsPerSample:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Data BitsPerSample'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Data Dump'])) { ?> <br /> <span id="spanMetaTypeBold">Data Dump:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Data Dump'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Data PlanarConfiguration'])) { ?> <br /> <span id="spanMetaTypeBold">Data PlanarConfiguration:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Data PlanarConfiguration'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Data Precision'])) { ?> <br /> <span id="spanMetaTypeBold">Data Precision:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Data Precision'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Data SampleFormat'])) { ?> <br /> <span id="spanMetaTypeBold">Data SampleFormat:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Data SampleFormat'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Date Created'])) { ?> <br /> <span id="spanMetaTypeBold">Date Created:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Date Created'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Date Stamp Mode'])) { ?> <br /> <span id="spanMetaTypeBold">Date Stamp Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Date Stamp Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Date/Time'])) { ?> <br /> <span id="spanMetaTypeBold">Date/Time:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Date/Time'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Date/Time Digitized'])) { ?> <br /> <span id="spanMetaTypeBold">Date/Time Digitized:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Date/Time Digitized'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Date/Time Original'])) { ?> <br /> <span id="spanMetaTypeBold">Date/Time Original:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Date/Time Original'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Device Mfg Description'])) { ?> <br /> <span id="spanMetaTypeBold">Device Mfg Description:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Device Mfg Description'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Device Model Description'])) { ?> <br /> <span id="spanMetaTypeBold">Device Model Description:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Device Model Description'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Device manufacturer'])) { ?> <br /> <span id="spanMetaTypeBold">Device manufacturer:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Device manufacturer'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Device model'])) { ?> <br /> <span id="spanMetaTypeBold">Device model:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Device model'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['DigiZoom Ratio'])) { ?> <br /> <span id="spanMetaTypeBold">DigiZoom Ratio:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['DigiZoom Ratio'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Digital Date Created'])) { ?> <br /> <span id="spanMetaTypeBold">Digital Date Created:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Digital Date Created'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Digital Time Created'])) { ?> <br /> <span id="spanMetaTypeBold">Digital Time Created:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Digital Time Created'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Digital Vari Program'])) { ?> <br /> <span id="spanMetaTypeBold">Digital Vari Program:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Digital Vari Program'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Digital Zoom'])) { ?> <br /> <span id="spanMetaTypeBold">Digital Zoom:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Digital Zoom'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Digital Zoom Ratio'])) { ?> <br /> <span id="spanMetaTypeBold">Digital Zoom Ratio:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Digital Zoom Ratio'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Dimension HorizontalPhysicalPixelSpacing'])) { ?> <br /> <span id="spanMetaTypeBold">Dimension HorizontalPhysicalPixelSpacing:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Dimension HorizontalPhysicalPixelSpacing'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Dimension HorizontalPixelOffset'])) { ?> <br /> <span id="spanMetaTypeBold">Dimension HorizontalPixelOffset:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Dimension HorizontalPixelOffset'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Dimension HorizontalPixelSize'])) { ?> <br /> <span id="spanMetaTypeBold">Dimension HorizontalPixelSize:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Dimension HorizontalPixelSize'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Dimension ImageOrientation'])) { ?> <br /> <span id="spanMetaTypeBold">Dimension ImageOrientation:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Dimension ImageOrientation'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Dimension PixelAspectRatio'])) { ?> <br /> <span id="spanMetaTypeBold">Dimension PixelAspectRatio:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Dimension PixelAspectRatio'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Dimension VerticalPhysicalPixelSpacing'])) { ?> <br /> <span id="spanMetaTypeBold">Dimension VerticalPhysicalPixelSpacing:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Dimension VerticalPhysicalPixelSpacing'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Dimension VerticalPixelOffset'])) { ?> <br /> <span id="spanMetaTypeBold">Dimension VerticalPixelOffset:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Dimension VerticalPixelOffset'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Dimension VerticalPixelSize'])) { ?> <br /> <span id="spanMetaTypeBold">Dimension VerticalPixelSize:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Dimension VerticalPixelSize'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['DocVersion'])) { ?> <br /> <span id="spanMetaTypeBold">DocVersion:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['DocVersion'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Dust Removal Data'])) { ?> <br /> <span id="spanMetaTypeBold">Dust Removal Data:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Dust Removal Data'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Dynamic Range'])) { ?> <br /> <span id="spanMetaTypeBold">Dynamic Range:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Dynamic Range'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Easy Shooting Mode'])) { ?> <br /> <span id="spanMetaTypeBold">Easy Shooting Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Easy Shooting Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Edit-Time'])) { ?> <br /> <span id="spanMetaTypeBold">Edit-Time:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Edit-Time'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Enveloped Record Version'])) { ?> <br /> <span id="spanMetaTypeBold">Enveloped Record Version:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Enveloped Record Version'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Exif Image Height'])) { ?> <br /> <span id="spanMetaTypeBold">Exif Image Height:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Exif Image Height'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Exif Image Width'])) { ?> <br /> <span id="spanMetaTypeBold">Exif Image Width:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Exif Image Width'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Exif Version'])) { ?> <br /> <span id="spanMetaTypeBold">Exif Version:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Exif Version'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Exposure Bias Value'])) { ?> <br /> <span id="spanMetaTypeBold">Exposure Bias Value:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Exposure Bias Value'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Exposure Compensation'])) { ?> <br /> <span id="spanMetaTypeBold">Exposure Compensation:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Exposure Compensation'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Exposure Difference'])) { ?> <br /> <span id="spanMetaTypeBold">Exposure Difference:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Exposure Difference'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Exposure Mode'])) { ?> <br /> <span id="spanMetaTypeBold">Exposure Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Exposure Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Exposure Program'])) { ?> <br /> <span id="spanMetaTypeBold">Exposure Program:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Exposure Program'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Exposure Sequence Number'])) { ?> <br /> <span id="spanMetaTypeBold">Exposure Sequence Number:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Exposure Sequence Number'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Exposure Time'])) { ?> <br /> <span id="spanMetaTypeBold">Exposure Time:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Exposure Time'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Exposure Tuning'])) { ?> <br /> <span id="spanMetaTypeBold">Exposure Tuning:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Exposure Tuning'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['F Number'])) { ?> <br /> <span id="spanMetaTypeBold">F Number:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['F Number'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['F-Number'])) { ?> <br /> <span id="spanMetaTypeBold">F-Number:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['F-Number'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Faces Detected'])) { ?> <br /> <span id="spanMetaTypeBold">Faces Detected:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Faces Detected'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['File Info'])) { ?> <br /> <span id="spanMetaTypeBold">File Info:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['File Info'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['File Info Array'])) { ?> <br /> <span id="spanMetaTypeBold">File Info Array:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['File Info Array'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['File Modified Date'])) { ?> <br /> <span id="spanMetaTypeBold">File Modified Date:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['File Modified Date'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['File Name'])) { ?> <br /> <span id="spanMetaTypeBold">File Name:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['File Name'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['File Size'])) { ?> <br /> <span id="spanMetaTypeBold">File Size:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['File Size'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['File Source'])) { ?> <br /> <span id="spanMetaTypeBold">File Source:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['File Source'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['FinePix Color Setting'])) { ?> <br /> <span id="spanMetaTypeBold">FinePix Color Setting:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['FinePix Color Setting'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Firmware'])) { ?> <br /> <span id="spanMetaTypeBold">Firmware:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Firmware'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Firmware Revision'])) { ?> <br /> <span id="spanMetaTypeBold">Firmware Revision:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Firmware Revision'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Firmware Version'])) { ?> <br /> <span id="spanMetaTypeBold">Firmware Version:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Firmware Version'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Flags 0'])) { ?> <br /> <span id="spanMetaTypeBold">Flags 0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Flags 0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Flags 1'])) { ?> <br /> <span id="spanMetaTypeBold">Flags 1:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Flags 1'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Flash'])) { ?> <br /> <span id="spanMetaTypeBold">Flash:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Flash'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Flash Activity'])) { ?> <br /> <span id="spanMetaTypeBold">Flash Activity:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Flash Activity'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Flash Bracket Compensation'])) { ?> <br /> <span id="spanMetaTypeBold">Flash Bracket Compensation:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Flash Bracket Compensation'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Flash Details'])) { ?> <br /> <span id="spanMetaTypeBold">Flash Details:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Flash Details'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Flash Exposure Compensation'])) { ?> <br /> <span id="spanMetaTypeBold">Flash Exposure Compensation:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Flash Exposure Compensation'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Flash Guide Number'])) { ?> <br /> <span id="spanMetaTypeBold">Flash Guide Number:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Flash Guide Number'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Flash Info'])) { ?> <br /> <span id="spanMetaTypeBold">Flash Info:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Flash Info'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Flash Mode'])) { ?> <br /> <span id="spanMetaTypeBold">Flash Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Flash Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Flash Output'])) { ?> <br /> <span id="spanMetaTypeBold">Flash Output:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Flash Output'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Flash Strength'])) { ?> <br /> <span id="spanMetaTypeBold">Flash Strength:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Flash Strength'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Flash Sync Mode'])) { ?> <br /> <span id="spanMetaTypeBold">Flash Sync Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Flash Sync Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Flash Used'])) { ?> <br /> <span id="spanMetaTypeBold">Flash Used:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Flash Used'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['FlashPix Version'])) { ?> <br /> <span id="spanMetaTypeBold">FlashPix Version:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['FlashPix Version'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Focal Length'])) { ?> <br /> <span id="spanMetaTypeBold">Focal Length:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Focal Length'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Focal Length 35'])) { ?> <br /> <span id="spanMetaTypeBold">Focal Length 35:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Focal Length 35'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Focal Plane Diagonal'])) { ?> <br /> <span id="spanMetaTypeBold">Focal Plane Diagonal:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Focal Plane Diagonal'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Focal Plane Resolution Unit'])) { ?> <br /> <span id="spanMetaTypeBold">Focal Plane Resolution Unit:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Focal Plane Resolution Unit'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Focal Plane X Resolution'])) { ?> <br /> <span id="spanMetaTypeBold">Focal Plane X Resolution:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Focal Plane X Resolution'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Focal Plane Y Resolution'])) { ?> <br /> <span id="spanMetaTypeBold">Focal Plane Y Resolution:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Focal Plane Y Resolution'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Focal Units per mm'])) { ?> <br /> <span id="spanMetaTypeBold">Focal Units per mm:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Focal Units per mm'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Focus Distance Lower'])) { ?> <br /> <span id="spanMetaTypeBold">Focus Distance Lower:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Focus Distance Lower'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Focus Distance Upper'])) { ?> <br /> <span id="spanMetaTypeBold">Focus Distance Upper:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Focus Distance Upper'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Focus Mode'])) { ?> <br /> <span id="spanMetaTypeBold">Focus Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Focus Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Focus Pixel'])) { ?> <br /> <span id="spanMetaTypeBold">Focus Pixel:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Focus Pixel'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Focus Type'])) { ?> <br /> <span id="spanMetaTypeBold">Focus Type:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Focus Type'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Focus Warning'])) { ?> <br /> <span id="spanMetaTypeBold">Focus Warning:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Focus Warning'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['FontFamilyName'])) { ?> <br /> <span id="spanMetaTypeBold">FontFamilyName:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['FontFamilyName'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['FontName'])) { ?> <br /> <span id="spanMetaTypeBold">FontName:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['FontName'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['FontSubFamilyName'])) { ?> <br /> <span id="spanMetaTypeBold">FontSubFamilyName:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['FontSubFamilyName'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['GENERATOR'])) { ?> <br /> <span id="spanMetaTypeBold">GENERATOR:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['GENERATOR'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['GPS Altitude'])) { ?> <br /> <span id="spanMetaTypeBold">GPS Altitude:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['GPS Altitude'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['GPS Altitude Ref'])) { ?> <br /> <span id="spanMetaTypeBold">GPS Altitude Ref:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['GPS Altitude Ref'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['GPS Img Direction'])) { ?> <br /> <span id="spanMetaTypeBold">GPS Img Direction:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['GPS Img Direction'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['GPS Img Direction Ref'])) { ?> <br /> <span id="spanMetaTypeBold">GPS Img Direction Ref:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['GPS Img Direction Ref'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['GPS Latitude'])) { ?> <br /> <span id="spanMetaTypeBold">GPS Latitude:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['GPS Latitude'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['GPS Latitude Ref'])) { ?> <br /> <span id="spanMetaTypeBold">GPS Latitude Ref:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['GPS Latitude Ref'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['GPS Longitude'])) { ?> <br /> <span id="spanMetaTypeBold">GPS Longitude:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['GPS Longitude'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['GPS Longitude Ref'])) { ?> <br /> <span id="spanMetaTypeBold">GPS Longitude Ref:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['GPS Longitude Ref'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['GPS Time-Stamp'])) { ?> <br /> <span id="spanMetaTypeBold">GPS Time-Stamp:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['GPS Time-Stamp'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['GPS Version ID'])) { ?> <br /> <span id="spanMetaTypeBold">GPS Version ID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['GPS Version ID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['GTS_PDFXConformance'])) { ?> <br /> <span id="spanMetaTypeBold">GTS_PDFXConformance:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['GTS_PDFXConformance'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['GTS_PDFXVersion'])) { ?> <br /> <span id="spanMetaTypeBold">GTS_PDFXVersion:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['GTS_PDFXVersion'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Gain Control'])) { ?> <br /> <span id="spanMetaTypeBold">Gain Control:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Gain Control'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Gamut'])) { ?> <br /> <span id="spanMetaTypeBold">Gamut:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Gamut'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Generator'])) { ?> <br /> <span id="spanMetaTypeBold">Generator:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Generator'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Global Altitude'])) { ?> <br /> <span id="spanMetaTypeBold">Global Altitude:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Global Altitude'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Global Angle'])) { ?> <br /> <span id="spanMetaTypeBold">Global Angle:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Global Angle'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['GraphicControlExtension'])) { ?> <br /> <span id="spanMetaTypeBold">GraphicControlExtension:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['GraphicControlExtension'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Gray TRC'])) { ?> <br /> <span id="spanMetaTypeBold">Gray TRC:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Gray TRC'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Grayscale and Multichannel Halftoning Information'])) { ?> <br /> <span id="spanMetaTypeBold">Grayscale and Multichannel Halftoning Information:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Grayscale and Multichannel Halftoning Information'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Grayscale and Multichannel Transfer Function'])) { ?> <br /> <span id="spanMetaTypeBold">Grayscale and Multichannel Transfer Function:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Grayscale and Multichannel Transfer Function'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Green Colorant'])) { ?> <br /> <span id="spanMetaTypeBold">Green Colorant:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Green Colorant'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Green TRC'])) { ?> <br /> <span id="spanMetaTypeBold">Green TRC:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Green TRC'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Grid and Guides Information'])) { ?> <br /> <span id="spanMetaTypeBold">Grid and Guides Information:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Grid and Guides Information'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Headline'])) { ?> <br /> <span id="spanMetaTypeBold">Headline:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Headline'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['High ISO Noise Reduction'])) { ?> <br /> <span id="spanMetaTypeBold">High ISO Noise Reduction:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['High ISO Noise Reduction'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['ICC Untagged Profile'])) { ?> <br /> <span id="spanMetaTypeBold">ICC Untagged Profile:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['ICC Untagged Profile'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['IHDR'])) { ?> <br /> <span id="spanMetaTypeBold">IHDR:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['IHDR'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['ISO'])) { ?> <br /> <span id="spanMetaTypeBold">ISO:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['ISO'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['ISO Info'])) { ?> <br /> <span id="spanMetaTypeBold">ISO Info:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['ISO Info'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['ISO Speed Ratings'])) { ?> <br /> <span id="spanMetaTypeBold">ISO Speed Ratings:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['ISO Speed Ratings'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Image Boundary'])) { ?> <br /> <span id="spanMetaTypeBold">Image Boundary:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Image Boundary'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Image Data Size'])) { ?> <br /> <span id="spanMetaTypeBold">Image Data Size:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Image Data Size'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Image Description'])) { ?> <br /> <span id="spanMetaTypeBold">Image Description:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Image Description'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Image Height'])) { ?> <br /> <span id="spanMetaTypeBold">Image Height:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Image Height'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Image Number'])) { ?> <br /> <span id="spanMetaTypeBold">Image Number:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Image Number'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Image Size'])) { ?> <br /> <span id="spanMetaTypeBold">Image Size:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Image Size'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Image Type'])) { ?> <br /> <span id="spanMetaTypeBold">Image Type:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Image Type'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Image Unique ID'])) { ?> <br /> <span id="spanMetaTypeBold">Image Unique ID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Image Unique ID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Image Width'])) { ?> <br /> <span id="spanMetaTypeBold">Image Width:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Image Width'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['ImageDescriptor'])) { ?> <br /> <span id="spanMetaTypeBold">ImageDescriptor:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['ImageDescriptor'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Inter Color Profile'])) { ?> <br /> <span id="spanMetaTypeBold">Inter Color Profile:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Inter Color Profile'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Interoperability Index'])) { ?> <br /> <span id="spanMetaTypeBold">Interoperability Index:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Interoperability Index'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Interoperability Version'])) { ?> <br /> <span id="spanMetaTypeBold">Interoperability Version:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Interoperability Version'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Iso'])) { ?> <br /> <span id="spanMetaTypeBold">Iso:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Iso'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['JPEG Comment'])) { ?> <br /> <span id="spanMetaTypeBold">JPEG Comment:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['JPEG Comment'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['JPEG Quality'])) { ?> <br /> <span id="spanMetaTypeBold">JPEG Quality:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['JPEG Quality'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Job Identifier'])) { ?> <br /> <span id="spanMetaTypeBold">Job Identifier:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Job Identifier'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Keywords'])) { ?> <br /> <span id="spanMetaTypeBold">Keywords:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Keywords'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Last-Author'])) { ?> <br /> <span id="spanMetaTypeBold">Last-Author:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Last-Author'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Last-Modified'])) { ?> <br /> <span id="spanMetaTypeBold">Last-Modified:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Last-Modified'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Last-Printed'])) { ?> <br /> <span id="spanMetaTypeBold">Last-Printed:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Last-Printed'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Last-Save-Date'])) { ?> <br /> <span id="spanMetaTypeBold">Last-Save-Date:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Last-Save-Date'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Layer Groups Enabled ID'])) { ?> <br /> <span id="spanMetaTypeBold">Layer Groups Enabled ID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Layer Groups Enabled ID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Layer Selection IDs'])) { ?> <br /> <span id="spanMetaTypeBold">Layer Selection IDs:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Layer Selection IDs'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Layer State Information'])) { ?> <br /> <span id="spanMetaTypeBold">Layer State Information:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Layer State Information'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Layers Group Information'])) { ?> <br /> <span id="spanMetaTypeBold">Layers Group Information:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Layers Group Information'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Lens'])) { ?> <br /> <span id="spanMetaTypeBold">Lens:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Lens'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Lens Data'])) { ?> <br /> <span id="spanMetaTypeBold">Lens Data:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Lens Data'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Lens Distortion Parameters'])) { ?> <br /> <span id="spanMetaTypeBold">Lens Distortion Parameters:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Lens Distortion Parameters'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Lens Information'])) { ?> <br /> <span id="spanMetaTypeBold">Lens Information:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Lens Information'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Lens Make'])) { ?> <br /> <span id="spanMetaTypeBold">Lens Make:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Lens Make'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Lens Model'])) { ?> <br /> <span id="spanMetaTypeBold">Lens Model:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Lens Model'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Lens Serial Number'])) { ?> <br /> <span id="spanMetaTypeBold">Lens Serial Number:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Lens Serial Number'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Lens Specification'])) { ?> <br /> <span id="spanMetaTypeBold">Lens Specification:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Lens Specification'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Lens Stops'])) { ?> <br /> <span id="spanMetaTypeBold">Lens Stops:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Lens Stops'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Lens Type'])) { ?> <br /> <span id="spanMetaTypeBold">Lens Type:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Lens Type'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Line-Count'])) { ?> <br /> <span id="spanMetaTypeBold">Line-Count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Line-Count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['LocalColorTable'])) { ?> <br /> <span id="spanMetaTypeBold">LocalColorTable:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['LocalColorTable'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['LocalColorTable ColorTableEntry'])) { ?> <br /> <span id="spanMetaTypeBold">LocalColorTable ColorTableEntry:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['LocalColorTable ColorTableEntry'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Long Focal Length'])) { ?> <br /> <span id="spanMetaTypeBold">Long Focal Length:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Long Focal Length'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Luminance'])) { ?> <br /> <span id="spanMetaTypeBold">Luminance:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Luminance'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:ANSI Query Mode'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:ANSI Query Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:ANSI Query Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:AccessVersion'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:AccessVersion:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:AccessVersion'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:Auto Compact'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:Auto Compact:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:Auto Compact'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:Build'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:Build:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:Build'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:CpgConversion'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:CpgConversion:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:CpgConversion'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:Perform Name AutoCorrect'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:Perform Name AutoCorrect:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:Perform Name AutoCorrect'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:ProjVer'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:ProjVer:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:ProjVer'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:Remove Personal Information'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:Remove Personal Information:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:Remove Personal Information'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:Row Limit'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:Row Limit:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:Row Limit'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:Show Values Limit'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:Show Values Limit:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:Show Values Limit'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:Show Values in Indexed'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:Show Values in Indexed:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:Show Values in Indexed'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:Show Values in Non-Indexed'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:Show Values in Non-Indexed:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:Show Values in Non-Indexed'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:Show Values in Remote'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:Show Values in Remote:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:Show Values in Remote'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:Show Values in Server'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:Show Values in Server:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:Show Values in Server'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:Show Values in Snapshot'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:Show Values in Snapshot:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:Show Values in Snapshot'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:Themed Form Controls'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:Themed Form Controls:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:Themed Form Controls'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:Track Name AutoCorrect Info'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:Track Name AutoCorrect Info:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:Track Name AutoCorrect Info'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:Use Default Connection File'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:Use Default Connection File:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:Use Default Connection File'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_PROP:Use Default Page Folder'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_PROP:Use Default Page Folder:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_PROP:Use Default Page Folder'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['MDB_USER_PROP:ReplicateProject'])) { ?> <br /> <span id="spanMetaTypeBold">MDB_USER_PROP:ReplicateProject:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['MDB_USER_PROP:ReplicateProject'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Mac Print Info'])) { ?> <br /> <span id="spanMetaTypeBold">Mac Print Info:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Mac Print Info'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Macro'])) { ?> <br /> <span id="spanMetaTypeBold">Macro:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Macro'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Macro Mode'])) { ?> <br /> <span id="spanMetaTypeBold">Macro Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Macro Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Make'])) { ?> <br /> <span id="spanMetaTypeBold">Make:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Make'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Makernote'])) { ?> <br /> <span id="spanMetaTypeBold">Makernote:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Makernote'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Makernote Version'])) { ?> <br /> <span id="spanMetaTypeBold">Makernote Version:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Makernote Version'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Manager'])) { ?> <br /> <span id="spanMetaTypeBold">Manager:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Manager'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Max Aperture Value'])) { ?> <br /> <span id="spanMetaTypeBold">Max Aperture Value:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Max Aperture Value'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Measured Color Array'])) { ?> <br /> <span id="spanMetaTypeBold">Measured Color Array:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Measured Color Array'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Measured EV'])) { ?> <br /> <span id="spanMetaTypeBold">Measured EV:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Measured EV'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Measured EV 2'])) { ?> <br /> <span id="spanMetaTypeBold">Measured EV 2:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Measured EV 2'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Measurement'])) { ?> <br /> <span id="spanMetaTypeBold">Measurement:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Measurement'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Media Black Point'])) { ?> <br /> <span id="spanMetaTypeBold">Media Black Point:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Media Black Point'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Media White Point'])) { ?> <br /> <span id="spanMetaTypeBold">Media White Point:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Media White Point'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Message-Bcc'])) { ?> <br /> <span id="spanMetaTypeBold">Message-Bcc:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Message-Bcc'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Message-Cc'])) { ?> <br /> <span id="spanMetaTypeBold">Message-Cc:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Message-Cc'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Message-From'])) { ?> <br /> <span id="spanMetaTypeBold">Message-From:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Message-From'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Message-Recipient-Address'])) { ?> <br /> <span id="spanMetaTypeBold">Message-Recipient-Address:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Message-Recipient-Address'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Message-To'])) { ?> <br /> <span id="spanMetaTypeBold">Message-To:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Message-To'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Metering Mode'])) { ?> <br /> <span id="spanMetaTypeBold">Metering Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Metering Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Model'])) { ?> <br /> <span id="spanMetaTypeBold">Model:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Model'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Multi Exposure'])) { ?> <br /> <span id="spanMetaTypeBold">Multi Exposure:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Multi Exposure'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['My Colors'])) { ?> <br /> <span id="spanMetaTypeBold">My Colors:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['My Colors'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['ND Filter'])) { ?> <br /> <span id="spanMetaTypeBold">ND Filter:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['ND Filter'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['New Subfile Type'])) { ?> <br /> <span id="spanMetaTypeBold">New Subfile Type:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['New Subfile Type'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Noise Reduction'])) { ?> <br /> <span id="spanMetaTypeBold">Noise Reduction:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Noise Reduction'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Notes'])) { ?> <br /> <span id="spanMetaTypeBold">Notes:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Notes'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Number of Components'])) { ?> <br /> <span id="spanMetaTypeBold">Number of Components:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Number of Components'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Object Data Preview Data'])) { ?> <br /> <span id="spanMetaTypeBold">Object Data Preview Data:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Object Data Preview Data'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Object Data Preview File Format'])) { ?> <br /> <span id="spanMetaTypeBold">Object Data Preview File Format:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Object Data Preview File Format'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Object Data Preview File Format Version'])) { ?> <br /> <span id="spanMetaTypeBold">Object Data Preview File Format Version:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Object Data Preview File Format Version'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Object Name'])) { ?> <br /> <span id="spanMetaTypeBold">Object Name:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Object Name'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['One Touch WB'])) { ?> <br /> <span id="spanMetaTypeBold">One Touch WB:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['One Touch WB'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['OneVisionCreationDate'])) { ?> <br /> <span id="spanMetaTypeBold">OneVisionCreationDate:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['OneVisionCreationDate'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['OneVisionCreator'])) { ?> <br /> <span id="spanMetaTypeBold">OneVisionCreator:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['OneVisionCreator'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['OneVisionDongleID'])) { ?> <br /> <span id="spanMetaTypeBold">OneVisionDongleID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['OneVisionDongleID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['OneVisionProducer'])) { ?> <br /> <span id="spanMetaTypeBold">OneVisionProducer:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['OneVisionProducer'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['OneVisionQueueName'])) { ?> <br /> <span id="spanMetaTypeBold">OneVisionQueueName:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['OneVisionQueueName'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Optical Zoom Code'])) { ?> <br /> <span id="spanMetaTypeBold">Optical Zoom Code:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Optical Zoom Code'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Orientation'])) { ?> <br /> <span id="spanMetaTypeBold">Orientation:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Orientation'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Original Decision Data Offset'])) { ?> <br /> <span id="spanMetaTypeBold">Original Decision Data Offset:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Original Decision Data Offset'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Originating Program'])) { ?> <br /> <span id="spanMetaTypeBold">Originating Program:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Originating Program'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Originator'])) { ?> <br /> <span id="spanMetaTypeBold">Originator:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Originator'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Owner Name'])) { ?> <br /> <span id="spanMetaTypeBold">Owner Name:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Owner Name'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['PSName'])) { ?> <br /> <span id="spanMetaTypeBold">PSName:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['PSName'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Padding'])) { ?> <br /> <span id="spanMetaTypeBold">Padding:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Padding'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Page-Count'])) { ?> <br /> <span id="spanMetaTypeBold">Page-Count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Page-Count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Paragraph-Count'])) { ?> <br /> <span id="spanMetaTypeBold">Paragraph-Count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Paragraph-Count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Password'])) { ?> <br /> <span id="spanMetaTypeBold">Password:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Password'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Photometric Interpretation'])) { ?> <br /> <span id="spanMetaTypeBold">Photometric Interpretation:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Photometric Interpretation'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Pict Info'])) { ?> <br /> <span id="spanMetaTypeBold">Pict Info:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Pict Info'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Picture Control'])) { ?> <br /> <span id="spanMetaTypeBold">Picture Control:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Picture Control'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Picture Mode'])) { ?> <br /> <span id="spanMetaTypeBold">Picture Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Picture Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Pixel Aspect Ratio'])) { ?> <br /> <span id="spanMetaTypeBold">Pixel Aspect Ratio:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Pixel Aspect Ratio'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Planar Configuration'])) { ?> <br /> <span id="spanMetaTypeBold">Planar Configuration:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Planar Configuration'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Platform'])) { ?> <br /> <span id="spanMetaTypeBold">Platform:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Platform'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Plug-in 1 Data'])) { ?> <br /> <span id="spanMetaTypeBold">Plug-in 1 Data:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Plug-in 1 Data'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Plug-in 2 Data'])) { ?> <br /> <span id="spanMetaTypeBold">Plug-in 2 Data:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Plug-in 2 Data'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Pre Capture Frames'])) { ?> <br /> <span id="spanMetaTypeBold">Pre Capture Frames:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Pre Capture Frames'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Presentation-Format'])) { ?> <br /> <span id="spanMetaTypeBold">Presentation-Format:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Presentation-Format'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Preview IFD'])) { ?> <br /> <span id="spanMetaTypeBold">Preview IFD:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Preview IFD'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Primary AF Point 1'])) { ?> <br /> <span id="spanMetaTypeBold">Primary AF Point 1:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Primary AF Point 1'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Primary AF Point 2'])) { ?> <br /> <span id="spanMetaTypeBold">Primary AF Point 2:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Primary AF Point 2'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Primary Platform'])) { ?> <br /> <span id="spanMetaTypeBold">Primary Platform:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Primary Platform'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Print Flags'])) { ?> <br /> <span id="spanMetaTypeBold">Print Flags:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Print Flags'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Print Flags Information'])) { ?> <br /> <span id="spanMetaTypeBold">Print Flags Information:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Print Flags Information'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Print IM'])) { ?> <br /> <span id="spanMetaTypeBold">Print IM:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Print IM'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Print Info'])) { ?> <br /> <span id="spanMetaTypeBold">Print Info:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Print Info'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Print Info 2'])) { ?> <br /> <span id="spanMetaTypeBold">Print Info 2:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Print Info 2'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Print Scale'])) { ?> <br /> <span id="spanMetaTypeBold">Print Scale:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Print Scale'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Print Style'])) { ?> <br /> <span id="spanMetaTypeBold">Print Style:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Print Style'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Processing Information Array'])) { ?> <br /> <span id="spanMetaTypeBold">Processing Information Array:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Processing Information Array'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Profile Connection Space'])) { ?> <br /> <span id="spanMetaTypeBold">Profile Connection Space:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Profile Connection Space'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Profile Date/Time'])) { ?> <br /> <span id="spanMetaTypeBold">Profile Date/Time:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Profile Date/Time'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Profile Description'])) { ?> <br /> <span id="spanMetaTypeBold">Profile Description:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Profile Description'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Profile Size'])) { ?> <br /> <span id="spanMetaTypeBold">Profile Size:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Profile Size'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['ProgId'])) { ?> <br /> <span id="spanMetaTypeBold">ProgId:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['ProgId'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Program Shift'])) { ?> <br /> <span id="spanMetaTypeBold">Program Shift:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Program Shift'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Province/State'])) { ?> <br /> <span id="spanMetaTypeBold">Province/State:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Province/State'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Quality'])) { ?> <br /> <span id="spanMetaTypeBold">Quality:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Quality'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Quality & File Format'])) { ?> <br /> <span id="spanMetaTypeBold">Quality & File Format:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Quality & File Format'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Rating'])) { ?> <br /> <span id="spanMetaTypeBold">Rating:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Rating'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Recommended Exposure Index'])) { ?> <br /> <span id="spanMetaTypeBold">Recommended Exposure Index:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Recommended Exposure Index'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Red Colorant'])) { ?> <br /> <span id="spanMetaTypeBold">Red Colorant:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Red Colorant'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Red TRC'])) { ?> <br /> <span id="spanMetaTypeBold">Red TRC:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Red TRC'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Related Image Height'])) { ?> <br /> <span id="spanMetaTypeBold">Related Image Height:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Related Image Height'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Related Image Width'])) { ?> <br /> <span id="spanMetaTypeBold">Related Image Width:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Related Image Width'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Release Date'])) { ?> <br /> <span id="spanMetaTypeBold">Release Date:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Release Date'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Rendering Intent'])) { ?> <br /> <span id="spanMetaTypeBold">Rendering Intent:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Rendering Intent'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Resolution Info'])) { ?> <br /> <span id="spanMetaTypeBold">Resolution Info:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Resolution Info'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Resolution Unit'])) { ?> <br /> <span id="spanMetaTypeBold">Resolution Unit:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Resolution Unit'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Resolution Units'])) { ?> <br /> <span id="spanMetaTypeBold">Resolution Units:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Resolution Units'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Retouch History'])) { ?> <br /> <span id="spanMetaTypeBold">Retouch History:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Retouch History'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Revision-Number'])) { ?> <br /> <span id="spanMetaTypeBold">Revision-Number:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Revision-Number'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Rows Per Strip'])) { ?> <br /> <span id="spanMetaTypeBold">Rows Per Strip:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Rows Per Strip'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Samples Per Pixel'])) { ?> <br /> <span id="spanMetaTypeBold">Samples Per Pixel:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Samples Per Pixel'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Saturation'])) { ?> <br /> <span id="spanMetaTypeBold">Saturation:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Saturation'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Scene Capture Type'])) { ?> <br /> <span id="spanMetaTypeBold">Scene Capture Type:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Scene Capture Type'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Scene Type'])) { ?> <br /> <span id="spanMetaTypeBold">Scene Type:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Scene Type'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Security'])) { ?> <br /> <span id="spanMetaTypeBold">Security:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Security'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Seed Number'])) { ?> <br /> <span id="spanMetaTypeBold">Seed Number:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Seed Number'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Self Timer 2'])) { ?> <br /> <span id="spanMetaTypeBold">Self Timer 2:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Self Timer 2'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Self Timer Delay'])) { ?> <br /> <span id="spanMetaTypeBold">Self Timer Delay:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Self Timer Delay'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Sensing Method'])) { ?> <br /> <span id="spanMetaTypeBold">Sensing Method:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Sensing Method'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Sensitivity Type'])) { ?> <br /> <span id="spanMetaTypeBold">Sensitivity Type:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Sensitivity Type'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Sensor Information Array'])) { ?> <br /> <span id="spanMetaTypeBold">Sensor Information Array:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Sensor Information Array'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Sequence Number'])) { ?> <br /> <span id="spanMetaTypeBold">Sequence Number:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Sequence Number'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Serial Info Array'])) { ?> <br /> <span id="spanMetaTypeBold">Serial Info Array:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Serial Info Array'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Serial Number'])) { ?> <br /> <span id="spanMetaTypeBold">Serial Number:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Serial Number'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Serial Number Format'])) { ?> <br /> <span id="spanMetaTypeBold">Serial Number Format:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Serial Number Format'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Sharpness'])) { ?> <br /> <span id="spanMetaTypeBold">Sharpness:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Sharpness'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Shooting Mode'])) { ?> <br /> <span id="spanMetaTypeBold">Shooting Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Shooting Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Short Document Identifier'])) { ?> <br /> <span id="spanMetaTypeBold">Short Document Identifier:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Short Document Identifier'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Short Focal Length'])) { ?> <br /> <span id="spanMetaTypeBold">Short Focal Length:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Short Focal Length'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Shot Info'])) { ?> <br /> <span id="spanMetaTypeBold">Shot Info:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Shot Info'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Shutter Speed Value'])) { ?> <br /> <span id="spanMetaTypeBold">Shutter Speed Value:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Shutter Speed Value'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Signature'])) { ?> <br /> <span id="spanMetaTypeBold">Signature:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Signature'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Slices'])) { ?> <br /> <span id="spanMetaTypeBold">Slices:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Slices'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Slide-Count'])) { ?> <br /> <span id="spanMetaTypeBold">Slide-Count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Slide-Count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Slow Shutter'])) { ?> <br /> <span id="spanMetaTypeBold">Slow Shutter:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Slow Shutter'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Slow Sync'])) { ?> <br /> <span id="spanMetaTypeBold">Slow Sync:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Slow Sync'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Software'])) { ?> <br /> <span id="spanMetaTypeBold">Software:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Software'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Source'])) { ?> <br /> <span id="spanMetaTypeBold">Source:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Source'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['SourceModified'])) { ?> <br /> <span id="spanMetaTypeBold">SourceModified:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['SourceModified'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Special Mode'])) { ?> <br /> <span id="spanMetaTypeBold">Special Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Special Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Strip Byte Counts'])) { ?> <br /> <span id="spanMetaTypeBold">Strip Byte Counts:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Strip Byte Counts'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Strip Offsets'])) { ?> <br /> <span id="spanMetaTypeBold">Strip Offsets:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Strip Offsets'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Sub-Sec Time'])) { ?> <br /> <span id="spanMetaTypeBold">Sub-Sec Time:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Sub-Sec Time'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Sub-Sec Time Digitized'])) { ?> <br /> <span id="spanMetaTypeBold">Sub-Sec Time Digitized:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Sub-Sec Time Digitized'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Sub-Sec Time Original'])) { ?> <br /> <span id="spanMetaTypeBold">Sub-Sec Time Original:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Sub-Sec Time Original'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Subject'])) { ?> <br /> <span id="spanMetaTypeBold">Subject:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Subject'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Subject Distance'])) { ?> <br /> <span id="spanMetaTypeBold">Subject Distance:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Subject Distance'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Subject Distance Range'])) { ?> <br /> <span id="spanMetaTypeBold">Subject Distance Range:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Subject Distance Range'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Tag Count'])) { ?> <br /> <span id="spanMetaTypeBold">Tag Count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Tag Count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Target Aperture'])) { ?> <br /> <span id="spanMetaTypeBold">Target Aperture:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Target Aperture'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Target Exposure Time'])) { ?> <br /> <span id="spanMetaTypeBold">Target Exposure Time:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Target Exposure Time'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Technology'])) { ?> <br /> <span id="spanMetaTypeBold">Technology:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Technology'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Template'])) { ?> <br /> <span id="spanMetaTypeBold">Template:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Template'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Thumbnail Compression'])) { ?> <br /> <span id="spanMetaTypeBold">Thumbnail Compression:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Thumbnail Compression'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Thumbnail Data'])) { ?> <br /> <span id="spanMetaTypeBold">Thumbnail Data:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Thumbnail Data'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Thumbnail Image Valid Area'])) { ?> <br /> <span id="spanMetaTypeBold">Thumbnail Image Valid Area:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Thumbnail Image Valid Area'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Thumbnail Length'])) { ?> <br /> <span id="spanMetaTypeBold">Thumbnail Length:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Thumbnail Length'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Thumbnail Offset'])) { ?> <br /> <span id="spanMetaTypeBold">Thumbnail Offset:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Thumbnail Offset'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Time Created'])) { ?> <br /> <span id="spanMetaTypeBold">Time Created:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Time Created'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Total-Time'])) { ?> <br /> <span id="spanMetaTypeBold">Total-Time:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Total-Time'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Trademark'])) { ?> <br /> <span id="spanMetaTypeBold">Trademark:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Trademark'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Transparency Alpha'])) { ?> <br /> <span id="spanMetaTypeBold">Transparency Alpha:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Transparency Alpha'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Transparency TransparentIndex'])) { ?> <br /> <span id="spanMetaTypeBold">Transparency TransparentIndex:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Transparency TransparentIndex'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Type'])) { ?> <br /> <span id="spanMetaTypeBold">Type:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Type'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['URL'])) { ?> <br /> <span id="spanMetaTypeBold">URL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['URL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['URL List'])) { ?> <br /> <span id="spanMetaTypeBold">URL List:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['URL List'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unique Document Identifier'])) { ?> <br /> <span id="spanMetaTypeBold">Unique Document Identifier:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unique Document Identifier'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unique Image ID'])) { ?> <br /> <span id="spanMetaTypeBold">Unique Image ID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unique Image ID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown 20'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown 20:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown 20'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown 27'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown 27:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown 27'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown 40'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown 40:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown 40'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown 41'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown 41:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown 41'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown 49'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown 49:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown 49'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown Camera Setting 10'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown Camera Setting 10:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown Camera Setting 10'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown Camera Setting 12'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown Camera Setting 12:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown Camera Setting 12'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown Camera Setting 13'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown Camera Setting 13:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown Camera Setting 13'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown Camera Setting 2'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown Camera Setting 2:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown Camera Setting 2'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown Camera Setting 3'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown Camera Setting 3:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown Camera Setting 3'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown Camera Setting 4'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown Camera Setting 4:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown Camera Setting 4'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown Camera Setting 7'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown Camera Setting 7:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown Camera Setting 7'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown Camera Setting 9'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown Camera Setting 9:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown Camera Setting 9'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x0000)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x0000):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x0000)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x0003)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x0003):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x0003)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x0017)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x0017):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x0017)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x0018)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x0018):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x0018)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x0019)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x0019):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x0019)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x001f)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x001f):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x001f)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x0022)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x0022):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x0022)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x0027)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x0027):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x0027)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x002d)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x002d):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x002d)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x002e)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x002e):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x002e)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x002f)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x002f):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x002f)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x0031)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x0031):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x0031)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x0032)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x0032):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x0032)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x0033)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x0033):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x0033)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x0035)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x0035):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x0035)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x0238)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x0238):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x0238)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x02b7)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x02b7):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x02b7)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x02bc)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x02bc):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x02bc)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x02e4)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x02e4):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x02e4)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x02e6)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x02e6):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x02e6)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x02e8)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x02e8):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x02e8)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x02f0)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x02f0):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x02f0)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x0301)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x0301):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x0301)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x0303)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x0303):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x0303)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x1022)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x1022):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x1022)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x1032)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x1032):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x1032)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x1200)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x1200):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x1200)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x1303)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x1303):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x1303)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x1408)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x1408):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x1408)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x1409)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x1409):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x1409)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x140a)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x140a):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x140a)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x1422)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x1422):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x1422)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x4008)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x4008):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x4008)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x4009)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x4009):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x4009)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x4011)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x4011):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x4011)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x4012)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x4012):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x4012)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x4017)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x4017):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x4017)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x4200)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x4200):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x4200)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x4749)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x4749):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x4749)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x5110)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x5110):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x5110)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x5111)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x5111):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x5111)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x5112)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x5112):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x5112)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x8568)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x8568):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x8568)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x8649)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x8649):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x8649)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0x935c)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0x935c):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0x935c)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc100)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc100):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc100)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc121)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc121):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc121)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc122)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc122):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc122)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc123)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc123):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc123)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc124)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc124):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc124)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc125)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc125):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc125)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc126)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc126):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc126)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc127)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc127):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc127)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc128)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc128):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc128)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc129)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc129):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc129)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc12a)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc12a):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc12a)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc12b)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc12b):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc12b)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc12c)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc12c):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc12c)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc12d)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc12d):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc12d)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc12e)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc12e):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc12e)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc12f)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc12f):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc12f)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc130)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc130):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc130)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc200)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc200):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc200)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc201)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc201):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc201)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc202)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc202):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc202)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc203)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc203):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc203)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc400)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc400):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc400)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc40b)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc40b):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc40b)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc419)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc419):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc419)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc41e)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc41e):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc41e)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc41f)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc41f):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc41f)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xc420)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xc420):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xc420)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xd20d)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xd20d):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xd20d)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xd20e)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xd20e):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xd20e)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xd20f)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xd20f):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xd20f)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xd210)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xd210):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xd210)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xd211)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xd211):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xd211)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xd212)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xd212):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xd212)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xd213)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xd213):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xd213)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xd214)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xd214):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xd214)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xd215)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xd215):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xd215)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xd216)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xd216):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xd216)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xd217)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xd217):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xd217)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xd218)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xd218):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xd218)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xd219)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xd219):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xd219)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xd21a)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xd21a):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xd21a)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xd21b)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xd21b):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xd21b)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Unknown tag (0xea1d)'])) { ?> <br /> <span id="spanMetaTypeBold">Unknown tag (0xea1d):&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Unknown tag (0xea1d)'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Urgency'])) { ?> <br /> <span id="spanMetaTypeBold">Urgency:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Urgency'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['User Comment'])) { ?> <br /> <span id="spanMetaTypeBold">User Comment:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['User Comment'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['VR Info'])) { ?> <br /> <span id="spanMetaTypeBold">VR Info:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['VR Info'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['VRD Offset'])) { ?> <br /> <span id="spanMetaTypeBold">VRD Offset:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['VRD Offset'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Valid AF Point Count'])) { ?> <br /> <span id="spanMetaTypeBold">Valid AF Point Count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Valid AF Point Count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Version Info'])) { ?> <br /> <span id="spanMetaTypeBold">Version Info:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Version Info'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Viewing Conditions'])) { ?> <br /> <span id="spanMetaTypeBold">Viewing Conditions:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Viewing Conditions'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Viewing Conditions Description'])) { ?> <br /> <span id="spanMetaTypeBold">Viewing Conditions Description:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Viewing Conditions Description'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Vignetting Correction Array 1'])) { ?> <br /> <span id="spanMetaTypeBold">Vignetting Correction Array 1:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Vignetting Correction Array 1'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Vignetting Correction Array 2'])) { ?> <br /> <span id="spanMetaTypeBold">Vignetting Correction Array 2:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Vignetting Correction Array 2'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['White Balance'])) { ?> <br /> <span id="spanMetaTypeBold">White Balance:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['White Balance'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['White Balance Bias'])) { ?> <br /> <span id="spanMetaTypeBold">White Balance Bias:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['White Balance Bias'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['White Balance Bracket'])) { ?> <br /> <span id="spanMetaTypeBold">White Balance Bracket:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['White Balance Bracket'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['White Balance Fine'])) { ?> <br /> <span id="spanMetaTypeBold">White Balance Fine:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['White Balance Fine'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['White Balance Mode'])) { ?> <br /> <span id="spanMetaTypeBold">White Balance Mode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['White Balance Mode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['White Balance RB Coefficients'])) { ?> <br /> <span id="spanMetaTypeBold">White Balance RB Coefficients:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['White Balance RB Coefficients'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['White Board'])) { ?> <br /> <span id="spanMetaTypeBold">White Board:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['White Board'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Win DEVMODE'])) { ?> <br /> <span id="spanMetaTypeBold">Win DEVMODE:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Win DEVMODE'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Windows XP Author'])) { ?> <br /> <span id="spanMetaTypeBold">Windows XP Author:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Windows XP Author'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Windows XP Comment'])) { ?> <br /> <span id="spanMetaTypeBold">Windows XP Comment:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Windows XP Comment'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Windows XP Keywords'])) { ?> <br /> <span id="spanMetaTypeBold">Windows XP Keywords:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Windows XP Keywords'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Windows XP Subject'])) { ?> <br /> <span id="spanMetaTypeBold">Windows XP Subject:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Windows XP Subject'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Windows XP Title'])) { ?> <br /> <span id="spanMetaTypeBold">Windows XP Title:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Windows XP Title'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Word-Count'])) { ?> <br /> <span id="spanMetaTypeBold">Word-Count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Word-Count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['World Time'])) { ?> <br /> <span id="spanMetaTypeBold">World Time:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['World Time'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['X Resolution'])) { ?> <br /> <span id="spanMetaTypeBold">X Resolution:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['X Resolution'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['X-Parsed-By'])) { ?> <br /> <span id="spanMetaTypeBold">X-Parsed-By:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['X-Parsed-By'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['X-TIKA:EXCEPTION:warn'])) { ?> <br /> <span id="spanMetaTypeBold">X-TIKA:EXCEPTION:warn:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['X-TIKA:EXCEPTION:warn'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['X-UA-Compatible'])) { ?> <br /> <span id="spanMetaTypeBold">X-UA-Compatible:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['X-UA-Compatible'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['XML Data'])) { ?> <br /> <span id="spanMetaTypeBold">XML Data:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['XML Data'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['XMP Value Count'])) { ?> <br /> <span id="spanMetaTypeBold">XMP Value Count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['XMP Value Count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['XPressPrivate'])) { ?> <br /> <span id="spanMetaTypeBold">XPressPrivate:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['XPressPrivate'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['XYZ values'])) { ?> <br /> <span id="spanMetaTypeBold">XYZ values:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['XYZ values'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['Y Resolution'])) { ?> <br /> <span id="spanMetaTypeBold">Y Resolution:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['Y Resolution'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['YCbCr Positioning'])) { ?> <br /> <span id="spanMetaTypeBold">YCbCr Positioning:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['YCbCr Positioning'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['_AdHocReviewCycleID'])) { ?> <br /> <span id="spanMetaTypeBold">_AdHocReviewCycleID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['_AdHocReviewCycleID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['_AuthorEmail'])) { ?> <br /> <span id="spanMetaTypeBold">_AuthorEmail:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['_AuthorEmail'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['_AuthorEmailDisplayName'])) { ?> <br /> <span id="spanMetaTypeBold">_AuthorEmailDisplayName:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['_AuthorEmailDisplayName'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['_EmailSubject'])) { ?> <br /> <span id="spanMetaTypeBold">_EmailSubject:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['_EmailSubject'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['_PreviousAdHocReviewCycleID'])) { ?> <br /> <span id="spanMetaTypeBold">_PreviousAdHocReviewCycleID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['_PreviousAdHocReviewCycleID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['access_permission:assemble_document'])) { ?> <br /> <span id="spanMetaTypeBold">access_permission:assemble_document:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['access_permission:assemble_document'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['access_permission:can_modify'])) { ?> <br /> <span id="spanMetaTypeBold">access_permission:can_modify:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['access_permission:can_modify'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['access_permission:can_print'])) { ?> <br /> <span id="spanMetaTypeBold">access_permission:can_print:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['access_permission:can_print'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['access_permission:can_print_degraded'])) { ?> <br /> <span id="spanMetaTypeBold">access_permission:can_print_degraded:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['access_permission:can_print_degraded'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['access_permission:extract_content'])) { ?> <br /> <span id="spanMetaTypeBold">access_permission:extract_content:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['access_permission:extract_content'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['access_permission:extract_for_accessibility'])) { ?> <br /> <span id="spanMetaTypeBold">access_permission:extract_for_accessibility:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['access_permission:extract_for_accessibility'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['access_permission:fill_in_form'])) { ?> <br /> <span id="spanMetaTypeBold">access_permission:fill_in_form:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['access_permission:fill_in_form'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['access_permission:modify_annotations'])) { ?> <br /> <span id="spanMetaTypeBold">access_permission:modify_annotations:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['access_permission:modify_annotations'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['bits'])) { ?> <br /> <span id="spanMetaTypeBold">bits:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['bits'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['channels'])) { ?> <br /> <span id="spanMetaTypeBold">channels:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['channels'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['comment'])) { ?> <br /> <span id="spanMetaTypeBold">comment:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['comment'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['content-language'])) { ?> <br /> <span id="spanMetaTypeBold">content-language:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['content-language'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['cp:category'])) { ?> <br /> <span id="spanMetaTypeBold">cp:category:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['cp:category'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['cp:revision'])) { ?> <br /> <span id="spanMetaTypeBold">cp:revision:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['cp:revision'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['cp:subject'])) { ?> <br /> <span id="spanMetaTypeBold">cp:subject:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['cp:subject'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['created'])) { ?> <br /> <span id="spanMetaTypeBold">created:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['created'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['creator'])) { ?> <br /> <span id="spanMetaTypeBold">creator:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['creator'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:% Complete'])) { ?> <br /> <span id="spanMetaTypeBold">custom:% Complete:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:% Complete'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:% Work Complete'])) { ?> <br /> <span id="spanMetaTypeBold">custom:% Work Complete:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:% Work Complete'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:AddParalink'])) { ?> <br /> <span id="spanMetaTypeBold">custom:AddParalink:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:AddParalink'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:AppVersion'])) { ?> <br /> <span id="spanMetaTypeBold">custom:AppVersion:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:AppVersion'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Approved Account Received'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Approved Account Received:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Approved Account Received'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Audit Fieldwork Completed'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Audit Fieldwork Completed:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Audit Fieldwork Completed'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Audit Supplement'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Audit Supplement:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Audit Supplement'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Certified'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Certified:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Certified'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Cleared'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Cleared:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Cleared'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Company'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Company:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Company'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:ContentTypeId'])) { ?> <br /> <span id="spanMetaTypeBold">custom:ContentTypeId:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:ContentTypeId'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Cost'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Cost:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Cost'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Created'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Created:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Created'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Created Date'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Created Date:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Created Date'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Created using'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Created using:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Created using'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:DeletePLink'])) { ?> <br /> <span id="spanMetaTypeBold">custom:DeletePLink:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:DeletePLink'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Disposal of Audit Queries'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Disposal of Audit Queries:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Disposal of Audit Queries'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:DocID_EU'])) { ?> <br /> <span id="spanMetaTypeBold">custom:DocID_EU:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:DocID_EU'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:DocSecurity'])) { ?> <br /> <span id="spanMetaTypeBold">custom:DocSecurity:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:DocSecurity'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Draft Account Received'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Draft Account Received:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Draft Account Received'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:EL_Author'])) { ?> <br /> <span id="spanMetaTypeBold">custom:EL_Author:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:EL_Author'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:EL_Language'])) { ?> <br /> <span id="spanMetaTypeBold">custom:EL_Language:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:EL_Language'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Editor'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Editor:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Editor'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:EurolookVersion'])) { ?> <br /> <span id="spanMetaTypeBold">custom:EurolookVersion:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:EurolookVersion'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Final Audit Started'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Final Audit Started:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Final Audit Started'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Formatting'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Formatting:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Formatting'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:HyperlinksChanged'])) { ?> <br /> <span id="spanMetaTypeBold">custom:HyperlinksChanged:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:HyperlinksChanged'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Interim Completed'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Interim Completed:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Interim Completed'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Interim Started'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Interim Started:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Interim Started'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:LCID'])) { ?> <br /> <span id="spanMetaTypeBold">custom:LCID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:LCID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Language'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Language:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Language'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Last Modified'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Last Modified:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Last Modified'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Last edited using'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Last edited using:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Last edited using'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:LinksUpToDate'])) { ?> <br /> <span id="spanMetaTypeBold">custom:LinksUpToDate:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:LinksUpToDate'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:MSCRMVersion'])) { ?> <br /> <span id="spanMetaTypeBold">custom:MSCRMVersion:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:MSCRMVersion'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Modified'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Modified:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Modified'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Order'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Order:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Order'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Owner'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Owner:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Owner'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:P2ToP3Cnvtd'])) { ?> <br /> <span id="spanMetaTypeBold">custom:P2ToP3Cnvtd:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:P2ToP3Cnvtd'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_0_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_0_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_0_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_10_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_10_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_10_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_11_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_11_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_11_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_12_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_12_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_12_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_13_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_13_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_13_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_14_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_14_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_14_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_15_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_15_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_15_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_16_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_16_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_16_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_17_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_17_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_17_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_18_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_18_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_18_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_19_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_19_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_19_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_1_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_1_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_1_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_20_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_20_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_20_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_21_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_21_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_21_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_22_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_22_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_22_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_23_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_23_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_23_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_24_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_24_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_24_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_25_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_25_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_25_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_26_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_26_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_26_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_27_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_27_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_27_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_28_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_28_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_28_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_2_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_2_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_2_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_3_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_3_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_3_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_4_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_4_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_4_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_5_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_5_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_5_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_6_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_6_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_6_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_7_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_7_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_7_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_8_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_8_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_8_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_-1_9_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_-1_9_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_-1_9_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_1_5_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_1_5_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_1_5_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_1_6_0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_1_6_0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_1_6_0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_5_1_1'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_5_1_1:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_5_1_1'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_5_1_2'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_5_1_2:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_5_1_2'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PD3_5_3_1'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PD3_5_3_1:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PD3_5_3_1'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdAllOrgIDs'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdAllOrgIDs:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdAllOrgIDs'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdAllPosIDs'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdAllPosIDs:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdAllPosIDs'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdAuthorisor'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdAuthorisor:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdAuthorisor'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdCompanyName'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdCompanyName:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdCompanyName'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdEffectDate'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdEffectDate:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdEffectDate'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdExtension'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdExtension:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdExtension'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdGroupName'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdGroupName:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdGroupName'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdItemID'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdItemID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdItemID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdItemLabel'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdItemLabel:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdItemLabel'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdItemModuleID'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdItemModuleID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdItemModuleID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdItemModuleName'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdItemModuleName:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdItemModuleName'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdItemName'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdItemName:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdItemName'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdItemOrigID'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdItemOrigID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdItemOrigID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdItemStatus'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdItemStatus:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdItemStatus'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdItemType'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdItemType:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdItemType'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdLastDate'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdLastDate:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdLastDate'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdLastDateOnly'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdLastDateOnly:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdLastDateOnly'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdOperatorName'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdOperatorName:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdOperatorName'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdParentGroup'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdParentGroup:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdParentGroup'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdPrintNumber'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdPrintNumber:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdPrintNumber'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdReminderDate'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdReminderDate:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdReminderDate'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdResponsible'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdResponsible:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdResponsible'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdReviewDate'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdReviewDate:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdReviewDate'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdUserID'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdUserID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdUserID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdVersion'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdVersion:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdVersion'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdVersionDate'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdVersionDate:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdVersionDate'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdVersionDateOnly'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdVersionDateOnly:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdVersionDateOnly'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:PdWaterMark'])) { ?> <br /> <span id="spanMetaTypeBold">custom:PdWaterMark:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:PdWaterMark'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Plan Approved'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Plan Approved:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Plan Approved'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:SPSDescription'])) { ?> <br /> <span id="spanMetaTypeBold">custom:SPSDescription:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:SPSDescription'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:ScaleCrop'])) { ?> <br /> <span id="spanMetaTypeBold">custom:ScaleCrop:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:ScaleCrop'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Scheduled Duration'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Scheduled Duration:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Scheduled Duration'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Scheduled Finish'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Scheduled Finish:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Scheduled Finish'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Scheduled Start'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Scheduled Start:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Scheduled Start'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:ShareDoc'])) { ?> <br /> <span id="spanMetaTypeBold">custom:ShareDoc:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:ShareDoc'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Source'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Source:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Source'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Status'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Status:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Status'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_BU_NAME'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_BU_NAME:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_BU_NAME'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_COST_AVOID'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_COST_AVOID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_COST_AVOID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_COST_SAVE'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_COST_SAVE:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_COST_SAVE'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EDIT_DATE'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EDIT_DATE:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EDIT_DATE'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_COST1'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_COST1:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_COST1'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_COST2'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_COST2:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_COST2'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_FINDING_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_FINDING_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_FINDING_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_IMPACT'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_IMPACT:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_IMPACT'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_LEVEL1_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_LEVEL1_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_LEVEL1_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_LEVEL2_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_LEVEL2_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_LEVEL2_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_LEVEL3_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_LEVEL3_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_LEVEL3_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_LIKELIHOOD'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_LIKELIHOOD:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_LIKELIHOOD'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_PRIORITY_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_PRIORITY_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_PRIORITY_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_RECTEXT1_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_RECTEXT1_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_RECTEXT1_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_RECTEXT2_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_RECTEXT2_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_RECTEXT2_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_RECTYPE1_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_RECTYPE1_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_RECTYPE1_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_RECTYPE2_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_RECTYPE2_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_RECTYPE2_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_RS_IMPLEMENTED_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_RS_IMPLEMENTED_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_RS_IMPLEMENTED_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_RS_INPROGRESS_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_RS_INPROGRESS_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_RS_INPROGRESS_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_RS_NOTRELEVANT_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_RS_NOTRELEVANT_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_RS_NOTRELEVANT_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_RS_NOTVERIFIED_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_RS_NOTVERIFIED_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_RS_NOTVERIFIED_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_RS_OPEN_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_RS_OPEN_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_RS_OPEN_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_RS_PENDING_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_RS_PENDING_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_RS_PENDING_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_RS_RISKACCEPTED_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_RS_RISKACCEPTED_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_RS_RISKACCEPTED_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_RS_VERIFIED_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_RS_VERIFIED_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_RS_VERIFIED_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_TAB1_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_TAB1_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_TAB1_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_TAB2_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_TAB2_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_TAB2_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_TAB3_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_TAB3_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_TAB3_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_TAB4_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_TAB4_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_TAB4_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_TAB5_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_TAB5_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_TAB5_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_TAB6_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_TAB6_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_TAB6_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_TAB8_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_TAB8_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_TAB8_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_USERCAT1_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_USERCAT1_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_USERCAT1_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_USERCAT2_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_USERCAT2_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_USERCAT2_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_USERCAT3_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_USERCAT3_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_USERCAT3_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_USERCAT4_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_USERCAT4_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_USERCAT4_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_EX_USERTEXT_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_EX_USERTEXT_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_EX_USERTEXT_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_FINDING_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_FINDING_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_FINDING_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_LEAD'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_LEAD:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_LEAD'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_MANAGEMENT_ADDRESS'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_MANAGEMENT_ADDRESS:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_MANAGEMENT_ADDRESS'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_MANAGEMENT_FIRST'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_MANAGEMENT_FIRST:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_MANAGEMENT_FIRST'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_MANAGEMENT_LAST'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_MANAGEMENT_LAST:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_MANAGEMENT_LAST'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_MANAGEMENT_TITLE'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_MANAGEMENT_TITLE:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_MANAGEMENT_TITLE'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_MANAGER'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_MANAGER:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_MANAGER'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_PARENT_NAME'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_PARENT_NAME:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_PARENT_NAME'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_PRIMARY_ADDRESS'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_PRIMARY_ADDRESS:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_PRIMARY_ADDRESS'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_PRIMARY_FIRST'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_PRIMARY_FIRST:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_PRIMARY_FIRST'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_PRIMARY_LAST'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_PRIMARY_LAST:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_PRIMARY_LAST'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_PRIMARY_TITLE'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_PRIMARY_TITLE:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_PRIMARY_TITLE'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_PROJECT_BKGRD_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_PROJECT_BKGRD_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_PROJECT_BKGRD_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_PROJECT_COST1_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_PROJECT_COST1_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_PROJECT_COST1_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_PROJECT_COST2_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_PROJECT_COST2_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_PROJECT_COST2_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_PROJECT_DATE'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_PROJECT_DATE:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_PROJECT_DATE'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_PROJECT_OBJ_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_PROJECT_OBJ_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_PROJECT_OBJ_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_PROJECT_PLAN_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_PROJECT_PLAN_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_PROJECT_PLAN_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_PROJECT_STAFF'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_PROJECT_STAFF:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_PROJECT_STAFF'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_PROJECT_TYPE'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_PROJECT_TYPE:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_PROJECT_TYPE'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_PROJECT_USERTEXT1_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_PROJECT_USERTEXT1_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_PROJECT_USERTEXT1_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_PROJECT_USERTEXT2_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_PROJECT_USERTEXT2_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_PROJECT_USERTEXT2_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_REPORT_TITLE'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_REPORT_TITLE:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_REPORT_TITLE'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_SHORT_NAME'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_SHORT_NAME:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_SHORT_NAME'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_TAB1_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_TAB1_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_TAB1_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_TAB2_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_TAB2_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_TAB2_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_TAB3_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_TAB3_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_TAB3_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_TAB4_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_TAB4_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_TAB4_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TM_TAB5_LABEL'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TM_TAB5_LABEL:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TM_TAB5_LABEL'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TemplateUrl'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TemplateUrl:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TemplateUrl'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:TemplateVersion'])) { ?> <br /> <span id="spanMetaTypeBold">custom:TemplateVersion:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:TemplateVersion'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:To Client'])) { ?> <br /> <span id="spanMetaTypeBold">custom:To Client:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:To Client'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:To Deputy Director'])) { ?> <br /> <span id="spanMetaTypeBold">custom:To Deputy Director:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:To Deputy Director'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:To Director'])) { ?> <br /> <span id="spanMetaTypeBold">custom:To Director:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:To Director'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:To Minister'])) { ?> <br /> <span id="spanMetaTypeBold">custom:To Minister:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:To Minister'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:To Oireachtas'])) { ?> <br /> <span id="spanMetaTypeBold">custom:To Oireachtas:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:To Oireachtas'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Type'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Type:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Type'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:UseDefaultLanguage'])) { ?> <br /> <span id="spanMetaTypeBold">custom:UseDefaultLanguage:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:UseDefaultLanguage'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Version'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Version:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Version'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:Work'])) { ?> <br /> <span id="spanMetaTypeBold">custom:Work:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:Work'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_AdHocReviewCycleID'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_AdHocReviewCycleID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_AdHocReviewCycleID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_AuthorEmail'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_AuthorEmail:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_AuthorEmail'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_AuthorEmailDisplayName'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_AuthorEmailDisplayName:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_AuthorEmailDisplayName'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_DocHome'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_DocHome:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_DocHome'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_EmailEntryID'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_EmailEntryID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_EmailEntryID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_EmailStoreID0'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_EmailStoreID0:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_EmailStoreID0'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_EmailStoreID1'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_EmailStoreID1:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_EmailStoreID1'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_EmailSubject'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_EmailSubject:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_EmailSubject'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_NewReviewCycle'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_NewReviewCycle:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_NewReviewCycle'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_PreviousAdHocReviewCycleID'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_PreviousAdHocReviewCycleID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_PreviousAdHocReviewCycleID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_ReviewCycleID'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_ReviewCycleID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_ReviewCycleID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_ReviewingToolsShownOnce'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_ReviewingToolsShownOnce:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_ReviewingToolsShownOnce'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_SharedFileIndex'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_SharedFileIndex:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_SharedFileIndex'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_SourceUrl'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_SourceUrl:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_SourceUrl'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_TemplateID'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_TemplateID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_TemplateID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_TentativeReviewCycleID'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_TentativeReviewCycleID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_TentativeReviewCycleID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_VPID_ALTERNATENAMES'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_VPID_ALTERNATENAMES:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_VPID_ALTERNATENAMES'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_dlc_DocId'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_dlc_DocId:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_dlc_DocId'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_dlc_DocIdItemGuid'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_dlc_DocIdItemGuid:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_dlc_DocIdItemGuid'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:_dlc_DocIdUrl'])) { ?> <br /> <span id="spanMetaTypeBold">custom:_dlc_DocIdUrl:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:_dlc_DocIdUrl'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:display_urn:schemas-microsoft-com:office:office#Author'])) { ?> <br /> <span id="spanMetaTypeBold">custom:display_urn:schemas-microsoft-com:office:office#Author:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:display_urn:schemas-microsoft-com:office:office#Author'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:display_urn:schemas-microsoft-com:office:office#Editor'])) { ?> <br /> <span id="spanMetaTypeBold">custom:display_urn:schemas-microsoft-com:office:office#Editor:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:display_urn:schemas-microsoft-com:office:office#Editor'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:xd_ProgID'])) { ?> <br /> <span id="spanMetaTypeBold">custom:xd_ProgID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:xd_ProgID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:xd_Signature'])) { ?> <br /> <span id="spanMetaTypeBold">custom:xd_Signature:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:xd_Signature'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['custom:{DFC8691F-2432-4741-B780-3CAE3235A612}'])) { ?> <br /> <span id="spanMetaTypeBold">custom:{DFC8691F-2432-4741-B780-3CAE3235A612}:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['custom:{DFC8691F-2432-4741-B780-3CAE3235A612}'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['date'])) { ?> <br /> <span id="spanMetaTypeBold">date:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['date'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['dc:creator'])) { ?> <br /> <span id="spanMetaTypeBold">dc:creator:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['dc:creator'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['dc:description'])) { ?> <br /> <span id="spanMetaTypeBold">dc:description:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['dc:description'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['dc:format'])) { ?> <br /> <span id="spanMetaTypeBold">dc:format:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['dc:format'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['dc:language'])) { ?> <br /> <span id="spanMetaTypeBold">dc:language:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['dc:language'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['dc:publisher'])) { ?> <br /> <span id="spanMetaTypeBold">dc:publisher:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['dc:publisher'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['dc:subject'])) { ?> <br /> <span id="spanMetaTypeBold">dc:subject:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['dc:subject'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['dc:title'])) { ?> <br /> <span id="spanMetaTypeBold">dc:title:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['dc:title'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['dcterms:created'])) { ?> <br /> <span id="spanMetaTypeBold">dcterms:created:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['dcterms:created'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['dcterms:modified'])) { ?> <br /> <span id="spanMetaTypeBold">dcterms:modified:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['dcterms:modified'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['description'])) { ?> <br /> <span id="spanMetaTypeBold">description:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['description'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['encoding'])) { ?> <br /> <span id="spanMetaTypeBold">encoding:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['encoding'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['exif:DateTimeOriginal'])) { ?> <br /> <span id="spanMetaTypeBold">exif:DateTimeOriginal:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['exif:DateTimeOriginal'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['exif:ExposureTime'])) { ?> <br /> <span id="spanMetaTypeBold">exif:ExposureTime:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['exif:ExposureTime'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['exif:FNumber'])) { ?> <br /> <span id="spanMetaTypeBold">exif:FNumber:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['exif:FNumber'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['exif:Flash'])) { ?> <br /> <span id="spanMetaTypeBold">exif:Flash:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['exif:Flash'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['exif:FocalLength'])) { ?> <br /> <span id="spanMetaTypeBold">exif:FocalLength:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['exif:FocalLength'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['exif:IsoSpeedRatings'])) { ?> <br /> <span id="spanMetaTypeBold">exif:IsoSpeedRatings:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['exif:IsoSpeedRatings'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['extended-properties:AppVersion'])) { ?> <br /> <span id="spanMetaTypeBold">extended-properties:AppVersion:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['extended-properties:AppVersion'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['extended-properties:Application'])) { ?> <br /> <span id="spanMetaTypeBold">extended-properties:Application:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['extended-properties:Application'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['extended-properties:Company'])) { ?> <br /> <span id="spanMetaTypeBold">extended-properties:Company:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['extended-properties:Company'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['extended-properties:DocSecurity'])) { ?> <br /> <span id="spanMetaTypeBold">extended-properties:DocSecurity:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['extended-properties:DocSecurity'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['extended-properties:Manager'])) { ?> <br /> <span id="spanMetaTypeBold">extended-properties:Manager:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['extended-properties:Manager'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['extended-properties:Notes'])) { ?> <br /> <span id="spanMetaTypeBold">extended-properties:Notes:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['extended-properties:Notes'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['extended-properties:PresentationFormat'])) { ?> <br /> <span id="spanMetaTypeBold">extended-properties:PresentationFormat:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['extended-properties:PresentationFormat'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['extended-properties:Template'])) { ?> <br /> <span id="spanMetaTypeBold">extended-properties:Template:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['extended-properties:Template'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['extended-properties:TotalTime'])) { ?> <br /> <span id="spanMetaTypeBold">extended-properties:TotalTime:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['extended-properties:TotalTime'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['gAMA'])) { ?> <br /> <span id="spanMetaTypeBold">gAMA:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['gAMA'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['geo:lat'])) { ?> <br /> <span id="spanMetaTypeBold">geo:lat:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['geo:lat'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['geo:long'])) { ?> <br /> <span id="spanMetaTypeBold">geo:long:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['geo:long'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['height'])) { ?> <br /> <span id="spanMetaTypeBold">height:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['height'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['language'])) { ?> <br /> <span id="spanMetaTypeBold">language:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['language'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['machine:architectureBits'])) { ?> <br /> <span id="spanMetaTypeBold">machine:architectureBits:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['machine:architectureBits'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['machine:endian'])) { ?> <br /> <span id="spanMetaTypeBold">machine:endian:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['machine:endian'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['machine:machineType'])) { ?> <br /> <span id="spanMetaTypeBold">machine:machineType:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['machine:machineType'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['machine:platform'])) { ?> <br /> <span id="spanMetaTypeBold">machine:platform:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['machine:platform'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['meta:author'])) { ?> <br /> <span id="spanMetaTypeBold">meta:author:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['meta:author'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['meta:character-count'])) { ?> <br /> <span id="spanMetaTypeBold">meta:character-count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['meta:character-count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['meta:character-count-with-spaces'])) { ?> <br /> <span id="spanMetaTypeBold">meta:character-count-with-spaces:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['meta:character-count-with-spaces'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['meta:creation-date'])) { ?> <br /> <span id="spanMetaTypeBold">meta:creation-date:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['meta:creation-date'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['meta:keyword'])) { ?> <br /> <span id="spanMetaTypeBold">meta:keyword:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['meta:keyword'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['meta:last-author'])) { ?> <br /> <span id="spanMetaTypeBold">meta:last-author:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['meta:last-author'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['meta:line-count'])) { ?> <br /> <span id="spanMetaTypeBold">meta:line-count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['meta:line-count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['meta:page-count'])) { ?> <br /> <span id="spanMetaTypeBold">meta:page-count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['meta:page-count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['meta:paragraph-count'])) { ?> <br /> <span id="spanMetaTypeBold">meta:paragraph-count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['meta:paragraph-count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['meta:print-date'])) { ?> <br /> <span id="spanMetaTypeBold">meta:print-date:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['meta:print-date'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['meta:save-date'])) { ?> <br /> <span id="spanMetaTypeBold">meta:save-date:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['meta:save-date'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['meta:slide-count'])) { ?> <br /> <span id="spanMetaTypeBold">meta:slide-count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['meta:slide-count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['meta:word-count'])) { ?> <br /> <span id="spanMetaTypeBold">meta:word-count:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['meta:word-count'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['modified'])) { ?> <br /> <span id="spanMetaTypeBold">modified:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['modified'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['pHYs'])) { ?> <br /> <span id="spanMetaTypeBold">pHYs:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['pHYs'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['pdf:PDFExtensionVersion'])) { ?> <br /> <span id="spanMetaTypeBold">pdf:PDFExtensionVersion:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['pdf:PDFExtensionVersion'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['pdf:PDFVersion'])) { ?> <br /> <span id="spanMetaTypeBold">pdf:PDFVersion:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['pdf:PDFVersion'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['pdf:encrypted'])) { ?> <br /> <span id="spanMetaTypeBold">pdf:encrypted:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['pdf:encrypted'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['pdfa:PDFVersion'])) { ?> <br /> <span id="spanMetaTypeBold">pdfa:PDFVersion:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['pdfa:PDFVersion'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['pdfaid:conformance'])) { ?> <br /> <span id="spanMetaTypeBold">pdfaid:conformance:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['pdfaid:conformance'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['pdfaid:part'])) { ?> <br /> <span id="spanMetaTypeBold">pdfaid:part:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['pdfaid:part'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['photoshop:ColorMode'])) { ?> <br /> <span id="spanMetaTypeBold">photoshop:ColorMode:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['photoshop:ColorMode'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['producer'])) { ?> <br /> <span id="spanMetaTypeBold">producer:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['producer'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['protected'])) { ?> <br /> <span id="spanMetaTypeBold">protected:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['protected'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['publisher'])) { ?> <br /> <span id="spanMetaTypeBold">publisher:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['publisher'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['sRGB'])) { ?> <br /> <span id="spanMetaTypeBold">sRGB:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['sRGB'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['samplerate'])) { ?> <br /> <span id="spanMetaTypeBold">samplerate:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['samplerate'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['subject'])) { ?> <br /> <span id="spanMetaTypeBold">subject:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['subject'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['tiff:BitsPerSample'])) { ?> <br /> <span id="spanMetaTypeBold">tiff:BitsPerSample:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['tiff:BitsPerSample'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['tiff:ImageLength'])) { ?> <br /> <span id="spanMetaTypeBold">tiff:ImageLength:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['tiff:ImageLength'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['tiff:ImageWidth'])) { ?> <br /> <span id="spanMetaTypeBold">tiff:ImageWidth:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['tiff:ImageWidth'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['tiff:Make'])) { ?> <br /> <span id="spanMetaTypeBold">tiff:Make:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['tiff:Make'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['tiff:Model'])) { ?> <br /> <span id="spanMetaTypeBold">tiff:Model:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['tiff:Model'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['tiff:Orientation'])) { ?> <br /> <span id="spanMetaTypeBold">tiff:Orientation:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['tiff:Orientation'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['tiff:ResolutionUnit'])) { ?> <br /> <span id="spanMetaTypeBold">tiff:ResolutionUnit:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['tiff:ResolutionUnit'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['tiff:SamplesPerPixel'])) { ?> <br /> <span id="spanMetaTypeBold">tiff:SamplesPerPixel:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['tiff:SamplesPerPixel'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['tiff:Software'])) { ?> <br /> <span id="spanMetaTypeBold">tiff:Software:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['tiff:Software'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['tiff:XResolution'])) { ?> <br /> <span id="spanMetaTypeBold">tiff:XResolution:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['tiff:XResolution'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['tiff:YResolution'])) { ?> <br /> <span id="spanMetaTypeBold">tiff:YResolution:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['tiff:YResolution'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['title'])) { ?> <br /> <span id="spanMetaTypeBold">title:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['title'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['trapped'])) { ?> <br /> <span id="spanMetaTypeBold">trapped:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['trapped'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['version'])) { ?> <br /> <span id="spanMetaTypeBold">version:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['version'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['w:comments'])) { ?> <br /> <span id="spanMetaTypeBold">w:comments:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['w:comments'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['width'])) { ?> <br /> <span id="spanMetaTypeBold">width:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['width'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmp:CreatorTool'])) { ?> <br /> <span id="spanMetaTypeBold">xmp:CreatorTool:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmp:CreatorTool'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpDM:album'])) { ?> <br /> <span id="spanMetaTypeBold">xmpDM:album:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpDM:album'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpDM:artist'])) { ?> <br /> <span id="spanMetaTypeBold">xmpDM:artist:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpDM:artist'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpDM:audioChannelType'])) { ?> <br /> <span id="spanMetaTypeBold">xmpDM:audioChannelType:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpDM:audioChannelType'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpDM:audioCompressor'])) { ?> <br /> <span id="spanMetaTypeBold">xmpDM:audioCompressor:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpDM:audioCompressor'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpDM:audioSampleRate'])) { ?> <br /> <span id="spanMetaTypeBold">xmpDM:audioSampleRate:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpDM:audioSampleRate'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpDM:audioSampleType'])) { ?> <br /> <span id="spanMetaTypeBold">xmpDM:audioSampleType:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpDM:audioSampleType'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpDM:composer'])) { ?> <br /> <span id="spanMetaTypeBold">xmpDM:composer:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpDM:composer'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpDM:duration'])) { ?> <br /> <span id="spanMetaTypeBold">xmpDM:duration:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpDM:duration'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpDM:genre'])) { ?> <br /> <span id="spanMetaTypeBold">xmpDM:genre:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpDM:genre'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpDM:logComment'])) { ?> <br /> <span id="spanMetaTypeBold">xmpDM:logComment:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpDM:logComment'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpDM:releaseDate'])) { ?> <br /> <span id="spanMetaTypeBold">xmpDM:releaseDate:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpDM:releaseDate'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpMM:DerivedFrom:DocumentID'])) { ?> <br /> <span id="spanMetaTypeBold">xmpMM:DerivedFrom:DocumentID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpMM:DerivedFrom:DocumentID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpMM:DerivedFrom:InstanceID'])) { ?> <br /> <span id="spanMetaTypeBold">xmpMM:DerivedFrom:InstanceID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpMM:DerivedFrom:InstanceID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpMM:DocumentID'])) { ?> <br /> <span id="spanMetaTypeBold">xmpMM:DocumentID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpMM:DocumentID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpMM:History:Action'])) { ?> <br /> <span id="spanMetaTypeBold">xmpMM:History:Action:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpMM:History:Action'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpMM:History:InstanceID'])) { ?> <br /> <span id="spanMetaTypeBold">xmpMM:History:InstanceID:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpMM:History:InstanceID'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpMM:History:SoftwareAgent'])) { ?> <br /> <span id="spanMetaTypeBold">xmpMM:History:SoftwareAgent:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpMM:History:SoftwareAgent'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpMM:History:When'])) { ?> <br /> <span id="spanMetaTypeBold">xmpMM:History:When:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpMM:History:When'];?></span><?php } // END check if empty ?>
<?php if (!empty($searchResult['meta']['raw']['xmpTPg:NPages'])) { ?> <br /> <span id="spanMetaTypeBold">xmpTPg:NPages:&nbsp; </span><span id="spanMetaContent"><?php echo $searchResult['meta']['raw']['xmpTPg:NPages'];?></span><?php } // END check if empty ?>


<!--------------------------------------------------------------------
-- Data dictionaries
--------------------------------------------------------------------->

<?php
if (!empty($searchResult['databasename'])) {
?>
  <h3><?php echo $searchResult['databasename']; ?></h3>
  <br />
<?php
} // END check if empty
?>

<?php
if (!empty($searchResult['columnname'])) {
?>
  <br /><b>Column Name: </b><?php echo $searchResult['columnname']; ?>
<?php
} // END check if empty
?>

</div><!-- ./div_view_detail -->

<br />
<br />
<span style=font-size:10pt><a href="javascript:history.back()">[Back to results]</a></span>
<br />
<br />

</div><!-- ./container container70 -->

<div class="push"></div><!-- ./push -->
</div><!-- ./wrapper -->
<div id="footercopyright"></div><!-- ./footercopyright -->




</body>
</html>
