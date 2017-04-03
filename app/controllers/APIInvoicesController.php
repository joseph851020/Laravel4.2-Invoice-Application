<?php

use IntegrityInvoice\Repositories\InvoiceRepositoryInterface;

class APIInvoicesController extends Controller {


	protected $invoice;
	protected $tenantID;
	protected $perPage;

	function __construct(InvoiceRepositoryInterface $invoice) {
		$this->invoice = $invoice;
		$this->tenantID = Input::get('tenantID');
		$this->perPage = Input::get('perPage');
	}

	/**
	 * Display a listing of the resource.
	 * GET /apiinvoices
	 *
	 * @return Response
	 */
	public function index()
	{

		return $this->invoice->getAll($this->tenantID, $this->perPage)->toArray();
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /apiinvoices/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /apiinvoices
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /apiinvoices/{id}
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
	 * GET /apiinvoices/{id}/edit
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
	 * PUT /apiinvoices/{id}
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
	 * DELETE /apiinvoices/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
