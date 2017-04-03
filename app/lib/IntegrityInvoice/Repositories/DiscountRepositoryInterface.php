<?php namespace IntegrityInvoice\Repositories;

interface DiscountRepositoryInterface{
	
	public function getAll();
	
	public function find($id);
	
	public function create($input);
	
	public function update($id, $input);
	
	public function remove($id);
	
	public function findByMonth($months);
}
