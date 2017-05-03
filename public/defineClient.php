<?php 

// Connect to Elasticsearch (1-node cluster)
$esPort = getenv('APP_ES_PORT') ?: 9200;
$client = new Elasticsearch\Client([
		'hosts' => [ 'localhost:' . $esPort ]
]);
//$client = Elasticsearch\ClientBuilder::create()->build();

?>