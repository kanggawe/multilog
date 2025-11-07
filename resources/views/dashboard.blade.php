@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Dashboard</h1>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-xl font-semibold mb-4">Welcome, {{ $user->name }}!</h2>
                
                <div class="mb-4">
                    <p class="text-gray-600"><strong>Email:</strong> {{ $user->email }}</p>
                    <p class="text-gray-600"><strong>Role:</strong> 
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                            {{ $user->role->name ?? 'No Role' }}
                        </span>
                    </p>
                </div>

                <div class="mt-6">
                    @if($user->isAdmin())
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                            <div class="flex">
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">
                                        <strong>Admin Access:</strong> You have full administrative privileges.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif($user->isManager())
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                            <div class="flex">
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Manager Access:</strong> You have limited administrative privileges.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                            <div class="flex">
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        <strong>User Access:</strong> You have basic access to the system.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold mb-2">Quick Actions</h3>
                        <ul class="list-disc list-inside text-sm text-gray-600">
                            @if($user->isAdmin() || $user->isManager())
                                <li><a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">Manage Users</a></li>
                            @endif
                            <li><a href="{{ route('account.profile') }}" class="text-blue-600 hover:underline">View Profile</a></li>
                            <li><a href="{{ route('account.settings') }}" class="text-blue-600 hover:underline">Settings</a></li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold mb-2">Statistics</h3>
                        <ul class="text-sm text-gray-600">
                            <li>Total Users: {{ \App\Models\User::count() }}</li>
                            <li>Your Role: {{ $user->role->name ?? 'N/A' }}</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold mb-2">Account Info</h3>
                        <ul class="text-sm text-gray-600">
                            <li>Registered: {{ $user->created_at->format('M d, Y') }}</li>
                            <li>Last Login: Recently</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
