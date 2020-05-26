<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function findAlerts()
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.alert = true')
            ->orderBy('m.date_added', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }
    
    public function findRecent()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.date_added', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult();
    }
    
    public function findMostViewed()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.views', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult();
    }
    
    public function findByCategory($id, $total = null)
    {
        $qb = $this->createQueryBuilder('m')
            ->where(':category MEMBER OF m.category')
            ->setParameters(['category' => $id]);
        
        if (!empty($total)) {
            $qb = $qb->setMaxResults($total);
        }
        
        return $qb
            ->getQuery()
            ->getResult();
    }
}
