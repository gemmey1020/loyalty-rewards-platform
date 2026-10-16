@extends('layouts.app')

@section('title', __('dashboard.panel'))

@section('content')
    <!-- content begin -->
    <div class="no-bottom no-top zebra" id="content">
        <div id="top"></div>

        <!-- section begin -->
        <section id="subheader" class="text-light">
            <div class="subheader-overlay"></div>

            <div class="subheader-content center-y relative text-center">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center d-flex justify-content-center hover-underline responsive-heading">
                            {{ __('dashboard.dashboard_images') }}
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </section>
        <!-- section close -->


        <!-- section begin -->
        <section id="section-cars" class="bg-gray-100">
            <div class="container">
                <div class="row">
                    @include('admin.partials.sidebar')

                    <div class="col-lg-9">


                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- display the error message --}}
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- display the validation errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        @endif

                            <div class="card p-4 rounded-5 mb-4">
                                <h4 id="table-title" class="mb-3">{{ $type }} Images</h4>

                                <div class="table-responsive">
                                    <table id="bannerImagesTable" class="table table-striped w-100">
                                        <thead>
                                        <tr>
                                            <th>{{ __('dashboard.table.id') }}</th>
                                            <th>{{ __('dashboard.table.name') }}</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($bannerImages as $image)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $image->name }}</td>
                                                <td>
                                                    <img src="{{ asset('banner/' . $image->image_type . '/' . $image->image) }}"
                                                         alt="{{ __('dashboard.alt.banner_image') }}" width="240" height="80" class="rounded">
                                                </td>
                                                <td>

                                                    <div class="modal-footer">
                                                        @if( $image->default == 0)
                                                            <a href="{{ route('system-setup.make-default', $image->id) }}" class="btn btn-primary btn-sm">
                                                                {{ __('dashboard.button.set_background') }}
                                                            </a>
                                                        @endif
                                                        <form action="{{ route('system-setup.destroy', $image->id) }}" method="POST">
                                                            @csrf
                                                            @method('GET')
                                                            <button type="submit" class="btn btn-danger">{{ __('dashboard.button.delete') }}</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>


                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- content close -->

@endsection
