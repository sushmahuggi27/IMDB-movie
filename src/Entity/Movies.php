<?php 

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\MoviesRepository;
use App\Service\EntityManagerInterface;


/**
 * @ORM\Entity(repositoryClass="App\Repository\MoviesRepository")
 * @ORM\Table(name="movies",uniqueConstraints={@ORM\UniqueConstraint(columns={"title"})})
 */
class Movies
{
  /** 
  * @ORM\Id
  * @ORM\GeneratedValue
  * @ORM\Column(name="id", type="integer")
   */
  private $id;

  /**
   * @ORM\Column(name="title", type="string",length=255)
   */

  private $title;

  /**
   * @ORM\Column(name="rel_date", type="string",length=10)
   */
  private $relDate;

    /**
   * @ORM\Column(name="language", type="string",length=255)
   */
  private $language;

  /**
   * @ORM\Column(name="type", type="string",length=255)
   */
  private $type;

  /**
   * @ORM\Column(name="description", type="string",length=255)
   */
  private $description;

  /**
   * @ORM\Column(name="poster", type="string",length=255)
   */
  private $poster;

  /**
   * @ORM\ManyToMany(targetEntity="Director",inversedBy="movies")
   * @ORM\JoinTable(name="movies_director_index",
   * joinColumns={@ORM\JoinColumn(name="movies_id",referencedColumnName="id")},
   * inverseJoinColumns={@ORM\JoinColumn(name="director_id",referencedColumnName="id")})
   */
  private $director;

  /**
   * @ORM\ManyToMany(targetEntity="Actors",inversedBy="movies")
   * @ORM\JoinTable(name="movies_actors_index",
   * joinColumns={@ORM\JoinColumn(name="movies_id",referencedColumnName="id")},
   * inverseJoinColumns={@ORM\JoinColumn(name="actors_id",referencedColumnName="id")})
   */
  private $actors;

  /**
   * @ORM\ManyToMany(targetEntity="Production",inversedBy="movies")
   * @ORM\JoinTable(name="movies_production_index",
   * joinColumns={@ORM\JoinColumn(name="movies_id",referencedColumnName="id")},
   * inverseJoinColumns={@ORM\JoinColumn(name="production_id",referencedColumnName="id")})
   */
  private $production;

  /**
   * @ORM\ManyToMany(targetEntity="Media",inversedBy="movies")
   * @ORM\JoinTable(name="movies_media_index",
   * joinColumns={@ORM\JoinColumn(name="movies_id",referencedColumnName="id")},
   * inverseJoinColumns={@ORM\JoinColumn(name="media_id",referencedColumnName="id")})
   */
  private $media;

  /**
   * @ORM\OneToMany(targetEntity=Reviews::class, mappedBy="movies")
   */
  private $reviews;

  // /**
  //  * @ORM\ManyToOne(targetEntity="Review",inversedBy="movies")
  //  * @ORM\JoinTable(name="review",
  //  * joinColumns={@ORM\JoinColumn(name="movies_id",referencedColumnName="id")})
  //  */
  // private $review;

  public function __construct()
  {
      $this->actors = new \Doctrine\Common\Collections\ArrayCollection();
      $this->production = new \Doctrine\Common\Collections\ArrayCollection();
      $this->director = new \Doctrine\Common\Collections\ArrayCollection();
      $this->media = new \Doctrine\Common\Collections\ArrayCollection();
      $this->review = new \Doctrine\Common\Collections\ArrayCollection();
      $this->reviews = new \Doctrine\Common\Collections\ArrayCollection();
  }

  public function getId():?int
  {
    return $this->id;
  }
   
  public function setTitle(string $title)
  {
    $this->title=$title;
  } 
  public function getTitle():?String
  {
    return $this->title;
  }
     
   
  public function setRelDate(string $relDate)
  {
    $this->relDate=$relDate; 
  } 
  public function getRelDate():?String
  {
    return $this->relDate;
  }
  
  
  public function setLanguage(string $language)
  {
    $this->language=$language; 
  }
  public function getLanguage():?String
  {
    return $this->language;
  }
  
  
  public function setType(string $type)
  {
    $this->type=$type;    
  }
  public function getType():?String
  {
    return $this->type;
  }


  public function setDescription(string $description)
  {
    $this->description=$description;    
  }
  public function getDescription():?String
  {
    return $this->description;
  } 

  public function setPoster(?string $poster)
  {
    $this->poster=$poster;    
  }
  public function getPoster():?String
  {
    return $this->poster;
  } 
  

  /**
   * @return Collection|Actors[]
   */
  public function getActors():Collection
  {
      return $this->actors;
  }

  public function addActors(Actors $actors)
  {
    if (!$this->actors->contains($actors)) 
    {
        $this->actors[] = $actors;
    }  
  }

  public function removeActors(Actors $actors)
  {
    $this->actors->removeElement($actors); 
  }

  /**
   * @return Collection|Production[]
   */
  public function getProduction(): Collection
  {
    return $this->production;
  }

  public function addProduction(Production $production)
  {
    if (!$this->production->contains($production)) 
    {
        $this->production[] = $production;
    }  
  }

  public function removeProduction(Production $production)
  {
    $this->production->removeElement($production); 
  }


  /**
   * @return Collection|Director[]
   */
  public function getDirector(): Collection
  {
    return $this->director;
  }
  public function addDirector(Director $director)
  {
    if (!$this->director->contains($director)) 
    {
        $this->director[] = $director;
    }  
  }
  public function removeDirector(Director $director)
  {
    $this->director->removeElement($director); 
  }

  /**
   * @return Collection|Media[]
   */
  public function getMedia(): Collection
  {
    return $this->media;
  }
  public function addMedia(Media $media)
  {
    if (!$this->media->contains($media)) 
    {
        $this->media[] = $media;
    }  
  }
  public function removeMedia(Media $media)
  {
    $this->media->removeElement($media); 
  }

  /**
   * @return Collection|Reviews[]
   */
  public function getReview(): Collection
  {
    return $this->reviews;
  }
  public function addReview(Reviews $reviews)
  {
    if (!$this->reviews->contains($reviews)) 
    {
        $this->reviews[] = $reviews;
    }  
  }
  public function removeReview(Reviews $reviews)
  {
    $this->reviews->removeElement($reviews); 
  }

  public function toArray()
  {
      return [
          'id' => $this->getId(),
          'title' =>$this->getTitle(),
          'relDate' =>$this->getRelDate(),
          'language' => $this->getLanguage(),
          'type' =>$this->getType(),
          'description' =>$this->getDescription(),
          'poster' => $this->getPoster()
      ];
  }
}
?>