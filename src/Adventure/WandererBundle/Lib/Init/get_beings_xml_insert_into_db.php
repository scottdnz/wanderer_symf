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


function get_attrib_vals($attribs) {
  $attrib_vals = array();
  if ($attribs->p == "1") {
    $attrib_vals[] = "p";
  }
  if ($attribs->b == "1") {
      $attrib_vals[] = "b";
  } 
  if ($attribs->r == "1") {
      $attrib_vals[] = "r";
  }
  if ($attribs->m == "1") {
      $attrib_vals[] = "m";
  }
  if ($attribs->f == "1") {
      $attrib_vals[] = "f";
  }
  if ($attribs->n == "1") {
      $attrib_vals[] = "n";
  }
  if ($attribs->c == "1") {
     $attrib_vals[] = "c";
  }
  if (sizeof($attrib_vals) == 0) {
    $attrib_vals = array("", "");
  }
  elseif (sizeof($attrib_vals) == 1) {
     $attrib_vals[] = "";
  }
  return $attrib_vals;
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

foreach ($obj->being as $being) {  
  $resist_vals = get_attrib_vals($being->resistant);
  $vulnerable_vals = get_attrib_vals($being->vulnerable);

  $rec_strgs[] = sprintf("('%s', '%s', 
  %d, %d, %d, %d, '%s',
  '%s', '%s', '%s', '%s', '%s', '%s', 
  '%s',%d, %d, %d, 
  %d, %d, %d, %d, 
  '%s', '%s', '%s', '%s', %d, %d)", 
  $being->name,
  $being->race,
  $being->hp,
  $being->level,
  $being->mp,
  $being->defence,
  $being->image,
  $being->str,
  $being->dex,
  $being->con,
  $being->wis,
  $being->itg,
  $being->cha,
  $being->mood,
  $being->location_y,
  $being->location_x,
  $being->location_storey,
  $being->weapon_id1,
  $being->item1_id,
  $being->item2_id,
  $being->gp,
  $resist_vals[0],
  $resist_vals[1],
  $vulnerable_vals[0],
  $vulnerable_vals[1],
  $being->weapon_id2,
  $being->weapon_id3
  );
}
    
$sql = "insert into being(
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
weapon_id3) values %s";   
$sql = sprintf($sql, join($rec_strgs, ","));
 

//echo $sql;

$res = $db_conn->query($sql);
$db_conn->connect();
if (strlen($db_conn->get_error()) > 0) {
  echo "Error: " . $db_conn->get_error();
  exit(0);
}
else {
  echo "Records inserted into being. \n";
}
