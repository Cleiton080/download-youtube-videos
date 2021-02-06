<?php declare(strict_types=1);

namespace App;

class Video
{
    protected $title;

    protected $description;

    protected $thumbnail;

    protected $format;

    protected $quality;

    protected $url;

    /**
     * Start a new video instance
     */
    public function __construct(
        string $url,
        string $format = null,
        string $quality = null,
        string $title = 'Unknown',
        string $description = 'No Description'
    )
    {
        $this->url = $url;
        $this->format = $format;
        $this->quality = $quality;
        $this->title = $title;
        $this->description = $description; 
    }

    /**
     * Download the video
     */
    public function download() 
    {
        
    }
}