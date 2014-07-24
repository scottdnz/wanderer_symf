<?php 
/**
 * This file retrieves locations from an XML source file, and inserts them
 * into the database location table.
 * 
 * @author Scott Davies
 * @version 1.0
 * @package
 */


require_once(__DIR__ . "/../../Entity/DBConnection.php");
use Adventure\WandererBundle\Entity\DBConnection;


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
  exit(0);
}


/*echo "Enter the weapons XML source file name:\n";
$f_name = trim(fgets(STDIN));*/
if (sizeof($argv) < 2) {
  echo "Please enter a filename argument. \n";
  exit(0);
}
$f_name = trim($argv[1]);
if (! file_exists($f_name)) {
  echo "The file " . $f_name . " cannot be found. \n";
  exit(0);
} 


$content = file_get_contents($f_name);
$obj = simplexml_load_string($content);
//$obj = $xml->locations;
$rec_strgs = array();

foreach ($obj->weapon as $weapon) {
 
  //echo $item->name . "\n";
  
  $rec_strgs[] = sprintf("('%s', '%s', '%s',
       %d, %d, %d, 
      '%s', %d, %d, '%s', %d, %d, 
      '%s', %d, 
      %d, '%s', %d, %d, %d, %d)", 
  $weapon->name,
  $weapon->description,
  $weapon->image,
  $weapon->location_y,
  $weapon->location_x,
  $weapon->location_storey,
  $weapon->dmg1_type,
  $weapon->dmg1_min,
  $weapon->dmg1_max,
  $weapon->dmg2_type,
  $weapon->dmg2_min,
  $weapon->dmg2_max,
  $weapon->bonus_status_type,
  $weapon->bonus_status_val,
  $weapon->reqd_level,
  $weapon->reqd_class,
  $weapon->equipped,
  $weapon->condtn,
  $weapon->deteriorates,
  $weapon->available);
}
    
$sql = "insert into weapon (
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
) values %s";   
$sql = sprintf($sql, join($rec_strgs, ","));
 

//echo $sql;

$res = $db_conn->query($sql);
$db_conn->connect();
if (strlen($db_conn->get_error()) > 0) {
  echo "Error: " . $db_conn->get_error();
  exit(0);
}
else {
  echo "Records inserted into weapon. \n";
}
