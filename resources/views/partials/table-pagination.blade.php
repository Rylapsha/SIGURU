<div class="d-flex flex-column align-items-center gap-2">
    @if ($paginator->hasPages())
    <div class="d-flex justify-content-center">
        {{ $paginator->links('partials.pagination-links') }}
    </div>
    @endif

    <div class="text-muted fs-7 text-center">
        Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} results
    </div>
</div>
