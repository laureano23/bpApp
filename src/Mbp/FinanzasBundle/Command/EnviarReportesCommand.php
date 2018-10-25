<?php
namespace Mbp\FinanzasBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EnviarReportesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('finanzas:enviarReportes')
            ->setDescription('Envía el libro de IVA ventas e IVA compras al estudio contable');
    }

    /**
     * @Route("/test/execute", name="mbp_reportes", options={"expose"=true})
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
            $desde=new \DateTime();
            $desde=$desde->modify('-1 month');
            $hasta=new \DateTime();
            $hasta=$hasta->modify('-1 month');
			$desde = $desde->modify('first day of this month');
            $hasta = $hasta->modify('last day of this month');
                                    
			$reporteador = $this->getContainer()->get('reporteador');
			$kernel = $this->getContainer()->get('kernel');			
			
			$jru = $reporteador->jru();
					
			$ruta = $kernel->locateResource('@MbpFinanzasBundle/Reportes/LibroIVAVentas.jrxml');
			
			$destinoIVAVentas = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/pdf/').'LibroIVAVentas.pdf';		
			
			//Parametros HashMap
			$param = $reporteador->getJava('java.util.HashMap');
			$rutaLogo = $reporteador->getRutaLogo($kernel);
			
			$param->put('fechaDesde', $desde->format('d/m/Y'));
			$param->put('fechaHasta', $hasta->format('d/m/Y')); 
			
			$conn = $reporteador->getJdbc();
						
			$desde = $desde->format('Y-m-d');
			$hasta = $hasta->format('Y-m-d');
							
			
			$sql = "SELECT
			     CASE WHEN Facturas.`moneda`=1 THEN Facturas.`perIIBB`*Facturas.`tipoCambio` ELSE Facturas.`perIIBB` END AS Facturas_perIIBB,
			     CASE WHEN Facturas.`moneda`=1 THEN Facturas.`iva21`*Facturas.`tipoCambio` ELSE Facturas.`iva21` END AS Facturas_iva21,
			     CASE WHEN Facturas.`moneda`=1 THEN Facturas.`total`*Facturas.`tipoCambio` ELSE Facturas.`total` END AS Facturas_total,
			     cliente.`idCliente` AS cliente_idCliente,
			     cliente.`rsocial` AS cliente_rsocial,
			     Facturas.`id` AS Facturas_id,
			     Facturas.`fecha` AS Facturas_fecha,
			     Facturas.`concepto` AS Facturas_concepto,
			     Facturas.`clienteId` AS Facturas_clienteId,
			     Facturas.`ptoVta` AS Facturas_ptoVta,
			     Facturas.`fcNro` AS Facturas_fcNro,
			     Facturas.`rSocial` AS Facturas_rSocial,
			     Facturas.`cuit` AS Facturas_cuit,
			     Facturas.`ivaCond` AS Facturas_ivaCond,
			     Facturas.`porcentajeIIBB` AS Facturas_porcentajeIIBB,
			     Facturas.`moneda` AS Facturas_moneda,
			     Facturas.`tipoCambio` AS Facturas_tipoCambio,
			     Facturas.`tipoId` AS Facturas_tipoId,
				 Facturas.`dtoTotal` AS Facturas_dtoTotal,
			     TipoComprobante.`id` AS TipoComprobante_id,
			     TipoComprobante.`esFactura` AS TipoComprobante_esFactura,
			     TipoComprobante.`esNotaCredito` AS TipoComprobante_esNotaCredito,
			     TipoComprobante.`esNotaDebito` AS TipoComprobante_esNotaDebito,
			     TipoComprobante.`esBalance` AS TipoComprobante_esBalance,
			     TipoComprobante.`descripcion` AS TipoComprobante_descripcion,
			     TipoComprobante.`subTipoA` AS TipoComprobante_subTipoA,
			     TipoComprobante.`subTipoB` AS TipoComprobante_subTipoB,
			     TipoComprobante.`subTipoE` AS TipoComprobante_subTipoE,
			     Facturas.`tipoIva` AS Facturas_tipoIva,
			     PosicionIVA.`id` AS PosicionIVA_id,
			     PosicionIVA.`posicion` AS PosicionIVA_posicion,
			     PosicionIVA.`esResponsableInscripto` AS PosicionIVA_esResponsableInscripto,
			     PosicionIVA.`esResponsableNoInscripto` AS PosicionIVA_esResponsableNoInscripto,
			     PosicionIVA.`esExento` AS PosicionIVA_esExento,
			     PosicionIVA.`esResponsableMonotributo` AS PosicionIVA_esResponsableMonotributo,
			     PosicionIVA.`esConsumidorFinal` AS PosicionIVA_esConsumidorFinal,
			     PosicionIVA.`esExportacion` AS PosicionIVA_esExportacion,
			     FacturaDetalle.`id` AS FacturaDetalle_id,
			     FacturaDetalle.`descripcion` AS FacturaDetalle_descripcion,
			     FacturaDetalle.`cantidad` AS FacturaDetalle_cantidad,
			     FacturaDetalle.`precio` AS FacturaDetalle_precio,
			     SUM(CASE WHEN FacturaDetalle.`ivaGrabado`= 1 THEN
				 FacturaDetalle.`cantidad` * FacturaDetalle.`precio`
				 ELSE 0 END) AS netoGrabado,
			     SUM(CASE WHEN FacturaDetalle.`ivaGrabado`= 0 THEN
				 FacturaDetalle.`cantidad` * FacturaDetalle.`precio`
				 ELSE 0 END) AS netoNoGrabado,
			     FacturaDetalle.`articuloId` AS FacturaDetalle_articuloId,
			     FacturaDetalle.`remitoId` AS FacturaDetalle_remitoId,
			     FacturaDetalle.`ivaGrabado` AS FacturaDetalle_ivaGrabado,
			     factura_detallesFacturas.`factura_id` AS factura_detallesFacturas_factura_id,
			     factura_detallesFacturas.`facturadetalle_id` AS factura_detallesFacturas_facturadetalle_id
			FROM
			     `cliente` cliente INNER JOIN `Facturas` Facturas ON cliente.`idCliente` = Facturas.`clienteId`
			     INNER JOIN `TipoComprobante` TipoComprobante ON Facturas.`tipoId` = TipoComprobante.`id`
			     INNER JOIN `PosicionIVA` PosicionIVA ON Facturas.`tipoIva` = PosicionIVA.`id`
			     INNER JOIN `factura_detallesFacturas` factura_detallesFacturas ON Facturas.`id` = factura_detallesFacturas.`factura_id`
			     RIGHT JOIN `FacturaDetalle` FacturaDetalle ON factura_detallesFacturas.`facturadetalle_id` = FacturaDetalle.`id`
			WHERE
			     Facturas.`fecha` BETWEEN '$desde' AND '$hasta'
			 AND TipoComprobante.`esBalance` = 0
			GROUP BY Facturas.`id`
			ORDER BY
			     Facturas.`fecha` ASC";
			
        $jru->runPdfFromSql($ruta, $destinoIVAVentas, $param, $sql, $conn->getConnection());	

        $sqlIVACompras = "SELECT
				Proveedor.`id` AS Proveedor_id,
				Proveedor.`provincia` AS Proveedor_provincia,
				Proveedor.`rsocial` AS Proveedor_rsocial,
				Proveedor.`denominacion` AS Proveedor_denominacion,
				Proveedor.`direccion` AS Proveedor_direccion,
				Proveedor.`cuit` AS Proveedor_cuit,
				Proveedor.`vencimientoFc` AS Proveedor_vencimientoFc,
				Proveedor.`imputacionGastos` AS Proveedor_imputacionGastos,
				FacturaProveedor.`proveedorId` AS FacturaProveedor_proveedorId,
				FacturaProveedor.`totalFc` AS FacturaProveedor_totalFc,
				FacturaProveedor.`concepto` AS FacturaProveedor_concepto,
				FacturaProveedor.`vencimiento` AS FacturaProveedor_vencimiento,
				FacturaProveedor.`iibbCf` AS FacturaProveedor_iibbCf,
				FacturaProveedor.`iibbBsas` AS FacturaProveedor_iibbBsas,
				FacturaProveedor.`iibbOtras` AS FacturaProveedor_iibbOtras,
				FacturaProveedor.`perIva3` AS FacturaProveedor_perIva3,
				FacturaProveedor.`perIva5` AS FacturaProveedor_perIva5,
				FacturaProveedor.`iva10_5` AS FacturaProveedor_iva10_5,
				FacturaProveedor.`iva21` AS FacturaProveedor_iva21,
				FacturaProveedor.`netoNoGrabado` AS FacturaProveedor_netoNoGrabado,
				FacturaProveedor.`neto` AS FacturaProveedor_neto,
				FacturaProveedor.`iva27` AS FacturaProveedor_iva27,
				FacturaProveedor.`numFc` AS FacturaProveedor_numFc,
				FacturaProveedor.`sucursal` AS FacturaProveedor_sucursal,
				FacturaProveedor.`fechaEmision` AS FacturaProveedor_fechaEmision,
				FacturaProveedor.`id` AS FacturaProveedor_id,
				TipoComprobante.`id` AS TipoComprobante_id,
				TipoComprobante.`esFactura` AS TipoComprobante_esFactura,
				TipoComprobante.`esNotaCredito` AS TipoComprobante_esNotaCredito,
				TipoComprobante.`esNotaDebito` AS TipoComprobante_esNotaDebito,
				TipoComprobante.`esBalance` AS TipoComprobante_esBalance,
				TipoComprobante.`descripcion` AS TipoComprobante_descripcion,
				TipoComprobante.`subTipoA` AS TipoComprobante_subTipoA,
				TipoComprobante.`subTipoB` AS TipoComprobante_subTipoB,
				TipoComprobante.`subTipoE` AS TipoComprobante_subTipoE,
				TipoComprobante.`esNegro` AS TipoComprobante_esNegro,
				TipoComprobante.`abreviatura` AS TipoComprobante_abreviatura,
				FacturaProveedor.`tipoId` AS FacturaProveedor_tipoId
			FROM
			     `Proveedor` Proveedor INNER JOIN `FacturaProveedor` FacturaProveedor ON Proveedor.`id` = FacturaProveedor.`proveedorId`
			     INNER JOIN `TipoComprobante` TipoComprobante ON FacturaProveedor.`tipoId` = TipoComprobante.`id`
			WHERE
			     FacturaProveedor.`fechaEmision` BETWEEN '$desde' AND '$hasta'";		     
			

        $destinoIVACompras = $kernel->locateResource('@MbpProveedoresBundle/Resources/public/pdf/').'LibroIVACompras.pdf';
        $ruta = $kernel->locateResource('@MbpProveedoresBundle/Reportes/LibroIVACompras.jrxml');
        $jru->runPdfFromSql($ruta, $destinoIVACompras, $param, $sqlIVACompras, $conn->getConnection());	
        

        $mensaje = \Swift_Message::newInstance()
				->setSubject("Libro IVA ventas Metalurgica BP")
				->setFrom('administracion@metalurgicabp.com.ar')
				->setTo('lucilamariani@estudioplataroti.com.ar')
                ->setBody("Este es un envío automático");
        $adjunto1 = \Swift_Attachment::fromPath($destinoIVAVentas);
        $adjunto2 = \Swift_Attachment::fromPath($destinoIVACompras);
        $mensaje->attach($adjunto1);
        $mensaje->attach($adjunto2);
				
        $this->getContainer()->get('mailer')->send($mensaje);
    }
}