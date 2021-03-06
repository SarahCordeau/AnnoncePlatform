<?php

/*
 * src\OC\CoreBundle\Controller\CoreController.php
 */
namespace OC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller
{
    public function indexAction()
    {
        
        return $this->render('OCCoreBundle:Core:home.html.twig');
    }
    
    public function contactAction(Request $request) {
        $session = $request->getSession();
        $session->getFlashBag()->add('info', 'La page de contact n’est pas encore disponible, merci de revenir plus tard.');
        
        return $this->redirectToRoute('oc_core_homepage', array('page' => 1));
    }
    
    public function loginAction() {
        return $this->render('OCCoreBundle:Core:login.html.twig');
    }
    
    public function postAction(Request $request) {
        $session = $request->getSession();
        $session->getFlashBag()->add('warning', 'Vous devez être connecté pour poster.');
        
        return $this->redirectToRoute('oc_core_homepage', array('page' => 1));
    }
}
