<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Import Medical Certificates";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["masters"]["sub"]["import_medical_test"]["active"] = true;
include("inc/nav.php");

?>
<style>
.logo
{
	margin-left:10px;
	
	height:80px;
	width:90px;
	background-repeat: no-repeat;
	background-size:100%;
	border:1px dashed lightgrey;
}


#click_upload
{
	background-color:rgb(80, 77, 77);
	color: white;
	font-size: 12px;
	margin-top:60px;
}
#edit_photo
{
	border:none;
	height : 29px;
	margin-top : 10px;
	
}
</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
<?php
//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
//$breadcrumbs["New Crumb"] => "https://url.com"
include("inc/ribbon.php");
?>

<!-- MAIN CONTENT -->
<div id="content">

<div class="row">
<!-- widget div-->
<div>

<!-- widget edit box -->
<div class="jarviswidget-editbox">
	<!-- This area used as dropdown edit box -->

</div>
<!-- end widget edit box -->
	 <!-- NEW WIDGET START -->
<article class="col-sm-12 col-md-8">

<?php if ($docs): ?>
<!-- Widget ID (each widget will need unique ID)-->
<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-editbutton="false">
<header>
<span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
<h2>Matched Document(s) <span class="badge bg-color-greenLight"><?php if(!empty($docscount)) {?><?php echo $docscount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>

</header>

<!-- widget div-->
<div>

<!-- widget edit box -->
<div class="jarviswidget-editbox">
	<!-- This area used as dropdown edit box -->

</div>
<!-- end widget edit box -->

<!-- widget content -->
  <?php
	$attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
	echo  form_open_multipart('tswreis_schools/import_medical_certificates',$attributes);
	?>

	<div class="tree smart-form">
	<ul>
		<?php foreach ($docs as $doc):?>
	
		<?php if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]) && isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"])):?>
		<li>
				<span><i class="fa fa-lg fa-folder-open"></i>&nbsp;<?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Name'];?></span>
				
				<ul>
				<li>
						<span class="label label-primary"><i class="fa fa-lg fa-minus-circle"></i> Personal Information</span>
						<ul>
							<li style="">
								<div class="well well-sm ">
								<table id="dt_basic" class="table table-striped table-bordered table-hover">
				                    <tbody>
									<tr>
										<th>Name</th><td><i class="icon-leaf">
											<?php if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Name']) && !empty($doc['doc_data']['widget_data']['page1']['Personal Information']['Name'])):?><?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Name']?><?php else:?><?php echo "Name not available";?><?php endif;?></i></td>
										
									</tr>
									<tr>
										<th>Mobile</th><td><i class="icon-leaf">
											<?php if(isset($doc['doc_data']['widget_data']["page1"]['Personal Information']['Mobile']['mob_num']) && !empty($doc['doc_data']['widget_data']["page1"]['Personal Information']['Mobile']['mob_num'])):?>
												<?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Mobile']['mob_num']?><?php else:?><?php echo "Mobile Number not available";?><?php endIf;?></i></td>
									</tr>
									
									
									<tr>
										<th>Hospital Unique ID</th><td colspan="2"><i class="icon-leaf"><input type="text" class="hidden" name="unique_id" value="<?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];?>"/><?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];?></i></td>
									</tr>
									
									<tr>
										<th>School Name</th><td colspan="2"><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'];?></i></td>
									</tr>
									<tr>
										<th>Class</th><td colspan="2"><i class="icon-leaf">
											<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Class'];?></i></td>
									</tr>
									<tr>
										<th>Section</th><td colspan="2"><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Section'];?></i></td>
									</tr>
									<tr>
										<th>Father Name</th><td colspan="2"><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Father Name'];?></i></td>
									</tr>
									
									</tbody>
								</table>
								<fieldset class="demo-switcher-1">
                            	<div class="panel panel-default">
                           		 <div class="panel-heading  text-center"><strong>Medical Certificates</strong></div>
                          	  <div class="form-group ">
                              
                         
                          <input type="file" id="files"  name="hs_req_attachments[]" style="display:none;" multiple>
                            <label for="files" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                               Browse.....
                           </label>
                           <input type="hidden" id="doc_id"  name="doc_id" value="<?php echo $doc['doc_properties']['doc_id'];?>">
                          </div>
                   
                          </div>
                        
                        </fieldset>
								</div>
							</li>							
						</ul>
					</li>
					</ul>
					
					
					<?php endIf;?>
					
					<?php endforeach;?>
					</li>
						</ul>
						
<button type="button" class="btn btn-primary pull-right btn-sm" onclick="window.history.back();"style="margin-right:15px">Back</button>
<button type="submit" class="btn btn-success pull-right btn-sm" style="margin-right:6px">Update</button> <br><br><br>
</div>				
</div>
<!-- end widget content -->

</div><!-- ROW -->
<?php else: ?>
	<p>
		<?php echo "Searching value is not found.";?>
	</p>
	
	<button type="button" class="btn btn-primary pull-right btn-sm" onclick="window.history.back();">Back</button><br>
	<?php endif ?>
	
<div>

			
</div>		

</div>

<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
//include required scripts
include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S)--> 

<script type="text/javascript">



$(document).ready(function() {

$('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
$('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Collapse this branch').on('click', function(e) {
var children = $(this).parent('li.parent_li').find(' > ul > li');
if (children.is(':visible')) {
children.hide('fast');
$(this).attr('title', 'Expand this branch').find(' > i').removeClass().addClass('fa fa-lg fa-plus-circle');
} else {
children.show('fast');
$(this).attr('title', 'Collapse this branch').find(' > i').removeClass().addClass('fa fa-lg fa-minus-circle');
}
e.stopPropagation();
});			

//$("#zoom_id").zoomify({zooming:true})
 

	//$('#zoom_id').zoomify({zooming:true});
//$(".logo_img").zoomify({zooming:true})

	/* $(document).on('click','.photo_upload',function()
	{
		$("#upload_photo").trigger('click')
		console.log('photo');
	}) */
    

		//uploading the logo in app creation //
	

	
				/* $.smallBox({
					title : "James Simmons liked your comment",
					content : "<i class='fa fa-clock-o'></i> <i>2 seconds ago...</i>",
					color : "#296191",
					iconSmall : "fa fa-thumbs-up bounce animated",
					timeout : 4000
				}); */
				if (window.File && window.FileList && window.FileReader) 
		{
				
		//var numFiles = $("input:file")[0].files.length;
		 $("#files").on("change", function(e) {
			$(".remove").parent(".pip").remove();
			var files = e.target.files,
	        filesLength = files.length;
	        console.log('filesLength',filesLength);
		    for(var j=0;j<filesLength;j++)
		    {
		    	var f = files[j]
		        var fileReader = new FileReader();
		        fileReader.onload = (function(e) {
		          var file = e.target;
		          $("<span   class=\"pip\">" +
	            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" style=\"width:130px;height:130px\" title=\"" + file.name + "\"/>" +
	            "<br/><span class=\"remove\">Remove image</span>" +
	            "</span>").insertAfter("#files");
		          $(".remove").on('click',function(){
		            $(this).parent(".pip").remove();
		          });
		        });
        		 fileReader.readAsDataURL(f);

		     var size = $("input:file")[0].files[j].size;
			 /*if(size > 2000000 )
			 {

		        $.bigBox({
					title   : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message !",
					content : "Attach file less than 2 MB !",
					color   : "#C46A69",
					icon    : "fa fa-warning shake animated",
					timeout : 8000
			    });
			      
				
				e.preventDefault();
				$("input:file").val(""); 
				var no_of_files = $("input:file")[0].files.length;
			    var count = no_of_files+' files attached';
			    $('.file_attach_count').text(count);
          		$(this).removeClass("hide");
          		
				break;
				
			 }*//*else{
			 	 fileReader.onload = (function(e) {
		          var file = e.target;
		          $("<span   class=\"pip\">" +
	            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" style=\"width:130px;height:130px\" title=\"" + file.name + "\"/>" +
	            "<br/><span class=\"remove\">Remove image</span>" +
	            "</span>").insertAfter("#files");
		          $(".remove").on('click',function(){
		            $(this).parent(".pip").remove();
		          });
		        });
			 }*/
		    }
		     $("input:file").html("#files");
		 	
		 	//var files = $(".imageThumb").array(); 
		});

		}


			
})





</script>
<!--<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<?php 
//include footer
include("inc/footer.php"); 
?>