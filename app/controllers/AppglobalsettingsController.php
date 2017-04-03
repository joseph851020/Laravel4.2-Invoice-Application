<?php

class AppglobalsettingsController extends \BaseController {

	/**
	 * Display a listing of appglobalsettings
	 *
	 * @return Response
	 */
	public function index()
	{
		$appglobalsettings = Appglobalsetting::all();

		return View::make('appglobalsettings.index', compact('appglobalsettings'));
	}

	/**
	 * Show the form for creating a new appglobalsetting
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('appglobalsettings.create');
	}

	/**
	 * Store a newly created appglobalsetting in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Appglobalsetting::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Appglobalsetting::create($data);

		return Redirect::route('appglobalsettings.index');
	}

	/**
	 * Display the specified appglobalsetting.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$appglobalsetting = Appglobalsetting::findOrFail($id);

		return View::make('appglobalsettings.show', compact('appglobalsetting'));
	}

	/**
	 * Show the form for editing the specified appglobalsetting.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$appglobalsetting = Appglobalsetting::find($id);

		return View::make('appglobalsettings.edit', compact('appglobalsetting'));
	}

	/**
	 * Update the specified appglobalsetting in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$appglobalsetting = Appglobalsetting::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Appglobalsetting::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$appglobalsetting->update($data);

		return Redirect::route('appglobalsettings.index');
	}

	/**
	 * Remove the specified appglobalsetting from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Appglobalsetting::destroy($id);

		return Redirect::route('appglobalsettings.index');
	}

}
