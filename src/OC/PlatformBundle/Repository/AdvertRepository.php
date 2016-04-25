<?php

// src/OC/PlatformBundle/Repository/AdvertRepository.php

namespace OC\PlatformBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdvertRepository extends EntityRepository {


    public function getAdverts($page, $nbPerPage) {
        $q = $this->createQueryBuilder('a')
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
