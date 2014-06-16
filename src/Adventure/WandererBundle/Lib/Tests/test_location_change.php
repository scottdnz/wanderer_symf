<?php

require("../../Entity/DBConnection.php");

require("location_class_test.php");

use Adventure\WandererBundle\Entity\DBConnection;


class DBConnectionTest extends PHPUnit_Framework_TestCase {

  protected static $conn;
  

  public static function setUpBeforeClass() {
    // Comment the next lines out to test the connect/close methods
    $vals = yaml_parse_file("../../../../../app/config/parameters.yml");
    $params = $vals["parameters"];
    $db_params = array("hostname"=> $params["database_host"],
    "username"=> $params["database_user"],
    "password"=> $params["database_password"],
    "database"=> $params["database_name"],
    "options"=> array("port"=> "")
    );
	  self::$conn = new DBConnection($db_params);
    self::$conn->connect();
  }
  
  
  public static function tearDownAfterClass() {
    // Comment this out if needed
    $ignored = self::$conn->close();
    //remove_test_data();  
  }
  
  
  public function test_set_from_XML() {   
    $xml_strg = file_get_contents("location_req.xml");    
    $loc = new Location();
    $loc->set_from_XML($xml_strg);
    $this->assertEquals($loc->get_error(), "");
  }
  
  
  public function test_get_as_dict() {
    $xml_strg = file_get_contents("location_req.xml");
    $loc = new Location();
    $loc->set_from_XML($xml_strg);
    
    $loc_dict = $loc->get_as_dict();
    /*echo "short_lbl: " . $loc_dict["short_lbl"] . "\n";
    $exit_vals = array("n", "ne", "e", "se", "s", "sw", "w", "nw", "up", "down");
    foreach ($exit_vals as $exit_val) {
      echo $exit_val . ": " . $loc_dict["exits"][$exit_val] . "\n";
    }*/
    $this->assertEquals($loc->get_error(), "");
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
