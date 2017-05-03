<?php

error_reporting(0);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//get and set url protocol
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
//set and sanitize global variables for URL construction
$server = isset($_SERVER['SERVER_NAME']) ? htmlentities(strip_tags($_SERVER['SERVER_NAME'])) : null;
$path = isset($_SERVER['PHP_SELF']) ? htmlentities(strip_tags(dirname($_SERVER['PHP_SELF']))) : null;
$fileName = isset($_SERVER['SCRIPT_NAME']) ? htmlentities(strip_tags(basename($_SERVER['SCRIPT_NAME']))) : null;
$fileNameURI = isset($_SERVER['REQUEST_URI']) ? htmlentities(strip_tags($_SERVER['REQUEST_URI'])) : null;
$fileExtension = isset($_SERVER['PATH_INFO']) ? pathinfo($fileName, PATHINFO_EXTENSION) : null;

//assign value for title of page, limit to 60-70 characters
$pageTitle = 'Knowledge Graph API - Demo';
//assign value for description of page, limit to 155 charcters
$pageDescription = 'Search page for Google KnowledgeGraph API demo.';
//get file last modified date for use in Schema.org date properties
$pageLastModified = date ('c', getlastmod());
?>
<!doctype html>
<html lang="en-US" id="page" prefix="og: http://ogp.me/ns#" vocab="http://schema.org/" typeof="WebPage" resource="<?php echo $protocol.$server.$path.'/'.$fileName; ?>#page">
<head>
<meta charset="utf-8">
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $pageTitle; ?></title>
<meta name="description" content="<?php echo $pageDescription; ?>"/>
<meta property="og:title" content="<?php echo $pageTitle; ?>"/>
<meta property="og:description" content="<?php echo $pageDescription; ?>"/>
<meta property="og:image" content="https://www.lib.montana.edu/~jason/meta/img/clark-share-default.png"/>
<meta property="og:url" content="https://www.lib.montana.edu/~jason/"/>
<meta property="og:type" content="website"/>
<meta name="twitter:creator" property="og:site_name" content="@jaclark"/>
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:site" content="http://www.jasonclark.info"/>
<link rel="apple-touch-icon" type="image/png" href="../../meta/img/manifest.png"/>
<link rel="icon" type="image/x-icon" href="../../favicon.ico"/>
<link rel="canonical" href="<?php echo $protocol.$server.$path.'/'.$fileName; ?>"/>
<link rel="manifest" href="../../manifest.json"/>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,700"/>
<link rel="stylesheet" href="../../meta/styles/global.css"/>
<style>
form {text-align:center;}
input[type="submit"] {background-color:#207cca;border:1px solid #207cca;border-radius:4px;color:#fafafa;/*left:-8px;*/padding:0 15px;/*position:relative;*/}
input[type="submit"]:hover {background-color:#fafafa;color:#207cca;}
input[type="text"] {border-radius:4px;outline:none;}
.search {background:#eff1f3;border:1px solid #d8d8d8;padding:8px 15px;width:70%;}
label{position:absolute;overflow:hidden;clip: rect(0 0 0 0);height:1px;width:1px;margin:-1px;padding:0;border:0;}
.result {overflow-wrap:break-word;word-wrap:break-word;-ms-word-break:break-all;word-break:break-all;word-break:break-word;-ms-hyphens:auto;-moz-hyphens:auto;-webkit-hyphens:auto;hyphens:auto;}
.result li:not(:last-child) {border-bottom:1px solid #ccc;margin-bottom:1em;padding-bottom:1em;}
@media all and (max-width:30.063em) {
        input.search{width:85%;}
        .button{display:block;margin:auto;}
}
</style>
</head>
<body>
<div class="app" property="mainContentOfPage">
<header role="banner" aria-label="site header including title and navigation">
<div class="container">
<h1 class="site-title"><a href="../../index.html">Jason A. Clark</a></h1>
<nav class="nav" id="nav" role="navigation" typeof="SiteNavigationElement" resource="<?php echo $protocol.$server.$path.'/'.$fileName; ?>#nav">
        <a class="content-link" property="name url" href="../../about.html">about</a>
        <a class="content-link" property="name url" href="../../code.html">code</a>
        <a class="content-link" property="name url" href="../../talks.html">talks</a>
</nav>
</div>
</header>
<main id="work" role="main" property="mainEntity" typeof="CreativeWork" resource="<?php echo $protocol.$server.$path.'/'.$fileName; ?>#work">
<div class="content" property="text">
<section aria-label="main content">
        <h1 property="alternativeHeadline name"><?php echo $pageTitle; ?></h1>
        <p class="center" property="about description">Search across entities and schema.org types.</p>
<?php
// Set default value for query
$q = isset($_GET['q']) ? urlencode(strip_tags(trim($_GET['q']))) : null;

// Set default value for API format
$form = isset($_GET['form']) ? htmlentities(strip_tags($_GET['form'])) : 'json';

// Set default value for page length (number of records to display per page)
$limit = isset($_GET['limit']) ? strip_tags((int)$_GET['limit']) : '15';

// Set default value for page start index
$start = isset($_GET['start']) ? strip_tags((int)$_GET['start']) : '1';

// Set default value for type browse
$type = isset($_GET['type']) ? htmlentities(strip_tags($_GET['type'])) : null;

// Set default value for results sorting
$sort = isset($_GET['sort']) ? htmlentities(strip_tags($_GET['sort'])) : null;

// Set API version for Google Knowledge Graph API
$v = isset($_GET['v']) ? strip_tags((int)$_GET['v']) : 'v1';

// Set user API key for Google Knowledge Graph API
$key = isset($_GET['key']) ? $_GET['key'] : 'AIzaSyBpCW-EUz2EqI8YIjmQYYXwTzZu8kXGPEw';
?>
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <label for="q">Enter search query:</label>
                <input class="search" type="text" name="q" id="q" tabindex="1" placeholder="Search..." value="<?php if (!is_null($q)) { echo urldecode($q); } ?>" required />
                <input class="button" type="submit" value="Search" />
        </form>
<?php
if (!is_null($q)) {
// Process query

$service_url = 'https://kgsearch.googleapis.com/v1/entities:search';
$params = array(
  'query' => $q,
  'limit' => $limit,
  'indent' => TRUE,
  'types' => $type,
  'key' => $key);
$url = $service_url . '?' . http_build_query($params);
// View source to see raw API call - REMOVE from production code
echo '<!--' . $url . '-->';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = json_decode(curl_exec($ch), true);
//echo '<pre>'.var_dump($response).'</pre>';
curl_close($ch);
?>
<p class="result"><strong>Entity search for <?php echo '"'.urldecode($q).'"'.(is_null($type) ? "" : "with a type of <em>$type</em>"); ?></strong></p>
        <ol class="result">
<?php
if (empty($response['itemListElement'][0][result])) { 
?>
                <li style="list-style:none;">Is that made up? That sounds made up. We couldn't find any matches. Can you try a different query?</li>
<?php
} else {
        foreach ($response['itemListElement'] as $item) {
?>
                <li>
<?php
                $image = isset($item['result']['image']['contentUrl']) ? $item['result']['image']['contentUrl'] : null;
                if (is_null($image)) {
                echo '<svg width="150" height="150"><text x="2" y="15" fill="red">Image not available</text><rect x="0" y="0" width="150" height="150" style="fill:blue;stroke:grey;stroke-width:1;fill-opacity:0.1;stroke-opacity:0.9">Sorry, your browser does not support inline SVG.
                </svg>';
                } else {
                echo '<img src="'.$image.'" />';
                }
?>
                <br />
                <strong><?php echo $item['result']['name']; ?></strong>
                <br />
<?php
                $url = isset($item['result']['url']) ? $item['result']['url'] : null;
                if (is_null($url)) {
                echo 'No URL available.';
                } else {
                echo '<a href="'.$url.'">'.$url.'</a>';                         
                }
?>
                <br />
<?php
                $description = isset($item['result']['description']) ? $item['result']['description'] : 'No short description available.';
                echo $description;
?>
                <br />
<?php
                $wikipedia = isset($item['result']['detailedDescription']['url']) ? $item['result']['detailedDescription']['url'] : null;
                if (is_null($wikipedia)) {
                echo 'Wikipedia URL not available.';
                } else {
                echo '<a href="'.$wikipedia.'">'.$wikipedia.'</a>';                             
                }
?>
                <br />
<?php
                $body = isset($item['result']['detailedDescription']['articleBody']) ? $item['result']['detailedDescription']['articleBody'] : 'Wikipedia snippet not available.';
                echo $body;
?>
                <br />
                <?php echo 'knowledge graph id: '.$item['result']['@id']; ?>
                <br />
                <?php echo 'score: '.$item['resultScore']; ?>
                <br />
                type(s):
<?php
        foreach ($item['result']['@type'] as $type) {
?>
                <span><a href="index.html?q=<?php echo urlencode($_GET['q']).'&amp;type='.$type; ?>"><?php echo $type; ?></a></span>
<?php
        }
?>
                </li>
<?php
        }// End foreach loop
}// End if/else check for empty resultset
?>
        </ul>
<?php
} // End (!is_null($q))
?>
</section>
</div>
</main>
<footer role="contentinfo" aria-label="site footer including navigation and copyright">
        <hr class="border-ftr">
        <p class="nav center">
                <a property="relatedLink" href="../../search.html">Search</a>
                <a property="relatedLink" href="http://feeds.feedburner.com/diginit">Feed</a>
                <a property="relatedLink" href="../../sitemap.html">Sitemap</a>
        </p>
        <p class="center">
                &copy; <a property="author creator copyrightHolder reviewedBy" href="https://plus.google.com/103883366387427181221?rel=author">Jason A. Clark</a>  <time property="dateModified lastReviewed" datetime="2016-02-18T18:44:03-07:00" title="published 2016-02-18T18:44:03-07:00">2016</time>
        </p>
</footer>
</div>
<script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', 'YOUR-GOOGLE-ANALYTICS-ID-HERE', 'auto');
        ga('send', 'pageview');
</script>
</body>
</html>