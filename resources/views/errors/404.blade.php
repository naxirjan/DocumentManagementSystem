@extends( 'layouts.master' )



@section('page-content')
	<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->

		<div class="error-container">
			<div class="well text-center">
				<br /><br />
				<h1 class="grey lighter smaller">
					<span class="blue bigger-125">
						<i class="ace-icon fa fa-sitemap"></i>
						404
					</span>
					Page Not Found
				</h1>

				<hr>
				<h3 class="lighter smaller">We looked everywhere but we couldn't find it!</h3>

				<div>
					
					<div class="space"></div>
					<h4 class="smaller">Try one of the following:</h4>

					<ul class="list-unstyled spaced inline bigger-110 margin-15">
						<li>
							<i class="ace-icon fa fa-hand-o-right blue"></i>
							Re-check the url for typos
						</li>

						<li>
							<i class="ace-icon fa fa-hand-o-right blue"></i>
							Read the faq
						</li>

						<li>
							<i class="ace-icon fa fa-hand-o-right blue"></i>
							Tell us about it
						</li>
					</ul>
				</div>

				<hr>
				<div class="space"></div>

				<div class="center">
					<a href="javascript:history.back()" class="btn btn-grey">
						<i class="ace-icon fa fa-arrow-left"></i>
						Go Back
					</a>

				</div>
			</div>
		</div>

		<!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div>
@endsection


@section('footer-section')
	@include('usManager.includes.footer')
@endsection

@section('page_related_scripts')

@endsection
