<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    private $Book;

    public function __construct(){
            $this->Book=[
                ['ref'=>1, 'title'=>'Bookb','nbrPage'=>300,'picture'=>'images/th.jpeg'],
                ['ref'=>2, 'title'=>'Victord','nbrPage'=>200,'picture'=>'images/vh.jpeg'],
            ];
    }
    #[Route('/Book', name: 'app_Book')]
    public function index(): Response
    {
        return $this->render('Book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    public function new(ManagerRegistry $doctrine):Response{
        $Book=new Book();
        $Book->setTitle('Book&');
        $Book->setNbrPages(30);

        $em=$doctrine->getManager();
        $em->persist($Book); //commit
        $em->flush();
        return $this->redirectToRoute(route:'app_list_Book');


    

    }
    #[Route('/Book/count/', name: 'app_search_category')]
    public function countCategoryBooks(BookRepository $BookRepository): Response
    {
        // Get the count of Books in the category "Romance"
        $count = $BookRepository->countBooksByCategory('Romance');

        // Render the result in a template (Twig)
        return $this->render('book/count.html.twig', [
            'category' => 'Romance',
            'count' => $count,
        ]);
    }
    #[Route('Book/dates/', name: 'Books_between_dates')]
    public function listBooksBetweenDates(BookRepository $BookRepository): Response
    {
        // Define the start and end dates
        $startDate = new \DateTime('2014-01-01');
        $endDate = new \DateTime('2018-12-31');

        // Call the repository method to get Books between the dates
        $Book = $BookRepository->findBooksPublishedBetweenDates($startDate, $endDate);

        // Render the list in the Twig template
        return $this->render('Book/list_between_dates.html.twig', [
            'Book' => $Book,
        ]);
    }

    
    
    



    
}
