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


  function __construct() {
    $this->clear_error();
  }
  
  
  private function set_error($beginning, $error_msg) {
    $this->error .= sprintf("Error - %s %s. ", $beginning, $error_msg);
  }
  
  
  private function clear_error() {
    $this->_error = "";
  }
  
  
  public function get_error() {
    return $this->_error;
  }
  
  
  public function set_from_XML($xml_strg) {
    try {
      $xml = simplexml_load_string($xml_strg);
      $obj = $xml->location;
      $exits = $obj->exits;
      
      $this->short_lbl = $obj->short_lbl;
      $this->area = $obj->area;
      $this->x_val = intval($obj->x_val);
      $this->y_val = intval($obj->y_val);
      $this->description = $obj->description;
      $this->image = $obj->image;
      $this->exits = array("n"=> intval($exits->n),
        "ne"=> intval($exits->ne),
        "e"=> intval($exits->e),
        "se"=> intval($exits->se),
        "s"=> intval($exits->s),
        "sw"=> intval($exits->sw),
        "w"=> intval($exits->w),
        "nw"=> intval($exits->nw),
        "up"=> intval($exits->up),
        "down"=> intval($exits->down) 
      );
      $this->storey_val = intval($obj->storey_val);
      $this->visited = intval($obj->visited);
    }
    catch (Exception $exc) {
      $this->set_error("set_from_XML message: ", $exc->getMessage());
    }
  }
  
  
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
  
  
  public function move($direction) {
    $conf = "";
    switch ($direction) {
  	case "n":
  	  if ( ($this->y_val < $Y_LIMIT) && ($this->exits["n"] == 1) ) { 
          $this->y_val++;
          $conf .= "You move north. ";
  	  }
  	  else {
  	    $this->set_error("You cannot go north. ", "");
  	  }
      break;
    case "ne":
      if ( ($this->y_val < $Y_LIMIT) && ($this->x_val < $X_LIMIT) && 
        ($this->exits["ne"] == 1) ) {
        $this->y_val++;
        $this->x_val++;
        $conf .= "You move northeast. ";
      }
      else {
        $this->set_error("You cannot go northeast. ", "");
      }
      break;
    case "e":
      break;
    case "se":
      break;
    case "s":
      break;
    case "sw":
      break;
    case "w":
      break;
    case "nw":
      break;
    case "up":
      break;
    case "down":
      break;
    }
    return $conf;
    
  } 
  
}
