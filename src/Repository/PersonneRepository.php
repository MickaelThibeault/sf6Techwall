<?php

namespace App\Repository;

use App\Entity\Personne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Personne>
 *
 * @method Personne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personne[]    findAll()
 * @method Personne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    private function addIntervalAge(QueryBuilder $qb, $ageMin, $ageMax)
    {
        $qb->andWhere('p.age >= :ageMin and p.age <= :ageMax')
        ->setParameters(['ageMin'=> $ageMin, 'ageMax'=> $ageMax]);
    }

    /**
     * @return Personne[] Returns an array of Personne objects
     */
    public function findPersonneByAgeInterval($ageMin, $ageMax): array
    {
        $qb = $this->createQueryBuilder('p');
        $this->addIntervalAge($qb, $ageMin, $ageMax);
        return $qb->getQuery()
            ->getResult()
        ;
    }

    public function statsPersonnesByAgeInterval($ageMin, $ageMax): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('avg(p.age) as ageMoyen, count(p.id) as nombrePersonnes');
        $this->addIntervalAge($qb, $ageMin, $ageMax);

        return $qb->getQuery()
            ->getScalarResult()
        ;
    }

//    public function findOneBySomeField($value): ?Personne
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
