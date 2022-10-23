<?php $current_page = "social_network"; ?>
<?php $main_nav = ""; ?>
<?php include("inc/header_bar.php"); ?>
<?php include("inc/sidebar.php"); ?>

<section class="content">
	<div class="container-fluid">
		<div class="block-header">
            <h2>Social Network</h2>
        </div>
		
		<div class="row clearfix">
		    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		      	<div class="card">
			        <div class="header">
			            <button type="button" class="btn bg-pink waves-effect" data-toggle="tooltip" data-placement="top" title="Back" onclick="window.history.back();"><i class="material-icons">arrow_back</i></button>
			        </div>
			        <div class="body">
			            <div class="row clearfix">
			              <div class="col-md-4">
			                  <a class="twitter-timeline" data-width="400" data-height="400" data-theme="white" href="https://twitter.com/RSPraveenSwaero?ref_src=twsrc%5Etfw">Tweets by RSPraveenSwaero</a>
			              </div>
			              <div class="col-md-4">
			                  <iframe width="500" height="380" src="https://www.youtube-nocookie.com/embed/wdiJkpm3vlo" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			              </div>
			              <div class="col-md-4"></div>
			            </div>
			        </div>
		      	</div>
		    </div>
		</div>
	</div>
</section>

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>


<?php include("inc/footer_bar.php"); ?>