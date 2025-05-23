<?php
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('Cart page can be rendered', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->get(route('cart.index'));
    // dd($response);
    $response->assertInertia(function (Assert $page) {
        $page->component('Emporium/Cart');
    });
    $response->assertStatus(200);
});

test('User can see cart items', function(){
    $user = User::factory()->create();
    $item = add_items_to_cart($user->id);
    $this->actingAs($user);
    $cartItems = $user->cart()->get();
    $response = $this->get(route('cart.index'));
    expect($cartItems[0]->id)->toEqual($item->id);
    $response->assertInertia(function (Assert $page) use ($cartItems)  {
        $page->component('Emporium/Cart')
            ->has('items', count($cartItems))
            ->where('items', $cartItems);
    });
});