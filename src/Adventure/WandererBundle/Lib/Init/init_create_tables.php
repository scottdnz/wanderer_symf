<?php 
/**
 * This file creates tables with their fields in the database.
 * 
 * @author Scott Davies
 * @version 1.0
 * @package
 */


require_once(__DIR__ . "/../../Entity/DBConnection.php");


/**
 * Drops all tables in the database, if required.
 * @param "database connection object" $conn
 */
function drop_all_tables($db_conn) {
	$sql = "show tables;";
	$res = $db_conn->query($sql);
	foreach($res as $row) {
		$tbl = $row["Tables_in_wanderer"];
		$sql = sprintf("drop table if exists %s;", $tbl);
		$res = $db_conn->query($sql);
		if (strlen($db_conn->get_error()) < 1) {
			echo "Table '" . $tbl . "' dropped.\n";
		}
		else {
			echo sprintf("Problem, table '%s' NOT dropped.\nError: %s", $tbl, 
			            $db_conn->get_error());
		}
	}
	return;
}


/**
 * Creates all the tables via functions.
 * @param "database connection object" $conn
 */
 
function create_all_tables($db_conn) {	
	$error_msg = "";
  $res = create_location($db_conn);
  $error_msg .= $db_conn->get_error();
  $res = create_pc($db_conn);
  $error_msg .= $db_conn->get_error();
  $res = create_being($db_conn);	
  $error_msg .= $db_conn->get_error();
	$res = create_weapon($db_conn);
	$error_msg .= $db_conn->get_error();
	$res = create_item($db_conn);
	$error_msg .= $db_conn->get_error();
	$res = create_armour($db_conn);
	$error_msg .= $db_conn->get_error();
	$res = create_magic_item($db_conn);
	$error_msg .= $db_conn->get_error();
	return $error_msg;
}


/* Creates a table. */
function create_location($db_conn) {
	$sql = "create table location(
id int not null auto_increment,
short_lbl varchar(20) not null,
area varchar(25) not null,
x_val int not null,
y_val int not null,
description varchar(255) not null,
image varchar(35) not null,
exit_n tinyint not null, 
exit_ne tinyint not null,
exit_e tinyint not null,  
exit_se tinyint not null, 
exit_s tinyint not null, 
exit_sw tinyint not null, 
exit_w tinyint not null, 
exit_nw tinyint not null, 
exit_up  tinyint not null,
exit_down tinyint not null, 
storey_val int not null,
visited tinyint not null,
primary key (id)
);";
  $db_conn->query($sql);  
  return $db_conn->get_error();
}


/* Creates a table for the main player/npcs. */
function create_pc($db_conn) {
	$sql = "create table pc(
id int not null auto_increment,
name varchar(25),
xp int not null,
level smallint not null,
class varchar(10),
race varchar(15),
hp smallint not null,
mp smallint not null,
defence smallint not null,
image varchar(35) not null,
str smallint not null,
dex smallint not null,
con smallint not null,
wis smallint not null,
itg smallint not null,
cha smallint not null,
mood char(1),
willing_follow tinyint,
following tinyint,
location_y smallint not null,
location_x smallint not null,
weapon1_id int,
weapon2_id int,
weapon3_id int,
armour1_id int,
armour2_id int,
armour3_id int,
armour4_id int,
armour5_id int,
armour6_id int,
item1_id int,
item2_id int,
item3_id int,
item4_id int,
item5_id int,
item6_id int,
item7_id int,
item8_id int,
item9_id int,
item10_id int,
magic_item1_id int,
magic_item2_id int,
magic_item3_id int,
magic_item4_id int,
magic_item5_id int,
magic_item6_id int,
magic_item7_id int,
magic_item8_id int,
magic_item9_id int,
magic_item10_id int,
gp int not null,
primary key (id)
);";
  $db_conn->query($sql);  
  return $db_conn->get_error();
}


/* Creates a table. */
function create_being($db_conn) {
	$sql = "create table being(
id int not null auto_increment,
name varchar(25),
race varchar(25),
race_plural varchar(30),
hp smallint not null,
level smallint not null,
mp smallint not null,
defence smallint not null,
image varchar(35) not null,
str smallint not null,
dex smallint not null,
con smallint not null,
wis smallint not null,
itg smallint not null,
cha smallint not null,
mood char(1) not null,
location_y smallint not null,
location_x smallint not null,
weapon_id1 int not null,
item1_id int,
item2_id int,
gp int not null,
resistant varchar(25),
vulnerable varchar(25),
weapon_id2 int,
weapon_id3 int,
primary key (id)
);";
  $db_conn->query($sql);  
  return $db_conn->get_error();
}


/* Creates a table. */
function create_item($db_conn) {
	$sql = "create table item(
id int not null auto_increment,
name varchar(30),
description varchar(255),
image varchar(35),
utility varchar(50),
state varchar(50),
location_y smallint,
location_x smallint,
uses_remaining smallint,
primary key (id)
);";
  $db_conn->query($sql);  
  return $db_conn->get_error();
}


/* Creates a table. */
function create_weapon($db_conn) {
	$sql = "create table weapon(
id int not null auto_increment,
name varchar(30) not null,
description varchar(255) not null,

dmg1_type char(1) not null,
dmg1_min smallint not null,
dmg1_max smallint not null,

dmg2_type char(1) not null,
dmg2_min smallint not null,
dmg2_max smallint not null,

bonus_status_type char(1),
bonus_status_val smallint,

reqd_level smallint not null,
reqd_class char(1),

equipped tinyint not null,
condtn smallint,
deteriorates tinyint not null,
location_y smallint,
location_x smallint,
primary key (id)
);";
  $db_conn->query($sql);  
  return $db_conn->get_error();
}


/* Creates a table. */
function create_armour($db_conn) {
	$sql = "create table armour(
id int not null auto_increment,
name varchar(30) not null,
description varchar(255) not null,
condtn smallint,
body_section tinyint,
equipped tinyint,
bonus_status_type char(1),
bonus_status_val smallint,
reqd_level smallint,
reqd_class char(1),
location_y smallint,
location_x smallint,
primary key (id)
);";
  $db_conn->query($sql);  
  return $db_conn->get_error();
}


/* Creates a table. */
function create_magic_item($db_conn) {
	$sql = "create table magic_item(
id int not null auto_increment,
name varchar(30) not null,
description varchar(255) not null,
equipped tinyint,
bonus_status_type char(1),
bonus_status_val smallint,
spell_id int,
required_level smallint,
required_class char(1),
duration_remaining smallint,
location_y smallint,
location_x smallint,
state varchar(50),
primary key (id)
);";
  $db_conn->query($sql);  
  return $db_conn->get_error();
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


drop_all_tables($db_conn);


$error_msg = create_all_tables($db_conn);
if (strlen($error_msg) > 0) {
  echo $error_msg . "\n";
}
else {
  echo "All tables created.\n";
}

$res = $db_conn->close();


?>
