<?php

namespace Mbp\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/test/dbfRead")
     */
    public function dbfRead()
    {
    	print_r(md5(uniqid()));		  
    }
}
