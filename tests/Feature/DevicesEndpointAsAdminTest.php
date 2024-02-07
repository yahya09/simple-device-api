<?php

use App\Models\Device;
use App\Models\User;
use App\Models\UserRole;

test('can get all device list', function () {
    $adminUser = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $response = $this->actingAs($adminUser)->get('/api/devices');

    $response->assertStatus(200);

    //TODO: test response includes device with zero stock
});

test('can insert new device information', function () {
    $adminUser = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $device = Device::factory()->make()->toArray();
    $response = $this->actingAs($adminUser)->post('/api/devices', $device);

    $response->assertStatus(201);
    $this->assertDatabaseHas('devices', $device);
});

test('can update device information except stock', function () {
    $adminUser = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $device = Device::factory()->create();
    $newDeviceData = \Illuminate\Support\Arr::except(Device::factory()->make()->toArray(), ['stock']);
    $response = $this->actingAs($adminUser)->put("/api/devices/{$device->id}", $newDeviceData);

    $response->assertStatus(200);
    $this->assertDatabaseHas('devices', $newDeviceData);
    $this->assertDatabaseMissing('devices', $device->toArray());
});

test('can update device information stock only', function () {
    $adminUser = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $device = Device::factory()->create();
    $newDeviceData = [
        'name' => $device->name,
        'color' => $device->color,
        'price' => $device->price,
        'stock' => max($device->stock - 10, 1)
    ];
    $response = $this->actingAs($adminUser)->put("/api/devices/{$device->id}", ['stock' => $newDeviceData['stock']]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('devices', $newDeviceData);
    $this->assertDatabaseMissing('devices', $device->toArray());
});

test('can delete not pre-ordered device', function () {
    $adminUser = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $device = Device::factory()->create();
    $response = $this->actingAs($adminUser)->delete("/api/devices/{$device->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('devices', $device->toArray());
});

test('can not delete pre-ordered device', function () {
    $adminUser = User::factory()->create([
        'role' => UserRole::Admin,
    ]);
    $device = Device::factory()->create([
        'pre_ordered_count' => 1,
    ]);
    $response = $this->actingAs($adminUser)->delete("/api/devices/{$device->id}");

    $response->assertStatus(400);
    $this->assertDatabaseHas('devices', Arr::except($device->toArray(), ['created_at', 'updated_at']));
});
