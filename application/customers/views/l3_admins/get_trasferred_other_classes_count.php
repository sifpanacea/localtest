<?php $current_page = ""; ?>
<?php $main_nav = ""; ?>
<?php include("inc/header_bar.php"); ?>
<?php include("inc/sidebar.php"); ?>

<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card"> 
                <div class="header">
                	<h2>Classes Transffered Student</h2>
                	<ul class="header-dropdown m-r--5">
					    <div class="button-demo">
					    	<button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
					    </div>
					</ul>
                </div>
                <div class="body">
                	<div class="row clearfix">
				    	
				        	<div class="col-sm-3">
				        		<label>Select District</label>
					            <select class="form-control show-tick" id="select_dt_name" name="select_dt_name" >
				                    <option value="" selected="" disabled="">Select a district</option>
										<?php if(isset($distslist)): ?>
										<?php foreach ($distslist as $dist):?>
										<option value='<?php echo $dist['_id']?>' ><?php echo ucfirst($dist['dt_name'])?></option>
										<?php endforeach;?>
										<?php else: ?>
										<option value="1"  disabled="">No district entered yet</option>
										<?php endif ?>
								</select><i></i><br>
								
							</div>
							<div class="col-sm-3">

								<label>Select School</label>
								<select class="form-control show-tick"  id="school_name" name="school_name" disabled=true>
								      <option value="0" selected="" disabled="">Select a district first</option>
								</select><i></i>
							</div>
						
					</div>
					<div class="row clearfix">
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
					        
								<tr>
									<td>bcwelfare</td>
									<td>2019</td>
									<td>23</td>
									<td>56</td>
									<td>45</td>
									<td>89</td>
									
								</tr>
							
							</tbody>
						</table>
					</div>
					</div>
                	
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('inc/footer_bar.php')?>

<script type="text/javascript">
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

</script>