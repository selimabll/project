<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: 'integer')] // Ensure this is correct
    private ?int $nbrBooks = null;


    // Change this property to hold multiple books
    #[ORM\OneToMany(mappedBy: 'Author', targetEntity: Book::class)]
    private Collection $Book;

    public function __construct() {
        $this->Book = new ArrayCollection(); // Initialize the collection
    }
    public function getBooks(): Collection
    {
        return $this->Book;
    }

    public function setBooks(Collection $Book): static
    {
        $this->Book = $Book;

        return $this;
    }
    
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email; 
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
    public function getNbrBooks(): ?int
    {
        return $this->nbrBooks;
    }

    public function setNbrBooks(int $nbrBooks): static
    {
        $this->nbrBooks = $nbrBooks;
        return $this;
    }

    

    
}
