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
  
  
  public function test_() {   
   
    $res = self::$conn->query($sql);  
    
    
    $content = file_get_contents("location_req.xml");
    
    //self::$conn->get_error());;        
    $this->assertEquals(self::$conn->get_error(), "");
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
