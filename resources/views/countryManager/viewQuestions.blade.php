@extends( 'layouts.master' )

@section('page_sepecific_plugin')
@endsection


@section('navbar-section')
	@include('countryManager.includes.navBar')
@endsection

@section('sidebar-section')
	@include('countryManager.includes.sideBar')
@endsection

@section('breadcrumb-section')
	@section('breadcrumb-content')
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="#">Home</a>
	</li>
	<li class="active">View Questions</li>
	@endsection
	@include('countryManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('countryManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1> 
	View Questions
	<!-- <small><i class="ace-icon fa fa-angle-double-right"></i> Required (*) Fields Must Be Filled</small> -->
</h1>
@endsection


@section('page-content')
<div class="row">
    <div class="col-md-12">
        <div class="table-header">
            View All Questions
        </div>

        <table id="questions-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Question</th>
                    <th class="text-center">Question Type</th>
                    <th class="">Added By</th>
                    <th class="hidden-480">Is Sum</th>
                    <th class="hidden-480">Is Average</th>
                    <th class="hidden-480">Created Date</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $question)
                <?php 
                    /*Check If Question Is Assigned In Section / Template*/
                    $check=checkIfQuestionAlreadyAssigned($question->question_id);
                    if(session('current_role_user_id')==$question->added_by)
                    {
                    ?>
                    <tr>
                        <td>{{$question->question}}</td>
                        <td class="hidden-480 text-center">
                            {{$question->question_type}}
                        </td>
                        <td class="">{{$question->first_name}} {{$question->last_name}} &nbsp;<small class="label label-sm label-primary arrowed-in-right arrowed-in">{{$question->role}}</small></td>
                        <td class="hidden-480 text-center">{{$question->is_sum}}</td>
                        <td class="hidden-480 text-center">{{$question->is_average}}</td>
                        <td class="hidden-480">{{date('d F, Y',strtotime($question->created_at))}}</td>
                        <td class="text-center"><span class="label label-sm arrowed-in-right arrowed-in label-{{($question->status=='Active'?'success':'danger')}}">{{$question->status}}</span></td>
                        <td class="text-center">
                             
                            <div class="hidden-sm hidden-xs action-buttons">
								
                                <a title="View Question Detail" target="_blank" class="green" href="/countryManager/viewQuestionDetail/{{$question->question_id}}">
									<i class="ace-icon fa fa-search-plus bigger-150"></i>
								</a>

								<a title="Edit Question" target="_blank" class="red" href="/countryManager/editQuestion/{{$question->question_id}}">
									<i class="ace-icon fa fa-pencil bigger-150"></i>
								</a>
							</div>

							<div class="hidden-md hidden-lg">
								<div class="inline pos-rel">
									<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
										<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
									</button>

									<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
										<li>
											<a target="_blank" href="/countryManager/viewQuestionDetail/{{$question->question_id}}" class="tooltip-info" data-rel="tooltip" title="View Question Detail">
												<span class="green">
													<i class="ace-icon fa fa-search-plus bigger-120"></i>
												</span>
											</a>
										</li>

										<li>
											<a target="_blank" href="/countryManager/editQuestion/{{$question->question_id}}" class="tooltip-success" data-rel="tooltip" title="Edit Question">
												<span class="red">
													<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
												</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
                        </td>

                    </tr>
                    <?php
                    }
                    ?>
                @empty
                @endforelse
        </tbody>
        </table> 
    </div>
</div>
@endsection


@section('footer-section')
	@include('countryManager.includes.footer')
@endsection

@section('page_related_scripts')
<script src="{{asset('../assets/js/dataTables/jquery.dataTables.js')}}"></script>
<script src="{{asset('../assets/js/dataTables/jquery.dataTables.bootstrap.js')}}"></script>
<script src="{{asset('../assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js')}}"></script>
<script src="{{asset('../assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js')}}"></script>
        

<script type="text/javascript">
		jQuery(function($) {
				$('#questions-table')
				.dataTable({
                    "aaSorting": [],
                });
        } );    
			
</script>
@endsection
