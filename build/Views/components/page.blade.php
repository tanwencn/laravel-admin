<div class="float-left">
    {{ trans('admin.pagination.range', [
    'first' => $results->firstItem(),
    'last' => $results->lastItem(),
    'total' => $results->total(),
    ]) }}
</div>

<div class="float-right">
    {{ $results->appends(request()->query())->links() }}
</div>
