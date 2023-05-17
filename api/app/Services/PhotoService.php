<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class PhotoService
{
    public function addPhoto(UploadedFile $file)
    {
        $fileName = $this->generateUniqueFileName($file);
        $filePath = $file->storeAs('photos', $fileName, 'public');

        return [
            'publicId' => $fileName,
            'url' => Storage::url($filePath),
        ];
    }

    public function deletePhoto($publicId)
    {
        $filePath = "photos/{$publicId}";
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }

    private function generateUniqueFileName(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = md5(uniqid()) . '.' . $extension;

        return $fileName;
    }
}
