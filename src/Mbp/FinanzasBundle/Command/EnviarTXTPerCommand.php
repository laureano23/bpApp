<?php
namespace Mbp\FinanzasBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mbp\FinanzasBundle\Controller\AplicativosController;

class EnviarTXTPerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('finanzas:enviarTXTPercepciones')
            ->setDescription('Envía los TXT correspondientes a las percepciones por mail');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

		$em = $this->getContainer()->get('doctrine')->getManager();
		$repo = $em->getRepository('MbpFinanzasBundle:Facturas');
		$desde=new \DateTime();
		$desde=$desde->modify('-1 month');
		$hasta=new \DateTime();
		$hasta=$hasta->modify('-1 month');
		$desde = $desde->modify('first day of this month');
		$hasta = $hasta->modify('last day of this month');
								
		$kernel = $this->getContainer()->get('kernel');			

		//PERCEPCIONES
		$res = $repo->percepcionesTXT($desde, $hasta);
		
		$nombreArchivo="AR"."-".$this->getContainer()->getParameter('cuit_prod')."-".$desde->format("Ym").AplicativosController::$periodoPercepcion."-".AplicativosController::$codigoPercepcion."-LOTE1.txt";
			
		$basePath = $kernel->locateResource('@MbpFinanzasBundle/Resources/public/txt/');
		$file=fopen($basePath.$nombreArchivo, "w");
		
		foreach ($res as $linea) {				
			$str = $linea['cuit'].$linea['fecha'].$linea['tipoCbte'].$linea['subTipoCbte'].$linea['ptoVta'].$linea['fcNro'].$linea['subTotal'].$linea['perIIBB'].$linea['finLinea'].PHP_EOL;	
			fwrite($file, $str);
		}
		fclose($file);

        $mensaje = \Swift_Message::newInstance()
				->setSubject("TXT Percepciones IIBB Metalurgica BP")
				->setFrom('administracion@metalurgicabp.com.ar')
				->setTo('lucilamariani@estudioplataroti.com.ar')
                ->setBody("Este es un envío automático");
        $adjunto1 = \Swift_Attachment::fromPath($basePath.$nombreArchivo);
        $mensaje->attach($adjunto1);
				
        $this->getContainer()->get('mailer')->send($mensaje);
    }
}