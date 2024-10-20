<?php

namespace App\Repository ; 


use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) //acces doctrine
    {
        parent::__construct($registry, Author::class);
    }

    public function findByBookCountRange(int $minBooks, int $maxBooks)
    {
        $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT a FROM App\Entity\Author a WHERE a.nbrBooks >= :minBooks AND a.nbrBooks <= :maxBooks'
    )
    ->setParameter('minBooks', $minBooks)
    ->setParameter('maxBooks', $maxBooks);

    return $query->getResult();

    }

    // src/Repository/AuthorRepository.php

public function findAuthorsWithNoBooks(): array
{
    return $this->createQueryBuilder('a')
        ->where('a.nbrBooks = :nbrBooks')
        ->setParameter('nbrBooks', 0)
        ->getQuery()
        ->getResult();
}

public function listAuthorByEmail(): array
{
    return $this->findBy([], ['email' => 'ASC']); // Finding all authors and ordering them by email
}


    //    /**
    //     * @return Author[] Returns an array of Author objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Author
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
