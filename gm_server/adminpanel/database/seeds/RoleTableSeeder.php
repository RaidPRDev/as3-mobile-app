<?php

use Illuminate\Database\Seeder;
use App\Role; 

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
        					[
        						'name' => 'admin',
        						'display_name' => 'Admin',
        						'description' => 'Super admin'
        					],
        					[
        						'name' => 'approver',
        						'display_name' => 'Approver',
        						'description' => 'Approver'
        					],
        					[
        						'name' => 'editor',
        						'display_name' => 'Editor',
        						'description' => 'Editor'
        					],
        					[
        						'name' => 'user',
        						'display_name' => 'General User',
        						'description' => 'General User'
        					],
        					
        			   ];
					   
		foreach($roles as $key=>$value)
		{
			Role::create($value);
		}	
    }
}
