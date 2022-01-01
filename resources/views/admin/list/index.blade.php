@extends('admin.common.layout')

@push('script')
<script>
	var columns  = '{!! json_encode($columns) !!}';
	columns = JSON.parse(columns);
</script>
<script src="{{ asset('js/admin/table/table.js') }}"></script>
@endpush

@section('content')
    <div class="m_datatable"></div>
@endsection