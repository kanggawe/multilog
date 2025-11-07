@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">User Details</h1>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="mb-6">
                <div class="flex items-center mb-4">
                    <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center mr-4">
                        <span class="text-gray-600 font-semibold text-2xl">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <p class="text-gray-900">{{ $user->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <p class="text-gray-900">{{ $user->email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                        @if($user->role && $user->role->name === 'admin') bg-red-100 text-red-800
                        @elseif($user->role && $user->role->name === 'manager') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800
                        @endif">
                        {{ $user->role->name ?? 'No Role' }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Registered</label>
                    <p class="text-gray-900">{{ $user->created_at->format('F d, Y H:i') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                    <p class="text-gray-900">{{ $user->updated_at->format('F d, Y H:i') }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
                @if(auth()->user()->isAdmin())
                    <div class="space-x-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this user?')">
                                Delete
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
