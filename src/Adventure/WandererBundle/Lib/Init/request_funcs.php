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
    "image"=> $obj->image,
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


function parse_xml_item_get_2d_array($obj) {
  $utilities = $obj->utilities;
  $states = $obj->states;
  
  $item = array("name"=> $obj->name,
  "description"=> $obj->description,
  "image"=> $obj->image,
  "location_y"=> intval($obj->location_y),
  "location_x"=> intval($obj->location_x),
  "uses_remaining"=> intval($obj->uses_remaining),
  "util_breakable"=> intval($utilities->breakable),
  "util_climbable"=> intval($utilities->climbable),
  "util_lightable"=> intval($utilities->lightable),
  "util_openable"=> intval($utilities->openable),
  "util_takeable"=> intval($utilities->takeable),
  "state_open"=> intval($states->open),
  "state_useable"=> intval($states->useable),
  "state_lit"=> intval($states->lit)
  );
  return $item;
}


function insert_item($db_conn, $item) {
  $sql = sprintf("insert into item (
name,
description,
image,
location_y,
location_x,
uses_remaining,
util_breakable,
util_climbable,
util_lightable,
util_openable,
util_takeable,
state_open,
state_useable,
state_lit) values (
'%s', '%s', '%s', %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %d)",  
$item["name"],
$item["description"],
$item["image"],
$item["location_y"],
$item["location_x"],
$item["uses_remaining"],
$item["util_breakable"],
$item["util_climbable"],
$item["util_lightable"],
$item["util_openable"],
$item["util_takeable"],
$item["state_open"],
$item["state_useable"],
$item["state_lit"]);
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
  $respObj = new SimpleXMLElement("<response />");
  $error_elem = $respObj->addChild("error");
  $error_elem->{0} = $error;
  $conf_elem = $respObj->addChild("conf");
  $conf_elem->{0} = $conf;
  //Testing. Save XML object to file.
  //$respObj->asXML('xml_resp.xml');
  return $respObj->asXML();
}



