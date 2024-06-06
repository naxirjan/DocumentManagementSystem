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
	<li class="active">View Question Details</li>
	@endsection
	@include('usManager.includes.breadCrumb')
@endsection

@section('settingbox-section')
	@include('usManager.includes.settingBox')
@endsection

@section('pageheader-section')
<h1> 
	View Question Details
	
</h1>
@endsection


@section('page-content')
<div class="row"> 
     <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="form-horizontal">   
         <!--Question Description-->    
        <div class="form-group">
            <label class="col-sm-3" for="question"><b>Question Description:</b> </label>
            <div class="col-sm-9">
               <label class="" style="">{{$question[0]->question}}</label>
            </div>
        </div>    
        
        <!--Question Type-->
        <div class="form-group">
            <label class="col-sm-3"><b>Question Type:</b> </label>
            <div class="col-sm-9">
                <label class="">{{$question[0]->question_type}}
                </label>
            </div>
        </div>
        
        <!-- Status -->
        <div class="form-group">
            <label class="col-sm-3"><b>Status:</b></label>
            <div class="col-sm-9">
                <label class="">
                   <span class="label label-sm label-{{($question[0]->status=='Active'?'success':'danger')}} arrowed-in-right arrowed-in"> {{$question[0]->status}} </span>
                </label>
            </div>      
        </div>
        <!--Is Sum & Is Average-->             
        <div class="form-group">
        	<label class="col-sm-3"></label>
            <?php 
                /*If Is Sum*/
                if($question[0]->is_sum=='Yes')
                {
                    ?>
                        <div class="col-sm-2"> 
                        <label>
                            <i class="blue fa fa-check-square"></i>
                            <span class="lbl"> Is Sum </span>
                        </label>				
                       </div>        
                    <?php
                }
                /*If Is Average*/
                if($question[0]->is_average=='Yes')
                {
                    ?>
                    <div class="col-sm-2">
                        <label>
                           <i class="blue fa fa-check-square"></i>
                            <span class="lbl"> Is Average </span>
                        </label>
                   </div>
                    <?php
                }         
            ?>       
        </div>

        
        <?php 
            if(count($question_meta)>0)
            {
                ?>    
                    <div class="form-group">
                        <div>
                            <label class="col-sm-3" for="district_operation"><b>Items:</b></label>
                        </div>
                        <div class="col-sm-5">
                            <label class="" style="text-align: left;">
                           
                            <?php
                                foreach($question_meta as $meta)
                                {
                                    ?>
                                        <p><i class='ace-icon fa fa-caret-right blue'></i> {{$meta->value}}</p>
                                        
                                    <?php
                                }
                                ?>
                           
                            </label> 
                        </div>
                    </div>
                    <br />
                <?php
            }
        ?>
            
        </div>
    </div>
     <div class="col-md-3"></div>

</div>
@endsection


@section('footer-section')
	@include('usManager.includes.footer')
@endsection

@section('page_related_scripts')
<script type="text/javascript">
</script>
@endsection
