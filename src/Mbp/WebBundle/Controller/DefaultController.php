<?php

namespace Mbp\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Mbp\WebBundle\Entity\SubCategoria;
use Mbp\WebBundle\Clases\ReCaptcha;



use Mbp\WebBundle\Entity\TiposRadiadores;

class DefaultController extends Controller
{
    /**
     * @Route("/index", name="index")
     * @Template() 
     * @Cache(smaxage="604800")
     */
    public function indexAction()
    {
        return $this->render('MbpWebBundle:newFront:index.html.twig');
    }

    /**
     * @Route("/about", name="about")
     * @Template() 
     * @Cache(smaxage="604800")
     */
    public function about()
    {
        return $this->render('MbpWebBundle:newFront:about.html.twig');
    }

    /**
     * @Route("/contacto", name="contacto")
     * @Template() 
     * @Cache(smaxage="604800")
     */
    public function contacto()
    {
        return $this->render('MbpWebBundle:newFront:contact.html.twig');
    }

    /**
     * @Route("/products", name="products")
     * @Template() 
     * @Cache(smaxage="604800")
     */
    public function products()
    {
        return $this->render('MbpWebBundle:newFront:products.html.twig');
    }
}
 