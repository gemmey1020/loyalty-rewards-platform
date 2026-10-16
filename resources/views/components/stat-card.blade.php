

<div class="col-lg-3 col-6 mb25 order-sm-1 card-analytics">
    @if ($url)
        <a href="{{ $url }}" class="text-decoration-none text-dark">
            @endif

            @if ($imageType)

                <div onclick="document.getElementById('autoSubmitForm-{{ $imageType }}').submit()"
                     class="banner-card-wrapper">
                    <form id="autoSubmitForm-{{ $imageType }}" action="{{ url('/system-setup/show') }}" method="GET">
                        <input type="hidden" name="image_type" value="{{ $imageType }}">
                    </form>

                    <div class="card banner-card shadow">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                            <div class="icon-circle mb-3">
                                <i class="fa {{ $icon }} fa-2x"></i>
                            </div>
                            <h4 class="card-title mb-2">{{ $label }}</h4>

                            <p class="text-muted">Click to manage</p>
                        </div>
                        <div class="card-footer banner-card-footer text-center py-2">
                            <span class="text-white">View Options</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="card p-4 rounded-5 text-center h-100">
                    <input type="text" size="10" disabled hidden/>
                    <div class="symbol mb40">
                        <i class="fa id-color fa-2x {{ $icon }}"></i>
                    </div>
                    <span class="h1 mb0 {{ $level }}">{{ number_format($count) }}</span>
                    <span class="text-gray">{{ $label }}</span>
                </div>
            @endif

            @if ($url)
        </a>
    @endif
</div>
