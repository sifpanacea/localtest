<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Manage Feedbacks";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["feedback"]["sub"]["manage_calendar"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL --><link href="<?php echo(CSS.'datepicker.css'); ?>" media="screen" rel="stylesheet" type="text/css" /><style>
.datepicker{ z-index: 100000 !important;}
</style>
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Feedbacks"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	<div class="row">
     				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-group"></i> </span>
							<h2><?php echo "List of feedbacks";?></h2>
		
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
					 <tbody>
					<?php if ($feedbacks): ?>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Form Status</th>
						<th>Created Time</th>
						<th>Action</th>
						
					</tr>
					<?php foreach ($feedbacks as $feedback=>$eachfeedback):?>
					<tr>
                    <tbody>
					<tr>
						<td class="name"><?php echo $eachfeedback['feedback_name'];?></td>
						<td class="desc"><?php echo $eachfeedback['feedback_desc'];?></td>
						<td  class="req_status"><?php echo $eachfeedback['req_status'];?></td>
						<td><?php echo $eachfeedback['req_time'];?></td>
						<td>
						<?php if($eachfeedback['req_status'] == 'Processed' || $eachfeedback['req_status'] == "Edited"){?>
							<?php echo '<a href="#" class="view_form" id="'.$eachfeedback['id'].'" events="feedback">View form</a>';?>
							<?php echo '<a href="#" class="user_form" id="'.$eachfeedback['id'].'" events="feedback">Use this form</a>';?>
							<?php }else
								{
									echo 'Form yet to be designed.';
								};
						?>
						</td>
						
					</tr>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo "No events created.";?>
        			</p>
        			<?php endif ?>
					</tbody>
								</table>
		
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
					</article>
        
        </div><!-- ROW -->
		<!-- /.view form modal -->
		<div class="modal fade bs-example-modal-sm" id="view_modal">
			<div class="modal-dialog" style="width:480px;height:550px;">
				<div class="modal-content">
					<div class="modal-header" style="padding:8px;">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><strong>View Form<strong></h4>
					</div>
					<div class="modal-body" id="modal-body">
						<!-- data will be added using AJAX-->
					</div>
					<div class="modal-footer" style="padding:8px;">
						<button type="button" class="btn btn-default btn-xs" id="comment">Comment</button>
						<button type="button" class="btn btn-default btn-xs" id="previous">Previous</button>
						<button type="button" class="btn btn-default btn-xs" id="next">Next</button>
					</div>	
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		
		<!-- /select users modal -->
		<div class="modal fade bs-example-modal-sm" id="user_modal">
			<div class="modal-dialog" style="width:480px;height:550px;">
				<div class="modal-content">
					<div class="modal-header" style="padding:8px;">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><strong>Select users<strong></h4>
					</div>
					<div class="modal-body" id="user-body">
						<!-- data will be added using AJAX-->
						<form class="users_form" method="post" action="save_feedback">
							<div class="form-group">
								<label for="select-users" class="control-label col-md-3">Select Users:</label>
								<select id="users" name="multiselect[]" multiple="multiple"></select>
								</div>
								<div class="form-group">
								<label class="control-label col-md-3">Expiry Date:</label>
								<input type="text" name="date" value="" class="datepicker hasDatepicker input-group" id="date" style="width:250px" readonly>
								</div>
								<input type="hidden" name="id" id="form_id" val=""/>
								<input type="hidden" name="name" id="form_name" val=""/>
								<input type="hidden" name="description" id="form_desc" val=""/>
								
							
						</form>			
					</div>
					<div class="modal-footer" style="padding:8px;">
						<button type="button" class="btn btn-default btn-xs" id="submit">submit</button>
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
<script src="<?php echo(JS.'bootstrap-datepicker.js');?>" type="text/javascript"></script>
<script>
nowDate = new Date();
	var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);	
	console.log(today)
$('#date').datepicker({
	startDate: today,
	todayHighlight:true,
	format:"yyyy-mm-dd"
})
	$(document).ready(function() {
	
		// PAGE RELATED SCRIPTS

 

		var prev_id;
		var id_;
		$(document).on('click','.view_form',function (e)
		{
			e.preventDefault()
			//alert("d");
			var id = $(this).attr('id');
			id_=id;
			//alert(prev_id);
			if(prev_id != id)
			{
				prev_id = id;
				var events = $(this).attr('events');
				$.ajax({
					url: '../printer/view_form_sub_admin/'+events+'/'+id,
					type: 'POST',
					async:true,
					timeout: 10000 ,
					beforeSend: function()
					{
						
					},
					success: function (data) 
					{
						var result_=JSON.parse(data);
						var image_length = result_.imag_str.length;
						$('#modal-body').empty();
						for(i=0;i<image_length;i++)
						{
							var j=i;
							j++;
							$('<div class="image_results" id="'+j+'"><img src="'+result_.imag_str[i].print_image+'" height="550" width="450"/></div>').appendTo('#modal-body');
							$('.image_results').hide();
							
						}
						var show_id = 1;
						show_dialog(show_id);
					},
					error: function (XMLHttpRequest, textStatus, errorThrown)
					{
					 console.log('error', errorThrown);
					}
				})//ajax end//GIFT-1132-YFR09-POAP GIFT-REGIS-OGKSV-MUBU
			
			}
			else
			{	
				var show_id = 1;
				$('.image_results').hide();
				show_dialog(show_id);
			}
			function show_dialog(show_id)
			{	
				$('#'+show_id+'').show();
				$('.image_results').removeClass("active");
				$('#'+show_id+'').addClass("active");
				$('#view_modal').modal("show");
			}

		})//view form end..
	
		$(document).on('click','#next',function()
		{
			var current_id = $('#modal-body').children('.active').attr('id');
			var image_div_length = $('#modal-body').children('div').length;
			//console.log(image_div_length)
			if(current_id < image_div_length)
			{
				$('.image_results').removeClass("active");
				$('#'+current_id+'').hide();
				$('#'+current_id+'').next('div').show();
				$('#'+current_id+'').next('div').addClass("active")
			}
			else
			{
				console.log("No more pages");
			}
		});//next end..

		$(document).on('click','#previous',function()
		{
			var current_id = $('#modal-body').children('.active').attr('id');
			if(current_id != "1")
			{
				$('.image_results').removeClass("active");
				$('#'+current_id+'').hide();
				$('#'+current_id+'').prev('div').show();
				$('#'+current_id+'').prev('div').addClass("active")
			}
			else
			{
				console.log("No more pages");
			}			
		});//previous end..		
		$(document).on('click','#comment',function()
		{	
			$('.image_results').hide();
			if($('.modal-body').children('div').hasClass('comments'))
			{
				$('#comment').hide()
				$('#previous').hide()
				$('#next').hide()
				$('.modal-body').children('.comments').show();
			}
			else
			{
			$('<div class="comments" id="comments"><div class="form-group"><label class="">Comment</label><textarea class="form-control" id="comments_text" rows="4" name="message"></textarea></div><div class="form-group"><button class="btn btn-default btn-xs pull-right" id="submit_comment">submit</button></div></div>').appendTo('.modal-body');
				$('#comment').hide()
				$('#previous').hide()
				$('#next').hide()
			}
		});
		$(document).on('click','#submit_comment',function()
		{
			var comment = $('#comments_text').val();
			$.ajax({
				url:'feedback_form_comment/'+id_+'/'+comment,
				type:'POST',
				success:function()
				{
					window.location.reload();
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)
				{
					console.log('error', errorThrown);
				}
				
			})
		})
		$(document).on('click','.user_form',function (e)
		{
			var form_id = $(this).attr('id')
			var that = $(this).parent('tr')
			console.log($(this).parents('tr').children('.name').html())
			var name = $(this).parents('tr').children('.name').html();
			var desc = $(this).parents('tr').children('.desc').html();
			$('#user_modal').modal("show");
			//$('#dp3').datepicker();
			//$('#date').datepicker({dateFormat: 'dd/mm/yy',minDate:'0',});
			$('#form_id').val(''+form_id+'');
			$('#form_name').val(''+name+'');
			$('#form_desc').val(''+desc+'');
		})
		var usersss = [];
		var userss = [];
		var users=[];
		var groupname=[];
		var grup=[];
		var grupss=[];
		var groupnamess=new Array();
		var grouplist={};

		$.ajax({
		url: 'get_group_list',
		type: 'POST',
		async:false,
		dataType:"json",
		success: function (data) {
			console.log(data)
			groupnamess=data;
			console.log("groupnameeeee",groupnamess);
		for(var i in groupnamess)
		{
		 grup[i]=groupnamess[i];
		 userss.push({
				label:groupnamess[i],
				value:groupnamess[i]
			  });
		 var current_name = groupnamess[i]
		  current_name = current_name.replace(/\s+/g, '');
		$('<optgroup label="'+groupnamess[i]+'" id="'+current_name+'">').appendTo('#users')
		grupss.push(groupnamess[i]);
		}
		for(var i in grupss)

		{
			//console.log("geeeeeeeeeeeeeeeeeeeeeeeeee",groupnamess[i]);
			 $.ajax({
				url: 'get_user_list',
				type: 'POST',
				async:false,
				dataType:"json",
				data:{'name':groupnamess[i]},	
				success: function (data) {
					console.log("55555555555555555555"+data);
					if(data!='[]')
					{
						var group=groupnamess[i];
						var current_grp = group.replace(/\s+/g, '');
						//console.log("cccccccccccccccccccccc"+group);
						grouplist[group]=new Array();
						//console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"+data);
						var usrs=data;
						for(var j in usrs)
						{
							grouplist[group].push(usrs[j]);
							$('<option value="'+usrs[j]+'">'+usrs[j]+'</option>').appendTo('#'+current_grp+'');
							//console.log("rrrrrrrrrrrrrrr"+usrs[j]);
						}
						//console.log('ooooooooooooooooooooooooooo', grouplist);
																
					}
				},
				error:function(XMLHttpRequest, textStatus, errorThrown)
				{
					console.log('error', errorThrown);
				}
			});	
			// console.log("eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee");
		}	

		users=userss;
		//console.log('successddddddd', users);
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
		 console.log('error', errorThrown);
		}
	});
		
	$("#users").multiselect({
			nonSelectedText: 'Select Users',
			enableClickableOptGroups:true,
			includeSelectAllOption: true,
			enableFiltering: true,
			enableCaseInsensitiveFiltering: true,
			maxHeight: 300,
			buttonWidth: '250px'
        });
	})
	$(document).on('click','#submit',function()
	{
		var usrs = $('#users').val()
		var dat = $('#date').val()
		if(dat!='' && usrs!=null)
		{
			$('.users_form').submit();
		}
		else
		{
			$.SmartMessageBox({
				title : "Alert !",
				content : "Date and users should not be empty",
				buttons : '[Ok]'
			    }, function(ButtonPressed) {
					if (ButtonPressed === "Ok") 
					{
					 
					}
				});
		}
	})
	
</script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>