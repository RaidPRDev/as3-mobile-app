<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsersTest extends TestCase
{
    //use WithoutMiddleware; 

	protected $baseUrl = 'http://localhost:8000/';

	public function user_log_in()
    {
        
        $this->visit('admin/login')
         ->type('info@admin.com', 'email')
         ->type('p@ssw0rd', 'password')
         //->check('terms')
         ->press('Login')
         ->see('Welcome to Admin Panel !!!');
    }

    /**
     * create tags test case
     *
     * @return void
     */
    public function testUserCreate()
    {
    	$this->user_log_in();

        $this->visit('admin/users/create')
         ->type(csrf_token(),'_token')
         ->type('test user', 'name')
         ->type('erertret', 'email')
         ->type('erertret', 'password')
         ->type('erertret', 'confirm_password')
         ->select('2', 'role')
         ->press('Create')
         ->see('USERS');
         //->seePageIs(route('admin.tags.index'));
    }

    /**
     * edit tags test case
     *
     * @return void
     */
    public function testUserUpdate()
    {
    	$this->user_log_in();

        $this->visit('admin/users/3/edit')
         ->type('PUT','_method')
         ->type('test user', 'name')
         ->type('erertret', 'email')
         ->type('erertret', 'password')
         ->type('erertret', 'confirm_password')
         ->select('3', 'role')
         ->press('Update')
         ->see('USERS');
         //->see('Tag created successfully!');
    }

    /**
     * delete tags test case
     *
     * @return void
     */
    public function testUserDelete()
    {
    	$this->user_log_in();
    	
        $this->visit(route('admin.users.destroy', 4))
         //->type('delete','_method')
         //->type(csrf_token(),'_token')
         /*->type('test', 'title')
         ->type('2', 'category_id')
         ->type('1', 'status')*/
         //->press('Delete')
         ->see('USERS');
         //->see('Tag created successfully!');
    }
}
