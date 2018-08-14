<?php
namespace Mbp\FinanzasBundle\Entity;

/**
 * FacturasRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FacturasRepository extends \Doctrine\ORM\EntityRepository
{
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
					LPAD(f.rSocial, 30, ' ') AS nombreComprador,
					CASE WHEN f.moneda=1
						THEN LPAD(REPLACE(ROUND((f.total*f.tipoCambio), 2), '.', ''), 15, 0)
						ELSE LPAD(REPLACE(f.total, '.', ''), 15, 0) END as montoTotal,
					CASE WHEN f.iva21 > 0 AND f.moneda = 0  
						THEN LPAD(0, 15, '0') 
						WHEN f.iva21 = 0 AND f.moneda = 0
						THEN LPAD(REPLACE((f.total-f.iva21-f.perIIBB), '.', ''), 15, '0') 
						WHEN f.iva21 > 0 AND f.moneda = 1  
						THEN LPAD(0, 15, '0') 
						ELSE LPAD(REPLACE(ROUND(((f.total-f.iva21-f.perIIBB) * f.tipoCambio), 2), '.', ''), 15, '0') 
						END AS montoNoGrabado,	
					LPAD(0, 15, '0') AS percepcionNoCategorizados,   
					LPAD(0, 15, '0') AS montoExcento,  					
					LPAD(0, 15, '0') AS pagoCuentaImpNacionales,   
					CASE WHEN f.moneda = 0
						THEN LPAD(REPLACE(f.perIIBB, '.', ''), 15, '0')
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
                ->andWhere('f.fecha BETWEEN :desde AND :hasta')
                ->setParameter('desde', $desde->format('Y-m-d'))
				->setParameter('hasta', $hasta->format('Y-m-d'))
				->getQuery()
				->getArrayResult();

				//print_r($res);
				//exit;

				/*
				* CASE WHEN f.iva21 > 0  
                        THEN  
						ELSE LPAD(REPLACE((f.total-f.iva21-f.perIIBB), '.', ''), 15, '0') END AS montoNoGrabado,   
				*/ 
				
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
					CASE WHEN f.iva21 = 0 AND f.moneda = 0
						THEN LPAD(0, 15, '0') 
						WHEN f.iva21 > 0 AND f.moneda = 0
						THEN LPAD(REPLACE((f.total-f.iva21-f.perIIBB), '.', ''), 15, 0) 
						WHEN f.iva21 = 0 AND f.moneda = 1
						THEN LPAD(0, 15, '0') 
						ELSE LPAD(REPLACE(ROUND(((f.total-f.iva21-f.perIIBB) * f.tipoCambio), 2), '.', ''), 15, 0) 
						END as netoGrabado,						
					CASE WHEN f.iva21=0
						THEN LPAD(3, 4, '0')
                    	ELSE LPAD(5, 4, '0') END AS alicuotaIVACodigoAfip,
					CASE WHEN f.moneda = 0
					THEN LPAD(REPLACE(f.iva21, '.', ''), 15, '0')
					ELSE LPAD(REPLACE(ROUND((f.iva21 * f.tipoCambio), 2), '.', ''), 15, '0') AS impuestoLiquidado")
                ->join('f.tipoId', 'tipo')                
                ->join('f.clienteId', 'cliente')
                ->join('cliente.iva', 'posicionIVA')
                ->where('tipo.esBalance = 0')
                ->andWhere('f.fecha BETWEEN :desde AND :hasta')
                ->setParameter('desde', $desde->format('Y-m-d'))
				->setParameter('hasta', $hasta->format('Y-m-d'))
				->getQuery()
				->getArrayResult();

				
		return $res;

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
			
			print_r($resulFacturas);
			
		}catch(\Exception $e){
			$this->get('logger')->error($e->getMessage());
			return false;			
		}	
	}

	public function ListarFcParaImputar($idCliente)
	{
		$em = $this->getEntityManager();
		$repoFacturas = $em->getRepository('MbpFinanzasBundle:Facturas');
		$repoTrans = $em->getRepository('MbpFinanzasBundle:TransaccionCobranzaFactura');

		try{
			$qbFacturas = $repoFacturas->createQueryBuilder('f')
				->select('')
				->where('f.clienteId = :idCliente')
				->setParameter('idCliente', $idCliente)
				->getQuery();
			$res = $qbFacturas->getResult();

			$qb2 = $repoTrans->createQueryBuilder('t');			
			$query2 = $qb2->select('fc.id, SUM(t.aplicado) AS aplicado')
					->join('t.facturaImputada', 'fc')
					->where('fc.clienteId = :idCliente')
					->groupBy('t.facturaImputada')
					->setParameter('idCliente',$idCliente)
					->getQuery();
			$res2 = $query2->getResult();

			$i=0;
			$resp = array();
			foreach ($res as $rec) {
				$resp[$i]['id'] = $rec->getId();				
				$resp[$i]['fechaEmision'] = $rec->getFecha()->format('d-m-Y H:i:s');
				$resp[$i]['concepto'] = "FACTURA N° ".$rec->getfcNro();
				$resp[$i]['numFc'] = $rec->getfcNro();
				$resp[$i]['vencimiento'] = $rec->getvencimiento()->format('d/m/Y');
				$resp[$i]['haber'] = $rec->getTotal()*$rec->getTipoCambio();								
				$resp[$i]['pendiente'] = 0;
				$i++;
			}

			for($i=0; $i<count($res2); $i++){
				for ($j=0; $j < count($res); $j++) { 
					if(array_key_exists($i, $res2) && $res2[$i]['id'] == $res[$j]->getId()){					
						$resp[$j]['valorAplicado'] = $res2[$i]['aplicado'];									
					}
				}
			}

			return $resp;


		}catch(\Exception $e){
			throw $e;			
		}
	}
}
