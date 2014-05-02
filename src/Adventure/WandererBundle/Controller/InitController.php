<?php

namespace Adventure\WandererBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . "/../Lib/Init/location_maker.php";

class InitController extends Controller {


  public function entryAction() {
    return $this->render('AdventureWandererBundle:Init:entry.html.twig');
  }
  
  
  // Init entry add
  public function addAction(Request $request) {
    $yesXML = "NOTXML";  
    if ($request->isXmlHttpRequest()) {
      $yesXML = "isXML";
    }
    //var_dump($request)
    $content = $request->getContent();   
    
    $xml = simplexml_load_string($content);
    $obj = $xml->location;
    
    $error = "error here";
    $conf = "conf here";
    $xml_resp = get_resp_strg($error, $conf);

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
