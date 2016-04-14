<?php

/*
 * src/OC/PlatformBundle/Controller/AdvertController.php
 */

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\AdvertSkill;
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
            ->getAdverts($page, $nbPerPage);

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
//        $listApplications = $em
//            ->getRepository('OCPlatformBundle:Application')
//            ->findBy(array('advert' => $advert))
//        ;
        $listApplications = $em
            ->getRepository('OCPlatformBundle:Application')
            ->findByAdvert($advert);

        // On récupère maintenant la liste des AdvertSkill
//        $listAdvertSkills = $em
//            ->getRepository('OCPlatformBundle:AdvertSkill')
//            ->findBy(array('advert' => $advert))
//        ;
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

//        // On récupère l'EntityManager
//        $em = $this->getDoctrine()->getManager();
//
//        // Création de l'entité Advert
//        $advert = new Advert();
//        $advert->setTitle('Formateur Web-développement.');
//        $advert->setAuthor('Sarah');
//        $advert->setContent("L'école Simplon.co de Boulogne sur Mer recherche un formateur expérimenté.");
//
//        // On récupère toutes les compétences possibles
//        $listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll();
//
//        // Pour chaque compétence
//        foreach ($listSkills as $skill) {
//            // On crée une nouvelle « relation entre 1 annonce et 1 compétence »
//            $advertSkill = new AdvertSkill();
//
//            // On la lie à l'annonce, qui est ici toujours la même
//            $advertSkill->setAdvert($advert);
//            // On la lie à la compétence, qui change ici dans la boucle foreach
//            $advertSkill->setSkill($skill);
//
//            // Arbitrairement, on dit que chaque compétence est requise au niveau 'Expert'
//            $advertSkill->setLevel('Expert');
//
//            // Et bien sûr, on persiste cette entité de relation, propriétaire des deux autres relations
//            $em->persist($advertSkill);
//        }
//
//        // Doctrine ne connait pas encore l'entité $advert. Si vous n'avez pas définit la relation AdvertSkill
//        // avec un cascade persist (ce qui est le cas si vous avez utilisé mon code), alors on doit persister $advert
//        $em->persist($advert);
//
//        // On déclenche l'enregistrement
//        $em->flush();
//
//        // Reste de la méthode qu'on avait déjà écrit
//        if ($request->isMethod('POST')) {
//            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
//            return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));
//        }
//
//        return $this->render('OCPlatformBundle:Advert:add.html.twig');
        // La gestion d'un formulaire est particulière, mais l'idée est la suivante :

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

        // La méthode findAll retourne toutes les catégories de la base de données
//        $listCategories = $em->getRepository('OCPlatformBundle:Category')->findAll();
        // On boucle sur les catégories pour les lier à l'annonce
//        foreach ($listCategories as $category) {
//            $advert->addCategory($category);
//        }
        // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
        // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine
        // Étape 2 : On déclenche l'enregistrement
//        $em->flush();
//
//        if ($request->isMethod('POST')) {
//            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');
//
//            return $this->redirectToRoute('oc_platform_view', array('id' => $id));
//        }

        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
                'advert' => $advert
        ));
    }

    public function deleteAction($id) {

        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        // On boucle sur les catégories de l'annonce pour les supprimer
//        foreach ($advert->getCategories() as $category) {
//            $advert->removeCategory($category);
//        }
//
//        // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
//        // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine
//        // On déclenche la modification
//        $em->flush();
//
//        return $this->render('OCPlatformBundle:Advert:delete.html.twig');

        if ($request->isMethod('POST')) {
            // Si la requête est en POST, on deletea l'article

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
//        // Récupération de l'EntityManager
//        $em = $this->getDoctrine()->getManager();
//
//        $repo = $em->getRepository('OCPlatformBundle:Advert');
//
//        // Récupération des trois derniers adverts
//        $listAdverts = $repo->getAdvertByDate($limit);
//
//        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
//                'listAdverts' => $listAdverts
//        ));

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

//        // Récupération de l'EntityManager
//        $em = $this->getDoctrine()->getManager();
//
//        $repo = $em
//            ->getRepository('OCPlatformBundle:Advert');
//
//        // Récupération des trois derniers adverts
//        $listAdverts = $repo->getAdvertByDate($limit);
//
//
//        return $this->render('OCPlatformBundle:Advert:list.html.twig', array(
//                'listAdverts' => $listAdverts
//        ));

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
