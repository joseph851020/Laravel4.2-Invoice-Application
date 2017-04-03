<?php

class MaintenancesController extends \BaseController {

	/**
	 * Display a listing of maintenances
	 *
	 * @return Response
	 */
	public function index()
	{
		$maintenances = Maintenance::all();

		return View::make('maintenances.index', compact('maintenances'));
	}

	/**
	 * Show the form for creating a new maintenance
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('maintenances.create');
	}

	/**
	 * Store a newly created maintenance in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Maintenance::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Maintenance::create($data);

		return Redirect::route('maintenances.index');
	}

	/**
	 * Display the specified maintenance.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$maintenance = Maintenance::findOrFail($id);

		return View::make('maintenances.show', compact('maintenance'));
	}

	/**
	 * Show the form for editing the specified maintenance.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$maintenance = Maintenance::find($id);

		return View::make('maintenances.edit', compact('maintenance'));
	}

	/**
	 * Update the specified maintenance in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$maintenance = Maintenance::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Maintenance::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$maintenance->update($data);

		return Redirect::route('maintenances.index');
	}

	/**
	 * Remove the specified maintenance from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Maintenance::destroy($id);

		return Redirect::route('maintenances.index');
	}

}
