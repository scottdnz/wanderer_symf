<?php 
/**
 * This file retrieves locations from the database, and converts records
 * into XML.
 * 
 * @author Scott Davies
 * @version 1.0
 * @package
 */


require_once(__DIR__ . "/../../Entity/DBConnection.php");
use Adventure\WandererBundle\Entity\DBConnection;


function test_print($res) {
  foreach ($res as $rec) {
    echo $rec["short_lbl"] . ", " . $rec["area"] . ", " . $rec["x_val"] . ", " . $rec["y_val"] . "\n"; 
  }
}


function make_xml_from_recs($recs) {
  $xml_obj = new SimpleXMLElement("<locations />");  
  foreach ($recs as $rec) {
    $fields = array("id", "short_lbl", "area", "description", "image", "x_val", "y_val", "storey_val", "visited");
    foreach ($recs as $rec) {
      $loc_elem = $xml_obj->addChild("location");
      foreach ($fields as $field) {
        $elem = $loc_elem->addChild($field);
        $elem->{0} = $rec[$field];
      }
      $elem = $loc_elem->addChild("exits");
      $elem->n = $rec["exit_n"];
      $elem->ne = $rec["exit_ne"];
      $elem->e = $rec["exit_e"];
      $elem->se = $rec["exit_se"];
      $elem->s = $rec["exit_s"];
      $elem->sw = $rec["exit_sw"];
      $elem->w = $rec["exit_w"];
      $elem->up = $rec["exit_up"];
      $elem->down = $rec["exit_down"];
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


$sql = "select * from location group by short_lbl order by y_val, x_val;";
$res = $db_conn->query($sql);  
if (strlen($db_conn->get_error()) > 0) {
  echo $db_conn->get_error();
}

//test_print($res);


$xml_obj = make_xml_from_recs($res);

$date_sfx = date("Ymd_his");
$f_name = sprintf("locations_%s.xml", $date_sfx);
$xml_obj->asXML($f_name);
echo "File " . $f_name . "written.\n";


