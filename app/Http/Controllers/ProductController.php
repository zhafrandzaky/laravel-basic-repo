<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
    ) {
    }

    public function index(Request $request): View
    {
        $editableProduct = null;

        if ($request->filled('edit')) {
            $editableProduct = Product::query()->findOrFail((int) $request->query('edit'));
        }

        return view('products.index', [
            'products' => Product::query()->latest()->get(),
            'editableProduct' => $editableProduct,
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        try {
            $this->productService->create($request->validated());

            return redirect()
                ->route('products.index')
                ->with('success', 'Produk berhasil ditambahkan.');
        } catch (Throwable $throwable) {
            Log::error('Gagal menambahkan produk.', [
                'message' => $throwable->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Produk gagal ditambahkan. Silakan coba lagi.');
        }
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        try {
            $this->productService->update($product, $request->validated());

            return redirect()
                ->route('products.index')
                ->with('success', 'Produk berhasil diperbarui.');
        } catch (Throwable $throwable) {
            Log::error('Gagal memperbarui produk.', [
                'product_id' => $product->id,
                'message' => $throwable->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Produk gagal diperbarui. Silakan coba lagi.');
        }
    }

    public function destroy(Product $product): RedirectResponse
    {
        try {
            $this->productService->delete($product);

            return redirect()
                ->route('products.index')
                ->with('success', 'Produk berhasil dihapus.');
        } catch (Throwable $throwable) {
            Log::error('Gagal menghapus produk.', [
                'product_id' => $product->id,
                'message' => $throwable->getMessage(),
            ]);

            return redirect()
                ->route('products.index')
                ->with('error', 'Produk gagal dihapus. Silakan coba lagi.');
        }
    }
}
