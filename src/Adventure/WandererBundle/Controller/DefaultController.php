<?php

namespace Adventure\WandererBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class DefaultController extends Controller
{

    public function indexAction()
    {
        return $this->render('AdventureWandererBundle:Default:index.html.twig');
    }
    
}
