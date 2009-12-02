<?php

// 
// porting from Holo
// @see http://github.com/cocoiti/Holo/
// @see http://d.hatena.ne.jp/cocoiti/20090919

class Zenmondo_Controller_Request_Http extends Zend_Controller_Request_Http
{
    /**
     * super globals
     */
    private $_var;

    //
    public function __construct($uri = null)
    {
        $this->_var = new Zenmondo_Controller_Request_Http_Var();
        // default ...?
        $this->setVarCheckEncoding(mb_internal_encoding());

        return parent::__construct($uri);
    }

    public function setVarCheckEncoding($encoding)
    {
        $this->_var->setCheckEncoding($encoding);
    }

    public function setVarCheckEncodingMode($mode)
    {
        $this->_var->setCheckEncodingMode($mode);
    }

    public function getRawPost($key = null, $default = null)
    {
        return parent::getPost($key = null, $default = null);
    }

    public function __get($key)
    {  
        switch (true) {
            case isset($this->_params[$key]):
                return $this->_params[$key];
            case isset($_GET[$key]):
                return $_GET[$key];
            case isset($_POST[$key]):
                return $this->getPost($key);
            case isset($_COOKIE[$key]):
                return $_COOKIE[$key];
            case ($key == 'REQUEST_URI'):
                return $this->getRequestUri();
            case ($key == 'PATH_INFO'):
                return $this->getPathInfo();
            case isset($_SERVER[$key]):
                return $_SERVER[$key];
            case isset($_ENV[$key]):
                return $_ENV[$key];
            default:
                return null;
        }
    }


    //@override
    public function getPost($key = null, $default = null)
    {
        
        $post = $this->_var->get($_POST);
        if (null === $key) {
            return $post;
        } else if ($post->offsetExists($key)) {
            return $post[$key];
        } else {
            return $default;
        }
    }
}
