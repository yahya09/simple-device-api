<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Models\Device;
use App\Services\DeviceRepositoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DeviceController extends Controller
{

    private DeviceRepositoryService $deviceRepositoryService;

    public function __construct(DeviceRepositoryService $deviceRepositoryService)
    {
        $this->deviceRepositoryService = $deviceRepositoryService;
        $this->authorizeResource(Device::class, 'device');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user == null || !$user->isAdministrator()) {
            return $this->deviceRepositoryService->getAvailableDevices();
        }

        return $this->deviceRepositoryService->getAllDevices();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeviceRequest $request)
    {
        try {
            $newDevice = $this->deviceRepositoryService->insertNewDevice($request->validated());

            return Response::json($newDevice, 201);
        } catch (\Exception $e) {
            return Response::make(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        return $device;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeviceRequest $request, Device $device)
    {
        try {
            $validated = $request->validated();
            if (empty($validated)) {
                return Response::json($device);
            }

            $updatedDevice = $this->deviceRepositoryService->updateDevice($device, $validated);

            return Response::json($updatedDevice);
        } catch (\Exception $e) {
            return Response::make(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        try {
            $this->deviceRepositoryService->deleteDevice($device);

            return Response::noContent();
        } catch (\Exception $e) {
            return Response::make(['error' => $e->getMessage()], 400);
        }
    }
}
