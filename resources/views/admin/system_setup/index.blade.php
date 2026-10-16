@extends('layouts.app')
@push('css')
    <link href="{{ asset('template/css/components/Banner.css') }}" rel="stylesheet" type="text/css">
@endpush
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
                        <div
                            class="col-md-12 text-center d-flex justify-content-center hover-underline responsive-heading">
                            {{ __('dashboard.edit_images') }}
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
                        {{--Banner Image--}}
                        <div class="row flex-column flex-sm-row justify-content-around align-items-center">

                            <x-stat-card icon="fa-user" imageType="dashboard"
                                         :label="__('dashboard.banner.dashboard')"/>

                            <x-stat-card icon="fa-sign-in" imageType="login" :label="__('dashboard.banner.login')"/>

                            <x-stat-card icon="fa-file-text" imageType="register"
                                         :label="__('dashboard.banner.register')"/>

                        </div>
                        <div class="gradient-line"></div>

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

                        <!-- Banner Upload Card -->
                        <form action="{{ route('system-setup.store') }}" method="post" enctype="multipart/form-data" >
                            @csrf
                            <div class="banner-image-upload-card">
                            <div class="card banner-card shadow">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                                    <div class="icon-circle mb-3">
                                        <i class="fa fa-file-image-o fa-2x"></i>
                                    </div>

                                    <h4 class="card-title mb-3">
                                        {{ __('dashboard.banner.card_upload') }}
                                    </h4>

                                    <div class="image-preview-container mb-3">
                                        <div class="image-preview" id="imagePreview">
                                            <div class="upload-placeholder">
                                                <i class="fa fa-cloud-upload fa-2x mb-2"></i>
                                                <p>{{ __('dashboard.banner.drag_drop') }}</p>
                                            </div>
                                            <img src="" alt="Preview" class="preview-img" style="display: none;">
                                        </div>
                                    </div>

                                    <div class="custom-file-upload mb-3 text-center">
                                        <label for="bannerImage" class="btn btn-outline-primary">
                                            <i class="fa fa-image me-2"></i>{{ __('dashboard.banner.choose_image') }}
                                        </label>
                                        <p>{{ __('dashboard.recommended_dimensions') }}</p>
                                        <input
                                            type="file"
                                            id="bannerImage"
                                            name="image"
                                            accept="image/*"
                                            style="display: none;"
                                        >
                                    </div>

                                    <!-- Image Name Input Field -->
                                    <div class="form-group w-100 mb-3">
                                        <label for="imageName" class="form-label">
                                            {{ __('dashboard.banner.image_name_label') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="imageName"
                                            name="name"
                                            placeholder="{{ __('dashboard.banner.image_name_placeholder') }}"
                                            required
                                        >
                                        <div class="invalid-feedback">
                                            {{ __('dashboard.banner.image_name_required') }}
                                        </div>
                                    </div>

                                    <div class="form-group w-100 mb-3">
                                        <label for="pageSelector" class="form-label">
                                            {{ __('dashboard.banner.apply_label') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select
                                            class="form-select"
                                            id="pageSelector"
                                            name="image_type"
                                            required
                                        >
                                            <option value="dashboard" selected disabled>
                                                {{ __('dashboard.banner.select_page') }}
                                            </option>
                                            <option value="dashboard">
                                                {{ __('dashboard.banner.dashboard') }}
                                            </option>
                                            <option value="login">
                                                {{ __('dashboard.banner.login') }}
                                            </option>
                                            <option value="register">
                                                {{ __('dashboard.banner.register') }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ __('dashboard.banner.page_required') }}
                                        </div>
                                    </div>

                                    <button
                                        type="submit"
                                        id="saveImageButton"
                                        class="btn btn-primary w-100 mt-2"
                                        disabled
                                    >
                                        <i class="fa fa-save me-2"></i>{{ __('dashboard.banner.save_button') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- content close -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('bannerImage');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.querySelector('.preview-img');
            const uploadPlaceholder = document.querySelector('.upload-placeholder');
            const pageSelector = document.getElementById('pageSelector');
            const imageName = document.getElementById('imageName');
            const saveButton = document.getElementById('saveImageButton');
            const bannerForm = document.getElementById('bannerImageForm');
            const formImageType = document.getElementById('formImageType');
            const formImageName = document.getElementById('formImageName');

            // Function to check if all required fields are filled
            function checkRequiredFields() {
                const hasImage = fileInput.files && fileInput.files[0];
                const hasPageSelected = pageSelector.value && pageSelector.value !== "";
                const hasName = imageName.value.trim() !== "";

                // Enable save button only if all three required fields are filled
                saveButton.disabled = !(hasImage && hasPageSelected && hasName);
            }

            // Function to handle the selected file
            function handleFile(file) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        uploadPlaceholder.style.display = 'none';
                        previewImg.style.display = 'block';

                        // Check if all fields are filled
                        checkRequiredFields();
                    }

                    reader.readAsDataURL(file);

                    // Store file in the fileInput for later form submission
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;
                } else {
                    alert('Please upload an image file');
                }
            }

            // Handle file selection through input
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    handleFile(this.files[0]);
                }
            });

            // Handle drag and drop events
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                imagePreview.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // Handle drag enter and over styling
            ['dragenter', 'dragover'].forEach(eventName => {
                imagePreview.addEventListener(eventName, highlight, false);
            });

            // Handle drag leave and drop styling
            ['dragleave', 'drop'].forEach(eventName => {
                imagePreview.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                imagePreview.classList.add('drag-highlight');
            }

            function unhighlight() {
                imagePreview.classList.remove('drag-highlight');
            }

            // Handle drop event
            imagePreview.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const file = dt.files[0];
                handleFile(file);
            });

            // Make image preview clickable to trigger file input
            imagePreview.addEventListener('click', function() {
                fileInput.click();
            });

            // Handle page selection
            pageSelector.addEventListener('change', function() {
                checkRequiredFields();
            });

            // Handle image name input
            imageName.addEventListener('input', function() {
                checkRequiredFields();
            });

            // Handle save button click
            saveButton.addEventListener('click', function() {
                // Validate form one more time
                if (!fileInput.files || !fileInput.files[0]) {
                    alert('Please select an image file');
                    return;
                }

                if (!pageSelector.value) {
                    alert('Please select a page');
                    return;
                }

                if (!imageName.value.trim()) {
                    alert('Please enter a name for the image');
                    return;
                }

                // Set form values
                formImageType.value = pageSelector.value;
                formImageName.value = imageName.value.trim();

                // Clone the file to the hidden form input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(fileInput.files[0]);
                document.getElementById('formBannerImage').files = dataTransfer.files;

                // Submit the form
                bannerForm.submit();
            });
        });
    </script>

@endsection
