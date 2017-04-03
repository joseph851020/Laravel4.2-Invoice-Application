<?php

class LabelsController extends \BaseController {

	/**
	 * Display a listing of labels
	 *
	 * @return Response
	 */
	public function index()
	{
		$labels = Label::all();

		return View::make('labels.index', compact('labels'));
	}

	/**
	 * Show the form for creating a new label
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('labels.create');
	}

	/**
	 * Store a newly created label in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Label::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Label::create($data);

		return Redirect::route('labels.index');
	}

	/**
	 * Display the specified label.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$label = Label::findOrFail($id);

		return View::make('labels.show', compact('label'));
	}

	/**
	 * Show the form for editing the specified label.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$label = Label::find($id);

		return View::make('labels.edit', compact('label'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$label = Label::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Label::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$label->update($data);

		return Redirect::route('labels.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Label::destroy($id);

		return Redirect::route('labels.index');
	}

}