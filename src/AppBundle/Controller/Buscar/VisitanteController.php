<?php

namespace AppBundle\Controller\Buscar;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class VisitanteController extends Controller
{
    var $strDqlLista = "";     
    
    /**
     * @Route("/buscar/visitante/{campoCodigo}/{campoNombre}", name="buscar_visitante")
     */    
    public function listaAction(Request $request, $campoCodigo,$campoNombre) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista($request->getSession());
        $form->handleRequest($request);
        $this->lista($request->getSession());
        if ($form->isValid()) {            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form, $request->getSession());
                $this->lista($request->getSession());
            }
        }
        $arVisitantes = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('AppBundle:Buscar:visitante.html.twig', array(
            'arVisitantes' => $arVisitantes,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
            ));
    }        
    
    private function lista($session) {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('AppBundle:Visitante')->listaDQL(
                $session->get('filtroCodigoGrupo'),
                $session->get('filtroIdentificacion'),
                $session->get('filtroNombreVisitante')
                );
    }       
    
    private function formularioLista($session) {   
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
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
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
          
}
