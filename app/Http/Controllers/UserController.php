<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Storage;

class UserController extends Controller
{
    public function get_balance()
    {
        return $this->user->money;
    }

    public function mybets()
    {
        $igri = \DB::table('bets')->where('userid', $this->user->id)->where('over', 0)->take(999999)->get();
                foreach($igri as $i)
                {
                    $match = \DB::table('matches')->where('id', $i->game)->where('status', '<>', 2)->first();
                    if($match !== NULL){
                    $i->id = $match->id;
                    }else{
                    $i->id = 0;
                    }
                    /* Выводим */
                    $i->date = \DB::table('matches')->where('id', $i->game)->where('status', '<>', 2)->pluck('Time');
                    $i->status = \DB::table('matches')->where('id', $i->game)->pluck('status');
                    $Team1 = \DB::table('matches')->where('id', $i->game)->where('status', '<>', 2)->pluck('Team1');
                    $Team2 = \DB::table('matches')->where('id', $i->game)->where('status', '<>', 2)->pluck('Team2');
                    $Team1Bank = \DB::table('matches')->where('id', $i->game)->where('status', '<>', 2)->pluck('Team1Bank');
                    $Team2Bank = \DB::table('matches')->where('id', $i->game)->where('status', '<>', 2)->pluck('Team2Bank');
                    $Bank = \DB::table('matches')->where('id', $i->game)->where('status', '<>', 2)->pluck('Bank');
                    $i->yourtype = 0;
                    if($i->team == $Team1){
                        $i->yourtype = 1;
                    }elseif($i->team == $Team2){
                        $i->yourtype = 2;
                    }
                    /* TEAM 1 */
                    $i->name1 = \DB::table('team')->where('id', $Team1)->pluck('name');
                    $i->img1 = \DB::table('team')->where('id', $Team1)->pluck('img');
                    $i->percent1 = 0;
                    if($Bank == 0){

                    }else{
                    $i->percent1 = floor($Team1Bank / $Bank * 100);
                    }
                    /* TEAM 1 */

                    /* TEAM 2 */
                    $i->name2 = \DB::table('team')->where('id', $Team2)->pluck('name');
                    $i->img2 = \DB::table('team')->where('id', $Team2)->pluck('img');
                    $i->percent2 = 0;
                    if($Bank == 0){

                    }else{
                    $i->percent2 = ceil($Team2Bank / $Bank * 100);
                    }
                    $i->betValue = $i->price;
                    $team2Percent = $i->percent2;
                    $team1Percent = $i->percent1;
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
                    $i->potencial = 0;
                    if($i->team == $Team1){
                        $i->potencial = $i->price * $valueFor1team1;
                    }elseif($i->team == $Team2){
                        $i->potencial = $i->price * $valueFor1team2;
                    }
                    /* TEAM 2 */
                    /* Выводим */
                }     
        $igriHistory = \DB::table('bets')->where('userid', $this->user->id)->where('over', 1)->take(999999)->get();
        foreach($igriHistory as $is)
        {
            $match = \DB::table('matches')->where('id', $is->game)->where('status', 2)->first();
            if($match !== NULL){
            $is->id = $match->id;
            }else{
            $is->id = 0;
            }
            $winner = \DB::table('matches')->where('id', $is->game)->where('status', 2)->pluck('winner');
            $is->date = \DB::table('matches')->where('id', $is->game)->where('status', 2)->pluck('Time');
            $Team1 = \DB::table('matches')->where('id', $is->game)->where('status', 2)->pluck('Team1');
                    $Team2 = \DB::table('matches')->where('id', $is->game)->where('status',  2)->pluck('Team2');
                    $Team1Bank = \DB::table('matches')->where('id', $is->game)->where('status', 2)->pluck('Team1Bank');
                    $Team2Bank = \DB::table('matches')->where('id', $is->game)->where('status', 2)->pluck('Team2Bank');
                    $Bank = \DB::table('matches')->where('id', $is->game)->where('status', 2)->pluck('Bank');
                    if($winner == 1){
                    $winner = $Team1;
                    }else{
                    $winner = $Team2;
                    }
                                /* TEAM 1 */
                    $is->name1 = \DB::table('team')->where('id', $Team1)->pluck('name');
                    $is->img1 = \DB::table('team')->where('id', $Team1)->pluck('img');
                    $is->percent1 = 0;
                    if($Bank == 0){

                    }else{
                    $is->percent1 = floor($Team1Bank / $Bank * 100);
                    }
                    /* TEAM 1 */

                    /* TEAM 2 */
                    $is->name2 = \DB::table('team')->where('id', $Team2)->pluck('name');
                    $is->img2 = \DB::table('team')->where('id', $Team2)->pluck('img');
                    $is->percent2 = 0;
                    if($Bank == 0){

                    }else{
                    $is->percent2 = ceil($Team2Bank / $Bank * 100);
                    }
                    $is->betValue = $is->price;
                    $team2Percent = $is->percent2;
                    $team1Percent = $is->percent1;
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
            if($winner <> $is->team){
                $is->win = 0;
            }else{
                $is->win = 1;
            }
            $is->potencial = 0;
            if($is->team == $Team1){
                        $is->potencial = $is->price * $valueFor1team1;
                    }elseif($is->team == $Team2){
                        $is->potencial = $is->price * $valueFor1team2;
                    }
        }    
        return view('pages.user.mybets', compact('igri', 'igriHistory'));
    }

    public function refCode(){
        if(!Auth::guest()){
            $code = \DB::table('activeRef')->where('userid', $this->user->id)->first(); 
            if($code !== NULL){
               $returnValue = [
               'type' => 'error'
               ];
            }else{
                $returnValue = [
                'type' => 'ok'
                ];
            }
        }else{
            $returnValue = [
            'type' => 'error'
            ];            
        }
        return $returnValue;
    }

    public function acceptCode(Request $r){
        $code = $r->code;
        $codes = \DB::table('users')->where('ref', $code)->first();
        $user = \DB::table('activeRef')->where('userid', $this->user->id)->first();
        if($code == ""){
            $returnValue = ['msg' => 'Вы пытаетесь ввести пустой код', 'type' => 'error'];
        }elseif ($this->user->is_admin == 5) {
            $returnValue = ['msg' => 'Вы забанены', 'type' => 'error'];  
        }elseif($codes == NULL){
            $returnValue = ['msg' => 'Такого кода не существует', 'type' => 'error'];
        }elseif($user !== NULL){
            $returnValue = ['msg' => 'Вы уже ввели код', 'type' => 'error'];
        }elseif($codes->ref == $this->user->ref){
            $returnValue = ['msg' => 'Вы пытаетесь ввести свой код', 'type' => 'error'];
        }else{
            \DB::table('activeRef')->insertGetId(['userid' => $this->user->id, 'code' => $code]);
            \DB::table('users')->where('id', $this->user->id)->update(['money' => $this->user->money + $codes->refMoney]);
            $returnValue = ['type' => 'success'];
        }
        return $returnValue;
    }

    public function deposit(){
        $deposit = \DB::table('trades')->where('user', $this->user->steamid64)->where('status', '<>', 1)->first();
        if($deposit == NULL){
            $deposit = 0;
        }
        return view('pages.user.deposit', compact('deposit'));
    }

    public function withdraw(){
        $deposit = \DB::table('trades')->where('user', $this->user->steamid64)->where('status', 0)->first();
        if($deposit == NULL){
            $deposit = 0;
        }
        return view('pages.user.withdraw', compact('deposit'));
    }

    public function myInventory(){
        if(!\Cache::has('inventory_' . $this->user->steamid64)) {
            $jsonInventory = file_get_contents('http://steamcommunity.com/profiles/' . $this->user->steamid64 . '/inventory/json/730/2?l=english');
            $items = json_decode($jsonInventory, true);
                if ($items['success']) {
                    foreach ($items['rgDescriptions'] as $class_instance => $item) {
                        if($item['market_hash_name'] == '2016 Service Medal'){
                            break;
                        }
                        $info = $this->getItemPrice($item['market_hash_name']);
                        $items['rgDescriptions'][$class_instance]['amount'] = $info;
                    }

                }
            \Cache::put('inventory_' . $this->user->steamid64, $items, 15);
        }else{
           $items = \Cache::get('inventory_' . $this->user->steamid64);
        }
       return $items;
    }

    public function newOffer(Request $r)
    {
        $items = $r->assetids;
        $url = $r->tradeurl;
        $sum = $r->checksum;
        $remember = $r->remember;
        $hashname = $r->hashname;
        $token = $this->_parseToken($link = $url);
        $partner = $this->_parsePartner($link = $url);
        $hashname = explode(",", $hashname);
        $price = 0;
        foreach($hashname as $key => $values)
        {
            if($values == ""){
                break;
            }else{    
                $si = $this->getItemPrice($values);
                $price = $price + $si;
            }
        }
        if(Auth::guest()){
            $returnValue = [
            'type' => 'error',
            'msg' => 'Пожалуйста, ввойдите'
            ];
        }else{
            if($remember == "on"){
                \DB::table('users')->where('id', $this->user->id)->update(['trade_link' => $url, 'accessToken' => $token]);
            }
            $code = $this->code();
            $value = [
                'assetids' => $items,
                'token' => $token,
                'partner' => $partner,
                'checksum' => round($price),
                'steamid' => $this->user->steamid64,
                'code' => $code
            ];
            $this->redis->rpush('new.offer', json_encode($value));
            $returnValue = [
            'type' => 'success'
            ];
        }
        return $returnValue;
    }

    public function confirmTrade(Request $r)
    {
        $tid = $r->tid;
        $row = \DB::table('trades')->where('tid', $tid)->where('user', $this->user->steamid64)->first();
        if($row !== NULL){
            $value = [
            'tid' => $tid,
            'steamid' => $this->user->steamid64
            ];
            $this->redis->rpush('confirm.offer', json_encode($value));
        }
    }

    public function confirmTradeBot(Request $r)
    {
        $action = $r->action;
        $tid = $r->tid;
        $steamid = $r->steamid;
        $user = \DB::table('users')->where('steamid64', $steamid)->first();
        $row = \DB::table('trades')->where('tid', $tid)->where('user', $steamid)->first();
        $ref = \DB::table('activeRef')->where('userid', $user->id)->first();
        if($action == 'accept' && $row->status !== 1) {
            if($row->summa > 0) \DB::table('users')->where('steamid64', $user->steamid64)->update(['money' => $user->money + $row->summa]);
            if($row->summa > 0) \DB::table('items')->where('trade', $tid)->update(['status' => 1]);
            if($row->summa > 0) \DB::table('trades')->where('tid', $tid)->update(['status' => 1]);
            if($ref !== NULL) \DB::table('activeRef')->where('userid', $user->id)->update(['vneseno' => $ref->vneseno + ($row->summa / 10)]);
        } elseif($action == 'error') {
            \DB::table('items')->where('trade', $tid)->delete();
            \DB::table('trades')->where('tid', $tid)->delete();
        }
    }

    public function addItem(Request $r)
    {
        $items = $r->items;
        $tid = $r->tid;
        $steamid = $r->steamid;
        $code = $r->code;
        $s = 0;
        foreach ($items as $key => $value) {
            \DB::table('items')->insertGetId(['trade' => $tid, 'market_hash_name' => $value['market_hash_name'], 'img' => $value['icon_url'], 'time' => time(), 'assetids' => $value['assetid']]);
            $si = $this->getItemPrice($value['market_hash_name']);
            $s += $si;
        }
        \DB::table('trades')->insertGetId(['tid' => $tid, 'code' => $code, 'status' => '0', 'user' => $steamid, 'summa' => round($s * 10), 'time' => time()]);
        return $items;
    }

    public function inventorybot()
    {
        $items = \DB::table('items')->where('status', 1)->get();
        $is = 0;
        if($items !== []){
            foreach($items as $i)
                {
                    $is++;
                    $si = $this->getItemPrice($i->market_hash_name);
                    $i->price = round($si * 10);
                    $return = $items;
                }
            $returnValue =[
                'items' => $return,
                'is' => $is
            ];    
        }else{
        $returnValue =[
            'items' => 0,
            'is' => 0
        ];
        }    
        return $returnValue;
    }

    public function newWithdrawOffer(Request $r)
    {
        $items = $r->assetids;
        $url = $r->tradeurl;
        $sum = $r->checksum;
        $remember = $r->remember;
        $hashname = $r->hashname;
        $token = $this->_parseToken($link = $url);
        $partner = $this->_parsePartner($link = $url);
        $hashname = explode(",", $hashname);
        $assetids = explode(",", $items);
        $price = 0;
        foreach($hashname as $key => $values)
        {
            if($values == ""){
                break;
            }else{
                $item = \DB::table('items')->where('market_hash_name', $values)->first();    
                $si = $this->getItemPrice($values);
                $price = $price + $si;
            }
        }
        $price = $price * 10;
        if(Auth::guest()){
            $returnValue = [
            'type' => 'error',
            'msg' => 'Пожалуйста, ввойдите'
            ];
        }elseif($price > $this->user->money){
            $returnValue = [
            'type' => 'error',
            'msg' => 'Недостаточно монеток на балансе'
            ];
        }else{
            if($remember == "on"){
                \DB::table('users')->where('id', $this->user->id)->update(['trade_link' => $url, 'accessToken' => $token]);
            }
            $code = $this->code();
            \DB::table('users')->where('id', $this->user->id)->update(['money' => $this->user->money - $price]);
            $value = [
                'names' => $hashname,
                'token' => $token,
                'partner' => $partner,
                'checksum' => round($price),
                'steamid' => $this->user->steamid64,
                'code' => $code,
                'ids' => $assetids
            ];
            $this->redis->rpush('new.withdraw.offer', json_encode($value));
            $returnValue = [
            'type' => 'success'
            ];
        }    
        return $returnValue;
    }

    public function declineWithdraw(Request $r)
    {
        $steamid = $r->steamid;
        $sum = $r->sum;
        $user = \DB::table('users')->where('steamid64', $steamid)->first();
        \DB::table('users')->where('steamid64', $steamid)->update(['money' => $user->money + $sum]);
        return $sum;
    }

    public function acceptOfferWithdraw(Request $r)
    {
        $tid = $r->tid;
        $steamid = $r->steamid;
        $checksum = $r->checksum;
        $code = $r->code;
        $assetids = $r->assetids;
        foreach ($assetids as $key) {
            \DB::table('items')->where('assetids', $key)->delete();
        }
        \DB::table('trades')->insertGetId(['tid' => $tid, 'code' => $code, 'status' => 1, 'user' => $steamid, 'summa' => $checksum, 'time' => time()]);
        return $code;
    }

    private function _parseToken($tradeLink)
    {
        $query_str = parse_url($tradeLink, PHP_URL_QUERY);
        parse_str($query_str, $query_params);
        return isset($query_params['token']) ? $query_params['token'] : false;
    }

    private function _parsePartner($tradeLink)
    {
        $query_str = parse_url($tradeLink, PHP_URL_QUERY);
        parse_str($query_str, $query_params);
        return isset($query_params['partner']) ? $query_params['partner'] : false;
    }

    function code()
    {
        $code="";
        $values="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        for($i=0;$i<5;$i++)
        {
            $code.=$values[rand(0,61)];
        }
        return $code;
    }

    public function getActualCurs() {
        $link = self::BANK_URL;
        $str  = file_get_contents($link);

        preg_match('#<Valute ID="R01235">.*?.<Value>(.*?)</Value>.*?</Valute>#is', $str, $value);

        $usd = $value[1];

        return $usd;
    }

    const BANK_URL = 'http://www.cbr.ru/scripts/XML_daily.asp';

    public function getItemPrice($name)
    {
        $file = Storage::get('items.txt');
        $json = json_decode($file, true);
        $price = $json['response']['items'][$name]['value'];
        $usd = $this->getActualCurs();
        $price = $price / 100 * $usd;
        return $price;
    }
}