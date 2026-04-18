<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_page_can_be_rendered(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertOk();
        $response->assertSee('Manajemen Produk');
    }

    public function test_product_can_be_created(): void
    {
        $response = $this->post(route('products.store'), [
            'name' => 'Keyboard Mechanical',
            'description' => 'Keyboard dengan switch tactile.',
            'price' => 750000,
            'stock' => 12,
        ]);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Keyboard Mechanical',
            'stock' => 12,
        ]);
    }

    public function test_product_store_validation_is_applied(): void
    {
        $response = $this->from(route('products.index'))->post(route('products.store'), [
            'name' => '',
            'description' => 'Invalid data',
            'price' => -10,
            'stock' => -1,
        ]);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHasErrors(['name', 'price', 'stock']);
    }

    public function test_product_can_be_updated(): void
    {
        $product = Product::query()->create([
            'name' => 'Monitor Lama',
            'description' => 'Versi lama',
            'price' => 1250000,
            'stock' => 5,
        ]);

        $response = $this->from(route('products.index', ['edit' => $product->id]))
            ->put(route('products.update', $product), [
                'name' => 'Monitor Baru',
                'description' => 'Versi baru',
                'price' => 1500000,
                'stock' => 8,
            ]);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Monitor Baru',
            'stock' => 8,
        ]);
    }

    public function test_product_can_be_deleted(): void
    {
        $product = Product::query()->create([
            'name' => 'Headset',
            'description' => 'Headset gaming',
            'price' => 450000,
            'stock' => 4,
        ]);

        $response = $this->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}
