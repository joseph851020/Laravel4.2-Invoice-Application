<?php
use IntegrityInvoice\Repositories\CompanyDetailsRepositoryInterface;
use IntegrityInvoice\Services\CompanyDetails\Creator;
use IntegrityInvoice\Services\CompanyDetails\Reader;
use IntegrityInvoice\Services\CompanyDetails\Updater;
use IntegrityInvoice\Services\CompanyDetails\Remover;

class CompanyController extends BaseController {
	
	public $tenantID;
	public $userId;
	public $company;
	public $tenantVerification;	
	

	public function __construct(CompanyDetailsRepositoryInterface $company)
    {
    	$this->company = $company;
    	$this->tenantID = Session::get('tenantID');
		$this->userId = Session::get('user_id');		 
		$this->tenantVerification = Tenant::where('tenantID', '=', $this->tenantID)->pluck('verified');
 
    }
	
	public function index()
	{
        return View::make('company.index')
		  ->with('title', 'Company Profile')
		  ->with('countries', Country::all())
		  ->with('referral_code', Tenant::where('tenantID','=', $this->tenantID)->pluck('referral_code'))
		  ->with('company', Company::where('tenantID','=', $this->tenantID)->first());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('company.create');
	}

	 
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function logo()
	{
        return View::make('company.uploadlogo');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function uploadlogo()
	{
        $input = Input::All();
        $rules = array(
             'file' => 'image|mimes:jpg,jpeg,png|max:1000|required',
        );
		
		
         $validation = Validator::make($input, $rules);
		 
		 if($validation->fails()){
			return Redirect::route('logo')->withErrors($validation)->withInput();
		 }else{
			
			// Resize file if wider tha 200px or higher than 100px
			
			// Image::make(Input::file('file')->getRealPath())->resize(300, 200)->save('foo.jpg');
	
	        $file = Input::file('file'); // your file upload input field in the form should be named 'file'
	
	        $destinationPath = public_path().'/te_da/'.Session::get('tenantID').'/';
	        $filename = Session::get('tenantID').'.png';
	        //$filename = $file['name'];
	        //$extension =$file->getClientOriginalExtension(); //if you need extension of the file
	        $uploadSuccess = Input::file('file')->move($destinationPath, $filename);
			
			
			// open the image file
			$img = Image::make($destinationPath.$filename);
			
			$imgSize = getimagesize($destinationPath.$filename);
			
			$w = $imgSize[0];
        	$h = $imgSize[1];
			
			// now you are able to resize the instance
			
			if($h > 250){
				$img->resize(null, 250,  function ($constraint) {
    				$constraint->aspectRatio();
				});
			}
			
			if($w > 500){
				$img->resize(500, null,  function ($constraint) {
    				$constraint->aspectRatio();
					$constraint->upsize();
				});
			}
 
			$img->save($destinationPath.$filename);
			
			
			if( $uploadSuccess ) {
          	 return Redirect::route('logo')
					->with('flash_message', 'Logo was successfully updated');
	        } else {
	             return Redirect::route('logo')
						->with('flash_message', 'Logo was not updated');
	        }

		 }

        
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
		// $id = Company::where('tenantID', '=', $this->tenantID)->pluck('id');	
		$updateService = new Updater($this->company, $this);	
		return $updateService->update(array(
			'company_name' =>Input::get('company_name'),
			'add_1' =>Input::get('add_1'),
			'add_2' =>Input::get('add_2'),
			'postal_code' =>Input::get('postal_code'),
			'city' =>Input::get('city'),
			'state' =>Input::get('state'),
			'country' =>Input::get('country'),
			'email' =>Input::get('email'),
			'phone' =>Input::get('phone'),
			'fax' =>Input::get('fax'),
			'website' =>Input::get('website'),
			'phone' =>Input::get('phone'),
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
		));
	}
	
	public function companyDetailsUpdateFails($errors){
		
		return Redirect::route('company')->withErrors($errors)->withInput();
	}
	
	public function companyDetailsUpdateSucceeds(){
		
		return Redirect::route('company')
					->with('flash_message', 'Business profile was updated successfully');
	}

	public function companyDetailsDeletionFails(){
		
		return Redirect::route('company');
	}
	
	public function companyDetailsDeletionSucceeds(){
		
		return Redirect::route('company')
					->with('flash_message', 'Deletion was successfully');
	}

 
	
	public function cancel()
	{
        return View::make('company.cancel');
	}
 

}