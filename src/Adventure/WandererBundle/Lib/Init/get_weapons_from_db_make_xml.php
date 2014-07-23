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

/*
function test_print($res) {
  foreach ($res as $rec) {
    echo $rec["short_lbl"] . ", " . $rec["area"] . ", " . $rec["x_val"] . ", " 
        . $rec["y_val"] . "\n"; 
  }
}
*/


function make_xml_from_recs($recs) {
  $xml_obj = new SimpleXMLElement("<weapons />");
  $fields = array("id", "name", "description", "image", "location_y", "location_x", 
"location_storey", "dmg1_type", "dmg1_min", "dmg1_max", "dmg2_type", "dmg2_min",
"dmg2_max", "bonus_status_type", "bonus_status_val", "reqd_level", "reqd_class",
"equipped", "condtn", "deteriorates", "available"); 
  foreach ($recs as $rec) {    
    foreach ($recs as $rec) {
      $item_elem = $xml_obj->addChild("weapon");
      foreach ($fields as $field) {
        $elem = $item_elem->addChild($field);
        $elem->{0} = $rec[$field];
      }
    }   
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


$sql = "select * from weapon;";
$res = $db_conn->query($sql);  
if (strlen($db_conn->get_error()) > 0) {
  echo $db_conn->get_error();
}

//test_print($res);


$xml_obj = make_xml_from_recs($res);

$date_sfx = date("Ymd_his");
$f_name = sprintf("weapons_%s.xml", $date_sfx);
$xml_obj->asXML($f_name);
echo "File " . $f_name . " written.\n";


