<?php 
/**
 * This file retrieves items from the database, and converts records
 * into XML.
 * 
 * @author Scott Davies
 * @version 1.0
 * @package
 */


require_once(__DIR__ . "/../../Entity/DBConnection.php");
use Adventure\WandererBundle\Entity\DBConnection;



function sort_special_attribs($attrib_category, $fld_names, $being_elem) {
  $elem = $being_elem->addChild($attrib_category);
  $elem->p = "0";
  $elem->b = "0";
  $elem->r = "0";
  $elem->m = "0";
  $elem->f = "0";
  $elem->n = "0";
  $elem->c = "0";
  foreach ($fld_names as $rec_field_name) { 
    
    if ($rec_field_name == "p") {
      $elem->p = "1";
    };
    if ($rec_field_name == "b") {
      $elem->b = "1";
    };
    if ($rec_field_name == "r") {
      $elem->r = "1";
    };
    if ($rec_field_name == "m") {
      $elem->m = "1";
    };
    if ($rec_field_name == "f") {
      $elem->f = "1";
    };
    if ($rec_field_name == "n") {
      $elem->n = "1";
    };
    if ($rec_field_name == "c") {
      $elem->c = "1";
    };
  }
  return $being_elem;
}



function make_xml_from_recs($recs) {
  $xml_obj = new SimpleXMLElement("<beings />");
  
  $fields = array("id", "name", "race", "hp", "level", "mp", "defence",
"image", "str", "dex", "con", "wis", "itg", "cha", "mood", "location_y",
"location_x", "location_storey", "weapon_id1", "item1_id", "item2_id", "gp",
"weapon_id2", "weapon_id3");
//  $special_attribs = array("p", "b", "r", "m", "f", "n", "c");
 
  foreach ($recs as $rec) {
    $being_elem = $xml_obj->addChild("being");
    foreach ($fields as $field) {
      $elem = $being_elem->addChild($field);
      $elem->{0} = $rec[$field];
    }
    
    $fld_names = array($rec["vulnerability1"], $rec["vulnerability2"]);
    $being_elem = sort_special_attribs("vulnerable", $fld_names, $being_elem);
    $fld_names = array($rec["resistance1"], $rec["resistance2"]);
    $being_elem = sort_special_attribs("resistant", $fld_names, $being_elem);
  }   
  return $xml_obj;
}


/**
 * Main flow of program.
 */
//Get Database connection vals from the Symfony config
$config_vals = yaml_parse_file("../../../../../app/config/parameters.yml");
$params = $config_vals["parameters"];
$db_params = array("hostname"=> $params["database_host"],
"username"=> $params["database_user"],
"password"=> $params["database_password"],
"database"=> $params["database_name"],
"options"=> array("port"=> "")
);
$db_conn = new DBConnection($db_params);
$db_conn->connect();
if (strlen($db_conn->get_error()) > 0) {
  echo "Error: " . $db_conn->get_error();
  exit();
}


$sql = "select * from being;";
$res = $db_conn->query($sql);  
if (strlen($db_conn->get_error()) > 0) {
  echo $db_conn->get_error();
}


$xml_obj = make_xml_from_recs($res);


$date_sfx = date("Ymd_his");
$f_name = sprintf("beings_%s.xml", $date_sfx);
$xml_obj->asXML($f_name);
echo "File " . $f_name . " written.\n";


