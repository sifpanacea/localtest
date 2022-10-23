<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Create Group";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["chat"]['sub']['create_group']["active"] = true;
include("inc/nav.php");

?>
<link rel="stylesheet" href="<?php echo(CSS.'bootstrap-multiselect.css'); ?>" type="text/css"/>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["BC Welfare Masters"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	
	
	
<div class="row">
     				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>Messaging </h2>
		
						</header>
		
						<!-- widget div-->
<div>

	<!-- widget edit box -->
	<div class="jarviswidget-editbox">
		<!-- This area used as dropdown edit box -->

	</div>
	<!-- end widget edit box -->

	<!-- widget content -->
	
	<div class="widget-body no-padding">
	<?php
	$attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
	echo  form_open('bc_welfare_mgmt/create_group',$attributes);
	?>
		<!--<form class="smart-form">-->
			<header>
				Please enter the group name.
			</header>
			<fieldset>
			<div class="row">
			<section class="col col-12">
				<label class="label" for="first_name">Group Name</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="group_name" id="group_name" value="" required>
				</label>
			</section>
			</div>
			
			</fieldset>
			<footer>
				<button type="submit" class="btn bg-color-green txt-color-white submit" >
					Create
				</button>
				<button type="reset" class="btn btn-default">
					Clear
				</button>
			</footer>
		<?php echo form_close();?>

	</div>
	<!-- end widget content -->

</div>
<!-- end widget div -->
		
					</div>
					<!-- end widget -->
					</article>
        
        </div><!-- ROW -->
        
        <div class="row">
     				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>All Groups <span class="badge bg-color-greenLight"><?php if(!empty($groupscount)) {?><?php echo $groupscount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body no-padding">
					<table id="dt_basic" class="table table-striped table-bordered table-hover">
					<?php if ($groups): ?>
					<tr>
						<th>Group Name</th>
						<th>Action</th>
					</tr>
					<?php foreach ($groups as $group):?>
                    <tbody>
					<tr>
						<td><?php echo ucwords($group["group_name"]) ;?></td>
						<td>
                			<button class="btn btn-success btn-xs" onclick="edit_group('<?php echo $group['group_name'] ;?>','<?php echo $group['_id'] ;?>')" >Add/Edit members to group</button>
                			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                			|
                			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
						<a class='ldelete' href='<?php echo URL."bc_welfare_mgmt/delete_group/".$group['_id'];?>'>
                			<?php echo lang('app_delete')?>
                			</a>
						</td>
					</tr>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo "No groups created yet.";?>
        			</p>
        			<?php endif ?>
									</tbody>
									<?php if($links):?>
									<tfoot>
									
                      <tr>
                         <td colspan="5">
                            <?php echo $links; ?>
                         </td>
                      </tr>
					   
				    </tfoot>
                   <?php endif ?>
								</table>
		
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
					</article>
        
        </div><!-- ROW -->
        
        
        <!-- Modal -->
					<div class="modal fade" id="group_edit" tabindex="-1" role="dialog" >
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title" id="group_edit_label_lg">Add or edit existing members to group</h4>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-12">
											<h5 class="modal-title" id="group_edit_label">Group Name</h5>
											
											<form action="save_users_to_group" method="post">
									<fieldset>
											<div class="row">
					                    
										</div>
												<div class="row">
												<div class="col-md-12"><br>
												Select user groups &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Select users<br>
														<select id="select_group_name" name="select_group_name[]" multiple="multiple" required>
															<option value='admin' >Admin</option>
															<option value='doctors' >Doctors</option>
															<option value='hs' >Health Supervirors</option>
															<option value='ha' >Health Assistant</option>
															<option value='superior' >Superior</option>
														</select>
															<select id="users" name="users[]"  multiple="multiple" required>
															<option value=0 >Select at least a group</option>
															</select>
															</div>
															</div><br>
													<input type="hidden" id="ed_group_name" name="ed_group_name"/>
													<input type="submit" class="btn bg-color-pink txt-color-white btn-sm" id="set_group_btn" value="Set">
						                    </section>
											</fieldset>
										</form>	
										</div>
									</div>
								</div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->

	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<script src="<?php echo(JS.'bootstrap-multiselect.js'); ?>" type="text/javascript"></script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
$(document).ready(function() {
	 var values = [];
	 //$('#select_group_name').multiselect();
	 var	userslist = <?php echo json_encode($users); ?>;
	 console.log( userslist );
	 $('#users').multiselect({
		    includeSelectAllOption: true,
		    nonSelectedText:'Select Users',
		    buttonWidth: '190px',
	 });
	 $('#select_group_name').multiselect({
		 nonSelectedText:'Select Groups',
			buttonWidth: '190px',
         onChange: function(option, checked, select) {
        	 console.log( ": " + option.val() );
        	 console.log( ": " + checked );
        	 var group_name = option.val();
        	
        	 if(checked === true){
        		 $('#select_group_name option:selected')//.each(function()
 				{
        		 var selected_group=option.val();
        		 for (var i = 0; i < userslist[selected_group].length; i++) {
        			 
        			 //values.push(userslist[selected_group][i].email);
        			 values.push({
							label:userslist[selected_group][i].email+" , "+userslist[selected_group][i].username,
							value:userslist[selected_group][i].email
						    });
        		}
        		 $('#users').multiselect('dataprovider', values);
 				}
				}
        	 if(checked === false){
        		 $('#select_group_name option:selected')//.each(function()
  				{
        			 var selected_group=option.val();
        			 var remove = [];
        			 for (var i = 0; i < userslist[selected_group].length; i++) {
            			 remove.push({
    							label:userslist[selected_group][i].email+" , "+userslist[selected_group][i].username,
    							value:userslist[selected_group][i].email
    						    });
            		}

        			 for(var i in remove)
 					{
 					var xRemove=remove[i];
 					// console.log("jjjjjjjjjjjjjjjjj"+xRemove)
 					values = $.grep(values, function(val){
 				    return val.value !== xRemove.value;
 					})//.get(0);
 					}
         		 $('#users').multiselect('dataprovider', values);
  				}
        	 }
         }
     });
});
function edit_group(group_name, group_id) {
	$('#group_edit_label').text("Group name : "+group_name);
	$('#ed_group_name').val(group_name);
	$('#group_edit').modal('show');
}
</script>