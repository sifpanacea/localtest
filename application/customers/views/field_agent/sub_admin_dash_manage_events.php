<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Manage Events";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["events"]["sub"]["manage_calendar"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Events"] = "";
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
							<h2><?php echo "List of events";?></h2>
		
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
					
					<?php if ($events): ?>
					<tr>
						<th>Name</th>
						<th>Description</th>
						<th>Form Status</th>
						<th>Created Time</th>
						<th>Action</th>
						
					</tr>
					<?php foreach ($events as $event=>$eachevent):?>
					<tr>
                    <tbody>
					<tr>
						<td><?php echo $eachevent['event_name'];?></td>
						<td><?php echo $eachevent['event_desc'];?></td>
						<td><?php echo $eachevent['req_status'];?></td>
						<td><?php echo $eachevent['req_time'];?></td>
						<td>
						<?php if($eachevent['req_status'] == 'Processed' || $eachevent['req_status'] == "Edited")
							{?>
							<a href="" class="view_form" id="<?php echo $eachevent['id'];?>" events="events">View form</a>
							<?php echo anchor("sub_admin/event_use/".$eachevent['id'], 'Use this form.');?>
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
								<div class="view_images"></div>
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
					</article>
        
        </div><!-- ROW -->
		<div class="modal fade bs-example-modal-sm" id="view_modal">
			<div class="modal-dialog" style="width:480px;height:550px;">
				<div class="modal-content">
					<div class="modal-header" style="padding:8px;">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><strong>View Form<strong></h4>
					</div>
					<div class="modal-body">
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

<script>

	$(document).ready(function() {
		// PAGE RELATED SCRIPTS
		var prev_id;
		var id_;
		$(document).on('click','.view_form',function (e)
		{
			e.preventDefault()
			
			var id = $(this).attr('id');
			id_=id;
			
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
						//console.log(result_)
						var image_length = result_.imag_str.length;
						$('.modal-body').empty();
						for(i=0;i<image_length;i++)
						{
							var j=i;
							j++;
							$('<div class="image_results" id="'+j+'"><img src="'+result_.imag_str[i].print_image+'" height="550" width="450"/></div>').appendTo('.modal-body');
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
			var current_id = $('.modal-body').children('.active').attr('id');
			var image_div_length = $('.modal-body').children('div').length;
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
			var current_id = $('.modal-body').children('.active').attr('id');
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
				url:'event_form_comment',
				type:'POST',
				data:{'id':id_,'comments':comment},
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
	})

</script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>