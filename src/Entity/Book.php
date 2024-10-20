<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $nbrPages = null;

    #[ORM\ManyToOne(inversedBy: 'book')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Author $author = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;



    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Datepublish = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\Column]
    private ?int $Published = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getNbrPages(): ?int
    {
        return $this->nbrPages;
    }

    public function setNbrPages(int $nbrPages): static
    {
        $this->nbrPages = $nbrPages;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    

    public function getDatepublish(): ?\DateTimeInterface
    {
        return $this->Datepublish;
    }

    public function setDatepublish(\DateTimeInterface $Datepublish): static
    {
        $this->Datepublish = $Datepublish;

        return $this;
    }

    public function getcategory(): ?string
    {
        return $this->category;
    }

    public function setcategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getPublished(): ?int
    {
        return $this->Published;
    }

    public function setPublished(int $Published): static
    {
        $this->Published = $Published;

        return $this;
    }
}