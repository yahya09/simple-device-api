<?php

use App\Models\Customer;
use App\Models\Device;
use App\Models\Preorder;

test('can get all device list', function () {
    $device = Device::factory()->create([
        'stock' => 10,
        'pre_ordered_count' => 10,
    ]);
    $response = $this->actingAs(getAdminUser())->getJson('/api/devices');

    $response->assertStatus(200)->assertJson([['id' => $device->id]]);
});

test('can get device detail', function () {
    $device = Device::factory()->create();
    $response = $this->actingAs(getAdminUser())->getJson("/api/devices/{$device->id}");

    $response->assertStatus(200)->assertJson($device->toArray());
});

test('can get device pre-ordered customer list', function () {
    $device = Device::factory()->create(['stock' => 10]);
    $customers = Customer::factory()->createMany(10);

    Preorder::query()->insert($customers->map(function ($customer) use ($device) {
        return [
            'device_id' => $device->id,
            'customer_id' => $customer->id,
        ];
    })->toArray());

    $response = $this->actingAs(getAdminUser())->getJson("/api/devices/{$device->id}/po-customers");

    $response->assertStatus(200)->assertJson($customers->toArray());

});

test('can insert new device information', function () {
    $device = Device::factory()->make()->toArray();
    $response = $this->actingAs(getAdminUser())->postJson('/api/devices', $device);

    $response->assertStatus(201);
    $this->assertDatabaseHas('devices', $device);
});

test('can update device information partially', function () {
    $device = Device::factory()->create();
    $newDeviceData = Arr::except(Device::factory()->make()->toArray(), ['stock']);
    $response = $this->actingAs(getAdminUser())->putJson("/api/devices/{$device->id}", $newDeviceData);

    $response->assertStatus(200);
    $this->assertDatabaseHas('devices', $newDeviceData);
    $this->assertDatabaseMissing('devices', $device->toArray());
});

test('can update device information stock only', function () {
    $device = Device::factory()->create();
    $newDeviceData = [
        'name' => $device->name,
        'color' => $device->color,
        'price' => $device->price,
        'stock' => max($device->stock - 10, 1)
    ];
    $response = $this->actingAs(getAdminUser())->putJson("/api/devices/{$device->id}", ['stock' => $newDeviceData['stock']]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('devices', $newDeviceData);
    $this->assertDatabaseMissing('devices', $device->toArray());
});

test('can not update device stock lower than preorder count', function () {
    $device = Device::factory()->create(['stock' => 20, 'pre_ordered_count' => 10]);
    $newDeviceData = [
        'name' => $device->name,
        'color' => $device->color,
        'price' => $device->price,
        'stock' => 5
    ];
    $response = $this->actingAs(getAdminUser())->put("/api/devices/{$device->id}", ['stock' => $newDeviceData['stock']]);

    $response->assertStatus(400);
    $this->assertDatabaseMissing('devices', $newDeviceData);
    $this->assertDatabaseHas('devices', Arr::except($device->toArray(), ['created_at', 'updated_at']));
});

test('can delete not pre-ordered device', function () {
    $device = Device::factory()->create();
    $deviceData = $device->toArray();
    $response = $this->actingAs(getAdminUser())->deleteJson("/api/devices/{$device->id}");

    $response->assertStatus(204);
    $this->assertSoftDeleted('devices', $deviceData);
});

test('can not delete pre-ordered device', function () {
    $device = Device::factory()->create([
        'pre_ordered_count' => 1,
    ]);
    $response = $this->actingAs(getAdminUser())->delete("/api/devices/{$device->id}");

    $response->assertStatus(400);
    $this->assertNotSoftDeleted('devices', $device->toArray());
});
