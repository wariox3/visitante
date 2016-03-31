<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SeguridadController extends Controller {

    /**
     * @Route("/login", name="login")
     */                      
    public function loginAction() {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('AppBundle:Seguridad:login.html.twig', array(
                    // last username entered by the user
                    'last_username' => $lastUsername,
                    'error' => $error));
    }
    
    /**
     * @Route("/logout", name="logout")
     */ 
    public function logoutAction() {
        
    }    

}
