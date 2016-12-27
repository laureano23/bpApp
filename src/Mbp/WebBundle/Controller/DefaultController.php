<?php

namespace Mbp\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Mbp\WebBundle\Entity\Categorias;

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
        $req = $this->getRequest();
        $nombre = $req->request->get('nombre');

        if($nombre){
            try{
                $message = \Swift_Message::newInstance();
                $message->setSubject("hola");
                $message->setFrom("sasa@metalurgicabp.com.ar");
                $message->setTo("laureano@metalurgicabp.com.ar");
                $message->setBody("Prueba");

                $mailer = $this->get('mailer');
                $mailer->send($message);
                

                $req->getSession()
                    ->getFlashBag()
                    ->add('success', 'En breve responderemos su mensaje');

                $req->getSession()
                    ->getFlashBag()
                    ->add('statusOk', '');
                
                
            }catch(\Exception $e){
                $req->getSession()
                    ->getFlashBag()
                    ->add('statusFail', '');

                $req->getSession()
                    ->getFlashBag()
                    ->add('fail', 'Error en el envío, intente más tarde!'); 
            }
           
        }

         return $this->render('MbpWebBundle:Default:contacto.html.twig');
        
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
        $em->clear();
    	$repo = $em->getRepository('MbpWebBundle:Categorias');
    	$items = $repo->findAll();		


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


    /**
     * @Route("/como-llegar", name="comoLlegar")
     * @Template()
     */
    public function comoLlegarAction()
    {
        return $this->render('MbpWebBundle:Default:comoLlegar.html.twig');
    }


    /**
     * @Route("/novedades", name="novedades")
     * @Template()
     */
    public function novedadesAction()
    {
        return $this->render('MbpWebBundle:Default:novedades.html.twig');
    }
	
}
 