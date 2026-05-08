@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')


    <div class="p-6">

        <div class="max-w-[1440px] mx-auto">

            <script type="application/json" id="dashboard-context">

                @json($dashboardData)
                
            </script>

            {{-- Error: Flask tidak jalan --}}
            @if(isset($apiError))

                @include('dashboard.partials.api-error')

            @else

                @include('dashboard.partials.dashboard-header')

                {{-- KPI CARDS --}}
                @include('dashboard.partials.kpi-cards')

                @includeIf("dashboard.roles.$role")

            @endif

        </div>

    </div>

@endsection