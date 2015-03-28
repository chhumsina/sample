<?php

class CategoryMgController extends \BackendController 
{
  public function index()
  {
    $query = Category::select('id, name, description, disable, created_at');

    // Adds a clause to the query
    if ($username) {
      $query->where('name', 'LIKE', "%$name%");
    }
    if (Input::has('status')) {
      $query->where('status', '=', $status);
    }

    $category = $query->paginate(20);

    $this->layout->content = View::make('backend.category.list',compact('category'));
  }

  public function create()
  {
   $this->layout->content = View::make('backend.category.create');
  }

  public function store()
  {
    Category::create(Input::all());
  }
}