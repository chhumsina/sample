<?php

class LocationController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /location
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /location/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /location
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /location/{id}
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
	 * GET /location/{id}/edit
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
	 * PUT /location/{id}
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
	 * DELETE /location/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	/**
	 * Get a listing of the resource.
	 * GET /location
	 *
	 * @return Response
	 */
	public function getKhanByProvinceCode($code)
	{
		$listKhans = Khan::where('province__code','=',$code)->lists('name_en', 'code');
		return Response::json($listKhans);
	}
	
	/**
	 * Get a listing of the resource.
	 * GET /location
	 *
	 * @return Response
	 */
	public function getCommuneByKhanCode($code)
	{
		$listCommunes = DB::table('communes')->where('khan__code',$code)->lists('name_en', 'code');
		return Response::json($listCommunes);
	}
	
	/**
	 * Get a listing of the resource.
	 * GET /location
	 *
	 * @return Response
	 */
	public function getVillageByCommuneCode($code)
	{
		$listCommunes = DB::table('villages')->where('commune__code',$code)->lists('name_en', 'code');
		return Response::json($listCommunes);
	}
}