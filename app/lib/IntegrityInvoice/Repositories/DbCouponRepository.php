<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\CouponRepositoryInterface;
use Coupon;

class DbCouponRepository implements CouponRepositoryInterface{
	
	public function getAll()
	{	
		return Coupon::all();
	}
	
	
	public function find($id = 0)
	{
		return Coupon::where('id','=', $id)->first();
	}
 
	public function create($input = array())
	{
		return Coupon::create($input);
	}
	
	public function update($id = 0, $input = array())
	{
		return Coupon::where('id', '=', $id)->update($input);	
	}
	
	
	public function remove($id = 0)
	{
		return Coupon::where('id', '=', $id)->delete();
	}
	
	public function exists($couponcode)
	{
		if(Coupon::where('code','=', $couponcode)->first())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function valid($coupon_code)
	{
		if(Coupon::where('code','=', $coupon_code)->where('valid_to','>', date('Y-m-d H:m:s'))->first())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function validForSelectedPlan($coupon_code = "", $plan_id)
	{
		$coupon = Coupon::where('code','=', $coupon_code)->first();
		
		if($coupon->plan_validity != $plan_id && $coupon->plan_validity != 0)
		{
			return false;
		}
		else
		{
			return true;
		}
		 
	}
	
	public function getCouponDiscount($coupon_code = "")
	{
		return Coupon::where('code','=', $coupon_code)->pluck('value');
	}
 
}
