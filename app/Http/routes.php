<?php

get('/login', ['as' => 'login', 'uses' => 'SteamController@login']);
get('/', ['as' => 'index', 'uses' => 'PagesController@index']);
get('/matches', ['as' => 'matches', 'uses' => 'MatchesController@index']);
get('/matchid/{id}', ['as' => 'matchid', 'uses' => 'MatchesController@game']);
get('/contact', ['as' => 'contact', 'uses' => 'PagesController@contact']);
get('/about', ['as' => 'about', 'uses' => 'PagesController@about']);
post('/number_matches', ['as' => 'number_matches', 'uses' => 'MatchesController@number_matches']);
post('/refCode', ['as' => 'refcode', 'uses' => 'UserController@refCode']);

Route::group(['middleware' => 'auth'], function () {
    get('/logout', ['as' => 'logout', 'uses' => 'SteamController@logout']);
    get('/mybets', ['as' => 'my.bets', 'uses' => 'UserController@mybets']);
    get('/deposit', ['as' => 'deposit', 'uses' => 'UserController@deposit']);
    get('/withdraw', ['as' => 'withdraw', 'uses' => 'UserController@withdraw']);
    post('/get_balance', ['as' => 'get_balance', 'uses' => 'UserController@get_balance']);
    post('/updateCode', ['as' => 'updateCode', 'uses' => 'UserController@updateCode']);
    post('/acceptCode', ['as' => 'acceptCode', 'uses' => 'UserController@acceptCode']);
    post('/new_bet', ['as' => 'new_bet', 'uses' => 'MatchesController@new_bet']);
  	post('/change_bet', ['as' => 'change_bet', 'uses' => 'MatchesController@change_bet']);
  	post('/acceptCode', ['as' => 'accept', 'uses' => 'UserController@acceptCode']);
  	post('/myinventory', ['as' => 'inventory', 'uses' => 'UserController@myInventory']);
  	post('/inventorybot', ['as' => 'inventory', 'uses' => 'UserController@inventorybot']);
  	post('/newOffer', ['as' => 'newOffer', 'uses' => 'UserController@newOffer']);
  	post('/newWithdrawOffer', ['as' => 'newWithdrawOffer', 'uses' => 'UserController@newWithdrawOffer']);
  	post('/confirmTrade', ['as' => 'confirmTrade', 'uses' => 'UserController@confirmTrade']);
  	get('/banned', ['as' => 'banned', 'uses' => 'PagesController@banned']);
  	get('/getItemPrice2', 'UserController@getItemPrice2');
});

Route::group(['middleware' => 'auth', 'middleware' => 'access:admin'], function () {
	get('/admin', ['as' => 'admin', 'uses' => 'AdminController@index']);
	get('/admin/users', ['as' => 'admin.users', 'uses' => 'AdminController@users']);
	get('/admin/matches', ['as' => 'admin.matches', 'uses' => 'AdminController@matches']);
	get('/admin/addTeam', ['as' => 'admin.addTeam', 'uses' => 'AdminController@addTeam']);
	get('/admin/manage_user/{userid}', ['as' => 'admin.manage_user', 'uses' => 'AdminController@manage_user']);
	get('/admin/manage_match/{matchid}', ['as' => 'admin.manage_user', 'uses' => 'AdminController@manage_match']);
	get('/admin/addMatch', ['as' => 'add.match', 'uses' => 'AdminController@addMatch']);
	get('/admin/ref', ['as' => 'admin.ref', 'uses' => 'AdminController@indexRef']);
	get('/admin/ref/code', ['as' => 'add.ref.code', 'uses' => 'AdminController@addCode']);
	get('/admin/userBet/{gameid}', ['as' => 'userBet', 'uses' => 'AdminController@userBet']);
	post('/admin/addMatch', ['as' => 'add.match', 'uses' => 'AdminController@addMatches']);
	post('/admin/manage_user', ['as' => 'admin.manage', 'uses' => 'AdminController@manage_user_lol']);
	post('/admin/winteam', ['as' => 'admin.winteam', 'uses' => 'AdminController@winteam']);
	post('/admin/stream', ['as' => 'admin.stream', 'uses' => 'AdminController@stream']);
	post('/admin/addTeam', ['as' => 'admin.addTeam', 'uses' => 'AdminController@addTeamPost']);
	post('/admin/addCode', ['as' => 'admin.addCode', 'uses' => 'AdminController@addCodePost']);
});

Route::group(['prefix' => 'api', 'middleware' => 'secretKey'], function () {
	post('/statusGame', 'MatchesController@statusGame');
	post('/addItem', 'UserController@addItem');
	post('/acceptOffer', 'UserController@confirmTradeBot');
	post('/declineWithdraw', 'UserController@declineWithdraw');
	post('/acceptOfferWithdraw', 'UserController@acceptOfferWithdraw'); 
});
