<?php

namespace Services;

class Output
{
    private string $header;
    private string $footer;
    private string $language;
    private bool $debugMode;
    private object $translator;


    public function __construct($defaultHeader, $defaultFooter, $defaultLanguage, $debugMode)
    {
        $this->header = $defaultHeader;
        $this->footer = $defaultFooter;
        $this->language = $defaultLanguage;
        $this->debugMode = $debugMode;
    }

    /**
     * @param string $route
     * @param array $data
     *   Array keys will become variables
     * @param array $settings
     *   [header,footer,language,debugMode]
     */
    public function load(string $route, $data = [], $settings = []): void
    {
        if (isset($settings['header'])) {
            $this->header = $settings['header'];
        }
        if (isset($settings['footer'])) {
            $this->footer = $settings['footer'];
        }
        if (isset($settings['language'])) {
            $this->language = $settings['language'];
        }
        if (isset($settings['debugMode'])) {
            $this->debugMode = $settings['debugMode'];
        }

        $content = '';
        $this->translator = new Translator($this->language);

        if ($this->header) {
            $content = $this->loadFile($this->header, $data);
        }

        $routePaths = explode('/', $route);
        $content .= $this->loadFile('src/' . $routePaths[0] . '/V/' . $routePaths[1] . 'View.php', $data);

        if ($this->footer) {
            $content .= $this->loadFile($this->footer, $data);
        }

        if($this->debugMode){
            //ADD DEBUG ENGINE HERE
        }

        $content = $this->translateContent($content);

        echo $content;
    }


    public function loadFile(string $route, array $data): string
    {
        extract($data);

        ob_start();
        include $route;

        return ob_get_clean();
    }

    protected function translateContent(string $content): string
    {
        preg_match_all('/\{\{(.+?)\}\}/', $content, $matches);
        $keys = $matches[1];

        foreach ($keys as $key) {
            $translation = $this->translator->translate($key);
            $content = str_replace('{{' . $key . '}}', $translation, $content);
        }

        return $content;
    }

}