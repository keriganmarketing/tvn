<?php 
function searchXML($field,$toSearch){	
	preg_match_all('/<'.$field.'>(.*)<\/'.$field.'>/U', $toSearch, $out);
	if(isset($out[1])){
        return strip_tags(trim($out[1][0]));
    } else {
        return "";
    }
}