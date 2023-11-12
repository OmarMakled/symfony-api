<?php

namespace App\Tests\Service\Uploader;

use App\Service\Uploader\Photo;
use App\Service\Uploader\PhotoUploader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class PhotoUploaderTest extends TestCase
{
    private const UPLOAD_DIR = __DIR__ . '/uploads/';
    private const UPLOAD_URL = 'http://example.com/uploads/';

    public function testUpload()
    {
        $file1 = $this->createUploadedFile('test1.jpg');
        $file2 = $this->createUploadedFile('test2.png');
        $photoUploader = new PhotoUploader(self::UPLOAD_DIR, self::UPLOAD_URL);

        $uploadedPhotos = $photoUploader->upload([$file1, $file2]);

        self::assertCount(2, $uploadedPhotos);
        self::assertInstanceOf(Photo::class, $uploadedPhotos[0]);
        self::assertInstanceOf(Photo::class, $uploadedPhotos[1]);
    }

    private function createUploadedFile(string $filename): UploadedFile
    {
        $sourcePath = __DIR__ . '/test_files/' . $filename;
        $targetPath = self::UPLOAD_DIR . $filename;
        (new Filesystem())->copy($sourcePath, $targetPath);
        return new UploadedFile($targetPath, $filename);
    }

    protected function tearDown(): void
    {
        (new Filesystem())->remove(self::UPLOAD_DIR);
    }
}
