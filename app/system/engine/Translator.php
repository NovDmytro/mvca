<?php

namespace Engine;

class Translator
{
    protected string $lang;
    protected array $translations;

    public function __construct($lang)
    {
        $this->lang = $lang;
        $this->loadTranslations();
    }

    private function loadTranslations(): void
    {
        $file = 'system/translations/' . $this->lang . '.php';
        if (file_exists($file)) {
            $this->translations = require $file;
        }
    }

    public function translate($key)
    {
        if (isset($this->translations[$key])) {
            return htmlspecialchars($this->translations[$key], ENT_QUOTES, 'UTF-8');
        } else {
            return $key;
        }
    }
}
