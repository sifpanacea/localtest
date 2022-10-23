$(document).ready(function() {
var bla,typvl;
var count=0;
var MaxInputs   = 100; //maximum input boxes allowed
var FieldCtrl   = $("#FldCtrl"); //Input boxes wrapper ID
var AddButton   = $("#AddMoreFileBox"); //Add button ID
var fFlag = 0;
var x = FieldCtrl.length; //initlal text box count
var FieldCount=1; //to keep track of text box added
$('#FldCtrl').hide();
//$("#hidbtn").hide();

//$('.col-md-12').on('mouseenter',function() { 
  //  $('#hidbtn').show(); 

//});

//$(".col-md-12").mouseover(function(){
   // $(".removediv").css("visibility","visible");
 // });
  //$(".col-md-12").mouseout(function(){
    //$(".removediv").css("visibility","hidden");
 // });
$(AddButton).click(function (e)  //on add input button click
{
	
        if(fFlag==0) //max input box allowed
        {
			fFlag=1;
			
           $('#FldCtrl').append('<div class="breadcrumb row col-md-12"><div class="col-md-4"><input id="mytext" type="text" class="form-control" name="mytext" id="field_" placeholder="Text" value=""/></div><div class=""><select class="bootstrap-select" id="typval"><option value="text">Text</option><option value="number">Number</option></select>&nbsp;&nbsp;<input name="" class="" type="checkbox" value=""><div class="col-md-2 pull-right"><button type="button" class="removeclass btn btn-danger btn-sm"><span class="glyphicon glyphicon-minus-sign"></span></button>&nbsp;<button type="button" class="addclass btn btn-success btn-sm"><span class="glyphicon glyphicon-ok"></span></button></div></div></div>');
           
			//text box increment
          //  FieldCount++; //text box added increment
			
        }
		$(FieldCtrl).show();

});

$("body").on("click",".removeclass", function(e){ //user click on remove text
        if( fFlag = 1 ) {
                $(this).parents('.col-md-12').remove(); //remove text box
                x--; //decrement textbox
                FieldCount--; //text box added increment
				fFlag=0;
				$('#FldCtrl').hide();
        }
return false;
})

$("body").on("click",".addclass", function(e){ //user click on add text
        
			bla = $('#mytext').val();
			typvl=$('#typval').val();		
			count+=1;
 $('<div id="div'+count+'" class="crdiv'+count+' breadcrumb row col-md-12" draggable="auto"><div class="row col-md-10"><div class="col-md-4"><label class="label label-default" id="Label'+count+'" name="Label[]"></label></div><div class="col-md-6"><input type="" class="form-control" name="txt[]" id="intxt'+count+'"/></div></div><div id="hiddiv" class="col-md-2 pull-right"><button type="button" id="hidbtn" alt="Delete" class="removediv btn btn-danger btn-sm"><span class="glyphicon glyphicon-minus-sign"></span></button></div></div>').appendTo('#divfrm');

		$('#Label'+count+'').text(bla);
		$('#intxt'+count+'').attr('type', typvl);
		$('#FldCtrl').hide();
		//bla = $('#txt_name').val();
//set
//$('#txt_name').val('bla');

		
})
$("body").on("click",".removediv", function(e){ //user click on remove text
         if(count >= 1 ) {
			 //var ala = $('#div'+count+'').val();
                $(this).parents('.col-md-12').remove(); //remove text box
				
                //x--; //decrement textbox
                Count--; //text box added increment
				//fFlag=0;
				//$('#FldCtrl').hide();
		 }
return false;
})
//$("#div'+count+'").on("mouseenter",function() 
	// { 
	//$("#hiddiv").show();
	// });
  //  $("#div'+count+'").on("mouseleave",function() 
//	{ 
	//$("#hiddiv").hide();
//	});
 
 


});
