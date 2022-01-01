@extends('admin.common.layout')

@push('style')
<link href="{{ asset('css/admin/table/jquery-ui.bundle.css') }}" rel="stylesheet" />
@endpush

@push('script')
<script>
	var columns  = '{!! json_encode($columns) !!}';
	columns = JSON.parse(columns);
</script>
<script src="{{ asset('js/admin/table/table.js') }}"></script>
<script src="{{ asset('js/admin/table/change-status.js') }}"></script>
<script src="{{ asset('js/admin/table/jquery-ui.bundle.js') }}"></script>
<script src="{{ asset('js/admin/table/draggable.js') }}"></script>
<script>
	jQuery(document).ready(function() {
	    $('button').on('click', function () {
	        var sorted_array = $("#m_sortable_portlets").sortable('toArray');
	        var json = JSON.stringify(sorted_array);
	        document.getElementById('blockSort').value = json;
	        document.getElementById('blockSortForm').submit();
	    });
	});
</script>
@endpush

@section('content')
    	<div class="m_datatable"></div>
	</div>
</div>
<div class="m-portlet">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Sort blocks in all pages (* means all pages)
				</h3>
			</div>
		</div>
	</div>
	<div class="m-portlet__body">
	    <div class="row" id="m_sortable_portlets">
	    	@foreach($blocks as $block)
			<div class="col-lg-12">
				<div class="m-portlet m-portlet--mobile m-portlet--sortable 
					{{ !$block->show_all_pages ? 'm-portlet--brand' : 'm-portlet--danger' }}
					
					m-portlet--head-solid-bg" id="{{ $block->id }}">
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
								<span class="m-portlet__head-icon">
									{{ $block->id }}
								</span>
								<h3 class="m-portlet__head-text">
									{{ ucfirst($block->type) }}
									<small>
										{{ ucfirst($block->widget_type) }}
									-
									{{ $block->show_all_pages ? 'All pages' : 'Some pages' }}
									</small>
								</h3>
							</div>
						</div>
						<div class="m-portlet__head-tools">
							<ul class="m-portlet__nav">
								
								<li class="m-portlet__nav-item">
									<a href="javascript:void(0)" class="m-portlet__nav-link m-portlet__nav-link--icon">
									</a>
								</li>
								<li class="m-portlet__nav-item">
									<span class="m-switch m-switch--outline m-switch--icon m-switch--success m-switch--sm">
										<label>
											<input type="checkbox" onclick="changeStatus({{$block->id}})" 
											@if($block->activated == 1){
												checked="true"
											@endif
											/>
											<span></span>
										</label>
									</span>
								</li>
								<li class="m-portlet__nav-item">
									<a href="javascript:void(0)" class="m-portlet__nav-link m-portlet__nav-link--icon">
										<i class="la la-angle-down"></i>
									</a>
								</li>
								<li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover">
									<a href="javascript:void(0)" class="m-portlet__nav-link m-portlet__nav-link--icon m-dropdown__toggle">
										<i class="la la-ellipsis-v"></i>
									</a>
									<div class="m-dropdown__wrapper">
										<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
										<div class="m-dropdown__inner">
											<div class="m-dropdown__body">
												<div class="m-dropdown__content">
													<ul class="m-nav">
														<li class="m-nav__section m-nav__section--first">
															<span class="m-nav__section-text">
																Quick Actions
															</span>
														</li>
														<li class="m-nav__item">
															<a href="{{ route('admin.block.list.edit', $block->id)}}" class="m-nav__link">
																<i class="m-nav__link-icon flaticon-share"></i>
																<span class="m-nav__link-text">
																	Edit this block
																</span>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			@endforeach
			<div class="col-lg-12">
			</div>
		</div>

		<div class="row" style="margin-top: 30px;">
			<div class="col-lg-12 ml-lg-auto">
				<form id="blockSortForm" method="post" action="sort">
					@csrf
					<input type="hidden" name="blockSort" id="blockSort">
				</form>
				<button type="submit" class="btn btn-block btn-lg btn-success">
					{{ __('Save Changes') }}
				</button>
			</div>
		</div>
	</div>
</div>
@endsection

