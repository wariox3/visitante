<?php

namespace AppBundle\Controller\Administrador;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\UserType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class UsuarioController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/administrador/usuario", name="administrador_usuario")
     */    
    public function listaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);        
        $this->listar();
        $arUsuarios = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                                       
        return $this->render('AppBundle:Administrador/Usuarios:lista.html.twig', array(
            'form' => $form->createView(),
            'arUsuarios' => $arUsuarios
            ));
    }  
    
    /**
     * @Route("/administrador/usuario/nuevo/{codigoUsuario}", name="administrador_usuario_nuevo")
     */    
    public function nuevoUsuarioAction(Request $request, $codigoUsuario) {
        $em = $this->getDoctrine()->getManager();        
        $arUsuario = new \AppBundle\Entity\User();      
        if($codigoUsuario != 0) {
            $arUsuario = $em->getRepository('AppBundle:User')->find($codigoUsuario);
        }
        $form = $this->createForm(UserType::class, $arUsuario);
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $em->persist($arUsuario);
            $arUsuario = $form->getData();                       
            $arUsuario->setRoles('ROLE_USER');
            if($codigoUsuario == 0) {
                $arUsuario->setPassword(password_hash($arUsuario->getPassword(), PASSWORD_BCRYPT));                    
            }
            
            $em->flush();
            return $this->redirect($this->generateUrl('administrador_usuario'));
                       
        }
        return $this->render('AppBundle:Administrador/Usuarios:nuevo.html.twig', array(
            'form' => $form->createView()
        ));
    }           

    /**
     * @Route("/administrador/usuario/cambiar/clave/{codigoUsuario}", name="administrador_usuario_cambiar_clave")
     */    
    public function cambiarClaveAction(Request $request, $codigoUsuario) {
        $em = $this->getDoctrine()->getManager();        
        $arUsuario = new \AppBundle\Entity\User();              
        $arUsuario = $em->getRepository('AppBundle:User')->find($codigoUsuario);
        $form = $this->createFormBuilder()
            ->add('clave', TextType::class, array('data' => '', 'required' => true))            
            ->add('clave2', TextType::class, array('data' => '', 'required' => true))            
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'))            
            ->getForm();                    
        $form->handleRequest($request);
        if ($form->isValid()) {  
            $strClave = $form->get('clave')->getData();
            $strClave2 = $form->get('clave2')->getData();            
            if($strClave == $strClave2) {
                $strClave = password_hash($strClave, PASSWORD_BCRYPT);
                $arUsuario->setPassword($strClave);
                $em->persist($arUsuario);                        
                $em->flush();
                return $this->redirect($this->generateUrl('administrador_usuario'));
            } else {
                echo "Las claves no coinciden";
            }                                                                                                                  
            
        }
        return $this->render('AppBundle:Administrador/Usuarios:cambiarClave.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                        
        $this->strDqlLista = $em->getRepository('AppBundle:User')->listaDql();  
    }
    
    private function formularioLista() {
        $form = $this->createFormBuilder()                                    
            ->add('TxtNumero', TextType::class, array('label'  => 'Numero','data' => ""))                                                               
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))                                            
            ->getForm();        
        return $form;
    }          
}
