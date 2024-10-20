<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;


class AuthorController extends AbstractController
{   private $authors;
    public function __construct(){
            $this->authors=[
                ['id'=>1, 'name'=>'Taha Hussain','nbrBooks'=>300,'picture'=>'images/th.jpeg','email'=>"taah@gmail.com"],
                ['id'=>2, 'name'=>'Victor Hugo','nbrBooks'=>200,'picture'=>'images/vh.jpeg','email'=>"victor@gmail.com"],
            ];
    }
   #[Route("/library",name:"app_library",methods:["GET"])]
    public function index(){
       return $this->render('author/index.html.twig');
   }

   #[Route("/author/{name}",
       name:"app_author",
       methods:["GET"],
       defaults:["name"=>"taha hussain"])]
   public function showAuthor($name){
       return $this->render('author/show.html.twig',
       array(
           'name'=>$name
       ));
   }

   //return list of authors
   #[Route("/list",name:"app_list",methods:["GET"])]
   public function authorList(){

       return $this->render('author/list.html.twig',
       [
           'authors'=>$this->authors
       ]);
   }

   #[Route ("/showDetails/{id}",name:"app_showDetail",methods:["GET"])]
   
    public function showDetailsAction(int $id): Response
    {
       
        $author = null;
        foreach ($this->authors as $a) {
            if ($a['id'] == $id) {
                $author = $a;
                break;
            }
        }
    

        
        if (!$author) {
            throw $this->createNotFoundException('Author not found.');
        }

        
        return $this->render('author/showDetails.html.twig', [
            'author' => $author
        ]);
    }

    #[Route('/author/{id}', name: 'app_showDetail', methods: ['GET'])]
public function showAuthorBooks(Author $author, BookRepository $BookRepository): Response
{
    $Books = $BookRepository->findBy(['author' => $author]);
    return $this->render('author/books.html.twig', [
        'author' => $author,
        'Books' => $Books,
    ]);
}

#[Route('/list/authors/', name: 'app_authors_list', methods: ['GET'])]
public function searchlist(Request $request, AuthorRepository $authorRepository)
{
   // Récupérer les paramètres de recherche
   $minBooks = $request->query->get('minBooks', 9);
   $maxBooks = $request->query->get('maxBooks', 99);
   
   // Récupérer les auteurs selon les critères de recherche
   $authors = $authorRepository->findByBookCountRange($minBooks, $maxBooks);
   
   return $this->render('author/authors_n.html.twig', [
       'authors' => $authors,
       'minBooks' => $minBooks,
       'maxBooks' => $maxBooks,
   ]);
}

#[Route('/list/with_nobooks', name: 'authors_with_no_books')]
public function listAuthorsWithNoBooks(AuthorRepository $authorRepository): Response
{
    // Retrieve authors with 0 books
    $authors = $authorRepository->findBy(['nbrBooks' => 0]);

    return $this->render('author/authors_no_books.html.twig', [
        'authors' => $authors,
    ]);
}
#[Route('/authors/delete_all', name: 'delete_all_authors_with_no_books')]
    public function deleteAllAuthorsWithNoBooks(AuthorRepository $authorRepository, EntityManagerInterface $entityManager): Response
    {
        // Retrieve authors with 0 books
        $authors = $authorRepository->findBy(['nbrBooks' => 0]);

        // Remove each author
        foreach ($authors as $author) {
            $entityManager->remove($author);
        }
        
        $entityManager->flush();

        return $this->redirectToRoute('authors_with_no_books'); // Redirect after deletion
    }

    // In your controller
public function listAuthorsByEmail(AuthorRepository $authorRepository)
{
    $authors = $authorRepository->listAuthorByEmail(); // Explicitly call the method from repository

    return $this->render('author/list.html.twig', [
        'authors' => $authors,
    ]);
}

#[Route('/authors/by_email', name: 'author_by_email')]
    public function listAuthorByEmail(AuthorRepository $authorRepository): Response
    {
        // Fetch the authors ordered by email
        $authors = $authorRepository->listAuthorByEmail();

        // Pass the authors to the Twig template
        return $this->render('author/authors_sorted_by_emails.html.twig', [
            'authors' => $authors,
        ]);
    } 



}


    
    //var_dump($id);
    //die();
    //return $this->render("author/showDetails.html.twig",array());
   //}
