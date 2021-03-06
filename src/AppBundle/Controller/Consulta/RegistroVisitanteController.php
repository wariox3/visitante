<?php

namespace AppBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class RegistroVisitanteController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/consulta/registro/visitante", name="consulta_registro_visitante")
     */    
    public function listaAction(Request $request)
    {        
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista($request->getSession());
        $form->handleRequest($request);        
        $this->listar($request->getSession());         
        if($form->isValid()) {
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form, $request->getSession());
                $form = $this->formularioLista($request->getSession());                
                $this->generarExcel();                
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form, $request->getSession());   
                $form = $this->formularioLista($request->getSession());
                $this->listar($request->getSession());
            }
        }        
        $arRegistros = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->getInt('page', 1), 50);        
        return $this->render('AppBundle:Consulta/RegistroVisitante:lista.html.twig', array(
            'form' => $form->createView(),
            'arRegistros' => $arRegistros
            ));
    }   
    
    private function listar($session) {
        $em = $this->getDoctrine()->getManager();  
        $strFechaDesde = "";
        $strFechaHasta = "";        
        $filtrarFecha = $session->get('filtroRegistroFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroRegistroFechaDesde');
            $strFechaHasta = $session->get('filtroRegistroFechaHasta');                    
        }        
        $this->strDqlLista = $em->getRepository('AppBundle:Registro')->listaDql(
                "", "",
                $session->get('filtroCodigoGrupo'),
                $session->get('filtroCodigoVisitante'),
                $strFechaDesde,
                $strFechaHasta                
                );  
    }

    private function filtrar ($form, $session) {
        $session->set('filtroIdentificacion', $form->get('TxtNumeroIdentificacion')->getData());
        if($form->get('TxtNumeroIdentificacion')->getData() == "") {
            $session->set('filtroCodigoVisitante', "");
        }
        $arGrupo = $form->get('grupoRel')->getData();
        if($arGrupo) {
            $session->set('filtroCodigoGrupo', $arGrupo->getCodigoGrupoPk());
        } else {
            $session->set('filtroCodigoGrupo', '');
        }  
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroRegistroFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroRegistroFechaHasta', $dateFechaHasta->format('Y/m/d'));                 
        $session->set('filtroRegistroFiltrarFecha', $form->get('filtrarFecha')->getData());        
    }
    
    private function formularioLista($session) {
        $em = $this->getDoctrine()->getManager();
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroRegistroFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroRegistroFechaDesde');
        }
        if($session->get('filtroRegistroFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroRegistroFechaHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);        
        $strNombreVisitante = "";
        if($session->get('filtroIdentificacion')) {
            $arVisitante = $em->getRepository('AppBundle:Visitante')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if($arVisitante) {                
                $session->set('filtroCodigoVisitante', $arVisitante->getCodigoVisitantePk());
                $strNombreVisitante = $arVisitante->getNombre();
            }  else {
                $session->set('filtroCodigoVisitante', null);
                $session->set('filtroIdentificacion', null);
            }          
        }        
        
        $arrayPropiedadesGrupo = array(
                'class' => 'AppBundle:Grupo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                    ->orderBy('g.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
            );
        if($session->get('filtroCodigoGrupo')) {
            $arrayPropiedadesGrupo['data'] = $em->getReference("AppBundle:Grupo", $session->get('filtroCodigoGrupo'));
        }       
        $form = $this->createFormBuilder() 
            ->add('grupoRel', EntityType::class, $arrayPropiedadesGrupo)                
            ->add('TxtNumeroIdentificacion', TextType::class, array('label'  => 'NumeroIdentificacion','data' => $session->get('filtroIdentificacion')))            
            ->add('TxtNombreVisitante', TextType::class, array('label'  => 'NombreVisitante','data' => $strNombreVisitante))                                                
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                                
            ->add('filtrarFecha', CheckboxType::class, array('required'  => false, 'data' => $session->get('filtroRegistroFiltrarFecha')))                                             
            ->add('BtnFiltrar',  SubmitType::class, array('label'  => 'Filtrar'))                                            
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))                                            
            ->getForm();        
        return $form;
    }
    
    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();        
        $objPHPExcel = new \PHPExcel();        
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'I'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        /*for($col = 'AD'; $col !== 'AF'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }*/
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIGO')
                ->setCellValue('B1', 'CLIENTE')
                ->setCellValue('C1', 'GRUPO')
                ->setCellValue('D1', 'IDENTIFICACION')
                ->setCellValue('E1', 'NOMBRE')
                ->setCellValue('F1', 'ARL')
                ->setCellValue('G1', 'ENTRADA')
                ->setCellValue('H1', 'SALIDA');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arRegistros = new \AppBundle\Entity\Registro();
        $arRegistros = $query->getResult();
        foreach ($arRegistros as $arRegistro) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRegistro->getCodigoRegistroPk())
                    ->setCellValue('B' . $i, $arRegistro->getClienteRel()->getNombre())
                    ->setCellValue('C' . $i, $arRegistro->getGrupoRel()->getNombre())
                    ->setCellValue('D' . $i, $arRegistro->getVisitanteRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arRegistro->getVisitanteRel()->getNombre())
                    ->setCellValue('F' . $i, $arRegistro->getFechaArl()->format('Y/m/d'))
                    ->setCellValue('G' . $i, $arRegistro->getFechaEntrada()->format('Y/m/d H:i'));
            if($arRegistro->getFechaSalida()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $i, $arRegistro->getFechaSalida()->format('Y/m/d H:i'));
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('registros');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Registros.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }        
}
