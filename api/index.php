<?php

function __autoload($classname) {
include strtolower($classname) . '.php';
}




// initialize the request object and store the requested URL
$request = new Request();
$request->url_elements = array();
if(isset($_SERVER['PATH_INFO'])) {
    $request->url_elements = explode('/', $_SERVER['PATH_INFO']);
}
//print_r($request->url_elements);

// figure out the verb and grab the incoming data
$request->verb = $_SERVER['REQUEST_METHOD'];
switch($request->verb) {
    case 'GET':
        $request->parameters = $_GET;
        break;
    case 'POST':
        $request->parameters = json_decode(file_get_contents('php://input'), 1);
    case 'PUT':
        $request->parameters = json_decode(file_get_contents('php://input'), 1);
        break;
    case 'DELETE':
    default:
        // we won't set any parameters in these cases
        $request->parameters = array();
}

$response;

// route the request
if($request->url_elements) {
    $controller_name = ucfirst($request->url_elements[1]) .'Controller';
    if(class_exists($controller_name)) {
        $controller = new $controller_name();
        $action_name = ucfirst($request->verb) . "Action";
        $response = $controller->$action_name($request);
    } else {
        header('HTTP/1.0 400 Bad Request');
        //$response = "Unknown Request for " . $request->url_elements[1];
    }
} 
else {
    header('HTTP/1.0 400 Bad Request');
}

$result = json_encode($response);
 print_r($result);

?>