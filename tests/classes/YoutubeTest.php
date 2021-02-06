<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class YoutubeTest extends TestCase {
    public function testShouldConnectOnYoutube(): void 
    {
        $youtube = new App\Youtube('https://www.youtube.com/watch?v=FLtqAi7WNBY');
        
        $connection = $youtube->connect();

        $this->assertTrue($connection, 'Should establish connection with the server.');
    }

    public function testShouldGetTheIdOutOfTheUrl(): void 
    {
        $youtube = new App\Youtube('https://www.youtube.com/watch?v=FLtqAi7WNBY');

        $youtube->connect();

        $videoId = $this->invokeMethod($youtube, 'getVideoId');

        $this->assertSame('FLtqAi7WNBY', $videoId, 'Should get the video id out of the youtube url.');
    }

    public function testShouldThrowAnExceptionInvalidUrl(): void
    {
        $this->expectExceptionCode(401);
        $this->expectExceptionMessage('That is an invalid url.');
        
        $youtube = new App\Youtube('https://www.youtube.com/watch');

        $youtube->connect();

        $this->invokeMethod($youtube, 'getVideoId');
    }

    public function testShouldGetACollectionOfVideos(): void
    {
        $youtube = new App\Youtube('https://www.youtube.com/watch?v=FLtqAi7WNBY');

        $youtube->connect();
        $videos = $youtube->getVideos();

        $this->assertIsArray($videos, 'Get a collection of youtube videos.');
        $this->assertContainsOnlyInstancesOf('App\Video', $videos);
    }

    public function testShouldThrowAnExceptionVideoNotFound(): void
    {
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('Video was not found.');

        $youtube = new App\Youtube('https://www.youtube.com/watch?v=FLtqAi7WNfY');

        $youtube->connect();
        $youtube->getVideos();
    }

    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}