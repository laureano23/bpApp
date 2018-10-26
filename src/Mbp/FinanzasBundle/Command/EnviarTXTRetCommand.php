<?php
namespace Mbp\FinanzasBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mbp\FinanzasBundle\Controller\AplicativosController;

class EnviarTXTRetCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('finanzas:enviarTXTRetenciones')
            ->setDescription('Envía los TXT correspondientes a las retenciones de IIBB por mail');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

		$em = $this->getContainer()->get('doctrine')->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:Facturas');
		$desde=new \DateTime();
		$hasta=new \DateTime();
		$quincena;

		//PARA QUE LAS FECHAS FUNCIONEN EL COMANDO SE DEBE EJECUTAR EL 1 Y EL 16 DE CADA MES
		if(16 >= $desde->format('d') && $desde->format('d') <= 31){
			$desde->modify('first day of this month');
			$hasta->modify('first day of this month');
			$hasta->modify('+14 day');
			$quincena=2;
		}else{
			$desde->modify('-1 month');
			$hasta->modify('-1 month');
			$desde->modify('first day of this month');
			$desde->modify('+15 day');
			$hasta->modify('last day of this month');
			$quincena=1;
		}
								
		$kernel = $this->getContainer()->get('kernel');			

		//RETENCIONES
		$res=$repo->retencionesTXT($desde->format('Y-m-d'), $hasta->format('Y-m-d'));
			
		$nombreArchivo="AR"."-".$this->getContainer()->getParameter('cuit_prod')."-".$desde->format("Ym").$quincena."-".AplicativosController::$codigoRetencion."-LOTE1.txt";
		
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/txt/');
		$file=fopen($basePath.$nombreArchivo, "w");
		
		foreach ($res as $linea) {				
			$str = $linea['cuit'].$linea['fecha'].$linea['ptoVta'].$linea['fcNro'].$linea['retencion'].$linea['finLinea'].PHP_EOL;	
			fwrite($file, $str);
		}

		fclose($file);

        $mensaje = \Swift_Message::newInstance()
				->setSubject("TXT Retenciones IIBB Metalurgica BP")
				->setFrom('administracion@metalurgicabp.com.ar')
				->setTo('lucilamariani@estudioplataroti.com.ar')
                ->setBody("Este es un envío automático");
        $adjunto1 = \Swift_Attachment::fromPath($basePath.$nombreArchivo);
        $mensaje->attach($adjunto1);
				
        $this->getContainer()->get('mailer')->send($mensaje);
    }
}