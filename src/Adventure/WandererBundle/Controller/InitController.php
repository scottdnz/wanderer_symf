<?php

namespace Adventure\WandererBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Adventure\WandererBundle\Entity\DBConnection;


require_once __DIR__ . "/../Entity/DBConnection.php";
require_once __DIR__ . "/../Lib/Init/location_maker.php";


class InitController extends Controller {


  public function entryAction() {
    return $this->render('AdventureWandererBundle:Init:entry.html.twig');
  }
  
  
  // Init entry add
  public function addAction(Request $request) {
    $error = "";
    $conf = "";
    
    if (! $request->isXmlHttpRequest()) {
      
    }   
    
    $content = $request->getContent();   
    $xml = simplexml_load_string($content);
    
    if (! isset($xml->op)) {
      $error = "Operation is missing."; 
    }
    
    
    $vals = yaml_parse_file(__DIR__ . "/../../../../app/config/parameters.yml");
    $params = $vals["parameters"];
    $db_params = array("hostname"=> $params["database_host"],
    "username"=> $params["database_user"],
    "password"=> $params["database_password"],
    "database"=> $params["database_name"],
    "options"=> array("port"=> "")
    );

    
    $db_conn = new DBConnection($db_params);
    $db_conn->connect();
    
    
    if ($xml->op == "SaveNewLocation") {
    
      $loc = parse_xml_location_get_2d_array($xml->location);     
      $error_msg = insert_location($db_conn, $loc);
      if (strlen($error_msg) > 0) {
       $error .= "Problem saving location to database: " . $error_msg;
      }
      else {
        $conf .= "The location was successfully added/edited. ";
      }
    }
    $xml_resp = get_resp_strg($error, $conf);    
    
    $db_conn->close();

    return new Response($xml_resp, 
      Response::HTTP_OK,
      array('Content-Type'=> 'text/xml')
    );
  }
    
}

/*
$response = new Response();

$response->setContent('<html><body><h1>Hello world!</h1></body></html>');
$response->setStatusCode(Response::HTTP_OK);
$response->headers->set('Content-Type', 'text/html');

// prints the HTTP headers followed by the content
$response->send();
*/
