<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;


/**
 * @extends ServiceEntityRepository<Offre>
 *
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }

    

    public function getOffresPaginator(int $page, int $limit= 6):array
        {
            $limit = abs($limit);

            $result = [];

            $query = $this->getEntityManager()->createQueryBuilder()
            ->select('o')
            ->from('App\Entity\Offre','o')
            ->setMaxResults($limit)
            ->setFirstResult(($page * $limit)- $limit);

            $paginator = new Paginator($query);

            $data = $paginator->getQuery()->getResult();

            if (empty($data)){

                return $result;
            }

            // Calcul du nombre de page
            $pages = ceil($paginator->count()/ $limit);

            // On remplit le tableau
            $result ['data'] = $data;
            $result ['pages'] = $pages;
            $result ['page'] = $page;
            $result ['limit'] = $limit;
    
            return $result;
        }

//    /**
//     * @return Offre[] Returns an array of Offre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offre
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
