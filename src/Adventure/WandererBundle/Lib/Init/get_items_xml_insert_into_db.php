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


/*echo "Enter the items XML source file name:\n";
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
$rec_strgs = array();

foreach ($obj->item as $item) {
  //echo $item->name . "\n";
  $utilities = $item->utilities;
  $states = $item->states;
  
  $rec_strgs[] = sprintf("('%s', '%s', '%s',
  %d, %d, %d,
  %d, 
  %d, %d, %d, %d, %d, 
  %d, %d, %d, 
  %d
  )", 
  $item->name,
  $item->description,
  $item->image,

  $item->location_y,
  $item->location_x,
  $item->location_storey,

  $item->uses_remaining,

  $utilities->breakable,
  $utilities->climbable,
  $utilities->lightable,
  $utilities->openable,
  $utilities->takeable,
  $states->open,
  $states->useable,
  $states->lit,
  $item->available
  );
}
    
$sql = "insert into item (
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
  echo "Records inserted into item. \n";
}
