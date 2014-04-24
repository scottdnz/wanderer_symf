<?php

namespace Adventure\WandererBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//require_once __DIR__ . "/../Lib/Init/location_maker.php";

class ActivityController extends Controller {

  public function mainAction() {
    return $this->render('AdventureWandererBundle:Activity:main.html.twig');
  }

  public function inventoryAction() {
    return $this->render('AdventureWandererBundle:Activity:inventory.html.twig');
  }
  
}
