<?php $current_page = ""; ?>
<?php $main_nav = ""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

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
                           Gender Wise Students List                                
                        </h2> 
                        <ul class="header-dropdown m-r--5">
                            <div class="button-demo">
                            <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                            </div>
                        </ul>                           
                    </div>
                    <div class="body">           
                        <h2 class="card-inside-title">Select the Filters</h2>
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <label>Select District</label>
                                <select id="select_dt_name" class="form-control district_filter">
                                    <option value="All" selected="">All</option>
                                    <?php if(isset($distslist)): ?>
                                   
                                        <?php foreach ($distslist as $dist):?>
                                        <option value='<?php echo $dist['_id']; ?>' ><?php echo ucfirst($dist['dt_name'])?></option>
                                        <?php endforeach;?>
                                        <?php else: ?>
                                        <option value="1"  disabled="">No District entered yet</option>
                                    <?php endif ?>
                                </select>
                            </div>
                            <div class="col-sm-3"> 
                                <label>Select School</label>                                
                                <select class="form-control show-tick school_filter" id="school_name" disabled=true >
                                    <option value="All"  selected="">All</option>
                                </select>
                            </div>
                            <!-- <div class="col-sm-3">
                                <label>Select Class</label>                                 
                                <select class="form-control show-tick" id="">
                                    <option value="All"  selected="">All</option>
                                    <?php //foreach ($classes as $class):?>
                                        <option value=''><?php //echo ucwords($class["class_name"]) ;?></option>
                                    <?php //endforeach; ?>
                                </select>
                            </div> -->
                            <div class="col-sm-3" id="show_table" style="margin-top: 25px;">
                                <button type="button" class="btn bg-teal waves-effect">Show Classes</button>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 total_schools_count">
                                 <button type="button" class="btn btn-primary">Total Institutions - <span class="badge" id="total_schools_count"></span></button>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 total_female_schools_count">
                                 <button type="button" class="btn btn-primary">Girls Institutions - <span class="badge" id="total_female_schools_count"></span></button>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 total_male_schools_count">
                                 <button type="button" class="btn btn-primary">Boys Institutions - <span class="badge" id="total_male_schools_count"></span></button>
                            </div> 
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 total_degree_schools_count">
                                 <button type="button" class="btn btn-primary">Degree Colleges count out of total -<span class="badge" id="total_degree_schools_count"></span></button>
                                
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <button type="button" class="btn btn-primary">Total Students -<span class="badge" id="total_students_count"></span></button>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <button type="button" class="btn btn-primary">Girls -<span class="badge" id="female_students_count"></span></button>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <button type="button" class="btn btn-primary">Boys -<span class="badge" id="male_students_count"></span></button>
                            </div>
                           
                        </div>
                        <div class="row clearfix">
                           
                            <div id="get_students_gender_wise"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="loading_modal">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content" id="loading">
                <center>
                <div class="card">
                    <img src="<?php echo(IMG.'loader.gif'); ?>" id="gif" >
                </div>
                </center>
            </div>
        </div>
    </div> 
</section>
<?php include('inc/footer_bar.php'); ?>

<script type="text/javascript">
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
            url: 'get_schools_list',
            type: 'POST',
            data: {"dist_id" : dist},
            success: function (data) {          

                result = $.parseJSON(data);
                console.log(result)

                options.prop("disabled", false);
                options.empty();
                options.append($("<option />").val("All").prop("selected", true).text("All"));
                $.each(result, function() {
                    options.append($("<option />").val(this.school_code).text(this.school_name));
                });
                       
                },
                error:function(XMLHttpRequest, textStatus, errorThrown)
                {
                 console.log('error', errorThrown);
                }
            });
    });

    get_students_details();
    data_for_cards_with_filters();

   
    $("#show_table").click(function() {

        dist_name = $('#select_dt_name').val();
        scl_name = $("#school_name option:selected").text();
        $("#loading_modal").modal('show');
      
        $.ajax({
            url: 'get_classes_wise_male_female_data',
            type: 'POST',
            data: {"dist_id" : dist_name , "schl_name" : scl_name },
            success: function (data) {          

                result = $.parseJSON(data);
                $("#loading_modal").modal('hide');

                get_students_details(result);

                },
                error:function(XMLHttpRequest, textStatus, errorThrown)
                {
                 console.log('error', errorThrown);
                }
            });

        data_for_cards_with_filters();
        
    });

   /* function show_classes_for_data(result)
    {
        $('#classes_list').empty();
        $data = "";

        $data += '<div class='+'"col-lg-1 col-md-1 col-sm-2 col-xs-2"'+'>';

        $.each(result, function(index, val){
             
            $data +=  '<button type="button" class="btn btn-primary">Class <span class="badge" id="">'+val+'</span></button>';
        });

        $data += "</div>";

        $('#classes_list').html($data);
    }*/

    /* Bar For Classes*/

    function get_students_details(result)
    {
        $('#get_students_gender_wise').empty();

        data_table = "";
        data_table += "<table id='more_requests' class='table table-striped table-bordered' width='100%'><thead><tr><th>Class</th><th>Total Count</th><th>Girls</th><th>Boys</th></tr></thead><tbody>";

        $.each(result, function(index, val) {

            var clsName = val.class;
            var countsData = val.count;

            data_table += '<tr><td>'+clsName+'</td><td>'+countsData.total_students+'</td><td>'+countsData.female+'</td><td>'+countsData.male+'</td></tr>';
        });

        
        data_table += "</tbody></table>";

        

        $('#get_students_gender_wise').html(data_table);
        

        $('#more_requests').DataTable({
                "paging": true,
                "lengthMenu" : [25, 50, 75, 100]
              });
    }

    function data_for_cards_with_filters()
    {
        $('#total_schools_count').empty();
        $('#total_students_count').empty();
        $('#female_students_count').empty();
        $('#male_students_count').empty();

        var districts = $('.district_filter option:selected').val();
        var schools = $('.school_filter option:selected').text();

        $.ajax({
            url : 'get_students_data_genderwise',
            type : 'POST',
            data : {'district_name':districts, 'school_name':schools},
            success : function(data){
                var data = $.parseJSON(data);
                //console.log(data);
                show_counts_in_cards(data, schools);
            }
        });


    }

    function show_counts_in_cards(data, schools)
    {
        $('#total_schools_count').empty();
        $('#total_female_schools_count').empty();
        $('#total_male_schools_count').empty();
        $('#total_degree_schools_count').empty();
        $('#total_students_count').empty();
        $('#male_students_count').empty();
        $('#female_students_count').empty();
       
        var totalSchools = data.total_schools;
        var female_totalSchools = data.female_schools;
        var male_totalSchools = data.male_schools;
        var degree_totalSchools = data.degree_col;
        var totalStudents = data.total_students;
        var totalMaStudents = data.total_male_students;
        var totalFeStudents = data.total_female_students;

        if(schools == "All")
        {
            $('.total_schools_count').show();
            $('.total_female_schools_count').show();
            $('.total_male_schools_count').show();
            $('.total_degree_schools_count').show();

        }else{
            $('.total_schools_count').hide();
            $('.total_female_schools_count').hide();
            $('.total_male_schools_count').hide();
            $('.total_degree_schools_count').hide();
        }
        
        $('#total_schools_count').html('<div class="text"></div><div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">'+totalSchools+'</div>');

        $('#total_female_schools_count').html('<div class="text"></div><div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">'+female_totalSchools+'</div>');

        $('#total_male_schools_count').html('<div class="text"></div><div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">'+male_totalSchools+'</div>');

        $('#total_degree_schools_count').html('<div class="text"></div><div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">'+degree_totalSchools+'</div>');

        $('#total_students_count').html('<div class="text"></div><div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">'+totalStudents+'</div>');

        $('#male_students_count').html('<div class="text"></div><div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">'+totalMaStudents+'</div>');

        $('#female_students_count').html('<div class="text"></div><div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">'+totalFeStudents+'</div>');


    }
</script>
