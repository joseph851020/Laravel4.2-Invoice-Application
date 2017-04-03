<?php

class APIController extends BaseController {
	
	private $url_params;
	private $tenantID;
	private $authUser;
	private $email;
	private $password;
	private $id = 0;
	private $action;
	private $applications;
	private $enc_request;
	
	public function __construct()
	{
		$this->url_params = Request::all();
		$this->id = (int)$this->url_params['id'];
		
		//Define our id-key pairs
		$this->applications = array(
		   'APP001' => '28e336ac6c9423d946ba02d19c6a2632', //randomly generated app key
		);
		
		//get the encrypted request
	    $this->enc_request = $this->url_params['enc_request'];
	    
	    //get the provided app id
	    $this->app_id = $this->url_params['app_id'];
		
		if( !isset($applications[$app_id]) ) 
		{
		     return 'Application does not exist!';
		}
		//decrypt the request
   		$this->url_params = json_decode(trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->applications[$this->app_id], base64_decode($this->enc_request), MCRYPT_MODE_ECB)));
   
   		if( $this->url_params == false || isset($this->url_params['action']) == false) 
   		{
		    return 'Request is not valid';
		}
		
		//cast it into an array
   		$this->url_params = (array) $this->url_params;
		
		$action = $this->url_params['action'];
		$this->action.'()';
		
		// dd($this->url_params['id']);
	}
	
	public function authenticate($username="", $password="")
	{
		if(Auth::attempt(array('email' => $username, 'password' => $password)))
		{
			$this->authUser = Auth::user();
			$this->tenantID = $this->authUser->tenantID;
			return true;
		}	
		
	}


	public function removeItem()
	{
		if($this->authenticate($this->url_params['username'], $this->url_params['password']))
		{
			if($this->url_params['tenantID'] == $this->tenantID){
				 
				$remover = new IntegrityInvoice\Api\Item\Remover();
				return $remover->remove($this->tenantID, $this->id);
			}
			else
			{
				return json_encode("Unknown User!");
			}		
		}
		else
		{
			return json_encode('Invalid Credenitial!');
		}
	}
	

	public function createItem()
	{
		if($this->authenticate($this->url_params['username'], $this->url_params['password']))
		{
			if($this->url_params['tenantID'] == $this->tenantID){
				 
				$item_params =  
				$creator = new IntegrityInvoice\Api\Item\Creator();
				return $creator->remove($this->tenantID, $this->url_params);
			}
			else
			{
				return json_encode("Unknown User!");
			}		
		}
		else
		{
			return json_encode('Invalid Credenitial!');
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
         
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
   
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
