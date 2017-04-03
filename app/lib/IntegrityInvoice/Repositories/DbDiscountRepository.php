<?php namespace IntegrityInvoice\Repositories;

use IntegrityInvoice\Repositories\DiscountRepositoryInterface;
use Discount;

class DbDiscountRepository implements DiscountRepositoryInterface{
	
	public function getAll()
	{	
		return Discount::all();
	}
	
	
	public function find($id = 0)
	{
		return Discount::where('id','=', $id)->first();
	}
 
	public function create($input = array())
	{
		return Discount::create($input);
	}
	
	public function update($id = 0, $input = array())
	{
		return Discount::where('id', '=', $id)->update($input);	
	}
	
	public function findByMonth($months)
	{
		return Discount::where('month','=', $months)->first();
	}
	
	
	public function remove($id = 0)
	{
		return Discount::where('id', '=', $id)->delete();
	}
 
}
