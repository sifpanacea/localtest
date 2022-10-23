var count=0;
var divcount=1;
var obj={};
$(document).ready(function() {

$('<div id="divfrm'+divcount+'" class="divf" align="center"></div>').appendTo('#mainpage');

$('#me').click(function(){
		var collsql = $('#collsql').val();
		// console.log(collsql);
		var btnUpload=$('#me');
		var mestatus=$('#mestatus');
		var files=$('#files');
		// console.log("filessssssssss",files);
		new AjaxUpload(btnUpload, {
			action: 'uploadSQL',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				 if (! (ext && /^(json)$/.test(ext))){ 
                    // extension is not allowed 
					mestatus.text('Sorry! only JSON files are allowed');
					return false;
				}
				mestatus.html('<img src="/TLSTEC/bootstrap/dist/img/ajax-loader.gif" height="16" width="16">');
			},
			onComplete: function(file, response){
				//On completion clear the status
				mestatus.text('Database imported Sucessfully!');
				//On completion clear the status
				files.html('');
				//Add uploaded file to list 
			}
		});
		
	});

	$('#me1').click(function(){
		var btnUpload=$('#me1');
		var mestatus=$('#mestatus1');
		var files=$('#files');
		new AjaxUpload(btnUpload, {
			action: 'uploadNoSql',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				 if (! (ext && /^(json)$/.test(ext))){ 
                    // extension is not allowed 
					mestatus.text('Sorry! only JSON files are allowed');
					return false;
				}
				mestatus.html('<img src="/TLSTEC/bootstrap/dist/img/ajax-loader.gif" height="16" width="16">');
			},
			onComplete: function(file, response){
				//On completion clear the status
				mestatus.text('Database imported Sucessfully!');
				//On completion clear the status
				files.html('');
				//Add uploaded file to list
			}
		});
		
	});
	$('#me2').click(function(){
		var btnUpload=$('#me2');
		var mestatus=$('#mestatus2');
		var files=$('#files');
		new AjaxUpload(btnUpload, {
			action: '',//'/cloudcaskfinal/index.php/dashboard/uploadNoSql',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				 if (! (ext && /^(json)$/.test(ext))){ 
                    // extension is not allowed 
					mestatus.text('Only JSON files are allowed');
					return false;
				}
				mestatus.html('<img src="/bootstrap/dist/img/ajax-loader.gif" height="16" width="16">');
			},
			onComplete: function(file, response){
				//On completion clear the status
				mestatus.text('Database imported Sucessfully!');
				//On completion clear the status
				files.html('');
				//Add uploaded file to list
			}
		});
});


$('#single').click(function(){
	
	// alert("single");
	count ++;
	$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="text" min="" max="" des=""></label><input type="" class="col-md-4 ialign " name="" id="intxt'+count+'"></input><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text("Click to change the field name");
		//$('#Labelmin'+count+'').text("Min");
		//$('#Labelmax'+count+'').text("Max");
		
});

$('#multi').click(function(){
	// alert("multi");
	count ++;
	
	$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="textarea" min="" max="" des=""></label><weight style="visibility:hidden">textarea</weight><textarea class="col-md-4 textdisable" row="2" name="txt[]" id="intxt'+count+'"></textarea><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');


		$('#Label'+count+'').text("Click to change the field name");
		
});

$('#date').click(function(){
	// alert("date");
	count ++;
	$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="date" min="" max="" des=""></label><input type="date" class="col-md-4 ialign " name="date" id="intxt'+count+'"></input><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text("Click to change the field name");
		
		
})


$("body").on("click",".removediv", function(e){ //user click on remove text
         if(count >= 1 ) {
				
				var labelremove=$(this).parents('.col-md-12').find('label').html()
				var weightremove=$(this).parents('.col-md-12').find('weight').html() 
				$(this).parents('.col-md-12').remove(); //remove text box
				var currPage = $('#divcount').val();
				var ccount = $('#divfrm'+currPage+'').children().length;	
		 }
return false;
})

$(document).on('click','.labcheck',function()
{
var labelchange= $(this).attr('id');
var forname=$(this).attr('name');

//alert(forname);
var tempcount = labelchange.replace(/\D/g,'');
$('#Label'+tempcount+'').replaceWith('<input id="mytext'+tempcount+'" type="text" class="col-md-3 labelfortext" name="mytext" placeholder="Enter Field Name" value="" required/>');
$("body").on("focusout",'#mytext'+tempcount+'', function(e)

   {		
		
		labelname=$('#mytext'+tempcount+'').val();
		if(labelname!='' && labelname!== undefined)
		{
		$('#mytext'+tempcount+'').replaceWith('<label class="labcheck lalign col-md-4 control-label" id="Label'+tempcount+'" name='+forname+'></label>')
		$('#Label'+tempcount+'').text(labelname);  
		}
		else
		{
			$('#Label'+tempcount+'').text("Click to change the field name"); 
			//alert("empty")
		
		}
		
}); //end of mytext count
})//end of label change name count dddd
$(document).on('click','#sbt',function()//create json
{
//$('.tform').submit(function(e)
//{
//alert("prevented");
//e.preventDefault();	
//})
$('.mainpage').children('div').each(function(index, element) 
{

   var divid=$(this).attr('id');
   //alert(divid)
   var change= $(this).attr('id');
   
   $(this).children('div').each(function(index, element) {//divfrm-div1,2,3,
   var diviid=$(this).attr('id');
   var iddd = diviid.replace(/\D/g,'');
   var secnam=$(this).find('label').attr('name');
   var labelname=$(this).find('label').html()
   //var inputtype=$(this).find('input').attr('type');
   var inputval=$(this).find('input').val();
   
   
   // console.log(labelname);
   // console.log(inputval);
   
   if ((secnam == 'text'))
		{
			
			obj[labelname] = inputval;
			
			// console.log(obj);
		}
		else if(secnam == 'textarea')
		{
			var areaval=$(this).find('textarea').val();
			obj[labelname] = areaval;
			// console.log(obj);
		}
		else if(secnam == 'date')
		{
			obj[labelname] = inputval;
			// console.log(obj);
		}			
   });
});//mainpage class .each end
var str = JSON.stringify (obj);
document.getElementById('code').value = str;
document.getElementById('appid').value = $('#appss').val();
document.getElementById('appname').value = $('#appss :selected').text();
//alert(document.getElementById('appname').value);
//alert($('#appss :selected').text());
formsubmit();
});//prevent form submit end
function formsubmit()
{
	var valuecheck=$('#code').val();
	// alert("value checkkkkkkk")
	// alert(valuecheck)
	if(valuecheck!='')
	{
	$('.tform').submit();
	// alert("form submitted")
	}
	else
	{
	// alert("form Empty");
	}
}


});