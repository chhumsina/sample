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
		$memActive = Member::where('use_type','2')->where('status','1')->count();
		$memInactive = Member::where('use_type','2')->where('status','0')->count();
		$members = $memActive + $memInactive;

		// post record
		$postActive = Post::where('status','1')->count();
		$postInactive = Post::where('status','0')->count();
		$posts = $postActive + $postInactive;

		// category record
		$cateActiveQuery = "select * from category where status = 1";
		$cateInactiveQuery = "select * from category where status = 0";
		$cateActive = DB::select(DB::raw($cateActiveQuery));
		$cateInactive = DB::select(DB::raw($cateInactiveQuery));
		$categories = $cateActive + $cateInactive;


		$this->layout->content = View::make('backend.dashboard',compact('members','memActive','memInactive',
																		'posts','postActive','postInactive',
																		'categories','cateActive','cateInactive'));
	}
}
