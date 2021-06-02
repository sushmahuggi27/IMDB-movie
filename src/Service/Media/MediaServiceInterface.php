<?php

namespace App\Service\Media;

use App\Controller\MediaController;
use App\Entity\Media;
use App\Service\Media\MediaService;

Interface MediaServiceInterface
{
    public function getAllMedia(): Array;
    public function getMedia($id): Media;
    public function addMedia(Media $media): Media;
    public function deleteMedia($id);
    public function updateMedia(Media $media): Media;
}
