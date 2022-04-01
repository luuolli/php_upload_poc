<?php

namespace App\Controllers\Traits;

trait FlipbookUploadTrait
{
    protected $flipbook_root_folder = "books/";

    private function flipbookUpload($name, &$paths = null)
    {
        for ($i = 0; $i < count($paths); $i++) {
            $paths[$i] = $this->flipbook_root_folder . $name . "/" . $paths[$i];
        }
    }
}
