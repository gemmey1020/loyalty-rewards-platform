@extends('layouts.app')

@section('title', 'Point Deduction History')

@section('content')
    <div class="no-bottom no-top zebra" id="content">
        <div id="top"></div>

        <!-- Subheader Section -->
        <section id="subheader" class="jarallax text-light">
            <img src="{{ asset('template/images/background/14.jpg') }}" class="jarallax-img" alt="">
            <div class="center-y relative text-center">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1>{{ __('dashboard.deduction_history.title') }} - {{ $user->name }}</h1>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content Section -->
        <section id="section-history" class="bg-gray-100">
            <div class="container">
                <div class="card p-4 rounded-5">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('dashboard.deduction_history.date') }}</th>
                                    <th>{{ __('dashboard.deduction_history.invoice_number') }}</th>
                                    <th>{{ __('dashboard.deduction_history.points') }}</th>
                                    <th>{{ __('dashboard.deduction_history.reason') }}</th>
                                    <th>{{ __('dashboard.deduction_history.deducted_by') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deductions as $deduction)
                                    <tr>
                                        <td>{{ $deduction->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ optional($deduction->invoice)->invoice_number ?? 'N/A' }}</td>
                                        <td>{{ $deduction->points_deducted }}</td>
                                        <td>{{ $deduction->reason }}</td>
                                        <td>{{ optional($deduction->deductedBy)->name ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            {{ __('dashboard.deduction_history.no_records') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admins.users.show', $user->id) }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> {{ __('dashboard.deduction_history.back') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
