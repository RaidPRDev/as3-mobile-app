<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
        				[
        					'name' => 'admin',
    						'display_name' => 'Admin dashboard',
    						'description' => 'Admin dashboard'
        				],
        				[
        					'name' => 'category-create',
    						'display_name' => 'Create category',
    						'description' => 'Create category'
        				],
        				[
        					'name' => 'category-update',
    						'display_name' => 'Update category',
    						'description' => 'Update category'
        				],
        				[
        					'name' => 'category-delete',
    						'display_name' => 'Delete category',
    						'description' => 'Delete category'
        				]
        			   ];
					   
		foreach($permissions as $key=>$value)
		{
			Permission::create($value);
		}						   
    }
}
