<?php declare(strict_types=1);

namespace App\Interfaces;

interface VideoService
{
    /**
     * Connect with the video service
     * @return bool
     */
    public function connect(): bool;

    /**
     * Get a collection of videos
     * @return array
     */
    public function getVideos(): array;
}