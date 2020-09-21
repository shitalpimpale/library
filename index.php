<?php

require_once('database.php');
require_once('BookController.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// all of our endpoints start with /book
// everything else results in a 404 Not Found
if ($uri[3] !== 'book') {
  $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
  $response['statusCode'] = 404;
  $response['body'] = null;
  echo json_encode($response);
    exit();
}

// the Book id is, of course, optional and must be a number:
$Book = null;
if (isset($_GET['bookId'])) {
    $Book = (int) $_GET['bookId'];
}

$requestMethod = $_SERVER["REQUEST_METHOD"];
// pass the request method and Book ID to the BookController and process the HTTP request:
$controller = new BookController($dbConnection, $requestMethod, $Book);
$controller->processRequest();