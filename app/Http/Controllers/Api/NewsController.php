<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Newspaper;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of all newspapers.
     */
    public function index()
    {
        try {
            $newspapers = Newspaper::with('category', 'user')
                ->latest()
                ->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Newspapers retrieved successfully',
                'data' => $newspapers
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve newspapers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a specific newspaper.
     */
    public function show($id)
    {
        try {
            $newspaper = Newspaper::with('category', 'user')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Newspaper retrieved successfully',
                'data' => $newspaper
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Newspaper not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve newspaper',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created newspaper.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $validated['author'] = auth()->user()->name ?? 'Anonymous';
            $validated['user_id'] = auth()->id();

            // Handle image upload
            if ($request->hasFile('image')) {
                $path = 'news-images/';
                $imgName = 'news-image' . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path($path), $imgName);
                $validated['image'] = $path . $imgName;
            }

            $newspaper = Newspaper::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Newspaper created successfully',
                'data' => $newspaper->load('category', 'user')
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create newspaper',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a newspaper.
     */
    public function update(Request $request, $id)
    {
        try {
            $newspaper = Newspaper::findOrFail($id);

            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'category_id' => 'sometimes|required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($newspaper->image && file_exists(public_path($newspaper->image))) {
                    unlink(public_path($newspaper->image));
                }

                $path = 'news-images/';
                $imgName = 'news-image' . time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path($path), $imgName);
                $validated['image'] = $path . $imgName;
            }

            $newspaper->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Newspaper updated successfully',
                'data' => $newspaper->load('category', 'user')
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Newspaper not found'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update newspaper',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a newspaper.
     */
    public function destroy($id)
    {
        try {
            $newspaper = Newspaper::findOrFail($id);

            // Delete image
            if ($newspaper->image && file_exists(public_path($newspaper->image))) {
                unlink(public_path($newspaper->image));
            }

            $newspaper->delete();

            return response()->json([
                'success' => true,
                'message' => 'Newspaper deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Newspaper not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete newspaper',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get newspapers by category.
     */
    public function getByCategory($categoryId)
    {
        try {
            $newspapers = Newspaper::where('category_id', $categoryId)
                ->with('category', 'user')
                ->latest()
                ->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Newspapers retrieved successfully',
                'data' => $newspapers
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve newspapers',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
