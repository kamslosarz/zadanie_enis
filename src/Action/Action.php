<?php


namespace App\Action;

class Action
{
    public function render($file, $variables)
    {
        extract($variables);
        include APP_DIR . '/src/resources/' . $file;
    }
}