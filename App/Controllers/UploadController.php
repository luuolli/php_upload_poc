<?php

namespace App\Controllers;

use App\Controllers\Traits\FileUploadTrait;
use App\Controllers\Traits\FlipbookUploadTrait;
use App\Controllers\Traits\UnityUploadTrait;
use DirectoryIterator;
use Exception;
use PhpZip\ZipFile;

class UploadController
{
    use FileUploadTrait;
    use FlipbookUploadTrait;
    use UnityUploadTrait;

    private $folder_hash_name = "";

    public function uploadFromRequest($metadata, $name)
    {
        $metaArray = explode(",", $metadata);

        $file = $_FILES['zip'];
        $this->setTmpName($file);
        $this->setFileName($file);
        $this->setFileType($file);
        $this->setDirPath();
        $this->extractZipToTemp();

        $files = $this->getPathsFromFolder();
        $paths = $this->getArrayPaths($files);

        $this->flipbookUpload($this->folder_hash_name, $paths);

        // $this->unityUpload($this->folder_hash_name, $paths);

        $this->removeTempFolder($this->dir_path);

        return $paths;
    }

    /**
     * Extract zip file to temp
     */
    private function extractZipToTemp()
    {
        $zipFile = new ZipFile();
        $zipFile->openFile($this->tmp_name);

        $this->setDirPath();

        mkdir($this->dir_path, 0777, true);
        $zipFile->extractTo($this->dir_path);
    }

    /**
     * Return list of all files grouped in respective folders
     */
    private function getPathsFromFolder($tmp_dir_path = null)
    {
        $root = $tmp_dir_path ?? $this->dir_path;

        $rdi = new \RecursiveDirectoryIterator($root);
        $rii = new \RecursiveIteratorIterator($rdi);

        $tree = [];

        foreach ($rii as $splFileInfo) {
            $file_name = $splFileInfo->getFilename();

            // Skip hidden files and directories.
            if ($file_name[0] === '.') {
                continue;
            }

            $path = $splFileInfo->isDir() ? array($file_name => array()) : array($file_name);
            for ($depth = $rii->getDepth() - 1; $depth >= 0; $depth--) {
                $path = array($rii->getSubIterator($depth)->current()->getFilename() => $path);
            }

            $tree = array_merge_recursive($tree, $path);
        }
        return (array)$tree;
    }

    /**
     * Return list of paths files, with full path
     * ```json
     * [
     *  "BALAINHA_MIOLO_001_020.html",
     *  "style/MovingBackgrounds.min.css",
     *]
     * ```
     */
    private function getArrayPaths($objet, array &$array = [], $path = "")
    {
        if (is_null($array) || count($array) == 0) {
            $array = array();
        }
        if (is_array($objet)) {
            foreach ($objet as $key => $value) {
                $new_path = "";
                if (is_string($key)) {
                    $new_path = ($path != "" ? $path . "/" : "") . $key;
                } else {
                    $new_path = $path;
                }
                $this->getArrayPaths($value, $array, $new_path);
            }
        } else if (is_string($objet)) {
            $path = (($path != "" ? $path . "/" : "") . $objet);
            array_push($array, $path);
        }

        return $array;
    }

    /**
     * Remove temporary folder
     */
    private function removeTempFolder($path = null)
    {
        try {
            $iterator = new DirectoryIterator($path);
            foreach ($iterator as $fileinfo) {
                if ($fileinfo->isDot()) continue;
                if ($fileinfo->isDir()) {
                    if ($this->removeTempFolder($fileinfo->getPathname()))
                        @rmdir($fileinfo->getPathname());
                }
                if ($fileinfo->isFile()) {
                    @unlink($fileinfo->getPathname());
                }
            }
        } catch (Exception $e) {
            return false;
        }
        @rmdir($this->dir_path);
        return true;
    }


    public function uploadToCDN(&$file_names, $root_path = "/")
    {
    }
}
