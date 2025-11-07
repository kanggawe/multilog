@extends('layouts.app')

@section('title', 'Dashboard - ' . config('app.name', 'Laravel'))

@section('page_title', 'Dashboard')
@section('page_subtitle', "Welcome back! Here's what's happening with your business today.")

@php
    $page = 'dashboard';
    $showBreadcrumb = false;
@endphp

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12 space-y-6 xl:col-span-7">
        <!-- Metric Group One -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6">
            <!-- Metric Item Start -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-500/20">
                    <i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>

                <div class="mt-5 flex items-end justify-between">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Users</span>
                        <h4 class="mt-2 text-3xl font-bold text-gray-800 dark:text-white/90">
                            2,340
                        </h4>
                    </div>

                    <span class="flex items-center gap-1 rounded-full bg-green-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-green-600 dark:bg-green-500/15 dark:text-green-500">
                        <i class="fas fa-arrow-up text-xs"></i>
                        11.01%
                    </span>
                </div>
            </div>
            <!-- Metric Item End -->

            <!-- Metric Item Start -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 dark:bg-green-500/20">
                    <i class="fas fa-shopping-cart text-green-600 dark:text-green-400 text-xl"></i>
                </div>

                <div class="mt-5 flex items-end justify-between">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Orders</span>
                        <h4 class="mt-2 text-3xl font-bold text-gray-800 dark:text-white/90">
                            5,359
                        </h4>
                    </div>

                    <span class="flex items-center gap-1 rounded-full bg-red-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-red-600 dark:bg-red-500/15 dark:text-red-500">
                        <i class="fas fa-arrow-down text-xs"></i>
                        9.05%
                    </span>
                </div>
            </div>
            <!-- Metric Item End -->
        </div>

        <!-- ====== Chart One Start -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Revenue Overview</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Last 7 days performance</p>
                </div>
                <button class="text-sm text-blue-600 hover:text-blue-700 font-medium dark:text-blue-400">View Report</button>
            </div>
            
            <div class="flex items-end justify-between h-64 space-x-2">
                <div class="flex-1 flex flex-col items-center">
                    <div class="chart-bar w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-lg" style="height: 45%;"></div>
                    <span class="mt-2 text-xs text-gray-500 dark:text-gray-400">Mon</span>
                </div>
                <div class="flex-1 flex flex-col items-center">
                    <div class="chart-bar w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-lg" style="height: 65%;"></div>
                    <span class="mt-2 text-xs text-gray-500 dark:text-gray-400">Tue</span>
                </div>
                <div class="flex-1 flex flex-col items-center">
                    <div class="chart-bar w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-lg" style="height: 35%;"></div>
                    <span class="mt-2 text-xs text-gray-500 dark:text-gray-400">Wed</span>
                </div>
                <div class="flex-1 flex flex-col items-center">
                    <div class="chart-bar w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-lg" style="height: 80%;"></div>
                    <span class="mt-2 text-xs text-gray-500 dark:text-gray-400">Thu</span>
                </div>
                <div class="flex-1 flex flex-col items-center">
                    <div class="chart-bar w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-lg" style="height: 55%;"></div>
                    <span class="mt-2 text-xs text-gray-500 dark:text-gray-400">Fri</span>
                </div>
                <div class="flex-1 flex flex-col items-center">
                    <div class="chart-bar w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-lg" style="height: 75%;"></div>
                    <span class="mt-2 text-xs text-gray-500 dark:text-gray-400">Sat</span>
                </div>
                <div class="flex-1 flex flex-col items-center">
                    <div class="chart-bar w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-lg" style="height: 90%;"></div>
                    <span class="mt-2 text-xs text-gray-500 dark:text-gray-400">Sun</span>
                </div>
            </div>
        </div>
        <!-- ====== Chart One End -->
    </div>
    
    <div class="col-span-12 xl:col-span-5">
        <!-- ====== Chart Two Start -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Sales Overview</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Last 30 days</p>
                </div>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 3 months</option>
                </select>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Total Sales</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">$125,430</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: 75%;"></div>
                </div>
                
                <div class="flex items-center justify-between mt-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Target</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">$150,000</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                    <div class="bg-purple-500 h-2 rounded-full" style="width: 60%;"></div>
                </div>
                
                <div class="flex items-center justify-between mt-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Growth</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">+12.5%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                    <div class="bg-green-500 h-2 rounded-full" style="width: 85%;"></div>
                </div>
            </div>
        </div>
        <!-- ====== Chart Two End -->
    </div>

    <div class="col-span-12">
        <!-- ====== Table One Start -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 md:px-6 md:py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Recent Orders</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Latest customer orders</p>
                    </div>
                    <button class="text-sm text-blue-600 hover:text-blue-700 font-medium dark:text-blue-400">View All</button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Order ID</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Customer</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Product</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Amount</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-white/[0.03] dark:divide-gray-800">
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#1234</td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 dark:bg-blue-500/20">
                                        <span class="text-blue-600 text-xs font-semibold dark:text-blue-400">JD</span>
                                    </div>
                                    <span class="text-sm text-gray-900 dark:text-white">John Doe</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">iPhone 13 Pro</td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">$999</td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-400">Completed</span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">2024-01-15</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#1235</td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3 dark:bg-purple-500/20">
                                        <span class="text-purple-600 text-xs font-semibold dark:text-purple-400">JS</span>
                                    </div>
                                    <span class="text-sm text-gray-900 dark:text-white">Jane Smith</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">MacBook Pro</td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">$2,499</td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-500/20 dark:text-yellow-400">Pending</span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">2024-01-14</td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#1236</td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 dark:bg-green-500/20">
                                        <span class="text-green-600 text-xs font-semibold dark:text-green-400">MJ</span>
                                    </div>
                                    <span class="text-sm text-gray-900 dark:text-white">Mike Johnson</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">AirPods Pro</td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">$249</td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-400">Processing</span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">2024-01-13</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- ====== Table One End -->
    </div>
</div>
@endsection

@push('styles')
<style>
    .chart-bar {
        transition: all 0.3s ease;
    }
    
    .chart-bar:hover {
        opacity: 0.8;
        transform: translateY(-2px);
    }
</style>
@endpush
