<?php

namespace Engine;

class Translator
{
    protected string $lang;
    private string $path;
    protected array $translations;

    public function __construct($lang,$path)
    {
        $this->lang = $lang;
        $this->path = $path;
        $this->loadTranslations();
    }

    private function loadTranslations(): void
    {
        $file = $this->path. $this->lang . '.php';
        if (file_exists($file)) {
            $this->translations = require $file;
        }
    }

    public function translate($key)
    {
        if (isset($this->translations[$key])) {
            return htmlspecialchars($this->translations[$key], ENT_QUOTES);
        } else {
            return $key;
        }
    }
}
