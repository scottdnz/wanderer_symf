<?php

//require_once("../central_config.php");


function get_test_db_conn() {
  global $db_params;
  $conn = mysqli_init();
  $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 30);
  $conn->real_connect($db_params["server"], $db_params["username"], $db_params["passwd"], $db_params["db"]);
  return $conn;
}


function create_quick_test_data() {
  $conn = get_test_db_conn();
  //Create table
  $sql = "create table test_records(id int not null auto_increment, first_name varchar(50), last_name varchar(50), phone varchar(15), category varchar(20), primary key (id));";
  $query_result = mysqli_query($conn, $sql);
  //Insert test records
  $sql = "insert into test_records(first_name, last_name, phone, category) values ('scott', 'davies', '280-5481', 'admin_user'),('jared', 'taylor', '444-5555', 'user')";
  $query_result = mysqli_query($conn, $sql);
  $result = mysqli_close($conn);
}


function remove_test_data() {
  $conn = get_test_db_conn();
  $sql = "drop table test_records;";
  $query_result = mysqli_query($conn, $sql);
  $result = mysqli_close($conn);  
}


//create_quick_test_data();
//remove_test_data();
