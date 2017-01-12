<?php

namespace Mbp\ClientesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Mbp\ClientesBundle\Entity\Cliente;
use Mbp\ClientesBundle\Entity\ClienteRepository;

class DashboardController extends Controller
{   
	/**
     * @Route("/index", name="indexCliente", options={"expose"=true})
     */ 
	public function indexAction()
	{
		//RECIBO EL DATO DEL CLIENTE DEL LOGIN
		$clienteId = 1714;
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository("MbpClientesBundle:Cliente");

		$cliente = $repo->find($clienteId);

		return $this->render("MbpClientesBundle:Default:index.html.twig", array("cliente" => $cliente->getRsocial()));
	}

	/**
     * @Route("/verPedidos", name="verPedidos", options={"expose"=true})
     */ 
	public function verPedidosAction()
	{
		//RECIBO EL DATO DEL CLIENTE DEL LOGIN
		$clienteId = 1714;
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository("MbpProduccionBundle:PedidoClientes");

		$pedidos = $repo->createQueryBuilder("p")
			->select("")
			->where("p.cliente = :cliente")
			->setParameter("cliente", $clienteId)
			->getQuery()
			->getArrayResult();


		return $this->render("MbpClientesBundle:Default:verPedidos.html.twig", array("pedidos" =>array(
			"rows"=>$pedidos,
			"current"=>1,
			"rowCount"=>10)));
	}
}