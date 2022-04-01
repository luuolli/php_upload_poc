<?php

namespace App\Controllers\Traits;

trait UnityUploadTrait
{
    protected $unity_root_folder = "games/unity/";

    private function unityUpload($name, &$paths = null)
    {
        for ($i = 0; $i < count($paths); $i++) {
            $paths[$i] = $this->unity_root_folder . $name . "/" . $paths[$i];
        }
    }
}
