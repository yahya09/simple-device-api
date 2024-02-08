<?php

namespace App\Services;

use App\Models\Device;
use Illuminate\Support\Facades\DB;

class DeviceRepositoryService
{
    private Device $deviceModel;

    public function __construct(Device $deviceModel)
    {
        $this->deviceModel = $deviceModel;
    }

    public function getAvailableDevices()
    {
        return $this->deviceModel->where(DB::raw('stock - pre_ordered_count'), '>', 0)->get();
    }

    public function getAllDevices()
    {
        return $this->deviceModel->all();
    }

    public function insertNewDevice(mixed $validated)
    {
        return $this->deviceModel->create($validated);
    }

    public function updateDevice(Device $device, mixed $validated)
    {
        $this->validateUpdate($validated, $device);

        $device->update($validated);

        return $device;
    }

    public function deleteDevice(Device $device)
    {
        $this->validateDelete($device);

        return $device->delete();
    }

    /**
     * @param mixed $validated
     * @param Device $device
     * @return mixed
     * @throws \Exception
     */
    private function validateUpdate(mixed $validated, Device $device)
    {
        if (isset($validated['stock']) && $validated['stock'] < $device->pre_ordered_count) {
            throw new \Exception('Stock cannot be lower than pre-ordered count!');
        }
    }

    private function validateDelete(Device $device)
    {
        if ($device->have_been_preordered) {
            throw new \Exception('Cannot delete device with pre-orders!');
        }
    }

    public function getPreorderCustomers(Device $device)
    {
        $device->load('preorders.customer');

        return $device->preorders->pluck('customer');
    }
}
