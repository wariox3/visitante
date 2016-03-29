<?php

namespace AppBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistroVisitanteController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/utilidad/registro/visitante", name="utilidad_registro_visitante")
     */    
    public function listaAction(Request $request)
    {        
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);        
        $this->listar($form);         
        if($form->isValid()) {
            $identificacion = $form->get('TxtIdentificacion')->getData();
            if($identificacion) {
                $arVisitante = $em->getRepository('AppBundle:Visitante')->findOneBy(array('numeroIdentificacion' => $identificacion));
                if($arVisitante) {
                    $arUsuario = $this->getUser();
                    $arCliente = $em->getRepository();
                    $arRegistro = $em->getRepository('AppBundle:Registro')->findOneBy(array('codigoVisitanteFk' => $arVisitante->getCodigoVisitantePk(), 'estadoSalida' => 0));
                    if($arRegistro) {
                        $arRegistroAct = $em->getRepository('AppBundle:Registro')->find($arRegistro->getCodigoRegistroPk());
                        $arRegistroAct->setFechaSalida(new \DateTime('now'));
                        $arRegistroAct->setEstadoSalida(1);
                    } else {
                        $arRegistroAct = new \AppBundle\Entity\Registro();
                        $arRegistroAct->setVisitanteRel($arVisitante);
                        $arRegistroAct->setEstadoEntrada(1);
                        $arRegistroAct->setFechaEntrada(new \DateTime('now'));
                    }
                    $em->persist($arRegistroAct);
                    $em->flush();
                }
            }
        }        
        $arRegistros = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->getInt('page', 1), 50);        
        return $this->render('AppBundle:Utilidad/RegistroVisitante:lista.html.twig', array(
            'form' => $form->createView(),
            'arRegistros' => $arRegistros
            ));
    }   
    
    private function listar($form) {
        $em = $this->getDoctrine()->getManager();         
        $this->strDqlLista = $em->getRepository('AppBundle:Registro')->listaDql(1,0);  
    }
    
    private function formularioLista() {        
        $form = $this->createFormBuilder()              
            ->add('TxtIdentificacion', TextType::class, array('label'  => 'Numero','data' => ""))                                                               
            ->add('BtnFiltrar',  SubmitType::class, array('label'  => 'Guardar'))                                            
            ->getForm();        
        return $form;
    }
        
}
