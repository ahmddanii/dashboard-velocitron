@extends('layouts.app')

@section('title', 'Pending Requests')

@section('content')

    <div class="p-6">

        <div class="max-w-[1440px] mx-auto">

            <div class="flex justify-between items-center mb-8">

                <div>

                    <h2 class="font-display-lg text-display-lg">
                        Pending Requests
                    </h2>

                    <p class="text-on-surface-variant mt-1">

                        Review transaksi sebelum diproses DSS.

                    </p>

                </div>

            </div>

            <x-ui.card class="overflow-hidden">

                <div class="dashboard-card-header">

                    <h3 class="dashboard-title">
                        Request Queue
                    </h3>

                </div>

                <div class="dashboard-card-body overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead>

                            <tr class="border-b border-outline-variant">

                                <th class="text-left py-3">
                                    Request
                                </th>

                                <th class="text-left py-3">
                                    Requester
                                </th>

                                <th class="text-left py-3">
                                    Type
                                </th>

                                <th class="text-left py-3">
                                    Sales
                                </th>

                                <th class="text-left py-3">
                                    Status
                                </th>

                                <th class="text-right py-3">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($requests as $request)

                                <tr class="border-b border-outline-variant">

                                    <td class="py-4">

                                        <div>

                                            <p class="font-semibold">
                                                {{ $request->title }}
                                            </p>

                                            <p class="text-xs text-on-surface-variant mt-1">

                                                {{ $request->description }}

                                            </p>

                                        </div>

                                    </td>

                                    <td class="py-4">

                                        {{ $request->requester->name }}

                                    </td>

                                    <td class="py-4 capitalize">

                                        {{ $request->request_type }}

                                    </td>

                                    <td class="py-4">

                                        ${{ number_format($request->sales, 0) }}

                                    </td>

                                    <td class="py-4">

                                        <span class="px-2 py-1 rounded-full text-xs font-bold
                                                        bg-amber-100 text-amber-700">

                                            Pending

                                        </span>

                                    </td>

                                    <td class="py-4 text-right">

                                        <a href="{{ route('requests.review', $request->id) }}" class="inline-flex items-center gap-2
                                                        px-3 py-2 rounded-lg
                                                        bg-secondary text-white
                                                        text-xs font-semibold">

                                            <span class="material-symbols-outlined text-sm">
                                                psychology
                                            </span>

                                            Review DSS

                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="py-10 text-center text-slate-400">

                                        Tidak ada pending request.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </x-ui.card>

        </div>

    </div>

@endsection