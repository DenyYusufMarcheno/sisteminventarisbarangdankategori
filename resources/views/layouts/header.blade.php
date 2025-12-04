<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>

<div class="page-heading mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <h3>@yield('title')</h3>
        <div class="d-flex align-items-center">
            <span class="badge bg-primary me-2">
                <i class="bi bi-person-circle me-1"></i>
                {{ auth()->user()->name }}
            </span>
            <span class="badge bg-info">
                <i class="bi bi-shield-check me-1"></i>
                {{ ucfirst(auth()->user()->role) }}
            </span>
        </div>
    </div>
</div>