<?php

use Illuminate\Database\Seeder;
use App\TransactionType;

class TransactionTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactiontypes = [
        	[
        		"type"=>"create_customer",
        		"description_template"=>"{uname} stripe account was created on {date}" 
        	],
        	[
        		"type"=>"create_token",
        		"description_template"=>"Stripe token was created for adding stripe payment card of {uname} on {date}"
        	],
        	[
        		"type"=>"create_customer_card",
        		"description_template"=>"Stripe payment card of {uname} was created on {date}" 
        	],
        	[
        		"type"=>"update_customer_card",
        		"description_template"=>"Stripe payment card of {uname} was updated on {date}" 
        	],
        	[
        		"type"=>"charge_card",
        		"description_template"=>"{uname} card was charged and captured for processing of payment of project {projectname} on {date}"
        	],
        	[
        		"type"=>"delete_customer_card",
        		"description_template"=>"{uname} stripe payment card was deleted on {date}" 
        	],
        	[
        		"type"=>"notification",
        		"description_template"=>"{uname} has funded \${amt} to project {projectname} on {date}" 
        	],
        	[
        		"type"=>"organisation_user_wallet",
        		"description_template"=>"\${amt} has been added to organisation referral user {uname} wallet for project {projectname} on {date}"
        	],
        	[
        		"type"=>"Referral_user_wallet",
        		"description_template"=>"\${amt} has been added to referral user {uname} wallet for project {projectname} on {date}"
        	],
        	[
        		"type"=>"project_creator_wallet",
        		"description_template"=>"\${amt} has been added to project creator {uname} wallet for project {projectname} on {date}"
        	],
        	[
        		"type"=>"goaliemind_wallet",
        		"description_template"=>"\${amt} has been added to goaliemind wallet for project {projectname} on {date}"        	]
        ];

        foreach($transactiontypes as $key=>$value)
        {
        	TransactionType::create($value);
        }
    }
}
