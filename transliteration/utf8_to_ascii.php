<?php



/**
* US-ASCII transliterations of Unicode text
* @version $Id: utf8_to_ascii.php,v 1.1.1.1 2006/03/03 23:43:09 harryf Exp $
* @package utf8_to_ascii
*/

/**
* Conversion "database" loaded into this array as needed
*/
$UTF8_TO_ASCII = array();

/* Start of Demo Section */
/*
$item = $_GET['item'];

$item = trim($item);
$item = str_replace('\\x', '&#', $item);
$text = stripslashes(htmlspecialchars_decode($item));
$out = utf8_to_ascii($text);
echo $out;
*/
/* End of Demo Section */

//--------------------------------------------------------------------
/**
* US-ASCII transliterations of Unicode text
* Ported Sean M. Burke's Text::Unidecode Perl module (He did all the hard work!)
* Also unicode ord() implementation embedded - from PHP manual (see below)
* @see http://search.cpan.org/~sburke/Text-Unidecode-0.04/lib/Text/Unidecode.pm
* @see http://www.php.net/manual/en/function.ord.php#46267
* @param string UTF-8 string to convert
* @param string (default = ?) Character use if character unknown
* @return string US-ASCII string
* @package utf8_to_ascii
* @TODO - this is a brute force implementation - may be smarter (faster?) to use preg_replace_callback
*/
function utf8_to_ascii($str, $unknown = '?') {
    
    global $UTF8_TO_ASCII;
    
    if ( strlen($str) == 0 ) { return; }
    
    preg_match_all('/.{1}|[^\x00]{1,1}$/us', $str, $ar); 
    $chars = $ar[0];
    
    foreach ( $chars as $i => $c ) {
    
        $ud = 0;
        if (ord($c{0})>=0 && ord($c{0})<=127) { continue; } // ASCII - next please
        if (ord($c{0})>=192 && ord($c{0})<=223) { $ord = (ord($c{0})-192)*64 + (ord($c{1})-128); }
        if (ord($c{0})>=224 && ord($c{0})<=239) { $ord = (ord($c{0})-224)*4096 + (ord($c{1})-128)*64 + (ord($c{2})-128); }
        if (ord($c{0})>=240 && ord($c{0})<=247) { $ord = (ord($c{0})-240)*262144 + (ord($c{1})-128)*4096 + (ord($c{2})-128)*64 + (ord($c{3})-128); }
        if (ord($c{0})>=248 && ord($c{0})<=251) { $ord = (ord($c{0})-248)*16777216 + (ord($c{1})-128)*262144 + (ord($c{2})-128)*4096 + (ord($c{3})-128)*64 + (ord($c{4})-128); }
        if (ord($c{0})>=252 && ord($c{0})<=253) { $ord = (ord($c{0})-252)*1073741824 + (ord($c{1})-128)*16777216 + (ord($c{2})-128)*262144 + (ord($c{3})-128)*4096 + (ord($c{4})-128)*64 + (ord($c{5})-128); }
        if (ord($c{0})>=254 && ord($c{0})<=255) { $chars{$i} = $unknown; continue; } //error
        
        $bank = $ord >> 8;
        
        if ( !array_key_exists($bank, $UTF8_TO_ASCII) ) {
            $bankfile = './transliteration/transliteration-files/'. sprintf("x%02x",$bank).'.php';
            if ( file_exists($bankfile) ) {
                include  $bankfile;
            } else {
                $UTF8_TO_ASCII[$bank] = array();
            }
        }
        
        $newchar = $ord & 255;
        if ( array_key_exists($newchar, $UTF8_TO_ASCII[$bank]) ) {
            $chars{$i} = $UTF8_TO_ASCII[$bank][$newchar];
        } else {
            $chars{$i} = $unknown;
        }
    }
    
    return implode('',$chars);
    
}
