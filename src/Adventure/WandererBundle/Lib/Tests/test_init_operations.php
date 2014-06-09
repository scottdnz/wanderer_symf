<?php
//Run on the CLI with: phpunit test_DBConnection.php
require_once("../central_config.php");
require("../../Entity/DBConnection.php");


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
"description"=> "<p>You are on the edge of the town docks, facing a large body of water. You can see several ships moored and sailors peforming various tasks on the long wharf.</p>",
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
    $y_val = $obj->y_val;
    echo "y_val: " . $y_val . "\n";
  }
  
  
  
}
