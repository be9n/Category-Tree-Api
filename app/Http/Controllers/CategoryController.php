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
            $categories = Category::whereParent()->loadDescendants()->get();

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
        $category->loadDescendants();

        return CategoryResource::make($category);
    }

    public function destroy(Category $category)
    {
        if ($category->children()->count()) {
            return response(['message' => "You can't delete this category before deleting it's subcategories"], 422);
        }

        $category->delete();

        return response(['message' => 'The category is deleted successfully'], 200);
    }

    public function forceDestroy(Category $category){
        $category->delete();

        return response(['message' => 'The category is deleted with all the subcategories successfully'], 200);
    }
}
