<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user_id = $request->headers->get('id');
            $products = Product::where('user_id', $user_id)->get();
            return $this->sendSuccess("Product list", $products);
        } catch (Exception $e) {
            return $this->sendError("Failed to get Product list", 200, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $user_id = $request->headers->get('id');
            if($request->has('image'))
                {
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time().'.'.$extension;
                    $path = 'uploads/images';
                    $file->move($path, $filename);
                    $img_url = "uploads/images/" . $filename;
                }

            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'unit' => $request->unit,
                'img_url' => $img_url,
                'category_id' => $request->category_id,
                'user_id' => $user_id,
            ]);
            if (!$product) {
                return $this->sendError("Failed to Create Product", 200);
                if ($img_url) {
                    unlink($img_url);
                }
            }
            return $this->sendSuccess("Product Created", $product, 201);
        } catch (Exception $e) {
            return $this->sendError("Failed to Create Product", 200, $e->getMessage());
        }
    }

    public function show($product, Request $request)
    {
        try {
            $user_id = $request->headers->get('id');
            $product = Product::where('user_id', $user_id)->findOrFail($product);
            return $this->sendSuccess("Product Details", $product);
        } catch (Exception $e) {
            return $this->sendError("Failed to get Product Details", 200, $e->getMessage());
        }
    }

    public function update(Request $request, $product)
    {
        try {
            $user_id = $request->headers->get('id');
            $product = Product::where('user_id', $user_id)->findOrFail($product);
            $product->name = $request->name;
            $product->price = $request->price;
            $product->unit = $request->unit;
            $product->category_id = $request->category_id;
            $product->user_id = $user_id;

            $new_img_url = null;
            if ($request->file('image')) {
                $image = $request->file('image');
                $image_name = time() . '.' . $image->extension();
                $image->move(public_path('uploads/images'), $image_name);
                $new_img_url = "uploads/images/" . $image_name;
                unlink($product->img_url);
            }

            $product->img_url = $new_img_url ? $new_img_url : $product->img_url;
            $product->save();

            if (!$product) {
                return $this->sendError("Failed to Update Product", 200);
            }
            return $this->sendSuccess("Product Updated", $product);
        } catch (Exception $e) {
            return $this->sendError("Failed to Update Product", 200, $e->getMessage());
        }
    }
    public function destroy($product, Request $request)
    {
        try {
            $user_id = $request->headers->get('id');
            $product = Product::where('user_id', $user_id)->findOrFail($product);
            if ($product->img_url) {
                unlink($product->img_url);
            }
            if (!$product->delete()) {
                return $this->sendError("Failed to Delete Product", 200);
            }
            return $this->sendSuccess("Product Deleted", []);
        } catch (Exception $e) {
            return $this->sendError("Failed to Delete Product", 200, $e->getMessage());
        }
    }
}