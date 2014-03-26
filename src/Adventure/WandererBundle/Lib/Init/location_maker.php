<?php


//require("../../lib/db_funcs.php");

function test_check($content) {
  $xml = simplexml_load_string($content);
  $obj = $xml->location;
  $y_val = $obj->y_val;
  //file_put_contents($fname, $y_val);
  return $y_val;
}

/**
 * Creates a SQL string with placeholders and runs a query. Returns result info.
 * @param mysqli-connection-object $conn
 * @param simplexml-object $obj
 * @return array $result_dic
 */
function insert_location($conn, $obj) {
  $sql = sprintf("insert into location (
y_val, x_val, short_lbl, area, description, exits, storey_val, image, visited)
values (
%d, %d, '%s', '%s', '%s', '%s', %d, '%s', %d);",
  $obj->y_val,
  $obj->x_val,
  $obj->short_lbl,
  $obj->area,
  $obj->description,
  $obj->exits,
  $obj->storey_val,
  $obj->image,
  $obj->visited
  );    
  //$result_dic = run_modify_query($conn, $sql);
	//return $result_dic;
}


/**
 * Creates an XML document object from the parameters passed in. Returns it
 * converted to a string.
 * @param string $error
 * @param string $conf
 * @return string
 */
function get_resp_strg($error, $conf) {
  $respObj = new SimpleXMLElement("<location_response />");
  $error_elem = $respObj->addChild("error");
  $error_elem->{0} = $error;
  $conf_elem = $respObj->addChild("conf");
  $conf_elem->{0} = $conf;
  //Testing. Save XML object to file.
  //$respObj->asXML('xml_resp.xml');
  return $respObj->asXML();
}


/*if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $error = "";
  $conf = "";

  $result_dic = get_connection();
  if (strlen($result_dic["error"]) > 0) {
	  // Possible connection error found.
	  $error .= $result_dic["error"];
  }
  if (strlen($error) == 0) {
    $conn = $result_dic["connection"];
    //DB connection is OK. Process the XML request.
    $req = file_get_contents('php://input');
    //Testing. Save XML request string to file.
    //file_put_contents("xml_req.xml", $req);
    $xml = simplexml_load_string($req);
    
    if ($xml->op == "SaveNew") {
      $result_dic = insert_location($conn, $xml->location);
      if (strlen($result_dic["error"]) > 0) {
	     $error .= "Problem saving location to database: " . $result_dic["error"];
      }
    } 
  }
  if (strlen($error) == 0) {
    $conf .= "The location was successfully added/edited. ";
  }
  $resp_strg = get_resp_strg($error, $conf); 
  
  header("Content-type: text/xml");
  echo $resp_strg;

}*/

?>
