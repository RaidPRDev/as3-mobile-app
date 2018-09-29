<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return redirect('index.php/admin');
});

//Route::auth();

// Include the authentication and password routes
Route::auth(['authentication', 'password']);

Route::get('/home', 'HomeController@index');

//Route::get('/admin/notification', 'ArticleController@notification');

Route::group(['middleware'=>['admin_auth','admin']],function(){
	Route::get('/admin', 'AdminController@index');	
	
	//Group to put all the routes that need login first
	Route::group(array('prefix'=> 'admin','middleware'=>['role:admin']), function(){

		//Begin Project Tags
		Route::get('projectTags', 'ProjectsController@listProjectTags')->name('admin.projects.listProjectTags');
		Route::get('projectTags/create', 'ProjectsController@createProjectTags')->name('admin.projects.createProjectTags');
		Route::post('projectTags/store', 'ProjectsController@storeProjectTags')->name('admin.projects.storeProjectTags');
		Route::get('projectTags/edit/{id}', 'ProjectsController@editProjectTags')->name('admin.projects.editProjectTags');
		Route::put('projectTags/update/{id}', 'ProjectsController@updateProjectTags')->name('admin.projects.updateProjectTags');
		Route::delete('projectTags/destroy/{id}', 'ProjectsController@destroyProjectTags')->name('admin.projects.destroyProjectTags');
		Route::get('ajax_get_projectTags', 'ProjectsController@ajax_get_projectTags');

		Route::get('{user}/projectTags', 'ProjectsController@listUserProjectTags')->name('admin.{user}.listProjectTags');
		Route::get('ajaxUserProjectTags/{user}', 'ProjectsController@ajaxUserProjectTags');
		//End Project Tags

		/*Begin Settings Routes*/
		Route::get('settings', 'SettingController@index')->name('admin.settings.index');
		Route::get('settings/edit/{id}', 'SettingController@edit')->name('admin.settings.edit');
		Route::put('settings/update/{id}', 'SettingController@update')->name('admin.settings.update');
		Route::get('settings/ajax_get_settings', 'SettingController@ajax_get_settings');
		/*End Settings Routes*/

		/*Begin Groups Routes*/
		Route::get('groups', 'GroupController@index')->name('admin.groups.index');
		Route::get('groups/ajax_get_groups', 'GroupController@ajax_get_groups');
		/*End Groups Routes*/
	});
		
	
	Route::group(array('prefix'=> 'admin','middleware'=>['role:admin|approver']), function(){
		//Route::auth();
		Route::resource('users' , 'UsersController' );
		
		Route::get('/ajax_get_users', 'UsersController@ajax_get_user');	
	});

	Route::group(array('prefix'=> 'admin','middleware'=>['role:admin|editor']), function(){
		//Route::auth();
		//Route::resource("articles","ArticleController");
		//Route::get('/ajax_get_templates/{id}', 'ArticleController@ajax_get_templates');
		//Route::get('/ajax_get_articles', 'ArticleController@ajax_get_articles');	
	});
	
	
	Route::group(array('prefix'=> 'admin','middleware'=>['role:admin|editor']), function(){
		//Route::auth();
		Route::resource("emails","EmailController");
		Route::get('/ajax_get_templates/{id}', 'EmailController@ajax_get_templates');
		Route::get('/ajax_get_emails', 'EmailController@ajax_get_emails');	
	});
	
	Route::group(array('prefix'=>'admin','middleware'=>['role:admin']), function(){
		Route::resource('changepassword' , 'ChangepasswordController' );
		//Route::get('passwordstore' , 'ChangepasswordController@store' );
	});
	//Entrust::routeNeedsRole('admin/users*', array('admin','approver'));
});	

Route::group(['prefix' => 'admin'], function() {
    Route::auth();
});

/*Project Routes*/
//Route::resource('admin/projects' , 'ProjectsController' );
Route::resource('admin/{user}/projects' , 'ProjectsController' );

Route::get('admin/ajax_get_projects/{user}', 'ProjectsController@ajax_get_project');

//Project Fund
Route::get('/admin/{user}/receivedProjectFunds' , 'ProjectsController@showreceivedProjectFunds' )->name('admin.{user}.projects.receivedProjectFunds');
Route::get('/admin/ajaxReceivedProjectFunds/{user}', 'ProjectsController@ajaxReceivedProjectFunds');	

Route::get('/admin/{user}/givenProjectFunds' , 'ProjectsController@showgivenProjectFunds' )->name('admin.{user}.projects.givenProjectFunds');
Route::get('/admin/ajaxGivenProjectFunds/{user}', 'ProjectsController@ajaxGivenProjectFunds');	
/*Project Routes*/

/*Transaction Routes*/
Route::get('admin/{user}/transactions', 'TransactionController@index')->name('admin.{user}.transactions.index');
Route::get('admin/{user}/transactions/ajax_get_transactions', 'TransactionController@ajax_get_transactions');
/*Transaction Routes*/


