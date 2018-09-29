<?php

/*
	 @author Rafael Alvarado Emmanuelli
	 @about RAID - Rafael Alvarado Indie Development
	 @date June 2016
	 @description Stripe API Token Object Functions
	 @reference https://stripe.com/docs/api/php
*/ 

class Token
{
	/* Create a card token */
	public function createCardToken($info)
	{
		try
		{
			$response =\Stripe\Token::create(array(
				"card" => array(
					"name" => $info["name"],
					"number" => $info["number"],
					"exp_month" => $info["exp_month"],
					"exp_year" => $info["exp_year"],
					"cvc" => $info["cvc"],
					"address_zip" => $info["zip"]
				)
			));
		}
		catch (Exception $e) { $response["ERROR"] = $e->getMessage(); }
		
		return $response;
	}
	
	/* Create a bank account token */
	public function createBankAccountToken($info)
	{
		try
		{
			$response =\Stripe\Token::create(array(
				"bank_account" => array(
					"country" => $info["country"],
					"currency" => $info["currency"],
					"account_holder_name" => $info["account_holder_name"],
					"account_holder_type" => $info["account_holder_type"],
					"routing_number" => $info["routing_number"],
					"account_number" => $info["account_number"]
				)
			));
		}
		catch (Exception $e) { $response["ERROR"] = $e->getMessage(); }
		
		return $response;
	}
	
	/* Retrieve a token */
	public function retrieveToken($info)
	{
		try
		{
			$response =\Stripe\Token::retrieve($info["id"]);
		}
		catch (Exception $e) { $response["ERROR"] = $e->getMessage(); }
		
		return $response;
	}
	
}

?>