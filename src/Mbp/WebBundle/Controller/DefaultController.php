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
        $em = $this->getDoctrine()->getManager('web');
    	$repo = $em->getRepository('MbpWebBundle:Categorias');
    	$req = $this->getRequest();
    	$categoria = $repo->find($req->query->get('cat')); 


        return $this->render('MbpWebBundle:newFront:products.html.twig', array('categorias' => $categoria));
    }

    public function listarCategoriasAsideAction()
    {
    	$em = $this->getDoctrine()->getManager('web');
        $em->clear();
    	$repo = $em->getRepository('MbpWebBundle:Categorias');
    	$items = $repo->findAll();		


    	return $this->render('MbpWebBundle:Default:lista_categorias_aside.html.twig', array('items' => $items));
    }
}
 