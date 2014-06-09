<?php

namespace Adventure\WandererBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Adventure\WandererBundle\Entity\DBConnection;


require_once __DIR__ . "/../Entity/DBConnection.php";
require_once __DIR__ . "/../Lib/Init/request_funcs.php";


class InitController extends Controller {

  //Default view
  public function entryAction() {
    return $this->render('AdventureWandererBundle:Init:entry.html.twig');
  }
  
  
  // Init entry add
  public function addAction(Request $request) {
    $error = "";
    $conf = "";
    
    if ($request->isXmlHttpRequest()) {
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
    
    
      if ($xml->op == "SaveNewItem") {
        $item = parse_xml_item_get_2d_array($xml->item);
        $error_msg = insert_item($db_conn, $item);
        if (strlen($error_msg) > 0) {
         $error .= "Problem saving item to database: " . $error_msg;
        }
        else {
          $conf .= "The item was successfully added/edited. ";
        }
      }
    }
    
    else {  // Not an XML request
      $error .= "Not a valid XML request";
    }
    
  
    $xml_resp = get_resp_strg($error, $conf);    
    $db_conn->close();
    return new Response($xml_resp, 
      Response::HTTP_OK,
      array('Content-Type'=> 'text/xml')
    );
  }
        
} // End of Controller


/*
$response = new Response();

$response->setContent('<html><body><h1>Hello world!</h1></body></html>');
$response->setStatusCode(Response::HTTP_OK);
$response->headers->set('Content-Type', 'text/html');

// prints the HTTP headers followed by the content
$response->send();
*/
