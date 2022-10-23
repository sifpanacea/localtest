<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = lang('app_title');

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["designtemplate"]["active"] = true;
include("inc/nav.php");

?>
<style>
.superbox-list
{
border: 1px solid;
}
</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
    <div class="row">
    <article class="col-sm-12 col-lg-6">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-2" data-widget-editbutton="false" data-widget-deletebutton="false">
						<!-- widget options:
						usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
		
						data-widget-colorbutton="false"
						data-widget-editbutton="false"
						data-widget-togglebutton="false"
						data-widget-deletebutton="false"
						data-widget-fullscreenbutton="false"
						data-widget-custombutton="false"
						data-widget-collapsed="true"
						data-widget-sortable="false"
		
						-->
						<header>
                        <span class="widget-icon"> <i class="fa fa-paste"></i> </span>
							<h2><?php if ($updType == 'create'){
								echo lang('appp_create').lang('template_sub_heading');
							}elseif ($updType == 'edit'){
								echo lang('appp_edit').lang('template_sub_heading');
							}else{
								echo lang('appp_use').lang('template_sub_heading');}?></h2>
		              
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body fuelux">
		
								<div class="wizard">
									<ul class="steps">
										<li data-target="#step1" class="active">
											<span class="badge badge-info">1</span><?php echo lang('app_prop');?><span class="chevron"></span>
										</li>
										<li data-target="#step2">
											<span class="badge">2</span><?php echo lang('app_design');?><span class="chevron"></span>
										</li>
										<li data-target="#step3">
											<span class="badge">3</span><?php echo lang('app_work_flow');?><span class="chevron"></span>
										</li>
                                        <li data-target="#step4">
											<span class="badge">4</span><?php echo lang('app_notify');?><span class="chevron"></span>
										</li>
                                    	<!--<li data-target="#step4">
											<span class="badge">4</span>Step 4<span class="chevron"></span>
										</li>
										<li data-target="#step5">
											<span class="badge">5</span>Step 5<span class="chevron"></span>
										</li>-->
									</ul>
									<div class="actions">
										<!--<button type="button" class="btn btn-sm btn-primary btn-prev">
											<i class="fa fa-arrow-left"></i>Prev
										</button>-->
										<?php if($app_over) { ?>
										<div class="submit">
                                        
										<button type="button" class="btn btn-sm btn-success btn-next" data-last="Finish">
											<?php echo lang('app_next');?><i class="fa fa-arrow-right"></i>
										</button></div>
										<?php } ?>
									</div>
								</div>
								<div class="step-content">
								<!--	<form class="form-horizontal" id="fuelux-wizard" method="post">-->
		
										<div class="step-pane active" id="step1">
		
											<!-- wizard form starts here -->
									<!--<fieldset>-->
									
       <?php
						 	$attributes = array('class' => 'smart-form','updType' => $updType);
							echo  form_open('dashboard/app_prop',$attributes);
							?>
		      					<!--<form class="smart-form">-->
									<!--<header>
										Please Enter The User Information.
									</header>-->
									<fieldset>
                                    <section>
                            			<label class="label"><?php echo lang('template_app_name');?></label>
                                			<label class="input" id="appNamee"> <i class="icon-append fa fa-pencil"></i>
											 <input id="appName" type="text" name="appName" maxlength="256" value="<?php echo set_value('appName', (isset($template->app_name)) ? $template->app_name : ''); ?>"required/>
										</label>
										<div class="note">
												<strong><?php echo lang('app_note');?></strong> <?php echo lang('app_note_txt');?>
											</div>
		     						</section>

									<section>
                            			<label class="label"><?php echo lang('app_description');?></label>
											<label class="textarea textarea-expandable" id="appDescriptionn"> <i class="icon-append fa fa-pencil"></i>
                                            <textarea rows="3" id="appDescription" name="appDescription" class="custom-scroll" required><?php echo set_value('appDescription',(isset($template->app_description)) ? $template->app_description : '');?></textarea>
										</label>
		     						</section>
                                    <section>
                                    
												<label class="label"><?php echo lang('app_type');?></label>
                                         <div class="inline-group app_type">       
												<label class="radio">
													<input type="radio" id="apptype" name="apptype" value="Private" checked <?PHP echo set_radio('apptype','1',TRUE); ?>>
													<i></i><?php echo lang('temp_private');?></label>
												<label class="radio">
													<input type="radio" id="apptype" value="Shared" name="apptype"<?PHP echo set_radio('apptype','1'); ?>>
													<i></i><?php echo lang('temp_share');?></label>
										</div>
                                    </section>
                                         
                                    <section>
                            			<label class="label"><?php echo lang('app_expiry');?></label>
											<!--<div class="form-group">
													<div class="input-group">-->
                                                    <label class="input" id="datepickerr">
                                                    	<i class="icon-append fa fa-calendar"></i>
														<input type="text" name="mydate"  value="" class="datepicker" data-dateformat="yy-mm-d" id="date" readonly="true">
														<!--<span class="input-group-addon"><i class="fa fa-calendar"></i></span>-->
                                                        </label>
												<!--	</div>
												</div>
-->
		     						</section>
                                    <section>
                            			<label class="label"><?php echo lang('app_category');?></label>
                                			<label class="select" id="appcategoryy">
												<select name="appcategory" id="appcategory">
													<option value="0"><?php echo lang('app_select');?></option>
													<option value="Accounting"><?php echo lang('common_comm_acc');?></option>
													<option value="Automotive"><?php echo lang('common_comm_auto');?></option>
													<option value="Banking"><?php echo lang('common_comm_bank');?></option>
													<option value="Construction"><?php echo lang('common_comm_const');?></option>
                                                    <option value="Financial"><?php echo lang('common_comm_financial');?></option>
                                                    <option value="Healthcare"><?php echo lang('common_comm_health');?></option>
                                                    <option value="Manufacturing"><?php echo lang('common_comm_manufacturing');?></option>
                                                    <option value="RealEstate"><?php echo lang('common_comm_real');?></option>
                                                    <option value="Others"><?php echo lang('common_comm_other');?></option>
                                                </select> <i></i> </label>
                                                <?php /*?><?PHP echo form_dropdown('appcategory1',set_value('appcategory', (isset($template->appcategorydropdown)) ? $template->appcategorydropdown : '')); ?><?php */?>
		     						</section>
									<section>
                                    
												<label class="label"><?php echo lang('profile_header');?></label>
                                         <div class="inline-group profile_header">       
												<label class="radio">
													<input type="radio" id="headertype" name="headertype" value="yes"<?PHP echo set_radio('headertype','1',TRUE); ?>>
													<i></i><?php echo lang('app_header_yes');?></label>
												<label class="radio">
													<input type="radio" id="headertype" value="no" name="headertype" checked <?PHP echo set_radio('headertype','1'); ?>>
													<i></i><?php echo lang('app_header_no');?></label>
												</div>
                                    </section>
                                    <section>
                                    
												<label class="label"><?php echo lang('blank_app');?></label>
                                         <div class="inline-group blank_app">      
												<label class="radio">
													<input type="radio" id="blank_app" name="blank_app" value="yes"<?PHP echo set_radio('blank_app','1',TRUE); ?>>
													<i></i><?php echo lang('app_header_yes');?></label>
												<label class="radio">
													<input type="radio" id="blank_app" value="no" name="blank_app" checked <?PHP echo set_radio('blank_app','1'); ?>>
													<i></i><?php echo lang('app_header_no');?></label>
												</div>
                                    </section> 
                                    </fieldset>
									
                                    <?php /*?><?php echo form_submit( 'submit1', lang('prop_ok_btn'), "class='btn btn-sm btn-success'"); ?> &nbsp;</p>
								<?php echo form_close()?><?php */?>
								
								
								<input type='hidden' id='_id' value='<?php echo set_value('_id', (isset($template->_id)) ? $template->_id : ''); ?>' />
								<input type='hidden' id='updType' name='updType' value="<?php echo set_value('updType', $updType); ?>" />
								<input type='hidden' id='app_category' value='<?php echo set_value('app_category', (isset($template->app_category)) ? $template->app_category : ''); ?>' />
								<input type='hidden' id='app_expiry' value='<?php echo set_value('app_expiry', (isset($template->app_expiry)) ? $template->app_expiry : ''); ?>' />
								</form>
		<!--<footer>
										
											
										<button type="button" class="btn btn-default" onclick="window.history.back();">
											Back
										</button>
									</footer>-->
                             <?php /*?>   <!--    <input type="submit" class="btn bg-color-greenDark txt-color-white btn-next" name="submit1" value="<?php echo lang('prop_ok_btn');?>"/>--><?php */?>
		
					</div>
		
												
		
									<!--</fieldset>-->
		
										<!--</div>-->
		
										<div class="step-pane" id="step2">
											<h3><strong><?php if($updType == 'create'){
											    echo lang('appp_create');} elseif ($updType == 'edit'){
												echo lang('appp_edit');} elseif ($updType == 'draft'){
												echo lang('appp_draft');} else { echo 'Using'; }?>&nbsp;<?php echo lang('template_sub_heading');?></strong></h3>
		
        <!--<div class="jarviswidget" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-paste"></i> </span>-->
									
					<!--	</header>-->
		
							
							<!-- widget content -->
                            
						<!--	<div class="widget-body">
                            <div class="row">-->
                           
	<!--
           <button type="button" class="btn btn-labeled btn-success">
 <span class="btn-label">
  <i class="glyphicon glyphicon-plus"></i></span>
 </>Add Element
</button>     -->         <!--   </div>-->
                            	<table align="right" class="hide"><td><?php echo lang('template_element_count');?>
                                    <input id="weight" type="button" name="weight" class="fill-params btn btn-success btn-sm" maxlength="1" disabled value="5"/></td>
                                    <td><?php echo lang('app_page_no');?>
                                    <!--<input id="divcount" type="button" class="fill-params btn btn-success btn-sm" maxlength="1" disabled value="" />-->
                                    <input id="totaldivcount" type="button" class="fill-params btn btn-success btn-sm" maxlength="1" disabled value="" />
                                    </td></table>
									<br /><br /><br />
									<div class="row">
									<label class='page_'><?php echo lang('app_page_no');?></label>
									<input id="divcount" type="button" class="fill-params btn btn-success btn-sm" maxlength="1" disabled value="" />
									<button type="button" id="print_remove" class="btn_print_remove pull-right btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span></button>
									<input type="text" class="pull-right template_name" id="template_name" disabled></input>
									<button class="pull-right btn print_template btn-labeled btn-success btn-sm" id="print_template"><span class="btn-label"><i class="glyphicon glyphicon-print"></i></span><?php echo lang('app_print_temp');?></button>
									
									</div>
                            <div class="device">
                                    <div class="deviceheader row col col-12">
                                    <div class="col col-3 logo_img logo" style="background-image: url();"><h5 class="hide" id="click_upload">Click here to upload logo&nbsp;&nbsp;<i class="fa fa-edit"></i></h5></div>
                                    <label class="control-label col col-4 companyname"><i class="fa fa-edit"></i><?php echo $customer_details->display_company_name;?></label>
                                    <?php $address = explode(",",$customer_details->company_address);?>
                                    <?php $count = count($address);?>
                                     <?php for($i=0;$i<$count;$i++) { ?>
                                      <label class="control-label col col-4 pull-right address" id='address<?php echo $i?>'><?php if($i==0){?><i class="fa fa-edit"></i><?php } ?><?php echo $address[$i]; ?></label>
                                     <?php } ?>
                                 </div><!--deviceheader-->
                                    <center><label id="apptitle" name="apptitle" class="control-label apptit"><?php echo set_value('appName', (isset($template->app_name)) ? $template->app_name : ''); ?></label></center>
    									<div id="mainpage" class="mainpage">
										</div><!-- div mainpage-->
                                        <div class="foraddbutton">
                                        <center><button type="button" id="AddMoreFileBox" class="btn btn-labeled btn-success"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span></><?php echo lang('template_add_field_btn');?></button>
                                        <div id="FldCtrl">
                                        </div><!--//end of FldCtrl--></center>
                                        </div><!--for addbutton-->
                                          <?php 
    								$attributes = array('class' => 'tform', 'id' => 'designtemplate');
    								echo form_open_multipart('code_gen/index', $attributes);?>
                               <footer><div class="row col col-12 menubottom pull-left"><button class="btn btn-default btn-labeled menubtn col col-12" type="button" id="prev"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span></><?php echo lang('app_previous');?></button><button class="btn btn-default menubtn btn-labeled col col-12" type="button" id="Deletepage" value="Delete Page"><span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span></><?php echo lang('temp_delete');?></button><button class="btn btn-default btn-labeled menubtn col col-12" type="button" id="nxt" value="Next"><?php echo lang('app_next');?></><span class="btn-label btn-label-right"><i class="glyphicon glyphicon-chevron-right"></i></span></button></div></footer>
    					  </div><!-- div device-->
	<div class="modal fade bs-example-modal-sm" id="namme">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!--<button type="button" class="close" id="closed_d"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title"><?php echo lang('app_select_temp');?></h4>
      </div>
      <div class="modal-body">
<div class="row">

	<!-- SuperBox -->
	<div class="superbox col-sm-12">

		 <div class="gallery">          
    <?php $a=0;?>
    <?php foreach ($files as $file):?>
					
						
						<div class="superbox-list" id="<?php echo $a;?>"><img src="<?php echo URI.TENANT.TEMPLATE.'thumb_'.$file->file_name;?> " data-img="<?php echo URI.TENANT.TEMPLATE.$file->file_name;?>" filename="<?php echo $file->file_name;?>" description="<?php echo $file->file_description;?>" alt="<?php echo $file->file_title;?>" title="<?php echo $file->file_title;?>" class="superbox-img"></div>
						
					
					<?php $a++;?>
					<?php endforeach;?>
			</div>	       
	     
			<ul class="pager">
			  <li>
				<a class="previous" href="#"><?php echo lang('app_previous');?></a>
			  </li>
			  <li>
				<a class="next" href="#"><?php echo lang('app_next');?></a>
			  </li>
			</ul>
	</div>
	<!-- /SuperBox -->
	
	<div class="superbox-show" style="height:300px; display: none"></div>

</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="closed"><?php echo lang('app_close');?></button>
        <!--<button type="button" class="btn btn-primary" id="save_desc">Save</button>-->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
	<?php /*?><!--<center><button type="button" id="AddMoreFileBox" class="btn btn-labeled btn-success">
    <span class="btn-label">
  <i class="glyphicon glyphicon-plus"></i></span>
 </><?php echo lang('template_add_field_btn');?></button></center>--><?php */?>
	
		<input type="hidden" id="scaffold_code"  name="scaffold_code"  class='scaffold_textarea' /><?php echo set_value('scaffold_code', ''); ?></textarea>
 	<input type='hidden' name='pagenumber' id='pagenumber' value="" />
	<input type='hidden' id='print' name='print' value=""/>
	<input type='hidden' id='notify_values' name='notify_values' value=""/>
	<input type='hidden' id='header_values' name='header_values' value=""/>
 	<input id="controller_name" type="hidden" name="controller_name" maxlength="256" value="<?php  echo ($updType == 'create' OR $updType == '') ? set_value('controller_name') : set_value($template->_id.'_con');?>"/>
      <input id="model_name" type="hidden" name="model_name" maxlength="256" value="<?php  echo ($updType == 'create' OR $updType == '') ? set_value('model_name') : set_value($template->_id.'_mod');?>"/>
      <input type='hidden' id='jsondef' value='<?php echo set_value('jsondef', (isset($template->app_template)) ? json_encode($template->app_template) : ''); ?>' />
      <input type='hidden' id='jsontemp' value='<?php echo set_value('jsontemp', (isset($template)) ? json_encode($template) : ''); ?>' />
      <input type='hidden' id='print_temp' checked name='print_temp' value='<?php echo set_value('print_temp', (isset($template->print_template)) ? json_encode($template->print_template) : ''); ?>' />

      <input type='hidden' id='_id' value='<?php echo set_value('_id', (isset($template->_id)) ? $template->_id : ''); ?>' />
      <input type='hidden' id='updType' name='updType' value="<?php echo set_value('updType', $updType); ?>" />
      <input type='hidden' id='workflow' name='workflow' value="<?php echo set_value('workflow', (isset($template->workflow)) ? json_encode($template->workflow) : ''); ?>" />
     <input id="iappName" type="hidden" name="appName" maxlength="256" value=""/>
      <input id="iappDescription" type="hidden" name="appDescription" value=""/>
      <input id="iapptype" type="hidden" name="apptype" value="Private"/>
      <input id="iappexpiry" type="hidden" name="appexpiry" value=""/>
	  <input id="iheadertype" type="hidden" name="headertype" value="no"/>
	  <input id="iblank_app" type="hidden" name="blank_app" value="no"/>
	  <input id="iappcategory" type="hidden" name="appcategory" value=""/>
	  <input id="icompany" type="hidden" name="companyname" value="<?php echo $customer_details->company_name;?>"/>
	  <input id="iaddress" type="hidden" name="companyaddress" value="<?php echo $customer_details->company_address;?>"/>
      <input id="appcomplete" type="hidden" name="appcomplete" value="0"/>
	  <br>
                       
                           <?php /*?><!-- <div align="right" class="submit"><?php echo form_submit( 'submit1', lang('template_move_btn'), "class='save btn btn-danger'"); ?> 					                            </div> --><?php */?>
								<?php echo form_close();?>
							<!--</div>-->
							<!-- end widget content -->
		
						<!--</div>-->
						<!-- end widget div -->
		
					<!--</div>-->
					<?php
						 	$attributes = array('class' => 'hide logo_form');
							echo  form_open_multipart('dashboard/compony_logo',$attributes);
							?>
					<input type='file' id='file' name='logo_file' class="hide logo_file" value=""/>
					<input type='text' id='app_id' name='app_id' class="hide app_id" value=""/>
					<input type='text' id='url' name='url' class="hide url" value="<?php echo URL;?>"/>
								<?php echo form_close();?>
        
										</div>
		
										<!--<div class="step-pane" id="step3">
											<h3><strong>Step 5 </strong> - Finished!</h3>
                                            <br>
											<br>
											<h1 class="text-center text-success"><i class="fa fa-check"></i> Congratulations!
											<br>
											<small>Click finish to end wizard</small></h1>
											<br>
											<br>
											<br>
											<br>
										</div>			-->						
		
								<!--	</form>-->
								</div>
		
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
		
				</article>
				<!-- WIDGET END -->

		</div><!--row-->
				

	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
<div class='clearfix'></div>
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
		<script src="<?php echo(JS.'bootstrap-tagsinput.js');?>" type="text/javascript"></script>
        <script src="<?php echo(JS.'dynamic-add-08.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo(JS.'jsondefs-08.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo(JS.'wizard.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo(JS.'superbox-template.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo(JS.'bootstrap-multiselect.js'); ?>" type="text/javascript"></script>
		
<script type="text/javascript">
	
	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	$('.superbox').SuperBox();
	$(document).ready(function() {
		var url = "<?php echo URL; ?>"
			var start=0,end=2;
			var image=[];
		  var $validator = $("#wizard-1").validate({
		    
		    rules: {
		      email: {
		        required: true,
		        email: "Your email address must be in the format of name@domain.com"
		      },
		      fname: {
		        required: true
		      },
		      lname: {
		        required: true
		      },
		      country: {
		        required: true
		      },
		      city: {
		        required: true
		      },
		      postal: {
		        required: true,
		        minlength: 4
		      },
		      wphone: {
		        required: true,
		        minlength: 10
		      },
		      hphone: {
		        required: true,
		        minlength: 10
		      }
		    },
		    
		    messages: {
		      fname: "Please specify your First name",
		      lname: "Please specify your Last name",
		      email: {
		        required: "We need your email address to contact you",
		        email: "Your email address must be in the format of name@domain.com"
		      }
		    },
		    
		    highlight: function (element) {
		      $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		    },
		    unhighlight: function (element) {
		      $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
		    },
		    errorElement: 'span',
		    errorClass: 'help-block',
		    errorPlacement: function (error, element) {
		      if (element.parent('.input-group').length) {
		        error.insertAfter(element.parent());
		      } else {
		        error.insertAfter(element);
		      }
		    }
		  });
		  
		 
		  
	
		// fuelux wizard
		  var wizard = $('.wizard').wizard();
		  
		  wizard.on('finished', function (e, data) {
		    //$("#fuelux-wizard").submit();
		    //console.log("submitted!");
		    $.smallBox({
		      title: "Congratulations! Your form was submitted",
		      content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
		      color: "#5F895F",
		      iconSmall: "fa fa-check bounce animated",
		      timeout: 4000
		    });
		    
		  });
		 
		  $('.btn-next').on('click',function(e)
		  {
			  e.stopPropagation();
			  // console.log("Clickeeeeeed");
		  });
		   $('#appName').on("change",function()
		   {
			  $('#appNamee').removeClass("state-error");
			  var appn=$('#appName').val();
			  $('#apptitle').text(appn);
			  $('#iappName').val(appn);
			  // console.log(appn);
		  })
		  $('#appDescription').on("change",function()
		   {
			  $('#appDescriptionn').removeClass("state-error");
			  var appd=$('#appDescription').val();
			  $('#iappDescription').val(appd);
			  			  // console.log(appd);
			  
			  //var appn=$('#appName').val();
			  //$('#apptitle').text(appn);
		  })
		  $('#date').on("change",function()
		   {
			  $('#datepickerr').removeClass("state-error");
			  //var datee = $('#date').datepicker('getDate');
			  var datep=$('#date').val();
			  $('#iappexpiry').val(datep);
			  			  // console.log(datep);
			  //var appn=$('#appName').val();
			 // $('#apptitle').text(appn);
		  })
		  $('#appcategory').on("change",function()
		   {
			  if(appcat!=0)
			  {
			  $('#appcategoryy').removeClass("state-error");
			  var appcat=$('#appcategory').val();
			  $('#iappcategory').val(appcat);
			  }
			  // console.log(appcat);
			  //var appn=$('#appName').val();
			  //$('#apptitle').text(appn);
		  })
		  $('input[name=apptype]').on("change",function()
		   {
			  //$('#appcategoryy').removeClass("state-error");
			  //var appn=$('#appName').val();
			  //$('#apptitle').text(appn);
			  var rtype= $('input[name=apptype]:checked').val();
			  $('#iapptype').val(rtype);
			  // console.log(rtype);
		  })
		  $('input[name=headertype]').on("change",function()
		   {
			  var rtype= $('input[name=headertype]:checked').val();
			  $('#iheadertype').val(rtype);
			  
			  if(rtype =="yes")
			  {
				$(".deviceheader").children().bind('click', function(){ return false; });
				$(".deviceheader").addClass("edit")
				profile_header(true);
			  }
			  if(rtype =="no")
			  {
				$(".deviceheader").children().unbind('click');
				$(".deviceheader").removeClass("edit")
				profile_header(false);
			  }
			  // console.log(rtype);
		  })
		  $('input[name=blank_app]').on("change",function()
		   {
			  var rtype= $('input[name=blank_app]:checked').val();
			  $('#iblank_app').val(rtype);
			  console.log(rtype)
			  
			  if(rtype =="yes")
			  {
				//$(".deviceheader").children().bind('click', function(){ return false; });
				$(".deviceheader").addClass("hide")
				$("#apptitle").addClass("hide")
				//profile_header(true);
			  }
			  if(rtype =="no")
			  {
				//$(".deviceheader").children().unbind('click');
				$(".deviceheader").removeClass("hide")
				$("#deviceheader").addClass("hide")
				//profile_header(false);
			  }
			  console.log(rtype); 
		  })
		   $('.datepicker').datepicker({dateFormat: 'dd/mm/yy'});
			
			
			$("#namme").on('show.bs.modal', function (e) 
			{
							
				if($('#mainpage').find('.active').children('.print_temp').val()!='')
				{
				$('.superbox-list').hide();
				$('.superbox-show').hide();
					start=$('#mainpage').find('.active').children('.print_temp').attr("start")|| "0"
					end=$('#mainpage').find('.active').children('.print_temp').attr("end")|| "2"
					//console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa222222222",start);
					//console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa222222222",end);
					start=parseInt(start);
					end=parseInt(end);
					var activ_id=$('#mainpage').find('.active').children('.print_temp').attr("img_id")||0;
					//console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa333333333",activ_id);
					$('.gallery').children('.superbox-list').slice(start,end).show();
					$('.gallery').children('#'+activ_id+'').addClass("active");
					var that=$('.gallery').children('#'+activ_id+'').find('.superbox-img')//trigger("click")//.trigger("click");
					$(that).parent('.superbox-list').trigger('click');
				}
				else
				{
					$('.gallery').children('.superbox-list').slice(start,end).show();
				}
			})
			
			$(".next").on("click",function(e)
			{
			if($('.gallery').children('.superbox-list').last().css('display')=='inline-block')
			{
			//console.log("sssssssssssO")
			$(".next").attr("disabled","disabled")
			}
			else
			{
			$(".next").removeAttr("disabled");
			}
			if($(".next").attr("disabled")=="disabled")
			{
            e.preventDefault();    
			}
			else
			{
			$('.superbox-list').removeClass("active");
			$('.superbox-list').hide();
			$('.superbox-show').hide();
			start=end;
			end=end+2;
			$('.gallery').children('.superbox-list').slice(start,end).show();
			}
			})
			
			$(".previous").on("click",function(e)
			{
			if($('.gallery').children('.superbox-list').first().css('display')=='inline-block')
			{
			//console.log("sssssssssss")
			$(".previous").attr("disabled","disabled")
			}
			else
			{
			$(".previous").removeAttr("disabled");
			}
			if($(".previous").attr("disabled")=="disabled")
			{
            e.preventDefault();    
			}
			else
			{
			$('.superbox-list').removeClass("active");
			$('.superbox-list').hide();
			$('.superbox-show').hide();
			end=start;
			start=start-2;
			$('.gallery').children('.superbox-list').slice(start,end).show();
			//console.log("dffffffffff");
			}
			})
			
		$('.superbox-list').hide();
		
		function _getData(offset)
		{
		$.ajax
		({
			url: '../upload/ajax_page',
			type: 'POST',
			data:'{"offset":'+offset+'}',
			success: function (data) 
			{
			//console.log("s");
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown, textStatus);
			}
		});
		}
		
		$(document).on('click','.select',function()
		{
		//console.log("dddddd");
		var active_id=$('.gallery').find('.active').attr('id');
		var title=$('.title').text();
		$('#mainpage').find('.active').children('.print_temp').val(title);
		$('#template_name').val(title);
		$('#namme').modal('hide');
		var imgsrc=$('.superbox-current-img').attr("src");
		
		//console.log(imgsrc);
		var imgdesc=$('.superbox-img-description').text();
		//console.log(imgdesc);
		var url_img=$('.superbox-imageinfo').attr("src");
		
		if(imgsrc != undefined)
		{
			var relative_url_ = imgsrc.split('//');
			var relative__url = relative_url_[1].split('/')
			var relative_url = "";
			for (i = 1; i < relative__url.length; i++) {
			  relative_url += "/";
			  relative_url += relative__url[i];
			}
			$('#mainpage').find('.active').children('.print_temp').attr("relative_url",relative_url);
			$('#mainpage').find('.active').children('.print_temp').attr("img_src",imgsrc);
		}
		
		$('#mainpage').find('.active').children('.print_temp').attr("img_desc",imgdesc);
		
		$('#mainpage').find('.active').children('.print_temp').attr("start",start);
		$('#mainpage').find('.active').children('.print_temp').attr("end",end);
		$('#mainpage').find('.active').children('.print_temp').attr("img_id",active_id);
		//$('#mainpage').find('.active').children('.print_temp').attr("file_name",name_img);
		$('.superbox-list').removeClass("active");
		$('.superbox-show').hide();
		if(imgsrc != undefined)
		{
		$('.device').css("background-image", "url("+imgsrc+")");
		$('.device').css("background-repeat", "no-repeat");
		$('.device').css("background-size", "100% 100%");
		}

		//console.log(url_img);
		});
		$(document).on('click','.image-delete',function()
		{
		$('.superbox-list').removeClass("active");
		$('.superbox-show').hide();
		});
		
	})//document end

</script>
<script>
$(document).ready(function () {
 	
	// Restricting special characters in app name
	$('#appName').bind('keypress', function (event) {
		var regex = /^\s*[a-zA-Z0-9,\s]+\s*$/;  //new RegExp("^[a-zA-Z0-9]+$"); 
		var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
		if (!regex.test(key)) 
		{
			$('#appNamee').addClass("state-error");
			event.preventDefault();
			return false;
		}
		else
		{
		   $('#appNamee').removeClass("state-error");  
		}
    });
	
	// Admin username
    $.ajax({
				url: ''+url+'dashboard/adminusername',
				type: 'POST',

				success: function (data) {

				users=data;
				div = "";
				user = jQuery.parseJSON(data);
				div = div + "<div>"+user+"</div>";

				$('#adminusername').html(div);
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)
				{
				console.log('error', errorThrown);
				}
	})
	
});
</script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>