<?php 
class Subscription extends Eloquent {
	
	public function is_active(){
        $status = Tenant::where('tenantID', '=', Session::get('tenantID'))->pluck('status');
		
		if($status == 1){
			return true;
		}else{
			return false;
		}
	} 
	
	public function due(){
		$valid_to_date = $this->valid_to() != false ? $this->valid_to() : strtotime('yesterday', time());
		$today = strftime("%Y-%m-%d", time());
		if($today > $valid_to_date){
			return true;
		}else{
			return false;
		}
	} 
	
	public function restrict_access(){
		if($this->due() && $this->get_plan_id(Session::get('tenantID')) != 1){
			return true;
		}else{
			return false;
		}		
		
	} // END Restrict Access
	
	
	public function valid_to(){
	 
		$valid_to = PaymentsHistory::where('tenantID', '=', Session::get('tenantID'))->orderBy('id', 'desc')->take(1)->pluck('valid_to');
		
		if($valid_to != NULL){
			return $valid_to;
		}else{
			return date('Y-m-d', strtotime('today'));
		}
	 
	}
	
	public function valid_from(){
  
		$valid_from = PaymentsHistory::where('tenantID', '=', Session::get('tenantID'))->orderBy('id', 'desc')->take(1)->pluck('valid_from');
		
		if($valid_from != NULL){
			return $valid_from;
		}else{
			return date('Y-m-d', strtotime('today'));
		}
		
	} 
	
	// Get tenant
	public function get_subscription($tenantID){
		// result query
		return Tenant::where('id', '=', $tenantID)->get();
	}
	
	public function get_plan_id($tenantID){
		// result query
		return Tenant::where('id', '=', $tenantID)->pluck('account_plan_id');
	}
	 
	// Get By name
	public function get_plan($id){
		return AccountPlan::where('id', '=', $id);
	} // END	
	
	
	// VALID TO DAYS REMAIANING
	public function get_valid_to_days_remaining($tenantID){
		$valid_to = $this->valid_to();
		$today = date('Y-m-d', strtotime('today'));
		
		$diff = strtotime($valid_to) - strtotime($today);
		$days = floor($diff / (60*60*24));
		return $days;

	}
	
}