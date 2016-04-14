<?php

// src/OC/PlatformBundle/Entity/AdvertRepository.php

namespace OC\PlatformBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdvertRepository extends EntityRepository {


    public function getAdverts($page, $nbPerPage) {
        $q = $this->createQueryBuilder('a')
            // Jointure sur Image
            ->leftJoin('a.image', 'i')
            ->addSelect('i')
            // Jointure sur Category
            ->leftJoin('a.categories', 'c')
            ->addSelect('c')
            ->orderBy('a.date', 'DESC')
            ->getQuery();

        // Permet de faire une pagination
        $q
            // On définit l'annonce à partir de laquelle commencer la liste
            ->setFirstResult(($page - 1) * $nbPerPage)
            // Ainsi que le nombre d'annonce à afficher sur une page
            ->setMaxResults($nbPerPage)
        ;

        // Enfin, on retourne l'objet Paginator correspondant à la requête construite
        // (n'oubliez pas le use correspondant en début de fichier)
        return new Paginator($q, true);
    }

    public function getAdvertWithCategories(array $categoryNames) {
        $qb = $this->createQueryBuilder('a');

        // On fait une jointure avec l'entité Category avec pour alias « c »
        $qb
            ->join('a.categories', 'c')
            ->addSelect('c')
        ;

        // Puis on filtre sur le nom des catégories à l'aide d'un IN
        $qb->where($qb->expr()->in('c.name', $categoryNames));
        // La syntaxe du IN et d'autres expressions se trouve dans la documentation Doctrine
        // Enfin, on retourne le résultat
        return $qb
                ->getQuery()
                ->getResult()
        ;
    }

    public function whereCurrentYear(QueryBuilder $qb) {
        $qb
            ->andWhere('a.date BETWEEN :start AND :end')
            ->setParameter('start', new \Datetime(date('Y') . '-01-01'))  // Date entre le 1er janvier de cette année
            ->setParameter('end', new \Datetime(date('Y') . '-12-31'))  // Et le 31 décembre de cette année
        ;
    }

}
