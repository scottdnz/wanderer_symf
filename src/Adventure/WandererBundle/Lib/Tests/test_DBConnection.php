<?php
//Run on the CLI with: phpunit test_DBConnection.php
require_once("../central_config.php");
require_once("mk_test_db.php");
require("../../Entity/DBConnection.php");


class DBConnectionTest extends PHPUnit_Framework_TestCase {

  protected static $conn;
  

  public static function setUpBeforeClass() {
    create_quick_test_data();
    // Comment the next lines out to test the connect/close methods
    global $db_params;
	  self::$conn = new DBConnection($db_params);
    self::$conn->connect();
  }
  
  
  public static function tearDownAfterClass() {
    // Comment this out if needed
    $ignored = self::$conn->close();
    remove_test_data();  
  }
  
  
  public function testConnect() {
    global $db_params;
	  $db_conn = new DBConnection($db_params);
    $db_conn->connect();
		$this->assertNotEquals(false, $db_conn); 
		$db_conn->close();
	}
	
	
	public function testConnectBadParam() {
    global $db_params;
    $db_params["password"] = "10 angry polar bears";
	  $db_conn = new DBConnection($db_params);
    $res = $db_conn->connect();
    $error_posn = strpos($db_conn->get_error(), "Access denied for user");
    $this->assertNotEquals(false, $error_posn); 
    $this->assertEquals(false, $res);
    
//    echo "error: " . $db_conn->get_error();
    /*
		$this->assertNotEquals(false, $db_conn); 
		$db_conn->close();*/
	}
	
	
	public function testClose() {
    global $db_params;
	  $db_conn = new DBConnection($db_params);
    $db_conn->connect();
    $res = $db_conn->close();
    $this->assertNotEquals(false, $res); 
  }

    
  public function testQueryInsert() {
    $insert_sql = "insert into test_records(first_name, last_name, phone, 
category) values ('sarah', 'davies', '280-5333', 'user');";
    $res = self::$conn->query($insert_sql);  
    $this->assertNotEquals(false, $res); 
  }  
  
  
  public function testQueryInsertBadField() {
    $insert_sql = "insert into test_records(first_name, last_name, phone, 
category) values ('sarah', 'davies', '280-5333', 'user', bad);";
    $res = self::$conn->query($insert_sql);  
    //echo "error: " . self::$conn->get_error();
    $this->assertStringStartsWith("Query: There was a problem running query:", 
      self::$conn->get_error());
    $this->assertStringEndsWith("Error: Unknown column 'bad' in 'field list'", 
      self::$conn->get_error());;
  }  
	
	
	public function testQueryInsertNoTable() {
    $insert_sql = "insert into records(first_name, last_name, phone, category) 
values ('sarah', 'davies', '280-5333', 'user', bad);";
    $res = self::$conn->query($insert_sql);  
    $this->assertStringEndsWith("Error: Table 'wanderer.records' doesn't exist", 
      self::$conn->get_error());;
  }  
	
	  
	public function testQueryInsertFieldsNumMismatch() {
    $insert_sql = "insert into test_records(first_name, last_name, phone, 
category) values ('sarah', 'davies', '280-5333');";
    $res = self::$conn->query($insert_sql);  
    $expected = "Error: Column count doesn't match value count at row 1";
    $this->assertStringEndsWith($expected, self::$conn->get_error());;
  }  
	
	
	public function testQueryInsertBadSyntax() {
    $insert_sql = "insert into test_records(first_name, last_name, phone, 
category) values ('sarah', 'davies', '2014-05-08, 'user');";
    $res = self::$conn->query($insert_sql);  
    $expected = "Error: You have an error in your SQL syntax;";
    $error_posn = strpos(self::$conn->get_error(), $expected);
    $this->assertNotEquals(false, $error_posn); 
  }
  
  
  
  
    
  
}

/*
create_quick_test_data();
$db_conn = new DBConnection($db_params);
echo "here 1";
echo $db_conn->get_error() . "\n";
$res = $db_conn->connect();
echo $db_conn->get_error() . "\n";
$res = $db_conn->close();
echo $db_conn->get_error() . "\n";
remove_test_data();
*/
