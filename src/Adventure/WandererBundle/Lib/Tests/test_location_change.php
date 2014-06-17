<?php

//require("../../Entity/DBConnection.php");
require_once("../central_config.php");
require_once("location_class_test.php");

//use Adventure\WandererBundle\Entity\DBConnection;


/**
 * Reads an XML document with multiple locations and extracts the single location
 * with matching x & y values.
*/
function get_start_location_xml($xml_strg) {
    $obj = simplexml_load_string($xml_strg);
    $start_y = 1;
    $start_x = 3;
    foreach ($obj->location as $loc) {
      if ( ($loc->y_val == $start_y) && ($loc->x_val == $start_x) ) {
        $start_loc = $loc;
        break;
      }
    }
    // Copy XML element into standalone XML doc
    $obj = new SimpleXMLElement("<locations />");    
    $loc_node = $obj->addChild("location");
    foreach($start_loc->children() as $ch) {
      // Exits is a node on its own with 10 children
      if ($ch->getName() == "exits") {
        $exits = $loc_node->addChild("exits");
        foreach($ch->children() as $exit_ch) {
          $exit_node = $exits->addChild($exit_ch->getName(), intval($exit_ch));
        }
      }
      else {
        $ch_node = $loc_node->addChild($ch->getName(), $ch);
      }
    }
    return $obj->asXML();
}


class LocationTest extends PHPUnit_Framework_TestCase {

  //protected static $conn;
  protected static $xml_strg;
  protected static $start_location_xml;
  protected static $loc;
  protected static $X_LIMIT;
  protected static $Y_LIMIT;
  

  public static function setUpBeforeClass() {
    // Comment the next lines out to test the connect/close methods
    /*$vals = yaml_parse_file("../../../../../app/config/parameters.yml");
    $params = $vals["parameters"];
    $db_params = array("hostname"=> $params["database_host"],
    "username"=> $params["database_user"],
    "password"=> $params["database_password"],
    "database"=> $params["database_name"],
    "options"=> array("port"=> "")
    );
	  self::$conn = new DBConnection($db_params);
    self::$conn->connect();
    */
    //self::$xml_strg = file_get_contents("location_req.xml");
    self::$xml_strg = file_get_contents("../Init/locations_20140616.xml");
    self::$start_location_xml = get_start_location_xml(self::$xml_strg);
    self::$loc = new Location();
  }
  
  
  public static function tearDownAfterClass() {
    // Comment this out if needed
    //$ignored = self::$conn->close();
    //remove_test_data();  
  }

  
  
  public function test_set_from_XML() {   
    self::$loc->set_from_XML(self::$start_location_xml);
    $this->assertEquals(self::$loc->get_error(), "");
  }
  
  
  
  public function test_get_as_dict() {
    self::$loc->set_from_XML(self::$start_location_xml);
    $loc_dict = self::$loc->get_as_dict();
    $this->assertEquals(self::$loc->get_error(), "");
    /*echo "short_lbl: " . $loc_dict["short_lbl"] . "\n";
    $exit_vals = array("n", "ne", "e", "se", "s", "sw", "w", "nw", "up", "down");
    foreach ($exit_vals as $exit_val) {
      echo $exit_val . ": " . $loc_dict["exits"][$exit_val] . "\n";
    }*/
  }
  
  
  public function test_get_as_XML() {
    self::$loc->set_from_XML(self::$start_location_xml);
    $xml_text = self::$loc->get_as_XML();
    //echo $xml_text;
    $this->assertEquals(self::$loc->get_error(), "");
  }
  
      
  public function test_move_north() {   
    self::$loc->set_from_XML(self::$start_location_xml);
    $confirmation = self::$loc->try_move("n");
    $this->assertEquals($confirmation, "You move north. ");
  }
  
  
  public function test_move_northeast_no_exit() {   
    self::$loc->set_from_XML(self::$start_location_xml);
    $confirmation = self::$loc->try_move("ne");
    $this->assertEquals(self::$loc->get_error(), "Error - Type: Moving, You cannot go northeast. ");
  }
  
  
  public function test_move_east() {   
    self::$loc->set_from_XML(self::$start_location_xml);
    $confirmation = self::$loc->try_move("e");
    $this->assertEquals($confirmation, "You move east. ");
  }
  
  
  public function test_move_southeast() {   
    self::$loc->set_from_XML(self::$start_location_xml);
    $confirmation = self::$loc->try_move("se");
    $this->assertEquals($confirmation, "You move southeast. ");
  }
  
  
  public function test_move_south_no_exit() {   
    self::$loc->set_from_XML(self::$start_location_xml);
    $confirmation = self::$loc->try_move("s");
    //echo self::$loc->get_error();
    $this->assertEquals(self::$loc->get_error(), "Error - Type: Moving, You cannot go south. ");
  }
  
  
  public function test_move_southwest_no_exit() {   
    self::$loc->set_from_XML(self::$start_location_xml);
    $confirmation = self::$loc->try_move("sw");
    $this->assertEquals(self::$loc->get_error(), "Error - Type: Moving, You cannot go southwest. ");
  }
  
  
  public function test_move_west() {   
    self::$loc->set_from_XML(self::$start_location_xml);
    $confirmation = self::$loc->try_move("w");
    $this->assertEquals($confirmation, "You move west. ");
  }
  
  
  public function test_move_northwest_no_exit() {   
    self::$loc->set_from_XML(self::$start_location_xml);
    $confirmation = self::$loc->try_move("nw");
    $this->assertEquals(self::$loc->get_error(), "Error - Type: Moving, You cannot go northwest. ");
  }
  
  
  public function test_move_up_no_exit() {   
    self::$loc->set_from_XML(self::$start_location_xml);
    $confirmation = self::$loc->try_move("up");
    $this->assertEquals(self::$loc->get_error(), "Error - Type: Moving, You cannot climb up. ");
  }
  
  
  public function test_move_down_no_exit() {   
    self::$loc->set_from_XML(self::$start_location_xml);
    $confirmation = self::$loc->try_move("down");
    $this->assertEquals(self::$loc->get_error(), "Error - Type: Moving, You cannot climb down. ");
  }
  
  
  public function test_move_bad_direction() {   
    self::$loc->set_from_XML(self::$start_location_xml);
    $confirmation = self::$loc->try_move("tiddlywinks");
    $this->assertEquals(self::$loc->get_error(), "Error - Type: Moving, You cannot move in that direction. ");
  }
    
  /*
  public function test_() {   
   
    $res = self::$conn->query($sql);  
    //echo $res;
    //self::$conn->get_error());;        
    $this->assertEquals(self::$conn->get_error(), "");
  }
  */
  
  
}
