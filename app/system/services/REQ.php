<?php

namespace Service;

class REQ
{
    private static $instance;
    private $GET;
    private $POST;
    private $COOKIE;
    private $SERVER;
    private $JSON;

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

    public static function start(): REQ
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function GET(string $key,$type='varchar',$case='')
    {
        return $this->filter(rawurldecode($this->GET[$key]),$type,$case);
    }
	
    public function POST(string $key,$type='varchar',$case='')
    {
        return $this->filter($this->POST[$key],$type,$case);
    }

    public function COOKIE(string $key,$type='varchar',$case='')
    {
        return $this->filter($this->COOKIE[$key],$type,$case);
    }

    public function SERVER(string $key,$type='varchar',$case='')
    {
        return $this->filter($this->SERVER[$key],$type,$case);
    }

    public function JSON(string $key,$type='varchar',$case='')
    {
        return $this->filter($this->JSON[$key],$type,$case);
    }

    private function sanitize($out)
    {
        $out=preg_replace('/[^\p{L}\p{N}\s\x09-\x0a\x20-\x7E\x{2116}]/u', '', $out);
        $out=preg_replace('/\x5C/', '/', $out);
        return trim($out);
    }

    private function specialChars($out)
    {
        return htmlspecialchars($out, ENT_QUOTES, 'UTF-8');
    }

    private function stripTags($out)
    {
        $allowedTags='<p><strong><em><u><h1><h2><h3><h4><h5><h6><img>';
        $allowedTags.='<li><ol><ul><span><br><ins><del><b>';
        $out=strip_tags($out,$allowedTags);
        $out=$this->closeTags($out);
        return $out;
    }

    private function filter($out,$type,$case)
    {
        $out=$this->sanitize($out);
if($type=='int'){$out=$this->intFilter($out);}
elseif($type=='dec'){$out=$this->decFilter($out);}
elseif($type=='hex'){$out=$this->hexFilter($out);}
elseif($type=='email'){$out=$this->emailFilter($out);}
elseif($type=='latin'){$out=$this->latinFilter($out);}
elseif($type=='varchar'){$out=$this->varcharFilter($out);}
elseif($type=='html'){$out=$this->htmlFilter($out);}
else{unset($out);}
if($case=='low'){$out=mb_strtolower($out);}
if($case=='up'){ $out=mb_strtoupper($out);}
        return $out;
    }

    private function htmlFilter($out)
    {
        return $this->stripTags($out);
    }
    private function intFilter($out)
    {
        $out=preg_replace('%[^0-9]%u','',$out);
        return $this->specialChars($out);
    }
    private function decFilter($out)
    {
        $out=preg_replace('%[^0-9.]%u','',$out);
        return $this->specialChars($out);
    }
    private function hexFilter($out)
    {
        $out=preg_replace('%[^a-fA-F0-9]%u','',$out);
        return $this->specialChars($out);
    }
    private function emailFilter($out)
    {
        $out=filter_var($out,FILTER_SANITIZE_EMAIL);
        return $this->specialChars($out);
    }
    private function latinFilter($out)
    {
        $out=preg_replace('%[^A-Za-z0-9._-]%u','',$out);
        return $this->specialChars($out);
    }
    private function varcharFilter($out)
    {
        return $this->specialChars($out);
    }


    private function closeTags($content)
    {
        $position = 0;
        $open_tags = array();
        $ignored_tags = array('br', 'hr', 'img');
        while (($position = strpos($content, '<', $position)) !== FALSE)
        {
            if (preg_match("|^<(/?)([a-z\d]+)\b[^>]*>|i", substr($content, $position), $match))
            {
                $tag = strtolower($match[2]);
                if (in_array($tag, $ignored_tags) == FALSE)
                {
                    if (isset($match[1]) AND $match[1] == '')
                    {
                        if (isset($open_tags[$tag]))
                            $open_tags[$tag]++;
                        else
                            $open_tags[$tag] = 1;
                    }
                    if (isset($match[1]) AND $match[1] == '/')
                    {
                        if (isset($open_tags[$tag]))
                            $open_tags[$tag]--;
                    }
                }
                $position += strlen($match[0]);
            }
            else
                $position++;
        }
        foreach ($open_tags as $tag => $count_not_closed)
        {
            $content .= str_repeat("</{$tag}>", $count_not_closed);
        }
        return $content;
    }
}