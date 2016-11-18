<?php

namespace Mbp\ClientesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ClientesBundle\Entity\Cliente;
use Mbp\ClientesBundle\Entity\ClienteRepository;

class DefaultController extends Controller
{   
    public function clientesearchAction()
    {
        $em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		
		$param = $request->query->get('query');
		$rep = $em->getRepository('MbpClientesBundle:Cliente')->searchCliente($param);
		return new Response();
    }
    
	 
    public function newClienteAction()
    {
        $em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$repoLocalidad = $em->getRepository('MbpPersonalBundle:Localidades');
		$repoProvincia = $em->getRepository('MbpPersonalBundle:Provincias');
		$repoClientes = $em->getRepository('MbpClientesBundle:Cliente');
		
		$cliente = 0;
		if($request->request->get('id') > 0){
			$cliente = $repoClientes->find($request->request->get('id'));
		}else{
			$cliente = new Cliente();	
		}
		$estadoCuenta = $request->request->get('cuentaCerrada') == 'on' ? 1 : 0;
				
		$cliente->setRsocial($request->request->get('rsocial'));
		$cliente->setDenominacion($request->request->get('denominacion'));
		$cliente->setDireccion($request->request->get('direccion'));
		$cliente->setLocalidad($repoLocalidad->find($request->request->get('localidad')));
		$cliente->setProvincia($repoProvincia->find($request->request->get('provincia')));
		$cliente->setEmail($request->request->get('email'));
		$cliente->setCuit($request->request->get('cuit'));
		$cliente->setCPostal($request->request->get('cPostal'));
		$cliente->setIva($request->request->get('iva'));
		$cliente->setTelefono1($request->request->get('telefono1'));
		$cliente->setContacto1($request->request->get('contacto1'));
		$cliente->setTelefono2($request->request->get('telefono2'));
		$cliente->setContacto2($request->request->get('contacto2'));
		$cliente->setTelefono3($request->request->get('telefono3'));
		$cliente->setContacto3($request->request->get('contacto3'));
		$cliente->setCondVenta($request->request->get('condVenta'));
		$cliente->setVencimientoFc($request->request->get('vencimientoFc'));
		$cliente->setNetoPercepcion($request->request->get('netoPercepcion'));
		$cliente->setPorcentajePercepcion($request->request->get('porcentajePercepcion'));
		$cliente->setCuentaCerrada($estadoCuenta);
		
		$em->persist($cliente);
		$em->flush();
		
		echo json_encode(array(
			'success' => true,
			'id' => $cliente->getId()
		));
        return new Response();
    }

	public function eliminarAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$repo = $em->getRepository('MbpClientesBundle:Cliente');
		
		$id = $request->request->get('id');
		$cliente = $repo->find($id);
		
		$em->remove($cliente);
		$em->flush();
		
		echo json_encode(array(
			'success' => true
		));
		
		return new Response();
	}
}


















