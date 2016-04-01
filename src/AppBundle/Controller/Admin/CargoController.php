<?php
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Form\Type\CargoType;
class CargoController extends Controller
{
    var $strDqlLista = "";    

    /**
     * @Route("/admin/cargo", name="admin_cargo")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro($request->getSession());
        $form->handleRequest($request);
        $this->lista($request->getSession());
        if ($form->isValid()) {
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form, $request->getSession());
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form, $request->getSession());
                $this->generarExcel();
            }
        }

        $arCargos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('AppBundle:Admin/Cargo:lista.html.twig', array(
            'arCargos' => $arCargos,
            'form' => $form->createView()));
    }

    /**
     * @Route("/admin/cargo/nuevo/{codigoCargo}", name="admin_cargo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoCargo = '') {
        $em = $this->getDoctrine()->getManager();
        $arCargo = new \AppBundle\Entity\Cargo();
        if($codigoCargo != '' && $codigoCargo != '0') {
            $arCargo = $em->getRepository('AppBundle:Cargo')->find($codigoCargo);
        }
        $form = $this->createForm(CargoType::class, $arCargo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arCargo = $form->getData();
            $em->persist($arCargo);
            $em->flush();

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('admin_cargo_nuevo', array('codigoCargo' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('admin_cargo'));
            }
        }
        return $this->render('AppBundle:Admin/Cargo:nuevo.html.twig', array(
            'arCargo' => $arCargo,
            'form' => $form->createView()));
    }

    private function lista($session) {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('AppBundle:Cargo')->listaDQL(
                $session->get('filtroNombreCargo')
                );
    }

    private function filtrar ($form, $session) {
        $session->set('filtroNombreCargo', $form->get('TxtNombre')->getData());               
        $this->lista($session);
    }

    private function formularioFiltro($session) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroNombreCargo')))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
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
                    ->setCellValue('B1', 'NOMBRE');

        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        $arCargos = new \AppBundle\Entity\Cargo();
        $arCargos = $query->getResult();
        foreach ($arCargos as $arCargo) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCargo->getCodigoCargoPk())
                    ->setCellValue('B' . $i, $arCargo->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Cargo');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Cargo.xlsx"');
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