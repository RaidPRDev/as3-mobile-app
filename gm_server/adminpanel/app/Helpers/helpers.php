<?php

/**
 * trims text to a space then adds ellipses if desired
 * @param string $input text to trim
 * @param int $length in characters to trim to
 * @param bool $ellipses if ellipses (...) are to be added
 * @param bool $strip_html if html tags are to be stripped
 * @return string 
 */
function trim_text($input, $length, $ellipses = true, $strip_html = true) {
    //strip tags, if desired
    if ($strip_html) {
        $input = strip_tags($input);
    }
  return  $input;
    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
        return $input;
    }
  
    //find last space within length
    $last_space = strrpos(substr($input, 0, $length), ' ');
    $trimmed_text = substr($input, 0, $last_space);
  
    //add ellipses (...)
    if ($ellipses) {
        $trimmed_text .= '...';
    }
  	
    return $trimmed_text;
}

function myTruncate($string, $limit, $break=".", $pad="...") {
        if(strlen($string) <= $limit) return $string; 
        if(false !== ($breakpoint = strpos($string, $break, $limit))) { 
            if($breakpoint < strlen($string) - 1) { 
                $string = substr($string, 0, $breakpoint) . $pad; } 
            } return $string; 
}

function truncate($text, $length = 100, $options = array()) {
	
    $default = array(
        'ending' => '...', 'exact' => true, 'html' => true
    );
    $options = array_merge($default, $options);
	
    extract($options);

	//echo "<pre>";var_dump($options);exit;
    if ($html) {
        if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        $totalLength = mb_strlen(strip_tags($ending));
        $openTags = array();
        $truncate = '';

        preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
        foreach ($tags as $tag) {
            if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                    array_unshift($openTags, $tag[2]);
                } else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                    $pos = array_search($closeTag[1], $openTags);
                    if ($pos !== false) {
                        array_splice($openTags, $pos, 1);
                    }
                }
            }
            $truncate .= $tag[1];

            $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
            if ($contentLength + $totalLength > $length) {
                $left = $length - $totalLength;
                $entitiesLength = 0;
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                    foreach ($entities[0] as $entity) {
                        if ($entity[1] + 1 - $entitiesLength <= $left) {
                            $left--;
                            $entitiesLength += mb_strlen($entity[0]);
                        } else {
                            break;
                        }
                    }
                }

                $truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
                break;
            } else {
                $truncate .= $tag[3];
                $totalLength += $contentLength;
            }
            if ($totalLength >= $length) {
                break;
            }
        }
    } else {
        if (mb_strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
        }
    }
    if (!$exact) {
        $spacepos = mb_strrpos($truncate, ' ');
        if (isset($spacepos)) {
            if ($html) {
                $bits = mb_substr($truncate, $spacepos);
                preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                if (!empty($droppedTags)) {
                    foreach ($droppedTags as $closingTag) {
                        if (!in_array($closingTag[1], $openTags)) {
                            array_unshift($openTags, $closingTag[1]);
                        }
                    }
                }
            }
            $truncate = mb_substr($truncate, 0, $spacepos);
        }
    }
    $truncate .= $ending;

    if ($html) {
        foreach ($openTags as $tag) {
            $truncate .= '</'.$tag.'>';
        }
    }
	echo $truncate;
    
	
    //return $truncate;
}

  function truncateHTML($str, $len, $end = '&hellip;'){
        //find all tags
        $tagPattern = '/(<\/?)([\w]*)(\s*[^>]*)>?|&[\w#]+;/i';  //match html tags and entities
        preg_match_all($tagPattern, $str, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER );
        //WSDDebug::dump($matches); exit; 
        $i =0;
        //loop through each found tag that is within the $len, add those characters to the len,
        //also track open and closed tags
        // $matches[$i][0] = the whole tag string  --the only applicable field for html enitities  
        // IF its not matching an &htmlentity; the following apply
        // $matches[$i][1] = the start of the tag either '<' or '</'  
        // $matches[$i][2] = the tag name
        // $matches[$i][3] = the end of the tag
        //$matces[$i][$j][0] = the string
        //$matces[$i][$j][1] = the str offest

        while($matches[$i][0][1] < $len && !empty($matches[$i])){

            $len = $len + strlen($matches[$i][0][0]);
            if(substr($matches[$i][0][0],0,1) == '&' )
                $len = $len-1;


            //if $matches[$i][2] is undefined then its an html entity, want to ignore those for tag counting
            //ignore empty/singleton tags for tag counting
            if(!empty($matches[$i][2][0]) && !in_array($matches[$i][2][0],array('br','img','hr', 'input', 'param', 'link'))){
                //double check 
                if(substr($matches[$i][3][0],-1) !='/' && substr($matches[$i][1][0],-1) !='/')
                    $openTags[] = $matches[$i][2][0];
                elseif(end($openTags) == $matches[$i][2][0]){
                    array_pop($openTags);
                }else{
                    $warnings[] = "html has some tags mismatched in it:  $str";
                }
            }


            $i++;

        }

        $closeTags = '';

        if (!empty($openTags)){
            $openTags = array_reverse($openTags);
            foreach ($openTags as $t){
                $closeTagString .="</".$t . ">"; 
            }
        }

        if(strlen($str)>$len){
            // Finds the last space from the string new length
            $lastWord = strpos($str, ' ', $len);
            if ($lastWord) {
                //truncate with new len last word
                $str = substr($str, 0, $lastWord);
                //finds last character
                $last_character = (substr($str, -1, 1));
                //add the end text
                $truncated_html = ($last_character == '.' ? $str : ($last_character == ',' ? substr($str, 0, -1) : $str) . $end);
            }
            //restore any open tags
            $truncated_html .= $closeTagString;


        }else
        $truncated_html = $str;


        return $truncated_html; 
    }

	function textWrap($text) { 
	        $new_text = ''; 
	        $text_1 = explode('>',$text); 
	        $sizeof = sizeof($text_1); 
	        for ($i=0; $i<$sizeof; ++$i) { 
	            $text_2 = explode('<',$text_1[$i]); 
	            if (!empty($text_2[0])) { 
	                $new_text .= preg_replace('#([^\n\r .]{25})#i', '\\1  ', $text_2[0]); 
	            } 
	            if (!empty($text_2[1])) { 
	                $new_text .= '<' . $text_2[1] . '>';    
	            } 
	        } 
	        return $new_text; 
	} 
?>