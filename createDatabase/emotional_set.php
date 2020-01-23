<?php

/**
  * This class generate items which store emotional synsets
  */

class Emotionalset{

//properties declaration:

public $set = [] ;



/***Methods of class ***/



public function getSet()
	{
		for ($i=0 ; $i< sizeof($this->set) ; $i++)
			{
				//$this->set[$i]->printSynset();
				$this->set[$i]->printSynsetCoefficient();
				echo "<br>";
		}	
}


public function getSize()
	{
		return sizeof($this->set);
}



public function addSynset(Synset $synset)
	{
		array_push($this->set,$synset);
}



public function addSynsets($synset)
	{
		for ($i=0 ; $i<sizeof($synset) ; $i++)
			{
				array_push($this->set,$synset[$i]);
		}
}


public function getSynsetByID($value)
	{
		foreach ($this->set as $node )
			{
				if ($value==$node->getID())
					{
						return $node->getSynset();
						
						//return printed version
						//return $node->printSynset();
				}
		}
		//$string="Το ID που εισάγατε δεν αντιστοιχεί σε synset. Προσπαθήστε ξανά!"
		return -1;
}


public function getSynsetByILR($value)
	{
		foreach ($this->set as $node )
			{
				if ($value==$node->getILR())
					{
						return $node;
						
						//return printed version
						//return $node->printSynset();
				}
		}
		//$string="Το ILR που εισάγατε δεν αντιστοιχεί σε synset. Προσπαθήστε ξανά!"
		return -1;
}


public function getListID()
	{
		$table=array();
		for ($i=0 ; $i<sizeof($this->set) ; $i++)
			{
				$table[$i]=$this->set[$i]->getID();
		}
		return $table;
}


public function getListILR()
	{
		$table=array();
		for ($i=0 ; $i<sizeof($this->set) ; $i++)
			{
				$table[$i]=$this->set[$i]->getILR();
		}
		return $table;
}


public function countWordFreq($word)
	{
		$count=0;
		foreach ($this->set as $node)
			{
				foreach ($node->getSynset() as $table)
					{
						if ($table==$word)
							{
								$count++;
						}
				}
		}
		
		return $count;
}


}

?>