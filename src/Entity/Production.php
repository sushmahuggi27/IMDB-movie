<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Service\EntityManagerInterface;
use App\Service\ProductionService;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductionRepository")
 * @ORM\Table(name="production",uniqueConstraints={@ORM\UniqueConstraint(columns={"name"})})
 */

class Production
{
    /** 
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(name="id", type="integer")
    */
    private $id;

    /**
     * @ORM\Column(name="name", type="string",length=225)
     */
    private $name;

    /**
     * @ORM\Column(name="country", type="string",length=225)
     */
    private $country;

    /**
     * @ORM\Column(name="description", type="string",length=255)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="Movies",mappedBy="production")
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

    public function setName(string $name)
    {
        $this->name=$name;
    }
    public function getName():?String
    {
        return $this->name;
    }

    public function setCountry(string $country)
    {
        $this->country=$country;
    }
    public function getCountry():?String
    {
        return $this->country;
    }

    public function setDescription(string $description)
    {
        $this->description=$description;    
    }
    public function getDescription():?String
    {
        return $this->description;
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
        if(!$this->movies->contains($movies))
        {
            $this->movies[]=$movies;
            $movies->addProduction($this);
        }
    }
    public function removeMovies(Movies $movies)
    {
        if($this->movies->removeElement($movies))
        {
            $movies->removeProduction($this);
        }
    }
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'country' => $this->getCountry(),
            'description' => $this->getDescription()
        ];
    }
}
?>