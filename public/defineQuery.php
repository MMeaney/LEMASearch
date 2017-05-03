<?php 
//$param['body']['query']['match']['_all'] = $_REQUEST['q']; // what to search for
/*
$param['body'] = [
	'query' => [
		'match' => [
			'_all' => $_REQUEST['q']
				, 'fuzziness' => '1'
	    ]
	],
	'highlight' => [
		'fields' => [
	        '_all' => new \stdClass() 
		]
	]
];
*/

$param['body'] = [
	'query' => [
		'query_string' => [
			'query' => $_REQUEST['q'],
			'default_operator' => 'AND',
			'fields' => [ 
				'file.filename^99'
				, 'file.url^50'
                , "content"
				, 'meta.author'
				, '_all'
				//'file.filename.raw^99'
				//, 'file.url.raw^50'
				//, 'meta.author.raw'
			//, 'fuzziness' => '1'
			//, 'prefix_length' =>  2
		
			]
		]
	],
	'highlight' => [
		'fields' => [
	        '*' => new \stdClass() 
		]
	]
	/*
	'query' => [
		'bool' => [
			'should' => [
				'match' => [
					'_all' => $_REQUEST['q']
				],
				'match' => [
					'_all' => [
						'query' => $_REQUEST['q'],
						'fuzziness' => '1',
						'prefix_length' =>  2
					]
				]
			]
		]
	]
	//*/
];

//$param['body']['highlight']['fields']['file.url'] = (object) [];
//$param['body']['highlight']['fields']['file.filename'] = (object) [];

//$param = $_REQUEST['q']; // what to search for
	
// Send search query to Elasticsearch and get results
$result = $client->search($param);
//$result = $client->search($param)['size'] = 10;
$results = $result['hits']['hits'];
$resultscount = $result['hits']['total'];
//$results = $client->search($param);
//$numresults = (count($results));



/////////////////////////////////////////////////
// Define MAIN TEST query only

/*


$paramMainTest['body'] = [

	'query' => [
		'query_string' => [
			'query' => $_REQUEST['q'],
				'default_operator' => 'AND',
				//'fuzziness' => '1',
				'fields' => [ 'file.filename.raw^99'
					, 'file.url.raw^50'
					, 'meta.author.raw'
					, '_all'
		
			]
		]
	],

	/*
	'query' => [
		'match' => [
			'_all' => $_REQUEST['q']
			//'content' => $_REQUEST['q']
		]
	],
	//*/
	
/*
	'highlight' => [
		'fields' => [
			'*' => new \stdClass()
		]
	]
];

$resultMainTest = $client->search($paramMainTest);
$resultsMainTest = $resultMainTest['hits']['hits'];
$resultsCountMainTest = $resultMainTest['hits']['total'];


//$paramMainTest['body']['highlight']['fields']['content'] = new \stdClass();
//$paramMainTest['body']['highlight']['fields']['meta.author'] = new \stdClass();




//$aggAuthor 		= $resultfileshare['aggregations']['aggAuthor']['buckets'];

//$aggFileType 	= $resultfileshare['aggregations']['aggFileType']['buckets'];

*/

/////////////////////////////////////////////////	
// Define NETWORK FILES query only
/*
$paramfileshare['body'] = [
	'query' => [
		'match' => [
			'_all' => $_REQUEST['q']
		]
	],

	'highlight' => [
		'fields' => [
			'_all' => new \stdClass() 
		]
	]
];

*/

/*
$paramfileshare['body'] = [
	'query' => [
		'multi_match' => [
			'query' => $_REQUEST['q'],
			'from' =>  0,
			'type' => 'cross_fields', // Possible values 'best_fields', 'most_fields', 'phrase', 'prase_prefix', 'cross_fields'
			'fields' => ['file.filename^9', 'meta.author^8', 'file.url^7', 'content^6', 'file.content_type^5', 'meta.raw.Application-Name^4'],
			'operator' => 'or'
				//, 'fuzziness' => '1'
				//, 'prefix_length' => 2
		]
	]
];
*/

// First, setup full text search bits

/*


	'from' => 10,
	'size' => 10,

 */

$fullTextClauses = [];
//$fullTextClauses = [];
$fullTextClauses[] = 
[
	'query' => [
		'query_string' => [
			'query' => $_REQUEST['q'],
			'default_operator' => 'AND',
			'fields' => [ 
				'file.filename^99'
				, 'file.url^50'
                , "content"
				, 'meta.author'
				, '_all'
				//'file.filename.raw^99'
				//, 'file.url.raw^50'
				//, 'meta.author.raw'
			//, 'fuzziness' => '1'
			//, 'prefix_length' =>  2
		
			]
		]
	]
	//,
	//'highlight' => [
	//	'fields' => [
	//        '*' => new \stdClass() 
	//	]
	//]
];



/*
 [
 'bool' => [
 'should' => [
 'match' => [
 '_all' => [
 'query' => $_REQUEST['q']
 //, 'fuzziness' => '1'
 //, 'prefix_length' =>  1
 ]
 ]
 ]
 ]

 ];
 //*/



/*
	['multi_match' => [
		'query' => $_REQUEST['q'],
		'type' => 'cross_fields', // Possible values 'best_fields', 'most_fields', 'phrase', 'prase_prefix', 'cross_fields'
		'fields' => [ 'file.filename^2'
					, 'meta.author'
					, 'file.url'
					, 'content'
					, 'path.real'
					, 'file.content_type'
					, 'meta.raw.Application-Name'
				
		],
		//'fuzziness' => '1',
		//'prefix_length' => 2,
		'operator' => 'or'
				//, 'fuzziness' => '1'
				//, 'prefix_length' => 2
		]
	];

*/

/*
    if ($_REQUEST['title']) {
      $fullTextClauses[] = [ 'match' => [ 'title' => $_REQUEST['title'] ] ];
    }

    if ($_REQUEST['description']) {
      $fullTextClauses[] = [ 'match' => [ 'description' => $_REQUEST['description'] ] ];
    }

    if ($_REQUEST['ingredients']) {
      $fullTextClauses[] = [ 'match' => [ 'ingredients' => $_REQUEST['ingredients'] ] ];
    }

    if ($_REQUEST['directions']) {
      $fullTextClauses[] = [ 'match' => [ 'directions' => $_REQUEST['directions'] ] ];
    }

    if ($_REQUEST['tags']) {
      $tags = Util::recipeTagsToArray($_REQUEST['tags']);
      $fullTextClauses[] = [ 'terms' => [
        'tags' => $tags,
        'minimum_should_match' => count($tags)
      ] ];
    }
*/

    if (count($fullTextClauses) > 0) {
      $query = [ 'bool' => [ 'must' => $fullTextClauses ] ];
      //$query = [ 'query' => $fullTextClauses ];
    } else {
      $query = [ 'match_all' => (object) [] ];
    }

    // Then setup exact match bits
    $filterClauses = [];
/*
    if ($_REQUEST['prep_time_min_low'] || $_REQUEST['prep_time_min_high']) {
      $rangeFilter = [];
      if ($_REQUEST['prep_time_min_low']) {
        $rangeFilter['gte'] = (int) $_REQUEST['prep_time_min_low'];
      }
      if ($_REQUEST['prep_time_min_high']) {
        $rangeFilter['lte'] = (int) $_REQUEST['prep_time_min_high'];
      }
      $filterClauses[] = [ 'range' => [ 'prep_time_min' => $rangeFilter ] ];
    }
*/
    if ($_REQUEST['FileSizeLow'] || $_REQUEST['FileSizeHigh']) {
      $rangeFilter = [];
      if ($_REQUEST['FileSizeLow']) {
        $rangeFilter['gte'] = (int) $_REQUEST['FileSizeLow'];
      }
      if ($_REQUEST['FileSizeHigh']) {
        $rangeFilter['lte'] = (int) $_REQUEST['FileSizeHigh'];
      }
      $filterClauses[] = [ 'range' => [ 'file.filesize' => $rangeFilter ] ];
    }
    
    if ($_REQUEST['FileCreateFrom'] || $_REQUEST['FileCreateTo']) {
      $rangeFilter = [];
      if ($_REQUEST['FileCreateFrom']) {
        $rangeFilter['gte'] = $_REQUEST['FileCreateFrom'];
      }
      if ($_REQUEST['FileCreateTo']) {
        $rangeFilter['lte'] = $_REQUEST['FileCreateTo'];
      }
      $filterClauses[] = [ 'range' => [ 'meta.raw.meta:creation-date' => $rangeFilter ] ];
    }

    /*
    //if ($_REQUEST['Relevant']) {
    //$filterClauses[] = [ 'term' => [ 'meta.author.raw' => $_REQUEST['Author'] ] ];
    //}
    if(!empty($_REQUEST['inputRelevantDocument'])) {
    	foreach($_REQUEST['inputRelevantDocument'] as $mltRelevantDocument) {
    		$filterClauses[] = [ 
    			'more_like_this' => [ 
    				'fields' => [
    					'content',
    					'meta.author',
    					'file.content_type'
    				]    				
    			],
    			'like' => [
    				'_id' => $mltRelevantDocument
    			]
    		];
    		//echo $check; //echoes the value set in the HTML form for each checked checkbox.
    		//so, if I were to check 1, 3, and 5 it would echo value 1, value 3, value 5.
    		//in your case, it would echo whatever $row['Report ID'] is equivalent to.
    	}
    }
//*/    
    if ($_REQUEST['From']) {
    $fromPagination = [ 'From' => $_REQUEST['From'] ];
    }
    
    if ($_REQUEST['Author']) {
      $filterClauses[] = [ 'term' => [ 'meta.author.raw' => $_REQUEST['Author'] ] ];
    }

    if ($_REQUEST['Application']) {
      $filterClauses[] = [ 'term' => [ 'meta.raw.Application-Name.raw' => $_REQUEST['Application'] ] ];
    }

    if ($_REQUEST['FileType']) {
      //$filterClauses[] = [ 'term' => [ 'file.filename' => $_REQUEST['FileType'] ] ];
      $filterClauses[] = [ 'term' => [ 'file.content_type.raw' => $_REQUEST['FileType'] ] ];
    }

    if (count($filterClauses) > 0) {
      $filter = [ 'bool' => [ 'must' => $filterClauses ] ];
    }

    // Build complete search request body
    if (count($filterClauses) > 0) {
      $paramfileshare['body'] = 
      [ 'query' =>
        [ 'filtered' =>
        //[ 'bool' => [ 'must' =>
          [ 'query' => $query, 'filter' => $filter ]
        ]        		
        //]
      ];
    } else {
      $paramfileshare['body'] = [ 'query' => $query ];
    }




/*
$json_query_fileshare = '
{
	"query": {
		"multi_match": {
			"query": "'.$_REQUEST['q'].'",
			"fields": ["file.filename", "file.url"],
			"type": "best_fields"
		}
	},

	"aggs": {
		"agg_author": {
			"terms": {
				"field": "meta.author",
				"size": 0
			}
		}
	},
	"highlight": {
		"fields": {
			"_all": {}
		}
	}
}';
//*/

	
////get the search query
////$query = $_REQUEST['q'];
	
////get filter of agg/facet
$aggFilterValue = $_GET['agg'];
$aggFilterField = $_GET['agg_field'];

	
////$paramfileshare['body']['query']['filtered']['query']['match']['title'] = $query;
	
if ($aggFilterValue) {
	$paramfileshare['body']['query']['filtered']['filter']['term'][$aggFilterField] = $aggFilterValue;
}



$paramfileshare['body']['aggregations']['aggAuthor']['terms']['field'] = "meta.author.raw";
$paramfileshare['body']['aggregations']['aggAuthor']['terms']['size'] = 0;
$paramfileshare['body']['aggregations']['aggAuthor']['terms']['missing'] = "(Blank)";
//$paramfileshare['body']['aggregations']['aggAuthor']['terms']['order']['_term'] = "asc";
//$paramfileshare['body']['aggregations']['aggAuthor']['terms']['min_doc_count'] = 0;


$paramfileshare['body']['aggregations']['aggApplication']['terms']['field'] = "meta.raw.Application-Name.raw";
$paramfileshare['body']['aggregations']['aggApplication']['terms']['size'] = 0;
$paramfileshare['body']['aggregations']['aggApplication']['terms']['missing'] = "(Blank)";
//$paramfileshare['body']['aggregations']['aggApplication']['terms']['order']['_term'] = "asc";


/*
//$paramfileshare['body']['aggregations']['aggFileType']['terms']['field'] = "file.filename";
$paramfileshare['body']['aggregations']['aggFileType']['terms']['script'] = "doc['file.filename'].getValue().substring(0,3)";
$paramfileshare['body']['aggregations']['aggFileType']['terms']['size'] = 0;
//$paramfileshare['body']['aggregations']['aggFileType']['terms']['order']['_count'] = "asc";
//$paramfileshare['body']['aggregations']['aggFileType']['terms']['script'] = "doc['file.filename'].getValue().substring(0, lastIndexOf('.'))";
*/

//"script": "doc['my_field'].getValue().substring(0,6)",
//$filetype = substr($filetypestring, strrpos($filetypestring, ".") + 1);
//names.substring(0, lastIndexOf('.'))


$paramfileshare['body']['aggregations']['aggContentType']['terms']['field'] = "file.content_type.raw";
$paramfileshare['body']['aggregations']['aggContentType']['terms']['size'] = 0;
$paramfileshare['body']['aggregations']['aggContentType']['terms']['missing'] = "";
//$paramfileshare['body']['aggregations']['aggContentType']['terms']['order']['_term'] = "asc";



$resultfileshare = $client->search($paramfileshare);
$resultsfileshare = $resultfileshare['hits']['hits'];
$resultscountfileshare = $resultfileshare['hits']['total'];
$aggsfileshareauthor = $resultfileshare['aggs']['aggAuthor'];
$aggsFileShareFileType = $resultfileshare['aggs']['aggFileType'];
$aggsFileShareContentType = $resultfileshare['aggs']['aggContentType'];

////get filter of agg/facet
$aggFilterValue = $_GET['agg'];
$aggFilterField = $_GET['agg_field'];

$aggAuthor 		= $resultfileshare['aggregations']['aggAuthor']['buckets'];
$aggFileType 	= $resultfileshare['aggregations']['aggFileType']['buckets'];
$aggContentType	= $resultfileshare['aggregations']['aggContentType']['buckets'];
$aggApplication = $resultfileshare['aggregations']['aggApplication']['buckets'];


$aggAuthorCount  	 = count($aggAuthor);
$aggFileTypeCount 	 = count($aggFileType);
$aggContentTypeCount = count($aggContentType);
$aggApplicationCount = count($aggApplication);
  
//$numresultsfileshare = (count($resultsfileshare));
	
/////////////////////////////////////////////////	
// Define OPEN DATA query only

$paramopendata['body'] = [
	'query' => [
		'match' => [
			'_all' => $_REQUEST['q']
	    ]
	],
	'highlight' => [
	    'fields' => [
	        '_all' => new \stdClass() 
		]
	]
];
$resultopendata = $client->search($paramopendata);
$resultsopendata = $resultopendata['hits']['hits'];
$resultscountopendata = $resultopendata['hits']['total'];
//$numresultsopendata = (count($resultsopendata));
	
/////////////////////////////////////////////////	
// Define DATA DICTIONARY query only

$paramdatadictionary['body'] = [
	'query' => [
		'match' => [
			'_all' => $_REQUEST['q']
	    ]
	],
	'highlight' => [
	    'fields' => [
	        '_all' => new \stdClass() 
		]
	]
];
$resultdatadictionary = $client->search($paramdatadictionary);
$resultsdatadictionary = $resultdatadictionary['hits']['hits'];
$resultscountdatadictionary = $resultdatadictionary['hits']['total'];
//$numresultsdatadictionary = (count($resultsdatadictionary));
	
/////////////////////////////////////////////////	
// Define PEOPLE query only

/*
$paramPeople['body'] = [
	'query' => [
		'match' => [
			'_all' => $_REQUEST['q']
	    ]
	],
	'highlight' => [
	    'fields' => [
	        '_all' => new \stdClass() 
		]
	]
];

//*/


///*
$paramPeople['body'] = [
	'query' => [
		'query_string' => [
			'query' => $_REQUEST['q'],
			'default_operator' => 'AND',
			'fields' => [
				'_all'
				//, 'fuzziness' => '1'
				//, 'prefix_length' =>  2
			]
		]
	]
];

//*/

$resultPeople = $client->search($paramPeople);
$resultsPeople = $resultPeople['hits']['hits'];
$resultsCountPeople = $resultPeople['hits']['total'];



/////////////////////////////////////////////////
// Define SNIPPETS query only

$paramSnippetMain['body'] = [
		'query' => [
				'match' => [
						'_all' => $_REQUEST['q']
						//, 'fuzziness' => '1'
						//, 'prefix_length' =>  1
				]
		],
		'highlight' => [
				'fields' => [
						'_all' => new \stdClass()
				]
		]
];

$resultSnippetMain = $client->search($paramSnippetMain);
$resultsSnippetMain = $resultSnippetMain['hits']['hits'];
$resultsCountSnippetMain = $resultSnippetMain['hits']['total'];




/////////////////////////////////////////////////
// Define Research query only

$paramResearch['body'] = [
		'query' => [
				'match' => [
						'_all' => $_REQUEST['q']
				]
		],
		'highlight' => [
				'fields' => [
						'_all' => new \stdClass()
				]
		]
];

$resultResearch = $client->search($paramResearch);
$resultsResearch = $resultResearch['hits']['hits'];
$resultsCountResearch = $resultResearch['hits']['total'];




/////////////////////////////////////////////////
// Define LEAP SITE query only

/*$paramLEAP['body'] = [
 'query' => [
 'match' => [
 '_all' => $_REQUEST['q']
 ]
 ],
 'highlight' => [
 'fields' => [
 '_all' => new \stdClass()
 ]
 ]
 ];*/


$paramLEAPSite = [
		'index' => 'leap2',
		'type' => 'leap_site',
		'body' => [
				'query' => [
						'match' => [
								'_all' => $_REQUEST['q']
						]
				]
		]
		//, 'sort' => [['epa_regno' => ['order' => 'asc']]],
];



$resultLEAPSite = $client->search($paramLEAPSite);
$resultsLEAPSite = $resultLEAPSite['hits']['hits'];
$resultsCountLEAPSite = $resultLEAPSite['hits']['total'];


$aggsLEAPSite = $resultLEAPSite['aggs']['aggLEAPSite'];
//$aggsfileshareauthor = $resultfileshare['aggs']['aggAuthor'];
//$aggsFileShareFileType = $resultfileshare['aggs']['aggFileType'];
//$aggsFileShareContentType = $resultfileshare['aggs']['aggContentType'];

////get filter of agg/facet
$aggFilterValueLEAPSite = $_GET['agg'];
$aggFilterFieldLEAPSite = $_GET['agg_field'];

$aggLEAPSiteBuckets 	= $resultLEAPSite['aggregations']['aggLEAPSite']['buckets'];
//$aggFileType 	= $resultfileshare['aggregations']['aggFileType']['buckets'];
//$aggContentType	= $resultfileshare['aggregations']['aggContentType']['buckets'];
//$aggApplication = $resultfileshare['aggregations']['aggApplication']['buckets'];


$aggLEAPSiteCount  	 = count($aggLEAPSite);
//$aggFileTypeCount 	 = count($aggFileType);
//$aggContentTypeCount = count($aggContentType);
//$aggApplicationCount = count($aggApplication);



/////////////////////////////////////////////////
// Define LEAP LICENCE query only

$paramLEAPLicence = [
		'index' => 'leap2',
		'type' => 'leap_licence',
		'body' => [
				'query' => [
						'match' => [
								'_all' => $_REQUEST['q']
						]
				]
		]
];

$paramLEAPLicence['size'] = 100;

$resultLEAPLicence = $client->search($paramLEAPLicence);
$resultsLEAPLicence = $resultLEAPLicence['hits']['hits'];
$resultsCountLEAPLicence = $resultLEAPLicence['hits']['total'];


$aggsLEAPLicence = $resultLEAPLicence['aggs']['aggLEAPLicence'];

////get filter of agg/facet
$aggFilterValueLEAPLicence = $_GET['agg'];
$aggFilterFieldLEAPLicence = $_GET['agg_field'];

$aggLEAPLicenceBuckets	= $resultLEAPLicence['aggregations']['aggLEAPLicence']['buckets'];

$aggLEAPLicenceCount	= count($aggLEAPLicence);

/////////////////////////////////////////////////
// Define LEAP COMPLAINT query only

$paramLEAPComplaint = [
		'index' => 'leap2',
		'type' => 'leap_complaint',
    	'routing' => '_id',
		'body' => [
				'query' => [
						'match' => [
								'_all' => $_REQUEST['q']
						]
				]
		]
		//, 'sort' => [['epa_dateofoccurance' => ['order' => 'desc']]]
];

$paramLEAPComplaint['size'] = 100;

$resultLEAPComplaint = $client->search($paramLEAPComplaint);
$resultsLEAPComplaint = $resultLEAPComplaint['hits']['hits'];
$resultsCountLEAPComplaint = $resultLEAPComplaint['hits']['total'];


$aggsLEAPComplaint = $resultLEAPComplaint['aggs']['aggLEAPComplaint'];

////get filter of agg/facet
$aggFilterValueLEAPComplaint = $_GET['agg'];
$aggFilterFieldLEAPComplaint = $_GET['agg_field'];

$aggLEAPComplaintBuckets	= $resultLEAPComplaint['aggregations']['aggLEAPComplaint']['buckets'];

$aggLEAPComplaintCount	= count($aggLEAPComplaint);




/////////////////////////////////////////////////
// Define LEAP NON-COMPLIANCE query only

$paramLEAPNonCompliance = [
		'index' => 'leap2',
		'type' => 'leap_non_compliance',
		'body' => [
				'query' => [
						'match' => [
								'_all' => $_REQUEST['q']
						]
				]
		]
		//, 'sort' => [['epa_dateofoccurance' => ['order' => 'desc']]]
];

$paramLEAPNonCompliance['size'] = 100;

$resultLEAPNonCompliance = $client->search($paramLEAPNonCompliance);
$resultsLEAPNonCompliance = $resultLEAPNonCompliance['hits']['hits'];
$resultsCountLEAPNonCompliance = $resultLEAPNonCompliance['hits']['total'];


$aggsLEAPNonCompliance = $resultLEAPNonCompliance['aggs']['aggLEAPNonCompliance'];

////get filter of agg/facet
$aggFilterValueLEAPNonCompliance = $_GET['agg'];
$aggFilterFieldLEAPNonCompliance = $_GET['agg_field'];

$aggLEAPNonComplianceBuckets	= $resultLEAPNonCompliance['aggregations']['aggLEAPNonCompliance']['buckets'];

$aggLEAPNonComplianceCount	= count($aggLEAPNonCompliance);



/////////////////////////////////////////////////
// Define LEAP COMPLIANCE INVESTIGATION query only

$paramLEAPComplianceInvestigation = [
		'index' => 'leap2',
		'type' => 'leap_compliance_investigation',
		'body' => [
				'query' => [
						'match' => [
								'_all' => $_REQUEST['q']
						]
				]
		]
		//, 'sort' => [['epa_dateofoccurance' => ['order' => 'desc']]]
];

$paramLEAPComplianceInvestigation['size'] = 100;

$resultLEAPComplianceInvestigation = $client->search($paramLEAPComplianceInvestigation);
$resultsLEAPComplianceInvestigation = $resultLEAPComplianceInvestigation['hits']['hits'];
$resultsCountLEAPComplianceInvestigation = $resultLEAPComplianceInvestigation['hits']['total'];


$aggsLEAPComplianceInvestigation = $resultLEAPComplianceInvestigation['aggs']['aggLEAPComplianceInvestigation'];

////get filter of agg/facet
$aggFilterValueLEAPComplianceInvestigation = $_GET['agg'];
$aggFilterFieldLEAPComplianceInvestigation = $_GET['agg_field'];

$aggLEAPComplianceInvestigationBuckets	= $resultLEAPComplianceInvestigation['aggregations']['aggLEAPComplianceInvestigation']['buckets'];

$aggLEAPComplianceInvestigationCount	= count($aggLEAPComplianceInvestigation);





/////////////////////////////////////////////////
// Define LEAP SHAREPOINT DOCUMENTS query only

/*
$paramLEAPSharepointDocuments = [
		'index' => 'leap2',
		'type' => 'leap_docs',
		'body' => [
				'query' => [
						'match' => [
								'_all' => $_REQUEST['q']
						]
				]
		]
		//, 'sort' => [['epa_dateofoccurance' => ['order' => 'desc']]]
];
//*/

$paramLEAPSharepointDocuments = [
	'index' => 'leap2',
	'type' => 'leap_docs',
	'body' => [
		'query' => [
			'query_string' => [
				'query' => $_REQUEST['q'],
				'default_operator' => 'OR'		
				
			]
		]
	]
		//, 'sort' => [['epa_dateofoccurance' => ['order' => 'desc']]]
];






$paramLEAPSharepointDocuments['size'] = 10000;

$resultLEAPSharepointDocuments = $client->search($paramLEAPSharepointDocuments);
$resultsLEAPSharepointDocuments = $resultLEAPSharepointDocuments['hits']['hits'];
$resultsCountLEAPSharepointDocuments = $resultLEAPSharepointDocuments['hits']['total'];


$aggsLEAPSharepointDocuments = $resultLEAPSharepointDocuments['aggs']['aggLEAPSharepointDocuments'];

////get filter of agg/facet
$aggFilterValueLEAPSharepointDocuments = $_GET['agg'];
$aggFilterFieldLEAPSharepointDocuments = $_GET['agg_field'];

$aggLEAPSharepointDocumentsBuckets	= $resultLEAPSharepointDocuments['aggregations']['aggLEAPSharepointDocuments']['buckets'];

$aggLEAPSharepointDocumentsCount	= count($aggLEAPSharepointDocuments);





/////////////////////////////////////////////////
// Define AER/PRTR MEASURMENTS query only

$paramPRTRMeasurements = [
		'index' => 'aer',
		'type' => 'measurements',
		'body' => [
				'query' => [
						'match' => [
								'_all' => $_REQUEST['q']
						]
				]
		]
		//, 'sort' => [['epa_dateofoccurance' => ['order' => 'desc']]]
];

$paramPRTRMeasurements['size'] = 10000;

$resultPRTRMeasurements = $client->search($paramPRTRMeasurements);
$resultsPRTRMeasurements = $resultPRTRMeasurements['hits']['hits'];
$resultsCountPRTRMeasurements = $resultPRTRMeasurements['hits']['total'];


$aggsPRTRMeasurements = $resultPRTRMeasurements['aggs']['aggPRTRMeasurements'];

////get filter of agg/facet
$aggFilterValuePRTRMeasurements = $_GET['agg'];
$aggFilterFieldPRTRMeasurements = $_GET['agg_field'];

$aggPRTRMeasurementsBuckets	= $resultPRTRMeasurements['aggregations']['aggPRTRMeasurements']['buckets'];

$aggPRTRMeasurementsCount	= count($aggPRTRMeasurements);





/////////////////////////////////////////////////
// Define CRM DATA DICTIONARY query only









$paramCRMDataDictionary = [
		'index' => 'crm',
		'type' => 'datadictionary',
		'body' => [
				'query' => [
				'query_string' => [
						'query' => $_REQUEST['q'],
						'default_operator' => 'AND',
						'fields' => [ '_all' ]
						]
					]
		]
		//, 'sort' => [['epa_dateofoccurance' => ['order' => 'desc']]]
];

$paramCRMDataDictionary['size'] = 10000;

$resultCRMDataDictionary = $client->search($paramCRMDataDictionary);
$resultsCRMDataDictionary = $resultCRMDataDictionary['hits']['hits'];
$resultsCountCRMDataDictionary = $resultCRMDataDictionary['hits']['total'];


$aggsCRMDataDictionary = $resultCRMDataDictionary['aggs']['aggCRMDataDictionary'];

////get filter of agg/facet
$aggFilterValueCRMDataDictionary = $_GET['agg'];
$aggFilterFieldCRMDataDictionary = $_GET['agg_field'];

$aggCRMDataDictionaryBuckets	= $resultCRMDataDictionary['aggregations']['aggCRMDataDictionary']['buckets'];

$aggCRMDataDictionaryCount	= count($aggCRMDataDictionary);






/////////////////////////////////////////////////
// Define WEB SEARCH query only

$paramEPAWeb = [
		'index' => 'epaweb',
		'type' => 'pages',
		'body' => [
				'query' => [
						'query_string' => [
								'query' => $_REQUEST['q'],
								'default_operator' => 'AND',
								'fields' => [
										'_all'
		
								]
						]
				]
		]

		
		
		//, 'sort' => [['epa_dateofoccurance' => ['order' => 'desc']]]
];

$paramEPAWeb['size'] = 10000;

$resultEPAWeb = $client->search($paramEPAWeb);
$resultsEPAWeb = $resultEPAWeb['hits']['hits'];
$resultsCountEPAWeb = $resultEPAWeb['hits']['total'];


$aggsEPAWeb = $resultEPAWeb['aggs']['aggEPAWeb'];

////get filter of agg/facet
$aggFilterValueEPAWeb = $_GET['agg'];
$aggFilterFieldEPAWeb = $_GET['agg_field'];

$aggEPAWebBuckets	= $resultEPAWeb['aggregations']['aggEPAWeb']['buckets'];

$aggEPAWebCount	= count($aggEPAWeb);






////////////////////////////////////////////////////////////////////////////////
// Define WEB SEARCH query only - Multiple Sites - Scrapy Pipeline (2017-03-06)


$paramWeb['body'] = [
	'query' => [
		'query_string' => [
			'query' => $_REQUEST['q'],
			'default_operator' => 'AND',
			'fields' => [
				'path^9',
				'_all'
								//, 'fuzziness' => '1'
								//, 'prefix_length' =>  2

			]
		]
	],
	'highlight' => [
		'fields' => [
			'*' => new \stdClass()
		]
	]
];

$paramWeb['size'] = 10000;

$resultWeb = $client->search($paramWeb);
$resultsWeb = $resultWeb['hits']['hits'];
$resultsCountWeb = $resultWeb['hits']['total'];


$aggsWeb = $resultWeb['aggs']['aggWeb'];

////get filter of agg/facet
$aggFilterValueWeb = $_GET['agg'];
$aggFilterFieldWeb = $_GET['agg_field'];

$aggWebBuckets	= $resultWeb['aggregations']['aggWeb']['buckets'];

$aggWebCount	= count($aggWeb);




// Commented


//$u_tmp="xlsx";

// Query Definition
/*
$json_query_fileshare = '
{
	"query": {
		"multi_match": {
			"query": "'.$_REQUEST['q'].'",
			"fields": ["file.filename", "file.url"],
			"type": "best_fields"
		}
	},

	"aggs": {
		"agg_author": {
			"terms": {
				"field": "meta.author",
				"size": 0
			}
		}
	},
	"highlight": {
		"fields": {
			"_all": {}
		}
	}
}';

/* 
 	"query": {
		"filtered": {
			"filter": {
				"term": {
					"meta.author": "Maurice Meaney"
				}
			}
		}
	},
*/


/*
 	'query' => [
		'filtered' => [
			'filter' => [
				'term' => ['meta.author' => 'Maurice Meaney' ]
			]
		]
	],

*//*
	'aggregations' => [
		'agg_author' => [
			'terms' => [
				'field' => 'meta.author',
				'size' 	=> 0
			]
		]
	],

// Filter query results by checkbox, etc. (code for testing)
	'filter' => [
		'wildcard' => [
			'file.filename' => "*$u_tmp"
		]
	],
 *
 */


/*
 // First, setup full text search bits
 $fullTextClauses = [];

 if ($_REQUEST['q']) {
 $fullTextClauses[] = [ 'match' => [ 'q' => $_REQUEST['q'] ] ];
 }

 if ($_REQUEST['databasename']) {
 $fullTextClauses[] = [ 'match' => [ 'databasename' => $_REQUEST['databasename'] ] ];
 }

 if ($_REQUEST['title']) {
 $fullTextClauses[] = [ 'match' => [ 'title' => $_REQUEST['title'] ] ];
 }

 if ($_REQUEST['columnname']) {
 $fullTextClauses[] = [ 'match' => [ 'columnname' => $_REQUEST['columnname'] ] ];
 }

 if ($_REQUEST['description']) {
 $fullTextClauses[] = [ 'match' => [ 'description' => $_REQUEST['description'] ] ];
 }

 if ($_REQUEST['datatype']) {
 $fullTextClauses[] = [ 'match' => [ 'datatype' => $_REQUEST['datatype'] ] ];
 }

 if ($_REQUEST['system']) {
 $fullTextClauses[] = [ 'match' => [ 'system' => $_REQUEST['system'] ] ];
 }

 if ($_REQUEST['fkdependencies']) {
 $fullTextClauses[] = [ 'match' => [ 'fkdependencies' => $_REQUEST['fkdependencies'] ] ];
 }

 if ($_REQUEST['servername']) {
 $fullTextClauses[] = [ 'match' => [ 'servername' => $_REQUEST['servername'] ] ];
 }

 if ($_REQUEST['tags']) {
 $tags = Util::recipeTagsToArray($_REQUEST['tags']);
 $fullTextClauses[] = [ 'terms' => [
 'tags' => $tags,
 'minimum_should_match' => count($tags)
 ] ];
 }

 if (count($fullTextClauses) > 0) {
 $query = [ 'bool' => [ 'must' => $fullTextClauses ] ];
 //$query = [ 'match' => [ '_all' => $fullTextClauses ] ];
 //$query = [ 'match_all' => $fullTextClauses ];
 } else {
 $query = [ 'match_all' => (object) [] ];
 //$query = [ 'match' => (object) [] ];
 }

 // Then setup exact match bits
 $filterClauses = [];

 if ($_REQUEST['field_size_low'] || $_REQUEST['field_size_high']) {
 $rangeFilter = [];
 if ($_REQUEST['field_size_low']) {
 $rangeFilter['gte'] = (int) $_REQUEST['field_size_low'];
 }
 if ($_REQUEST['field_size_high']) {
 $rangeFilter['lte'] = (int) $_REQUEST['field_size_high'];
 }
 $filterClauses[] = [ 'range' => [ 'size' => $rangeFilter ] ];
 }

 if ($_REQUEST['servings']) {
 $filterClauses[] = [ 'term' => [ 'servings' => $_REQUEST['servings'] ] ];
 }

 if (count($filterClauses) > 0) {
 $filter = [ 'bool' => [ 'must' => $filterClauses ] ];
 }

 // Build complete search request body
 if (count($filterClauses) > 0) {
 $params['body'] = [ 'query' =>
 [ 'filtered' =>
 [ 'query' => $query, 'filter' => $filter ]
 ]
 ];
 } else {
 //$paramfileshare['body'] = [ 'query' => $query ];
 $paramfileshare['body'] = [ 'query' => $query ];
 }

 // Send search query to Elasticsearch and get results
 //$result = $client->search($paramfileshare);
 //$results = $result['hits']['hits'];



 */






?>