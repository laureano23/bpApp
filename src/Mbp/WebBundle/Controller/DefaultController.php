<?php

namespace Mbp\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Mbp\WebBundle\Entity\SubCategoria;



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
        $em = $this->getDoctrine()->getManager('web');
        $repo = $em->getRepository('MbpWebBundle:SubCategoria');

        $sub = $repo->find(1);
        $sub->setDescripcion("Engine 220V");
        $sub->setTranslatableLocale("en");

        $em->persist($sub);
        $em->flush();

        return $this->render('MbpWebBundle:Default:index.html.twig');
    }

    /**
     * @Route("/contacto", name="contacto")
     * @Cache(smaxage="604800")
     */
    public function contactoAction()
    {
        $req = $this->getRequest();
        $nombre = $req->request->get('nombre');
        $apellido = $req->request->get('apellido');
        $email = $req->request->get('email');
        $telefono = $req->request->get('telefono');
        $empresa = $req->request->get('empresa');        
        $asunto = $req->request->get('asunto');
        $consulta = $req->request->get('consulta');

        if($nombre){
            try{
                $message = \Swift_Message::newInstance();
                $message->setSubject("Consulta web: ".$asunto);
                $message->setFrom($email);
                $message->setTo("info@metalurgicabp.com.ar");
                $message->setBody("
                    <strong>Nombre y Apellido: </strong>".$nombre." ".$apellido."<br>
                    <strong>Email: </strong>".$email."<br>
                    <strong>Teléfono: </strong>".$telefono."<br>
                    <strong>Empresa: </strong>".$empresa."<br>
                    <strong>Consulta: </strong>".$consulta."<br>
                    ", "text/html");

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
     * @Cache(smaxage="604800")
     */
    public function clientesAction()
    {
        return $this->render('MbpWebBundle:Default:clientes.html.twig');
    }

    public function listarCategoriasAction()
    {
    	$em = $this->getDoctrine()->getManager('web');
    	$repo = $em->getRepository('MbpWebBundle:Categorias');
        $locale = $req = $this->getRequest()->getLocale();

    	$items = $repo->listarCategorias($locale);
		
		

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
     * @Route("/calidad", name="calidad")
     * @Template()
     * @Cache(smaxage="604800")
     */
    public function calidadAction()
    {
        return $this->render('MbpWebBundle:Default:calidad.html.twig');
    }

    /**
     * @Route("/historiaEmpresa", name="historiaEmpresa")
     * @Cache(smaxage="604800")
     * @Template()
     */
    public function historiaEmpresaAction()
    {
        return $this->render('MbpWebBundle:Default:historiaEmpresa.html.twig');
    }

    /**
     * @Route("/mision", name="mision")
     * @Cache(smaxage="604800")
     * @Template()
     */
    public function misionAction()
    {
        return $this->render('MbpWebBundle:Default:mision.html.twig');
    }


    /**
     * @Route("/como-llegar", name="comoLlegar")
     * @Template()
     * @Cache(smaxage="604800")
     */
    public function comoLlegarAction()
    {
        return $this->render('MbpWebBundle:Default:comoLlegar.html.twig');
    }


    /**
     * @Route("/novedades", name="novedades")
     * @Cache(smaxage="604800")
     * @Template()
     */
    public function novedadesAction()
    {
        return $this->render('MbpWebBundle:Default:novedades.html.twig');
    }
	
}
 