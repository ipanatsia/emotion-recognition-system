<?php

/**
  * This class represents the structure of the word
  */
  
class Word{

//properties declaration:

var $word;
var $ni;
var $qi=[];
var $coefficient;
var $weight=[];


/***Methods of class ***/ 

function __construct($string,$ni,$qi,$coefficient) 
	{
    	$this->word=$string;
		$this->ni=$ni;
		$this->qi=$qi;
		$this->coefficient=$coefficient;
}



public function getCoefficient()
	{
		return $this->coefficient;
}


   
public function getWord()
	{
		return $this->word;
}


public function getNi()
	{
		return $this->ni;
}


public function getQi()
	{
		return $this->qi;
}



public function printWord()
	{
		echo "<b> Word : </b>", $this->word , " <b> Ni : </b>", $this->ni, " <b> Qki : </b>", print_r($this->qi), "<b> Coefficient : </b>", $this->coefficient ;

}


public function getWeight()
	{
		self::setWeight();
		return $this->weight;
}

private function setWeight()
	{
		$emo_types=array("h","sd","a","f","d","su");
		for ($i=0 ; $i<sizeof($emo_types) ; $i++)
			{
				if (($this->qi[$emo_types[$i]]<>0)&&($this->ni<>0))
					{
						$this->weight[$emo_types[$i]]= ($this->qi[$emo_types[$i]]/$this->ni)*(1-($this->qi[$emo_types[$i]]*$this->coefficient)/$this->qi[$emo_types[$i]]);
						$this->weight[$emo_types[$i]] = sprintf("%01.2f", $this->weight[$emo_types[$i]]);
				}
				else
					{
						$this->weight[$emo_types[$i]]=0.0;
				}
		}
}

}

?>