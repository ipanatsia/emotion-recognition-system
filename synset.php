<?php

/**
  * This class depicts the structure of a synset
  */

class Synset{

//properties declaration:

var $synset=[];
var $emotion;
var $coefficient=0.1;
var $ID;
var $ILR;

const HAPPY = "ΧΑΡΑ";
const SADNESS = "ΛΥΠΗ";
const ANGER = "ΘΥΜΟΣ";
const FEAR = "ΦΟΒΟΣ";
const DISGUST = "ΑΠΕΧΘΕΙΑ";
const SURPRISE = "ΕΚΠΛΗΞΗ";

/***Methods of class ***/ 


public function __construct($synset,$emotion,$ID,$ILR)
	{
		self::setSynset($synset);
		self::setEmotion($emotion);
		$this->ID=$ID;
		$this->ILR=$ILR;
}

public function getCoefficient()
	{
		return $this->coefficient;
}


public function setCoefficient( $value)
	{
		$this->coefficient=$value;
}
   


public function getSynset()
	{
		return $this->synset;
}



public function setSynset($synset)
	{
		if (is_array($synset))
			{
				$this->synset=$synset;
		}
		else
			{
				array_push($this->synset,$synset);
		}
}


public function printSynset()
	{
		for ($i=0 ; $i<sizeof($this->synset) ; $i++)
			{
				echo " | ", $this->synset[$i], " | ";
		}
}


public function printSynsetDetailed()
	{
		/* detailed print*/
		echo "<table style=\"width:100%\">","<tr>";
		echo "<td><b> ID: </b> ", $this->ID, "</td> <td><b> Synset : </b>", self::printSynset() , "</td> <td><b> Emotion : </b>", $this->emotion , "</td> <td><b> Coefficient : </b>", $this->coefficient, "</td> <td><b> ILR : </b> ", $this->ILR , " </td></tr></table>" ;
}

public function printSynsetCoefficient()
	{
		/* detailed print*/
		echo "<table style=\"width:60%\">","<tr>";
		echo "<td><b> Synset : </b>", self::printSynset() , "</td> <td><b> Coefficient : </b>", $this->coefficient, "</td><td><b> ID : </b>", $this->ID, "</td> </tr></table>" ;
}


public function getEmotion()
	{
		return $this->emotion;
}


public function setEmotion($value)
	{
		switch ($value)
			{
				case Synset::HAPPY: $this->emotion=Synset::HAPPY; break;
				case Synset::SADNESS: $this->emotion=Synset::SADNESS;break;
				case Synset::ANGER: $this->emotion=Synset::ANGER;break;
				case Synset::FEAR: $this->emotion=Synset::FEAR;break;
				case Synset::DISGUST: $this->emotion=Synset::DISGUST;break;
				case Synset::SURPRISE: $this->emotion=Synset::SURPRISE;break;
				default: echo "problem, try again!!";break;
		}
}


public function getID()
	{
		return $this->ID;
}



public function getILR()
	{
		return $this->ILR;
}


public function setILR( $value)
	{
		$this->ILR=$value;
}

}

?>