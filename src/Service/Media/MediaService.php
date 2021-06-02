<?php

namespace App\Service\Media;

use App\Entity\Media;
use App\Controller\MediaController;
use App\Service\Media\MediaServiceInterface;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class MediaService implements MediaServiceInterface
{
    /**
     * @var MediaRepository
     */
    private $mediaRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    function __construct(
        MediaRepository $mediaRepository, 
        EntityManagerInterface $entityManager)
    {
        $this->mediaRepository = $mediaRepository;
        $this->entityManager = $entityManager;
    }

    public function getAllMedia(): Array 
    {
        return $this->mediaRepository->findAll();
    }

    public function getMedia($id): Media
    {
        $media = $this->mediaRepository->find($id);
        if($media == null) {
            throw new Exception("Media details with respect to the id does not exist");
        } else {
        return $media;
        }
    }

    public function addMedia(Media $media): Media
    {
        $this->entityManager->persist($media);
        $this->entityManager->flush();
        $media = $this->mediaRepository->findOneBy(["id" => $media->getId()]);
        return $media;
    }
    
    public function deleteMedia($id)
    {
        $medias = $this->mediaRepository->findOneBy(['id' =>$id]);
        $this->entityManager->remove($medias);
        $this->entityManager->flush();   
    }

    public function updateMedia(Media $media): Media
    {
        $this->entityManager->persist($media);
        $this->entityManager->flush();
        return $media;
    }
}

