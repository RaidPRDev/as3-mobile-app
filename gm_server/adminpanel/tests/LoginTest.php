<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
	protected $baseUrl = 'http://localhost:8000/';
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {

         $this->visit('admin/login')
         ->type('info@admin.com', 'email')
         ->type('p@ssw0rd', 'password')
         //->check('terms')
         ->press('Login')
         ->see('Welcome to Admin Panel !!!');
        //$this->assertTrue(true);
    }
}
