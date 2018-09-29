<?php

/*
	 @author Rafael Alvarado Emmanuelli
	 @about RAID - Rafael Alvarado Indie Development
	 @date June 2016
	 @description Stripe API Card Object Functions
	 @reference https://stripe.com/docs/api/php
*/ 

class Card
{
	public function addDefaultCard($info)
	{
		try
		{
			$response = \Stripe\Customer::create(array(
				"email" => $info["email"],
				"description" => $info["description"]
			));
		}
		catch (Exception $e) { $response["ERROR"] = $e->getMessage(); }
		
		return $response;
	}
	
	public function deleteCard($info)
	{
		try
		{
			$response = $customer->sources->retrieve($info["cardid"])->delete();
		}
		catch (Exception $e) { $response["ERROR"] = $e->getMessage(); }
		
		return $response;
	}
	
	public function chargeCard($info)
	{
		try
		{
			$charge = \Stripe\Charge::create(array(
			  "amount" => $info["amount"],
			  "currency" => $info["currency"],
			  "customer" => $info["customerid"],
			  "card" => $info["cardid"], 
			  "description" => "Charge for " . $info["email"],
			  "metadata" => array("userid" => $info["uid"],"paymentid" => $info["paymentid"])
			));
			
			$response["chargeData"] = $charge;
			$response["result"] = true;
		}
		catch (\Stripe\Error\Card $e) 
		{ 
			$response["result"] = false;
			$response["ERROR"] = $e->getMessage(); 
		}
		
		return $response;
	}
}

?>