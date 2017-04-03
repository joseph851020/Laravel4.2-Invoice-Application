<?php namespace IntegrityInvoice\Repositories;

interface CouponRepositoryInterface{
	
	public function getAll();
	
	public function find($id);
	
	public function create($input);
	
	public function update($id, $input);
	
	public function exists($coupon_code);
	
	public function valid($coupon_code);	
	
	public function validForSelectedPlan($coupon_code, $plan_id);
	
	public function getCouponDiscount($coupon_code);
	
	public function remove($id);
}
