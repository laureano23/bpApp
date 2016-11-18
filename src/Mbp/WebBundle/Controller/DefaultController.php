<?php

namespace Mbp\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/index", name="index")
     * @Template() 
     */
    public function indexAction()
    {
    	
        return $this->render('MbpWebBundle:Default:index.html.twig');
    }

    /**
     * @Route("/contacto", name="contacto")
     * @Template()
     */
    public function contactoAction()
    {
        return $this->render('MbpWebBundle:Default:contacto.html.twig');
    }

     /**
     * @Route("/productos", name="productos")
     * @Template()
     */
    public function productosAction()
    {
    	$em = $this->getDoctrine()->getManager('web');
    	$repo = $em->getRepository('MbpWebBundle:Categorias');
    	$req = $this->getRequest();
    	$categoria = $repo->find($req->query->get('cat'));

    	//$productos = $categoria->getArticulos();
		$sub = $categoria->getSubCategoria();
       // \Doctrine\Common\Util\Debug::dump($sub); 
 

        return $this->render('MbpWebBundle:Default:productos.html.twig', array('categorias' => $categoria));
    }

     /**
     * @Route("/clientes", name="clientes")
     * @Template()
     */
    public function clientesAction()
    {
        return $this->render('MbpWebBundle:Default:clientes.html.twig');
    }

    public function listarCategoriasAction()
    {
    	$em = $this->getDoctrine()->getManager('web');
    	$repo = $em->getRepository('MbpWebBundle:Categorias');

    	$items = $repo->listarCategorias();
		
		

    	return $this->render('MbpWebBundle:Default:lista_categorias.html.twig', array('items' => $items));
    }

    public function listarCategoriasAsideAction()
    {
    	$em = $this->getDoctrine()->getManager('web');
    	$repo = $em->getRepository('MbpWebBundle:Categorias');

    	$items = $repo->listarCategorias();		
		 

    	return $this->render('MbpWebBundle:Default:lista_categorias_aside.html.twig', array('items' => $items));
    }

    /**
     * @Route("/prueba", name="prueba")
     * @Template()
     */
    public function pruebaAction()
    {
        return $this->render('MbpWebBundle:Default:prueba.html.twig');
    }

    /**
     * @Route("/calidad", name="calidad")
     * @Template()
     */
    public function calidadAction()
    {
        return $this->render('MbpWebBundle:Default:calidad.html.twig');
    }

    /**
     * @Route("/historiaEmpresa", name="historiaEmpresa")
     * @Template()
     */
    public function historiaEmpresaAction()
    {
        return $this->render('MbpWebBundle:Default:historiaEmpresa.html.twig');
    }

    /**
     * @Route("/mision", name="mision")
     * @Template()
     */
    public function misionAction()
    {
        return $this->render('MbpWebBundle:Default:mision.html.twig');
    }
}
 