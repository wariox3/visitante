<?php
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Form\Type\ClienteType;
class ClienteController extends Controller
{
    var $strDqlLista = "";    

    /**
     * @Route("/admin/cliente", name="admin_cliente")
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

        $arClientes = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('AppBundle:Admin/Cliente:lista.html.twig', array(
            'arClientes' => $arClientes,
            'form' => $form->createView()));
    }

    /**
     * @Route("/admin/cliente/nuevo/{codigoCliente}", name="admin_cliente_nuevo")
     */
    public function nuevoAction(Request $request, $codigoCliente = '') {
        $em = $this->getDoctrine()->getManager();
        $arCliente = new \AppBundle\Entity\Cliente();
        if($codigoCliente != '' && $codigoCliente != '0') {
            $arCliente = $em->getRepository('AppBundle:Cliente')->find($codigoCliente);
        }
        $form = $this->createForm(ClienteType::class, $arCliente);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arCliente = $form->getData();
            $em->persist($arCliente);
            $em->flush();

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('admin_cliente_nuevo', array('codigoCliente' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('admin_cliente'));
            }
        }
        return $this->render('AppBundle:Admin/Cliente:nuevo.html.twig', array(
            'arCliente' => $arCliente,
            'form' => $form->createView()));
    }

    private function lista($session) {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('AppBundle:Cliente')->listaDQL(
                $session->get('filtroNombreCliente')
                );
    }

    private function filtrar ($form, $session) {
        $session->set('filtroNombreCliente', $form->get('TxtNombre')->getData());               
        $this->lista($session);
    }

    private function formularioFiltro($session) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroNombreCliente')))
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
        $arClientes = new \AppBundle\Entity\Cliente();
        $arClientes = $query->getResult();
        foreach ($arClientes as $arCliente) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCliente->getCodigoClientePk())
                    ->setCellValue('B' . $i, $arCliente->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Cliente');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Cliente.xlsx"');
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