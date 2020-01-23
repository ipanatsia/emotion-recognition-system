<?php
/* Flexible English/Greek Stemmer 
by Stergios Tegos,
Multimedia Lab, School of Informatics,
Aristotle University of Thessaloniki
*******************
Based on the work by George Ntais, Spyros Saroukos & Richard Heyes.
*/

/* Demo Section (used only for testing) */
/*
$demoSentence = $_GET['sentence'];
var_dump(words_stemming($demoSentence));
*/
/* - */

// Input: Sentence/Keywords etc
// Output: Array of stemmed words
function words_stemming($q) {
	$stemmed_words = array();
	$q_pieces = explode(" ", $q);
	foreach ($q_pieces as $q_pieces_value) {
		if (preg_match("/^[a-zA-Z\p{Cyrillic}0-9\s\-]+$/u", $q_pieces_value)) {
			require_once 'stem_english.php';
			$stem = PorterStemmer::Stem($q_pieces_value);
			$stemmed_words[] = $stem;
		} else {
			require_once 'stem_greek.php';
			$stem = array();
			$stem = stemWord($q_pieces_value);
			$stemmed_words[] = $stem[0];
		}
	}
	return $stemmed_words;
}
?>