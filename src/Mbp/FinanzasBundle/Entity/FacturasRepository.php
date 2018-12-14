<?php
namespace Mbp\FinanzasBundle\Entity;
use Mbp\FinanzasBundle\Entity\Facturas;
use Mbp\FinanzasBundle\Entity\FacturaDetalle;

/**
 * FacturasRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FacturasRepository extends \Doctrine\ORM\EntityRepository
{
	public function crearComprobante($objFC){
		$em = $this->getEntityManager();
		$comprobante=new Facturas;
		$repoTipoIVA=$em->getRepository('MbpFinanzasBundle:PosicionIVA');
		$repoCCClientes=$em->getRepository('MbpFinanzasBundle:CCClientes');
		$repoTipoCbte=$em->getRepository('MbpFinanzasBundle:TipoComprobante');
		$repoCliente=$em->getRepository('MbpClientesBundle:Cliente');
		$repoArt=$em->getRepository('MbpArticulosBundle:Articulos');
		$repoRemitosDetalle=$em->getRepository('MbpArticulosBundle:RemitosClientesDetalles');

		
		$comprobante->setPtoVta($objFC->getPuntoVenta());
		$tipoIVA=$repoTipoIVA->findOneByEsResponsableInscripto(true);
		if(empty($tipoIVA)) throw new \Exception("No existe el tipo de IVA responsable inscripto", 1);
		$comprobante->setTipoIva($tipoIVA);
		$comprobante->setDigitoVerificador($objFC->getDigitoVerificador());
		$comprobante->setTipoCambioRefFac($objFC->getRefTipoCambio());
		$cc=$repoCCClientes->crearMovimientoCC($objFC, $comprobante);
		$comprobante->setCcId($cc);

		$tipoCbte;
		switch (true) {
			case $objFC->sosFacturaA():
				$tipoCbte=$repoTipoCbte->findOneBy(array('esFactura'=>true, 'subTipoA'=>true));
				if(empty($tipoCbte)) throw new \Exception("No existe el tipo de comprobante Factura A", 1);
				break;
			case $objFC->sosNotaCreditoA();
				$tipoCbte=$repoTipoCbte->findOneBy(array('esNotaCredito'=>true, 'subTipoA'=>true));
				if(empty($tipoCbte)) throw new \Exception("No existe el tipo de comprobante Nota de Crédito A", 1);

				//Al ser una NC puede tener facturas asociadas que esta anulando				
				foreach ($objFC->getFacturasAsociadas() as $fc) {
					$comprobante->addFacturasAsociada($this->find($fc['id']));
				}
				break;
			case $objFC->sosNotaDebitoA();
				$tipoCbte=$repoTipoCbte->findOneBy(array('esNotaDebito'=>true, 'subTipoA'=>true));
				if(empty($tipoCbte)) throw new \Exception("No existe el tipo de comprobante Nota de débito A", 1);
				break;
			case $objFC->sosPresupuesto();
				$tipoCbte=$repoTipoCbte->findOneBy(array('esNegro'=>true));
				if(empty($tipoCbte)) throw new \Exception("No existe el tipo de comprobante Presupuesto", 1);
				break;
			default:
				# code...
				break;
		}
		
		
		
		
		$comprobante->setTipoId($tipoCbte);
		$comprobante->setTipoCambio($objFC->getCotizacionMoneda());
		if($objFC->getMoneda()=='DOL'){
			$comprobante->setMoneda(1);
		}else{
			$comprobante->setMoneda(0);
		}
		$comprobante->setDepartamento($objFC->getPartido());
		$comprobante->setPorcentajeIIBB($objFC->getAlicuotaPercepcion());
		$comprobante->setTotal($objFC->getTotalComprobante());
		$comprobante->setFecha(\DateTime::createFromFormat('Ymd', $objFC->getFechaEmision()));
		$comprobante->setVencimiento($objFC->getFechaVencimiento());
		$comprobante->setFcNro($objFC->getNumero());
		$comprobante->setConcepto($objFC->getDescripcionCbte());

		$cliente=$repoCliente->find($objFC->getCliente());
		if(empty($cliente)) throw new \Exception("No se encontró el cliente", 1);
		$comprobante->setClienteId($cliente);

		$comprobante->setCae($objFC->getCAE());		
		$comprobante->setVtoCae($objFC->getVencimientoCAE());
		$comprobante->setDtoTotal($objFC->getDescuento());
		$comprobante->setPerIIBB($objFC->getMontoPercepcion());
		$comprobante->setIva21($objFC->getTotalIVA());
		$comprobante->setRSocial($cliente->getRsocial());
		$comprobante->setDomicilio($cliente->getDireccion());
		$comprobante->setCuit($cliente->getCuit());

		if($objFC->sosPresupuesto()){
			$comprobante->setEsPresupuesto(true);
		}

		$posicion=$cliente->getIva()->getPosicion();
		if(empty($posicion)) throw new \Exception("El cliente no tiene una posición definida frente al IVA", 1);
		$comprobante->setIvaCond($posicion);

		$comprobante->setCondVta($objFC->getCondicionVenta());

		foreach ($objFC->getDetallesVenta() as $d) {
			$art=$repoArt->findOneByCodigo($d->codigo);
			$detalleFc=new FacturaDetalle();
			$detalleFc->setArticuloId($art);
			$detalleFc->setCantidad($d->cantidad);
			//SI EL CODIGO ES ZZZ SETEO LA DESCRIPCION ENVIADA POR EL CLIENTE
			if($art->getCodigo()=="ZZZ"){
				$detalleFc->setDescripcion($d->descripcion);
			}else{
				$detalleFc->setDescripcion($art->getDescripcion());
			}
			
			$detalleFc->setFacturaId($comprobante);
			$detalleFc->setIvaGrabado($d->ivaGrabado);
			$detalleFc->setPrecio($d->precio);
			$rem=$repoRemitosDetalle->find($d->remitoNum);
			$detalleFc->setRemitoDetalleId($rem);
			//marcamos el remito como entregado
			if($rem != NULL){
				$rem->setFacturado(true);
			}

			$comprobante->addFacturaDetalleId($detalleFc);

			
		}

		$em->persist($comprobante);
		$em->persist($cc);
		$em->flush();

		return $comprobante->getId();
	}

	public function citiVentasCbtes(\DateTime $desde, \DateTime $hasta)
	{
		$em = $this->getEntityManager();
		$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');

		//si no hay IVA liquidado en la factura el importe neto es 0 y se completa el campo importe excento
		//el doc del cliente esta fijado en 80 que es CUIT, si se modifica hay q cambiar la consulta

		$desdeSql=$desde->format('Y-m-d');
		$hastaSql=$desde->format('Y-m-d');

		$res=$repoFacturas->createQueryBuilder('f')
				->select(" 
                    DATE_FORMAT(f.fecha, '%Y%m%d') AS fechaEmision,
                    LPAD(tipo.codigoAfip, 3, '0') as tipoCbteAfip,
                    LPAD(f.ptoVta, 5, '0') AS ptoVta,
                    LPAD(f.fcNro, 20, '0') AS fcNroDesde,
					LPAD(f.fcNro, 20, '0') AS fcNroHasta,
					LPAD(80, 2, '0') AS codDocumento,
                    CASE WHEN posicionIVA.esResponsableInscripto = true 
                        THEN LPAD(cliente.cuit, 20, '0')
                        ELSE '' END AS numIdentificacion,
					RPAD(REPLACE(f.rSocial, '°', ' '), 30, ' ') AS nombreComprador,
					CASE WHEN tipo.esNotaCredito=true
						THEN LPAD(LPAD(REPLACE(ROUND((f.total*f.tipoCambio), 2), '.', ''), 14, 0), 15, '0')
						ELSE LPAD(REPLACE(ROUND((f.total*f.tipoCambio), 2), '.', ''), 15, 0) END as montoTotal,
					CASE WHEN f.iva21 > 0 AND tipo.esNotaCredito=true  
						THEN LPAD(0, 15, '0') 
						WHEN f.iva21 = 0 AND tipo.esNotaCredito=true
						THEN LPAD(LPAD(REPLACE(ROUND(((f.total-f.iva21-f.perIIBB)*f.tipoCambio), 2), '.', ''), 14, '0'), 15, '0') 
						WHEN f.iva21 > 0 AND tipo.esNotaCredito=false
						THEN LPAD(0, 15, '0') 
						ELSE LPAD(REPLACE(ROUND(((f.total-f.iva21-f.perIIBB) * f.tipoCambio), 2), '.', ''), 15, '0') 
						END AS montoNoGrabado,	
					LPAD(0, 15, '0') AS percepcionNoCategorizados,   
					LPAD(0, 15, '0') AS montoExcento,  					
					LPAD(0, 15, '0') AS pagoCuentaImpNacionales,   
					CASE WHEN tipo.esNotaCredito=true
						THEN LPAD(LPAD(REPLACE(ROUND((f.perIIBB*f.tipoCambio), 2), '.', ''), 14, '0'), 15, '0')
						ELSE LPAD(REPLACE(ROUND((f.perIIBB * f.tipoCambio), 2), '.', ''), 15, '0')
						AS perIIBB,
					LPAD(0, 15, '0') AS impPercepcionImpMunicipales,   
                    LPAD(0, 15, '0') AS impInternos,   
                    CASE WHEN f.moneda = 1 
                        THEN 'DOL'
                        ELSE 'PES' END AS moneda,
					LPAD(FLOOR(f.tipoCambio), 4, '0') AS tipoCambio,
					RPAD(REPLACE((f.tipoCambio - FLOOR(f.tipoCambio)), '0.', ''), 6, '0') AS tipoCambioDecimal,
                    LPAD(1, 1, '0') AS cantAlicuotasIVA,
					CASE WHEN f.iva21 = 0   
                        THEN 'N'
						ELSE ' ' END AS codigoDeOperacion,
                    LPAD(0, 15, '0') AS otrosTributos,
                    DATE_FORMAT(f.vencimiento, '%Y%m%d') AS fechaVencimiento
                    ")
                ->join('f.tipoId', 'tipo')                
                ->join('f.clienteId', 'cliente')
                ->join('cliente.iva', 'posicionIVA')
				->where('tipo.esBalance = 0')
				->andWhere('tipo.esNegro = 0')
                ->andWhere('f.fecha BETWEEN :desde AND :hasta')
                ->setParameter('desde', $desde->format('Y-m-d'))
				->setParameter('hasta', $hasta->format('Y-m-d'))
				->getQuery()
				->getArrayResult();

				
		return $res;

	}

	public function citiVentasAlicuota(\DateTime $desde, \DateTime $hasta)
	{
		$em = $this->getEntityManager();
		$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');

		$desdeSql=$desde->format('Y-m-d');
		$hastaSql=$desde->format('Y-m-d');

		$res=$repoFacturas->createQueryBuilder('f')
                ->select("
                    LPAD(tipo.codigoAfip, 3, '0') as tipoCbteAfip,
                    LPAD(f.ptoVta, 5, '0') AS ptoVta,
					LPAD(f.fcNro, 20, '0') AS fcNro,
					CASE WHEN f.iva21 = 0 AND tipo.esNotaCredito=true
						THEN LPAD(0, 15, '0') 
						WHEN f.iva21 > 0 AND tipo.esNotaCredito=true
						THEN LPAD(
							LPAD(
								REPLACE(
									ROUND(((f.total-f.iva21-f.perIIBB)*f.tipoCambio), 2), '.', ''), 14, 0), 15, '0') 
						WHEN f.iva21 = 0 AND tipo.esNotaCredito=false
						THEN LPAD(0, 15, '0') 
						ELSE LPAD(REPLACE(ROUND(((f.total-f.iva21-f.perIIBB) * f.tipoCambio), 2), '.', ''), 15, 0) 
						END as netoGrabado,						
					CASE WHEN f.iva21=0
						THEN LPAD(3, 4, '0')
                    	ELSE LPAD(5, 4, '0') END AS alicuotaIVACodigoAfip,
					CASE WHEN tipo.esNotaCredito=true
					THEN LPAD(
						LPAD(
							REPLACE(
								ROUND((f.iva21*f.tipoCambio), 2), '.', ''), 14, '0'), 15, '0')
					ELSE LPAD(REPLACE(ROUND((f.iva21 * f.tipoCambio), 2), '.', ''), 15, '0') AS impuestoLiquidado")
                ->join('f.tipoId', 'tipo')                
                ->join('f.clienteId', 'cliente')
                ->join('cliente.iva', 'posicionIVA')
				->where('tipo.esBalance = 0')
				->andWhere('tipo.esNegro = 0')
                ->andWhere('f.fecha BETWEEN :desde AND :hasta')
                ->setParameter('desde', $desde->format('Y-m-d'))
				->setParameter('hasta', $hasta->format('Y-m-d'))
				->getQuery()
				->getArrayResult();

		//print_r($res);
		//exit;

		return $res;

	}

	public function buscarCbtesAsociados($arrayIdFcs){
		$em = $this->getEntityManager();
		$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');

		$qbFacturas = $repoFacturas->createQueryBuilder('fac')
				->select('tipo.codigoAfip,
					fac.ptoVta,
					fac.fcNro,
					fac.id,
					fac.total')			
				->leftJoin('fac.tipoId', 'tipo')
				->where('fac.id IN (:ids)')
				->setParameter('ids', $arrayIdFcs)	
				->getQuery()
				->getArrayResult();
		
		return $qbFacturas;
	}

	public function listaFacturasClientes($idCliente)
	{		
		$em = $this->getEntityManager();
		$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');
		
		try{
			$qbFacturas = $repoFacturas->createQueryBuilder('fac')
				->select('')
				->join('fac.clienteId', 'cliente')			
				->where('cliente.id = :idCliente')
				->setParameter('idCliente', $idCliente)	
				->getQuery();
			$resulFacturas = $qbFacturas->getResult();
			
			if(empty($resulFacturas)){
				return;
			}
			
		}catch(\Doctrine\ORM\ORMException $e){
			$this->get('logger')->error($e->getMessage());
		}	
		
		return $resulFacturas;
	}	
	
	public function listarFcCC($idCliente)
	{
		$em = $this->getEntityManager();
		$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');
		
		try{
			$qbFacturas = $repoFacturas->createQueryBuilder('fac')
				->select('
					fac.fecha, fac.vencimiento, fac.concepto, fac.tipo, fac.tipoCambio
					CASE fac.tipo WHEN 1 THEN item.cantidad * item.precio * tipoCambio ELSE 0 END AS debe,
					CASE fac.tipo WHEN 3 THEN item.cantidad * item.precio ELSE 0 END AS haber
				')
				->join('fac.clienteId', 'cliente')	
				->join('fac.facturaDetalleId', 'item')		
				->where('cliente.id = :idCliente')
				->setParameter('idCliente', $idCliente)	
				->getQuery();
			$resulFacturas = $qbFacturas->getResult();
			
			if(empty($resulFacturas)){
				return;
			}
			
			
		}catch(\Exception $e){
			$this->get('logger')->error($e->getMessage());
			return false;			
		}	
	}

	public function listarFacturasParaAsociar($idCliente){
		$em = $this->getEntityManager();
		$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');
		$repoCliente = $em->getRepository('MbpClientesBundle:Cliente');

		$cliente=$repoCliente->find($idCliente);
		$qb = $em->createQueryBuilder();

		$sub=$repoFacturas->createQueryBuilder('asoc')
			->select('asocFc.id')
			->join('asoc.facturasAsociadas', 'asocFc');

		$qb=$repoFacturas->createQueryBuilder('f')
			->select("f.id, f.fcNro as numFc, f.total as haber, date_format(f.vencimiento, '%d/%m/%Y') as vencimiento")
			->leftJoin('f.tipoId', 'tipo')
			->leftJoin('f.facturasAsociadas', 'facAsoc')
			->where('f.clienteId = :cliente')
			->andWhere('tipo.esFactura = true')
			->andWhere($qb->expr()->notIn('f.id', $sub->getDQL()))
			->setParameter('cliente', $cliente)
			->orderBy('f.fecha', 'DESC')
			->getQuery()
			->getArrayResult();
		
		return $qb;
	}

	public function ListarFcParaImputar($idCliente)
	{
		$em = $this->getEntityManager();
		$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');
		$repoTrans = $em->getRepository('MbpFinanzasBundle:TransaccionCobranzaFactura');

		$sql="
			SELECT sub.*, sub.haber - ifnull(sub.aplicado, 0) as pendiente
			FROM(SELECT 
				f.id,
				f.clienteId,
				date_format(f.fecha, '%d/%m/%Y') as fechaEmision,
				fcNro as numFc,
				ROUND((f.total*f.tipoCambio), 2) as haber,
				SUM(ifnull(tr.aplicado,0)) as aplicado,
				date_format(f.vencimiento, '%d/%m/%Y') as vencimiento    
			FROM Facturas f
			LEFT JOIN TransaccionCobranzaFactura tr ON tr.facturaId = f.id
			GROUP BY f.id) as sub
			where ((sub.haber - sub.aplicado) > 0 || isnull(sub.haber - sub.aplicado || sub.id not in (
				SELECT fcImputadas.nc_id FROM Facturas_NotasCredito AS fcImputadas
				)
			))
			AND sub.clienteId = $idCliente";
		
		$stmt = $em->getConnection()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function retencionesTXT($desdeSql, $hastaSql){		
		$em = $this->getEntityManager();
		
		$sth = $em->getConnection()->prepare("
			select 
				CONCAT(CONCAT(CONCAT(CONCAT(SUBSTRING(prov.cuit, 1, 2), '-'), SUBSTRING(prov.cuit, 3, 8)), '-'), SUBSTRING(prov.cuit, 11, 12)) AS cuit,
				DATE_FORMAT(op.fechaEmision, '%d/%m/%Y') AS fecha,
				LPAD(fc.sucursal, 4, '0') AS ptoVta,
				LPAD(tr.id, 8, '0') AS fcNro,
				tr.aplicado as aplicado,
				pago.importe as pagoImporte,
				baseImponible,
				LPAD((truncate((tr.aplicado * pago.importe / baseImponible), 2)), 11 ,'0') AS retencion,
				'A' AS finLinea
			from TransaccionOPFC tr
				left join FacturaProveedor fc on fc.id = tr.facturaId
				left join OrdenPago op on op.id = tr.ordenPagoId
				inner join Proveedor prov on prov.id = op.proveedorId
				inner join OrdenDePago_detallesPagos op_det on op_det.ordenPago_id = op.id
				inner join Pago pago on pago.id = op_det.pago_id
				left join FormasPagos fp on fp.id = pago.idFormaPago
				inner join
				(select SUM(case when f.neto > op.topeRetencionIIBB then tr.aplicado else 0 end) as baseImponible, op.id as opId
					from TransaccionOPFC as tr
					inner join FacturaProveedor f on f.id = tr.facturaId
					inner join OrdenPago op on op.id = tr.ordenPagoId
					where op.fechaEmision between '$desdeSql' and '$hastaSql'
					group by op.id) as sub on sub.opId = op.id
			where fp.retencionIIBB = true
				and op.fechaEmision between '$desdeSql' and '$hastaSql'
				and fc.neto > op.topeRetencionIIBB
				group by tr.id
		");
		
		$sth->execute();
		return $sth->fetchAll();
	}

	public function percepcionesTXT($desde, $hasta)
	{
		$em = $this->getEntityManager();
		$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');

		$res=$repoFacturas->createQueryBuilder('f')
				->select("
					CONCAT(CONCAT(CONCAT(CONCAT(SUBSTRING(cliente.cuit, 1, 2), '-'), SUBSTRING(cliente.cuit, 3, 8)), '-'), SUBSTRING(cliente.cuit, 11, 12)) AS cuit,
					f.perIIBB * f.tipoCambio,
					DATE_FORMAT(f.fecha, '%d/%m/%Y') AS fecha,
					CASE WHEN tipo.esFactura = true THEN 'F'
						WHEN tipo.esNotaCredito = true THEN 'C'
						WHEN tipo.esNotaDebito = true THEN 'D'
						ELSE '' END AS tipoCbte,
					CASE WHEN tipo.subTipoA = true THEN 'A'
						WHEN tipo.subTipoB = true THEN 'B'
						ELSE '' AS subTipoCbte,
					LPAD(f.ptoVta, 4, '0') AS ptoVta,
					LPAD(f.fcNro, 8, '0') AS fcNro,					
					CASE WHEN tipo.esNotaCredito = true THEN CONCAT('-', LPAD(ROUND(((f.total - f.iva21 - f.perIIBB)* f.tipoCambio),2), 11, '0'))
						ELSE LPAD(ROUND(((f.total - f.iva21 - f.perIIBB)* f.tipoCambio),2), 12, '0') END AS subTotal,
					CASE WHEN tipo.esNotaCredito = true THEN CONCAT('-', LPAD(ROUND((f.perIIBB * f.tipoCambio),2), 10, '0'))
						ELSE LPAD(ROUND((f.perIIBB * f.tipoCambio),2), 11, '0') END AS perIIBB,					
					'A' AS finLinea")
				->join('f.tipoId', 'tipo')
				->join('f.clienteId', 'cliente')
				->where('f.fecha BETWEEN :desde AND :hasta')
				->andWhere('f.perIIBB != 0')
				->setParameter('desde', $desde)
				->setParameter('hasta', $hasta)
				->getQuery()
				->getArrayResult();

		return $res;
	}
}
