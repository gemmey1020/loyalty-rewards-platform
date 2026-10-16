@extends('layouts.app')

@section('title', __('auth.forgot_password.title'))

@section('content')

    <div class="no-bottom no-top" id="content">
        <div id="top"></div>
        <section id="section-hero" aria-label="section" class="jarallax">
            @if ($image && $image->image)
                <img src="{{ asset('banner/login/' . $image->image) }}" class="jarallax-img" alt="">
            @else
                <img src="{{ asset('template/images/background/2.jpg') }}" class="jarallax-img" alt="">
            @endif
            <div class="v-center">
                <div class="container">
                    <div class="row align-items-center log">
                        <div class="col-lg-4 offset-lg-4">
                            <div class="padding40 rounded-3 shadow-soft" data-bgcolor="#ffffff">
                                <h4>{{ __('auth.forgot_password.title') }}</h4>
                                <div class="spacer-10"></div>

                                @foreach (['success', 'error'] as $msg)
                                    @if (session($msg))
                                        <div class="alert alert-{{ $msg == 'success' ? 'success' : 'danger' }}">
                                            {{ session($msg) }}
                                        </div>
                                    @endif
                                @endforeach

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('post.password.forget') }}" method="POST">
                                    @csrf
                                    <div class="field-set">
                                        <input type="email" name="email" id="email" class="form-control"
                                            placeholder="{{ __('auth.email') }}" required>
                                    </div>
                                    <div class="spacer-10"></div>

                                    <div class="field-set">
                                        <button type="submit" id="send" class="btn btn-main btn-fullwidth rounded-3">
                                            {{ __('auth.reset_password.title') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
