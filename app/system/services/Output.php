<?php

namespace Services;

use Engine\Debug;

class Output
{
    private string $header;
    private string $footer;
    private string $language;
    private object $translator;


    public function __construct($defaultHeader, $defaultFooter, $defaultLanguage)
    {
        $this->header = $defaultHeader;
        $this->footer = $defaultFooter;
        $this->language = $defaultLanguage;
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
        if (isset($settings['language'])) {
            $this->language = $settings['language'];
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

        $debug=Debug::init();
        if($debug->enabled()){
            ob_start();
            var_dump($debug->getReports());
            $content .= ob_get_clean();


            $headers= "Received headers:\n";
            foreach (getallheaders() as $name => $value) {
                $headers.= "$name: $value\n";
            }


            $headers.= "\nSent headers (by PHP):\n";
            foreach (headers_list() as $header) {
                $headers.= $header . "\n";
            }
            $content .= '<pre>'.$headers.'</pre>';


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