<?php

/**
  * This class represents the methods and fields of a set of words.
  */
  

class Wordset{

//properties declaration:

public $set = [] ;




/***Methods of class ***/


public function getSet()
	{
		 
		for ($i=0 ; $i< sizeof($this->set) ; $i++)
			{
				$this->set[$i]->printWord();
				echo "<br>";
		}
}


public function getSize()
	{
		return sizeof($this->set);
}

public function addWord(Word $word)
	{
		$table=array();
		foreach ($this->set as $node)
			{
				array_push($table,$node->getWord());
		}
		
		// if the word already exists, don't move it into the array
		if (!(in_array($word->getWord(),$table)))
			{
				array_push($this->set,$word);
		}
		
}



public function getSetWeights()
	{
		echo "<table border=\"1\" style=\"width:50%\">";
		echo "<tr> <td> <b> ID </b> </td> <td> <b> Word </b> </td><td> <b> H </b> </td> <td> <b> Sd </b> </td><td> <b> A </b> </td><td> <b> F </b> </td><td> <b> D </b> </td><td> <b> Su </b> </td></tr>";
		$i=0;
		foreach ( $this->set as $node)
			{
				$weight=$node->getWeight();
				echo "<tr><td>".++$i."</td><td>".$node->getWord()."</td>";
				foreach ($weight as $temp)
					{
						echo "<td>".$temp."</td>";
				}
				echo "<tr>";
		}
		echo "</table>";
		
}

public function getWordWeight(Word $word)
	{
		
		
		foreach ( $this->set as $node)
			{
				if ($node->getWord()==$word->getWord())
					{
						$weight=$node->getWeight();
						return $weight;
				}
		
		
		}
}

}

?>