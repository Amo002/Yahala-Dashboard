@props(['type' => 'success', 'message' => null])

@if($message)
    <div class="alert alert-{{ $type }} alert-dismissible fade show d-flex align-items-center" role="alert">
        @switch($type)
            @case('success')
                <i class="bi bi-check-circle-fill me-2"></i>
                @break
            @case('danger')
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                @break
            @case('warning')
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                @break
            @case('info')
                <i class="bi bi-info-circle-fill me-2"></i>
                @break
        @endswitch
        <div>{{ $message }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif 