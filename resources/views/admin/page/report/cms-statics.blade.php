<div class="col-xl-3">
	<div class="m-portlet m-portlet--full-height ">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						Statics
					</h3>
				</div>
			</div>
			<div class="m-portlet__head-tools">
				<ul class="nav nav-pills nav-pills--info m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
					<li class="nav-item m-tabs__item ">
						<a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_widget2_tab2_content1" role="tab">
							All
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="m-portlet__body">
			<div class="m-demo__preview">
				<div class="m-list-timeline">
					<div class="m-list-timeline__items">
						@foreach($count as $key => $value)
						<div class="m-list-timeline__item">
							<span class="m-list-timeline__badge m-list-timeline__badge--success"></span>
							<span class="m-list-timeline__text">
								{{ $key }}
							</span>
							<span class="m-list-timeline__time">
								{{ $value }}
							</span>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</div>