<?php

use App\Models\Device;
use App\Models\User;
use App\Models\UserRole;

test('get available device list', function () {
    $availableDevice = Device::factory()->create([
        'stock' => 10,
        'pre_ordered_count' => 5,
    ]);
    $unavailableDevice = Device::factory()->create([
        'stock' => 10,
        'pre_ordered_count' => 10,
    ]);

    $response = $this->getJson('/api/devices');

    $response->assertStatus(200)
        ->assertJson([['id' => $availableDevice->id]])
        ->assertJsonMissing([['id' => $unavailableDevice->id]]);
});

test('get device detail', function () {
    $device = Device::factory()->create();
    $response = $this->getJson("/api/devices/{$device->id}");

    $response->assertStatus(200)
        ->assertJson($device->toArray());
});

test('can not get out of stock device detail', function () {
    $device = Device::factory()->create([
        'stock' => 10,
        'pre_ordered_count' => 10,
    ]);
    $response = $this->getJson("/api/devices/{$device->id}");

    $response->assertStatus(404)
        ->assertJsonStructure(['error']);
});

test('can not see device pre-ordered customer list', function () {
    $device = Device::factory()->create();
    $response = $this->getJson("/api/devices/{$device->id}/po-customers");

    $response->assertStatus(401);
});

test('failed to insert new device information as guest', function () {
    $device = Device::factory()->make()->toArray();
    $response = $this->postJson('/api/devices', $device);

    $response->assertStatus(401);
    $this->assertDatabaseMissing('devices', $device);
});

test('failed to update device information as guest', function () {
    $device = Device::factory()->create();
    $newDeviceData = [
        'name' => $device->name,
        'color' => $device->color,
        'price' => $device->price,
        'stock' => $device->stock + 10
    ];
    $response = $this->putJson("/api/devices/{$device->id}", ['stock' => $newDeviceData['stock']]);

    $response->assertStatus(401);
    $this->assertDatabaseMissing('devices', $newDeviceData);
    $this->assertDatabaseHas('devices', $device->toArray());
});

test('failed to delete device as guest', function () {
    $device = Device::factory()->create();
    $response = $this->deleteJson("/api/devices/{$device->id}");

    $response->assertStatus(401);
    $this->assertDatabaseHas('devices', $device->toArray());
});
