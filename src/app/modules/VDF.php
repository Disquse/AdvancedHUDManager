<?php
namespace app\modules;

use php\util\Regex;
use php\lib\str;

class VDF
{	
    private static $STRING = '"',
    $NODE_OPEN = '{',
    $NODE_CLOSE = '}',
    $BR_OPEN = '[',
    $BR_CLOSE = ']',
    $COMMENT = '/',
    $CR = '\r',
    $LF = '\n',
    $SPACE = ' ',
    $TAB = '\t',
    $WHITESPACE = [' ', "\t", "\r", "\n"];
    
    private static function _symtostr($line, $i, $token = null)
    {
        $token = $token ? $token : self::$STRING;
        $opening = $i + 1;
        $closing = $opening;
        $ci = str::pos($line, $token, $opening);
        while ($ci !== -1) {
            if (str::sub($line, $ci - 1, $ci) !== "\\") {
                $closing = $ci;
                break;
            }
            $ci = str::pos($line, $token, $ci + 1);
        }
     
        $finalstr = str::sub($line, $opening, $closing);
        return [$finalstr, $i + str::length($finalstr) + 1];
    }
     
    private static function _unquotedtostr($line, $i)
    {
        $ci = $i;
        while ($ci < str::length($line)) {
            if (self::ArraySearch(self::$WHITESPACE, str::sub($line, $ci, $ci + 1)) > -1) {
                break;
            }
            $ci += 1;
        }
        return [str::sub($line, $i, $ci), $ci];
    }
     
    private static function _parse($stream, $ptr = 0)
    {
        $laststr = null;
        $lasttok = null;
        $lastbrk = null;
        $i = $ptr;
        $next_is_value = false;
        $deserialized = [];
     
        while ($i < str::length($stream)) {
            $c = str::sub($stream, $i, $i + 1);
     
            if ($c === self::$NODE_OPEN) {
                $next_is_value = false;
     
                $parsed = self::_parse($stream, $i + 1);
                $deserialized[$laststr] = $parsed[0];
                $i = $parsed[1];
            }
            else if ($c === self::$NODE_CLOSE) {
                return [$deserialized, $i];
            }
            else if ($c === self::$BR_OPEN) {
                $_string = self::_symtostr($stream, $i, self::$BR_CLOSE);
                $lastbrk = $_string[0];
                $i = $_string[1];
            }
            else if ($c === self::$COMMENT) {
                if (($i + 1) < str::length($stream) && str::sub($stream,$i + 1,$i + 2) === "/") {
                    $i = str::pos($stream,"\n",$i);
                }
            }
            else if ($c === self::$CR || $c === self::$LF) {
                $ni = $i + 1;
                if ($ni < str::length($stream) && str::sub($stream, $ni, $ni+1) === self::$LF) {
                    $i = $ni;
                }
                if ($lasttok != self::$LF) {
                    $c = self::$LF;
                }
            }
            else if ($c !== self::$SPACE && $c !== self::$TAB) {
                $_string = ($c === self::$STRING ? self::_symtostr($stream, $i) : self::_unquotedtostr($stream, $i));
                $string = $_string[0];
                $i = $_string[1];
     
                if ($lasttok === self::$STRING && $next_is_value) {
                    if ($deserialized[$laststr] && $lastbrk != null) {
                        $lastbrk = null;
                    }
                    else {
                        $deserialized[$laststr] = $string;
                    }
                }
                $c = self::$STRING;
                $laststr = $string;
                $next_is_value = !$next_is_value;
            }
            else {
                $c = $lasttok;
            }
     
            $lasttok = $c;
            $i += 1;
        }
        return [$deserialized, $i];
    }
	
	private function encodeSegment(&$str, $arr, $tier = 0) {
		$indent = str_repeat(chr(9), $tier);
		foreach ($arr as $key => $value) {
			if (is_array($value)) {
				if($quoteTier0 = true && $tier == 0 || $tier > 0) {
					$key = '"' . $key . '"';
				}
				$str .= $indent . $key  . "\n" . $indent. "{\n";
 
				self::encodeSegment($str, $value, $tier+1);
 
				$str .= $indent . "}\n";
			} else {
				$str .= $indent . '"' . $key . '"' . chr(9) . '"' . $value . "\"\n";
			}
		}
		return $str;
	}
	static function encode($arr) {
		$str = "";
		self::encodeSegment($str, $arr);
		return $str;
	}
 
	static function encodeFile($file, $arr) {
		$contents = "";
		self::encodeSegment($contents, $arr);
 
		$fh = fopen($file, 'w');
		fwrite($fh, $contents);
		fclose($fh);
	}
 
    static function decode($string)
    {
        $string = self::RegexReplace('//.*',$string,'');
        $string = self::RegexReplace('\\s+',$string,' ');
        $_parsed = self::_parse($string);
        $res = $_parsed[0];
        $ptr = $_parsed[1];
		
        return $res;
    }
	
    static function decodeFile($path)
	{
		$contents = self::decode(file_get_contents($path));
		return $contents;
	}
	
    private static function ArraySearch($array,$value)
    {
        foreach($array as $k=>$v)
        {
            if($v == $value)
                return $k;
        }
        return -1;
    }
	
    private static function RegexReplace($pattern,$string,$replacement)
    {
        $regex = Regex::of($pattern)->with($string);
        return $regex->replace($replacement);
    }
}

?>