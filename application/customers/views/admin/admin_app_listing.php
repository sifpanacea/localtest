<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "User Application";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["users"]["sub"]["user management"]["sub"]["useapp"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Users Management"] = "";
		$breadcrumbs["User Management"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
     				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<?php echo sprintf('Application used by \'%s\'', str_replace('_', '@',$user));?>
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
					<?php if ($apps): ?>
					<tr>
						<th>Apps name</th>
						<th>Status</th>
					</tr>
					<?php foreach ($apps as $app):?>
                    <tbody>
					<tr>
						<td><?php echo $app['app_name'];?></td>
						<td id="<?php echo new MongoId($app['_id']);?>_td"><?php echo $app['status'];?></td>
						
                        <td id="<?php echo new MongoId($app['_id']);?>_a"><a class='delete' onclick='change("<?php echo new MongoId($app['_id']);?>","<?php echo $user;?>","<?php echo $app['status'];?>")'>
                			<button class="btn btn-default btn-xs">Change</button>
                			</a>
                		</td>
					</tr>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo lang('admin_no_users');?>
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
				

	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<script type="text/javascript">
<!--

//-->
change = function (_id,user,status){

	 $.ajax({
			url: '../app_status',
			type: 'POST',
			dataType:"json",
			data:{_id:_id,user:user,status:status},	
			success: function (data) {
				console.log(data);
				$.smallBox({
					title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
					content : data.message,
					color : "#296191",
					iconSmall : "fa fa-bell bounce animated",
					timeout : 4000
				});

				if(data.reply == true){
					if(status == 'new'){
						status = 'processed';
					}else{
						status = 'new';
					}
					
					console.log(_id);
					$('#'+_id+'_td').html(status);
					
					console.log(_id);
					$('#'+_id+'_a').html("<a class='delete' onclick='change(\"" +_id+ "\",\""+user+"\",\""+status+"\")'><button class='btn btn-default btn-xs'>Change</button></a>");
				}
				
			},
 		error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 	console.log('error', errorThrown);
 		}
		});	
};

</script>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->

<?php 
	//include footer
	include("inc/footer.php"); 
?>