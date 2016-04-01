<?php
namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Form\Type\VisitanteType;
class VisitanteController extends Controller
{
    var $strDqlLista = "";    

    /**
     * @Route("/admin/visitante", name="admin_visitante")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro($request->getSession());
        $form->handleRequest($request);
        $this->lista($request->getSession());
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('AppBundle:Visitante')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('admin_visitante'));
            }            
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form, $request->getSession());
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form, $request->getSession());
                $this->generarExcel();
            }
        }

        $arVisitantes = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('AppBundle:Admin/Visitante:lista.html.twig', array(
            'arVisitantes' => $arVisitantes,
            'form' => $form->createView()));
    }

    /**
     * @Route("/admin/visitante/nuevo/{codigoVisitante}", name="admin_visitante_nuevo")
     */
    public function nuevoAction(Request $request, $codigoVisitante = '') {
        $em = $this->getDoctrine()->getManager();
        $arVisitante = new \AppBundle\Entity\Visitante();
        if($codigoVisitante != '' && $codigoVisitante != '0') {
            $arVisitante = $em->getRepository('AppBundle:Visitante')->find($codigoVisitante);
        }
        $form = $this->createForm(VisitanteType::class, $arVisitante);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arVisitante = $form->getData();
            $em->persist($arVisitante);
            $em->flush();

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('admin_visitante_nuevo', array('codigoVisitante' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('admin_visitante'));
            }
        }
        return $this->render('AppBundle:Admin/Visitante:nuevo.html.twig', array(
            'arVisitante' => $arVisitante,
            'form' => $form->createView()));
    }

    private function lista($session) {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('AppBundle:Visitante')->listaDQL(
                $session->get('filtroCodigoGrupo'),
                $session->get('filtroIdentificacion'),
                $session->get('filtroNombreVisitante')
                );
    }

    private function filtrar ($form, $session) {
        $session->set('filtroNombreVisitante', $form->get('TxtNombre')->getData());
        $session->set('filtroIdentificacion', $form->get('TxtNumeroIdentificacion')->getData());
        $arGrupo = $form->get('grupoRel')->getData();
        if($arGrupo) {
            $session->set('filtroCodigoGrupo', $arGrupo->getCodigoGrupoPk());
        } else {
            $session->set('filtroCodigoGrupo', '');
        }
        $this->lista($session);
    }

    private function formularioFiltro($session) {
        $em = $this->getDoctrine()->getManager();
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
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroNombreVisitante')))
            ->add('TxtNumeroIdentificacion', TextType::class, array('label'  => 'NumeroIdentificacion','data' => $session->get('filtroIdentificacion')))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))                            
            ->getForm();
        return $form;
    }

    private function generarExcel() {
        ob_clean();
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'IDENTIFICACION')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'GRUPO')
                    ->setCellValue('E1', 'ARL');

        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        $arVisitantes = new \AppBundle\Entity\Visitante();
        $arVisitantes = $query->getResult();
        foreach ($arVisitantes as $arVisitante) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arVisitante->getCodigoVisitantePk())
                    ->setCellValue('B' . $i, $arVisitante->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arVisitante->getNombre())
                    ->setCellValue('D' . $i, $arVisitante->getGrupoRel()->getNombre())
                    ->setCellValue('E' . $i, $arVisitante->getFechaArl()->format('Y/m/d'));
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Visitante');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Visitante.xlsx"');
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