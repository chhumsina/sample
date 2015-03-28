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
		// member record
		$memActive = User::where('role_id','2')->where('disable','1')->count();
		$memInactive = User::where('role_id','2')->where('disable','0')->count();
		$members = $memActive + $memInactive;

		// Ads record
		$adsActive = Ads::where('disable','1')->count();
		$adsInactive = Ads::where('disable','0')->count();
		$ads = $adsActive + $AdsInactive;

		// category record
		$catActive = Ads::where('disable','1')->count();
		$catInactive = Ads::where('disable','0')->count();
		$categories = $catActive + $catInactive;

		// advertisement record
		$advActive = Ads::where('disable','1')->count();
		$advInactive = Ads::where('disable','0')->count();
		$advertisements = $advActive + $advInactive;


		$this->layout->content = View::make('backend.dashboard',compact('members','memActive','memInactive',
																		'ads','adsActive','adsInactive',
																		'categories','catActive','catInactive',
																		'advertisements','advActive','advInactive'));
	}
}
