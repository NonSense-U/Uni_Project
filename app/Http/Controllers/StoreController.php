<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use Google\Service\Drive\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stores = Store::all();
        return response()->json($stores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'storeName' => ['string','max:255'],
            'location' =>['string','max:255'],
            'stars' => ['integer','nullable'],
            'file' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
            'about' => 'nullable|string'
        ]);

        $store = Store::create([
            'store_owner_id' => $request->user()->StoreOwner->id,
            'storeName' => $request->storeName,
            'stars' => $request->stars,
            'location' => $request->location,
            'about' => $request->about
        ]);

        if($request->file!=null)
        {
            $file = $request->file('file');
            $fileName = $store->id;

            $googleDrivePath = 'uploads/' .$fileName ;

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

                    $store->storePicture = $shareableLink;
                    $store->save();
                } else {
                    return response()->json(['error' => 'File not found after upload.'], 404);
                }
            } else {
                return response()->json(['error' => 'Failed to upload file.'], 500);
            }
        }

        if($request->ui) return redirect()->route('dashboard.home')->with('success', 'Operation successful!');

        return response()->json($store);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $store = Store::find($request->id);
        if(!$store)
        {
            return response()->json(["message" => "There is no store with an id of {$request->id} !"],404);
        }
        return response()->json($store);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreRequest $request, Store $store)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $store = Store::find($request->id);

        if(!$store)
        {
            return response()->json(["message" => "There is no store with an id of {$request->id} !"],404);
        }

        if($request->user()->storeOwner->id != $store->store_owner_id)
        {
            return response()->json(["message" => "SHOO! You are not the Owner of this Store!"]);
        }
        $store->delete();

        return response()->json(["message" => "Your store has been successfully deleted !"],);
    }

}
