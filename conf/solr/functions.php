<?php

class Functions
{
    public function is_utf8($str)
    {
        $str = str_replace('_', ' ', $str);
        $str = ucwords($str);
        $str = str_replace(' ', '', $str);
        return $str;
    }
    
    // see http://www.php.net/manual/en/function.filesize.php#92462
    public static function remote_file_size($uri)
    {
        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close($ch);
        if ($data === false) return null;
        
        $content_length = null;
        if(preg_match('/Content-Length: (\d+)/', $data, $matches))
        {
            $content_length = ((int) $matches[1]) / 1024;
        }
        
        return $content_length;
    }
    
    public static function ping($uri)
    {
        return (self::remote_file_size($uri) !== null);
    }
    
    public static function utf8_to_ascii($nameString)
    {
        // source code at http://us3.php.net/manual/en/function.iconv.php#93609
        
        $r = '';
        $s1 = @iconv('UTF-8', 'ASCII//TRANSLIT', $nameString);
        $j = 0;
        for ($i = 0; $i < strlen($s1); $i++) {
            $ch1 = $s1[$i];
            $ch2 = substr($nameString, $j++, 1);
            if (strstr('`^~\'"', $ch1) !== false) {
                if ($ch1 <> $ch2) {
                    --$j;
                    continue;
                }
            }
            $r .= ($ch1=='?') ? $ch2 : $ch1;
        }
        return $r;
    }
    
    public static function time_elapsed()
    {
        static $a;
        if(!isset($a)) $a = microtime(true);
        return (string) round(microtime(true)-$a, 6);
    }
}

?>
