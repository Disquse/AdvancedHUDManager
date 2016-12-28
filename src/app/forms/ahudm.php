<?php
namespace ahudm;

class KVReader {
	public $quoteTier0 = true;
 
	public function __construct() {
 
	}

	public function write($arr) {
		$str = "";
		$this->writeSegment($str, $arr);
		return $str;
	}
 
	public function writeFile($file, $arr) {
		$contents = "";
		$this->writeSegment($contents, $arr);
 
		$fh = fopen($file, 'w');
		fwrite($fh, $contents);
		fclose($fh);
	}
 
	public function read($contents) {
		$out = array();
		$lines = explode("\n", $contents);
		$idx = 0;
		$this->readSegment($lines, $idx, count($lines), $out);
		return $out;
	}
 
	public function readFile($file) {
		return $this->read(file_get_contents($file));
	}
 
	private function writeSegment(&$str, $arr, $tier = 0) {
		$indent = str_repeat(chr(9), $tier);
		foreach ($arr as $key => $value) {
			if (is_array($value)) {
				if($this->quoteTier0 && $tier == 0 || $tier > 0) {
					$key = '"' . $key . '"';
				}
				$str .= $indent . $key  . "\n" . $indent. "{\n";
 
				$this->writeSegment($str, $value, $tier+1);
 
				$str .= $indent . "}\n";
			} else {
				$str .= $indent . '"' . $key . '"' . chr(9) . '"' . $value . "\"\n";
			}
		}
		return $str;
	}
 
	private function readSegment($lines, &$index, $linecount, &$output) {
		$key = false;
		while ($index <= $linecount) {
			$pos = 0;
			$line = $lines[$index++];
			$len = strlen($line);
			while ($pos < $len) {
				if($turnoffcomment == true) {
					$comment = false;
					$turnoffcomment = false;
				}
				$char = substr($line , $pos, 1);
				if ($char == " " || $char == "\t" || $char == "\r" || $char == "\n" ) {
					$pos++;
					continue;
				}
				if ($char == "/") {
					$char2 = substr($line, $pos, 2);
					if ($char2 == "/*") {
						$comment = true;
					} else if ($char2 == '//') {
						break;
					}
					$char2 = substr($line, $pos-1, 2);
					if ($char2 == "*/" && $comment == true ) {
						$turnoffcomment = true;
					}
				}
				if($comment) {
					$pos++;
					continue;
				}
 
				switch($char) {
					case "{":
						if ($key) {
							$arr = array();
							$this->readSegment($lines, $index, $linecount, $arr);
							$output[$key] = $arr;
							$key = false;
						}
						break;
					case "}":
						return;
					case "\"":
						$pos2 = strpos($line , "\"", $pos+1);
						$val = substr($line, $pos+1, (($pos2-1)-($pos)));
						$pos = $pos2;
 
						if (!$key) {
							$key = $val;
						} else {
							$output[$key] = $val;
							$key = false;
						}
						break;
				}
				$pos++;
			}
		}
	}
}
?>