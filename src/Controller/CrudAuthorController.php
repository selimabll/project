<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request; // Make sure this is added

#[Route('/crud')]
class CrudAuthorController extends AbstractController
{
    #[Route('/list', name: 'app_list_author')]
    public function list(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findAll();
        $totalBooks = 0; // Initialize total Books count
    
        // Calculate total Books if you have a relationship in the Author entity
        foreach ($authors as $author) {
            $totalBooks += $author->getNbrBooks(); // Assuming nbrBooks is a field in Author entity
        }

        return $this->render('crud_author/list.html.twig', [
            'authors' => $authors,
            'total_Books' => $totalBooks, // Pass total Books count to the template
        ]);
    }

    // Method to insert an author into the database
    #[Route("/new", name: "app_new_author")]
    public function newAuthor(Request $request, ManagerRegistry $doctrine): Response
{
    // Check if it's a POST request (form submission)
    if ($request->isMethod('POST')) {
        // Get the data from the form
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $nbrBooks = $request->request->get('nbrBooks');

        // Create a new Author entity
        $author = new Author();
        $author->setName($name);
        $author->setEmail($email);
        $author->setNbrBooks($nbrBooks);

        // Get the Doctrine entity manager
        $entityManager = $doctrine->getManager();

        // Persist the new author to the database
        $entityManager->persist($author);
        $entityManager->flush();

        // Redirect back to the list of authors or some success page
        return $this->redirectToRoute('app_list_author');
    }

    // If not a POST request, render the form template
    return $this->render('crud_author/new.html.twig'); // Ensure you have a new.html.twig template for the form
}

    // Method to delete an author
    #[Route("/delete/{id}", name: "app_delete_author")]
    public function deleteAuthor(ManagerRegistry $doctrine, AuthorRepository $authorRepository, $id): Response
    {
        $author = $authorRepository->find($id);

        if (!$author) {
            $this->addFlash('error', 'Author not found.');
        } else {
            $em = $doctrine->getManager();
            $em->remove($author);
            $em->flush();
            $this->addFlash('success', 'Author deleted successfully.');
        }

        return $this->redirectToRoute('app_list_author');
    }

    // Method to update an author
    #[Route("/update/{id}", name: "app_update_author")]
    public function updateAuthor(ManagerRegistry $doctrine, AuthorRepository $authorRepository, $id): Response
    {
        $author = $authorRepository->find($id);

        if (!$author) {
            $this->addFlash('error', 'Author not found.');
        } else {
            $em = $doctrine->getManager();
            // Here you would typically update the author properties based on form input
            $author->setName("Victor Hugo"); // Example update
            $em->flush();
            $this->addFlash('success', 'Author updated successfully.');
        }

        return $this->redirectToRoute('app_list_author');
    }
}
