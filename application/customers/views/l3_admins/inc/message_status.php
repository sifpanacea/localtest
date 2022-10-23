
  <!-- Bootstrap Toastr Plugin CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Bootstrap Toastr Plugin JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script type="text/javascript">
       
            toastr.options = {
                "closeButton": true, // true/false
                "positionClass": "toast-top-right",
                "hideDuration": "1000", // in milliseconds
                "timeOut": "5000", // in milliseconds
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        <?php if ($this->session->flashdata('success')) {?>
            // Display a success toast, with a title
            toastr.info('<?php echo $this->session->flashdata('success'); ?>', "Success")
            <?php } else if ($this->session->flashdata('failed')) {?>
             // Display a success toast, with a title
            toastr.error('<?php echo $this->session->flashdata('failed'); ?>', "Error")
        <?php } ?>
    </script>

