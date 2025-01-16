<?php

namespace App\Http\Controllers;

use App\Models\StoreInventory;
use App\Http\Requests\StoreStoreInventoryRequest;
use App\Http\Requests\UpdateStoreInventoryRequest;
use App\Models\Product;
use App\Models\Store;
use App\Notifications\InventoryNotification;
use Google\Service\Drive\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Find the store and eager load inventories along with their products
        $store = Store::with('inventories.product')->find($request->store_id);

        if (!$store) {
            return response()->json(['error' => 'Store not found'], 404);
        }

        return response()->json($store->inventories, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $store = Store::find($request->store_id);

        if (!$store) {
            return response()->json(["message" => "there is no store with an id of {$request->store_id} !"], 404);
        }

        if ($request->user()->storeOwner->id != $store->store_owner_id) {
            return response()->json(["message" => "SHOO! You are not the Owner of this Store!"]);
        }

        $request->validate([
            'price' => ['integer'],
            'quantity' => ['integer'],
            'productName' => ['string', 'max:255']
        ]);

        $product  = Product::where('name', $request->productName)->first();

        if (!$product) {
            $product = Product::create([
                'product_name' => $request->productName
            ]);
        }

        $storeInventory = StoreInventory::create([
            'store_id' => $request->store_id,
            'product_id' => $product->id,
            'price' => $request->price,
            'quantity' => $request->quantity
        ]);


        if ($request->file != null) {
            $file = $request->file('file');
            $fileName = $product->product_name;

            $googleDrivePath = 'uploads/' . $fileName;

            $uploadSuccess = Storage::disk('google')->put($googleDrivePath, file_get_contents($file));

            if ($uploadSuccess) {

                $client = new \Google\Client();
                $client->setClientId(config('filesystems.disks.google.clientId'));
                $client->setClientSecret(config('filesystems.disks.google.clientSecret'));
                $client->refreshToken(config('filesystems.disks.google.refreshToken'));

                $service = new \Google\Service\Drive($client);

                // Search for the file by name to get its ID
                $response = $service->files->listFiles([
                    'q' => "name='{$fileName}' and trashed=false",
                    'fields' => 'files(id, name)',
                ]);

                if (count($response->files) > 0) {
                    $fileId = $response->files[0]->id;

                    $permission = new Permission();
                    $permission->setType('anyone');
                    $permission->setRole('reader');
                    $service->permissions->create($fileId, $permission);

                    $shareableLink = "https://drive.google.com/uc?id={$fileId}";

                    $storeInventory->productPicture = $shareableLink;
                    $storeInventory->save();
                } else {
                    return response()->json(['error' => 'File not found after upload.'], 404);
                }
            } else {
                return response()->json(['error' => 'Failed to upload file.'], 500);
            }
        }


        $storeInventory->load('product');

        return response()->json($storeInventory, 202);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreInventoryRequest $request, StoreInventory $storeInventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoreInventory $storeInventory)
    {
        //
    }

    public function check_stock(StoreInventory $storeInventory, string $action)
    {
        //! notification should be sent to the store owner
        $storeInventory->store->store_owner->user->notify(new InventoryNotification($action));
    }
}
