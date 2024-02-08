<?php

use App\Models\Customer;
use App\Models\Device;
use App\Models\Preorder;

test('new user can preorder available device', function () {
    $customerData = Customer::factory()->make()->toArray();
    $device = Device::factory()->create(
        ['stock' => 10, 'pre_ordered_count' => 0]
    );
    $preorderData = [
        'customer_identity_number' => $customerData['id_number'],
        'customer_name' => $customerData['name'],
        'customer_phone_number' => $customerData['phone_number'],
        'device_id' => $device->id
    ];
    $response = $this->postJson('/api/preorder', $preorderData);

    $preorder = [
        'device_id' => $device->id,
        'customer_id' => Customer::where('id_number', $customerData['id_number'])->first()->id
    ];
    $response->assertStatus(201)
        ->assertJson($preorder);
    $this->assertDatabaseHas('preorders', $preorder);
    $this->assertDatabaseHas('customers', $customerData);
});

test('new user can not preorder out of stock device', function () {
    $customerData = Customer::factory()->make()->toArray();
    $device = Device::factory()->create(
        ['stock' => 10, 'pre_ordered_count' => 10]
    );
    $preorderCount = Preorder::count();
    $preorderData = [
        'customer_identity_number' => $customerData['id_number'],
        'customer_name' => $customerData['name'],
        'customer_phone_number' => $customerData['phone_number'],
        'device_id' => $device->id
    ];
    $response = $this->postJson('/api/preorder', $preorderData);

    $response->assertStatus(400);
    $this->assertDatabaseMissing('customers', $customerData);
    $this->assertDatabaseMissing('preorders', [
        'device_id' => $device->id,
        'customer_id' => null
    ]);
    $this->assertDatabaseCount('preorders', $preorderCount);
});
