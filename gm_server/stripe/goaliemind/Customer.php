<?php

/*
	 @author Rafael Alvarado Emmanuelli
	 @about RAID - Rafael Alvarado Indie Development
	 @date June 2016
	 @description Stripe API Customer Object Functions
	 @reference https://stripe.com/docs/api/php
*/ 

class Customer
{
	public function createCustomer($info)
	{
		try
		{
			// source: The source can either be a token or user’s credit card details
			
			$customerInfo = array();
			$customerInfo["email"] = $info["email"];
			$customerInfo["description"] = $info["description"];
			// $customerInfo["metadata"] = $info["metadata"];
			if (isset($info["tokenID"])) $customerInfo["source"] = $info["tokenID"];
			
			$response = \Stripe\Customer::create($customerInfo);
			$response["result"] = true;
		}
		catch (Exception $e) 
		{ 
			$response["result"] = false;
			$response["ERROR"] = $e->getMessage(); 
		}
		
		return $response;
	}
	
	public function retrieveCustomer($info)
	{
		try
		{
			$response = \Stripe\Customer::retrieve($info["id"]);
			$response["result"] = true;
		}
		catch (Exception $e) 
		{ 
			$response["result"] = false;
			$response["ERROR"] = $e->getMessage(); 
		}
		
		return $response;
	}
	
	public function updateCustomer($info)
	{
		try
		{
			//$customer = \Stripe\Customer::retrieve($info["customerid"]);
			$customer = \Stripe\Customer::retrieve($info["id"]);
			
			$customer->source = $info["tokenID"];
			$response = $customer->save();
			$response["result"] = true;
		}
		catch (Exception $e) 
		{ 
			$response["result"] = false;
			$response["ERROR"] = $e->getMessage(); 
		}
		
		return $response;
	}
	
	public function updateCustomerCard($info)
	{
		try
		{
			$customer = \Stripe\Customer::retrieve($info["customerid"]);
			$card = $customer->sources->retrieve($info["cardid"]);
			$card->name = $info["name"];
			$card->exp_month = $info["exp_month"];
			$card->exp_year = $info["exp_year"];
			$card->save();
			
			if ($info["isDefaultCard"] == true)	$customer->default_source = $info["cardid"]; 	
			
			$response = $customer->save();
			$response["result"] = true;
		}
		catch (Exception $e) 
		{ 
			$response["result"] = false;
			$response["ERROR"] = $e->getMessage(); 
		}
		
		return $response;
	}
	
	public function addCustomerCard($info)
	{
		try
		{
			$customer = \Stripe\Customer::retrieve($info["customerid"]);
			
			// Handles multiple cards
			$response = $customer->sources->create(array("source" => $info["tokenid"]));
			
			// Check if the user set this to be a Default Card 
			if ($info["isDefaultCard"] == true)
			{
				$customer->default_source = $info["cardid"];	
				$response = $customer->save();
			}
			
			$response["result"] = true;
		}
		catch (Exception $e) 
		{ 
			$response["result"] = false;
			$response["ERROR"] = $e->getMessage(); 
		}
		
		return $response;
	}
	
	public function deleteCustomerCard($info)
	{
		try
		{
			$customer = \Stripe\Customer::retrieve($info["customerid"]);
			$response = $customer->sources->retrieve($info["cardid"])->delete();
			$response["result"] = true;
		}
		catch (Exception $e) 
		{ 
			$response["result"] = false;
			$response["ERROR"] = $e->getMessage(); 
		}
		
		return $response;
	}
	
	public function deleteCustomer($info)
	{
		try
		{
			$customer = \Stripe\Customer::retrieve($info["id"]);
			$response = $customer->delete();
			$response["result"] = true;
		}
		catch (Exception $e) 
		{ 
			$response["result"] = false;
			$response["ERROR"] = $e->getMessage(); 
		}
		
		return $response;
	}
	
	public function getAllCustomers()
	{
		try	
		{ 
			$response = \Stripe\Customer::all(); 
			$response["result"] = true;
		}
		catch (Exception $e) 
		{ 
			$response["result"] = false;
			$response["ERROR"] = $e->getMessage(); 
		}
		
		return $response;
	}
}

?>