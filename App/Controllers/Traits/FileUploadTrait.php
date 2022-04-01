<?php

namespace App\Controllers\Traits;

trait FileUploadTrait
{
    private $type = "";
    private $tmp_name = "";
    private $file_name = "";
    private $dir_path = "";

    private function setTmpName($file)
    {
        $this->tmp_name = $file['tmp_name'];
    }

    private function setFileType($file)
    {
        $this->type = pathinfo($file['name'], PATHINFO_EXTENSION);
    }

    private function setFileName($file)
    {
        $this->file_name = $file['name'];
    }

    private function setDirPath()
    {
        $strTempName = substr($this->file_name, 0, -4);
        $this->folder_hash_name = \md5($strTempName);
        $this->dir_path = sys_get_temp_dir() . "/" . $this->folder_hash_name;
    }

    static function getOnlyNameFromPath($path)
    {
        return basename($path, "." . pathinfo(basename($path), PATHINFO_EXTENSION));
    }
}
