<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\MediaRepository;
use App\Service\EntityManagerInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 * @ORM\Table(name="media")
 */

class Media
{
    /** 
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(name="id", type="integer")
    */
    private $id;

    /**
     * @ORM\Column(name="image", type="string",length=225)
     */
    private $image;

    /**
     * @ORM\Column(name="vedio", type="string",length=225)
     */
    private $vedio;

    /**
     * @ORM\Column(name="name", type="string",length=225)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Movies",mappedBy="media")
     */
    private $movies;

    public function __construct()
    {
        $this->movies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId():?int
    {
        return $this->id;
    }

    public function setImage(?string $image)
    {
        $this->image=$image;
    }
    public function getImage():?String
    {
        return $this->image;
    }

    public function setVedio(string $vedio)
    {
        $this->vedio=$vedio;
    }
    public function getVedio():?String
    {
        return $this->vedio;
    }

    public function setName(string $name)
    {
        $this->name=$name;
    }
    public function getName():?String
    {
        return $this->name;
    }

    /**
     * @return Collection|Movies[]
     */
    public function getMovies():Collection
    {
        return $this->movies;
    }
    public function addMovies(Movies $movies)
    {
        if($this->movies->contains($movies))
        {
            $this->movies[]=$movies;
            $movies->addMedia($this);
        }
    }
    public function removeMovies(Movies $movies)
    {
        if($this->movies->removeElement($movies))
        {
            $movies->removeMedia($this);
        }
    }
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'image' => $this->getImage(),
            'vedio' => $this->getVedio()
        ];
    }
}
?>