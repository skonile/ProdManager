<?php
declare(strict_types = 1);

namespace App;

class Utils{
    static public function addDirSepToPath(string $path): string{
        if($path[-1] == DIR_SEP) return $path;
        return $path . DIR_SEP;
    }

    static public function firstLetterToLower(string $str): string{
        return \strtolower($str[0]) . substr($str, 1);
    }

    /**
     * Delete the given directory and its contents.
     *
     * @param string $dir
     * @return bool Returns true if the directory was deleted or false otherwise
     */
    static public function deleteDirectory(string $dir): bool{
        if (!is_dir($dir)) {
            return false;
        }
    
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $path = $dir . '/' . $file;
                if (is_dir($path)) {
                    Utils::deleteDirectory($path);
                } else {
                    unlink($path);
                }
            }
        }
        rmdir($dir);
        
        return true;
    }

    /**
     * Get all filenames of the files in directory.
     * 
     * @param string $dir Directory to search for files in.
     * @return array<string> Files in the directory.
     */
    static public function getFilesFromDir(string $dir, bool $returnFullpath = false): array{
        if(!is_dir($dir))
            return [];

        $files = [];
        $dirsFiles = scandir($dir);
        foreach($dirsFiles as $dirFile){
            if(is_file($dir . DIR_SEP . $dirFile)){
                $files[] = $returnFullpath? $dir . DIR_SEP . $dirFile: $dirFile;
            }
        }

        return $files;
    }
}