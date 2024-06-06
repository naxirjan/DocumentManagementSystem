<div id="sidebar" class="sidebar responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<button class="btn btn-success">
							<i class="ace-icon fa fa-signal"></i>
						</button>

						<button class="btn btn-info">
							<i class="ace-icon fa fa-pencil"></i>
						</button>

						<!-- #section:basics/sidebar.layout.shortcuts -->
						<button class="btn btn-warning">
							<i class="ace-icon fa fa-users"></i>
						</button>

						<button class="btn btn-danger">
							<i class="ace-icon fa fa-cogs"></i>
						</button>

						<!-- /section:basics/sidebar.layout.shortcuts -->
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list">
					<li class="{{(Request::segment(1) == 'countryManager' && Request::segment(2) == null)?'active':''}}">
						<a href="{{url('/countryManager')}}">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Dashboard </span>
						</a>

						<b class="arrow"></b>
					</li>
					
					<!-- Manage Users-Ghani -->
					<li class = "{{(Request::segment(1) == 'countryManager') && (Request::segment(2) == 'addUserForm' || Request::segment(2) == 'viewUsers' || Request::segment(2) == 'editUser')?'active':''}}">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-users"></i>
							<span class="menu-text">
								Manage Users
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="{{(Request::segment(2) == 'addUserForm')?'active':''}}">
								<a href="/countryManager/addUserForm">
									<i class="menu-icon fa fa-caret-right"></i>
									Add User
								</a>

								<b class="arrow"></b>
							</li>

							<li class="{{(Request::segment(2) == 'viewUsers' || Request::segment(2) == 'editUser')?'active':''}}">
								<a href="/countryManager/viewUsers">
									<i class="menu-icon fa fa-caret-right"></i>
									View Users
								</a>

								<b class="arrow"></b>
							</li>
						</ul>
					</li>
					<!--end-->

					<!-- Manage Questions-Nazir -->					
					<li class="{{(Request::segment(1) == 'countryManager') && (Request::segment(2) == 'addQuestion' || Request::segment(2) == 'viewQuestions' || Request::segment(2) == 'viewQuestionDetail' || Request::segment(2) == 'editQuestion')?'active':''}}">
			            <a href="#" class="dropdown-toggle">
			                <i class="menu-icon fa fa-question-circle"></i>
			                <span class="menu-text"> Manage Question</span>
			                <b class="arrow fa fa-angle-down"></b>
			            </a>

			            <b class="arrow"></b>
			            <ul class="submenu">

			                <li class="{{(Request::segment(2) == 'addQuestion')?'active':''}}">
			                    <a href="/countryManager/addQuestion">
			                        <i class="menu-icon fa fa-caret-right"></i>
			                        Add Question
			                    </a>

			                    <b class="arrow"></b>
			                </li>

			                <li class="{{(Request::segment(2) == 'viewQuestions' || Request::segment(2) == 'viewQuestionDetail' || Request::segment(2) == 'editQuestion')?'active':''}}">
			                    <a href="/countryManager/viewQuestions">
			                        <i class="menu-icon fa fa-caret-right"></i>
			                        View Questions
			                    </a>

			                    <b class="arrow"></b>
			                </li>
			            </ul>
			        </li>
			        <!--end-->

			        <!-- Manage Sections-Asad -->
			        <li class="{{(Request::segment(1) == 'countryManager') && (Request::segment(2) == 'addSection' || Request::segment(2) == 'viewSection' || Request::segment(2) == 'viewSectionDetail' || Request::segment(2) == 'editSection')?'active':''}}">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-credit-card"></i>
							<span class="menu-text">
								Manage Section
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							<li class="{{(Request::segment(2) == 'addSection')?'active':''}}">
								<a href="{{url('countryManager/addSection')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									Add Section
								</a>

								<b class="arrow"></b>
							</li>

							<li class="{{(Request::segment(2) == 'viewSection' || Request::segment(2) == 'viewSectionDetail' || Request::segment(2) == 'editSection')?'active':''}}">
								<a href="{{url('countryManager/viewSection')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									View Section
								</a>

								<b class="arrow"></b>
							</li>

						</ul>
					</li>
					<!--end-->

					<!-- Manage Templates-Asad -->
					<li class="{{(Request::segment(1) == 'countryManager') && (Request::segment(2) == 'addTemplate' || Request::segment(2) == 'viewTemplate' || Request::segment(2) == 'viewTemplateDetail')?'active':''}}">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-list-alt"></i>
							<span class="menu-text">
								Manage Templates
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							<li class="{{(Request::segment(2) == 'addTemplate')?'active':''}}">
								<a href="{{url('countryManager/addTemplate')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									Add Template
								</a>

								<b class="arrow"></b>
							</li>

							<li class="{{(Request::segment(2) == 'viewTemplate' || Request::segment(2) == 'viewTemplateDetail')?'active':''}}">
								<a href="{{url('countryManager/viewTemplate')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									View Templates
								</a>

								<b class="arrow"></b>
							</li>

						</ul>
					</li>
					<!--end-->

					<!-- Manage Project Instances-Asad -->
					<li class="{{(Request::segment(1) == 'countryManager') && (Request::segment(2) == 'addProjectInstance' || Request::segment(2) == 'viewProjectInstances' || Request::segment(2) == 'viewProjectInstanceDetail' ||  Request::segment(2) == 'editProjectInstance' ||  Request::segment(2) == 'viewAssignedProjectInstances' || Request::segment(2) == 'viewAssignedProjectInstanceDetail' || Request::segment(2) == 'submitDocumentTemplate')?'active':''}}">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-building"></i>
							<span class="menu-text">
								Project Instance 
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							<li class="{{(Request::segment(2) == 'addProjectInstance')?'active':''}}">
								<a href="{{url('countryManager/addProjectInstance')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									Add Project Instance
								</a>

								<b class="arrow"></b>
							</li>

							<li class="{{(Request::segment(2) == 'viewProjectInstances' || Request::segment(2) == 'viewProjectInstanceDetail' || Request::segment(2) == 'editProjectInstance')?'active':''}}">
								<a href="{{url('countryManager/viewProjectInstances')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									View Project Instances
								</a>
								<b class="arrow"></b>
							</li>

							<li class="{{(Request::segment(2) == 'viewAssignedProjectInstances' || Request::segment(2) == 'viewAssignedProjectInstanceDetail' || Request::segment(2) == 'submitDocumentTemplate')?'active':''}}">
								<a href="{{url('countryManager/viewAssignedProjectInstances')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									Assigned Project Instances
								</a>
								<b class="arrow"></b>
							</li>


						</ul>
					</li>
					<!--end-->

					<!-- Switch Role-Asad -->
					<li class="">
						<a href="{{url('countryManager/profile/'.Crypt::encryptString(Auth::user()->user_id))}}">
							<i class="menu-icon fa fa-key"></i>
							<span class="menu-text"> Switch Roles </span>
						</a>
						<b class="arrow"></b>
					</li>
					<!--end-->
					
				</ul><!-- /.nav-list -->

				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>