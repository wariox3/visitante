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
        $this->listar();         
        if($form->isValid()) {
            if($request->request->get('OpSalida')) {
                $codigoRegistro = $request->request->get('OpSalida');
                $arRegistro = $em->getRepository('AppBundle:Registro')->find($codigoRegistro);
                $arRegistro->setFechaSalida(new \DateTime('now'));
                $arRegistro->setEstadoSalida(1);
                $em->persist($arRegistro);
                $em->flush();
            }
            if($form->get('BtnGuardar')->isClicked()) {
                $identificacion = $form->get('TxtNumeroIdentificacion')->getData();
                if($identificacion) {
                $arVisitante = $em->getRepository('AppBundle:Visitante')->findOneBy(array('numeroIdentificacion' => $identificacion));
                if($arVisitante) {
                    $arUsuario = $this->getUser();
                    if($arUsuario->getCodigoClienteFk()) {
                        $arCliente = $em->getRepository('AppBundle:Cliente')->find($arUsuario->getCodigoClienteFk());
                        $arRegistro = $em->getRepository('AppBundle:Registro')->findOneBy(array('codigoVisitanteFk' => $arVisitante->getCodigoVisitantePk(), 'estadoSalida' => 0));
                        if($arRegistro) {
                            $arRegistroAct = $em->getRepository('AppBundle:Registro')->find($arRegistro->getCodigoRegistroPk());
                            $arRegistroAct->setFechaSalida(new \DateTime('now'));
                            $arRegistroAct->setEstadoSalida(1);
                        } else {
                            $fecha = new \DateTime('now');
                            $arRegistroAct = new \AppBundle\Entity\Registro();
                            $arRegistroAct->setClienteRel($arCliente);
                            $arRegistroAct->setGrupoRel($arVisitante->getGrupoRel());
                            $arRegistroAct->setCargoRel($arVisitante->getCargoRel());
                            $arRegistroAct->setVisitanteRel($arVisitante);
                            $arRegistroAct->setFechaArl($arVisitante->getFechaArl());                            
                            $arRegistroAct->setEstadoEntrada(1);
                            $arRegistroAct->setFechaEntrada($fecha);
                            if($fecha > $arVisitante->getFechaArl()) {
                                $arRegistroAct->setArlVencida(1);
                            }
                        }
                        $em->persist($arRegistroAct);
                        $em->flush();    
                        $session = $request->getSession();
                        $session->set('filtroIdentificacion', null);
                        
                    }
                }
            }                
            }
            return $this->redirect($this->generateUrl('utilidad_registro_visitante'));
        }        
        $arRegistros = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->getInt('page', 1), 50);        
        return $this->render('AppBundle:Utilidad/RegistroVisitante:lista.html.twig', array(
            'form' => $form->createView(),
            'arRegistros' => $arRegistros
            ));
    }   
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();         
        $this->strDqlLista = $em->getRepository('AppBundle:Registro')->listaDql(1,0);  
    }
    
    private function formularioLista() {         
        $form = $this->createFormBuilder()              
            ->add('TxtNumeroIdentificacion', TextType::class, array('label'  => 'NumeroIdentificacion','data' => ''))            
            ->add('TxtNombreVisitante', TextType::class, array('label'  => 'NombreVisitante','data' => ''))                                               
            ->add('BtnGuardar',  SubmitType::class, array('label'  => 'Guardar'))                                            
            ->getForm();        
        return $form;
    }
        
}
