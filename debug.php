<?php
 
class Debug
{
	protected static function varDump($var)
	{
		if (php_sapi_name() == 'cli')
		{
			var_dump($var);
		}
		else
		{
			ob_start();
			var_dump($var);
			$dump = ob_get_contents();
			ob_end_clean();
			echo htmlspecialchars($dump, ENT_QUOTES);
		}
	}

	protected static function parseObject($obj, $values=true)
	{ 
	    $obj_dump  = print_r($obj, 1);
	    $ret_list = array();
	    $ret_map = array();
	    $ret_name = '';
	    $dump_lines = preg_split('/[\r\n]+/',$obj_dump);
	    $ARR_NAME = 'arr_name';
	    $ARR_LIST = 'arr_list';
	    $arr_index = -1;
	   
	    // get the object type...
	    $matches = array();
	    preg_match('/^\s*(\S+)\s+\bObject\b/i',$obj_dump,$matches);
	    if(isset($matches[1])){ $ret_name = $matches[1]; }//if
	   
	    foreach($dump_lines as &$line){
	   
	      $matches = array();
	   
	      //load up var and values...
	      if(preg_match('/^\s*\[\s*(\S+)\s*\]\s+=>\s+(.*)$/', $line, $matches)){
	       
	        if(mb_stripos($matches[2],'array') !== false){
	       
	          $arr_map = array();
	          $arr_map[$ARR_NAME] = $matches[1];
	          $arr_map[$ARR_LIST] = array();
	          $arr_list[++$arr_index] = $arr_map;
	       
	        }else{
	       
	          // save normal variables and arrays differently...
	          if($arr_index >= 0){ 
	            $arr_list[$arr_index][$ARR_LIST][$matches[1]] = $matches[2];
	          }else{
	            $ret_list[$matches[1]] = $matches[2];
	          }//if/else
	       
	        }//if/else
	     
	      }else{
	     
	        // save the current array to the return list...
	        if(mb_stripos($line,')') !== false){
	       
	          if($arr_index >= 0){
	           
	            $arr_map = array_pop($arr_list);
	           
	            // if there is more than one array then this array belongs to the earlier array...
	            if($arr_index > 0){
	              $arr_list[($arr_index-1)][$ARR_LIST][$arr_map[$ARR_NAME]] = $arr_map[$ARR_LIST];
	            }else{
	              $ret_list[$arr_map[$ARR_NAME]] = $arr_map[$ARR_LIST];
	            }//if/else
	           
	            $arr_index--;
	           
	          }//if
	       
	        }//if
	     
	      }//if/else
	     
	    }//foreach
	   
	    $ret_map['class'] = $ret_name;
	    $ret_map['members'] = $ret_list;
	    return $ret_map;
	   
	  }//method
		 
	  
	/**
	 * Dump any kind of variable in a table (array, object, etc..)
	 *
	 * @param mixed $data
	 * @return null
	 */
	public static function dump($data, $firstLevel = true)
	{
		if ($firstLevel && count($data) == 1)
			$data = $data[0];
		
		if (php_sapi_name() == 'cli')
		{
			return self::varDump($data);
		}
			
		if (is_object($data))
		{
			return self::dump(self::parseObject($data), false);
		}
		else if (is_array($data))
		{
			echo '<table style="padding: 0px; border: solid 2px red; border-collapse:collapse;">';
			foreach ($data as $key1 => $elem1)
			{
				echo '<tr><td style="padding: 0px; border: solid 1px grey;"><pre>';
	            self::varDump($key1);
	            echo '</pre></td><td style="padding: 0px; border: solid 1px grey;">';
				self::dump($elem1, false);
				echo '</td></tr>';
			}
			echo '</table>';
		}
		else if (null === $data)
		{
			echo "NULL VALUE";
		}
		else
		{
			echo "<pre>";
			self::varDump($data);
			echo "</pre>";
			return;
		}
	}
}

function v()
{
	Debug::dump(func_get_args());
}

function w()
{
	$isHtml = (php_sapi_name() != 'cli');
	echo "\n_________________________________________________________________________________________________________________________" .($isHtml ? '</br>': '') . "\n";
	Debug::dump(func_get_args());
	echo "\n" .($isHtml ? '</br>': '') . "_________________________________________________________________________________________________________________________" .($isHtml ? '<pre>': '') . "\n";
	debug_print_backtrace();
	echo "" .($isHtml ? '</pre>': '') . "_________________________________________________________________________________________________________________________" .($isHtml ? '</br>': '') . "\n";
	die("script aborted on purpose.\n");
}
