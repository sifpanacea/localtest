<?php $current_page = ""; ?>
<?php $main_nav = ""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet" />

<section class="content">
	<div class="container-fluid">
	    <div class="block-header">
	        <h2>Parents Registration Details</h2>
	    </div>
	    <!-- Modal Size Example -->
	    <div class="row clearfix">
	        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	            <div class="card">
	                <div class="header">
	                     <div class="row clearfix">
	                         <div class="col-sm-2">
	                            <h3 class="font-bold col-green">Parent Info</h3>
	                         </div>
	                         <div class="col-sm-2 pull-right">
	                            <ul class="header-dropdown m-r--5">
	                               <li><button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button></li>
	                           </ul>
	                         </div>
	                     </div>
	                </div>
	                <div class="body">
	                	<div class="row clearfix">
	                	    <div class="col-sm-2">
	                	     <!--  <?php $end_date  //= date ( "Y-m-d", strtotime ( date('yy-m-d') . "-90 days" ) ); ?> -->
	                	         <span id="monitoring_datepicker">
	                	          Start Date :
	                	         <input type="text" id="passing_date" name="passing_date" class="form-control date" value="<?php echo date('yy-m-d'); ?>">
	                	         </span>
	                	    </div>
	                	    <div class="col-sm-2">
	                	         <span id="monitoring_datepicker">
	                	         End Date :
	                	         <input type="text" id="passing_end_date" name="passing_end_date" class="form-control date" value="<?php echo date('yy-m-d'); ?>">
	                	         </span>
	                	    </div>
	                	    <div class="col-sm-2">
                                <label>Select District</label>
                                 <select id="select_dt_name" class="form-control">
                                    <option value="All" selected="">All</option>
                                    <?php if(isset($distslist)): ?>
                                   
                                        <?php foreach ($distslist as $dist):?>
                                        <option value='<?php echo $dist['dt_name']; ?>' ><?php echo ucfirst($dist['dt_name'])?></option>
                                        <?php endforeach;?>
                                        <?php else: ?>
                                        <option value="1"  disabled="">No District entered yet</option>
                                    <?php endif ?>
                                </select>
                            </div>
	                	    <div class="col-sm-2"> 
	                	        <label>Select School</label>                                 
	                	        <select class="form-control show-tick" id="school_name" disabled=true >
	                	            <option value="All"  selected="">All</option>
	                	        </select>
	                	    </div>
	                	     <div class="col-sm-2">
                               	<div class="form-line">
                                    <button type="button" id="date_set" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
                                    <i class="material-icons">search</i>
                                    </button>
                               	</div>
	                        </div>
	                        <div class="col-sm-2">
	                        	<div class="form-line">
	                        		<button type="button" id="get_excel" class="btn bg-green btn-sm waves-effect">Get Excel</button>
	                        	</div>
	                        </div>
	                	 </div>
	                	 
	                    	<div id="Registartion_details_table"></div>
	                    
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</section>

<form style="display: hidden" action="<?php echo URL;?>panacea_mgmt/change_status_to_remove_from_list" method="POST" id="submit_for_close">
	<input type="hidden" name="ids" id="ids" value="">
</form>

<?php //include('inc/footer_bar.php') ?>

<!-- Jquery Core Js -->
    <script src="<?php echo(MDB_PLUGINS.'jquery/jquery.min.js'); ?>"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo(MDB_PLUGINS.'bootstrap/js/bootstrap.js'); ?>"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'jquery-slimscroll/jquery.slimscroll.js'); ?>"></script>

    <!-- Bootstrap Notify Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-notify/bootstrap-notify.js'); ?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'node-waves/waves.js'); ?>"></script>
 
  <!-- Jquery DataTable Plugin Js -->
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/jquery.dataTables.js"; ?>'></script>
    <script src="<?php echo MDB_PLUGINS."jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"; ?>"></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/dataTables.buttons.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.flash.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/jszip.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/pdfmake.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/vfs_fonts.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.html5.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.print.min.js"; ?>'></script>

    <script src="<?php echo(MDB_JS.'admin.js'); ?>"></script>
    <script src='<?php echo MDB_JS."pages/tables/jquery-datatable.js"; ?>'></script>
    <script src='<?php echo MDB_JS."pages/ui/modals.js"; ?>' ></script>

    <!-- Demo Js -->
    <script src="<?php echo(MDB_JS.'demo.js'); ?>"></script>
    <!-- Moment Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'momentjs/moment.js'); ?>"></script>
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script>
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script src="<?php echo MDB_PLUGINS.'bootstrap-notify/bootstrap-notify.js'; ?>"></script>

<script type="text/javascript">

	get_data_of_otp();

	setTimeout(function(){
	   window.location.reload(1);
	}, 10000);
	

	$('#date_set').click(function(){
		get_data_of_otp();
	});

	$('#get_excel').click(function(){
		var dist = $('#select_dt_name').val();
		var scl = $('#school_name').val();
		var start = $('#passing_date').val();
		var end = $('#passing_end_date').val();
		//alert(dist);

		$.ajax({

			//url:'get_excel_for_students_nos',
			url:'get_excel_for_registered_parents',
			type:'POST',
			data:{'dist_name':dist, 'school':scl, 'start_date':start, 'end_date':end},
			success : function(data){
                console.log(data);
                window.location = data;
            },
            error:function(XMLHttpRequest, textStatus, errorThrown)
            {
             console.log('error', errorThrown);
            }
		});
	});


	var today_date = $('#passing_end_date').val();
    $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#passing_end_date').change(function(e){
            today_date = $('#passing_end_date').val();;
    });

    var date = $('#set_date').val();
    
    $('#set_date').change(function(e){
        date = $('#set_date').val();
    });

    $('#select_dt_name').change(function(e){
        /*var datas = $('#select_dt_name').val();
         alert(datas);*/
        dist = $('#select_dt_name').val();
        dt_name = $("#select_dt_name option:selected").text();
        //alert(dist);
        var options = $("#school_name");
        options.prop("disabled", true);
       
        options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Fetching schools list..."));
        $.ajax({
            url: 'get_schools_list_with_dist_name',  //'get_schools_list',
            type: 'POST',
            data: {"dist_id" : dist},
            success: function (data) {          

                result = $.parseJSON(data);
               // console.log(result)

                options.prop("disabled", false);
                options.empty();
                options.append($("<option />").val("All").prop("selected", true).text("All"));
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

	
	function get_data_of_otp(){

		var dist = $('#select_dt_name').val();
		var scl = $('#school_name').val();
		var start = $('#passing_date').val();
		var end = $('#passing_end_date').val();

		$.ajax({
			url:'get_parents_data_to_fill',
			type:'POST',
			data:{'dist_name':dist, 'school_name':scl, 'start_date':start, 'end_date':end},
			success:function(data){
				var result = $.parseJSON(data);
				console.log(result);
				table_to_show_data(result);
			}
		});
	};

	function table_to_show_data(result){

		if(result == 'No Data Available'){
			$('#Registartion_details_table').html('<h4>No Data Available</h4>');
		}else{

			data_table = '<table class="table table-bordered" id="more_requests"><thead><tr><th class="hide">id</th><th>Student ID</th><th>Student Name</th><th>Mobile Number</th><th>District</th><th>School Name</th><th>OTP</th><th>Status</th></tr></thead><tbody>';

			$.each(result, function(){
				data_table = data_table+'<tr>';
				var id= Object.values(this['_id']);
				data_table = data_table+'<td class="hide">'+id+'</td>';
				data_table = data_table+'<td>'+this.doc_data['Hospital Unique ID']+'</td>';
				data_table = data_table+'<td>'+this.doc_data['Student Name']+'</td>';
				data_table = data_table+'<td>'+this.doc_data['Mobile No']+'</td>';
				data_table = data_table+'<td>'+this.doc_data['District']+'</td>';
				data_table = data_table+'<td>'+this.doc_data['School Name']+'</td>';
				data_table = data_table+'<td>'+this.doc_data['Otp']+'</td>';

				var status = this.doc_data['Status'];
				if(status == 1){
					data_table = data_table+'<td><button type="button" class="btn bg-red waves-effect close_case"><i class="material-icons">close</i></button></td>';
				}else{
					data_table = data_table+'<td><span class="badge bg-green">Authenticated</span></td>';
				}
				
				data_table = data_table+'</tr>';
			});

			data_table = data_table+'</tbody></table>';

			$('#Registartion_details_table').html(data_table);

		}


		$('#more_requests').DataTable({
	    "paging": true,
	    "lengthMenu" : [10, 25, 50, 75, 100]
	  });

		
			$('#more_requests').each(function(){
				$('.close_case').click(function(){
				var currentRow = $(this).closest("tr");
                var msg_id = currentRow.find("td:eq(0)").text();
               
                $("#ids").val(msg_id);
                $("#submit_for_close").submit();
			});
		});

	}


</script>