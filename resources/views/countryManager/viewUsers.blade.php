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
	<li class="active">View Users</li>
	@endsection
	@include('countryManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('countryManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1>
	View Users 
</h1>
@endsection

@section('page-content')
<div class="row">
    <div class="col-md-12">
         <!--Success Message-->
        @if(session('success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                </button>
                <strong>
                     {{session('success')}}
                </strong>
            </div>
        @endif
        <!--Fail Message-->
        @if(session('danger'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                </button>
                <strong>
                     {{session('danger')}}
                </strong>
            </div>
        @endif
    </div>
</div>
<div class="row">
	<div class="col-sm-12">	
		<div class="table-header">
			Results for "Users"
		</div>

		<div>
			<table id="dynamic-table" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<!--<th>User ID</th> -->
						<th class="text-center">Image</th>
						<th>FullName</th>
						<th class="hidden-480">Email</th>
						<th class="hidden-480">Role</th>
						<th class="text-center">Status</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
					@forelse($users as $user)
						<tr>
							<!--<td class="center">{{-- $user['user_id'] --}}</td> -->
							<td class="text-center">
								<img style="width:50px;height:50px" src = "{{asset('storage/UserImage/'.$user->image)}}" class = "img-circle">
							</td>
							<td>{{$user->first_name." ".$user->last_name}}</td>
							<td class="hidden-480">{{$user->email}}</td>
							<td class="hidden-480">
								<ul class="blue">
									{{--@foreach($user['roles'] as $roles)
									<li>	
										{{$roles['role']}}
									</li>
									@endforeach
									--}}
									@php 
									$roles = userActiveRoles($user->user_id);
									@endphp	
									@foreach($roles as $key => $role)
										@if($role['role_id'] == 1)
										<li><b>{{$role['role']}} (USA)</b></li>
										@elseif($role['role_id'] == 2)
										<li><b>{{$role['role']}} - {{$role['cont_name']}}</b></li>
										@else
										<li><b>{{$role['role']}} - {{$role['cont_name']}} ({{$role['dop_name']}})</b></li>
										@endif
									@endforeach
								</ul>
							</td>
							<td class="text-center">
								@if($user->status == 'Active')
									<span class="label label-lg label-info arrowed-in arrowed-in-right">Active</span>
								@else
									<span class="label label-lg label-danger arrowed-in arrowed-in-right">Inactive</span>	
								@endif
							</td>

							<td class="text-center">
								<div class="hidden-sm hidden-xs action-buttons">
									<a target="_blank" title="View User Detail" class="green" href="/countryManager/profile/{{Crypt::encryptString($user->user_id)}}">
										<i class="ace-icon fa fa-search-plus bigger-150"></i>
									</a>

									<a target="_blank" title="Edit User" class="red" href="/countryManager/editUser/{{$user->user_id}}">
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
												<a target="_blank" href="/countryManager/profile/{{Crypt::encryptString($user->user_id)}}" class="tooltip-info" data-rel="tooltip" title="View User Detail">
													<span class="blue">
														<i class="ace-icon fa fa-search-plus bigger-120"></i>
													</span>
												</a>
											</li>

											<li>
                                                <a target="_blank" href="/countryManager/editUser/{{$user->user_id}}" class="tooltip-success" data-rel="tooltip" title="Edit User">
                                                    <span class="green">
                                                        <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                    </span>
                                                </a>
											</li>
										</ul>
									</div>
								</div>
							</td>
						</tr> 
					@empty
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>												
@endsection

@section('footer-section')
	@include('countryManager.includes.footer')
@endsection

@section('page_related_scripts')
<script type="text/javascript">
	jQuery(function($) {
		var oTable1 = $('#dynamic-table').dataTable({"aaSorting": []});
	});
</script>
@endsection