<?php

use IntegrityInvoice\Repositories\ClientRepositoryInterface;
use IntegrityInvoice\Services\Client\Creator as ClientCreator;
use IntegrityInvoice\Services\Client\Reader as ClientReader;
use IntegrityInvoice\Services\Client\Updater as ClientUpdater;
use IntegrityInvoice\Services\Client\Remover as ClientRemover;



class APIClientsController extends \BaseController {


	public $tenantID;
	public $client;
	public $userId;
	public $user;
	public $perPage;
	public $totalClients;
	public $clientLimit;

	function __construct(ClientRepositoryInterface $client) {
		$this->client = $client;
		$this->tenantID = Input::get('tenantID');
		$this->perPage = Input::get('perPage');
		$this->_params = Input::all();
	}


	public function authenticateAccount()
	{

		$authenticator = new IntegrityInvoice\Api\Account\Authenticator($this);

		return $authenticator->authenticate(array(
			'email' => $this->_params['email'],
			'password' => $this->_params['password'],
			'tenantID' => $this->_params['tenantID']
		));
	}

	/**
	 * Display a listing of the resource.
	 * GET /apiclient
	 *
	 * @return Response
	 */
	public function index()
	{
		if ($this->user = $this->authenticateAccount()) {
			$clientreader = new ClientReader($this->client, $this);
			return $clientreader->readAll();
		}
		return false;
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /apiclient/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /apiclient
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /apiclient/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /apiclient/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /apiclient/{id}
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
	 * DELETE /apiclient/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
