<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return view('pages.admin.index');
    }

    public function users()
    {
        $users = \DB::table('users')->paginate(1);
        foreach($users as $us){
            $ref = \DB::table('activeRef')->where('userid', $us->id)->first();
            if($ref == NULL){
                $us->ref = NULL;
            }else{
                $us->ref = \DB::table('users')->where('ref', $ref->code)->first();
            }
            $us->stavok = \DB::table('bets')->where('userid', $us->id)->sum('price');   
        }

        $last_page = $users->lastPage();

        if(isset($_GET['page'])) {
            $current_page = $_GET['page'];
        }
        else
        {
            $current_page = 1;
        }

        if($current_page > $last_page) {
            return redirect('/admin/users?page='.$last_page);
        }
        else
        {
            return view('pages.admin.users', compact('users'));
        }
    }

    public function matches()
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
        $matchesOver = \DB::table('matches')->where('status', 1)->where('winner', '<>', 0)->orderBy('id', 'desc')->take(999999)->get();
        foreach ($matchesOver as $mo) {
            $mo->date = $mo->Time.' CET';

            /* TEAM 1 */
            $mo->name1 = \DB::table('team')->where('id', $mo->Team1)->pluck('name');
            $mo->img1 = \DB::table('team')->where('id', $mo->Team1)->pluck('img');
            $mo->percent1 = floor($mo->Team1Bank / $mo->Bank * 100);
            /* TEAM 1 */

            /* TEAM 2 */
            $mo->name2 = \DB::table('team')->where('id', $mo->Team2)->pluck('name');
            $mo->img2 = \DB::table('team')->where('id', $mo->Team2)->pluck('img');
            $mo->percent2 = ceil($mo->Team2Bank / $mo->Bank * 100);
            /* TEAM 2 */
            $mo->win = $mo->winner;
        }   
        return view('pages.admin.matches', compact('matches', 'matchesOver'));
    }

    public function addTeam()
    {
        return view('pages.admin.addTeam');
    }

    public function addTeamPost(Request $request)
    {
        $name = $request->name;
        $logo = $request->logo;
        \DB::table('team')->insertGetId(['name' => $name, 'img' => $logo]);
    }

    public function addMatch()
    {
        $teams = \DB::table('team')->get();
        return view('pages.admin.addmatch', compact('teams'));
    }

    public function addMatches(Request $request)
    {
        $team1 = $request->team1;
        $team2 = $request->team2;
        $date = $request->date;
        $stream = $request->stream;
        $team1 = \DB::table('team')->where('name', $team1)->pluck('id');
        $team2 = \DB::table('team')->where('name', $team2)->pluck('id');
        \DB::table('matches')->insertGetId(['Team1' => $team1, 'Team2' => $team2, 'Time' => $date, 'twitchname' => $stream]);
    }

    public function manage_match($matchId)
    {
        if (isset($matchId) && \DB::table('matches')->where('id', $matchId)->count()) {
        $game = \DB::table('matches')->where('id', $matchId)->first();
        $team1 = \DB::table('team')->where('id', $game->Team1)->first();
        $team2 = \DB::table('team')->where('id', $game->Team2)->first();
        $game->name1 = $team1->name;
        $game->name2 = $team2->name;
        $game->img1 = $team1->img;
        $game->img2 = $team2->img;
        if($game->Bank == 0){
            $game->percent1 = 0;
        }else{
            $game->percent1 = floor($game->Team1Bank / $game->Bank * 100);
        }
        if($game->Bank == 0){
            $game->percent2 = 0;
        }else{
            $game->percent2 = floor($game->Team2Bank / $game->Bank * 100);
        }
        $game->totalBet = \DB::table('bets')->where('game', $matchId)->sum('price');
        $game->totalUser = \DB::table('bets')->where('game', $matchId)->count('id');
        $game->winners = 0;
        if($game->winner !== 0){
            if($game->winner == 1){
                $game->winner = \DB::table('team')->where('id', $game->Team1)->pluck('name');
                $game->winners = 1;
            }elseif($game->winner == 2){
                $game->winner = \DB::table('team')->where('id', $game->Team2)->pluck('name');
                $game->winners = 2;
            }
        }
        return view('pages.admin.manage_match', compact('game'));
        }else{
        return redirect('/admin');    
        }
    }

    public function manage_user($userId)
    {
        if (isset($userId) && \DB::table('users')->where('id', $userId)->count()) {
            $user = \DB::table('users')->where('id', $userId)->first();
            foreach($user as $us){
            $ref = \DB::table('activeRef')->where('userid', $user->id)->first();
            if($ref == NULL){
                $user->ref = NULL;
            }else{
                $user->ref = \DB::table('users')->where('ref', $ref->code)->first();
            }
            if($ref !== NULL){
                $user->priglasil = \DB::table('activeRef')->where('code', $ref->code)->count('id');
            }else{
                $user->priglasil = 0;
            } 
            $user->stavok = \DB::table('bets')->where('userid', $user->id)->sum('price');   
        }
            return view('pages.admin.manage_user', compact('user'));
        }else{
            return redirect('/admin');
        }
    }

    public function manage_user_lol(Request $request)
    {
        $action = $request->action;
        $user = $request->user;
        if($action == 1){
            $balance = $request->balance;
            \DB::table('users')->where('id', $user)->update(['money' => $balance]);
        }elseif($action == 2){
            \DB::table('users')->where('id', $user)->update(['is_admin' => 5]);
        }elseif($action == 3){
            \DB::table('users')->where('id', $user)->update(['is_admin' => 0]);
        }elseif($action == 4){
            \DB::table('users')->where('id', $user)->update(['is_admin' => 1]);
        }elseif($action == 5){
            \DB::table('users')->where('id', $user)->update(['is_admin' => 0]);
        }
    }

    public function winteam(Request $request)
    {
        $gameId = $request->game;
        $team = $request->team;
        $game = \DB::table('matches')->where('id', $gameId)->first();
        if($game->status <> 2){
            \DB::table('bets')->where('game', $gameId)->update(['over' => 1]);
                /* Новое вычесление */
                $betOnTeam1users = $game->Team1Bank;
                $betOnTeam2users = $game->Team2Bank;
                if($betOnTeam1users==0)
                {
                    $team1Percent = '0';
                    $team2Percent = '100';
                }
                if($betOnTeam2users==0)
                {
                    $team2Percent = '0';
                    $team1Percent = '100';
                }
                if($betOnTeam1users==0 && $betOnTeam2users==0)
                {
                    $team1Percent = '0';
                    $team2Percent = '0';
                }
                if($betOnTeam1users >= 1 && $betOnTeam2users >= 1)
                {
                    $betTotal = $betOnTeam1users + $betOnTeam2users;
                    $team1Percent = floor($betOnTeam1users / $betTotal * 100);
                    $team2Percent = ceil($betOnTeam2users / $betTotal * 100);
                }

                $value1 = '0.'.$team2Percent;
                $value2 = '0.'.$team1Percent;
                $team1Value = 0;
                $team2Value = 0;
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
                if($team == 1)
                {
                    $valueFor1 = round(1 * $value1 * $team1Value, 2);
                }
                elseif($team == 2)
                {
                    $valueFor1 = round(1 * $value2 * $team2Value, 2);
                }
                        if($team == 1){
                            \DB::table('matches')->where('id', $gameId)->update(['winner' => 1, 'status' => 2]);
                            $bets = \DB::table('bets')->where('game', $gameId)->where('team', $game->Team1)->get();
                            foreach($bets as $b){
                                $user = \DB::table('users')->where('id', $b->userid)->first();
                                $betsPrice = $b->price + ($b->price * $valueFor1);
                                $refUser = \DB::table('activeRef')->where('userid', $b->userid)->first();
                                if($refUser !== NULL){
                                    \DB::table('users')->where('id', $b->userid)->update(['money' => $user->money + $betsPrice]);
                                    \DB::table('activeRef')->where('userid', $b->userid)->update(['doxod' => $refUser->doxod + $betsPrice]);
                                }else{                        
                                    \DB::table('users')->where('id', $b->userid)->update(['money' => $user->money + $betsPrice]);
                                    \DB::table('activeRef')->where('userid', $b->userid)->update(['doxod' => $refUser->doxod + $betsPrice]);
                                }
                            }
                        }elseif($team == 2){
                            \DB::table('matches')->where('id', $gameId)->update(['winner' => 2, 'status' => 2]);
                            $bets = \DB::table('bets')->where('game', $gameId)->where('team', $game->Team2)->get();
                            foreach($bets as $b){
                                $user = \DB::table('users')->where('id', $b->userid)->first();
                                $betsPrice = $b->price + ($b->price * $valueFor1);
                                $refUser = \DB::table('activeRef')->where('userid', $b->userid)->first();
                                if($refUser !== NULL){
                                    \DB::table('users')->where('id', $b->userid)->update(['money' => $user->money + $betsPrice]);
                                    \DB::table('activeRef')->where('userid', $b->userid)->update(['doxod' => $refUser->doxod + $betsPrice]);
                                }else{
                                    \DB::table('users')->where('id', $b->userid)->update(['money' => $user->money + $betsPrice]);
                                    \DB::table('activeRef')->where('userid', $b->userid)->update(['doxod' => $refUser->doxod + $betsPrice]);
                                }
                            }
                        }
            }        
    }

    public function stream(Request $request)
    {
        $stream = $request->stream;
        $game = $request->game;
        \DB::table('matches')->where('id', $game)->update(['twitchname' => $stream]);
    }

    public function userBet($gameIds)
    {
        if (isset($gameIds) && \DB::table('matches')->where('id', $gameIds)->count()) {
        $game = \DB::table('matches')->where('id', $gameIds)->first();
        $team1 = \DB::table('team')->where('id', $game->Team1)->first();
        $team2 = \DB::table('team')->where('id', $game->Team2)->first();
        $game->name1 = $team1->name;
        $game->name2 = $team2->name;
        $game->totalBet = \DB::table('bets')->where('game', $gameIds)->sum('price');
        $game->users = \DB::table('bets')->where('game', $gameIds)->count('id');
        $bets = \DB::table('bets')->where('game', $gameIds)->paginate(15);
        foreach($bets as $b){
            $b->id = \DB::table('users')->where('id', $b->userid)->pluck('id');
            $b->steamid64 = \DB::table('users')->where('id', $b->userid)->pluck('steamid64');
            if($b->team == $team1->id){
                $b->name = \DB::table('team')->where('id', $team1->id)->pluck('name');
            }elseif($b->team == $team2->id){
                $b->name = \DB::table('team')->where('id', $team2->id)->pluck('name');
            }
        }

        $last_page = $bets->lastPage();

        if(isset($_GET['page'])) {
            $current_page = $_GET['page'];
        }
        else
        {
            $current_page = 1;
        }

        if($current_page > $last_page) {
            return redirect('/admin/userBet/'.$gameIds.'?page='.$last_page);
        }
        else
        {
            return view('pages.admin.userBet', compact('game', 'bets'));
        }    
        
        }
    }

    public function indexRef(){
        $users = \DB::table('users')->where('ref', '<>', '')->paginate(20);
        foreach($users as $us){
            $ref = \DB::table('activeRef')->where('code', $us->ref)->first();
            if($ref !== NULL){
            $priglas = \DB::table('users')->where('id', $ref->userid)->first();
            $us->postavili = \DB::table('bets')->where('userid', $priglas->id)->sum('price');
            $us->priglasil = \DB::table('activeRef')->where('code', $us->ref)->count('id');
            $us->vnes = $ref->vneseno;
            }else{
            $us->postavili = 0;
            $us->priglasil = 0;      
            $us->vnes = 0;             
            }
        }

        $last_page = $users->lastPage();

        if(isset($_GET['page'])) {
            $current_page = $_GET['page'];
        }
        else
        {
            $current_page = 1;
        }

        if($current_page > $last_page) {
            return redirect('/admin/ref?page='.$last_page);
        }
        else
        {
            return view('pages.admin.ref', compact('users'));
        }
    }

    public function addCode(){   
        return view('pages.admin.addCode');
    }

    public function addCodePost(Request $r){
        $code = $r->code;
        $money = $r->money;
        $username = $r->username;
        $user = \DB::table('users')->where('username', $username)->first();
        $codes = \DB::table('users')->where('ref', $code)->first();
        if($user == NULL){
            $returnValue = ['type' => 'neok', 'msg' => 'Такого пользователя не существует'];
        }elseif($codes !== NULL){
            $returnValue = ['type' => 'neok', 'msg' => 'Такой код уже существует'];
        }else{
            \DB::table('users')->where('username', $username)->update(['ref' => $code, 'refMoney' => $money]);
            $returnValue = ['type' => 'ok'];
        }
        return $returnValue;
    }
}
