<?php

class Zenmondo_Controller_Request_Http_Var
{
    const ERROR_REMOVE_ONRY = 1;
    const IGNORE_SANITIZE = 2;
 
    private $_encoding;
    private $_mode = 0; 
 

    public function setCheckEncoding($encoding)
    {
        $this->_encoding = $encoding;
    }

    public function setCheckEncodingMode($mode)
    {
        $this->_mode = $mode;
    }


    public function get($data)
    {
        if (($this->_mode & self::ERROR_REMOVE_ONRY) === 0) {
            array_walk_recursive($data, array($this, 'checkInvalidEncoding'));
        } else {
            $work = array();
            foreach ($data as $key => $value) {
                $result = $this->removeInvalidEncoding($key, $value);
                if (is_array($result)) {
                    $work[$result[0]] = $result[1];
                }
            }
            $data = $work;
        }
 
        if (($this->_mode & self::IGNORE_SANITIZE) === 0) {
            $data = array_map(array($this, 'sanitizeNULL'), $data);
        }

        return new ArrayObject($data);
    }
    
    public function removeInvalidEncoding($key, $value)
    {
        if ($this->checkEncoding($key, '', $this->_encoding) === false) {
            return false;
        }
 
        if (is_array($value)) {
            $result = array();
            foreach($value as $keydata => $value) {
                $valid = $this->removeInvalidEncoding($keydata, $value);
                if (is_array($valid)) {
                    $result[$valid['key']] = $valid['value'];
                }
            }
            return array($key, $result);
        }
 
        if ($this->checkEncoding($key, $value, $this->_encoding) === false) {
            return false;
        }
        return array($key, $value);
    }
 
    public function checkInvalidEncoding($key, $value)
    {
        if ($this->checkEncoding($key, $value, $this->_encoding) === false) {
            throw new Zenmondo_Controller_Request_Http_Var_Excepstion('invalid encoding');
        }
    }
 
    public function checkEncoding($key, $value, $encoding)
    {
        if (mb_check_encoding($key, $encoding) === false) {
            return false;
        }
 
        if (mb_check_encoding($value, $encoding) === false) {
            return false;
        }
    }
 
    public function sanitizeNull($data)
    {
        if (is_array($data)) {
            return array_map($data);
        }
        return str_replace("\0", '', $data);
    }
}
