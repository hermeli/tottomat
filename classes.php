<?php
setlocale(LC_ALL, 'UTF-8');
/***********************************************************************
* Trikot-Totto Tottomat (Tippspiel für die Fussball EM/WM) 
* ----------------------------------------------------------------------
* Datei: classes.php
* 
* Klassendefinitionen und Methoden
* Email: wyss@superspider.net
***********************************************************************/

/***********************************************************************
* CLASS player
* Basic class for a player. Mainly holds values like 'TotalGoals'
***********************************************************************/
class player
{
	var $groupFavorite;
	var $totalGoals;
	var $name;
	var $username;
	var $champion;
	var $score;
}
/***********************************************************************
* CLASS team
* Basic class for a team. A team is part of multiple matches.
***********************************************************************/
class team
{
	var $teamNr;
	var $group;
	var $name;

	var $score;
	var $diffgoals;
	var $goals;
	
	function team($tmnr, $grp, $nme)
	{
		$this->teamNr = $tmnr;
		$this->group = $grp;
		$this->name = $nme;
		
		$this->score = 0;
		$this->diffgoals = 0;
		$this->goals = 0;
	}
}
/***********************************************************************
* CLASS match
* Holds two teams and the score of the match
***********************************************************************/
class match
{
	var $matchNr;
	var $group;
	
	var $team1;
	var $team1Pts;			// Punktzahl des Team1-Tipps
	var $team1BgColor;
	
	var $team2;
	var $team2Pts;			// Punktzahl des Team2-Tipps
	var $team2BgColor;
	
	var $matchRes;			// Matchergebnis, z.B: 1:2
	var $matchResPts;		// Punktzahl des Spielresultats
	var $matchResBgColor;	// Hintergrundfarbe		
	
	var $matchTotalPts;		// komplette Punktzahl des Matches
	
	// class constructor
    function match($mNr,$grp,$t1,$t2)
    {		
		$this->matchNr = $mNr;
		$this->group = $grp;
		$this->team1 = $t1;
		$this->team2 = $t2;
		
		$this->team1Pts = 0;
		$this->team1BgColor = "lightgray";
		
		$this->team2Pts = 0;
		$this->team2BgColor = "lightgray";
		
		$this->matchRes = "";
		$this->matchResPts = 0;
		$this->matchResBgColor = "";
		
		$this->matchTotalPts = 0;
	}

	/***********************************************************************
	* calculateGroupMatchPoints($matchRes_Master,$groupFavorite)
	***********************************************************************/ 
	function calculateGroupMatchPoints($matchRes_Master,$groupFavorite)
	{
		if (!checkGame($matchRes_Master)) return 0;
		if (!checkGame($this->matchRes)) return 0;
		
		// check for group favorite multiplier
		$mult = 1;
		if ( (floor(($this->matchNr)/6)) == array_search($groupFavorite,array("A","B","C","D","E","F","G","H")) ) 
			$mult=2;
	
		// check if full hit -> 12 points
		if ($this->matchRes == $matchRes_Master) 
		{
			$this->matchResPts = 12 * $mult;
			$this->matchResBgColor = "forestgreen";
			return;
		}
			
		// check if partial hit -> 5 points
		$mparts = explode(":", $matchRes_Master);
		$uparts = explode(":", $this->matchRes);	
		
		$leftmore = (($mparts[0] > $mparts[1]) && ($uparts[0] > $uparts[1]));
		$even = (($mparts[0] == $mparts[1]) && ($uparts[0] == $uparts[1])); 
		$rightmore = (($mparts[0] < $mparts[1]) && ($uparts[0] < $uparts[1]));
		if ( $leftmore || $even || $rightmore )
		{
			$this->matchResPts = 5 * $mult;
			$this->matchResBgColor = "lightgreen";
		}
	}
	
	/***********************************************************************
	* calculateFinalMatchPoints($matchRes_Master,$teamList,$opponentDictionary)
	***********************************************************************/ 
	function calculateFinalMatchPoints($teamList,$opponentDictionary)
	{		
		if (!$this->checkTeams()) return;
		
		// check if user team is in list of master teams -> 10 Points
		if (in_array($this->team1->name,$teamList))
		{ 
			$this->team1Pts = 10;
			$this->team1BgColor = "forestgreen";
		}
		if (in_array($this->team2->name,$teamList))
		{ 
			$this->team2Pts = 10;
			$this->team2BgColor = "forestgreen";
		}
		
		// check if user match is in opponentList (including cross correlation). If true, calculate matchResPts
		if (!$this->checkScore()) return;
		
		$u1 = $this->team1->name;
		$u2 = $this->team2->name;
		
		if (isset($opponentDictionary["$u1-$u2"]))
		{	
			// opponents were found in Dictionary	
			$matchRes_Master = $opponentDictionary["$u1-$u2"];
			//if (!checkGame($matchRes_Master)) return;
			
			// check if full hit -> 20 points
			if ($this->matchRes == $matchRes_Master) 
			{
				$this->matchResPts = 20;
				$this->matchResBgColor = "forestgreen";	
				return;
			}
			
			// check if partial hit -> 10 points
			$mparts = explode(":", $matchRes_Master);
			$uparts = explode(":", $this->matchRes);	
		
			$leftmore = (($mparts[0] > $mparts[1]) && ($uparts[0] > $uparts[1]));
			$even = (($mparts[0] == $mparts[1]) && ($uparts[0] == $uparts[1])); 
			$rightmore = (($mparts[0] < $mparts[1]) && ($uparts[0] < $uparts[1]));
			if ( $leftmore || $even || $rightmore )
			{
				$this->matchResPts = 10;
				$this->matchResBgColor = "lightgreen";
			}	
		} 	
	}
	
	/***********************************************************************
	* calculateMatchTotalPoints()
	***********************************************************************/ 
	function calculateMatchTotalPoints()
	{
		$this->matchTotalPts = $this->team1Pts + $this->team2Pts + $this->matchResPts;
		return $this->matchTotalPts;
	}
	
	/***********************************************************************
	* checkScore()
	***********************************************************************/ 
	function checkScore()
	{		
		if ($this->matchRes == "") return 0;
		if ($this->matchRes == "-") return 0;
	
		$parts = explode(":", $this->matchRes);
	
		if ( !is_numeric($parts[0]) ) return 0;
		if ( !is_numeric($parts[1]) ) return 0; 
		if ( $parts[0] < 0 ) return 0;
		if ( $parts[0] > 99) return 0;
		if ( $parts[1] < 0 ) return 0;
		if ( $parts[1] > 99) return 0;
		
		// check even final game
		if ( ($this->matchNr>=48) && ($parts[0] == $parts[1]) ) 
			return 0;
	
		return 1;
	}
	
	/***********************************************************************
	* checkTeams()
	***********************************************************************/ 
	function checkTeams()
	{
		if ( ($this->team1=="")||($this->team2=="")||($this->team1==$this->team2) )
			return 0;
		return 1;
	}
}
?>
