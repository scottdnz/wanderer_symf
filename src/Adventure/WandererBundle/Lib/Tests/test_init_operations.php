<?php
//Run on the CLI with: phpunit test_DBConnection.php
require_once("../central_config.php");
require("../../Entity/DBConnection.php");

use Adventure\WandererBundle\Entity\DBConnection;


class DBConnectionTest extends PHPUnit_Framework_TestCase {

  protected static $conn;
  

  public static function setUpBeforeClass() {
    // Comment the next lines out to test the connect/close methods
    global $db_params;
	  self::$conn = new DBConnection($db_params);
    self::$conn->connect();
  }
  
  
  public static function tearDownAfterClass() {
    // Comment this out if needed
    $ignored = self::$conn->close();
    //remove_test_data();  
  }
  

/*  
  public function test() {
    global $db_params;
    $res = self::$conn->query($_sql);  
    self::$conn->get_error());;
    
    
  }
*/


  public function test_insert_location() {   
    $loc = array("short_lbl"=> "Docks",
"area"=> "Renfyrd Town",
"x_val"=> 0,
"y_val"=> 0,
"description"=> "You are on the edge of the town docks, facing a large body of water. You can see several ships moored and sailors peforming various tasks on the long wharf.",
"image"=> "docks01.jpg",
"exit_n"=> 0,
"exit_ne"=> 0,
"exit_e"=> 0,
"exit_se"=> 0,
"exit_s"=> 1,
"exit_sw"=> 0,
"exit_w"=> 0,
"exit_nw"=> 0,
"exit_up"=> 0,
"exit_down"=> 0,
"storey_val"=> 1,
"visited"=> 0);
    
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
    
    $res = self::$conn->query($sql);  
    //echo $res;
    //self::$conn->get_error());;        
    $this->assertEquals(self::$conn->get_error(), "");
  }

  
  
  public function test_parse_location_xml() { 
    $content = file_get_contents("location_req.xml");
    $xml = simplexml_load_string($content);
    $obj = $xml->location;
    $exits = $obj->exits;
    
    $loc = array("short_lbl"=> $obj->short_lbl,
      "area"=> $obj->area,
      "x_val"=> intval($obj->x_val),
      "y_val"=> intval($obj->y_val),
      "description"=> $obj->description,
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
    
    //delete from location where id;
    /*var_dump($loc);
    echo "descr:" . $loc["description"];    */


  }
  
  
  public function test_parse_item_xml() { 
    $content = file_get_contents("item_req.xml");
    $xml = simplexml_load_string($content);
    $obj = $xml->item;
    
    $utilities = $obj->utilities;
    $states = $obj->states;
    
    $item = array("name"=> $obj->name,
    "description"=> $obj->description,
    "image"=> $obj->image,
    "location_y"=> intval($obj->location_y),
    "location_x"=> intval($obj->location_x),
    "uses_remaining"=> intval($obj->uses_remaining),
    "util_breakable"=> intval($utilities->breakable),
    "util_climbable"=> intval($utilities->climbable),
    "util_lightable"=> intval($utilities->lightable),
    "util_openable"=> intval($utilities->openable),
    "util_takeable"=> intval($utilities->takeable),
    "state_open"=> intval($states->open),
    "state_useable"=> intval($states->useable),
    "state_lit"=> intval($states->lit)
    );
    
    /*var_dump($item);
    echo "item: " . $item["util_openable"];*/
  }
  
  
  public function test_parse_weapon_xml() { 
    $content = file_get_contents("weapon_req.xml");
    $xml = simplexml_load_string($content);
    $obj = $xml->weapon;
  
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
    "deteriorates"=> intval($obj->deteriorates)
    );
//    var_dump($weapon);
  }
  
  
  public function test_parse_being_xml() { 
    $content = file_get_contents("being_req.xml");
    $xml = simplexml_load_string($content);
    $obj = $xml->being;
    
    $resistances = array("", "");
    $vulnerabilities = array("", "");
    $i = 0;
    foreach ($obj->resistances->resistance as $resistance) {
      $resistances[$i] = strval($resistance);
      $i++;
    }
    $i = 0;
    foreach ($obj->vulnerabilities->vulnerability as $vulnerability) {
      $vulnerabilities[$i] = strval($vulnerability);
      $i++;
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
    "mood"=> intval($obj->mood),
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
    "weapon_id3"=> intval($obj->weapon_id3)
    );
    var_dump($being);  
  }
  
  
  function test_get_available_items() {
    $sql = "select id, name from item where available = 1;";
    //$res = $db_conn->query($sql);
    $res = self::$conn->query($sql);  
    if (strlen(self::$conn->get_error()) > 0) {
      echo self::$conn->get_error();
    }
    
    /*
    $xml_obj = new SimpleXMLElement("<items />");  
    //$fields = array("id", "name");
    foreach ($res as $rec) {
      $loc_elem = $xml_obj->addChild("item");
      $elem = $loc_elem->addChild("id");
      $elem->{0} = $rec["id"];
      $elem = $loc_elem->addChild("name");
      $elem->{0} = $rec["name"];
    }
    echo $xml_obj->asXML();
    */
    $resp_obj = new SimpleXMLElement("<response />");
    $items_elem = $resp_obj->addChild("items");
    foreach ($res as $rec) {
      $item_elem = $items_elem->addChild("item");
      $elem = $item_elem->addChild("id");
      $elem->{0} = $rec["id"];
      $elem = $item_elem->addChild("name");
      $elem->{0} = $rec["name"];
    }
    echo $resp_obj->asXML();
  }
  
  
}
