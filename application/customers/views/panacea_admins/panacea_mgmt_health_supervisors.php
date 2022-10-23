<?php $current_page="Manage_Health_Supervisors";?>
<?php $main_nav="Masters";?>
<?php 
include('inc/header_bar.php');
include('inc/sidebar.php');
?>

<section class="content">
        <div class="container-fluid">
            <div class="block-header">
               <!--  <h2>BASIC FORM ELEMENTS</h2> -->
            </div>
            <!-- Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                CREATE NEW HEALTH SUPERVISOR
                                
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <div class="button-demo">
                                <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                                </div>
                            </ul>
                        </div>
                        <div class="body">
                            <?php
                            $attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
                            echo  form_open('panacea_mgmt/create_health_supervisors',$attributes);
                            ?>
                                                       
                            <h2 class="card-inside-title">Please Enter The Health Supervisors Information</h2>
                            
                            <div class="row clearfix">
                            	<div class="col-sm-3">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="number" class="form-control" placeholder="School Code" name="school_code" id="school_code" value="<?PHP echo set_value('school_code'); ?>" required />
                                        </div>
                                    </div>
                                </div>                                
                                <div class="col-sm-3">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="text" class="form-control" placeholder="HealthSupervisors Name" name="health_supervisors_name" id="health_supervisors_name" value="<?PHP echo set_value('health_supervisors_name'); ?>" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="form-line">                                        	
                                            <input type="number" class="form-control" placeholder="Mobile Number" name="health_supervisors_mob" id="health_supervisors_mob" value="<?PHP echo set_value('health_supervisors_mob'); ?>" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="form-line">                                        	
                                            <input type="number" class="form-control" placeholder="Phone Number" name="health_supervisors_ph" id="health_supervisors_ph" value="<?PHP echo set_value('health_supervisors_ph'); ?>" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="text" class="form-control" placeholder="Password" name="health_supervisors_password" id="health_supervisors_password" value="<?PHP echo set_value('health_supervisors_password'); ?>" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="email" class="form-control" placeholder="Email" name="health_supervisors_email" id="health_supervisors_email" value="<?PHP echo set_value('health_supervisors_email'); ?>" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea id="health_supervisors_addr" class="form-control" placeholder="Address" name="health_supervisors_addr" class="custom-scroll" ><?php echo set_value('health_supervisors_addr');?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="button-demo">                            	
                        		<button type="reset" class="btn bg-indigo waves-effect">CLEAR</button>
                        		<button type="submit" class="btn bg-light-green waves-effect">CREATE</button>
                        	</div>
                            <?php echo form_close(); ?>               
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              All Health Supervisors <span class="badge bg-color-greenLight"><?php if(!empty($health_supervisorscount)) {?><?php echo $health_supervisorscount;?><?php } else {?><?php echo "0";?><?php }?></span>		
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
							        <thead>
                                         
                                        <tr>
                                            <th>School Code</th>
                                            <th>HealthSupervisors Name</th>
                                            <th>Mobile Number</th>
                                            <th>Phone Number</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($health_supervisors as $hs):?>
                                    <tr>
                                        <td><?php echo ucwords($hs["school_code"]) ;?></td>
                                        <td><?php echo ucwords($hs["hs_name"]) ;?></td>
                                        <td><?php echo $hs["hs_mob"] ;?></td>
                                        <td><?php echo $hs["hs_ph"] ;?></td>
                                        <td><?php echo $hs["email"] ;?></td>
                                        <td><?php echo ucwords($hs["hs_addr"]) ;?></td>
                                        <td><?php //echo anchor("panacea_mgmt/panacea_mgmt_manage_states/".$hs['_id'], lang('app_edit')) ;?>
                                        
                                        <a class='ldelete' href='<?php echo URL."panacea_mgmt/panacea_mgmt_delete_health_supervisors/".$hs['_id'];?>'>
                                            <?php echo lang('app_delete')?>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                   
                                    </tbody>																			
								</table>
							</div>		
						</div>		
					</div>					
				</div>
			</div>	
	</div>
</section>

<?php 
    //include required scripts
    include("inc/scripts.php"); 
?>


<script>
$(document).ready(function() {
<?php if($message) {?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message!",
				content : "<?php echo $message?>",
				color : "#C79121",
				iconSmall : "fa fa-bell bounce animated"
			});
 <?php } ?> 

/* // DOM Position key index //
		
	l - Length changing (dropdown)
	f - Filtering input (search)
	t - The Table! (datatable)
	i - Information (records)
	p - Pagination (paging)
	r - pRocessing 
	< and > - div elements
	<"#id" and > - div with an id
	<"class" and > - div with a class
	<"#id.class" and > - div with an id and class
	
	Also see: http://legacy.datatables.net/usage/features
	*/	

	/* BASIC ;*/
		var responsiveHelper_dt_basic = undefined;
		var responsiveHelper_datatable_fixed_column = undefined;
		var responsiveHelper_datatable_col_reorder = undefined;
		var responsiveHelper_datatable_tabletools = undefined;
		
		var breakpointDefinition = {
			tablet : 1024,
			phone : 480
		};

		$('#dt_basic').dataTable({
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_dt_basic.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_dt_basic.respond();
			}
		});

	/* END BASIC */
	var js_url = "<?php echo JS; ?>";
	/* COLUMN FILTER  */
    var otable = $('#datatable_fixed_column').DataTable({
    	//"bFilter": false,
    	//"bInfo": false,
    	//"bLengthChange": false
    	//"bAutoWidth": false,
    	//"bPaginate": false,
    	//"bStateSave": true, // saves sort state using localStorage
		"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6'T>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12'i><'col-sm-6 col-xs-12'p>>",
		 "oTableTools": {
        	 "aButtons": [
        	      {
                 "sExtends": "xls",
                 "sTitle": "TLSTEC HS Report",
                 "sPdfMessage": "TLSTEC HS Excel Export",
                 "sPdfSize": "letter"
	             },
	          	{
	             	"sExtends": "print",
	             	"sMessage": "TLSTEC Schools Printout <i>(press Esc to close)</i>"
	         	}],
        	 "sSwfPath": js_url+"datatables/swf/copy_csv_xls_pdf.swf"
        },
		"autoWidth" : true,
		"preDrawCallback" : function() {
			// Initialize the responsive datatables helper once.
			if (!responsiveHelper_datatable_fixed_column) {
				responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
			}
		},
		"rowCallback" : function(nRow) {
			responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
		},
		"drawCallback" : function(oSettings) {
			responsiveHelper_datatable_fixed_column.respond();
		}		
	
    });
    
    // custom toolbar
    //$("div.toolbar").html('<div class="text-right"><img src="img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');
    	   
    // Apply the filter
    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
    	
        otable
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
            
    } );
    /* END COLUMN FILTER */   


});
</script> 
<?php 
	//include footer
	include('inc/footer_bar.php'); 
?>