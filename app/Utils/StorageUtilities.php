<?php


namespace App\Utils;


use Illuminate\Support\Facades\Storage;

class StorageUtilities
{

    // store personal photo in local storage
    public static function storePersonalPhoto($file): string
    {
        // store file in local storage & return image url
        return $file->store('personal_photos', 'public');
    }

    public static function storeCustomerPhoto($file): string
    {
        // store file in local storage & return image url
        return $file->store('customers_photos', 'public');
    }

    public static function storeEventPhoto($file): string
    {
        // store file in local storage & return image url
        return $file->store('events_photos', 'public');
    }

    // delete personal photo from local storage
    public static function deletePersonalPhoto($url): void
    {
        // remove '/storage/' from url
        $url = str_replace('/storage/', '', $url);

        Storage::disk('public')->delete($url);
    }

    // store certificate in local storage
    public static function storeCertificate($file): string
    {
        // store file in local storage
        return $file->store('certificates', 'public');
    }


    public static function replaceCertificate($oldFileUrl, $newFile): string
    {
        // delete the old file
        // remove '/storage/' from url
        $oldFileUrl = str_replace('/storage/', '', $oldFileUrl);
        if (Storage::disk('public')->exists($oldFileUrl)) {
            Storage::disk('public')->delete($oldFileUrl);
        }

        // store new file in local storage & return file url
        return $newFile->store('certificates', 'public');
    }

    public static function deleteFiles($urls): void
    {
        foreach ($urls as $url) {
            // remove '/storage/' from url
            $url = str_replace('/storage/', '', $url);
            if (Storage::disk('public')->exists($url)) {
                Storage::disk('public')->delete($url);
            }
        }
    }

}
