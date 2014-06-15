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


/*
<location>
    <short_lbl>Docks</short_lbl>
    <area>Renfyrd Town</area>
    <y_val>0</y_val>
    <x_val>0</x_val>
    <description>You are on the edge of the town docks, facing a large body of water. You can see several ships moored and sailors peforming various tasks on the long wharf.</description>
    <image>docks01.jpg</image>
    <exits>
      <n>0</n>
      <ne>0</ne>
      <e>0</e>
      <se>0</se>
      <s>1</s>
      <sw>0</sw>
      <w>0</w>
      <nw>0</nw>
      <up>0</up>
      <down>0</down>
    </exits>
    <storey_val>1</storey_val>
    <visited>0</visited>
  </location>
*/
/*
  fData.push("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>");
  fData.push("<request>");
  fData.push("<op>SaveNewLocation</op>");
  fData.push("<location>");
  fData.push("<y_val>" +  $("#locnYVal").val() + "</y_val>");
  fData.push("<x_val>" + $("#locnXVal").val() + "</x_val>");
  fData.push("<short_lbl>" + $("#locnShortLbl").val() + "</short_lbl>");
  fData.push("<area>" + $("#locnArea").val() + "</area>");
  fData.push("<description>" + description + "</description>");
  fData.push("<exits>" + exits + "</exits>");           
  fData.push("<image>" + $("#locnImage").val() + "</image>");
  fData.push("<storey_val>" + $("#locnStorey").val() + "</storey_val>");
  fData.push("<visited>0</visited>");
  fData.push("</location></request>");
  
*/
