<?php


/**
 * Need to add element & type checking
*/
function parse_xml_location_get_2d_array($obj) {
  //$xml = simplexml_load_string($xml_strg);
  //$obj = $xml->location;
  $exits = $obj->exits;
  $loc = array("short_lbl"=> $obj->short_lbl,
    "area"=> $obj->area,
    "x_val"=> intval($obj->x_val),
    "y_val"=> intval($obj->y_val),
    "description"=> $obj->description,
    "exit_n"=> intval($exits->n),
    "exit_ne"=> intval($exits->ne),
    "exit_e"=> intval($exits->e),
    "exit_se"=> intval($exits->se),
    "exit_s"=> intval($exits->s),
    "exit_sw"=> intval($exits->sw),
    "exit_w"=> intval($exits->w),
    "exit_nw"=> intval($exits->nw),
    "exit_up"=> intval($exits->up),
    "exit_down"=> intval($exits->down),
    "storey_val"=> intval($obj->storey_val),
    "visited"=> intval($obj->visited)
  );
  return $loc;
}


/**
 * Creates a SQL string with placeholders and runs a query. Returns result info.
 * @param mysqli-connection-object $db_conn
 * @param simplexml-object $obj
 * @return 
 */
function insert_location($db_conn, $loc) {
  $sql = sprintf("insert into location (
short_lbl,
area,
x_val,
y_val,
description,
image,
exit_n,
exit_ne,
exit_e,
exit_se,
exit_s,
exit_sw,
exit_w,
exit_nw,
exit_up,
exit_down,
storey_val,
visited) values (
'%s', '%s', %d, %d, '%s', '%s', %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %d
)", $loc["short_lbl"], $loc["area"], $loc["x_val"], $loc["y_val"], 
$loc["description"], $loc["image"], $loc["exit_n"], $loc["exit_ne"], 
$loc["exit_e"], $loc["exit_se"], $loc["exit_s"], $loc["exit_sw"], $loc["exit_w"], 
$loc["exit_nw"], $loc["exit_up"], $loc["exit_down"], $loc["storey_val"], 
$loc["visited"]);    
  $res = $db_conn->query($sql);  
  return $db_conn->get_error();
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


?>
