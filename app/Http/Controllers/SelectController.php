<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Partner;
use Illuminate\Http\Request;

class SelectController extends Controller
{
    public function products(Request $request)
    {
        $search = $request->query('q');
        $query = Product::query()->where('status', 'Available')->orderBy('product_name');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('key_attribute_value', 'like', "%{$search}%");
            });
        }

        $products = $query->limit(20)->get();

        return response()->json($products->map(function ($product) {
            return ['value' => $product->id, 'text' => $product->display_name];
        }));
    }

    public function partners(Request $request)
    {
        $search = $request->query('q');
        $query = Partner::query()->where('is_active', true)->orderBy('partner_name');

        if ($search) {
            $query->where('partner_name', 'like', "%{$search}%");
        }

        $partners = $query->limit(20)->get();

        return response()->json($partners->map(function ($partner) {
            return ['value' => $partner->id, 'text' => $partner->partner_name];
        }));
    }

    public function users(Request $request)
    {
        $search = $request->query('q');
        $query = \App\Models\User::query()->orderBy('name');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->limit(20)->get();

        return response()->json($users->map(function ($user) {
            return ['value' => $user->id, 'text' => "{$user->name} ({$user->email})"];
        }));
    }
}