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
  exit();
}


echo "Enter the locations XML source file name:\n";
$line = trim(fgets(STDIN));
echo "k" . $line . "k\n";

/*
$content = file_get_contents("location.xml");
$xml = simplexml_load_string($content);
$obj = $xml->location;
$exits = $obj->exits;
*/


