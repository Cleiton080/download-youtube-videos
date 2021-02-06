<?php declare(strict_types=1);

namespace App;

use App\Interfaces\VideoService;
use Exception;

class Youtube implements VideoService
{
    protected $url;
    
    protected $videoId;

    private $rawVideoInformation;

    /**
     * Create a new instance of Youtube
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Get the files with all the youtube video information
     * @return string
     */
    private function getVideoInformation(): string
    {
        return 'https://www.youtube.com/get_video_info?video_id=' . $this->videoId;
    } 

    /**
     * Establish connection with the youtube server.
     * @return bool
     */
    public function connect(): bool 
    {
        $this->videoId = $this->getVideoId();

        $content = file_get_contents($this->getVideoInformation());

        $this->rawVideoInformation = urldecode($content);

        // $informationSaved = file_put_contents('tmp/video_info.txt', urldecode($content));

        return !! $this->rawVideoInformation;
    }

    /**
     * Get the video id out of the youtube url
     * @return string
     */
    protected function getVideoId(): string
    {
        $videoIdPattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
        
        preg_match($videoIdPattern, $this->url, $videoId);

        if(empty($videoId)) {
            throw new \Exception('That is an invalid url.', 401);
        }

        return $videoId[1];
    }

    /**
     * Mount the collect that is going to be returned by getVideos eventually
     * @return array
     */
    private function mountVideoCollection(): array
    {
        preg_match('/"formats":(\[.*?\])/m', $this->rawVideoInformation, $maches);
        
        $formats = isset($maches[1]) ? json_decode($maches[1]) : [];

        if(empty($formats))
        {
            throw new Exception('Video was not found.', 404);
        }

        return $formats;

    }

    /**
     * Get a collection with the same video set with different qualities
     * @return array
     */
    public function getVideos(): array
    {
        $videos = $this->mountVideoCollection();
        $collection = [];

        foreach($videos as $video)
        {
            array_push($collection, new Video(
                $video->url,
                $video->mimeType,
                $video->qualityLabel
            ));
        }

        return $collection;
    }
}