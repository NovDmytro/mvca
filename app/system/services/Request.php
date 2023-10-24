<?php

namespace Services;

class Request
{
    private static $instance;
    private array $GET;
    private array $POST;
    private array $COOKIE;
    private array $SERVER;
    private mixed $JSON;

    private function __construct()
    {
        if ($_GET) {
            $this->GET = $_GET;
            unset($_GET);
        }
        if ($_POST) {
            $this->POST = $_POST;
            unset($_POST);
        }
        if ($_COOKIE) {
            $this->COOKIE = $_COOKIE;
            unset($_COOKIE);
        }
        if ($_SERVER) {
            $this->SERVER = $_SERVER;
            unset($_SERVER);
        }
        if ($this->SERVER('CONTENT_TYPE') == 'application/json') {
            $this->JSON = json_decode(
                file_get_contents('php://input'), true);
        }
    }

    public static function start(): Request
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function GET(string $key, $type = 'varchar', $case = '')
    {
        if (isset($this->GET[$key]) && $this->GET[$key] !== null) {
            return $this->filter(rawurldecode($this->GET[$key]), $type, $case);
        }
    }

    public function POST(string $key, $type = 'varchar', $case = '')
    {
        if (isset($this->POST[$key]) && $this->POST[$key] !== null) {
            return $this->filter($this->POST[$key], $type, $case);
        }
    }

    public function COOKIE(string $key, $type = 'varchar', $case = '')
    {
        if (isset($this->COOKIE[$key]) && $this->COOKIE[$key] !== null) {
            return $this->filter($this->COOKIE[$key], $type, $case);
        }
    }

    public function SERVER(string $key, $type = 'varchar', $case = '')
    {
        if (isset($this->SERVER[$key]) && $this->SERVER[$key] !== null) {
            return $this->filter($this->SERVER[$key], $type, $case);
        }
    }

    public function JSON(string $key, $type = 'varchar', $case = '')
    {
        if (isset($this->JSON[$key]) && $this->JSON[$key] !== null) {
            return $this->filter($this->JSON[$key], $type, $case);
        }
    }

    private function filter($out, $type, $case): string
    {
        $out = $this->sanitize($out);
        if ($type == 'int') {
            $out = $this->intFilter($out);
        } elseif ($type == 'dec') {
            $out = $this->decFilter($out);
        } elseif ($type == 'hex') {
            $out = $this->hexFilter($out);
        } elseif ($type == 'email') {
            $out = $this->emailFilter($out);
        } elseif ($type == 'latin') {
            $out = $this->latinFilter($out);
        } elseif ($type == 'varchar') {
            $out = $this->varcharFilter($out);
        } elseif ($type == 'html') {
            $out = $this->htmlFilter($out);
        } else {
            unset($out);
        }
        if ($case == 'low') {
            $out = mb_strtolower($out);
        }
        if ($case == 'up') {
            $out = mb_strtoupper($out);
        }
        return $out;
    }

    private function intFilter($out): string
    {
        $out = preg_replace('%[^0-9]%u', '', $out);
        return $this->specialChars($out);
    }

    private function decFilter($out): string
    {
        $out = preg_replace('%[^0-9.]%u', '', $out);
        return $this->specialChars($out);
    }

    private function hexFilter($out): string
    {
        $out = preg_replace('%[^a-fA-F0-9]%u', '', $out);
        return $this->specialChars($out);
    }

    private function emailFilter($out): string
    {
        $out = filter_var($out, FILTER_SANITIZE_EMAIL);
        return $this->specialChars($out);
    }

    private function latinFilter($out): string
    {
        $out = preg_replace('%[^A-Za-z0-9._-]%u', '', $out);
        return $this->specialChars($out);
    }

    private function varcharFilter($out): string
    {
        return $this->specialChars($out);
    }

    private function htmlFilter($out): string
    {
        return $this->stripTags($out);
    }

    private function specialChars($out): string
    {
        return htmlspecialchars($out, ENT_QUOTES, 'UTF-8');
    }

    private function sanitize($out): string
    {
        $out = preg_replace('/[^\p{L}\p{N}\s\x09-\x0a\x20-\x7E\x{2116}]/u', '', $out);
        $out = preg_replace('/\x5C/', '/', $out);
        return trim($out);
    }

    private function stripTags($out): string
    {
        $allowedTags = '<p><strong><em><u><h1><h2><h3><h4><h5><h6><img>';
        $allowedTags .= '<li><ol><ul><span><br><ins><del><b>';
        $out = strip_tags($out, $allowedTags);
        $out = $this->closeTags($out);
        return $out;
    }

    private function closeTags($content): string
    {
        $position = 0;
        $open_tags = array();
        $ignored_tags = array('br', 'hr', 'img');
        while (($position = strpos($content, '<', $position)) !== FALSE) {
            if (preg_match("|^<(/?)([a-z\d]+)\b[^>]*>|i", substr($content, $position), $match)) {
                $tag = strtolower($match[2]);
                if (!in_array($tag, $ignored_tags)) {
                    if (isset($match[1]) and $match[1] == '') {
                        if (isset($open_tags[$tag]))
                            $open_tags[$tag]++;
                        else
                            $open_tags[$tag] = 1;
                    }
                    if (isset($match[1]) and $match[1] == '/') {
                        if (isset($open_tags[$tag]))
                            $open_tags[$tag]--;
                    }
                }
                $position += strlen($match[0]);
            } else
                $position++;
        }
        foreach ($open_tags as $tag => $count_not_closed) {
            $content .= str_repeat("</{$tag}>", $count_not_closed);
        }
        return $content;
    }
}