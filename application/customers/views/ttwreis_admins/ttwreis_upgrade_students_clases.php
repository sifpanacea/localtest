<?php $current_page = "Updrade Classes"; ?>
<?php $main_nav = "Imports"; ?>
<?php include("inc/header_bar.php"); ?>
<?php include("inc/sidebar.php"); ?>

<section class="content">
		
    <div class="row clearfix">
       
 		<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12"> 			
 			    <div class="card">
 			    	<div class="header">
 			    		<h2>Upgrade Student Class</h2>
 			            
 			    	</div>
 			       
 			        <div class="body">
 			            <p>Select the school which you want to upgrade class</p>
 			        	<div class="row clearfix">
 			                <?php 
 			                $attributes = array('class' => 'smart-form');
 			                echo form_open_multipart('ttwreis_mgmt/upgrade_class_schoolwise',$attributes);?>
 			                    <div class="panel-body">
 			                    		<div class="form-line">
 			                    			<br>
 			                            <div class="col-sm-4">
 			                            <label for="first_name">District Name</label>
 			                            
 			                            <select id="select_dt_name_upgrade" name="select_dt_name_upgrade" class="form-control">
 			                            <option value="" selected="0" disabled="">Select a district</option>
 			                            <?php if(isset($distslist)): ?>
 			                                <?php foreach ($distslist as $dist):?>
 			                                <option value='<?php echo $dist['_id']?>' ><?php echo ucfirst($dist['dt_name'])?></option>
 			                                <?php endforeach;?>
 			                                <?php else: ?>
 			                                <option value="1"  disabled="">No district entered yet</option>
 			                                <?php endif ?>
 			                            </select>
 			                            </div>
 			                            <div class="col-sm-8">
 			                                <label for="first_name">School Name</label>
 			                                <select id="school_names" name="school_names" class="form-control" disabled=true>
 			                                <option value="0" selected="" disabled="">Select a district first</option>

 			                                </select>
 			                            </div>
 			                      
 			                            <br>
 			                            <br>
 			                            <br>
 			                            <br>
 			                            <br>

 			                            <button type="submit" class="btn btn-success waves-effect" name="submit" id="upgrade_sbt" data-toggle="modal" data-target="#import_waiting" data-backdrop="static" data-keyboard="false" >Upgrade Class</button>
 			                            
 			                        </div>
 			                        
 			                    </div>
 			                    <?php echo form_close();?>

 			        </div>
 			    </div>
 			</div>
 		</div>
 	</div>

	<!-- Classes Upgradation school wise acknowledgement -->
 	<div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
		   		<div class="header">
		       		<h2>
						Transferred Schools Count <span class="badge bg-color-greenLight"><?php if(!empty($total_transfer_scl_count)) {?><?php echo $total_transfer_scl_count;?><?php } else {?><?php echo "0";?><?php }?></span>
					</h2>
					<ul class="header-dropdown m-r--5">
	                    <div class="button-demo">
	                    <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
	                    </div>
	                </ul>
				</div>
				<div class="body">
		       		<div class="table-responsive">
		           		<table class="table table-bordered table-striped table-hover dataTable js-exportable">
						
				        	<thead>
					            <tr>
				                    <th>School Name</th>
									<th>Previous year Count</th>
									<th>Transfered Students Count</th>
									<th>Passed Out Count</th>
									<th>Other Classes Count</th>
									<th>New Students Count</th>
					            </tr>
					        </thead>
							<tbody>
					        <?php foreach ($cls_trsnfer_counts as $key => $value):?>
								<tr>
									<td><?php echo ucwords($value["school"]) ;?></td>
									<td><?php echo $value["prev_yr_count"] ;?></td>
									<td><?php echo $value["current_yr_count"] ;?></td>
									<td><?php echo $value["passed_out_count"] ;?></td>
									<td><a href="<?php echo URL.'ttwreis_mgmt/get_trasferred_student_count'; ?>"><?php echo $value["other_cls_count"] ;?></a></td>
									<td><?php echo $value["newly_joined_count"] ;?></td>
								</tr>
							<?php endforeach;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

<!-- Claased Upgradation with excel -->
	

</section>
			
					
<!-- Modal -->
<div class="modal fade" id="import_waiting" tabindex="-1" role="dialog" >
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Import in progress</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" style="display: block; margin: 0 auto; width: 100px;">
					</div>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->	
											
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
	 	
?>
<script src="<?php echo(JS.'dynamic-add-import.js'); ?>" type="text/javascript"></script>
<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script> 
        function displayRadioValue() { 
            var ele = document.getElementsByName('gender'); 
              
            for(i = 0; i < ele.length; i++) { 
                if(ele[i].checked) 
                document.getElementById("result").innerHTML
                        = "Gender: "+ele[i].value; 
            } 
        } 
</script> 

<script>
//$(document).ready(function() {
<?php if($message) {?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Import Failed!",
				content : "<?php echo $message?>",
				color : "#C79121",
				iconSmall : "fa fa-bell bounce animated"
			});
<?php } ?>

	$("#file").prop('disabled', true);
//=========================== dt name =============================

/*For Clss Update*/
	$('#select_dt_name_upgrade').change(function(e){
		dist = $('#select_dt_name_upgrade').val();
		console.log(dist, "disttttt");
		//alert(dist);
		
		
		var options = $("#school_names");
		options.prop("disabled", true);
		options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Fetching schools list..."));
		$.ajax({
			url: 'get_schools_list',
			type: 'POST',
			data: {"dist_id" : dist},
			success: function (data) {			

				result = $.parseJSON(data);
				console.log(result)

				options.prop("disabled", false);
				$("#class_select").prop("disabled", false);
				options.empty();
				options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Select school"));
				$.each(result, function() {
				    options.append($("<option />").val(this.school_name).text(this.school_name));
				});				
						
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
		});
	});
/*For Clss Update*/
	$('#select_dt_name').change(function(e){
		dist = $('#select_dt_name').val();
		console.log(dist, "disttttt");
		//alert(dist);
		
		
		var options = $("#school_name");
		options.prop("disabled", true);
		options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Fetching schools list..."));
		$.ajax({
			url: 'get_schools_list',
			type: 'POST',
			data: {"dist_id" : dist},
			success: function (data) {			

				result = $.parseJSON(data);
				console.log(result)

				options.prop("disabled", false);
				$("#class_select").prop("disabled", false);
				options.empty();
				options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Select school"));
				$.each(result, function() {
				    options.append($("<option />").val(this.school_name).text(this.school_name));
				});				
						
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
		});
	});

	$('#school_name').change(function(e){
		school_name = $("#school_name option:selected").text();
		if(school_name.length != 0)
		{
			$('#file').prop('disabled', false);
		}
	});
	
	
	$('#class_select').change(function(e){
	school_name_sel = $('#school_name').val();
	class_sel = $('#class_select').val();
	$("#upgrade_sbt").prop("disabled", false);
	alert(school_name_sel);
	alert(class_sel);
	});
	
	
//=================================================================
//});
</script>



<?php 
	//include footer
	include("inc/footer_bar.php"); 
?>
