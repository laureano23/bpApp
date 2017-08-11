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
     * @Route("/ingresarUsuario", name="ingresarUsuario")
     * @Template() 
     * @Cache(smaxage="604800")
     */
    public function ingresarUsuarioAction()
    {
        $req = $this->getRequest();
        $nombre = $req->request->get('nombre');
        $email = $req->request->get('email');
        $empresa = $req->request->get('empresa');        

        if($nombre){
            try{
                $message = \Swift_Message::newInstance();
                $message->setSubject("Nuevo usuario:");
                $message->setFrom($email);
                $message->setTo("laureano@metalurgicabp.com.ar");
                $message->setBody("
                    <strong>Nombre y Apellido: </strong>".$nombre."<br>
                    <strong>Email: </strong>".$email."<br>
                    <strong>Empresa: </strong>".$empresa."<br>", "text/html");

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

         return $this->render('MbpWebBundle:Default:nuevoUsuario.html.twig');
    }

    /**
     * @Route("/nuevoUsuario", name="nuevoUsuario")
     * @Template() 
     * @Cache(smaxage="604800")
     */
    public function nuevoUsuarioAction()
    {
        return $this->render('MbpWebBundle:Default:nuevoUsuario.html.twig');
    }

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
		
		mail($to, $subject, $message);
    	/*CAPTCHA*/
    	$secret = "6Ld-NSoUAAAAACPvwHWgUIgiBam6-_FJzkmFQAMD";
    	$response = null;
 
		try{// check secret key
		$reCaptcha = new ReCaptcha($secret);
			if ($req->request->get("g-recaptcha-response")) {
			    $response = $reCaptcha->verifyResponse(
			        $req->getClientIp(),
			        $req->request->get("g-recaptcha-response")
			    );
			}
			
			
	    	if($req->request->get("g-recaptcha-response") != "" && !$response->success){
	    		throw new \Exception('Error Captcha', 99);
				
	    	}
	        
	        $nombre = $req->request->get('nombre');
	        $apellido = $req->request->get('apellido');
	        $email = $req->request->get('email');
	        $telefono = $req->request->get('telefono');
	        $empresa = $req->request->get('empresa');        
	        $asunto = $req->request->get('asunto');
	        $consulta = $req->request->get('consulta');
	
	        if($nombre){
            
                $message = \Swift_Message::newInstance();
                $message->setSubject("Consulta web: ".$asunto);
                $message->setFrom('info@metalurgicabp.com.ar');
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
                
               } 
            }catch(\Exception $e){
            	$req->getSession()
                    ->getFlashBag()
                    ->add('statusFail', '');
                    
					
            	if($e->getCode() == 99){
            		$req->getSession()
	                    ->getFlashBag()
	                    ->add('fail', 'Error al validar el Captcha!'); 
            	}                

                $req->getSession()
	                    ->getFlashBag()
	                    ->add('fail', 'Error, por favor intente nuevamente en unos minutos'); 
        
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
     * @Route("/novedades/{page}", name="novedades", defaults={"page": 1})
     * @Cache(smaxage="604800")
     * @Template()
     */
    public function novedadesAction($page)
    {
    	$em = $this->getDoctrine()->getManager('web');
        $repo = $em->getRepository('MbpWebBundle:Novedades');
		$maxResult = 3;
		
		$firstResult=0;
		
		if(isset($page) && $page == 2){
			$firstResult = $maxResult;
		}
		
		if(isset($page) && $page > 2){
			$firstResult = $maxResult * $page;
		}
		
		$query = $repo->createQueryBuilder('n')
			->select('')
			->setFirstResult($firstResult)
			->setMaxResults($maxResult)			
			->orderBy('n.id', 'DESC')
			->getQuery()
			->getResult();
			
			
		$statusNext = "next";
		$statusPrevious = "previous";
		if(count($query) < 3){
			$statusNext = "next disabled";
		}
		
		if($page <= 1){
			$statusPrevious = "previous disabled";
		}
		
		
        return $this->render('MbpWebBundle:Default:novedades.html.twig', array(
        	'page' => $page,
        	'items' => $query,
			'next' => $statusNext,
			'previous' => $statusPrevious
			));
    }
	
}
 