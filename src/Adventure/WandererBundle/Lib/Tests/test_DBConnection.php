<?php
//Run on the CLI with: phpunit test_DBConnection.php
require_once("../central_config.php");
require_once("mk_test_db.php");
require("../../Entity/DBConnection.php");

/*
class DBConnectionTest extends PHPUnit_Framework_TestCase {

  public static function setUpBeforeClass() {
    create_quick_test_data();
  }
  

	public function testConnect() {
    global $db_params;
	  $db_conn = new DBConnection($db_params);
	  echo "here 1";
    $db_conn->connect();
		$this->assertEquals(false, $db_conn); 
	}
	
	
	public static function tearDownAfterClass() {
    remove_test_data();
  }
  
}*/

create_quick_test_data();
$db_conn = new DBConnection($db_params);
echo "here 1";
$res = $db_conn->connect();
$res = $db_conn->close();
remove_test_data();
