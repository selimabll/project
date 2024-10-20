<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/crud/Book')]
class CrudBookController extends AbstractController
{
    #[Route('/', name: 'app_crud_Book')]
    public function index(BookRepository $repository,AuthorRepository $authorRep, Request $request): Response
    {   $authorName=$request->query->get('authorName');
        if($authorName==''){
            $Books=$repository->findAll();
        }else{
            //search author by AuthorName
              $author=$authorRep->findOneBy(['name'=>$authorName]);
            //Books by author Name
            //repositoryBook => findByAuthor
            $Books=$repository->findByAuthor($author);
            if(count($Books)==0){
                return  new Response('No Books found');
            }
        }
        return $this->render('crud_Book/index.html.twig', [
            'Books' => $Books,
            'controller_name' => 'CrudBookController',
        ]);
    }

    #[Route('/new', name:'app_new_Book')]
  public function newBook(Request $request,ManagerRegistry $doctrine):Response
    {  //1. instance Book
        $Book= new Book();
        //2.create interface form
        $form=$this->createForm(BookType::class,$Book);
        //handle data from
        $form=$form->handleRequest($request);

        //..validation data
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$doctrine->getManager();
            $em->persist($Book);
            $em->flush();
            return $this->redirectToRoute('app_crud_Book');
        }

        return $this->render('crud_Book/form.html.twig',
        ['form'=>$form->createView()]);
    }


    #[Route('/edit/{id}', name:'app_editBook')]
    public function editBook(Request $request,ManagerRegistry $doctrine,Book $Book):Response
    {

        //2.create interface form
        $form=$this->createForm(BookType::class,$Book);
        //handle data from
        $form=$form->handleRequest($request);

        //..validation data
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_crud_Book');
        }

        return $this->render('crud_Book/form.html.twig',
            ['form'=>$form->createView()]);
    }

    #[Route('/Book/delete/{id}', name: 'app_delete_Book')]
    public function delete(EntityManagerInterface $entityManager, $id): Response
    {
        $Book = $entityManager->getRepository(Book::class)->find($id);

        if (!$Book) {
            throw $this->createNotFoundException('Book not found.');
        }

        $entityManager->remove($Book);
        $entityManager->flush();

        // Rediriger vers la page d'index des livres avec un message de succès
        $this->addFlash('success', 'Book has been deleted.');

        return $this->redirectToRoute('app_crud_Book'); // Remplacez 'app_crud_Book' par le nom de votre route d'index si nécessaire
    }




}