<x-layout :request="$request">
    <x-slot:heading>
        Hi {{ $request->user()->username }}
    </x-slot:heading>
    Welcome Home
</x-layout>
