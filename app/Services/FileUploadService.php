<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileUploadService
{
    /**
     * Upload and optimize an image file
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param array $options
     * @return array
     */
    public function uploadImage(UploadedFile $file, string $directory = 'uploads', array $options = []): array
    {
        $defaultOptions = [
            'max_width' => 1920,
            'max_height' => 1080,
            'quality' => 85,
            'generate_thumbnails' => true,
            'thumbnail_sizes' => [
                ['width' => 300, 'height' => 300, 'suffix' => 'thumb'],
                ['width' => 600, 'height' => 400, 'suffix' => 'medium'],
            ]
        ];

        $options = array_merge($defaultOptions, $options);

        $extension = $file->getClientOriginalExtension();
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;
        
        // Create organized directory structure
        $fullDirectory = $directory . '/' . date('Y/m');
        $path = $file->storeAs($fullDirectory, $filename, 'public');

        $result = [
            'original_path' => $path,
            'filename' => $filename,
            'directory' => $fullDirectory,
            'metadata' => [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'extension' => $extension,
            ]
        ];

        // Optimize main image
        $this->optimizeImage($path, $options);

        // Generate thumbnails if requested
        if ($options['generate_thumbnails']) {
            $result['thumbnails'] = $this->generateThumbnails($path, $options['thumbnail_sizes']);
        }

        return $result;
    }

    /**
     * Upload a general file (non-image)
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return array
     */
    public function uploadFile(UploadedFile $file, string $directory = 'uploads'): array
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;
        
        $fullDirectory = $directory . '/' . date('Y/m');
        $path = $file->storeAs($fullDirectory, $filename, 'public');

        return [
            'path' => $path,
            'filename' => $filename,
            'directory' => $fullDirectory,
            'metadata' => [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'extension' => $extension,
            ]
        ];
    }

    /**
     * Optimize an image file
     *
     * @param string $path
     * @param array $options
     * @return void
     */
    private function optimizeImage(string $path, array $options): void
    {
        try {
            $fullPath = Storage::disk('public')->path($path);
            
            $image = Image::make($fullPath);
            
            // Resize if image is larger than max dimensions
            if ($image->width() > $options['max_width'] || $image->height() > $options['max_height']) {
                $image->resize($options['max_width'], $options['max_height'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // Save with quality optimization
            $image->save($fullPath, $options['quality']);
            
        } catch (\Exception $e) {
            // Log error but don't fail the upload
            \Log::warning('Image optimization failed: ' . $e->getMessage(), ['path' => $path]);
        }
    }

    /**
     * Generate thumbnails for an image
     *
     * @param string $originalPath
     * @param array $sizes
     * @return array
     */
    private function generateThumbnails(string $originalPath, array $sizes): array
    {
        $thumbnails = [];
        $originalFullPath = Storage::disk('public')->path($originalPath);
        $pathInfo = pathinfo($originalPath);

        foreach ($sizes as $size) {
            try {
                $thumbnailFilename = $pathInfo['filename'] . '_' . $size['suffix'] . '.' . $pathInfo['extension'];
                $thumbnailPath = $pathInfo['dirname'] . '/' . $thumbnailFilename;
                $thumbnailFullPath = Storage::disk('public')->path($thumbnailPath);

                $image = Image::make($originalFullPath);
                $image->fit($size['width'], $size['height']);
                $image->save($thumbnailFullPath, 85);

                $thumbnails[$size['suffix']] = [
                    'path' => $thumbnailPath,
                    'url' => asset('storage/' . $thumbnailPath),
                    'width' => $size['width'],
                    'height' => $size['height'],
                ];

            } catch (\Exception $e) {
                \Log::warning('Thumbnail generation failed: ' . $e->getMessage(), [
                    'original_path' => $originalPath,
                    'size' => $size
                ]);
            }
        }

        return $thumbnails;
    }

    /**
     * Delete a file and its thumbnails
     *
     * @param string $path
     * @param array $thumbnails
     * @return bool
     */
    public function deleteFile(string $path, array $thumbnails = []): bool
    {
        $deleted = true;

        // Delete main file
        if (Storage::disk('public')->exists($path)) {
            $deleted = Storage::disk('public')->delete($path);
        }

        // Delete thumbnails
        foreach ($thumbnails as $thumbnail) {
            if (isset($thumbnail['path']) && Storage::disk('public')->exists($thumbnail['path'])) {
                Storage::disk('public')->delete($thumbnail['path']);
            }
        }

        return $deleted;
    }

    /**
     * Get file URL
     *
     * @param string $path
     * @return string
     */
    public function getFileUrl(string $path): string
    {
        return asset('storage/' . $path);
    }

    /**
     * Validate file type and size
     *
     * @param UploadedFile $file
     * @param array $allowedTypes
     * @param int $maxSize
     * @return bool
     */
    public function validateFile(UploadedFile $file, array $allowedTypes = [], int $maxSize = 10240): bool
    {
        // Check file size (in KB)
        if ($file->getSize() > ($maxSize * 1024)) {
            return false;
        }

        // Check file type
        if (!empty($allowedTypes)) {
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, $allowedTypes)) {
                return false;
            }
        }

        return true;
    }
}
