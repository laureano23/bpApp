<?php

namespace Mbp\SenchaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
		$authorizationChecker = $this->get('security.authorization_checker');
    	$logged = $authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY');
    	$env = $this->container->get('kernel')->getEnvironment();
    	
		if($env == 'dev' && $logged){
			return $this->render('MbpSenchaBundle:MetApp:index.html.twig');
		}

		if($env = 'prod' && $logged){
			return $this->render('MbpSenchaBundle:MetApp:indexBuild.html.twig');
		}

		return $this->render('MbpWebBundle:newFront:index.html.twig');
        
    }
	
	public function manualIndexAction()
    {
		return $this->render('MbpSenchaBundle:MetApp:manualIndex.html.php');
    }
}
   