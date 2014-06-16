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


echo "Enter the locations XML source file name:\n";
$f_name = trim(fgets(STDIN));
if (! file_exists($f_name)) {
  echo "The file " . $f_name . " cannot be found. \n";
  exit(0);
} 


$content = file_get_contents($f_name);
$obj = simplexml_load_string($content);
//$obj = $xml->locations;
$rec_strgs = array();

foreach ($obj->location as $loc) {
  $exits = $loc->exits;
  $rec_strgs[] = sprintf("('%s', '%s', '%s', '%s', %d, %d, %d, %d, %d, %d, %d, 
    %d, %d, %d, %d, %d, %d, %d)", 
    $loc->short_lbl,
    $loc->area, 
    $loc->description,
    $loc->image, 
    $loc->x_val,
    $loc->y_val,
    $loc->storey_val,
    $loc->visited,
    $exits->n, $exits->ne, $exits->e, $exits->se, $exits->s, $exits->sw, 
    $exits->w, $exits->nw, $exits->up, $exits->down);
}
$sql = "insert into location (short_lbl, area, description, image, x_val, y_val, ";
$sql .= "storey_val, visited, exit_n, exit_ne, exit_e, exit_se, exit_s, exit_sw, ";
$sql .= "exit_w, exit_nw, exit_up, exit_down) values %s;";
$sql = sprintf($sql, join($rec_strgs, ","));

//echo $sql;

$res = $db_conn->query($sql);
$db_conn->connect();
if (strlen($db_conn->get_error()) > 0) {
  echo "Error: " . $db_conn->get_error();
  exit(0);
}
else {
  echo "Records inserted into location. \n";
}





