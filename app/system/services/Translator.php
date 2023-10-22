<?php
namespace Service;

class Translator
{
    protected $lang;
    protected $translations;

    public function __construct($lang)
    {
        $this->lang = $lang;
        $this->loadTranslations();
    }

    private function loadTranslations()
    {
        $file =  'system/translations/' . $this->lang . '.php';
        if (file_exists($file)) {
            $this->translations = require $file;
        } else {
            //echo $file;
            // Обработка ошибки: файл с языковыми ресурсами не найден
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
