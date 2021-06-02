<?php

namespace App\Entity;

use App\Repository\ReviewsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReviewsRepository")
 */
class Reviews
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Range(
     *      min = 1, max = 10,  
     *      notInRangeMessage = "You must enter rating between {{ min }} and {{ max }}")
     */
    private $rating;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reviews;

    /**
     * @ORM\ManyToOne(targetEntity=Movies::class, inversedBy="reviews")
     */
    private $movies;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reviews")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(string $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getReviews(): ?string
    {
        return $this->reviews;
    }

    public function setReviews(string $reviews): self
    {
        $this->reviews = $reviews;

        return $this;
    }

    public function getMovies(): ?Movies
    {
        return $this->movies;
    }

    public function setMovies(?Movies $movies): self
    {
        $this->movies = $movies;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
