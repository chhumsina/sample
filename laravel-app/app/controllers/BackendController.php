<?php

class BackendController extends \BaseController {
	/*
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.backend';
	
	/**
	 * Backend page
	 */
	public function dashboard()
	{
		$this->layout->content = View::make('backend.dashboard',compact(''));
	}
}
