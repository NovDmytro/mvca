<?php

namespace Service;

class Output
{
    protected $output_scripts = array();
    protected $output_styles = array();
    protected $cache_version = 0;
    protected $header_path;
    protected $footer_path;
    protected $is_debug_console_enabled;
    protected $translator;


    public function __construct($header_path, $footer_path, $cache_version, $is_debug_console_enabled)
    {
        $this->header_path = $header_path;
        $this->footer_path = $footer_path;
        $this->cache_version = $cache_version;
        $this->is_debug_console_enabled = $is_debug_console_enabled;
        $this->output_scripts = [];
        $this->output_styles = [];
    }

    public function load(string $route, array $data = array(),$language='en',$hideHeaderFooter=FALSE)
    {


        $this->translator = new Translator($language);

        if(!$hideHeaderFooter){$content = $this->loadFile($this->header_path, $data);}

        $route_paths = explode("/", $route);
        $content .= $this->loadFile('src/' . $route_paths[0] . '/view/' . $route_paths[1] . 'View.php', $data);

        if(!$hideHeaderFooter){$content .= $this->loadFile($this->footer_path, $data);}
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
            $content = str_replace('{{'.$key.'}}', $translation, $content);
        }

        return $content;
    }

}