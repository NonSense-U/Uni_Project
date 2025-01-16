<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
        <h1 class="text-2xl font-bold mb-6 text-gray-700">Create User</h1>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <!-- Username -->
            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
                <input type="text" id="username" name="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 @error('username') border-red-500 @enderror" value="{{ old('username') }}" required>
                @error('username')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Phone Number -->
            <div class="mb-4">
                <label for="phoneNumber" class="block text-gray-700 font-medium mb-2">Phone Number</label>
                <input type="text" id="phoneNumber" name="phoneNumber" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 @error('phoneNumber') border-red-500 @enderror" value="{{ old('phoneNumber') }}">
                @error('phoneNumber')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 @error('email') border-red-500 @enderror" value="{{ old('email') }}">
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label for="role" class="block text-gray-700 font-medium mb-2">Role</label>
                <select id="role" name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 @error('role') border-red-500 @enderror">
                    <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="store_owner" {{ old('role') === 'store_owner' ? 'selected' : '' }}>Store Owner</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 @error('password') border-red-500 @enderror" required>
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 @error('password_confirmation') border-red-500 @enderror" required>
                @error('password_confirmation')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Additional Fields for Customer -->
            <div id="customerFields" class="hidden">
                <div class="mb-4">
                    <label for="firstName" class="block text-gray-700 font-medium mb-2">First Name</label>
                    <input type="text" id="firstName" name="firstName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 @error('firstName') border-red-500 @enderror" value="{{ old('firstName') }}">
                    @error('firstName')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="lastName" class="block text-gray-700 font-medium mb-2">Last Name</label>
                    <input type="text" id="lastName" name="lastName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 @error('lastName') border-red-500 @enderror" value="{{ old('lastName') }}">
                    @error('lastName')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:ring focus:ring-blue-300">Create User</button>
        </form>
    </div>

    <script>
        const roleSelect = document.getElementById('role');
        const customerFields = document.getElementById('customerFields');

        roleSelect.addEventListener('change', () => {
            if (roleSelect.value === 'customer') {
                customerFields.classList.remove('hidden');
            } else {
                customerFields.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
