<?php

class compress {
	
	/**
	 * Caching function from CambioCMS.org
	 * @param $files array
	 * @param $type string
	 * @param $folder string
	 * @return string
	 */
	static function getCache($files, $type, $folder) {
		$db = db::getInstance();
		if (!$files) { return false; }
		// set vars
		$filenamehash = "";
		$timehash = "";
		// loop data and make hashes
		foreach ($files as $item) {
			$timehash .= filemtime($item);
			$filenamehash .= $item;
		}
		$filenamehash = md5($filenamehash);
		$timehash = md5($timehash);
		// look in database for cache entry for filenamehash
		$db->select("SELECT * FROM assetscache");
		$cacheEntries = $db->get_obj();
		foreach ($cacheEntries as $cache) {
			if ($cache->filenamehash == $filenamehash) {
				// does cache file exists?
				if (file_exists($cache->cachefile)) {
					if ($cache->timehash == $timehash) {
						// cache file!
						return $cache->cachefile;
					} else {
						// file exists, but is older, first delete old one and write new!
						unlink($cache->cachefile);
						$newcachefile = self::makeCacheFileName($filenamehash, $timehash, $type, $folder);
						self::saveFilesToCache($files, $newcachefile);
						$db->update_array("assetscache", array("timehash"=>$timehash, "cachefile"=>$newcachefile),"filenamehash = '$filenamehash'");
						return $newcachefile;
					}
				} else {
					// cache file does not exists, make new one						
				}
			}					
		}
		
		// data is not yet cached
		$newcachefile = self::makeCacheFileName($filenamehash, $timehash, $type, $folder);
		self::saveFilesToCache($files, $newcachefile);
		$db->insert_array("assetscache", array("filenamehash"=>$filenamehash,"timehash"=>$timehash, "cachefile"=>$newcachefile), true);
		return $newcachefile;		
	}
	
	static function saveFilesToCache($data, $filename) {
		global $cf;
		$output = "";
		foreach ($data as $item) {
			if (file_exists($item)) {
				$output .= file_get_contents($item)."\n\r";
			}
		}
		// detect file type for compression
		if (substr(strtolower($filename),-3)=="css" && !$cf->whDebug) {
			$output = self::remove_css_comments($output);	
		} elseif (substr(strtolower($filename),-2)=="js" && !$cf->whDebug) {
			$output = self::remove_js_comments($output);
		}
		
		$fh = fopen($filename, 'w') or die("Unable to open $filename, please contact the system administrator.");
			fwrite($fh, $output);
		fclose($fh);
		return true;
	}
	
	static function makeCacheFileName($filenamehash, $timehash, $extension, $folder) {
		return $folder.$filenamehash.".".$timehash.".".$extension;
	}
	
	/**
	 * function to read cache entries in database
	 * 
	 * @return object
	 */
	static function readCacheEntries() {
		$db = db::getInstance();
		$sql = "SELECT * FROM assetscache";
		$db->select($sql);
		return $db->get_obj();
	}
	
	/**
	 * JSmart Compressor v1.0 function
	 *
	 * @copyright farhadi.ir
	 * @param string $str
	 * @return string
	 */
		
	static function remove_css_comments($str) {
		$res = '';
		$i=0;
		$inside_block = false;
		while ($i+1<strlen($str)) {
			if ($str{$i}=='"' || $str{$i}=="'") {//quoted string detected
				$quote = $str{$i};
				do {
					if ($str{$i} == '\\') {
						$res .= $str{$i++};
					}
					$res .= $str{$i++};
				} while ($i<strlen($str) && $str{$i}!=$quote);
				$res .= $str{$i++};
				continue;
			} elseif (strtolower(substr($res, -4))=='url(') {//uri detected
				do {
					if ($str{$i} == '\\') {
						$res .= $str{$i++};
					}
					$res .= $str{$i++};
				} while ($i<strlen($str) && $str{$i}!=')');
				$res .= $str{$i++};
				continue;
			} elseif ($str{$i}.$str{$i+1}=='/*') {//css comment detected
				$i+=3;
				while ($i<strlen($str) && $str{$i-1}.$str{$i}!='*/') $i++;
				if ($current_char == "\n") $str{$i} = "\n";
				else $str{$i} = ' ';
			}
			
			if (strlen($str) <= $i+1) break;
			
			$current_char = $str{$i};
			
			if ($inside_block && $current_char == '}') {
				$inside_block = false;
			}
			
			if ($current_char == '{') {
				$inside_block = true;
			}
			
			if (preg_match('/[\n\r\t ]/', $current_char)) $current_char = " ";
			
			if ($current_char == " ") {
				$pattern = $inside_block?'/^[^{};,:\n\r\t ]{2}$/':'/^[^{};,>+\n\r\t ]{2}$/';
				if (strlen($res) &&	preg_match($pattern, $res{strlen($res)-1}.$str{$i+1}))
					$res .= $current_char;
			} else $res .= $current_char;
			
			$i++;
		}
		if ($i<strlen($str) && preg_match('/[^\n\r\t ]/', $str{$i})) $res .= $str{$i};
		return $res;
	}
	
	/**
	 * JSmart Compressor v1.0 function
	 *
	 * @copyright farhadi.ir
	 * @param string $str
	 * @return string
	 */
	
	static function remove_js_comments($str) {
		$res = '';
		$maybe_regex = true;
		$i=0;
		$current_char = '';
		while ($i+1<strlen($str)) {
			if ($maybe_regex && $str{$i}=='/' && $str{$i+1}!='/' && $str{$i+1}!='*') {//regex detected
				if (strlen($res) && $res{strlen($res)-1} === '/') $res .= ' ';
				do {
					if ($str{$i} == '\\') {
						$res .= $str{$i++};
					} elseif ($str{$i} == '[') {
						do {
							if ($str{$i} == '\\') {
								$res .= $str{$i++};
							}
							$res .= $str{$i++};
						} while ($i<strlen($str) && $str{$i}!=']');
					}
					$res .= $str{$i++};
				} while ($i<strlen($str) && $str{$i}!='/');
				$res .= $str{$i++};
				$maybe_regex = false;
				continue;
			} elseif ($str{$i}=='"' || $str{$i}=="'") {//quoted string detected
				$quote = $str{$i};
				do {
					if ($str{$i} == '\\') {
						$res .= $str{$i++};
					}
					$res .= $str{$i++};
				} while ($i<strlen($str) && $str{$i}!=$quote);
				$res .= $str{$i++};
				continue;
			} elseif ($str{$i}.$str{$i+1}=='/*') {//multi-line comment detected
				$i+=3;
				while ($i<strlen($str) && $str{$i-1}.$str{$i}!='*/') $i++;
				if ($current_char == "\n") $str{$i} = "\n";
				else $str{$i} = ' ';
			} elseif ($str{$i}.$str{$i+1}=='//') {//single-line comment detected
				$i+=2;
				while ($i<strlen($str) && $str{$i}!="\n") $i++;
			}
			
			$LF_needed = false;
			if (preg_match('/[\n\r\t ]/', $str{$i})) {
				if (strlen($res) && preg_match('/[\n ]/', $res{strlen($res)-1})) {
					if ($res{strlen($res)-1} == "\n") $LF_needed = true;
					$res = substr($res, 0, -1);
				}
				while ($i+1<strlen($str) && preg_match('/[\n\r\t ]/', $str{$i+1})) {
					if (!$LF_needed && preg_match('/[\n\r]/', $str{$i})) $LF_needed = true;
					$i++;
				}
			}
			
			if (strlen($str) <= $i+1) break;
			
			$current_char = $str{$i};
			
			if ($LF_needed) $current_char = "\n";
			elseif ($current_char == "\t") $current_char = " ";
			elseif ($current_char == "\r") $current_char = "\n";
			
			// detect unnecessary white spaces
			if ($current_char == " ") {
				if (strlen($res) &&
					(
					preg_match('/^[^(){}[\]=+\-*\/%&|!><?:~^,;"\']{2}$/', $res{strlen($res)-1}.$str{$i+1}) ||
					preg_match('/^(\+\+)|(--)$/', $res{strlen($res)-1}.$str{$i+1}) // for example i+ ++j;
					)) $res .= $current_char;
			} elseif ($current_char == "\n") {
				if (strlen($res) &&
					(
					preg_match('/^[^({[=+\-*%&|!><?:~^,;\/][^)}\]=+\-*%&|><?:,;\/]$/', $res{strlen($res)-1}.$str{$i+1}) ||
					(strlen($res)>1 && preg_match('/^(\+\+)|(--)$/', $res{strlen($res)-2}.$res{strlen($res)-1})) ||
					preg_match('/^(\+\+)|(--)$/', $current_char.$str{$i+1}) ||
					preg_match('/^(\+\+)|(--)$/', $res{strlen($res)-1}.$str{$i+1})// || // for example i+ ++j;
					)) $res .= $current_char;
			} else $res .= $current_char;
			
			// if the next charachter be a slash, detects if it is a divide operator or start of a regex
			if (preg_match('/[({[=+\-*\/%&|!><?:~^,;]/', $current_char)) $maybe_regex = true;
			elseif (!preg_match('/[\n ]/', $current_char)) $maybe_regex = false;
			
			$i++;
		}
		if ($i<strlen($str) && preg_match('/[^\n\r\t ]/', $str{$i})) $res .= $str{$i};
		return $res;
	}
		
}

?>