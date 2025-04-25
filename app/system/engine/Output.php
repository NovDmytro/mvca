<?php

namespace Engine;

use Engine\Console;
use Engine\Debug;

class Output
{
    private Config $config;
    private mixed $header;
    private mixed $footer;
    private string $language;
    private object $translator;

    public function __construct(
        Config   $config,
    ){
        global $globalView;
        if(!isset($globalView)){$globalView=[];}
        $this->config = $config;
        $this->header = $this->config->get('defaultHeader');
        $this->footer = $this->config->get('defaultFooter');
        $this->language = $this->config->get('defaultLanguage');
    }

    public function addGlobal($data){
        global $globalView;
        $globalView = array_merge($globalView, $data);
    }

    public function setGlobal($data){
        global $globalView;
        $globalView = $data;
    }

    public function deleteGlobal($data){
        global $globalView;
        $globalView = [];
    }

    /**
     * @param string $route
     * @param array $data
     *   Array keys will become variables
     * @param array $settings
     *   [header,footer,language]
     */
    public function load(string $route, $data = [], $settings = []): void
    {
        if (isset($settings['header'])) {
            $this->header = $settings['header'];
        }
        if (isset($settings['footer'])) {
            $this->footer = $settings['footer'];
        }
        if (isset($settings['language']) && in_array($settings['language'], $this->config->get('supportedLanguages'))) {
            $this->language = $settings['language'];
        }
        $content = '';
        $this->translator = new Translator($this->language,$this->config->get('translationsPath'));
        if ($this->header) {
            $content = $this->loadFile($this->header, $data);
        }
        $routePaths = explode('/', $route);

        if ($this->config->get('routeTarget') == 'core') {
            $content .= $this->loadFile('system/Core/' . $routePaths[0] . '/V/' . $routePaths[1] . 'View.php', $data);
        } else {
            $content .= $this->loadFile($this->config->get('sourcesPath') . $routePaths[0] . '/V/' . $routePaths[1] . 'View.php', $data);
        }

        if ($this->footer) {
            $content .= $this->loadFile($this->footer, $data);
        }

        $content = $this->translateContent($content);
        echo $content;
    }


    public function loadFile(string $loadRoute, array $view): string
    {
        global $globalView;
        extract($globalView);
        extract($view);
        ob_start();
        include $loadRoute;
        return ob_get_clean();
    }

    public function translateContent(string $content): string
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