<?php

/*
 * src/OC/PlatformBundle/Controller/AdvertController.php
 */

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller {

    public function indexAction($page) {

        if ($page < 1) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        // Nombre d'annonces par page
        $nbPerPage = 5;
        
        $listAdverts = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCPlatformBundle:Advert')
            ->getAdverts($page, $nbPerPage); // Afin de faire une pagination on utilise le repository

        // Nombre total de pages
        $nbPages = ceil(count($listAdverts)/$nbPerPage);
        
        // Si la page n'existe pas on affiche une erreur
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }
        
        // On retourne la liste des annonces ainsi que $nbPages et $page pour la pagination
        return $this->render('OCPlatformBundle:Advert:advert.html.twig', array(
                'listAdverts' => $listAdverts,
                'nbPages' => $nbPages,
                'page' => $page
        ));
    }

    public function viewAction($id) {

        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em
            ->getRepository('OCPlatformBundle:Advert')
            ->find($id)
        ;

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        // On avait déjà récupéré la liste des candidatures
        $listApplications = $em
            ->getRepository('OCPlatformBundle:Application')
            ->findByAdvert($advert);

        // On récupère maintenant la liste des AdvertSkill
        $listAdvertSkills = $em
            ->getRepository('OCPlatformBundle:AdvertSkill')
            ->findByAdvert($advert);

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
                'advert' => $advert,
                'listApplications' => $listApplications,
                'listAdvertSkills' => $listAdvertSkills
        ));
    }

    public function addAction(Request $request) {

        if ($request->isMethod('POST')) {
            // Ici, on s'occupera de la création et de la gestion du formulaire

            $request->getSession()->getFlashBag()->add('info', 'Annonce bien enregistrée.');

            // Puis on redirige vers la page de visualisation de cet article
            return $this->redirect($this->generateUrl('oc_platform_view', array('id' => 1)));
        }

        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('OCPlatformBundle:Advert:add.html.twig');
    }

    public function editAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
                'advert' => $advert
        ));
    }

    public function deleteAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        if ($request->isMethod('POST')) {
            
            $request->getSession()->getFlashBag()->add('info', 'Annonce bien supprimée.');

            // Puis on redirige vers l'accueil
            return $this->redirect($this->generateUrl('oc_platform_home'));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de delete
        return $this->render('OCPlatformBundle:Advert:delete.html.twig', array(
                'advert' => $advert
        ));
    }

    public function menuAction($limit = 3) {

        $listAdverts = $this->getDoctrine()
            ->getManager()
            ->getRepository('OCPlatformBundle:Advert')
            ->findBy(
            array(), // Pas de critère
            array('date' => 'desc'), // On trie par date décroissante
            $limit, // On sélectionne $limit annonces
            0                        // À partir du premier
        );

        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
                'listAdverts' => $listAdverts
        ));
    }

    public function listAction($limit = 3) {
        
        $listAdverts = $this->getDoctrine()
            ->getManager()
            ->getRepository('OCPlatformBundle:Advert')
            ->findBy(
            array(), // Pas de critère
            array('date' => 'desc'), // On trie par date décroissante
            $limit, // On sélectionne $limit annonces
            0                        // À partir du premier
        );

        return $this->render('OCPlatformBundle:Advert:list.html.twig', array(
                'listAdverts' => $listAdverts
        ));
    }

}
