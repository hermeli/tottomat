<?php
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
	var $team1_hit;
	
	var $team2;
	var $team2_hit;
	
	var $matchRes;	// Matchergebnis, z.B: 1:2
	
	var $playPts;	// Punktzahl des Spielresultats
	var $matchPts;	// komplette Punktzahl des Matches
	
    function match($mNr,$grp,$t1,$t2)
    {		
		$this->matchNr = $mNr;
		$this->group = $grp;
		$this->team1 = $t1;
		$this->team2 = $t2;
		
		$this->matchRes = "";
		$this->playPts = 0;
		$this->matchPts = 0;
		$this->team1_hit = 0;
		$this->team2_hit = 0;
	}
	
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
	
	function checkTeams()
	{
		if ( ($this->team1=="")||($this->team2=="")||($this->team1==$this->team2) )
			return 0;
		return 1;
	}
}
?>
