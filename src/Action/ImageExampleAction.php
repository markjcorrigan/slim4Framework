<?php

namespace App\Action;

use Intervention\Image\ImageManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class ImageExampleAction
{
private StreamFactoryInterface $streamFactory;
private ImageManager $imageManager;

public function __construct(StreamFactoryInterface $streamFactory, ImageManager $imageManager)
{
$this->streamFactory = $streamFactory;
$this->imageManager = $imageManager;
}

public function __invoke(
ServerRequestInterface $request,
ResponseInterface $response
): ResponseInterface {
// Create image in memory
$image = $this->imageManager->canvas(800, 600, '#719e40');

// Encode image into given format (PNG) and quality (100%)
$data = $image->encode('png', 100)->getEncoded();

// Set content mime type
$response = $response->withHeader('Content-Type', 'image/png');

// Output image as stream
return $response->withBody($this->streamFactory->createStream($data));
}
}