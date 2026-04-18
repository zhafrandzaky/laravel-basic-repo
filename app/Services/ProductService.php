<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function create(array $data): Product
    {
        return DB::transaction(fn () => Product::query()->create($data));
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            $product->update($data);

            return $product->refresh();
        });
    }

    public function delete(Product $product): void
    {
        DB::transaction(fn () => $product->delete());
    }
}
