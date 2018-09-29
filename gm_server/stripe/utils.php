<?php

function prettyPrint( $json )
{
	$result = '';
	$level = 0;
	$in_quotes = false;
	$in_escape = false;
	$ends_line_level = NULL;
	$json_length = strlen( $json );
	echo $json_length;
	for( $i = 0; $i < $json_length; $i++ ) {
		$char = $json[$i];
		$new_line_level = NULL;
		$post = "";
		if( $ends_line_level !== NULL ) {
			$new_line_level = $ends_line_level;
			$ends_line_level = NULL;
		}
		if ( $in_escape ) {
			$in_escape = false;
		} else if( $char === '"' ) {
			$in_quotes = !$in_quotes;
		} else if( ! $in_quotes ) {
			switch( $char ) {
				case '}': case ']':
					$level--;
					$ends_line_level = NULL;
					$new_line_level = $level;
					$char.="<br>";
					for($index=0;$index<$level-1;$index++){$char.="-----";}
					break;
	
				case '{': case '[':
					$level++;
					$char.="<br>";
					for($index=0;$index<$level;$index++){$char.="-----";}
					break;
				case ',':
					$ends_line_level = $level;
					$char.="<br>";
					for($index=0;$index<$level;$index++){$char.="-----";}
					break;
	
				case ':':
					$post = " ";
					break;
	
				case "\t": case "\n": case "\r":
					$char = "";
					$ends_line_level = $new_line_level;
					$new_line_level = NULL;
					break;
			}
		} else if ( $char === '\\' ) {
			$in_escape = true;
		}
		if( $new_line_level !== NULL ) {
			$result .= "\n".str_repeat( "\t", $new_line_level );
		}
		$result .= $char.$post;
	}
	
	echo "RESULTS ARE: <br><br>$result";
	return $result;

}
?>