<?php

namespace App\Helpers;

use Illuminate\Filesystem\Filesystem;

class Search
{
    /**
     * Search file
     *
     * @return array [SplFileInfo]
     */
    public static function file($folder, $patternArray)
    {
        app()->make(Filesystem::class)->ensureDirectoryExists($folder);

        $return = [];
        $iti = new \RecursiveDirectoryIterator($folder);
        foreach (new \RecursiveIteratorIterator($iti) as $file) {
            $arr = explode('.', $file);
            if (in_array(strtolower(array_pop($arr)), $patternArray)) {
                $return[] = $file;
            }
        }

        return $return;
    }
}
