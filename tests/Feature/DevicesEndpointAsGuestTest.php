<?php

use App\Models\Device;
use App\Models\User;
use App\Models\UserRole;

test('get available device list', function () {
    $response = $this->get('/api/devices');

    $response->assertStatus(200);
    //TODO: test response only includes device with available stock and exclude stock field
});

test('failed to insert new device information as guest', function () {
    $device = Device::factory()->make()->toArray();
    $response = $this->post('/api/devices', $device, [
        'Accept' => 'application/json',
    ]);

    $response->assertStatus(401);
    $this->assertDatabaseMissing('devices', $device);
});
