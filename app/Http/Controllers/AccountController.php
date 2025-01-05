<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\AuthController;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Requests\UpdateStoreOwnerRequest;
use App\Models\Customer;
use App\Models\StoreOwner;
use App\Models\User;
use Google\Service\Drive\Permission;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'phoneNumber' => ['required_without:email', 'string', 'unique:' . User::class], //! Must Update
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'phoneNumber' => ['required_without:email', 'string', 'unique:' . User::class], //! Must Update
            'email' => ['required_without:phoneNumber', 'string', 'email', 'max:255', 'unique:' . User::class],
            'role' => ['string', 'in:customer,store_owner,admin'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::create([
            'username' => $request->username,
            'phoneNumber' => $request->phoneNumber,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
        ]);


        if ($request->role === 'customer') {
            $user->assignRole('customer');
            $request->validate([
                'fristName' => ['nullable', 'string', 'max:255'],
                'lastName' => ['nullable', 'string', 'max:255'],
            ]);

            Customer::create([
                'user_id' => $user->id,
                'firstName' => $request->firstName,
                'lastName' =>  $request->lastName
            ]);
        }

        if ($request->role === 'store_owner') {

            $user->assignRole($request->role);

            StoreOwner::create([
                'user_id' => $user->id,
            ]);
        }


        event(new Registered($user));

        //! For Testing
        if ($request->auth) {
            $AuthController = new AuthController();
            return $AuthController->generateToken($request);
        }
        return response()->json(["message" => "user Created Successfully!"]);
    }



    public function update(Request $request)
    {

        $validatedData = $request->validate([
            'username' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $request->user()->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = $request->user();


        if ($user->hasRole('customer')) {
            $validatedRequest = UpdateCustomerRequest::createFromBase($request);

            $customerController = new CustomerController();
            $reponse1 = $customerController->update($validatedRequest, $user->customer);
        }
        else if($user->hasRole('store_owner'))
        {
            $validatedRequest = UpdateStoreOwnerRequest::createFromBase($request);

            $customerController = new StoreOwnerController();
            $reponse1 = $customerController->update($validatedRequest, $user->storeOwner);
        }

        $user->fill($validatedData);

        // Hash the password if provided
        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }


        $user->save();



        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user,
            'data' => $reponse1
        ]);
    }

    public function destroy(Request $request)
    {
        $user = User::find($request->user()->id);
        if(!$user)
        {
            return response()->json(["message" => "This user does not exsist !",404]);
        }
        $user->delete();

        return response()->json(["message"=>"Your account has been successfully removed !"]);
    }

    public function upload_pic(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        $user = User::find(Auth::id());

        $file = $request->file('file');
        $fileName = $user->username;

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

                $shareableLink = "https://drive.google.com/file/d/{$fileId}/view?usp=sharing";

                $user->profile_pic = $shareableLink;
                $user->save();

                // Return the shareable link
                return response()->json(['link' => $shareableLink]);
            } else {
                return response()->json(['error' => 'File not found after upload.'], 404);
            }
        } else {
            return response()->json(['error' => 'Failed to upload file.'], 500);
        }
    }

    public function delete_pic(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->profile_pic) {

            // Get the file ID from the Google Drive URL
            $fileId = explode('/', $user->profile_pic);
            $fileId = $fileId[5];
            // dd($fileId);

            $client = new \Google\Client();
            $client->setClientId(config('filesystems.disks.google.clientId'));
            $client->setClientSecret(config('filesystems.disks.google.clientSecret'));
            $client->refreshToken(config('filesystems.disks.google.refreshToken'));

            $service = new \Google\Service\Drive($client);

            // Delete the file
            $service->files->delete($fileId);

            $user->profile_pic = null;
            $user->save();

            return response()->json(['message' => 'Profile picture deleted successfully']);
        } else {
            return response()->json(['error' => 'No profile picture found'], 404);
        }
    }
}
