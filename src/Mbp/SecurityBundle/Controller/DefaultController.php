<?php

namespace Mbp\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;

class DefaultController extends Controller
{
    public function apiLoginAction(Request $request){
        $request = $this->getRequest();
        $response=new Response;
        // Allow all websites
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $username=$request->query->get('user');
        $password=$request->query->get('password');
        // Retrieve the security encoder of symfony
        $factory = $this->get('security.encoder_factory');
        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository("MbpSecurityBundle:Users");

        //buscamos el usuario
        $user = $repo->findOneBy(array('username' => $username));

        // Check if the user exists !
        if(!$user){
            $response->setStatusCode(500); //401 es no autorizado
            return $response->setContent('Username doesnt exists');
        }

        /// Start verification
        $encoder = $factory->getEncoder($user);
        $salt = $user->getSalt();

        if(!$encoder->isPasswordValid($user->getPassword(), $password, $salt)) {
            $response->setStatusCode(500);
            return $response->setContent('Username or Password not valid');
        } 
        /// End Verification

        //CREAMOS EL TOKEN PARA ACCESO VIA API CON 2DO FIREWALL
        $time = time();
        $key = $this->getParameter('secret');


        $token = array(
            'iat' => $time, // Tiempo que inició el token
            'exp' => $time + (60*60), // Tiempo que expirará el token (+1 hora)
            'data' => [ // información del usuario
                'name' => $user
            ]
        );

        $jwtToken = JWT::encode($token, $key);

        //$data = JWT::decode($jwtToken, $key, array('HS256'));
        $user->setApiKey($jwtToken);
        $em->persist($user);
        $em->flush();

        $data = array(
            'token'=>$jwtToken
        );

        

        return $response->setContent(\json_encode($data));
    }

    public function loginAction(Request $request)
    {
        $request = $this->getRequest();
        $session = $request->getSession();		
		

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'MbpSecurityBundle:Default:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            )
        );
    }
	
	public function logoutAction()
    {
    	$sesion="Usted ha cerrado Sesion correctamente";
        return $this->render('MbpSecurity:Default:logout.html.twig', array(
        'mensaje'=>$sesion
        ));
    }
	
	public function sesionAction()
	{
		
		$roles = $this->get('security.context')->getToken()->getUser()->getRoles();
		$role = $roles[0]->getRole(); 
		$name = $this->get('security.context')->getToken()->getUsername();
		
        $resSector = $this->get('security.context')->getToken()->getUser()->getSectorId();
        		
		$sector="";
		if(!empty($resSector)){
			$sector = $resSector->getDescripcion();
		}
        
        
		
		
		$data = array(
			'nombre' => $name,
			'role' => $role,
            'sector' => $sector,
            'env' => $this->container->get('kernel')->getEnvironment()
		);
		
		echo json_encode($data);
		
		return new Response();
	}
}





