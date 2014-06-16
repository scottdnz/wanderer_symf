<?php

/**
 * This file ...
 * 
 * @author Scott Davies
 * @version 1.0
 * @package
 */
 
 
class Location {


  function __construct() {
  
  }
  
  
  public function set_from_XML($xml_strg) {
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
  
  
  public function get_as_dict() {
       
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
    "storey_val"=> intval($this->storey_val),
    "visited"=> intval($this->visited)
    );
    return $loc;
  }
  
}
