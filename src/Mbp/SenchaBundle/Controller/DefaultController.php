<?php

namespace Mbp\SenchaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$env = $this->container->get('kernel')->getEnvironment();
		if($env == 'dev'){
			return $this->render('MbpSenchaBundle:MetApp:index.html.twig');
		}else{
			return $this->render('MbpSenchaBundle:MetApp:indexBuild.html.twig');
		}
        
    }
	
	public function manualIndexAction()
    {
		return $this->render('MbpSenchaBundle:MetApp:manualIndex.html.php');
    }
}
