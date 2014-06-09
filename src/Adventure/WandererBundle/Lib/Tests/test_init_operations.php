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
    var_dump($loc);
    echo "descr:" . $loc["description"];    


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
    
    var_dump($item);
    echo "item: " . $item["util_openable"];
  }
  
  
}
