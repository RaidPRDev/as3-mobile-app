<?php

use Illuminate\Database\Seeder;
use App\User; 


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = [
        					[
        						'name' => 'admin',
        						'email' => 'admin@goaliemind.com',
        						'password' => '$2a$04$xnKlWGxxHxW9tHFIfqOZNe9lNpopXoO8fbjpAef0XyKvI1A02J84K',
        						'is_admin' => '1'
        					],
        					  					
        			   ];
					   
		foreach($users as $key=>$value)
		{
			User::create($value);
		}	
        
    }
}
