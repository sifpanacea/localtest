<?php
$page_css[] = "your_style.css";
$no_main_header = true;
$page_body_prop = array("id"=>"login", "class"=>"animated fadeInDown");
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Panacea School Health Program</title>

  <!-- Font Awesome Icons -->
 <link rel="shortcut icon" type="image/ico" href="<?php echo (IMG.'Panacea_small.png') ?>"/>
  <link href="<?php echo JS; ?>landing_page_js/fontawesome-free/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>

  <!-- Plugin CSS -->
  <link href="<?php echo JS; ?>landing_page_js/magnific-popup/magnific-popup.css" rel="stylesheet">
  <!-- Theme CSS - Includes Bootstrap -->
  
  <link href="<?php echo CSS; ?>landing_page_css/creative.min.css" rel="stylesheet">
</head>
<style type="text/css">
  header.masthead h1
{
  font-size:3rem;
}
</style>

<body id="page-top">

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
    <div class="container">
       <a class="navbar-brand js-scroll-trigger" href="#page-top"><img class="pull-left rounded-circle"  style="height: 90px;border-radius: 30px" src="<?php echo IMG; ?>TELANGANA.png" alt="" title="" /></a>

      <a class="navbar-brand js-scroll-trigger" href="#page-top"><img class="pull-left"  style="height: 100px;margin-top: 10px" src="<?php echo IMG; ?>PANACEA_LOGO_NEW.jpg" alt="" title="" /></a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto my-2 my-lg-0">
         <li class="nav-item">
            <a href="" target="_blank"> <img class="rounded-circle pull-right" style="height: 82px;margin-top: 20px;" src="<?php echo IMG; ?>Panacea_LOGO_Final.jpg" alt="" title="" /></a>
          </li>
          <li class="nav-item">
            <a href="" target="_blank"> <img class="rounded-circle pull-right" style="height: 100px;margin-top: 20px;margin-left: -6px;" src="<?php echo IMG; ?>SYNERGY.png" alt="" title="" /></a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Masthead -->
  <header class="masthead" style="background-image: url('<?php echo IMG; ?>telangana-residential-schools.png');">
    <div class="container h-100">
      <div class="row h-100 align-items-center justify-content-center text-center">
        <div class="col-lg-10 align-self-end">
          <h1 class="text-uppercase text-white font-weight-bold">PANACEA SCHOOL HEALTH PROGRAM</h1>
          <h1 class="text-uppercase text-white font-weight-bold">TELANGANA</h1>
          <hr class="divider my-4">
        </div>
        <div class="col-lg-8 align-self-baseline">
          <p class="text-white-75 font-weight-light mb-5"><!--Start Bootstrap can help you build better websites using the Bootstrap framework! Just download a theme and start customizing, no strings attached! -->
          </p>
          <a class="btn btn-info btn-xl js-scroll-trigger" href="#login">Sign In</a>
        </div>
      </div>
    </div>
  </header>


  <!-- About Section -->
  <section class="page-section" id="login" style="padding: 2rem 0">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
          <h2> Login here..</h2>
          <?php
             $attributes = array('class' => 'smart-form client-form', 'id' => 'login-form');

                 echo  form_open('auth/login',$attributes);
            ?>
              <input class="form-control login" type="email" name="identity" id="identity" placeholder="Username" required="required"><br>
              <input class="form-control login" type="password" name="password" id="password" placeholder="Password" required="required">
              <br>
               <button class="btn btn-success" type="submit" name="submit" value="Login"><?php echo lang('login_submit_btn');?></button>
          <?php echo form_close();?>

        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-grey py-5">
    <div class="container">
      <div class="small text-center text-muted"><img src="<?php echo IMG;?>/Havik_logo1.jpeg" style="height: 50px;width: 50px;"> &copy; Powered By <a target="_blank" href="http://www.haviktec.com/">Havik Healthcare IT Solutions Pvt Ltd.</a> All Rights Reserved</div>
    </div>
  </footer>

  <!-- Bootstrap core JavaScript -->
  <link href="<?php echo JS; ?>landing_page_js/jquery/jquery.min.js" rel="stylesheet">
  <link href="<?php echo JS; ?>landing_page_js/jquery/jquery.min.js" rel="stylesheet">
  <link href="<?php echo JS; ?>landing_page_js/bootstrap/js/bootstrap.bundle.min.js" rel="stylesheet">

  <!-- Plugin JavaScript -->
  <link href="<?php echo JS; ?>landing_page_js/jquery-easing/jquery.easing.min.js" rel="stylesheet">
  <link href="<?php echo JS; ?>landing_page_js/magnific-popup/jquery.magnific-popup.min.js" rel="stylesheet">

  <!-- Custom scripts for this template -->
  <link href="<?php echo JS; ?>landing_page_js/creative.min.js" rel="stylesheet">

</body>

</html>
<!-- Bootstrap Toastr Plugin CSS-->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <script src="<?php echo(JS.'jquery.min.js');?>" type="text/javascript"></script>  

<!-- Bootstrap Toastr Plugin JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
  <?php if($message) { ?>
     toastr.options = {
                "closeButton": true, // true/false
                "positionClass": "toast-top-right",
                "hideDuration": "1000", // in milliseconds
                "timeOut": "5000", // in milliseconds
                "showEasing": "swing",
                "hideEasing": "linear",
                "hideMethod": "fadeOut"
            }
        toastr.error('<?php echo $message?>', "<?php echo lang('common_message');?>")

<?php } ?>
</script>


