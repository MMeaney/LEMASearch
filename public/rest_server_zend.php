<?php
/*** 
A VERY Simple REST SERVER CLASS
for testing my tutorial on Zend_Rest_Client tutorials.
You should not use this as a production REST SERVER.

FOR PRODUCTION usage: 
You should implement A ZEND MVC controller Application
Using 1. zend rest context-switch
2. rest route.
3. Controller Front
I will post an advanced Zend Rest Server tutorial.
Please check back on lampdev.org
****/

// need to load the Zend Framework lib
// Assuming Zend is in the included path
// using Zend Framework 1.11
require_once('Zend/Loader/Autoloader.php');
$loader = Zend_Loader_Autoloader::getInstance();

class Simple_Rest_Server {
	protected $_request;
	public function __construct() {
		// i am using ZEND 1.11 
		// if ZEND 2, you should use Zend\Http\Request
		// use this Zend Request Class as it does 
		// most of the hard work for a web Request 
		$this->_request = new Zend_Controller_Request_Http();
	}
	public function get()
	{
		// we will dump the query_string
		// with a message from GET METHOD
		// if we get a get call
		// $this->_request->getQuery()
		// will return the $_SERVER['QUERY_STRING'] value
		$data = $this->_request->getQuery();
		return "FROM GET METHOD.\n" . 
		var_export($data, true);
	}
        public function HelloWorld()
	{
		// we will dump the query_string
		// with a message from HelloWorld METHOD
		// if we get a get call
		// $this->_request->getQuery()
		// will return the $_SERVER['QUERY_STRING'] value
		$data = $this->_request->getQuery();
		return "FROM HelloWorld.\n" . 
		var_export($data, true);
	}
	public function post()
	{
		// we will dump the POST DATA
		// with a message from POST METHOD
		// if we get a POST call
		// $this->_request->getRawBody()
		// will return the $_POST DATA
		$data = $this->_request->getRawBody();
		return "FROM POST METHOD.\n" . 
		"===== DATA: =====\n" .
		var_export($data, true);
	}
	public  function delete($data)
	{
		return "FROM DELETE METHOD.\n" . 
			var_export($data, true);
	}
	public  function index($data)
	{
		return "FROM INDEX METHOD.\n" . 
			var_export($data, true);
	}
}

// Initiate the Zend_Rest_Server class object
$server = new Zend_Rest_Server();
// set our Simple Rest Server class to handle the 
// Rest Service Handling
$server->setClass('Simple_Rest_Server');
// calling handle() will map and make available the 
// functions contained in Simple_Rest_Server
// for web service call from a client
$server->handle();

// let say you put this file as rest_server.php on the the 
// DocumentRoot of you web server
// you can hit the REST service by typing 
// the following url into the browser
// http://your_server/rest_server.php?method=get?m1=testone?m2=testtwo

