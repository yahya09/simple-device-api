<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Device;
use App\Models\Preorder;
use Illuminate\Support\Facades\DB;

class PreorderRepositoryService
{
    private Device $deviceModel;
    private Preorder $preorderModel;
    private Customer $customerModel;

    public function __construct(Device $deviceModel, Preorder $preorderModel, Customer $customerModel)
    {
        $this->deviceModel = $deviceModel;
        $this->preorderModel = $preorderModel;
        $this->customerModel = $customerModel;
    }

    public function createPreorder(mixed $preorderData)
    {
        $preorder = null;
        DB::transaction(function () use ($preorderData, &$preorder) {
            $device = $this->deviceModel->lockForUpdate()->find($preorderData['device_id']);

            $customer = $this->customerModel->updateOrCreate(
                ['id_number' => $preorderData['customer_identity_number']],
                ['name' => $preorderData['customer_name'], 'phone_number' => $preorderData['customer_phone_number']]
            );

            $this->validatePreorder($device, $customer);

            $preorder = $this->preorderModel->create([
                'device_id' => $device->id,
                'customer_id' => $customer->id
            ]);

            $device->pre_ordered_count += 1;
            $device->save();

        }, 3);


        return $preorder;
    }

    /**
     * @throws \Exception
     */
    private function validatePreorder(?Device $device, Customer $customer)
    {
        if (is_null($device)) {
            throw new \Exception('Device not found');
        }

        if ($device->available_stock <= 0) {
            throw new \Exception('Device is out of stock!');
        }

        $existingPreorder = $this->preorderModel
            ->where('device_id', $device->id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!is_null($existingPreorder)) {
            throw new \Exception("You have already pre-ordered this device!");
        }
    }
}
