<?php

/**
 * This file ...
 * 
 * @author Scott Davies
 * @version 1.0
 * @package
 */


require_once("../central_config.php");
 
 
class Location {
  
  private static $id;
  private static $short_lbl;
  private static $area;
  private static $x_val;
  private static $y_val;
  private static $description;
  private static $image;
  private static $exits;
  private static $storey_val;
  private static $visited;
  
  private static $full_val;


  function __construct() {
    $this->clear_error();
    $this->exits = array("n"=> 0,
      "ne"=> 0,
      "e"=> 0,
      "se"=> 0,
      "s"=> 0,
      "sw"=> 0,
      "w"=> 0,
      "nw"=> 0,
      "up"=> 0,
      "down");
    $this->full_val  = array("n"=> "north", 
    "ne"=> "northeast",
    "e"=> "east",
    "se"=> "southeast",
    "s"=> "south",
    "sw"=> "southwest",
    "w"=> "west",
    "nw"=> "northwest",
    "up"=> "up",
    "down"=> "down");    
  }
  
  
  /**
  * 
  */
  private function set_error($beginning, $error_msg) {
    $this->_error .= sprintf("Error - Type: %s, %s", $beginning, $error_msg);
  }
  
  
  /**
  * 
  */
  private function clear_error() {
    $this->_error = "";
  }
  
  
  /**
  * 
  */
  public function get_error() {
    return $this->_error;
  }
  
  
  /**
  * 
  */
  public function set_from_XML($xml_strg) {
    try {
      $xml = simplexml_load_string($xml_strg);
      $obj = $xml->location;
      $exits = $obj->exits;
      
      $this->id = $obj->id;
      $this->short_lbl = $obj->short_lbl;
      $this->area = $obj->area;
      $this->x_val = intval($obj->x_val);
      $this->y_val = intval($obj->y_val);
      $this->description = $obj->description;
      $this->image = $obj->image;      
           
      $this->exits["n"] = intval($exits->n);
      $this->exits["ne"] = intval($exits->ne);
      $this->exits["e"] = intval($exits->e);
      $this->exits["se"] = intval($exits->se);
      $this->exits["s"] = intval($exits->s);
      $this->exits["sw"] = intval($exits->sw);
      $this->exits["w"] = intval($exits->w);
      $this->exits["nw"] = intval($exits->nw);
      $this->exits["up"] = intval($exits->up);
      $this->exits["down"] = intval($exits->down);
      $this->storey_val = intval($obj->storey_val);
      $this->visited = intval($obj->visited);
    }
    catch (Exception $exc) {
      $this->set_error("set_from_XML message: ", $exc->getMessage());
    }
  }
  
  
  public function set_from_db_record($rec) {
    $this->id = $rec["id"];
    $this->short_lbl = $rec["short_lbl"];
    $this->area = $rec["area"];
    $this->x_val = intval($rec["x_val"]);
    $this->y_val = intval($rec["y_val"]);
    $this->description = $rec["description"];
    $this->image = $rec["image"];
    
    foreach ($this->full_val as $direction=> $val) {
      $this->exit[$direction] = intval($rec["exit_" . $direction]);
    }      
    
    $this->storey_val = intval($rec["storey_val"]);
    $this->visited = intval($rec["visited"]);
  }
  
  
  /**
  * 
  */
  public function get_as_XML() {
    $xml_obj = new SimpleXMLElement("<locations />");  
    $loc_elem = $xml_obj->addChild("location");
    
    $elem = $loc_elem->addChild("id", $this->id);
    $elem = $loc_elem->addChild("short_lbl", $this->short_lbl);
    $elem = $loc_elem->addChild("area", $this->area);
    $elem = $loc_elem->addChild("description", $this->description);
    $elem = $loc_elem->addChild("image", $this->image);
    $elem = $loc_elem->addChild("x_val", $this->x_val);
    $elem = $loc_elem->addChild("y_val", $this->y_val);
    $elem = $loc_elem->addChild("storey_val", $this->storey_val);
    $elem = $loc_elem->addChild("visited", $this->visited);
    
    $elem = $loc_elem->addChild("exits");
    $exits = $this->exits;
    $elem->n = $exits["n"];
    $elem->ne = $exits["ne"];
    $elem->e = $exits["e"];
    $elem->se = $exits["se"];
    $elem->s = $exits["s"];
    $elem->sw = $exits["sw"];
    $elem->w = $exits["w"];
    $elem->nw = $exits["nw"];
    $elem->up = $exits["up"];
    $elem->down = $exits["down"];
             
    return $xml_obj->asXML();
  }
  
  
  /**
  * 
  */
  public function get_as_dict() {
    try {   
      $loc = array("short_lbl"=> $this->short_lbl,
      "area"=> $this->area,
      "x_val"=> $this->x_val,
      "y_val"=> $this->y_val,
      "description"=> $this->description,
      "image"=> $this->image,
      "exits"=> array("n"=> $this->exits["n"],
        "ne"=> $this->exits["ne"],
        "e"=> $this->exits["se"],
        "se"=> $this->exits["se"],
        "s"=> $this->exits["s"],
        "sw"=> $this->exits["sw"],
        "w"=> $this->exits["w"],
        "nw"=> $this->exits["nw"],
        "up"=> $this->exits["up"],
        "down"=> $this->exits["down"]
      ),
      "storey_val"=> $this->storey_val,
      "visited"=> $this->visited
      );
    }
    catch (Exception $exc) {
      $this->set_error("get_as_dict message: ", $exc->getMessage());
      $loc = array();
    }
    return $loc;
  }
  
  
  /**
  * 
  */
  public function try_move($direction) {
    global $Y_LIMIT;
    global $X_LIMIT;
    $conf = "";
    $this->clear_error();
//    var_dump($this->exits);
    switch ($direction) {
  	case "n":
  	  if ( ($this->y_val >0) && ($this->exits["n"] == 1) ) { 
        $conf .= "You move north. ";
  	  }
  	  else {
  	    $this->set_error("Moving", "You cannot go north. ");
  	  }
      break;
    case "ne":
      if ( ($this->y_val > 0 ) && ($this->x_val < $X_LIMIT) && 
        ($this->exits["ne"] == 1) ) {
        $conf .= "You move northeast. ";
      }
      else {
        $this->set_error("Moving", "You cannot go northeast. ");
      }
      break;
    case "e":
      if ( ($this->x_val < $X_LIMIT) && ($this->exits["e"] == 1) ) { 
        $conf .= "You move east. ";
  	  }
  	  else {
  	    $this->set_error("Moving", "You cannot go east. ");
  	  }
      break;
    case "se":
       if ( ($this->y_val >= 0) && ($this->x_val < $X_LIMIT) && 
        ($this->exits["se"] == 1) ) {
        $conf .= "You move southeast. ";
      }
      else {
        $this->set_error("Moving", "You cannot go southeast. ");
      }  
      break;
    case "s":
      if ( ($this->y_val < $Y_LIMIT) && ($this->exits["s"] == 1) ) {
        $conf .= "You move south. ";
      }
      else {
        $this->set_error("Moving", "You cannot go south. ");
      }  
      break;
    case "sw":
      if ( ($this->y_val < $Y_LIMIT) && ($this->x_val > 0) && ($this->exits["sw"] == 1) ) {
        $conf .= "You move southwest. ";
      }
      else {
        $this->set_error("Moving", "You cannot go southwest. ");
      }  
      break;
    case "w":
      if ( ($this->x_val > 0) && ($this->exits["w"] == 1) ) {
       $conf .= "You move west. ";
      }
      else {
        $this->set_error("Moving", "You cannot go west. ");
      }  
      break;
    case "nw":
      if ( ($this->x_val > 0) && ($this->y_val > 0) && ($this->exits["nw"] == 1) ) {
       $conf .= "You move northwest. ";
      }
      else {
        $this->set_error("Moving", "You cannot go northwest. ");
      }  
      break;
    case "up":
      if ($this->exits["up"] == 1) {
        $conf .= "You climb up. ";
      }
      else {
        $this->set_error("Moving", "You cannot climb up. ");
      }  
      break;
    case "down":
      if ($this->exits["down"] == 1) {
        $conf .= "You climb down. ";
      }
      else {
        $this->set_error("Moving", "You cannot climb down. ");
      }  
      break;
    default:
      $this->set_error("Moving", "You cannot move in that direction. ");
    }
    return $conf;
  } 
  
  
  private function get_exits_msg() { 
    $possible_exits = array();  
    foreach ($this->exits as $dir=> $val) {
      //echo $dir . ": " . $val . "\n";
      if ($val == 1) {
        $possible_exits[] = $this->full_val[$dir];
      }
    }
    $last_index = sizeof($possible_exits) - 1;
    if (sizeof($possible_exits) > 1) {
      $all_except_last = array_slice($possible_exits, 0, -1);
      $first_part = implode(", ", $all_except_last);
      $last_part = " and " . $possible_exits[$last_index];
      $exit_msg = $first_part . $last_part;
    }
    else {
      $exit_msg = implode(", ", $possible_exits);
    } 
    return sprintf("You can go %s. ", $exit_msg);
  }
  
  
  /**
  * For the display window. Returns XML
  */
  public function get_display() {
    $xml_obj = new SimpleXMLElement("<descriptions />");  
    $descr_elem = $xml_obj->addChild("description");
    $elem = $descr_elem->addChild("id", $this->id);
    $elem = $descr_elem->addChild("short_lbl", $this->short_lbl);
    $elem = $descr_elem->addChild("area", $this->area);
    $elem = $descr_elem->addChild("description", $this->description);
    $elem = $descr_elem->addChild("image", $this->image);
    $elem = $descr_elem->addChild("exits_msg", $this->get_exits_msg());
    return $xml_obj->asXML();
  } 
  

  /**
  * 
  */  
  public function unlock_exit($direction) {
    $res = array("conf"=> "", "err"=> "");
    // If unlockable and have key
    //
    if ($this->exits[$direction] == 2) {    
      $this->exits[$direction] = 1;
      $res["conf"] = sprintf("You have unlocked the exit to the %s. ", $this->full_val($direction));
    }
    else {
      $res["err"] = sprintf("You cannot unlock the exit to the %s. ", $this->full_val($direction));
    }
    return $res;
  }
  

  /**
  * 
  */    
  public function set_visited() {
    $this->visited = 1;
  }
 
  
}
