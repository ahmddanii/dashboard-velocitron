@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="p-6">

        <div class="max-w-[1440px] mx-auto">

            {{-- Error: Flask tidak jalan --}}
            @if(isset($apiError))

                @include('dashboard.partials.api-error')

            @else
                <script type="application/json" id="dashboard-context">
                    @json($dashboardData)
                </script>

                @include('dashboard.partials.dashboard-header')

                {{-- FILTER BAR --}}
                <form method="GET" class="flex flex-wrap gap-3 mb-6">

                    {{-- Status Filter --}}
                    <x-ui.filter-select name="status" :selected="request('status')" :options="[

                    '' => 'All Status',

                    'approved' => 'Approved',

                    'rejected' => 'Rejected',
                ]" />

                    {{-- Period Filter --}}
                    <x-ui.filter-select name="period" :selected="request('period')" :options="[

                    '' => 'All Time',

                    '7days' => 'Last 7 Days',

                    '30days' => 'Last 30 Days',

                    'year' => 'This Year',
                ]" />

                </form>

                {{-- KPI CARDS --}}
                @include('dashboard.partials.kpi-cards')

                @includeIf("dashboard.roles.$role")

            @endif

        </div>

    </div>

@endsection