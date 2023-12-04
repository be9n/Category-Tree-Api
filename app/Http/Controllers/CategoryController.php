<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::parent()->loadDescendants(3)->get();

            return CategoryResource::collection($categories);
        } catch (\Illuminate\Database\Eloquent\RelationNotFoundException $exception) {
            return response(['error' => 'Relation ' . $exception->relation . ' is not found in the model ' . class_basename($exception->model)]);
        }
    }

    public function store(CategoryRequest $request)
    {
        $data['category'] = Category::create($request->validated());

        return response()->json($data);
    }

    public function update(Category $category, CategoryRequest $request)
    {
        $category->update($request->validated());

        return $category;
    }

    public function show(Category $category)
    {
        $category->loadDescendants(2);

        return CategoryResource::make($category);
    }
}
