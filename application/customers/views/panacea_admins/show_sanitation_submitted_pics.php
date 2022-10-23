<?php $current_page =""; ?>
<?php $main_nav =""; ?>
<?php include('inc/header_bar.php'); ?>
<!-- <?php //include('inc/sidebar.php'); ?> -->
<br>
<br>
<br>
<br>

<!-- <style type="text/css">
	img{

		height: 236px;
	}

	input[type="file"] {
  display: block;
}
.imageThumb {
  max-height: 75px;
  border: 2px solid;
  padding: 1px;
  cursor: pointer;
}
.pip {
  display: inline-block;
  margin: 10px 10px 0 0;
}
.remove {
  display: block;
  background: #444;
  border: 1px solid black;
  color: white;
  text-align: center;
  cursor: pointer;
}
.remove:hover {
  background: white;
  color: black;
}
</style> -->
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<section class="">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">




				<!-- row -->
		<?php if($pics): ?>
		<?php foreach($pics as $photo):?>
			<div class="row">
				<div class="col-md-12">
				<h2>
				<?php echo $photo['doc_data']['widget_data']['page4']['Declaration Information']['Date:']; ?></h2>

				<h2>Campus Photos</h2>

				<?php foreach($photo['doc_data']['widget_data']['daily']['Campus']['external_attachments'] as $show): ?>
				<div class="row">
				<div class="col-md-3"><a href="<?php echo URLCustomer.$show['file_path']; ?>"  rel="prettyPhoto[gal]">
					<img src="<?php echo URLCustomer.$show['file_path']; ?>" alt="" title="Sanitation Campus Photos"></a>
				</div>
				</div>
				<?php endforeach; ?>

				<h2>Kitchen Photos</h2>
				<?php foreach($photo['doc_data']['widget_data']['daily']['Kitchen']['external_attachments'] as $show): ?>
				<div class="row">
				<div class="col-md-3"><a href="<?php echo URLCustomer.$show['file_path']; ?>"  rel="prettyPhoto[gal]">
					<img src="<?php echo URLCustomer.$show['file_path']; ?>" alt="" title="Sanitation Kitchen Photos"></a>
				</div>
				<?php endforeach; ?>
				</div>

				<h2>Toilets Photos</h2>
				<?php foreach($photo['doc_data']['widget_data']['daily']['Toilets']['external_attachments'] as $show): ?>
				<div class="row">
				<div class="col-md-3"><a href="<?php echo URLCustomer.$show['file_path']; ?>"  rel="prettyPhoto[gal]">
					<img src="<?php echo URLCustomer.$show['file_path']; ?>" alt="" title="Sanitation Toilets Photos"></a>
				</div>
				</div>
				<?php endforeach; ?>

				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
		
		

	</div>
	<!-- END MAIN CONTENT -->

	

	</div>
</section>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<!-- END PAGE FOOTER -->

<?php 
	
	include("inc/footer_bar.php"); 
?>

<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>

<script src="<?php echo JS; ?>plugin/dropzone/dropzone.min.js"></script>
<script src="<?php echo JS; ?>sweetalert.min.js"></script>

<script type="text/javascript">
		
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		
		$(document).ready(function() {

				$("a[rel^='prettyPhoto']").prettyPhoto();

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
	            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
	            "<br/><span class=\"remove\">Remove image</span>" +
	            "</span>").insertAfter("#files");
		          $(".remove").on('click',function(){
		            $(this).parent(".pip").remove();
		          });
		        });
        		 fileReader.readAsDataURL(f);

		     /*var size = $("input:file")[0].files[j].size;
			 if(size > 2000000 )
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
				
			 }*/
		    }
		     $("input:file").html("#files");
		 	
		 	//var files = $(".imageThumb").array(); 
		});

		}	
	
		<?php if($this->session->flashdata('success')): ?>

        	 swal({
                title: "Good job!",
                text: "<?php echo $this->session->flashdata('success'); ?>",
                icon: "success",
    
         	 });
      		 <?php elseif($this->session->flashdata('failed')): ?>
       		swal({
                title: "Failed!",
                text: "<?php echo $this->session->flashdata('failed'); ?>",
                icon: "error",
    
         	 });
			<?php endif; ?>

		})

</script>
<!-- PAGE FOOTER -->


