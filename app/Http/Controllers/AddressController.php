<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Services\AddressService;

class AddressController extends Controller
{
    protected $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function index(): mixed
    {
        $user = auth()->user();
        $addresses = $user->addresses;

        return response()->json([
            'html' => view('partials.addresses', compact('addresses'))->render(),
        ]);
    }

    public function store(StoreAddressRequest $request)
    {
        $userAddress = $request->validated();

        if (auth()->user()->addresses()->count() == 3) {
            return response()->json(['message' => 'You can only have 3 addresses'], 400);
        }

        $this->addressService->createAddress($userAddress);

        return response()->json(['message' => 'Address added successfully']);
    }

    public function update(StoreAddressRequest $request, $id)
    {
        $address = auth()->user()->addresses()->findOrFail($id);

        $validated = $request->validated();

        $address->update($validated);

        return response()->json(['message' => 'Address updated successfully']);
    }

    public function destroy($id)
    {
        $address = auth()->user()->addresses()->findOrFail($id);

        if ($address->primary == 1) {
            return response()->json(['message' => 'Primary address cannot be deleted'], 400);
        }

        $address->delete();

        return response()->json(['message' => 'Address deleted successfully']);
    }

    public function makePrimary($id)
    {
        try {
            $address = auth()->user()->addresses()->findOrFail($id);

            auth()->user()->addresses()->update(['primary' => null]);

            $address->update(['primary' => true]);

            return response()->json(['message' => 'Address set as primary successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to set primary address'], 500);
        }
    }
}
