<?
///////////////////////////////////////
// PROMATHIUS BATTLE ENGINE
//////////////////////////////////////

// Check that the script is not being accessed directly
if ( !defined('PROMATHIUS') )
{
	die("Hacking attempt");
}
include ($game_root_path.'/header.php');
include ($game_root_path.'/actions/promscript.php');
initGUI("");

// Diagnostics?
$debug = true;
$dobattle = true;
$aftermath = false;

// Convert POST data to readable battle information
///////////
// $uarmy - post
// $attacktype = post
// $earmy - SQL of Enemy ID, calculate here
// $edefenses	- SQL of Enemy ID, calculate here
//

if($dobattle)
{
	$playerunit = 1;
	$enemyunit = 2;
	$attacktype = 1;
	
	// Prepare Skirmish
	// CASE IS very important
	//$uarmy['Spearmen'] = 1;
	$uarmy['Cavalry'] = 200;
	
	$earmy['Cavalry'] = 100;
	//$earmy['Cavalry'] = 10;
	$edefenses['Defense'] = 5;
	//$edefenses['Catapult'] = 10;
	
	// Record initial sent forces for later purposes
	$usarmy = $uarmy;
	$esarmy = $earmy;
	$esdefenses = $edefenses;
	
	doSkirmish($attacktype);
	//doBodyCount();
	echo "<Br><Br>";
	echo "<h1>".returnPlayerResult($casualties)."</h1>";
	if($death['player'] || $death['enemy'])
	{
		if($death['player'])
			echo "Your army fought to the last man.";
		else
			echo "The enemy army fought to the last man.";
	}
	elseif($casualties['player'] != $casualties['enemy'])
	{
		if($uflee)
			echo "Your army fled in panic.";
		elseif($eflee)
			echo "The enemy army resigned.";
	}
	else
			echo "Neither side won.";
	echo "<Br><Br>";
	echo "Player troops: ";print_r($uarmy); echo "<Br>";
	echo "Enemy troops: "; print_r($earmy);echo "<Br>";
	echo "Enemy defenses: "; print_r($edefenses);
	echo "<Br><br>Enemy has ".$casualties[enemy] . " casualties vs. ". $casualties[player]." player  casualties!<Br>";
	
	$aftermath = true; // Is this a real battle?
}

function returnPlayerResult($casualties)
{
	global $uflee, $eflee;
	
	// Calculated on the basis of ratio
	if($casualties[player] == $casualties[enemy])
	{
		$result = "Draw";
	}
	elseif($eflee)
	{
		if($casualties[player] > $casualties[enemy]*2)
			$result = "Costly Victory";
		elseif($casualties[player] > $casualties[enemy]*1.4)
			$result = "Average Victory";
		elseif($casualties[player]*3 < $casualties[enemy])
			$result = "Crushing Victory";
		elseif($casualties[player]*1.8 < $casualties[enemy])
			$result = "Big Victory";
		else
			$result = "Victory";
	}
	else
	{
		if($casualties[player] > ($casualties[enemy]+1)*60)
			$result = "Slaughter";
		elseif($casualties[player] > ($casualties[enemy]+1)*5)
			$result = "Crushing Defeat";
		elseif($casualties[player] > ($casualties[enemy]+1)*2.5)
			$result = "Major Defeat";
		elseif($casualties[player] > ($casualties[enemy]+1)*1.3)
			$result = "Close Defeat";
		else
			$result = "Defeat";
	}

	return $result;
}

// Record kills to DB
function doBodyCount(&$users, &$enemy)
{
		global $debug, $atktypedata, $uera, $casualties, $uflee, $eflee, $death, $uarmy, $earmy, $edefenses;
		
		foreach($uarmy as $id => $value)
		{
			$unitid = getUnitId($id);
			$users['troop'][$unitid] = $value*$config['game_factor'];
		}
		foreach($earmy as $id => $value)
		{
			$unitid = getUnitId($id);
			$enemy['troop'][$unitid] = $value*$config['game_factor'];
		}
		foreach($edefenses as $id => $value)
		{
			$enemy['buildings'][$id] = $value*$config['game_factor'];
		}
		
		saveUserData($users, 'troop');
		saveUserData($enemy, "troops buildings");
}

function getArmyStats($army, $defense = false)
{
	global $uera;
	foreach($army as $id => $value)
	{
		$stat['total'] += $value;
		$stat['abstracttotal'] += $value*$uera['troop'.$id.'size'];
	}
	if($defense)
	{
		foreach($defense as $id => $value)
		{
			$stat['total'] += $value;
			$stat['abstracttotal'] += $value*$uera['structure'.$id.'size'];
		}
	}
	echo "Total units of army is ".$stat['total'].'<Br>';

	return $stat;
}

function createTypeStats($army)
{
	global $uera;
	
	foreach($army as $id => $value)
	{
		$types[$uera['troop'.$id.'type']]['total'] += $value;
	}
	
	return $types;
}
function initTypes($army, $getstrings = false, $stat)
{
	global $uera;
	
	if($getstrings)
	{
		foreach($army as $id => $value)
		{
			$percentage = (($value*$uera['troop'.$id.'size']) / $stat['abstracttotal'])*100;

			for($i = 101; $i <= (ceil($percentage)+100); $i++)
			{
				$types[$id. $i] = $id;
			}
		}
	}
	else
	{
		foreach($army as $id => $value)
		{
			$types[$id] = $value;
		}
	}
	return $types;
}
function getUnitID($name)
{
	global $uera;
	
	$id = $uera['troopidentifier'.$name];
	
	return $id;
}
function getUnitName($id)
{
	global $uera;
	
	$name = $uera['troopidname'.$id];
	
	return $name;
}

function doSkirmish($attacktype)
{
	global $debug, $atktypedata, $uera, $casualties, $uflee, $eflee, $death, $uarmy, $earmy, $edefenses;
	
	$ustat = getArmyStats($uarmy);
	$estat = getArmyStats($earmy, $edefenses);
	$utypes = createTypeStats($uarmy);
	$etypes = createTypeStats($earmy);
	$utypesnames = initTypes($uarmy, 1, $ustat);
	$etypesnames = initTypes($earmy, 1, $estat);
	if($edefenses)
	{
		foreach($edefenses as $defense => $count)
		{
			$percentage = (($count*$uera['structure'.$defense.'size']) / $estat['abstracttotal'])*100;

			for($i = 101; $i <= (ceil($percentage)+100); $i++)
			{
				$etypesnames[$defense. $i] = $count;
			}
			//ECHO "count: ".$count;
			$etypedefense[$defense] = $defense;
		}
	}
	//print_r($etypesnames);
	// Work out the maximum number of casualties sustainable by each side before fleeing
	$umaxfight = $ustat['total'] * ( rand( $atktypedata[$attacktype]['MinFight'],$atktypedata[$attacktype]['MaxFight'] ) / 100);
	
	$emaxfight = $estat['total'] * ( rand( $atktypedata[$attacktype]['MinFight'],$atktypedata[$attacktype]['MaxFight'] ) / 100);
	

		$total = $ustat['total'] + $estat['total'];
		while(true)
		{
					if($casualties['player'] >= $ustat['total'])
					{
						$death['player'] = true; 
						$flee = true;
						$uflee = true;
						break;
					}
					if($casualties['enemy'] >= $estat['total'])
					{
						$death['enemy'] = true; 
						$flee = true;
						$eflee = true;
						break;
					}
					if($casualties['player'] >= $umaxfight)
					{
						$flee = true;
						$uflee = true;
						if($casualties['player'] >= $ustat['total'])
						{
							$death['player'] = true; 
						}
						break;
					}
					elseif($casualties['enemy'] >= $emaxfight)
					{
						if($casualties['enemy'] >= $estat['total'])
						{
							$death['enemy'] = true; 
						}
						$flee = true;
						$eflee = true;
						break;
					}
					if(!$flee)
					{
						$urand_unitsr = substr(array_rand($utypesnames, 1), 0, -3);
						$erand_unitsr = substr(array_rand($etypesnames, 1), 0, -3);
						//echo $urand_unitsr;
			
						$epass = false;
						if($earmy[$erand_unitsr] >= 1 || $edefenses[$erand_unitsr] >= 1)
							$epass = true;
						if($uarmy[$urand_unitsr] >= 1 && $epass)
						{
							$urand_units = getUnitID($urand_unitsr);
							if(!$etypedefense[$erand_unitsr])
								$erand_units = getUnitID($erand_unitsr);
							$urand_units = getStats($urand_units);
							$erand_units = getStats($erand_units, $etypedefense[$erand_unitsr]);
							$ufactor = determineAttackFactor($urand_units['type'], $erand_units['type'], $attacktype);
							$efactor = determineAttackFactor($erand_units['type'], $urand_units['type'], $attacktype);
							// Actual battle kills etc. are recorded in the fight function
							if(doFight($urand_units, $erand_units, $ufactor, $efactor, $etypedefense[$erand_unitsr]))
							{
								$fights++;
							}
						}
						IF($earmy[$erand_unitsr] < 1)
						{
							// unset here
						}
						IF($uarmy[$urand_unitsr] < 1)
						{
							// unset here
						}
					}
		}
}

function doFight ($uunit, $eunit, $ufactor, $efactor, $defense = false)
{
	global $debug, $uarmy, $earmy, $casualties, $uhealth, $ehealth, $ulast, $elast, $edefenses;
	
	//if($debug)
	//{
	//	echo "---------------- FIGHT ----------------<Br>";
	//	echo "'A $uunit[name] takes on a $eunit[name]'<bR><bR>";
	//}
	
	// Unit Health
	$udefense = ( $uunit['block'] + $uunit['armor'] );
	$edefense = ( $eunit['block'] + $eunit['armor'] );
	if($uhealth[$uunit['id']] <= 0)
			$uhealth[$uunit['id']] = 1;
	if($ehealth[$eunit['id']] <= 0)
		$ehealth[$eunit['id']] = 1;

	//echo "Last health of ". $eunit['id'] ." is " . $ehealth[$eunit['id']];
	$uhealth[$uunit['id']] = $udefense * $uhealth[$uunit['id']];
	$ehealth[$eunit['id']] = $edefense * $ehealth[$eunit['id']];
	 
	// Unit Damage
	$udamage = ( $uunit['weapon'] + $uunit['skill'] ) * $ufactor;
	$edamage = ( $eunit['weapon'] + $eunit['skill'] ) * $efactor;
	
	// Determine who gets the first move
	$random = rand( 1,2 );
	while($uhealth[$uunit['id']] > 0 && $ehealth[$eunit['id']] > 0)
	{
		$move++;
		// Player's move?
		if( $random == 1 )
		{
			$inflicted = $udamage;
			$ehealth[$eunit['id']] -= $inflicted;
			$random = 2;
		}
		// Enemies' move?
		else
		{
			$inflicted = $edamage;
			$uhealth[$uunit['id']] -= $inflicted;
			$random = 1;
		}
		//if($debug)
		//	echo "Player $random gets move #$move, inflicting $inflicted damage.<Br>";
	}
	if($ehealth[$eunit['id']] <= 0)
	{
		if($earmy[$eunit['id']] || $edefenses[$eunit['id']])
		{
			if($defense)
				$edefenses[$eunit['id']]--;
			else
				$earmy[$eunit['id']]--;
			$casualties['enemy']++;
			$success = true;
		//if($debug)
		//	echo "The enemy can't take it anymore.<Br>-- Player 1 wins the battle! --<Br><Br>";
		}
	}
	elseif($uhealth[$uunit['id']] <= 0)
	{
		if($uarmy[$uunit['id']])
		{
			$uarmy[$uunit['id']]--;
			$casualties['player']++;
			$success = true;
		}
	}
	$uhealth[$uunit['id']] = $uhealth[$uunit['id']]/$udefense;
	$ehealth[$eunit['id']] = $ehealth[$eunit['id']]/$edefense;

	return $success;
}

function getStats ($unit = '', $defense = '')
{
	global $config, $uera;

	// ID
	if($defense)
		$unitarr['id'] = $uera['structure'.$defense.'id'];
	else
		$unitarr['id'] = getUnitName($unit);
	
	// Name
	if($defense)
		$unitarr['name'] = $uera['structure'.$defense.'alt'];
	else
		$unitarr['name'] = $uera['troop'.$unit.'alt'];

	// Type
	if($defense)
		$unitarr['type'] = $uera['structure'.$defense.'dtype'];
	else
		$unitarr['type'] = $uera['troop'.$unit.'type'];
	
	// Defence
	$unitarr['armor'] = $uera['troop'.$unit.'armor']*4;
	$unitarr['block'] = $uera['troop'.$unit.'block']*4;
	if($defense)
		$unitarr['armor'] = $uera['structure'.$defense.'strength'];
		
	// Attack
	$unitarr['weapon'] =  $uera['troop'.$unit.'weapon'];
	$unitarr['skill'] =  $uera['troop'.$unit.'skill'];
	if($defense)
		$unitarr['weapon'] = $uera['structure'.$defense.'attack'];
	
	return $unitarr;
}

function determineAttackFactor($type1, $type2, $attacktype)
{
	global $atktypedata, $debug;
	
	$factorarray = explode(',', str_replace(" ", "", $atktypedata[$attacktype][$type1]));
	
	foreach($factorarray as $id => $value)
	{
		$factor = explode(':', str_replace(" ", "", $factorarray[$id]));
		if($factor[0] == $type2)
			$num  = $id;
	}
	
	$factor = explode(':', str_replace(" ", "", $factorarray[$num]));
	$attackfactors[$type1][$type2] = $factor[1];
	
	$type_effectiveness = $attackfactors[$type1][$type2];
	
	if($type_effectiveness == '')
	{
		$type_effectiveness = 1;
	}
	
	//echo $type1 . " has an effectiveness of ". $type_effectiveness . " against " . $type2. ". ";
	
	return $type_effectiveness;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////// BATTLE AFTERMATH
///////// Calculation of affected land and resources
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

function countFullArmy($user)
{
	foreach($user['troop'] as $id => $value)
	{
		$count += $value;
	}
	
	return $count;
}

if($aftermath)
{
	
	// We'll create a virtual enemy
	$num = 45;
	$enemy = loadUser($num, true);
	$erace = loadRace($enemy['race'], $enemy['region']);
	$eera = loadRegion($enemy['region'], $enemy['race']);
	echo "<Br>Enemy land:".$enemy['land'].'<Br>';
	// We need to calculate total troops remaining
	// Only those sent or empire troops??? hmmm....Only those sent as locals wouldn't do much
	$troops = countFullArmy($users); // No gamefactor here. Must be pre-gamefactored I think
	$eremaining = countFullArmy($enemy);
	$edeaths = $casualties['enemy'];

	if($casualties['player'] == $casualties['enemy'])
	{
		if($troops == 0 && $eremaining > 0)
			$draw = false;
		elseif($troops > 0 && $eremaining == 0)
			$draw = false;
		else
			$draw = true;
	}	
	if(!$draw)
	{
		if($uflee)
		{
			$winner = $enemy['num'];
			$loser = $users['num'];
		}
		else
		{
			$winner = $users['num'];
			$loser = $enemy['num'];
		}
	}
	else
	{
		$winner = 'draw_nowinner';
		$loser = 'draw_nowinner';
	}

	$conqueredland = getConqueredLand($enemy, $troops, $eremaining, $edeaths);
	$lootedwealth = $lootfactor * $enemy['cash'];
	$populationaffected = $lootfactor * $enemy['peasants'];
	$lootedscrolls = $lootfactor * $enemy['runes'];
	$lootedfood = $lootfactor * $enemy['food'];
	$capturedStructures = getCapturedBuildings($conqueredland, $enemy);

	$report['year'] = $actual_year;
	$report['season'] = $current_season;
	$report['offnum'] = $users['num'];
	$report['defnum'] = $enemy['num'];
	$report['oloss'] = $casualties['player'];
	$report['dloss'] = $casualties['enemy'];
	foreach($usarmy as $unit => $count)
	{
		$loss = $usarmy[$unit] - $uarmy[$unit];
		$olossarmy[$unit] .= $unit.':'.$loss;
	}
	foreach($esarmy as $unit => $count)
	{
		$loss = $esarmy[$unit] - $earmy[$unit];
		$dlossarmy[$unit] .= $unit.':'.$loss;
	}
	foreach($esdefenses as $unit => $count)
	{
		$loss = $esdefenses[$unit] - $edefenses[$unit];
		$dlossdefenses[$unit] .= $unit.':'.$loss;
	}
	$report['olossunits'] = implode("|", $olossarmy);
	$report['dlossunits'] = implode("|", $dlossarmy);
	$report['dlossdefenses'] = implode("|", $dlossdefenses);
	$report['totalloss'] = $casualties['player'] +$casualties['enemy'];
	$report['winner'] = $winner;
	$report['loser'] = $loser;
	foreach($usarmy as $unit => $count)
	{
		$osentarmy[$unit] = $unit.':'.$count;
	}
	foreach($esarmy as $unit => $count)
	{
		$dsentarmy[$unit] = $unit.':'.$count;
	}
	foreach($esdefenses as $unit => $count)
	{
		$dsentdefenses[$unit] = $unit.':'.$count;
	}
	$report['osentunits'] = implode("|", $osentarmy);
	$report['dsentunits'] = implode("|", $dsentarmy);
	$report['dsentdefenses'] = implode("|", $dsentdefenses);
	$report['land'] = $conqueredland;
	$report['wealth'] = $lootedwealth;
	$report['population'] = $populationaffected;
	$report['runes'] = $lootedscrolls;
	$report['food'] = $lootedfood;
	if($DamagedBuildings)
		$report['structures'] = implode("|", $DamagedBuildings);
	
	pillageLands($enemy, $conqueredland, $lootedwealth, $populationaffected, $lootedscrolls, $capturedStructures);
	recordAttack($report);
	attackNotify();
}

function pillagelands($enemy, $land, $wealth, $pop, $scrolls, $structures)
{
	global $config, $DamagedBuildings;
	
	$enemy['land'] -= $land;
	$enemy['freeland'] = calculateFreeLand($enemy);
	// Unused land??? <-----------------------------------------------------------------------------------
	$enemy['cash'] -= $wealth;
	$enemy['peasants'] -= $pop;
	$enemy['runes'] -= $scrolls;
	if($DamagedBuildings)
	{
		foreach($DamagedBuildings as $id => $value)
		{
			$enemy['buildings'][$id] -= $value  / $config['game_factor'];
			echo "$id goes down by " .  $value  / $config['game_factor'] . " and now is " . $enemy['buildings'][''.$id];
		}
	}
	
	saveUserData($enemy, "buildings land cash peasants runes freeland");
}

function recordAttack($vars) {
		
		// <---  Variables to save go here
		$update .=  "date=NOW(), ";
		$update .=  "year='" . $vars['year'] . '\', ';
		$update .=  "season='" . $vars['season'] . '\', ';
		$update .=  "offnum='" . $vars['offnum'] . '\', ';
		$update .=  "defnum='" . $vars['defnum'] . '\', ';
		$update .=  "oloss='" . $vars['oloss'] . '\', ';
		$update .=  "dloss='" . $vars['dloss'] . '\', ';
		$update .=  "olossunits='" . $vars['olossunits'] . '\', ';
		$update .=  "dlossunits='" . $vars['dlossunits'] . '\', ';
		$update .=  "dlossdefenses='" . $vars['dlossdefenses'] . '\', ';
		$update .=  "totalloss='" . $vars['totalloss'] . '\', ';
		$update .=  "winner='" . $vars['winner'] . '\', ';
		$update .=  "loser='" . $vars['loser'] . '\', ';
		$update .=  "osentunits='" . $vars['osentunits'] . '\', ';
		$update .=  "dsentunits='" . $vars['dsentunits'] . '\', ';
		$update .=  "dsentdefenses='" . $vars['dsentdefenses'] . '\', ';
		$update .=  "land='" . $vars['land'] . '\', ';
		$update .=  "wealth='" . $vars['wealth'] . '\', ';
		$update .=  "population='" . $vars['population'] . '\', ';
		$update .=  "runes='" . $vars['runes'] . '\', ';
		$update .=  "food='" . $vars['food'] . '\', ';
		$update .=  "structures='" . $vars['structures'] . '\'';
		

		$id = sqlsafeeval("SELECT id FROM game_attacks WHERE id = (SELECT MAX( id ) FROM game_attacks ) ;")+1;
		if (!mysql_safe_query("INSERT into game_attacks (id) values ($id);") && !$hide_fatal_errors)
			die (mysql_error());

		if (!mysql_safe_query("UPDATE game_attacks SET $update WHERE id=$id;") && !$hide_fatal_errors)
			die (mysql_error());
}

function getConqueredLand($enemy, $troops , $eremaining, $edeaths)
{
	global $config, $atktypedata, $debug, $lootfactor;
	
	$enemy['peasants'] *= $config['game_factor'];
	echo 	$enemy['peasants'].' peasants<br>';
	
	// density = 5000 / 10000 x 5
	$density = (($enemy['peasants']) / $enemy['land']) / 100;
	echo "The equation is for dens wibhgT:" . $density * $atktypedata[$attacktype]['PopulationDensityWeight']/100;
	
	// troops remaining
	$eremaining = $eremaining / $edeaths;
	$remaining = $eremaining;
	if($remaining > 1)
		$remaining = 1;
	$remaining = 1 - ($remaining * $atktypedata[$attacktype]['DefenseRemainingWeight']/100);

	// gainableland = 0.1 x 5000 * 15
	$gainableland = ($enemy['land'])* $atktypedata[$attacktype]['GainableLand']/100;
	if($gainableland > $enemy['land'])
	{
			$gainableland = $enemy['land'];
	}
	
	// controllable = 2500 / 10000 x 5
	$controllable = ($troops /($enemy['peasants']));
	if($controllable >= 1)
	{
			$controllable = 1;
	}
	$controllable = 1 - $controllable;
	// .: 5%

	$acceptability = $enemy['health'];

	// equation = 0.05 x 0.1 x 0.2
	echo "<br>Equation:" . $equation1 ;
	$equation2 = (1 - (1 * $acceptability/100)*($atktypedata[$attacktype]['OrderWeight']/100));
	$equation3 = ($equation2 - ($equation2 * $controllable)*($atktypedata[$attacktype]['PopulationControlWeight']/100));
	$equation3 -= ($equation3 * $density * $atktypedata[$attacktype]['PopulationDensityWeight']/100);
	$equation3 *= $remaining;
	$actual_land = floor($equation3 * $gainableland);
	if($actual_land > $enemy['land']) $actual_land = $enemy['land'];
	if($actual_land < 0) $actual_land = 0;
	
	if($debug)
	{
			echo "<Br>Population density loss is: " . $density;
			echo "<Br>Gainable land is: " . $gainableland . " acres";
			echo "<Br>Controllable land is: " . $controllable ."";
			echo "<Br>Troops remaining factor is: " . $remaining ."";
			echo "<Br>Acceptability is: " . $acceptability."%";
			echo "<br>-------------------<Br>Equation value: " . $equation3;
			echo "<Br>FINAL LAND: " . $actual_land;
	}
	
	$lootfactor = $equation3;
	
	return $actual_land;
}

function getCapturedBuildings($actual_land, $enemy)
{
	global $structures, $eera, $DamagedBuildings, $config;
	
	foreach($enemy['buildings'] as $structure => $value)
	{
			$value = $value * $config['game_factor'];
			$structure = str_replace('structure_', '', $structure);
			$value *= $eera['structure'.$structure.'land'];
			$value = $value / ($eera['structure'.$structure.'strength']/100);
			for($i = 1; $i <= $value; $i++)
			{
				$HaveStructures[$structure.$i] = $structure;
			}
	}
		for($i = 1; $i <= $enemy['freeland']; $i++)
		{
			$HaveStructures['empty'.$i] = 'empty';
		}

	shuffle_with_keys($HaveStructures);

	foreach($HaveStructures as $structureid => $structure)
	{
			if($structure != 'empty')
			{
				//$AffectedBuildings[$structureid] = $structure;  ...wtf was this here for???
				$Buildinglots += 1;
				if($Buildinglots > $actual_land)
				{
					$Buildinglots -= 1;
					break;
				}
				if($structure != 'empty'){
					$AffectedBuildings[$structure] += 1;
				}
			}
	}
	print_r($AffectedBuildings);

	// Work out actual affected buildings i.e. not half a building, lol
	if($AffectedBuildings)
	{
		foreach($AffectedBuildings as $structure => $land)
		{
			echo "<br>Structure: " . $structure;
					$ActualBuildings[$structure] = floor($land / $eera['structure'.$structure.'land']);
					$DamagedBuildings[$structure] = $land / $eera['structure'.$structure.'land'];
		}
	}

	echo "<Br>".$Buildinglots;
	print_r($ActualBuildings);

	return $ActualBuildings;
}

// Creat news notification
function attackNotify()
{
		// Retrieve array(s)
		$result = mysql_query('SELECT * FROM game_attacks WHERE notified=0');
		$attacksdb = array();
		
		while($row = mysql_fetch_assoc($result))
		{
			$attacksdb[$row['id']] = $row;
		}
		
		// Convert each attack to a news item
		foreach($attacksdb as $id => $content)
		{
				$variables['ATTACKER'] = loadUser($attacksdb[$id]['offnum'], TRUE);
				$variables['DEFENDER'] = loadUser($attacksdb[$id]['defnum'], TRUE);
				$variables['TOTAL_CASUALTIES'] = $attacksdb[$id]['totalloss'];
				$variables['ATTACKER_CASUALTIES'] = $attacksdb[$id]['oloss'];
				$variables['DEFENDER_CASUALTIES'] = $attacksdb[$id]['dloss'];
				$variables['ATTACKER_CASUALTY'] = explode("|", $attacksdb[$id]['olossunits']); //ARRAY
				$variables['DEFENDER_CASUALTY'] = explode("|", $attacksdb[$id]['dlossunits']); //ARRAY
				$variables['ATTACKER_DESTROROYED_DEFENSE'] = explode("|", $attacksdb[$id]['dlossdefenses']); //ARRAY
				$variables['WINNER'] = loadUser($attacksdb[$id]['winner'], TRUE);
				$variables['LOSER'] = loadUser($attacksdb[$id]['loser'], TRUE);
				$variables['ATTACKER_SENT'] = explode("|", $attacksdb[$id]['osentunits']); //ARRAY
				$variables['DEFENDER_SENT'] = explode("|", $attacksdb[$id]['dsentunits']); //ARRAY
				$variables['DEFENDER_DEFENSES'] = explode("|", $attacksdb[$id]['dsentdefenses']); //ARRAY
				$variables['LOOTED_WEALTH'] = $attacksdb[$id]['wealth'];
				$variables['LOOTED_LAND'] = $attacksdb[$id]['land'];
				$variables['LOOTED_POPULATION'] = $attacksdb[$id]['population'];
				$variables['LOOTED_RUNES'] = $attacksdb[$id]['runes'];
				$variables['LOOTED_FOOD'] = $attacksdb[$id]['food'];
				$variables['CAPTURED'] = explode("|", $attacksdb[$id]['structures']); //ARRAY
				$variables['TOTAL_CAPTURED'] = $attacksdb[$id]['captured'];
	
			// Defender's side
			$success1 = launchScript("attacks", $variables, $attacksdb[$id]['defnum'], 'DEFENDER');		
			// Attacker's side
			$success2 = launchScript("attacks", $variables, $attacksdb[$id]['offnum'], 'ATTACKER');
			
			if($success1 && $success2)
			{
					mysql_query("UPDATE game_attacks SET notified=1 WHERE id=$id") 
					or die(mysql_error());  
			}
			
		}
}

endScript('');

?>