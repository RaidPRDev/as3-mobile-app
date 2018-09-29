<?php

use Illuminate\Database\Seeder;
use App\Setting;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
        	[
        		'keyword'=>'minimum_withdrawal', 
        		'value'=>'25', 
        		'description'=>'User withdrawl minimum amount.'
        	],
        	[
        		'keyword'=>'currency', 
        		'value'=>'$', 
        		'description'=>'Symbol for currency.'
        	],
        	[
        		'keyword'=>'goalie_mind_percentage', 
        		'value'=>'20', 
        		'description'=>'Goaliemind % from funded project.'
        	],
        	[
        		'keyword'=>'referred_percentage', 
        		'value'=>'10', 
        		'description'=>'Referred % from funded project less than goaliemind %.'
        	],
        	[
        		'keyword'=>'dollar_to_star', 
        		'value'=>'50', 
        		'description'=>'Conversion of 1 dollar into stars.'
        	],
        	[
        		'keyword'=>'star_to_make_project', 
        		'value'=>'25', 
        		'description'=>'Stars required to make a project.'
        	]
        ];

        foreach($settings as $key=>$value)
        {
        	Setting::create($value);
        }
    }
}
