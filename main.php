<?php

session_start();

require'synset.php';
require'emotional_set.php';
require'word.php';
require'word_set.php';
require 'transliteration/utf8_to_ascii.php' ;
require 'stemming/words_stemming.php' ;

error_reporting(E_ALL & ~E_NOTICE);
ini_set('max_execution_time', 300);
 
// create 6 sets of emotional synsets
$synset_happy= new Emotionalset();
$synset_sadness= new Emotionalset();
$synset_anger= new Emotionalset();
$synset_fear= new Emotionalset();
$synset_disgust= new Emotionalset();
$synset_surprise= new Emotionalset();
 
// create the total emotional set ( synsets of all emotional sets )
$total_set= new Emotionalset();
 
//connect with DB
$USERNAME ="myuser";//$_SESSION["username"];  //database username
$PASSWORD ="12345";//$_SESSION["password"];  //database password
$DATABASE ="wordnet";//$_SESSION["database"];  //database name
$URL ="localhost";//$_SESSION["url"];    		   //database location

$con = mysql_connect($URL, $USERNAME, $PASSWORD);
if (!$con) 
    {
        die('Could not connect: ' . mysql_error());
}
 
//select DB and check user
$sql=mysql_select_db($DATABASE, $con) or die('Cannot connect to database.');
mysql_query("SET NAMES `utf8`",$con);
 
 
 
//edit the emotional keywords table
//get from database words with a particular appearance frequency
$Query = "SELECT * FROM `words` WHERE `Frequency` > 2";
$check_user = mysql_query($Query) or die("Prosoxi!Provlima stin epilogi pinaka user : " . mysql_error());
$number = mysql_num_rows( $check_user );
 
if ( empty($number))
    {
        echo "Πρόβληµα στη σύνδεση. Παρακαλώ ξαναπροσπαθήστε. <br>";
        exit;
}
else
    {
        while ($user = mysql_fetch_array( $check_user)) 
            {
                //get the synonyms of a word via synset table
                $query=mysql_query("SELECT * FROM `synset` WHERE `WORD`='$user[Word]'");
                while ($row=mysql_fetch_array($query))
                    {
                        //parse the synonym string
                        $key=extract_synonyms($row['SYNONYM']);
                        //create the synset
                        $synset= new Synset($key,$user['Synset'],$row['ID'],$row['ILR']);
                        //insert  synset into emotional set
                        $total_set->addSynset($synset);
                        classify($synset);
                }
        }
}
  
// restore all the synsets from the @total_set 
// which contain in their ILR the string 'similar_to'
$ILRtable=$total_set->getListILR();
$similar=array();
for ($i=0 ; $i<sizeof($ILRtable) ; $i++)
    {
        if (strpos($ILRtable[$i],'similar_to')==true)
            {
                array_push($similar,$ILRtable[$i]);
        }
}
 
$loop=1;
while ($similar && $loop<4)
    {
        $loop++;
        $new_similar=array();
        foreach ($similar as $node)
            {
                $table=extract_synonyms($node);
                $temp=end($table);
                //get all the synsets from the database which have the same ID with the ILR of the synset-key 
                $query=mysql_query("SELECT * FROM `synset` WHERE `ID`='$temp'");
                $number=mysql_num_rows($query);
                if ($number==0)
                    {
                        exit;
                }
                else
                    {
                        while ($row=mysql_fetch_array($query))
                            {
                                //parse the synonym string
                                $key=extract_synonyms($row['SYNONYM']);
                                //create a synset
                                $synset= new Synset($key,$total_set->getSynsetByILR($node)->getEmotion(),$row['ID'],$row['ILR']);
                                $synset->setCoefficient(0.1*$loop);
                                // add the synset to the total set
                                $total_set->addSynset($synset);
                                classify($synset);
                                if (strpos($synset->getILR(),'similar_to')==true)
                                    {
                                        array_push($new_similar,$synset->getILR());
                                }       
                        }
                }
        }
        $similar=$new_similar;
}
  
 
//create word set
$word_set = new Wordset();
 
// calculate words' weights
foreach($total_set->set as $node)
    {
        $qi=array();
        //get the coefficient of the word
        $coefficient=$node->getCoefficient();
        foreach ($node->getSynset() as $temp)
            {
                //search for words that ends with comma
				$string=$temp.",";
                //calculate the number of all synsets the word belongs to    
                $query=mysql_query("SELECT count(*) FROM `synset` WHERE `SYNONYM`  like '%$string%' ");
                $count=mysql_fetch_array($query);
                $ni=$count[0];
                //calculate the number of emotional synsets for a word in every emotional type
                $qi['h']=$synset_happy->countWordFreq($temp);
                $qi['sd']=$synset_sadness->countWordFreq($temp);
                $qi['a']=$synset_anger->countWordFreq($temp);
                $qi['f']=$synset_fear->countWordFreq($temp);
                $qi['d']=$synset_disgust->countWordFreq($temp);
                $qi['su']=$synset_surprise->countWordFreq($temp);
                //create a word and transport its variables
                $word=new Word($temp,$ni,$qi,$coefficient);
                // add the word to the word set
                $word_set->addWord($word);
        }
}
 
//print every synset in its emotion
//printSynsets();
 
//print word set with the variables
//$word_set->getSet();
 
//print wordset with weights
$word_set->getSetWeights();  
 
 
// restore weights to database
if( mysql_query("DESCRIBE `weights`")) 
    {
        //delete the table
        mysql_query("TRUNCATE table `weights` ");
        //create a new table
        $query=mysql_query("CREATE TABLE `weights`
        (
        `ID` int(30) AUTO_INCREMENT,
        `Word` varchar(900) COLLATE utf8_unicode_ci ,
        `Happyness` float(30),
        `Sadness` float(30),
        `Anger` float(30),
        `Fear` float(30),
        `Disgust` float(30),
        `Surprise` float(30),
        `Transliterate` varchar(900) COLLATE utf8_unicode_ci ,
        `Stemming` varchar(900) COLLATE utf8_unicode_ci ,
         PRIMARY KEY (`ID`)   
        )");
        //insert into the table the weights of every word
        foreach ($word_set->set as $node)
            {
                $temp=$word_set->getWordWeight($node);               
                $name=$node->getWord();
                $greeklish=transliterate($name);
                $stemming=stemming($name);
                mysql_query("INSERT INTO `weights`(`Word`, `Happyness`, `Sadness`, `Anger`, `Fear`, `Disgust`, `Surprise`,`Transliterate`,`Stemming`) 
                VALUES ('$name','$temp[h]','$temp[sd]','$temp[a]','$temp[f]','$temp[d]','$temp[su]','$greeklish','$stemming')");
        }   
}
else
    {
        // do the same procedure without table delete
		$query=mysql_query("CREATE TABLE `weights`
        (`ID` int(30) AUTO_INCREMENT,
        `Word` varchar(900) COLLATE utf8_unicode_ci ,
        `Happyness` float(30),
        `Sadness` float(30),
        `Anger` float(30),
        `Fear` float(30),
        `Disgust` float(30),
        `Surprise` float(30),
        `Transliterate` varchar(900) COLLATE utf8_unicode_ci ,
        `Stemming` varchar(900) COLLATE utf8_unicode_ci ,
         PRIMARY KEY (`ID`))");
        foreach ($word_set->set as $node)
            {
                $temp=$word_set->getWordWeight($node);
                $name=$node->getWord();
                $greeklish=transliterate($name);
                $stemming=stemming($name);
                mysql_query("INSERT INTO `weights`(`Word`, `Happyness`, `Sadness`, `Anger`, `Fear`, `Disgust`, `Surprise`,`Transliterate`,`Stemming`) 
                VALUES ('$name','$temp[h]','$temp[sd]','$temp[a]','$temp[f]','$temp[d]','$temp[su]','$greeklish','$stemming')");
        }
}
 
 
 
 
 
/**************functions*********************/
 
 
/**
 * This function extract the synonyms of a synset (@key)
 */
 
function extract_synonyms($key)
    {
        $table  = explode("#",$key);
        for ($i=0; $i<sizeof($table); $i++)
            {
                $table[$i] = substr($table[$i], 0, strpos($table[$i], ","));
        }
        //array_shift($table);
        return $table;
}
 
 
/**
 * This function classifies a synset (@synset) into an emotional set according to its emotion
 */
      
function classify($synset)
    { 
        global $synset_happy,$synset_sadness, $synset_anger, $synset_fear, $synset_disgust, $synset_surprise;
         
        switch ($synset->getEmotion())
            {
                case 'ΧΑΡΑ': $synset_happy->addSynset($synset); break;
                case 'ΛΥΠΗ': $synset_sadness->addSynset($synset);break;
                case 'ΘΥΜΟΣ':$synset_anger->addSynset($synset);break;
                case 'ΦΟΒΟΣ': $synset_fear->addSynset($synset);break;
                case 'ΑΠΕΧΘΕΙΑ': $synset_disgust->addSynset($synset);break;
                case 'ΕΚΠΛΗΞΗ': $synset_surprise->addSynset($synset);break;
                default: echo "problem, try again!!";break;
        }
}
 
 
function printSynsets()
    {
        global $synset_happy,$synset_sadness, $synset_anger, $synset_fear, 
        $synset_disgust, $synset_surprise;
         
        echo "<h1> ΧΑΡΑ </h1>";
        echo "<br> *************************** <br>";
        $synset_happy->getSet();
        echo "<h1> ΛΥΠΗ </h1>";
        echo "<br> *************************** <br>";
        $synset_sadness->getSet();
        echo "<h1> ΘΥΜΟΣ </h1>";
        echo "<br> *************************** <br>";
        $synset_anger->getSet();
        echo "<h1> ΦΟΒΟΣ </h1>";
        echo "<br> *************************** <br>";
        $synset_fear->getSet();
        echo "<h1> ΑΠΕΧΘΕΙΑ </h1>";
        echo "<br> *************************** <br>";
        $synset_disgust->getSet();
        echo "<h1> ΕΚΠΛΗΞΗ </h1>";
        echo "<br> *************************** <br>";
        $synset_surprise->getSet();
}   
 
/**
 * This function transforms the word (@item) from Greek to Greeklish
 */
 
function transliterate($item)
    {
        $item = trim($item);
        $item = str_replace('\\x', '&#', $item);
        $text = stripslashes(htmlspecialchars_decode($item));
        $out = utf8_to_ascii($text);
        return $out;
}
 
/**
 * This function returns the stem of a word (@item) 
 */
 
function stemming($item)
    {
        $temp=array();
        $temp=words_stemming($item);
        $out=implode($temp);
        return $out;
}
         
?>