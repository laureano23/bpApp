<?php

namespace Mbp\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Mbp\TestBundle\Entity\Category;
use Mbp\TestBundle\Entity\FormulasTest;

class DefaultController extends Controller
{
    /**
     * @Route("/test/nested")
     */
    public function nested()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repo=$em->getRepository('MbpTestBundle:FormulasTest');
    	$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');

    }
}


