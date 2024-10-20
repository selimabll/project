<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }
   

    public function countBooksByCategory(string $category): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager
            ->createQuery('SELECT COUNT(b.id) FROM App\Entity\Book b WHERE b.category LIKE :category')
            ->setParameter('category', 'Romance');
        
        return (int) $query->getSingleScalarResult();
    }

    public function findBooksPublishedBetweenDates(\DateTime $startDate, \DateTime $endDate)
    {
        $entityManager = $this->getEntityManager();

        // DQL query to get Books between the two dates
        $query = $entityManager->createQuery(
            'SELECT b FROM App\Entity\Book b WHERE b.Datepublish BETWEEN :startDate AND :endDate'
        )
        ->setParameter('startDate', $startDate)
        ->setParameter('endDate', $endDate);

        return $query->getResult();
    }
    

}
