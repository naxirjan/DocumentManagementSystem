{{--dd($userAssignedRoles)--}}
@extends( 'layouts.master' )

@section('page_sepecific_plugin')
@endsection


@section('navbar-section')
	@include('usManager.includes.navBar')
@endsection

@section('sidebar-section')
	@include('usManager.includes.sideBar')
@endsection

@section('breadcrumb-section')
	@section('breadcrumb-content')
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="#">Home</a>
	</li>
	<li class="active">US Manager Dashboard</li>
	@endsection
	@include('usManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('usManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1>
	User Profile 
	<!-- <small>
		<i class="ace-icon fa fa-angle-double-right"></i>
			overview &amp; stats
	</small> -->
</h1>
@endsection


@section('page-content')
<div class="row">
 	<div class="col-sm-12">	
		<div class="col-md-3">
	        <div class="text-center">
			<img src="{{asset('storage/UserImage/'.Auth::user()->image)}}" class='avatar img-circle img-thumbnail' style='width:211px; height:210px;' />								
	    	<div class="space-4"></div>
	    	<div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
				<div class="inline position-relative">
					<div class="user-title-label">
						<i class="ace-icon fa fa-circle light-green"></i>
						&nbsp;
						<span class="white"><b>{{$userData['first_name']." ".$userData['last_name']}}</b></span>
					</div>
				</div>
			</div>

			@if(Auth::user()->user_id == $user_id)
			<div class="space-4"></div>
	    	<div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
				<div class="inline position-relative">
					<div class="user-title-label">
						<i class="ace-icon fa fa-key light-green"></i>
						&nbsp;
						<span class="white"><b>{{session()->get('current_active_role_type')}}</b></span>
					</div>
				</div>
			</div>
			@endif
			<div class="space-4"></div>
			<button class = "btn btn-success" id = "changePassword" value = "{{ Request::segment(3) }}">Change Password</button>	
			</div>
	    </div>
	    <div class = "col-md-6">
				<div id="dialog-message" class = "hide" >
					<form>
					@if(Auth::user()->user_id == $user_id)	
					  <div class="form-group">
					    <label for="oldPassword">Old Password</label>
					    <input type="password" class="form-control" id="oldPassword" aria-describedby="emailHelp" placeholder="Old Password">
					     <small id="oldPasswordMessage" class="form-text text-muted text-danger"></small>
					  </div>
					@endif
					  <div class="form-group">
					    <label for="newPassword">New Password</label>
					    <input type="password" class="form-control" id="newPassword" placeholder="Password">
					  </div>
					  <div class="form-group">
					    <label for="confirmPassword">Confirm Password</label>
					    <input type="password" class="form-control" id="confirmPassword" placeholder="Password">
					    <small id="confirmPasswordMessage" class="form-text text-muted text-danger"></small>
					  </div>
					</form>
				</div>
		</div>
	    <!-- edit form column -->
	    <div class="col-md-9 personal-info">
	      	<h3>Personal Information</h3>
	      	<hr />
		    <div class="profile-user-info profile-user-info-striped">
		            <div class="profile-info-row">
		                          <div class="profile-info-name"> Name </div>

		                          <div class="profile-info-value">
		                            <span class="editable editable-click" id="username">
		                            	{{$userData['first_name']." ".$userData['last_name']}}
		                            </span>
		                          </div>
		            </div>
		            
		            <div class="profile-info-row">
		                          <div class="profile-info-name"> Email </div>

		                          <div class="profile-info-value">
		                            <span class="editable editable-click" id="country">{{$userData['email']}}
		                            </span>
		                          </div>
		            </div>

		            <div class="profile-info-row">
		                          <div class="profile-info-name"> Number </div>

		                          <div class="profile-info-value">
		                            <span class="editable editable-click" id="age">{{$userData['phone']}}
		                            </span>
		                          </div>
		            </div>

		            <div class="profile-info-row">
		                          <div class="profile-info-name"> Status </div>

		                          <div class="profile-info-value">
		                            @if($userData['status'] == 'Active')
		                            <span class="label label-success arrowed-in-right" id="age">{{$userData['status']}}</span>
		                          	@else
		                          	<span class="label label-danger arrowed-in-right" id="age">{{$userData['status']}}</span>
		                            @endif
		                          </div>
		            </div>
		    </div>
	    
	    	<div class="col-xs-12 col-sm-12 col-md-12">
	    		<h3>Assigned Roles</h3>
	      		<hr />
	      		<div  id="Msg" class="alert alert-block alert-success hide">
					<button type="button" class="close" data-dismiss="alert">
						<i class="ace-icon fa fa-times"></i>
					</button>
					<i class="ace-icon fa fa-check green"></i>
					Project Assigned Successfully ...!
				</div>	
	    	
		    	<div class="widget-box widget-color-blue ui-sortable-handle">
					<!-- /section:custom/widget-box.options -->
		            <div class="widget-body">
		               <div class="widget-main no-padding">
		                    <table class="table table-striped table-bordered table-hover">
		                        <thead class="thin-border-bottom">
		                            <tr>
		                                <th>
		                                  <i class="ace-icon fa fa-key"></i>
		                                  Role
		                                </th>
		                                <th>
		                                	<i class="ace-icon fa fa-flag"></i>Country
		                                </th>
		                                <th class="">
		                                	<i class="ace-icon fa fa-map-marker"></i>Locations
		                                </th>
		                            </tr>
		                        </thead>

		                        <tbody>
		                            @foreach($userAssignedRoles as $role_key => $roles)
		                            <tr>
		                                <td class=""><h5>{{$role_key}}</h5></td>
		                                <td>
		                                @foreach($roles['countries'] as $cont_key => $country)
		                                  	@if($country[0]['role_id'] == 1 || $country[0]['role_id'] == 2)
		                                  		<h5>
		                                  		@if($roles['countries'][$cont_key][0]['role_status'] == "Active")	
		                                  			@php $class = 'success' @endphp
			                                  		@if(Auth::user()->user_id == $user_id)
			                                  			<?php $url = url('switchRole').'/'.$country[0]['role_user_id'];?>
			                                  		<a title="Switch Role" href="{{$url}}" style="text-decoration:none">		
			                                  			@if($country[0]['role_user_id'] == Session::get('current_role_user_id'))
			                                  			<i class="ace-icon fa fa-circle light-green"></i>
			                                  			@else
			                                  			<i class="ace-icon fa fa-angle-double-right blue"></i>
			                                  			@endif
			                                  			<b>{{$cont_key}}</b>
			                                  		</a>		
			                                  		@else
			                                  			<i class="ace-icon fa fa-angle-double-right blue"></i>
			                                  			{{$cont_key}}
			                                  		@endif
		                                  		@else
		                                  			<i class="ace-icon fa fa-angle-double-right red"></i>
		                                  			{{$cont_key}}
		                                  			@php $class = 'danger' @endphp
		                                  		@endif
		                                  			<span class="pull-right label label-sm arrowed-in-right arrowed-in label-{{$class}}">{{$roles['countries'][$cont_key][0]['role_status']}}</span>	
		                                		
		                                  		</h5>
		                                  	@else
		                                  		<h5><i class="ace-icon fa fa-angle-double-right blue"></i>
		                                  			{{$cont_key}}</h5>
		                                  	@endif
		                                @endforeach
		                               	</td>
		                               	<td class="">
		                                @foreach($roles['dop'] as $dop)
		                                	@if($dop['role_id'] == 3 || $dop['role_id'] == 4 || $dop['role_id'] == 5)
		                                  	<h5>
		                                  		@if($dop['role_status'] == "Active")
		                                  			@php $class = 'primary' @endphp
			                                  		@if(Auth::user()->user_id == $user_id)		
			                                  			<?php $url = url('switchRole').'/'.$dop['role_user_id'];?> 
				                                  			@php $class = 'success' @endphp
			                                  		<a title="Switch Role" href="{{$url}}" style="text-decoration:none">
			                                  			@if($dop['role_user_id'] == Session::get('current_role_user_id'))
		                                          		<i class="ace-icon fa fa-circle light-green"></i>
		                                        		@else
			                                  			<i class="ace-icon fa fa-angle-double-right blue"></i>
			                                  			@endif
			                                  			<b>{{$dop['dop_name']}}</b>
			                                  		</a>
			                                  		@else
			                                  			<i class="ace-icon fa fa-angle-double-right blue"></i>
			                                  			{{$dop['dop_name']}}
			                                  		@endif
		                                  		@else
		                                  			<i class="ace-icon fa fa-angle-double-right red"></i>
		                                  			{{$dop['dop_name']}}
		                                  			@php $class = 'danger' @endphp
		                                  		@endif
		                                  		<span class="label label-sm arrowed-in-right arrowed-in label-{{$class}}">{{$dop['role_status']}}</span>
		                                  		@if($dop['role_id'] == 3 || $dop['role_id'] == 5 && $dop['role_status'] == "Active")
		                                  		<a href="javascript:void(0)" class="pull-right btn btn-xs btn-primary btnAssignProjects" data-role-user-id="{{$dop['role_user_id']}}" data-dop-id="{{$dop['dop_id']}}"><i class="ace-icon fa fa-plus"></i> Assigned Projects</a>
		                                  		<div class="space-6"></div>
		                                  		<?php 
		                                  			$projects = getUserProjectsByRoleUserID($dop['role_user_id']);
		                                  			if(count($projects)>0){
		                                  				echo "<ul style='list-style-type:square'>";
		                                  				foreach ($projects as $project) {
											            ?>
											            	<li>{{$project->proj_name}}
											            		<a class ="deleteUserProject" href="{{$project->project_user_id}}"><i class ='fa fa-trash red'></i></a>	
											            	</li>
											            <?php	
											            }
		                                  				echo "</ul>";		
		                                  			}
		                                  		?>
		                                  		@endif
		                                  	</h5>
		                                  	@endif
		                                @endforeach
		                                </td>
		                            </tr>
		                            @endforeach	
		                        </tbody>
		                    </table>
		               </div>
		            </div>
		        </div>
	    	</div>
	    	
	    	<div class = "col-md-8">
				<div id="dialog-assign-project" class ="hide">
				</div>
			</div>
	    </div>
	</div>
</div>
@endsection


@section('footer-section')
	@include('usManager.includes.footer')
@endsection

@section('page_related_scripts')
<script type="text/javascript">
jQuery(function($) {
	var error;
			
	$(document).on('focusout','#oldPassword',function(){
					var oldPassword = $(this).val();
					var userId = $('#changePassword').val();
					$.ajax({
						type:'POST',
						url:'/usManager/verifyPassword',
						data:{
							userId:userId,
							oldPassword:oldPassword,
							'_token':"{{csrf_token()}}",
						},
						success:function(data){
							if(data != 1){
								$('#oldPasswordMessage').html('Your Old Password Appears To Be Incorrect !..')
								$('.save-password').attr('disabled','disabled');		
							}
							else{
								$('#oldPasswordMessage').html('');
								$('.save-password').removeAttr('disabled');
							}
						}
					});	
	});

	$(document).on('click','.save-password',function(){
				var oldPassword = $('#oldPassword').val();
				var newPassword = $('#newPassword').val();
				var confirmPassword = $('#confirmPassword').val();
				var userId = $('#changePassword').val(); 
				$.ajax({
						type:'POST',
						url:'/usManager/changePassword',
						data:{
							userId:userId,
							oldPassword:oldPassword,
							newPassword:newPassword,
							confirmPassword:confirmPassword,
							'_token':"{{csrf_token()}}",
						},
						success:function(data){
							if(data == 0){
								$('#confirmPasswordMessage').html('Use only contain letters, numbers, dashes and underscores / New Password & Confirm Password Mismatch !..')
							}
							else{
								$('#oldPassword').val('');
								$('#newPassword').val('');
								$('#confirmPassword').val('');
								$('#confirmPasswordMessage').html('');
								$( "#dialog-message" ).dialog( "close" );
							}
						}
					})
	});

	//override dialog's title function to allow for HTML titles
	$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
					_title: function(title) {
						var $title = this.options.title || '&nbsp;'
						if( ("title_html" in this.options) && this.options.title_html == true )
							title.html($title);
						else title.text($title);
					}
	}));
			
	$( "#changePassword" ).on('click', function(e) {
					e.preventDefault();
			
					var dialog = $( "#dialog-message" ).removeClass('hide').dialog({
						modal: true,
						title: "<div class='widget-header widget-header-small'><h4 class='smaller'><i class='ace-icon fa fa-key'></i> Change Password</h4></div>",
						title_html: true,
						buttons: [ 
							{
								text: "Cancel",
								"class" : "btn btn-minier",
								click: function() {
									$( this ).dialog( "close" ); 
								} 
							},
							{
								text: "Save",
								"class" : "btn btn-primary btn-minier save-password",
								/*click: function() {
									$( this ).dialog( "close" ); 
								}*/ 
							}
						]
					});
			
					
					/*dialog.data( "uiDialog" )._title = function(title) {
						title.html( this.options.title );
					};*/
	});
                    
	/*Project Modal Appears When Assign button Click*/
    $(document).on('click',".btnAssignProjects",function(){
					
        let roleUserId = $(this).data('role-user-id');
		let dopId      = $(this).data('dop-id');
		
		if(roleUserId && dopId){
			$.ajax({
					type:'POST',
					url:'/usManager/userProjects',
					data:{roleUserId:roleUserId,dopId:dopId,'_token':"{{csrf_token()}}"},
					success:function(data){
						var dialog = $( "#dialog-assign-project" ).removeClass('hide').dialog({
						modal: true,
						width: '500',
						title: "SELECT PROJECT",
						title_html: true,
						buttons:[ 
							{
								text: "Cancel",
								"class" : "btn btn-minier",
								click: function() {
									$( this ).dialog( "close" ); 
								} 
							},
							{
								text: "Save",
								"class": "btn btn-primary btn-minier assign-projects",
		                        'current_role_user_id':roleUserId,
								click: function() {
									//$( this ).dialog( "close" ); 
								} 
							}
						]
						});
						$('#dialog-assign-project').html(data);
						
						/*If true So Hide Save Button*/
						if($('#flag').val()){
							$('.assign-projects').hide();
						}
					}
			});
		}
	});
    //end-->

    /*Assigned Project (Save)*/
	$(document).on('click','.assign-projects',function(){
				   
		let districtOperationProjectId = [];
		let roleUserId = $(this).attr('current_role_user_id');
                    
        $('.checkbox:checked').each(function () {
			districtOperationProjectId.push($(this).data('district-operation-project-id'));
		});      
			    		   
		if(districtOperationProjectId.length > 0){
			$.ajax({
				    url:'/usManager/assignProjects',
				    type:'POST',
				    data:{
				       	roleUserId:roleUserId,
				       	districtOperationProjectId:districtOperationProjectId,
				       	'_token':"{{csrf_token()}}",
				    },
				    success:function(data){
				       	if(data != 'Project Not Assigned'){
				       			
				       		$('#Msg').removeClass('hide');
				       		$('#dialog-assign-project').dialog('close');
				       		setTimeout(function(){
				       			window.location.href ="/usManager/profile/<?php echo Request::segment(3); ?>";	
				       		},2000);
				       			
				       	}
				    }
			});	
		}else{
			$('#errorMsg').removeClass('hide');
		}
	});
	//end-->

	/*Delete Assigned Project*/	
	$(document).on('click','.deleteUserProject',function(e){
		e.preventDefault();

		let projectUserId = $(this).attr('href');
		let roleUserId = $('#assignProjects').val();
		$.ajax({
				url:'/usManager/deleteUserProject',
			    type:'POST',
			    data:{
			       	projectUserId:projectUserId,
			       	roleUserId:roleUserId,
			       	'_token':"{{csrf_token()}}",
			    },
			    success:function(data){
			       	if(data == 'deleted'){
			       		window.location.href ="/usManager/profile/<?php echo Request::segment(3); ?>";
			       	}
                }	
		});
	});
	//end-->						
});
</script>
@endsection
