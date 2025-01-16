<x-layout>
    <x-slot:heading>These are Your Stores !</x-slot:heading>
    <div class="space-y-4">
        @foreach ($stores as $store)
            <a href="/store/{{ $store['id'] }}" class="hover:underline block px-4 py-6 border border-gray-200 rounded-lg">

                <div class="font-bold text-blue-500 text-sm">{{ $store->storeName }}</div>

                <div>
                    <strong>Location {{ $store['location'] }}</strong>
                </div>
                <div>
                    About: {{ $store['about'] }}
                </div>
                <x-button onclick="deleteStore({{ $store['id'] }})"> Delete Store </x-button>
            </a>
        @endforeach

        {{-- <div>
            {{ $jobs->links() }}
        </div> --}}
    </div>
    <script>
          function deleteStore(storeId) {
            fetch(`/store/${storeId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Include CSRF token
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                alert('Store deleted successfully.');
                location.reload(); // Refresh the page or handle UI update
            } else {
                alert('Failed to delete store.');
            }
        })
        .catch(error => console.error('Error:', error));
    }
    </script>
</x-layout>
