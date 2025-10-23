<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Order</h3>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $order->customer_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Customer Phone</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $order->customer_phone }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Delivery Type</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $order->delivery_type }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $order->status }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total Price</label>
                                <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Order Date</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $order->order_date }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Order Items</h3>
                        <div class="mt-4">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clothing Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight (kg)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->serviceType->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->clothingType->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->weight }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('order.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
