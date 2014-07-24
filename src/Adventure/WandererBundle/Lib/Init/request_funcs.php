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
  "location_storey"=> intval($obj->location_storey),
  "uses_remaining"=> intval($obj->uses_remaining),
  "util_breakable"=> intval($utilities->breakable),
  "util_climbable"=> intval($utilities->climbable),
  "util_lightable"=> intval($utilities->lightable),
  "util_openable"=> intval($utilities->openable),
  "util_takeable"=> intval($utilities->takeable),
  "state_open"=> intval($states->open),
  "state_useable"=> intval($states->useable),
  "state_lit"=> intval($states->lit),
  "available"=> intval($obj->available)
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
location_storey,
uses_remaining,
util_breakable,
util_climbable,
util_lightable,
util_openable,
util_takeable,
state_open,
state_useable,
state_lit,
available
) values (
'%s', '%s', '%s', %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %d)",  
$item["name"],
$item["description"],
$item["image"],
$item["location_y"],
$item["location_x"],
$item["location_storey"],
$item["uses_remaining"],
$item["util_breakable"],
$item["util_climbable"],
$item["util_lightable"],
$item["util_openable"],
$item["util_takeable"],
$item["state_open"],
$item["state_useable"],
$item["state_lit"],
$item["available"]
);
  $res = $db_conn->query($sql);  
  return $db_conn->get_error();
}


function parse_xml_weapon_get_2d_array($obj) {
  $weapon = array("name"=> $obj->name,
    "description"=> $obj->description,
    "image"=> $obj->image,
    "location_y"=> intval($obj->location_y),
    "location_x"=> intval($obj->location_x),
    "location_storey"=> intval($obj->location_storey),
    "dmg1_type"=> $obj->dmg1_type,
    "dmg1_min"=> intval($obj->dmg1_min),
    "dmg1_max"=> intval($obj->dmg1_max),
    "dmg2_type"=> $obj->dmg2_type,
    "dmg2_min"=> intval($obj->dmg2_min),
    "dmg2_max"=> intval($obj->dmg2_max),
    "bonus_status_type"=> $obj->bonus_status_type,
    "bonus_status_val"=> intval($obj->bonus_status_val),
    "reqd_class"=> $obj->reqd_class,
    "reqd_level"=> intval($obj->reqd_level),
    "equipped"=> intval($obj->equipped),
    "condtn"=> intval($obj->condtn),
    "deteriorates"=> intval($obj->deteriorates),
    "available"=> intval($obj->available)
    );
  return $weapon;
}


function insert_weapon($db_conn, $weapon) {
  $sql = sprintf("insert into weapon (
name,
description,
image,
location_y,
location_x,
location_storey,
dmg1_type,
dmg1_min,
dmg1_max,
dmg2_type,
dmg2_min,
dmg2_max,
bonus_status_type,
bonus_status_val,
reqd_level,
reqd_class,
equipped,
condtn,
deteriorates,
available
) values (
'%s', '%s', '%s', %d, %d, %d, '%s', %d, %d, '%s', %d, %d, '%s', %d, %d, '%s', %d, %d, %d, %d)",
$weapon["name"],
$weapon["description"],
$weapon["image"],
$weapon["location_y"],
$weapon["location_x"],
$weapon["location_storey"],
$weapon["dmg1_type"],
$weapon["dmg1_min"],
$weapon["dmg1_max"],
$weapon["dmg2_type"],
$weapon["dmg2_min"],
$weapon["dmg2_max"],
$weapon["bonus_status_type"],
$weapon["bonus_status_val"],
$weapon["reqd_level"],
$weapon["reqd_class"],
$weapon["equipped"],
$weapon["condtn"],
$weapon["deteriorates"],
$weapon["available"]);
  $res = $db_conn->query($sql);  
  return $db_conn->get_error();
}


function parse_xml_being_get_2d_array($obj) {
  $resistances = array("", "");
  $vulnerabilities = array("", "");
  if (sizeof($obj->resistant->children()) > 0) {
    $i = 0;
    //foreach ($obj->resistances->resistance as $resistance) {
    foreach ($obj->resistant->children() as $resistance) {
      if ($resistance == "1") {
        $resistances[$i] = $resistance->getName();
        $i++;
      }
    }
  }
  if (sizeof($obj->vulnerable->children()) > 0) {
    $i = 0;
    //foreach ($obj->vulnerabilities->vulnerability as $vulnerability) {
    foreach ($obj->vulnerable->children() as $vulnerability) {
      if ($vulnerability == "1") {  
        $vulnerabilities[$i] = $vulnerability->getName();
        $i++;
      }
    }
  }
  $being = array("name"=> strval($obj->name),
    "race"=> ($obj->race),
    "hp"=> intval($obj->hp),
    "level"=> intval($obj->level),
    "mp"=> intval($obj->mp),
    "defence"=> intval($obj->defence),
    "image"=> strval($obj->image),
    "str"=> intval($obj->str),
    "dex"=> intval($obj->dex),
    "con"=> intval($obj->con),
    "wis"=> intval($obj->wis),
    "itg"=> intval($obj->itg),
    "cha"=> intval($obj->cha),
    "mood"=> strval($obj->mood),
    "location_y"=> intval($obj->location_y),
    "location_x"=> intval($obj->location_x),
    "location_storey"=> intval($obj->location_storey),
    "weapon_id1"=> intval($obj->weapon_id1),
    "item1_id"=> intval($obj->item1_id),
    "item2_id"=> intval($obj->item2_id),
    "gp"=> intval($obj->gp),
    "resistance1"=> $resistances[0],
    "resistance2"=> $resistances[1],
    "vulnerability1"=> $vulnerabilities[0],
    "vulnerability2"=> $vulnerabilities[1],
    "weapon_id2"=> intval($obj->weapon_id2),
    "weapon_id3"=> intval($obj->weapon_id3),
    "available"=> intval($obj->available)
    );
  return $being;
}


function insert_being($db_conn, $being) {
  $sql = sprintf("insert into being (
name,
race,
hp,
level,
mp,
defence,
image,
str,
dex,
con,
wis,
itg,
cha,
mood,
location_y,
location_x,
location_storey,
weapon_id1,
item1_id,
item2_id,
gp,
resistance1,
resistance2,
vulnerability1,
vulnerability2,
weapon_id2,
weapon_id3) values (
'%s', '%s', 
%d, %d, %d, %d, '%s',
'%s', '%s', '%s', '%s', '%s', '%s', 
'%s',%d, %d, %d, 
%d, %d, %d, %d, 
'%s', '%s', '%s', '%s', %d, %d);", 
$being["name"],
$being["race"],
$being["hp"],
$being["level"],
$being["mp"],
$being["defence"],
$being["image"],
$being["str"],
$being["dex"],
$being["con"],
$being["wis"],
$being["itg"],
$being["cha"],
$being["mood"],
$being["location_y"],
$being["location_x"],
$being["location_storey"],
$being["weapon_id1"],
$being["item1_id"],
$being["item2_id"],
$being["gp"],
$being["resistance1"],
$being["resistance2"],
$being["vulnerability1"],
$being["vulnerability2"],
$being["weapon_id2"],
$being["weapon_id3"]);
  $res = $db_conn->query($sql);  
  return $db_conn->get_error();
}


function get_available_items_as_xml($db_conn) {
  // DB query
  $sql = "select id, name from item where available = 1 order by name;";
  $res = $db_conn->query($sql);
  
  // Store records in XML object
  $resp_obj = new SimpleXMLElement("<response />");
  $items_elem = $resp_obj->addChild("items");
  if (strlen($db_conn->get_error()) > 0) {
    $elem = $resp_obj->AddChild("error");
    $elem->{0} = $db_conn->get_error();
  }
  else {
    foreach ($res as $rec) {
      $item_elem = $items_elem->addChild("item");
      $elem = $item_elem->addChild("id");
      $elem->{0} = $rec["id"];
      $elem = $item_elem->addChild("name");
      $elem->{0} = $rec["name"];
    }
  }
  return $resp_obj->asXML();
}


function get_available_weapons_as_xml($db_conn) {
  // DB query
  $sql = "select id, name from weapon where available = 1 order by name;";
  $res = $db_conn->query($sql);
  
  // Store records in XML object
  $resp_obj = new SimpleXMLElement("<response />");
  $items_elem = $resp_obj->addChild("weapons");
  if (strlen($db_conn->get_error()) > 0) {
    $elem = $resp_obj->AddChild("error");
    $elem->{0} = $db_conn->get_error();
  }
  else {
    foreach ($res as $rec) {
      $item_elem = $items_elem->addChild("weapon");
      $elem = $item_elem->addChild("id");
      $elem->{0} = $rec["id"];
      $elem = $item_elem->addChild("name");
      $elem->{0} = $rec["name"];
    }
  }
  return $resp_obj->asXML();
}
  


/**
 * Creates an XML document object from the parameters passed in. Returns it
 * converted to a string.
 * @param string $error
 * @param string $conf
 * @return string
 */
function get_insert_resp_strg($error, $conf) {
  $resp_obj = new SimpleXMLElement("<response />");
  $error_elem = $resp_obj->addChild("error");
  $error_elem->{0} = $error;
  $conf_elem = $resp_obj->addChild("conf");
  $conf_elem->{0} = $conf;
  //Testing. Save XML object to file.
  //$respObj->asXML('xml_resp.xml');
  return $resp_obj->asXML();
}

