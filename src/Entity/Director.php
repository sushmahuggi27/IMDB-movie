<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\ActorsRepository;
use App\Service\EntityManagerInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DirectorRepository")
 * @ORM\Table(name="director",uniqueConstraints={@ORM\UniqueConstraint(columns={"name"})})
 */

class Director
{
    /** 
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(name="id", type="integer")
    */
    private $id;

    /**
     * @ORM\Column(name="name", type="string",length=225)");
     */
    private $name;

    /**
     * @ORM\Column(name="email", type="string",length=225)
     */
    private $email;

    /**
     * @ORM\Column(name="country", type="string",length=225)
     */
    private $country;

    /**
     * @ORM\Column(name="profile", type="string",length=225)
     */
    private $profile;

    /**
     * @ORM\ManyToMany(targetEntity="Movies",mappedBy="director")
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

    public function setEmail(string $email)
    {
        $this->email=$email;
    }
    public function getEmail():?String
    {
        return $this->email;
    }

    public function setCountry(string $country)
    {
        $this->country=$country;
    }
    public function getCountry():?String
    {
        return $this->country;
    }

    public function setProfile(string $profile)
    {
        $this->profile=$profile;
    }
    public function getProfile():?String
    {
        return $this->profile;
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
            $movies->addDirector($this);
        }
    }
    public function removeMovies(Movies $movies)
    {
        if(!$this->movies->removeElement($movies))
        {
            $movies->removeDirector($this);
        }
    }
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'country' => $this->getCountry(),
            'profile' => $this->getProfile()
        ];
    }
}
?>