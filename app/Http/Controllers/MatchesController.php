<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MatchesController extends Controller
{

    public function index()
    {
    	$matches = \DB::table('matches')->where('status', '<>', 2)->orderBy('id', 'desc')->take(999999)->get();
    	foreach ($matches as $m) {
    		/* TEAM 1 */
    		$m->name1 = \DB::table('team')->where('id', $m->Team1)->pluck('name');
    		$m->img1 = \DB::table('team')->where('id', $m->Team1)->pluck('img');
    		if($m->Bank == 0){
    		$m->percent1 = 0;
    		}else{
    		$m->percent1 = floor($m->Team1Bank / $m->Bank * 100);
    		}
    		/* TEAM 1 */

			/* TEAM 2 */
    		$m->name2 = \DB::table('team')->where('id', $m->Team2)->pluck('name');
    		$m->img2 = \DB::table('team')->where('id', $m->Team2)->pluck('img');
    		if($m->Bank == 0){
    		$m->percent2 = 0;
    		}else{
    		$m->percent2 = ceil($m->Team2Bank / $m->Bank * 100);
    		}
    		/* TEAM 2 */

    		/* Время */
    		$dateNow = date('Y-m-d G:i:s');
    		$m->date = $m->Time.' CET';
    		$m->timestamp1 = strtotime($m->Time);
    		$m->kek = $m->timestamp1 - time();
			if($m->kek >= 0){
				$m->live = 'ok';
			}elseif($m->kek < 0){
				$m->live = 'neok';
			}
			/* Время */
        }
        $matchesOver = \DB::table('matches')->where('status', 2)->where('winner', '<>', 0)->orderBy('id', 'desc')->take(999999)->get();
        foreach ($matchesOver as $mo) {
        	$mo->date = $mo->Time.' CET';

        	/* TEAM 1 */
    		$mo->name1 = \DB::table('team')->where('id', $mo->Team1)->pluck('name');
    		$mo->img1 = \DB::table('team')->where('id', $mo->Team1)->pluck('img');
            if($mo->Bank == 0){
                $mo->percent1 = 0;
                $mo->percent2 = 0;
            }else{
    		  $mo->percent1 = floor($mo->Team1Bank / $mo->Bank * 100);
              $mo->percent2 = ceil($mo->Team2Bank / $mo->Bank * 100);
            }
    		/* TEAM 1 */

			/* TEAM 2 */
    		$mo->name2 = \DB::table('team')->where('id', $mo->Team2)->pluck('name');
    		$mo->img2 = \DB::table('team')->where('id', $mo->Team2)->pluck('img');
    		/* TEAM 2 */
    		$mo->win = $mo->winner;
        }	
        return view('pages.matches.index', compact('matches', 'matchesOver'));
    }

    public function number_matches()
    {
    	$matches = \DB::table('matches')->where('status', 0)->count('id');
    	return $matches;
    }

    public function game($gameId)
    {
    	if (isset($gameId) && \DB::table('matches')->where('id', $gameId)->count()) {
    		$game = \DB::table('matches')->where('id', $gameId)->first();
    		$game->date = $game->Time.' CET';
    		$dateNow = date('Y-m-d G:i:s');
    		$game->timestamp1 = strtotime($game->Time);
    		$game->kek = $game->timestamp1 - time();
			if($game->kek >= 0){
				$game->live = 'ok';
			}elseif($game->kek < 0){
				$game->live = 'neok';
			}
            if(!Auth::guest()){
			$stavka = \DB::table('bets')->where('userid', $this->user->id)->where('game', $game->id)->first();
            }else{
                $stavka = NULL;
            }
			if($stavka == NULL){
				$yourtype = 0;
			}elseif($stavka->team == $game->Team2){
				$yourtype = 2;
			}elseif($stavka->team == $game->Team1){
				$yourtype = 1;
			}
			/* TEAM 1 */
    		$game->name1 = \DB::table('team')->where('id', $game->Team1)->pluck('name');
    		$game->img1 = \DB::table('team')->where('id', $game->Team1)->pluck('img');
    		$game->percent1 = 0;
    		if($game->Bank == 0){
            $game->percent1 = 0;    
    		}else{
    		$game->percent1 = floor($game->Team1Bank / $game->Bank * 100);
    		}
    		/* TEAM 1 */

			/* TEAM 2 */
    		$game->name2 = \DB::table('team')->where('id', $game->Team2)->pluck('name');
    		$game->img2 = \DB::table('team')->where('id', $game->Team2)->pluck('img');
    		$game->percent2 = 0;
    		if($game->Bank == 0){
            $game->percent2 = 0;    
    		}else{
    		$game->percent2 = ceil($game->Team2Bank / $game->Bank * 100);
    		}
    		/* TEAM 2 */

    		if($game->winner == 0){
    			$winner = 0;
    		}elseif($game->winner == 1){
    			$winner = 1;
    		}elseif($game->winner == 2){
    			$winner = 2;
    		};

    		$team2Percent = $game->percent2;
    		$team1Percent = $game->percent1;
			$value1 = '0.'.$team2Percent;
			$value2 = '0.'.$team1Percent;
			$team1Value = 1;
			$team2Value = 1;
			if($team1Percent > $team2Percent)
			{
				$team1Value = 1.5;
				$team2Value = 3;
			}
			elseif($team1Percent < $team2Percent)
			{
				$team1Value = 3;
				$team2Value = 1.5;
			}
			if($team1Percent==50)
			{
				$team1Value = 2;
				$team2Value = 2;
			}
			if($team2Percent > $team1Percent)
			{
				$team1Value = 3;
				$team2Value = 1.5;
			}
			elseif($team2Percent < $team1Percent)
			{
				$team1Value = 1.5;
				$team2Value = 3;
			}
			$valueFor1team1 = round(1 * $value1 * $team1Value, 2);
			$valueFor1team2 = round(1 * $value2 * $team2Value, 2);
            if(!Auth::guest()){
			$myBets = \DB::table('bets')->where('game', $gameId)->where('userid', $this->user->id)->first();
            }else{
            $myBets = NULL;    
            }
			if($myBets !== NULL){
			$price = $myBets->price;
			}else{
			$price = 0;	
			}
			if($myBets != NULL){
				if($myBets->team == $game->Team1){
					$ok1 = 'ok';
					$ok2 = 'neok';
				}elseif($myBets->team == $game->Team2){
					$ok2 = 'ok';
					$ok1 = 'neok';
				}
			}else{
				$ok1 = 'neok';
				$ok2 = 'neok';
				$myBets = 'neok';
			}
    		return view('pages.matches.game', compact('game', 'yourtype', 'winner', 'valueFor1team1',
    			'valueFor1team2', 'myBets', 'ok1', 'ok2', 'price', 'winner'));
    	}else{
    		return redirect()->route('index');
    	}
    }

    public function new_bet(Request $request){
    	$money = $request->money;
    	$team = $request->team;
    	$gameid = $request->gameid;
    	$game = \DB::table('matches')->where('id', $gameid)->get();
    	$bank = \DB::table('matches')->where('id', $gameid)->pluck('Bank');
    	$team1Bank = \DB::table('matches')->where('id', $gameid)->pluck('Team1Bank');
    	$team2Bank = \DB::table('matches')->where('id', $gameid)->pluck('Team2Bank');
    	$status = \DB::table('matches')->where('id', $gameid)->pluck('status');
    	$stavka = \DB::table('bets')->where('game', $gameid)->where('userid', $this->user->id)->get();
    	if(Auth::guest()){
    		$returnValue = [ 
    		'msg' => 'Пожалуйста, войдите',
    		'status' => 'error'
    		];
    	}elseif($status == 1 ){
    		$returnValue = [ 
    		'msg' => 'Игра уже идёт',
    		'status' => 'error'
    		];
    	}elseif($status == 2 ){
    		$returnValue = [ 
    		'msg' => 'Игра уже закончилась',
    		'status' => 'error'
    		];	
    	}elseif($money > $this->user->money){
    		 $returnValue = [ 
    		'msg' => 'У вас не достаточно денег на балансе',
    		'status' => 'error'
    		];
    	}elseif($stavka <> NULL){
    		$returnValue = [ 
    		'msg' => 'Вы уже поставили',
    		'status' => 'error'
    		];
    	}else{	
    		if($team == 1){
    			$tea = \DB::table('matches')->where('id', $gameid)->pluck('Team1');
    		}elseif($team == 2){
    			$tea = \DB::table('matches')->where('id', $gameid)->pluck('Team2');
    		}
    		\DB::table('bets')->insertGetId(['game' => $gameid, 'team' => $tea, 'userid' => $this->user->id, 'price' => $money]);
    		\DB::table('users')->where('id', $this->user->id)->update(['money' => $this->user->money - $money]);
    		if($team == 1){
    		\DB::table('matches')->where('id', $gameid)->update(['Bank' => $bank + $money, 'Team1Bank' => $team1Bank + $money]);
    		}elseif($team == 2){
    		\DB::table('matches')->where('id', $gameid)->update(['Bank' => $bank + $money, 'Team2Bank' => $team2Bank + $money]);
    		}
    		$returnValue = [ 
    		'msg' => 'Вы успешно поставили!',
    		'status' => 'success'
    		];
    	}
    	return $returnValue;
    }

    public function statusGame()
    {
    	$matches = \DB::table('matches')->where('status', '<>', 2)->orderBy('id', 'desc')->take(999999)->get();
    	foreach ($matches as $m) {
    		$dateNow = date('Y-m-d G:i:s');
    		$m->date = $m->Time.' CET';
    		$m->timestamp1 = strtotime($m->Time);
    		$m->kek = $m->timestamp1 - time();
			if($m->kek >= 0){
				$m->live = 'ok';
			}elseif($m->kek < 0){
				$m->live = 'neok';
				\DB::table('matches')->where('id', $m->id)->update(['status' => 1]);
			}
    	}	
    }

    public function change_bet(Request $request)
    {
    	$game = $request->game;
    	$bet = \DB::table('bets')->where('game', $game)->where('userid', $this->user->id)->first();
    	$games = \DB::table('matches')->where('id', $game)->first();
    	if($bet->team == $games->Team1){
    	   	$team = \DB::table('matches')->where('id', $game)->pluck('Team2');
    	   	\DB::table('matches')->where('id', $games->id)->update(['Team1Bank' => $games->Team1Bank - $bet->price, 'Team2Bank' => $games->Team2Bank + $bet->price]);
    	   	\DB::table('bets')->where('game', $games->id)->where('userid', $this->user->id)->update(['team' => $team]);
    	}elseif($bet->team == $games->Team2){
    		$team = \DB::table('matches')->where('id', $game)->pluck('Team1');
    	   	\DB::table('matches')->where('id', $games->id)->update(['Team2Bank' => $games->Team2Bank - $bet->price, 'Team1Bank' => $games->Team1Bank + $bet->price]);
    	   	\DB::table('bets')->where('game', $games->id)->where('userid', $this->user->id)->update(['team' => $team]);
    	}
    	return $team;
    }
}
