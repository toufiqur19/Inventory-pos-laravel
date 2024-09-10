<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user_id = $request->headers->get('id');
            $category = Category::where('user_id', $user_id)->get();
            return $this->sendSuccess("Category list", $category);
        } catch (Exception $e) {
            return $this->sendError("Failed to get Category list", 200, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $user_id = $request->headers->get('id');
            $category = Category::create([
                'name' => $request->name,
                'user_id' => $user_id,
            ]);
            return $this->sendSuccess("Category Created", $category, 201);
        } catch (Exception $e) {
            return $this->sendError("Failed to Create Category", 200, $e->getMessage());
        }
    }
    public function show($category, Request $request)
    {
        try {
            $user_id = $request->headers->get('id');
            $category = Category::where('user_id', $user_id)->findOrFail($category);
            return $this->sendSuccess("Category Details", $category);
        } catch (Exception $e) {
            return $this->sendError("Failed to get Category Details", 200, $e->getMessage());
        }
    }

    public function update(Request $request, $category)
    {
        try {
            $user_id = $request->headers->get('id');
            $category = Category::where('user_id', $user_id)->findOrFail($category)->update([
                'name' => $request->name,
            ]);
            if (!$category) {
                return $this->sendError("Category not found", 404);
            }
            return $this->sendSuccess("Category Updated", []);
        } catch (Exception $e) {
            return $this->sendError("Failed to Update Category", 200, $e->getMessage());
        }
    }

    public function destroy($category, Request $request)
    {
        try {
            $user_id = $request->headers->get('id');
            $category = Category::where('user_id', $user_id)->findOrFail($category);
            $category->delete();
            return $this->sendSuccess("Category Deleted", []);
        } catch (Exception $e) {
            return $this->sendError("Failed to Delete Category", 200, $e->getMessage());
        }
    }
}