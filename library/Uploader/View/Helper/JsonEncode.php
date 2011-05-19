<?php
class Uploader_View_Helper_JsonEncode extends Uploader_View_Helper_Abstract
{
    private static $_level = 0;
    
    public function jsonEncode($options)
    {
        if (self::$_level == 0) {
            $json = '[';
        } else {
            $json = '{';
        }
        $opts = array();
        foreach ($options as $key => $value) {
            $jsonOpt = '';
            if (self::$_level > 0) {
                $jsonOpt = $key . ':';
            }
            if (is_array($value)) {
                self::$_level++;
                $jsonOpt .= $this->jsonEncode($value);
                self::$_level--;
            } elseif (
                is_bool($value) || is_double($value) ||
                is_real($value) || is_int($value) ||
                strpos($value, 'function') !== false
            ) {
                $jsonOpt .= $value;
            } else {
                $jsonOpt .= '\'' . $value . '\'';
            }
            $opts[] = $jsonOpt;
        }
        $json .= implode(',', $opts);
        if (self::$_level == 0) {
            $json .= ']';
        } else {
            $json .= '}';
        }
        return $json;
    }
}
