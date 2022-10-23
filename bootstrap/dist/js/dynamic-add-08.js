var data = "";
var result = "";
var formDesign = "";
var con = "";
var mod = "";
var test = "";
var lessweight=1;
var ordweight=2;
var hvyweight=4;
var elementweight;
var draft;
var btn_clicked = false;
var logo_file_path = '';
var currentdiv=0;
var app_chose =[];
var mapping_check = false;
//app_chose.push({label:"Select APP",value:null})
//var companyname = "";
function appId(x) {
		var d = new Date();
		var yyyy = d.getFullYear();
		var mm = d.getMonth()+1;
		var dd = d.getDate();
		var hr = d.getHours();
		var mi = d.getMinutes();
		var ss = d.getSeconds();
		var ms = d.getMilliseconds();
		result = x+yyyy+mm+dd+hr+mi+ss+ms;
		//document.getElementById('appId').value = result;
		con = result+"_con";
		mod = result+"_mod";
		document.getElementById('controller_name').value = con;
		document.getElementById('model_name').value = mod;
}


//function for adding the profile header in application creation //
function profile_header(value)
{
	console.log("add_header_function");
	console.log(value);
	header_status = value;
	if(value == false && header_status == false)
	{
		if($('#divfrm1').children().hasClass("header_sec"))
		{
			remove_header();
		}
	}
	else if(value == true)
	{
		header_status == true;
		if($('#mainpage').children().is('#divfrm1')==true)
		{
			if(currentdiv > 0)
			{
				$('<div id="div" class="crdive header_sec breadcrumb sec col-md-12" elementweight="4"><label><h3>profile information</h3></label></div>').prependTo('#divfrm1');
				$('#divfrm1').show();
				$('.divf').removeClass("active");
				$('#divfrm1').addClass("active");
				var sel_temp=$('#mainpage').find('.active').children('.print_temp').val() ||'';
				if(sel_temp!='')
				{
					$('#template_name').val(sel_temp);
				}
				else
				{
					$('#template_name').val(" ");
				}
				var sel_img=$('#mainpage').find('.active').children('.print_temp').attr("img_src") || '';
				if(sel_img!='')
				{			
					$('.device').css("background-image", "url("+sel_img+")");	
				}
				else
				{
					$('.device').css("background-image", "none");
				}
				total_pages_length = $("#mainpage").children('div').length;
				console.log(total_pages_length);
				for(i=0;i<total_pages_length;i++)
				{
					console.log(i);
					console.log(total_pages_length);
					var curdivid=$("#mainpage").children('div').eq(i).attr('id');
					console.log(curdivid);
					var redivtempcount = curdivid.replace(/\D/g,'');
					var inprevweightvalue=22;
					$('#divfrm'+redivtempcount+'').children('div').each(function(index, element)
					{
						var intempweightvalue=$(this).attr('elementweight');
						intempweightvalue=parseInt(intempweightvalue)
						inprevweightvalue=parseInt(inprevweightvalue)-intempweightvalue;
						console.log(inprevweightvalue);
						if(inprevweightvalue < 0)
						{
							console.log("innerrrrr if",inprevweightvalue);
							var redivcount=redivtempcount;
							redivcount++;
							console.log(redivcount);
							if($('#mainpage').children().is('#divfrm'+redivcount+'')==true)
							{
								var remdivv=$(this).detach().prependTo('#divfrm'+redivcount+'');
								$('#divfrm'+redivcount+'').hide();
								document.getElementById('weight').value = inprevweightvalue;
								document.getElementById('divcount').value = 1;
							}
							
							else
							{
								totaldivcount=redivcount;
								$('<div id="divfrm'+redivcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
								$('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value="" relative_url="" img_desc="" img_src="" start="" end="" file_name="" img_id=""></input>').appendTo('#divfrm'+redivcount+'');
								$('#template_name').val("");
								$('.device').css("background-image", "none");
								$('#divfrm'+redivcount+'').hide();
								totaldivindex++;
								var elweight=$(this).attr('elementweight');
								document.getElementById('weight').value = elweight;
								document.getElementById('totaldivcount').value = totaldivcount;
								document.getElementById('divcount').value = 1;
								pageclear();
								label = totaldivcount;
								$(".divf").sortable({
								forcePlaceholderSize: true,
								axis: "y",
								scroll: false,
								sort: function () {},
								placeholder: 'ui-state-highlight',
								receive: function () {},
								update: function (event, ui) {}
								});
								var remdivvv=$(this).detach().appendTo('#divfrm'+redivcount+'');	
								currentdiv=1;														
							}
						}
					})
					
				}
			}
			else
			{
				add_header();
			}
		}
	}
}
function add_header()
{
	if(header_status == true)
	{
		if(!$('#divfrm1').children().hasClass("header_sec"))
		{
			$('<div id="div" class="crdive header_sec breadcrumb sec col-md-12" elementweight="4"><label><h3>profile information</h3></label></div>').prependTo('#divfrm1');
			var val = document.getElementById('weight').value
			val = parseInt(val)-4;
			document.getElementById('weight').value = val;
		}
	}
}

function remove_header()
{
	$('#divfrm1').children('.header_sec').remove();
	var weight_current_value = document.getElementById('weight').value;
	weight_current_value = parseInt(weight_current_value)+4;
	document.getElementById('weight').value = weight_current_value;
}

//profile header function end //

$(document).ready(function() {
var url_for_app = $('#url').val()
document.getElementById('weight').value = 22;
var fLabel,fType;
var weightvalue,tempdivcount;
var tempweight=document.getElementById('weight').value;
var tgs;
var companyname = [];
var chkbxreq='';
var chkbxkey='';
var tagval,min,max;
var chkvalue = "FALSE";
var chkkeyvalue = "FALSE";
var count=0;
var childcount,totaldivcount=1;
var MaxInputs   = 100; //maximum input boxes allowed
var FieldCtrl   = $("#FldCtrl"); //Input boxes wrapper ID
var AddButton   = $("#AddMoreFileBox"); //Add button ID
var fFlag = 0;
var x = FieldCtrl.length; //initlal text box count
var FieldCount=1; //to keep track of text box added
var divcount=1;
var section_list='';
var app_details='';
var applist_select_options = '<option value="none">Select Application</option>';
document.getElementById('divcount').value = divcount;
delete_disable();
document.getElementById('totaldivcount').value = divcount;
var optionvalue
$('#FldCtrl').hide();
weightvalue=$('#weight').val();
//var currentdiv=0;
$.ajax({
	url: ''+url_for_app+'dashboard/get_company_name',
	type: 'POST',
	success: function (data) {
	//console.log(data)
	var company_name=data.replace("[","").replace(/"/g, "").replace("]","").split(',');
	for(var i in company_name)
	{
	    companyname = company_name[i];
	}
	console.log("company name assigning",companyname);
	appId(companyname);
	if($('#updType').val() == "edit"){
		$('#controller_name').val($('#_id').val()+"_con");
		$('#model_name').val($('#_id').val()+"_mod");
	}
	else if($('#updType').val() == "draft")
	{
		$('#controller_name').val($('#_id').val()+"_con");
		$('#model_name').val($('#_id').val()+"_mod");
	}
	},
    error:function(XMLHttpRequest, textStatus, errorThrown)
	{
	 console.log('error', errorThrown);
    }
});	

function create_applist(data)
{
	//console.log("create_applist",data)
	try
	{
		var app_data = JSON.parse(data)
		var applist = app_data.applist
		applist_for_ref = applist;
		section_list = app_data.sectionlist
		app_details = app_data.appdetails
		//console.log(app_details)
		var length = applist.length;
		var app_option = '';
		for(ind =0; ind<length;ind++)
		{
			applist_select_options += '<option value="'+applist[ind]['appid']+'">'+applist[ind]['appname']+'</option>'
		}
	}
	catch (e)
	{
		console.log("error",e)
		console.log("Data",data)
	}
	
	//console.log("app_data",app_data.applist)
	
	//console.log("applist_for_ref",applist)
	
}

$.ajax({
	url: ''+url_for_app+'dashboard/fetch_applications',
	type: 'POST',
	async:false,
	success: function (data) {
	console.log("fetch_applications_success")//,data)
	create_applist(data);
	},
    error:function(XMLHttpRequest, textStatus, errorThrown)
	{
	 console.log('error', errorThrown);
    }
});	

function delete_disable()
{
	
	if($('#divcount').val()=="1")
	{
		
		$('#Deletepage').prop({disabled: true});
	}
	else
	{
		
		$('#Deletepage').prop({disabled: false});
	}
}

$(document).on('change','#divcount',function()
{
console.log("changeeee")
if($('#divcount').val()=="1")
{
	$('#Deletepage').attr("disabled","disabled")
}
else
{
	$('#Deletepage').attr("disabled","")
}	
})
//$('<div id="divfrm'+divcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
$('<div id="divfrm'+divcount+'" class="divf active" align="center"></div>').appendTo('#mainpage');//.droppable().appendTo('#mainpage');
$('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value="" img_desc="" img_src="" start="" end="" file_name="" relative_url="" img_id=""></input>').appendTo('#divfrm'+divcount+'');

var jsondef = $('#jsondef').val();
var jsontemp = $('#jsontemp').val();
$('#jsondef').val('');
$('#jsontemp').val('');

var print_temp = $('#print_temp').val();
$('#print_temp').val('');
var app_category = $('#app_category').val();
if(app_category!='' && app_category!=undefined)
{
$('#iappcategory').val(app_category);
}
var app_expiry = $('#app_expiry').val();
if(app_expiry!='' && app_expiry!=undefined)
{
$('#iappexpiry').val(app_expiry);
}
var appn=$('#appName').val();
if(appn!='' && appn!=undefined)
{
$('#apptitle').text(appn);
$('#iappName').val(appn);
}
var appd=$('#appDescription').val();
if(appd!='' && appd!=undefined)
{
$('#iappDescription').val(appd);
}
var rtype= $('input[name=apptype]:checked').val();
if(rtype!='' && rtype!=undefined)
{
$('#iapptype').val(rtype);
}
$('.datepicker').datepicker({dateFormat: 'dd/mm/yy'});
if (jsondef != "")
{
	var first_section = 0;
	sections = Array();
	secindex = 0;
	$('#appcategory').val(app_category);
	$('#date').val(app_expiry);
	var design_obj = $.parseJSON(jsondef);
	console.log("dddddddddddddddddd",design_obj)
	var temp_obj = $.parseJSON(jsontemp);
	
	var app_type = temp_obj.app_type;
	var use_profile_header = temp_obj.use_profile_header;
	
	if(app_type == "Private"){
		$('.app_type').html('<label class="radio"><input type="radio" id="apptype" name="apptype" value="Private" checked ><i></i>'+lang.app_private+'</label><label class="radio"><input type="radio" id="apptype" value="Shared" name="apptype"><i></i>'+lang.app_shared+'</label>');
	}else{
		$('.app_type').html('<label class="radio"><input type="radio" id="apptype" name="apptype" value="Private"><i></i>'+lang.app_private+'</label><label class="radio"><input type="radio" id="apptype" value="Shared" checked name="apptype"><i></i>'+lang.app_shared+'</label>');
	}
	
	if(use_profile_header == "yes"){
		$('.profile_header').html('<label class="radio"><input type="radio" id="headertype" name="headertype" checked value="yes"><i></i>'+lang.app_yes+'</label><label class="radio"><input type="radio" id="headertype" value="no" name="headertype"><i></i>'+lang.app_no+'</label></div>');
		$(".deviceheader").children().bind('click', function(){ return false; });
		$(".deviceheader").addClass("edit")
		profile_header(true);
	}else{
		$('.profile_header').html('<label class="radio"><input type="radio" id="headertype" name="headertype" value="yes"><i></i>'+lang.app_yes+'</label><label class="radio"><input type="radio" id="headertype" checked value="no" name="headertype"><i></i>'+lang.app_no+'</label></div>');
		$(".deviceheader").children().unbind('click');
		$(".deviceheader").removeClass("edit")
		profile_header(false);
	}
	
	var print_temp = $.parseJSON(print_temp);
	console.log(print_temp);
	var start=0,end=2;
	
	for(var i in design_obj){
		if(i>1)
		{
			$('#divfrm'+divcount+'').hide();
			divcount=i;
			totaldivcount=divcount;
			$('<div id="divfrm'+divcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
			$('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value="" img_desc="" img_src="" start="" end="" file_name="" img_id=""></input>').appendTo('#divfrm'+divcount+'');
			document.getElementById('divcount').value = totaldivcount;delete_disable()
			weightvalue=22;
			//weightvalue=parseInt(weightvalue)-elementweight;
			document.getElementById('weight').value = weightvalue;
			document.getElementById('totaldivcount').value = totaldivcount;
			pageclear();
			label = totaldivcount;
			$(".divf").sortable({
			forcePlaceholderSize: true,
			axis: "y",
			scroll: false,
			sort: function () {},
			placeholder: 'ui-state-highlight',
			receive: function () {},
			update: function (event, ui) {}
			});
			currentdiv=divcount;
		}
	 for(var s in design_obj[i]){
		
		
		if(($.inArray(s,sections) == -1) || (sections.length === 0))
			{
			secindex ++;
			
			
			fLabel = s;
			fType = "SBreak";
			if(fType == 'SBreak')
				{
					elementweight=2
					weightvalue=parseInt(weightvalue)-elementweight;
					if(weightvalue < 0)
					{
						$('#divfrm'+divcount+'').hide();
						divcount=divcount+1;
						totaldivcount=divcount;
						$('<div id="divfrm'+divcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
						$('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value="" img_desc="" img_src="" start="" end="" file_name="" img_id=""></input>').appendTo('#divfrm'+divcount+'');
						document.getElementById('divcount').value = totaldivcount;delete_disable()
						weightvalue=22;
						weightvalue=parseInt(weightvalue)-elementweight;
						document.getElementById('weight').value = weightvalue;
						document.getElementById('totaldivcount').value = totaldivcount;
						pageclear();
						label = totaldivcount;
						$(".divf").sortable({
						forcePlaceholderSize: true,
						axis: "y",
						scroll: false,
						sort: function () {},
						placeholder: 'ui-state-highlight',
						receive: function () {},
						update: function (event, ui) {}
						});
						currentdiv=divcount;

					}
//					else
//					{
//								checkvall();
//					}
				}

			
			count++;
			
			key = design_obj[i][s]['dont_use_this_name']['key'];
			desc = design_obj[i][s]['dont_use_this_name']['description'];
				if(first_section == 0){
						$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb sec first_sec col-md-12" elementweight="'+elementweight+'"><label class="labcheck col-md-4 control-label" name="section" id="Label'+count+'" name="Label[]" chkvalue='+chkvalue+' chkkeyvalue="'+key+'" des="'+desc+'"></label><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a></div>').appendTo('#divfrm'+divcount+'');
						first_section = 1;
				}else{
				
					$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb sec col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck col-md-4 control-label" name="section" id="Label'+count+'" name="Label[]" chkvalue="TRUE" chkkeyvalue="'+key+'" des="'+desc+'"></label><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
				}
							
							$('#Label'+count+'').text(fLabel);
							
							var chkdK = "";
							if(design_obj[i][s]['dont_use_this_name']['key'] == "TRUE"){
							chkdK = "checked";
							}
							
							$('#prop'+count+'').popover({
							html:true,
							title: 'Properties',
							content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput_ form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="hide checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea_" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'>'+desc+'</textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
							});
				sections[secindex]=fLabel;
				 }
		 
		for(var j in design_obj[i][s]){
		
			var y = design_obj[i][s][j].type;
			
			if(j != 'dont_use_this_name'){
				fType=y;
			if ((fType == 'text') || (fType == 'password') || (fType == 'newline') || (fType == 'file') || (fType == 'number')|| (fType == 'month')|| (fType == 'time')|| (fType == 'color')|| (fType == 'range')|| (fType == 'SBreak')|| (fType == 'select')|| (fType == 'MChoice')|| (fType == 'wtext')|| (fType == 'block')|| (fType == 'date') ||(fType == 'mobile'))
				{
					elementweight=2;
					weightvalue=parseInt(weightvalue)-elementweight;
					if(weightvalue < 0)
					{
						$('#divfrm'+divcount+'').hide();
						divcount=divcount+1;
						elementweight=2;
						totaldivcount=divcount;
						$('<div id="divfrm'+divcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
						$('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value="" img_desc="" img_src="" start="" end="" file_name="" img_id=""></input>').appendTo('#divfrm'+divcount+'');
						document.getElementById('divcount').value = totaldivcount;delete_disable()
						document.getElementById('totaldivcount').value = totaldivcount;
						weightvalue=22;
						weightvalue=parseInt(weightvalue)-elementweight;
						// console.log("wwwwwwwwwwwwwwwwwwwwwwww",weightvalue);
						document.getElementById('weight').value = weightvalue;
						pageclear();
						label = totaldivcount;
						$(".divf").sortable({
						forcePlaceholderSize: true,
						axis: "y",
						scroll: false,
						sort: function () {},
						placeholder: 'ui-state-highlight',
						receive: function () {},
						update: function (event, ui) {}
						});
						currentdiv=divcount;

					}
					//else
//					{
//						checkvall();
//					}
				}
				if ((fType == 'radio') || (fType == 'checkbox'))
				{
					elementweight=1
					// console.log("eeeeeeeeeeeeeeeee",elementweight);
					weightvalue=parseInt(weightvalue)-elementweight;
					// console.log("eeeeeeeeeeeeeeeee",weightvalue);
					if(weightvalue < 0)//<<<<<<<<<<<<
					{
						elementweight=1;
						$('#divfrm'+divcount+'').hide();
						divcount=divcount+1;
						totaldivcount=divcount;
						$('<div id="divfrm'+divcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
						$('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value="" img_desc="" img_src="" start="" end="" file_name="" img_id=""></input>').appendTo('#divfrm'+divcount+'');
						document.getElementById('divcount').value = totaldivcount;delete_disable()
						document.getElementById('totaldivcount').value = totaldivcount;
						weightvalue=22;
						weightvalue=parseInt(weightvalue)-elementweight;
						// console.log("wwwwwwwwwwwwwwwwwwwwwwww",weightvalue);
						document.getElementById('weight').value = weightvalue;
						pageclear();
						label = totaldivcount;
						$(".divf").sortable({
						forcePlaceholderSize: true,
						axis: "y",
						scroll: false,
						sort: function () {},
						placeholder: 'ui-state-highlight',
						receive: function () {},
						update: function (event, ui) {}
						});
						currentdiv=divcount;

					}
//					else
//					{
//								checkvall();
//					}
				}
				
				if ((fType == 'multiblock') || (fType == 'wtextarea') || (fType == 'photo') || (fType == 'textarea') || (fType == 'description') || (fType == 'imageElem'))
				{
					elementweight=4
					// console.log("eeeeeeeeeeeeeeeee",elementweight);
					weightvalue=parseInt(weightvalue)-elementweight;
					// console.log("eeeeeeeeeeeeeeeee",weightvalue);
					if(weightvalue < 0)//<<<<<<<<<<<<
					{
						elementweight=4
						$('#divfrm'+divcount+'').hide();
						divcount=divcount+1;
						totaldivcount=divcount;
						$('<div id="divfrm'+divcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
						$('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value="" img_desc="" img_src="" start="" end="" file_name="" img_id=""></input>').appendTo('#divfrm'+divcount+'');
						document.getElementById('divcount').value = totaldivcount;delete_disable()
						document.getElementById('totaldivcount').value = totaldivcount;
						weightvalue=22;
						weightvalue=parseInt(weightvalue)-elementweight;
						// console.log("wwwwwwwwwwwwwwwwwwwwwwww",weightvalue);
						document.getElementById('weight').value = weightvalue;
						pageclear();
						label = totaldivcount;
						$(".divf").sortable({
						forcePlaceholderSize: true,
						axis: "y",
						scroll: false,
						sort: function () {},
						placeholder: 'ui-state-highlight',
						receive: function () {},
						update: function (event, ui) {}
						});
						currentdiv=divcount;
					}
//					else
//					{
//								checkvall();
//					}
				}
				if((fType == 'retriever') ||(fType == 'mapper'))
				{
					console.log("retrieverretrieverretrieverretriever")
					var prop_def = design_obj[i][s][j]['properties']
					var type = prop_def['type']
					elementweight = getvalue(type)
					weightvalue=parseInt(weightvalue)-elementweight;
					// console.log("eeeeeeeeeeeeeeeee",weightvalue);
					if(weightvalue < 0)//<<<<<<<<<<<<
					{
						elementweight=4
						$('#divfrm'+divcount+'').hide();
						divcount=divcount+1;
						totaldivcount=divcount;
						$('<div id="divfrm'+divcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
						$('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value="" img_desc="" img_src="" start="" end="" file_name="" img_id=""></input>').appendTo('#divfrm'+divcount+'');
						document.getElementById('divcount').value = totaldivcount;delete_disable()
						document.getElementById('totaldivcount').value = totaldivcount;
						weightvalue=22;
						weightvalue=parseInt(weightvalue)-elementweight;
						// console.log("wwwwwwwwwwwwwwwwwwwwwwww",weightvalue);
						document.getElementById('weight').value = weightvalue;
						pageclear();
						label = totaldivcount;
						$(".divf").sortable({
						forcePlaceholderSize: true,
						axis: "y",
						scroll: false,
						sort: function () {},
						placeholder: 'ui-state-highlight',
						receive: function () {},
						update: function (event, ui) {}
						});
						currentdiv=divcount;

					}
//					else
//					{
//								checkvall();
//					}
				}
			switch(y){
			case "text":
				//weightvalue=weightvalue-1;
				//alert(weightvalue);
				fLabel = j;
				// console.log(fLabel);
				fType = "text";
				//alert(fType);
			count ++;
			
			var chkdR = "";
			var req_class = "";
			if(design_obj[i][s][j].required == "TRUE"){
			chkdR = "checked";
			req_class = "required";
			}
			var chkdN = "";
			if(design_obj[i][s][j].notify == "true"){
			chkdN = "checked";
			}
			var chkdK = "";
			if(design_obj[i][s][j].key == "TRUE"){
			chkdK = "checked";
			
			$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="2"><label class="labcheck lalign col-md-4 '+req_class+'" name="text" id="Label'+count+'" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' notify='+design_obj[i][s][j].notify+' min="'+design_obj[i][s][j].minlength+'" max="'+design_obj[i][s][j].maxlength+'" des="'+design_obj[i][s][j].description+'"></label><input type="" class="col-md-4 ialign " name="txt[]" id="intxt'+count+'" disabled></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value="'+design_obj[i][s][j].maxlength+'"></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value="'+design_obj[i][s][j].description+'"></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
			var dis = "";
			}else{
			
			$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="2"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="wtext" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+'  min="'+design_obj[i][s][j].minlength+'" max="'+design_obj[i][s][j].maxlength+'" des="'+design_obj[i][s][j].description+'"></label><hr class="widget col-md-4 hralign"><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value="'+design_obj[i][s][j].maxlength+'"></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value="'+design_obj[i][s][j].description+'"></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
			var dis = "disabled";
			}
			
			$('#Label'+count+'').text(fLabel);
			//$('#Labelmin'+count+'').text("Min");
			//$('#Labelmax'+count+'').text("Max");
			$('#intxt'+count+'').attr('type', fType);
			$('#FldCtrl').hide();
			$('div').remove('.tempdel');
			
				$('#prop'+count+'').popover({
				html:true,
				title: 'Properties',
				content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+'  value="'+design_obj[i][s][j].required+'" '+dis+'>'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" '+chkdN+' value="'+design_obj[i][s][j].notify+'" '+dis+'>'+lang.app_notify+'</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'>'+design_obj[i][s][j].description+'</textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min+' value='+design_obj[i][s][j].minlength+' '+dis+'></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max+' value="'+design_obj[i][s][j].maxlength+'" '+dis+'></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
				})
				//$('#prop'+count+'').popover('show');
		//		$('#prop'+count+'').trigger( "click" );
		$('#AddMoreFileBox').show();
				break;
			case "number":
				//weightvalue=weightvalue-1;
				//alert(weightvalue);
				fLabel = j;
				// console.log(fLabel);
				fType = "number";
				//alert(fType);
			count ++;
			
			var chkdR = "";
			if(design_obj[i][s][j].required == "TRUE"){
				chkdR = "checked";
				req_class = "required";
			}
			var chkdN = "";
			if(design_obj[i][s][j].notify == "true"){
			chkdN = "checked";
			}
			var chkdK = "";
			if(design_obj[i][s][j].key == "TRUE"){
				chkdK = "checked";
			}
			
			$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="2"><label class="labcheck lalign col-md-4 '+req_class+'" name="number" id="Label'+count+'" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' notify='+design_obj[i][s][j].notify+' min="'+design_obj[i][s][j].minlength+'" max="'+design_obj[i][s][j].maxlength+'" des="'+design_obj[i][s][j].description+'"></label><input type="" class="col-md-4 ialign " name="txt[]" id="intxt'+count+'" disabled></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value="'+design_obj[i][s][j].maxlength+'"></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value="'+design_obj[i][s][j].description+'"></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
			
			$('#Label'+count+'').text(fLabel);
			//$('#Labelmin'+count+'').text("Min");
			//$('#Labelmax'+count+'').text("Max");
			$('#intxt'+count+'').attr('type', fType);
			$('#FldCtrl').hide();
			$('div').remove('.tempdel');
			
			
				$('#prop'+count+'').popover({
				html:true,
				title: 'Properties',
				content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" '+chkdN+' value="'+design_obj[i][s][j].notify+'" >'+lang.app_notify+'</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'>'+design_obj[i][s][j].description+'</textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min+' value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max+' value="'+design_obj[i][s][j].maxlength+'"></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
				})
				//$('#prop'+count+'').popover('show');
		//		$('#prop'+count+'').trigger( "click" );
		$('#AddMoreFileBox').show();
				break;
			case "password":
			//weightvalue=weightvalue-1;
			//alert(weightvalue);
			fLabel = j;
			// console.log(fLabel);
			fType = "password";
			//alert(fType);
			//console.log(design_obj[i][s][j]);
			count ++;
			$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="2"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="password" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' min="'+design_obj[i][s][j].minlength+'" max="'+design_obj[i][s][j].maxlength+'" des="'+design_obj[i][s][j].description+'"></label><input type="" class="col-md-4 ialign " name="txt[]" id="intxt'+count+'" disabled></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value="'+design_obj[i][s][j].maxlength+'"></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value="'+design_obj[i][s][j].description+'"></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
			
			
				$('#Label'+count+'').text(fLabel);
				//$('#Labelmin'+count+'').text("Min");
				//$('#Labelmax'+count+'').text("Max");
				$('#intxt'+count+'').attr('type', fType);
				$('#FldCtrl').hide();
				$('div').remove('.tempdel');
				
				var chkdR = "";
				if(design_obj[i][s][j].required == "TRUE"){
				chkdR = "checked";
				}
				var chkdK = "";
				if(design_obj[i][s][j].key == "TRUE"){
				chkdK = "checked";
				}
			
				$('#prop'+count+'').popover({
				html:true,
				title: 'Properties',
				content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">'+lang.app_req+'</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'>'+design_obj[i][s][j].description+'</textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value="'+design_obj[i][s][j].maxlength+'"></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
				})
				//$('#prop'+count+'').popover('show');
		//		$('#prop'+count+'').trigger( "click" );
		$('#AddMoreFileBox').show();
		
			break;
			case "retriever": //retrieve***
			fLabel = j;
			fType = "retriever";
			//alert(fType);
			//console.log("retriever");
			//console.log(design_obj[i][s][j]);
			count ++;
			var prop_def = design_obj[i][s][j]['properties']
			prop_def = JSON.stringify(prop_def)
			prop_def = btoa(prop_def)
			/* var elementweight = getvalue(prop_def['type'])
			console.log("elementweight",elementweight) */
			$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des="" retrieve_ref="'+design_obj[i][s][j]['field_ref']+'" retrieve_def="'+prop_def+'" retrieve_type="'+design_obj[i][s][j]['properties']['type']+'" retrieve_lists="'+design_obj[i][s][j]['retrieve_list']+'" id_ref="'+design_obj[i][s][j]['coll_ref']+'">'+fLabel+'</label><a href="javascript:void(0);" id="prop'+count+'" class="popovr_ret delbtn pull-right btn btn-danger btn-xs tocheck" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
			//++++++++++
			
		//$('#Label'+count+'').text(lang.app_field_name);
		//$('#FldCtrl').hide();
		//$('div').remove('.tempdel');
		//console.log("applist_select_options",applist_select_options)
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><select class="app_list" id="app_list'+count+'" idcount="'+count+'" style="max-width:182px;min-height:28px">'+applist_select_options+'</select></div><div class="form-inline check_align"><select class="sec_list section'+count+'" idcount="'+count+'" id="section'+count+'" style="width:182px;min-height:28px;margin-right:10px;"><option value="none">select section</option></select><select class="field_list" idcount="'+count+'" id="field'+count+'" style="width:182px;min-height:28px"><option value="none">select field</option></select></div><div class="row col-md-12"><select class="retrievelist multiselect" idcount="'+count+'" id="retrieve'+count+'" style="width:182px;min-height:28px" multiple="multiple"><option value="none">select field</option></select><input type="button" class="btn btn-default btn-sm popsaveret col-md-offset-4" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
			})
			
			var result = $.grep(applist_for_ref, function(element, index) {
			return (element.appid === design_obj[i][s][j]['coll_ref']);
			});
			var mapp_options = '<option value="null">Select Field</option>';
			var app_label_name = result[0]['appname'];
			app_chose[design_obj[i][s][j]['coll_ref']]=[];
			app_chose[design_obj[i][s][j]['coll_ref']]={label:app_label_name,value:design_obj[i][s][j]['coll_ref'],is_used:true,fields:mapp_options,selected_fields:[]}
			$.each(design_obj[i][s][j]['retrieve_list'],function(ind,val)
			{
				var values = design_obj[i][s][j]['retrieve_list'][ind].split("_");
				var section = values[1]
				var field = values[2]
				app_chose[design_obj[i][s][j]['coll_ref']]['selected_fields'].push(field);
				var typ = app_details[design_obj[i][s][j]['coll_ref']][section][field].type
				mapp_options +='<option type="'+typ+'" appd="'+design_obj[i][s][j]['coll_ref']+'" value="'+design_obj[i][s][j]['retrieve_list'][ind]+'">'+field+'</option>' 
			})
			app_chose[design_obj[i][s][j]['coll_ref']]['fields'] = mapp_options;
			console.log("mapp_options",mapp_options)
			$('#AddMoreFileBox').show();
			break;
				
				
			case "mapper": //retrieve***
			fLabel = j;
			fType = "mapper";
			//alert(fType);
			//console.log("mapper");
			//console.log(design_obj[i][s][j]);
			count ++;
			var prop_def = design_obj[i][s][j]['properties']
			prop_def = JSON.stringify(prop_def)
			prop_def = btoa(prop_def)
			var chkdR = "";
				if(design_obj[i][s][j].required == "TRUE"){
				chkdR = "checked";
				}
				var chkdK = "";
				if(design_obj[i][s][j].key == "TRUE"){
				chkdK = "checked";
				}
				
			/* var elementweight = getvalue(prop_def['type']) */
			$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des="" prev_app="'+design_obj[i][s][j]['coll_ref']+'" map_ref="'+design_obj[i][s][j]['field_ref']+'" appid="'+design_obj[i][s][j]['coll_ref']+'" mapped_def="'+prop_def+'" mapped="'+design_obj[i][s][j]['field_ref']+'" prev_field="">'+fLabel+'</label><a href="javascript:void(0);" id="prop'+count+'" cnt="'+count+'" class="mapbtn delbtn pull-right btn btn-danger btn-xs map_tocheck" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
			
			$('#prop'+count+'').popover({
			html:true,
			title: 'Properties',
			content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+'  value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" '+chkdK+' value="">'+lang.app_notify+'</label></div><div class="form-inline check_align"><select class="aplist" id="aplist'+count+'" idcount="'+count+'" style="width:182px;min-height:28px;margin-right:5px;"></select><select class="maplist" id="maplist'+count+'" idcount="'+count+'" style="width:182px;min-height:28px"></select></div><div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavemap popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')
			})
			
			//17-08-2016//$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><select class="aplist" id="aplist'+count+'" idcount="'+count+'" style="width:182px;min-height:28px"></select></div><div class="form-inline check_align"><select class="maplist" id="maplist'+count+'" idcount="'+count+'" style="width:182px;min-height:28px"></select></div><div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavemap popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')  
			//})
			$('#AddMoreFileBox').show();
			break;
			
			case "instruction":
			//weightvalue=weightvalue-1;
			//alert(weightvalue);
			fLabel = j;
			// console.log(fLabel);
			fType = "instruction";
			//alert(fType);
			//console.log(design_obj[i][s][j]);
			count ++;
			$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb ins desc col-md-12 draggable" elementweight="4"><label class="labcheck col-md-4 control-label" name="instructions" id="Label'+count+'" name="Label[]" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' des="'+design_obj[i][s][j].description+'" instruct="'+design_obj[i][s][j].instructions+'"></label><div class="inst lab"><p class="labelch labcheck" id="inss'+count+'">'+design_obj[i][s][j].instructions+'</p></div><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class="ins delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text(fLabel);
		//$('#Label'+count+'').text(fLabel);
		//$('#intxt'+count+'').attr('type', fType);//sssssssss
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		var chkdR = "";
			if(design_obj[i][s][j].required == "TRUE"){
			chkdR = "checked";
			}
			var chkdK = "";
			if(design_obj[i][s][j].key == "TRUE"){
			chkdK = "checked";
			}
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="hide checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="'+design_obj[i][s][j].required+'">Required</label></div><div class="form-inline ins"><textarea class="ins'+count+' custom-scroll popinstextarea form-control col-md-5" id="ins'+count+'" type="text" value="" placeholder='+lang.app_instr+'>'+design_obj[i][s][j].instructions+'</textarea><textarea class="des'+count+' custom-scroll popinstextarea form-control col-md-5" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'>'+design_obj[i][s][j].description+'</textarea></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsaveins popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		
		})
		$('#AddMoreFileBox').show();
				break;
			case "textarea":
				//weightvalue=weightvalue-1;
				//alert(weightvalue);
				fLabel = j;
				// console.log(fLabel);
				fType = "textarea";
				//alert(fType);
				//console.log(design_obj[i][s][j]);
				count ++;
				
				var chkdR = "";
				var req_class = "";
				if(design_obj[i][s][j].required == "TRUE"){
				chkdR = "checked";
				req_class = "required";
				}
				var chkdN = "";
				if(design_obj[i][s][j].notify == "true"){
					chkdN = "checked";
				}
				var chkdK = "";
				if(design_obj[i][s][j].key == "TRUE"){
				chkdK = "checked";
				
				$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="4"><label class="lalign labcheck col-md-4 '+req_class+' control-label" id="Label'+count+'" name="textarea" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' notify='+design_obj[i][s][j].notify+' min="'+design_obj[i][s][j].minlength+'" max="'+design_obj[i][s][j].maxlength+'" des="'+design_obj[i][s][j].description+'"></label><weight style="visibility:hidden">textarea</weight><textarea class="col-md-4 textdisable" row="2" name="txt[]" id="intxt'+count+'" disabled></textarea><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value="'+design_obj[i][s][j].maxlength+'"></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value="">'+design_obj[i][s][j].description+'</input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
				var dis = "";
				}else{
					$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="4"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="wtextarea" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+'  min="'+design_obj[i][s][j].minlength+'" max="'+design_obj[i][s][j].maxlength+'" des="'+design_obj[i][s][j].description+'"></label><div class="col-md-5 hralign"><hr class="widget"><hr class="widget"><hr class="widget"></div></textarea><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value="'+design_obj[i][s][j].maxlength+'"></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value="">'+design_obj[i][s][j].description+'</input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
					var dis = "disabled";
				}
			
			
			$('#Label'+count+'').text(fLabel);
			$('#FldCtrl').hide();
			$('div').remove('.tempdel');
			
			$('#prop'+count+'').popover({
			html:true,
			title: 'Properties',
			content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'" '+dis+'>'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" '+chkdN+' value="'+design_obj[i][s][j].notify+'" '+dis+'>'+lang.app_notify+'</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'>'+design_obj[i][s][j].description+'</textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min+' value="'+design_obj[i][s][j].minlength+'" '+dis+'></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max+' value="'+design_obj[i][s][j].maxlength+'" '+dis+'></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
			})
		$('#AddMoreFileBox').show();
				break;
			case "select":
				//weightvalue=weightvalue-1;
				//alert(weightvalue);
				fLabel = j;
				//alert(fLabel);
				//console.log(design_obj[i][s][j]);
				tagvalarr = new Array();
				var vi = design_obj[i][s][j].options.length;
				for(var opt= 0; opt < vi ; opt++)
					{
					tagvalarr.push(design_obj[i][s][j].options[opt].value);//.toString();//getting tag values
				// console.log(tagvalarr);  
					}
				//commented for comma, issue
				//tagval = (tagvalarr).toString();
				//new
				tagval = JSON.stringify(tagvalarr);
				console.log(tagvalarr)
				count ++;
				
				var chkdR = "";
				var req_class = "";
				if(design_obj[i][s][j].required == "TRUE"){
					chkdR = "checked";
					req_class = "required";
				}
				var chkdN = "";
				if(design_obj[i][s][j].notify == "true"){
					chkdN = "checked";
				}
				var chkdK = "";
				if(design_obj[i][s][j].key == "TRUE"){
					chkdK = "checked";
				}
		
				$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="2"><label class="labcheck lalign col-md-4 '+req_class+' control-label" name="select" id="Label'+count+'" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' notify='+design_obj[i][s][j].notify+' des="'+design_obj[i][s][j].description+'" tags=""></label><div id="selectdiv'+count+'" class="col-md-5"><label class="select"><select id="select'+count+'"></select></label></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
					var tcount=1;
					$('#Label'+count+'').text(fLabel);	
					$('#Label'+count+'').attr("tags",tagval);	
					
					/* commented for comma issue
					var selectvalue = tagval.split(",");//splitting tag values
					for (var ii in selectvalue)// allocating the values to radio values
					{
					
					var selectlabel=selectvalue[ii];
					selectvalue[ii]=new Array();// Creatinga Array for JSON
					selectvalue[ii].label = ''+selectlabel+'';
					selectvalue[ii].value= ''+selectlabel+'';
					$('<option value="'+selectlabel+'">'+selectlabel+'</option>').appendTo('#select'+count+'');
					
					tcount++;
					}*/   //end
					
					//created for comma
					var ij
					for(ij=0;ij<tagvalarr.length;ij++)
					{
						$('<option value="'+tagvalarr[ij]+'">'+tagvalarr[ij]+'</option>').appendTo('#select'+count+'');
						tcount++;
					}
					//end
					
					
					$('#FldCtrl').hide();
					$('div').remove('.tempdel');
					
					
					$('#prop'+count+'').popover({
					html:true,
					title: 'Properties',
					content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" '+chkdN+' value="'+design_obj[i][s][j].notify+'">'+lang.app_notify+'</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="selects'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'>'+design_obj[i][s][j].description+'</textarea><input type="button" class="btn btn-default btn-sm popsavesel popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div>')//$('#divprop'+count+'').html()
					})//tagval
					$('#prop'+count+'').on('click',function(e)
					{
						var addusr= $(this).attr('id');
						tempcount = addusr.replace(/\D/g,'');
						$('#selects'+tempcount+'').tagsinput({
						})
						
						for(i_j=0;i_j<tagvalarr.length;i_j++)
						{
							$('#selects'+tempcount+'').tagsinput('add',tagvalarr[i_j]);
						}
					})
					$('#AddMoreFileBox').show();

				break;
			case "MChoice":
					//weightvalue=weightvalue-1;
					//alert(weightvalue);
					fLabel = j;
					//alert(fLabel);
					//console.log(design_obj[i][s][j]);
					tagvalarr = new Array();
					var vi = design_obj[i][s][j].options.length;
					for(var opt= 0; opt < vi ; opt++)
						{
						tagvalarr.push(design_obj[i][s][j].options[opt].value);//.toString();//getting tag values
					// console.log(tagvalarr);  
						}
					tagval = (tagvalarr).toString();
					count ++;
					
					var chkdR = "";
					var req_class = "";
					if(design_obj[i][s][j].required == "TRUE"){
						chkdR = "checked";
						req_class = "required";
					}
					var chkdN = "";
					if(design_obj[i][s][j].notify == "true"){
						chkdN = "checked";
					}
					var chkdK = "";
					if(design_obj[i][s][j].key == "TRUE"){
						chkdK = "checked";
					}
					
					$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="2"><label class="labcheck lalign col-md-4 '+req_class+' control-label" id="Label'+count+'" name="mchoice" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' notify='+design_obj[i][s][j].notify+' des="'+design_obj[i][s][j].description+'" tags="'+tagval+'"></label><div id="selectdiv'+count+'" class="col-md-5"><label class="select select-multiple"><select multiple class="custom-scroll" id="select'+count+'"></select></label></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
						var tcount=1;
						$('#Label'+count+'').text(j);	
						var selectvalue = tagval.split(",");//splitting tag values
						for (var ii in selectvalue)// allocating the values to radio values
						{
						
						var selectlabel=selectvalue[ii];
						selectvalue[ii]=new Array();// Creatinga Array for JSON
						selectvalue[ii].label = ''+selectlabel+'';
						selectvalue[ii].value= ''+selectlabel+'';
						$('<option value="'+selectlabel+'">'+selectlabel+'</option>').appendTo('#select'+count+'');
						tcount++;
						}
						$('#FldCtrl').hide();
						$('div').remove('.tempdel');
						
						
						$('#prop'+count+'').popover({
						html:true,
						title: 'Properties',
						content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" '+chkdN+' value="'+design_obj[i][s][j].notify+'">'+lang.app_notify+'</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="selects'+count+'" type="text" value="'+tagval+'" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'>'+design_obj[i][s][j].description+'</textarea><input type="button" class="btn btn-default btn-sm popsavesel popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div>')//$('#divprop'+count+'').html()
						})
						$('#prop'+count+'').on('click',function(e)
						{
							// console.log("clicked");
							var addusr= $(this).attr('id');
							tempcount = addusr.replace(/\D/g,'');
							$('#selects'+tempcount+'').tagsinput({
							})
						})
						$('#AddMoreFileBox').show();
						
				break;
			case "radio":
						//weightvalue=weightvalue-1;
						//alert(weightvalue);
						fLabel = j;
						//alert(fLabel);
						//console.log(design_obj[i][s][j]);
						tagvalarr = new Array();
						var vi = design_obj[i][s][j].options.length;
						for(var opt= 0; opt < vi ; opt++)
							{
							tagvalarr.push(design_obj[i][s][j].options[opt].value);//.toString();//getting tag values
						// console.log(tagvalarr);  
							}
						tagval = (tagvalarr).toString();
						count ++;
						
						var chkdR = "";
						var req_class = "";
						if(design_obj[i][s][j].required == "TRUE"){
							chkdR = "checked";
							req_class = "required";
						}
						var chkdN = "";
						if(design_obj[i][s][j].notify == "true"){
							chkdN = "checked";
						}
						var chkdK = "";
						if(design_obj[i][s][j].key == "TRUE"){
							chkdK = "checked";
						}
						
						$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="1"><label class="labcheck lalign col-md-4 '+req_class+' control-label" id="Label'+count+'" name="radio" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' notify='+design_obj[i][s][j].notify+' des="'+design_obj[i][s][j].description+'" tags="'+tagval+'"></label><div id="raddiv'+count+'" class="col-md-6 radd"></div><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
						var tcount=1;
						$('#Label'+count+'').text(fLabel);	
						var radiovalue = tagval.split(",");//splitting tag values
						for (var ii in radiovalue)// allocating the values to radio values
						{
						
						var radiolabel=radiovalue[ii];
						radiovalue[ii]=new Array();// Creatinga Array for JSON
						radiovalue[ii].label = ''+radiolabel+'';
						radiovalue[ii].value= ''+radiolabel+'';
						
						$('<label class="radio radio-inline radiomargin"><input type="radio" name="radio-inline" value="'+radiolabel+'" checked="checked"/>'+radiolabel+'</label>').appendTo('#raddiv'+count+'');
				
						tcount++;
						}
						
						$('#FldCtrl').hide();
						$('div').remove('.tempdel');
						
						var chkdR = "";
						if(design_obj[i][s][j].required == "TRUE"){
						chkdR = "checked";
						}
						var chkdK = "";
						if(design_obj[i][s][j].key == "TRUE"){
						chkdK = "checked";
						}
						
						$('#prop'+count+'').popover({
						html:true,
						title: 'Properties',
						content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" '+chkdN+' value="'+design_obj[i][s][j].notify+'">'+lang.app_notify+'</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="radioval'+count+'" type="text" value="'+tagval+'" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'>'+design_obj[i][s][j].description+'</textarea>'+'<input type="button" class="btn btn-default btn-sm popsaverad popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div>')//$('#divprop'+count+'').html()
						})
						$('#prop'+count+'').on('click',function(e)
						{
							var radioid= $(this).attr('id');
							tempcount = radioid.replace(/\D/g,'');
							$('#radioval'+tempcount+'').tagsinput({
							})
						})
						$('#AddMoreFileBox').show();
					
				break;
			case "checkbox":
						//weightvalue=weightvalue-1;
						//alert(weightvalue);
						fLabel = j;
						//alert(fLabel);
						//console.log(design_obj[i][s][j]);
						tagvalarr = new Array();
						var vi = design_obj[i][s][j].options.length;
						for(var opt= 0; opt < vi ; opt++)
							{
							tagvalarr.push(design_obj[i][s][j].options[opt].value);//.toString();//getting tag values
						// console.log(tagvalarr);  
							}
						tagval = (tagvalarr).toString();
						count ++;
						
						var chkdR = "";
						var req_class = "";
						if(design_obj[i][s][j].required == "TRUE"){
							chkdR = "checked";
							req_class = "required";
						}
						var chkdN = "";
						if(design_obj[i][s][j].notify == "true"){
							chkdN = "checked";
						}
						var chkdK = "";
						if(design_obj[i][s][j].key == "TRUE"){
							chkdK = "checked";
						}
						
						$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="1"><label class="labcheck lalign col-md-4 '+req_class+' control-label" id="Label'+count+'" name="checkbox" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' notify='+design_obj[i][s][j].notify+' des="'+design_obj[i][s][j].description+'" tags="'+tagval+'"></label><div id="chkdiv'+count+'" class="col-md-6"></div><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
							$('#Label'+count+'').text(j);	
							var chekvalue = tagval.split(",");//splitting tag values
							var tcount=1;
							for (var ii in chekvalue)// allocating the values to Checkbox
							{
							
							var chklabel=chekvalue[ii];
							chekvalue[ii]=new Array();// Creatinga Array for JSON
							chekvalue[ii].label = ''+chklabel+'';
							chekvalue[ii].value= ''+chklabel+'';
							$('<label class="checkbox checkbox-inline radiomargin"><input type="checkbox" name="checkbox-inline" value="'+chklabel+'" checked="checked"/>'+chklabel+'</label>').appendTo('#chkdiv'+count+'');
							}
							$('#FldCtrl').hide();
							$('div').remove('.tempdel');
							
							
							$('#prop'+count+'').popover({
							html:true,
							title: 'Properties',
							content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" '+chkdN+' value="'+design_obj[i][s][j].notify+'">'+lang.app_notify+'</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="chkval'+count+'" type="text" value="'+tagval+'" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'>'+design_obj[i][s][j].description+'</textarea><input type="button" class="btn btn-default btn-sm popsavechk popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div>')//$('#divprop'+count+'').html()
							})
							$('#prop'+count+'').on('click',function(e)
							{
								var radioid= $(this).attr('id');
								tempcount = radioid.replace(/\D/g,'');
								$('#chkval'+tempcount+'').tagsinput({
								})
							})
							$('#AddMoreFileBox').show();
				break;
			case "file":
							//weightvalue=weightvalue-1;
							//alert(weightvalue);
							fLabel = j;
							// console.log(design_obj[i][s][j]);
							fType = "file";
							//alert(fType);
							//console.log(design_obj[i][s][j]);
							count ++;
							
							var chkdR = "";
							var req_class = "";
							if(design_obj[i][s][j].required == "TRUE"){
								chkdR = "checked";
								req_class = "required";
							}
							var chkdK = "";
							if(design_obj[i][s][j].key == "TRUE"){
								chkdK = "checked";
							}
							
							$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="2"><label class="labcheck lalign col-md-4 '+req_class+'" id="Label'+count+'" name="file" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' min="'+design_obj[i][s][j].minlength+'" max="'+design_obj[i][s][j].maxlength+'" des="'+design_obj[i][s][j].description+'"></label><input type="file" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value="'+design_obj[i][s][j].maxlength+'"></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value="'+design_obj[i][s][j].description+'"></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

							$('#Label'+count+'').text(fLabel);
							$('#FldCtrl').hide();
							$('div').remove('.tempdel');
							
						
							$('#prop'+count+'').popover({
							html:true,
							title: 'Properties',
							content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value='+design_obj[i][s][j].required+'>'+lang.app_req+'</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'>'+design_obj[i][s][j].description+'</textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
							})


							$('#AddMoreFileBox').show();
							//end of file
				break;
				case "range":
							//weightvalue=weightvalue-1;
							//alert(weightvalue);
							fLabel = j;
							// console.log(design_obj[i][s][j]);
							fType = "range";
							//alert(fType);
							//console.log(design_obj[i][s][j]);
							count ++;

							$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="2"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="range" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' min="'+design_obj[i][s][j].minrange+'" max="'+design_obj[i][s][j].maxrange+'" des="'+design_obj[i][s][j].description+'"></label><input type="range" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled style="width:176px"></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value="'+design_obj[i][s][j].maxlength+'"></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value="'+design_obj[i][s][j].description+'"></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
							
							$('#Label'+count+'').text(fLabel);
							//$('#Labelmin'+count+'').text("From");
							//$('#Labelmax'+count+'').text("To");
							//$('#intxt'+count+'').attr('type', fType);
							$('#FldCtrl').hide();
							$('div').remove('.tempdel');
							//tAdd_Txtbox(fLabel,fType,"0","60",chkvalue, chkkeyvalue);//JSON Creation
							
							var chkdR = "";
							if(design_obj[i][s][j].required == "TRUE"){
							chkdR = "checked";
							}
							var chkdK = "";
							if(design_obj[i][s][j].key == "TRUE"){
							chkdK = "checked";
							}
							
							$('#prop'+count+'').popover({
							html:true,
							title: 'Properties',
							content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">Required</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description">'+design_obj[i][s][j].description+'</textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value="'+design_obj[i][s][j].minrange+'"></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value="'+design_obj[i][s][j].maxrange+'"></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
							})
							
							$('#AddMoreFileBox').show();
				break;
				
				case "imageElem":
				//weightvalue=weightvalue-1;
				//alert(weightvalue);
				fLabel = j;
				// console.log(fLabel);
				fType = "text";
				//alert(fType);
			count ++;
			
			var chkdR = "";
			var req_class = "";
			if(design_obj[i][s][j].required == "TRUE"){
			chkdR = "checked";
			req_class = "required";
			}
			var chkdN = "";
			if(design_obj[i][s][j].notify == "true"){
			chkdN = "checked";
			}
			var chkdK = "";
			if(design_obj[i][s][j].key == "TRUE"){
			chkdK = "checked";
			}
			var sameasfield=''+design_obj[i][s][j].sameasfield+'' || ""
			var cloned=''+design_obj[i][s][j].cloned+'' || ""
			var clone_value = false;
			if(sameasfield !="" && cloned == "")
			{
				clone_value = true;
			}
			$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="imageElem" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' page="'+design_obj[i][s][j].page+'" section="'+design_obj[i][s][j].section+'" sameasfield="'+design_obj[i][s][j].sameasfield+'" cloned="'+design_obj[i][s][j].cloned+'" des="'+design_obj[i][s][j].description+'" sel_value="'+design_obj[i][s][j].sameasfield+'"></label></textarea><a href="javascript:void(0);" id="prop'+count+'" class="delbtn pull-right btn btn-danger btn-xs cloned_'+clone_value+'" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button><div class="col- hralign" style="clear: both;"><img src="http://35.154.116.243/PaaS/bootstrap/dist/img/image_preview.png" alt="" style="height: 250px;width: 500px;"></div></div>').appendTo('#divfrm'+divcount+'');
			
			
			$('#Label'+count+'').text(fLabel);
			//$('#Labelmin'+count+'').text("Min");
			//$('#Labelmax'+count+'').text("Max");
			$('#intxt'+count+'').attr('type', fType);
			$('#FldCtrl').hide();
			$('div').remove('.tempdel');
			//var sameaas = "";
			/* if(sameasfield !="" && cloned== "")
			{
				console.log("checkbox11111")
				//$('#sameas'+count+'').trigger("click");
				//$('#sameas'+count+'').click();
				//$('#sameas'+count+'').attr("checked","checked");
				sameaas="checked"
			} */
			$('#prop'+count+'').popover({
			html:true,
			title: 'Properties',
			content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder="Field Name"/><label class="checkbox-inline check_align" id="same'+count+'" name="imga'+count+'"><input id="sameas'+count+'" name="img'+count+'" class="sameas" type="checkbox" value="" >Same As</label><label class="checkbox-inline"></label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><select class="sameasfields col-md-6" id="sameasfields'+count+'" disabled="disabled"></select></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
			})
			//$('#name'+count+'').val(fLabel);  checked=""
			
			if(sameasfield !="" && cloned == "")
			{
				console.log("checkbox11111")
				
				//$('#same17').click()//.prop('checked', true);//.attr('checked', true).change();//attr('checked','checked');//.prop('checked',true)//.change();
				//$('input[name=img'+count+']').trigger('click').prop('checked', true);//.attr('checked', true).change();
				//$('#sameas'+count+'').prop("checked",true).trigger('click')//.triggerHandler('click');
				//document.getElementById('sameas'+count+'').onclick();
				//$('.sameas').trigger("click")
				//.attr('checked', true).trigger("change");
				//$('#sameas'+count+'').trigger("click"); checked="'+sameaas+'"
				//$('#sameas'+count+'').click();
				//$('#sameas'+count+'').attr("checked","checked");
				//sameaas="checked"
			}
				/* $('#prop'+count+'').popover({
				html:true,
				title: 'Properties',
				content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+'  value="'+design_obj[i][s][j].required+'" '+dis+'>'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" '+chkdN+' value="'+design_obj[i][s][j].notify+'" '+dis+'>'+lang.app_notify+'</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'>'+design_obj[i][s][j].description+'</textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min+' value='+design_obj[i][s][j].minlength+' '+dis+'></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max+' value="'+design_obj[i][s][j].maxlength+'" '+dis+'></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
				}) */
				//$('#prop'+count+'').popover('show');
		//		$('#prop'+count+'').trigger( "click" );
		$('#AddMoreFileBox').show();
		


				break;
				case "date":
							//weightvalue=weightvalue-1;
							//alert(weightvalue);
							fLabel = j;
							// console.log(design_obj[i][s][j]);
							fType = "date";
							//alert(fType);
							//console.log(design_obj[i][s][j]);
							count ++;
							
							var chkdR = "";
							var req_class = "";
							if(design_obj[i][s][j].required == "TRUE"){
							chkdR = "checked";
							req_class = "required";
							}
							var chkdN = "";
							if(design_obj[i][s][j].notify == "true"){
								chkdN = "checked";
							}
							var chkdK = "";
							if(design_obj[i][s][j].key == "TRUE"){
							chkdK = "checked";
							}

							$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="4"><label class="labcheck lalign col-md-4 '+req_class+'" id="Label'+count+'" name="'+fType+'" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' notify='+design_obj[i][s][j].notify+'  des="'+design_obj[i][s][j].description+'"></label><input type="" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled style="height:20px"></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value="'+design_obj[i][s][j].description+'"></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

							$('#Label'+count+'').text(fLabel);
							//$('#Labelmin'+count+'').text("Min");
							//$('#Labelmax'+count+'').text("Max");
							$('#intxt'+count+'').attr('type', fType);
							$('#FldCtrl').hide();
							$('div').remove('.tempdel');
						
						
							$('#prop'+count+'').popover({
							html:true,
							title: 'Properties',
							content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" '+chkdN+' value="'+design_obj[i][s][j].notify+'">'+lang.app_notify+'<label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'>'+design_obj[i][s][j].description+'</textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
							})
							//$('#prop'+count+'').popover('show');
							//		$('#prop'+count+'').trigger( "click" );
							$('#AddMoreFileBox').show();
				break;
				case "time":
							//weightvalue=weightvalue-1;
							//alert(weightvalue);
							fLabel = j;
							// console.log(design_obj[i][s][j]);
							fType = "time";
							//alert(fType);
							//console.log(design_obj[i][s][j]);
							count ++;

							$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="2"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' min="'+design_obj[i][s][j].minlength+'" max="'+design_obj[i][s][j].maxlength+'" des="'+design_obj[i][s][j].description+'"></label><input type="" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled style="height:20px"></input><a href="javascript:void(0);" id="prop'+count+'"class="delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

							$('#Label'+count+'').text(fLabel);
							//$('#Labelmin'+count+'').text("Min");
							//$('#Labelmax'+count+'').text("Max");
							$('#intxt'+count+'').attr('type', fType);
							$('#FldCtrl').hide();
							$('div').remove('.tempdel');
						
							var chkdR = "";
							if(design_obj[i][s][j].required == "TRUE"){
							chkdR = "checked";
							}
							var chkdK = "";
							if(design_obj[i][s][j].key == "TRUE"){
							chkdK = "checked";
							}
							
							$('#prop'+count+'').popover({
							html:true,
							title: 'Properties',
							content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">Required</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description">'+design_obj[i][s][j].description+'</textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
							})
							//$('#prop'+count+'').popover('show');
							//		$('#prop'+count+'').trigger( "click" );
							$('#AddMoreFileBox').show();
				break;
				case "month":
							//weightvalue=weightvalue-1;
							//alert(weightvalue);
							fLabel = j;
							// console.log(design_obj[i][s][j]);
							fType = "month";
							//alert(fType);
							//console.log(design_obj[i][s][j]);
							count ++;

							$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="2"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' min="'+design_obj[i][s][j].minlength+'" max="'+design_obj[i][s][j].maxlength+'" des="'+design_obj[i][s][j].description+'"></label><input type="" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled style="height:20px"></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

							$('#Label'+count+'').text(fLabel);
							//$('#Labelmin'+count+'').text("Min");
							//$('#Labelmax'+count+'').text("Max");
							$('#intxt'+count+'').attr('type', fType);
							$('#FldCtrl').hide();
							$('div').remove('.tempdel');
							
							var chkdR = "";
							if(design_obj[i][s][j].required == "TRUE"){
							chkdR = "checked";
							}
							var chkdK = "";
							if(design_obj[i][s][j].key == "TRUE"){
							chkdK = "checked";
							}
							
							$('#prop'+count+'').popover({
							html:true,
							title: 'Properties',
							content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">Required</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description">type="checkbox" </textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
							})
							//$('#prop'+count+'').popover('show');
							//		$('#prop'+count+'').trigger( "click" );
							$('#AddMoreFileBox').show();
				break;
				
				case "newline":
					//weightvalue=weightvalue-1;
					//alert(weightvalue);
					fLabel = j;
					// console.log(fLabel);
					fType = "newline";
					//alert(fType);
					//console.log(design_obj[i][s][j]);
					count ++;
					
					$('<div id="div'+count+'" class="crdiv'+count+' opac breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="hide" name="newline"></label><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
				
					//$('#Label'+count+'').text("Field Name");
					//$('#Labelmin'+count+'').text("Min");
					//$('#Labelmax'+count+'').text("Max");
					$('#intxt'+count+'').attr('type', fType);
					$('#FldCtrl').hide();
					$('div').remove('.tempdel');
				
					//$('#prop'+count+'').popover({
					//html:true,
					//title: 'Properties',
					//content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
					//})
					//$('#prop'+count+'').popover('show');
//					$('#prop'+count+'').trigger( "click" );
					$('#AddMoreFileBox').show();
					
				break;
					
				case "photo":
					//weightvalue=weightvalue-1;
					//alert(weightvalue);
					fLabel = j;
					// console.log(design_obj[i][s][j]);
					fType = "photo";
					//alert(fType);
					//console.log(design_obj[i][s][j]);
					count ++;
					
					var min_size = (design_obj[i][s][j].upload.min_size)/1024;
					var max_size = (design_obj[i][s][j].upload.max_size)/1024;
					
					$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="'+min_size+'" max="'+max_size+'" des="'+design_obj[i][s][j].description+'" notify="'+design_obj[i][s][j].notify+'"></label><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button><div class="col-md-3 photo_element"><i class="glyphicon glyphicon-user"></i></div></div>').appendTo('#divfrm'+divcount+'');


					$('#Label'+count+'').text(fLabel);
					$('#FldCtrl').hide();
					$('div').remove('.tempdel');
					
					var chkdR = "";
					if(design_obj[i][s][j].required == "TRUE"){
					chkdR = "checked";
					}
					var chkdK = "";
					if(design_obj[i][s][j].key == "TRUE"){
					chkdK = "checked";
					}
					
					$('#prop'+count+'').popover({
					html:true,
					title: 'Properties',
					content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">'+lang.app_req+'</label><label class="checkbox-inline hide"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="'+design_obj[i][s][j].notify+'">'+lang.app_notify+'</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="'+design_obj[i][s][j].description+'" placeholder='+lang.app_desc+'>'+design_obj[i][s][j].description+'</textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min_size+' value="'+min_size+'"></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max_size+' value="'+max_size+'"></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
					})
					$('#AddMoreFileBox').show();
				break;
				
				case "mobile":
					//weightvalue=weightvalue-1;
					//alert(weightvalue);
					fLabel = j;
					// console.log(design_obj[i][s][j]);
					fType = "mobile";
					//alert(fType);
					//console.log(design_obj[i][s][j]);
					count ++;
					
					$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' notify='+design_obj[i][s][j].notify+' min="'+design_obj[i][s][j].minlength+'" max="'+design_obj[i][s][j].maxlength+'" des="'+design_obj[i][s][j].description+'"></label><input type="" class="col-md-1 ialign " name="" id="intxt'+count+'" disabled style="padding-left: 0px;padding-right: 0px;margin-right: 2px;width: 49px;" placeholder="code"></input><input type="" class="col-md-3 ialign " name="" id="intxt'+count+'" disabled placeholder="Number"></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-3 ialign" id="fMin'+count+'"placeholder="Min" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value="'+design_obj[i][s][j].description+'"></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

					$('#Label'+count+'').text(fLabel);
					$('#intxt'+count+'').attr('type', fType);
					$('#FldCtrl').hide();
					$('div').remove('.tempdel');
					
					var chkdR = "";
					var req_class = "";
					if(design_obj[i][s][j].required == "TRUE"){
					chkdR = "checked";
					req_class = "required";
					}
					var chkdN = "";
					if(design_obj[i][s][j].notify == "true"){
						chkdN = "checked";
					}
					var chkdK = "";
					if(design_obj[i][s][j].key == "TRUE"){
					chkdK = "checked";
					}
				
					$('#prop'+count+'').popover({
					html:true,
					title: 'Properties',
					content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" '+chkdN+' value="">'+lang.app_notify+'</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="'+design_obj[i][s][j].description+'" placeholder='+lang.app_desc+'>'+design_obj[i][s][j].description+'</textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min+' value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max+' value="'+design_obj[i][s][j].maxlength+'"></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
					})
					//$('#prop'+count+'').popover('show');
					//		$('#prop'+count+'').trigger( "click" );
					$('#AddMoreFileBox').show();
				break;
				
				case "block":
					//weightvalue=weightvalue-1;
					//alert(weightvalue);
					fLabel = j;
					// console.log(design_obj[i][s][j]);
					fType = "mobile";
					//alert(fType);
					//console.log(design_obj[i][s][j]);
					count ++;
					
					$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="2"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="blocktext" chkvalue="'+design_obj[i][s][j].required+'" chkkeyvalue="'+design_obj[i][s][j].key+'"></label><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value="'+design_obj[i][s][j].maxlength+'"></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value="'+design_obj[i][s][j].description+'"></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
					$('#Label'+count+'').text(fLabel);
					$('#intxt'+count+'').attr('type',fType);
					$('#FldCtrl').hide();
					$('div').remove('.tempdel');
					
					var chkdR = "";
					var req_class = "";
					if(design_obj[i][s][j].required == "TRUE"){
					chkdR = "checked";
					req_class = "required";
					}
					var chkdN = "";
					if(design_obj[i][s][j].notify == "true"){
						chkdN = "checked";
					}
					var chkdK = "";
					if(design_obj[i][s][j].key == "TRUE"){
					chkdK = "checked";
					}
					
					$('#prop'+count+'').popover({
					html:true,
					title: 'Properties',
					content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="" disabled>'+lang.app_req+'</label><label class="checkbox-inline"></label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="'+design_obj[i][s][j].description+'" placeholder="'+lang.app_desc+'">'+design_obj[i][s][j].description+'</textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min+' value="'+design_obj[i][s][j].minlength+'" disabled></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max+' value="'+design_obj[i][s][j].maxlength+'" disabled></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
					})
					$('#AddMoreFileBox').show();
				break;
				
				case "multiblock":
					//weightvalue=weightvalue-1;
					//alert(weightvalue);
					fLabel = j;
					// console.log(design_obj[i][s][j]);
					fType = "mobile";
					//alert(fType);
					//console.log(design_obj[i][s][j]);
					count ++;
					
					$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="mblocktext" chkvalue="'+design_obj[i][s][j].required+'" chkkeyvalue="'+design_obj[i][s][j].key+'"></label><div class="col-md-6 hralign" style="padding: 0px;width: 300px;"><div class="sq_b_div"><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span></div><div class="sq_b_div"><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></div><div class="sq_b_div"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span></div></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value="'+design_obj[i][s][j].maxlength+'"></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value='+design_obj[i][s][j].description+'"></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
					$('#Label'+count+'').text(fLabel);
					$('#intxt'+count+'').attr('type',fType);
					$('#FldCtrl').hide();
					$('div').remove('.tempdel');
					
					var chkdR = "";
					var req_class = "";
					if(design_obj[i][s][j].required == "TRUE"){
					chkdR = "checked";
					req_class = "required";
					}
					var chkdN = "";
					if(design_obj[i][s][j].notify == "true"){
						chkdN = "checked";
					}
					var chkdK = "";
					if(design_obj[i][s][j].key == "TRUE"){
					chkdK = "checked";
					}
					
					$('#prop'+count+'').popover({
					html:true,
					title: 'Properties',
					content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder='+lang.app_field_name+'/><label class="checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="" disabled>'+lang.app_req+'</label><label class="checkbox-inline"></label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="'+design_obj[i][s][j].description+'" placeholder="'+lang.app_desc+'">'+design_obj[i][s][j].description+'</textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min+' value="'+design_obj[i][s][j].minlength+'" disabled></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max+' value="'+design_obj[i][s][j].maxlength+'" disabled></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
					})
					$('#AddMoreFileBox').show();
				break;
				
			}
			}
		}
	 }
	 
		if(print_temp[i]['file_title'] !=""){
		console.log("PRINT_TEMPPPPP",print_temp);
		var title=print_temp[i]['file_title'];
		$('#print_temp'+i+'').val(title);
		$('#template_name').val(title);
		$('#namme').modal('hide');
		var imgsrc=print_temp[i]['file_path'] ||'';
		
		console.log(imgsrc);
		var imgdesc=print_temp[i]['file_description'] ||'';
		//console.log(imgdesc);
		var url_img=print_temp[i]['file_path'];
		//var name_img=$('.superbox-imageinfo').attr("filename");
		$('#print_temp'+i+'').attr("img_desc",imgdesc);
		$('#print_temp'+i+'').attr("img_src",imgsrc);
		$('#print_temp'+i+'').attr("relative_url",imgsrc);
		//$('#print_temp'+i+'').attr("start",start);
		//$('#print_temp'+i+'').attr("end",end);
		//$('#mainpage').find('.active').children('.print_temp').attr("img_id",active_id);
		//$('#mainpage').find('.active').children('.print_temp').attr("file_name",name_img);
		
		$('.device').css("background-image", "url("+imgsrc+")");
		$('.device').css("background-repeat", "no-repeat");
		$('.device').css("background-size", "100% 100%");
		}
	 
	}
}


$(AddButton).click(function (e)  //on add input button click
{
	//console.log($('.foraddbutton').height())
	if($('.foraddbutton').height()>440)
	{
		//console.log("ddas")
	var newheight=$('.foraddbutton').height();
	// console.log("hhhhhhhhhhhhhhhhhhhhhhhhhh",newheight)
	newheight=newheight+300;
	// console.log("hhhhhhhhhhhhhhhhhhhhhhhhhh",newheight)
	$('.device').css('min-height',newheight);
	}

        if(fFlag==0) //max input box allowed
        {
			fFlag=1;
		   $('#AddMoreFileBox').hide();
           $('<div class="col-md-12 breadcrumb ddd"><div class="startup"><label class="radio-inline"><input type="radio" class="form-input start" value="app" name="rad" id="intxt">'+lang.typable+'</label><label class="radio-inline"><input type="radio" class="form-input start" id="intxt" name="rad"value="widget">'+lang.writable+'</label><div class="col-md-1 pull-right"><button type="button" class="removeclass btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus-sign"></span></button></div></div></div>').appendTo('#FldCtrl');
		   
$('.start').change(function()
{
	var selectval=$('[name=rad]:checked').val();
	$('.ddd').hide();
	if(selectval=='app')
	{
    $('<div class="col-md-12 breadcrumb tempdel"><select class="selectpicker" id="typval"> <option value="SBreak">'+lang.app_Sbrake+'</option><option value="newline">'+lang.app_new_line+'</option><option value="description">'+lang.app_instruction+'</option><option id="optdefalt" value="text">'+lang.app_single_txt+'</option> <option value="number">'+lang.app_number+'</option> <option value="radio">'+lang.app_radio+'</option><option value="textarea">'+lang.app_multi_line+'</option><option value="file">'+lang.app_file+'</option><option value="select">'+lang.app_select+'</option><option value="photo">'+lang.app_photo+'</option><option value="mobile">'+lang.app_mobile+'</option><option value="checkbox">'+lang.app_checkbox+'</option><option value="date">'+lang.app_date+'</option><option value="newpage">New page</option><option value="retriever">Retriever</option><option value="mapper">Mapper</option><option value="imageElem">Image Field</option><option value="time" disabled>Time</option><option value="MChoice" disabled>Multiple Choice</option><option value="range" disabled>Range Bar</option> <option value="color" disabled>Color Picker</option> <option value="month" disabled>Month</option> <option value="password" disabled>Password</option></select>&nbsp;&nbsp;<div class="col-md-2 pull-right"><button type="button" class="removeclass btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus-sign"></span></button>&nbsp;<button type="button" class="addclass btn btn-success btn-xs"><span class="glyphicon glyphicon-ok"></span></button></div></div>').appendTo('#FldCtrl');
	//$('<div class="col-md-12 breadcrumb tempdel"><select class="selectpicker" id="typval"> <option value="SBreak">Section Break</option><option value="description">Instruction</option><option id="optdefalt" value="text">One Line Text</option> <option value="number">Number</option> <option value="radio">Radio</option><option value="textarea">Multi Line Text</option> <option value="password">Password</option> <option value="select">DropDown</option> <option value="checkbox">Checkboxes</option><option value="MChoice">Multiple Choice</option> <option value="PBreak">Page Break</option></select>&nbsp;&nbsp;<div class="col-md-2 pull-right"><button type="button" class="removeclass btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus-sign"></span></button>&nbsp;<button type="button" class="addclass btn btn-success btn-xs"><span class="glyphicon glyphicon-ok"></span></button></div></div>').appendTo('#FldCtrl');
	//description<label class="checkbox-inline"><input id="chkreqq" name="" class="chkreq" type="checkbox" value="">Required</label><label class="checkbox-inline"><input id="isakeyy" name="" class="isakey" type="checkbox" value="">Is a key?</label>
	}
	else
	{
		$('<div class="col-md-12 breadcrumb tempdel"><select class="" id="typval"><option id="optdefalt" value="wtext">'+lang.app_single_txt+'</option><option value="wtextarea">'+lang.app_multi_line+'</option><option value="blocktext">'+lang.app_block_text+'</option><option value="mblocktext">'+lang.app_block_multi_text+'</option><option value="freewriting" disabled>Free Writing</option></select><div class="col-md-2 pull-right"><button type="button" class="removeclass btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus-sign"></span></button>&nbsp;<button type="button" class="addclass btn btn-success btn-xs"><span class="glyphicon glyphicon-ok"></span></button></div></div>').appendTo('#FldCtrl');
	}
	
})

$("#typval").change(function () { //Checking Select Option
  var str = "";
  str = $("#typval option:selected").val();
  

 if(str=='SBreak')
  {
	$('#tagdiv').remove();
	$('#key').hide();
	
	}
	
	else 
	{
	$('#tagdiv').remove();
	$('#key').show();
	}
	
	}); // end of typval
	
  }
  		$('#AddMoreFileBox').hide();
		$('#FldCtrl').show();
		$('.ddd').show();
		$('input[name=rad]').attr('checked',false);
		$('#mytext').val('');//clearing textbox value
		$('#chkreqq').attr('checked', false); // uncheck the checkbox for required;
		chkbxreq='FALSE';
		$('#isakeyy').attr('checked', false); // uncheck the checkbox for key;
		chkbxkey='FALSE';
		$('#mytext').attr('placeholder',lang.app_text);//placing defualt text
		//////alert($('#optdefalt').val())
		$('#optdefalt').prop('selected',true);//default option select

});// end of addbutton

$("body").on("click",".addclass", function(e){ //user click on add

if(currentdiv>0)//&& currentdiv!=totaldivcount::::::::::
{
        console.log("sssssssssssssssss"+currentdiv);
		divcount=currentdiv;
		weightvalue=document.getElementById('weight').value; 
		adding();
}
else
{
	weightvalue=$('#weight').val();
	divcount = totaldivcount;
	adding();
}
	
function adding()
{
var str = "";
  str = $("#typval option:selected").val()
fLabel = $('#mytext').val();
fType=$('#typval').val();
if((fType == 'retriever') ||(fType == 'mapper'))
{
	add();
}
if ((fType == 'text') || (fType == 'password') || (fType == 'newline') || (fType == 'file') || (fType == 'number')|| (fType == 'month')|| (fType == 'time')|| (fType == 'color')|| (fType == 'range')|| (fType == 'SBreak')|| (fType == 'select')|| (fType == 'MChoice')|| (fType == 'wtext')|| (fType == 'blocktext')|| (fType == 'date') ||(fType == 'mobile'))
{
	elementweight=2;
	weightvalue=parseInt(weightvalue)-elementweight;
	// console.log("weightvalue",weightvalue); 
	if(weightvalue < 0)
	{
		checkval(elementweight);
	}
	else
	{
		checkvall();
	}
}
if ((fType == 'radio') || (fType == 'checkbox') )
{
	elementweight=1
	weightvalue=parseInt(weightvalue)-elementweight;
	if(weightvalue < 0)
	{
		checkval(elementweight);
	}
	else
	{
				checkvall();
	}
}

if ((fType == 'mblocktext') || (fType == 'wtextarea') || (fType == 'photo') || (fType == 'textarea') || (fType == 'description') || (fType == 'imageElem'))
{
	elementweight=4
	weightvalue=parseInt(weightvalue)-elementweight;
	if(weightvalue < 0)
	{
		checkval(elementweight);
	}
	else
	{
				checkvall();
	}
}

if(fType == "newpage")
{
	divcount++;
	totaldivcount=divcount;
	$('.divf').hide();
	$('.tempdel').hide();
	//$('.device').css('background-image', 'none');
	$('<div id="divfrm'+divcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
	$('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value=""  img_desc="" img_src="" start="" end="" file_name="" img_id="" relative_url=""></input>').appendTo('#divfrm'+divcount+'');
	$('#template_name').val("");
	$('.device').css("background-image", "none");
	$('.divf').removeClass("active");
	$('#divfrm'+divcount+'').addClass("active");
	document.getElementById('divcount').value = totaldivcount;delete_disable()
	document.getElementById('totaldivcount').value = totaldivcount;
	weightvalue=22;
	//weightvalue=parseInt(weightvalue)-elementweight;
	document.getElementById('weight').value = weightvalue;
	pageclear();
	label = totaldivcount;
	$(".divf").sortable({
	forcePlaceholderSize: true,
	axis: "y",
	scroll: false,
	sort: function () {},
	placeholder: 'ui-state-highlight',
	receive: function () {},
	update: function (event, ui) {}
	});
	//add();
	currentdiv=divcount;
	$('#FldCtrl').hide();
	$('div').remove('.tempdel');
	$('#AddMoreFileBox').show();
}
function checkval(elementweight)
	{
		var check_flag="false";
		$('#divfrm'+divcount+'').hide();
		divcount=divcount+1;
		document.getElementById('totaldivcount').value = totaldivcount;
        if($('#mainpage').children().is('#divfrm'+divcount+'')==true)
			{
				$('#divfrm'+divcount+'').show();
				$('.divf').removeClass("active");
				$('#divfrm'+divcount+'').addClass("active");
				var sel_temp=$('#mainpage').find('.active').children('.print_temp').val() ||'';
				if(sel_temp!='')
				{
					$('#template_name').val(sel_temp);
				}
				else
				{
					$('#template_name').val("");
				}
				var sel_img=$('#mainpage').find('.active').children('.print_temp').attr("img_src") || '';
				if(sel_img!='')
				{			
					$('.device').css("background-image", "url("+sel_img+")");	
				}
				else
				{
					$('.device').css("background-image", "none");
				}
                var divindex=$('#divfrm'+divcount+'').index();
				//console.log(divindex);
				var totaldivindex=$('#mainpage').children('div').length-1;				
				weightvalue=document.getElementById('weight').value;
				weightvalue=parseInt(weightvalue)-elementweight;
				var prevweightvalue=22;
				prevweightvalue=parseInt(prevweightvalue)-elementweight				
				//console.log("weightvalue",weightvalue)
				if(weightvalue < 0)
				{
					//console.log("weightvalue------------if",weightvalue);
					//console.log("111111111111111111111111111111111111");
					$('#divfrm'+divcount+'').children('div').each(function(index, element)
					{
						var tempweightvalue=$(this).attr('elementweight');
						tempweightvalue=parseInt(tempweightvalue)
						prevweightvalue=parseInt(prevweightvalue)-tempweightvalue;
						document.getElementById('weight').value = prevweightvalue;
						document.getElementById('divcount').value = divcount;delete_disable()
						delete_disable();
						currentdiv=divcount;							
						//console.log("prevweightvalue",prevweightvalue);
						check_flag="false";
						//console.log("22222222222222222222222222222222");
						if(prevweightvalue<0)
						{
							//console.log("333333333333333333333333333333333333333333");
							check_flag="true"
							//console.log("prevweightvalue------------if",prevweightvalue);
							var divcounttt=divcount;
							divcounttt++;
                            if($('#mainpage').children().is('#divfrm'+divcounttt+'')==true)
								{
                                    //$('.divf').removeClass("active");
									//$('#divfrm'+divcount+'').addClass("active");
									//console.log("4444444444444444444444444444444444444444444");
                                    var remdiv=$(this).detach().prependTo('#divfrm'+divcounttt+'');									//document.getElementById('divcount').value = divcounttt;
									$('#divfrm'+divcount+'').children('div').each(function(index, element)
									{
										var prevweightvalue_new = 22;
										var tempweight_value=$(this).attr('elementweight');
										tempweight_value=parseInt(tempweight_value)
										prevweightvalue_new=parseInt(prevweightvalue_new)-tempweight_value;
										document.getElementById('weight').value = prevweightvalue_new;
									})
									//document.getElementById('weight').value = prevweightvalue;
									//console.log("divcounttt------------",divcounttt);
									//currentdiv=divcounttt;	
									//console.log("detachhhhhhhhh",remdiv,currentdiv)
									rearrange();
								}
								else
								{
									//console.log("55555555555555555555555555555555555555555");
									totaldivcount=divcounttt;
                                    document.getElementById('totaldivcount').value = totaldivcount;
									$('<div id="divfrm'+divcounttt+'" class="divf" align="center"></div>').appendTo('#mainpage');
                                    $('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value=""  img_desc="" img_src="" start="" end="" file_name="" img_id="" relative_url=""></input>').appendTo('#divfrm'+divcounttt+'');
									$('#template_name').val("");
									$('.device').css("background-image", "none");
									//$('.divf').removeClass("active");
									//$('#divfrm'+divcounttt+'').addClass("active");
                                 	$('#divfrm'+divcounttt+'').hide();
									totaldivindex++;
									document.getElementById('divcount').value = totaldivcount;delete_disable()
									weightvalue=22;
									var elweight=$(this).attr('elementweight');
									weightvalue=parseInt(weightvalue)-elweight;
									document.getElementById('weight').value = weightvalue;
									pageclear();
									label = totaldivcount;
									$(".divf").sortable({
									forcePlaceholderSize: true,
									axis: "y",
									scroll: false,
									sort: function () {},
									placeholder: 'ui-state-highlight',
									receive: function () {},
									update: function (event, ui) {}
									});
									var remdiv=$(this).detach().appendTo('#divfrm'+divcounttt+'');
									//document.getElementById('divcount').value = divcounttt;
									currentdiv=divcounttt;	
									rearrange();		
										
								}
							for(i=divindex;i<=totaldivindex;i++)
							{
								//console.log("66666666666666666666666666666666666666666666666")
								var curdivid=$("#mainpage").children('div').eq(i).attr('id');
								var redivtempcount = curdivid.replace(/\D/g,'');
								var inprevweightvalue=22;
								$('#divfrm'+redivtempcount+'').children('div').each(function(index, element)
								{
									//console.log("77777777777777777777777777777777777777");
									var intempweightvalue=$(this).attr('elementweight');
									intempweightvalue=parseInt(intempweightvalue)
									inprevweightvalue=parseInt(inprevweightvalue)-intempweightvalue;	
										if(inprevweightvalue<0)
											{
												//console.log("8888888888888888888888888888888888");
												    var redivcount=redivtempcount;
												    redivcount++;
													var cchee=$('#mainpage').children().is('#divfrm'+redivcount+'');
													if($('#mainpage').children().is('#divfrm'+redivcount+'')==true)
														{
															//console.log("9999999999999999999999999999999999999")
															var remdivv=$(this).detach().prependTo('#divfrm'+redivcount+'');
                                                            //$('.divf').removeClass("active");
															//$('#divfrm'+redivcount+'').addClass("active");
															//document.getElementById('divcount').value = redivcount;
															document.getElementById('weight').value = inprevweightvalue;
															//currentdiv=divcounttt;
														}
													else
														{
															//console.log("101010101010101010101001111100000");
															totaldivcount=redivcount;
															$('<div id="divfrm'+redivcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
                                                            $('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value="" relative_url="" img_desc="" img_src="" start="" end="" file_name="" img_id=""></input>').appendTo('#divfrm'+redivcount+'');
															$('#template_name').val("");
															$('.device').css("background-image", "none");				
															//$('.divf').removeClass("active");
															//$('#divfrm'+redivcount+'').addClass("active");
                                                         	$('#divfrm'+redivcount+'').hide();
															totaldivindex++;
															//document.getElementById('divcount').value = totaldivcount;
															//weightvalue=22;
															var elweight=$(this).attr('elementweight');
															//weightvalue=parseInt(weightvalue)-elweight;
															//document.getElementById('weight').value = weightvalue;
															pageclear();
															label = totaldivcount;
															$(".divf").sortable({
															forcePlaceholderSize: true,
															axis: "y",
															scroll: false,
															sort: function () {},
															placeholder: 'ui-state-highlight',
															receive: function () {},
															update: function (event, ui) {}
															});
															var remdivvv=$(this).detach().appendTo('#divfrm'+redivcount+'');	
															currentdiv=redivcount;															
														}
											}
								})
								
							}
						}
						else
						{
							
						}

					}); //
					if(check_flag=="false" && prevweightvalue > 0)
						{
							//console.log("prevweightvalue------------else__IFFFFF",prevweightvalue);
							rearrange();
							document.getElementById('weight').value = prevweightvalue;
							document.getElementById('divcount').value = divcount;delete_disable();
							currentdiv=divcount;
							check_flag="";
						}
						 
				}
				else
				{
							//console.log("11111---111---11--11--11--11--11--11")
							rearrange();
							document.getElementById('weight').value = weightvalue;
							document.getElementById('divcount').value = divcount;delete_disable();
							currentdiv=divcount; 
							
							
				}
				//currentdiv=divcount;
				
			}
			else
			{
				//console.log("1212121212121212122121212121212121");
				totaldivcount=divcount;
                //$('.device').css('background-image', 'none');
				$('<div id="divfrm'+divcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
                $('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value=""  img_desc="" img_src="" start="" end="" file_name="" img_id="" relative_url=""></input>').appendTo('#divfrm'+divcount+'');
				$('#template_name').val("");
				$('.device').css("background-image", "none");
				$('.divf').removeClass("active");
				$('#divfrm'+divcount+'').addClass("active");
             	document.getElementById('divcount').value = totaldivcount;delete_disable()
				document.getElementById('totaldivcount').value = totaldivcount;
				weightvalue=22;
				weightvalue=parseInt(weightvalue)-elementweight;
				document.getElementById('weight').value = weightvalue;
				pageclear();
				label = totaldivcount;
				$(".divf").sortable({
				forcePlaceholderSize: true,
				axis: "y",
				scroll: false,
			    sort: function () {},
			    placeholder: 'ui-state-highlight',
			    receive: function () {},
			    update: function (event, ui) {}
				});
				add();
				currentdiv=divcount;
			}
		
	}

function checkvall()
		{
			 
			 document.getElementById('weight').value = weightvalue;
			 document.getElementById('divcount').value = divcount;
			 delete_disable();
			 add();

		}
function add()
{
count+=1;
if(!$('#mainpage').children('div').children('div').hasClass("first_sec"))
		{ 
		      
	            {
					if(fType != 'SBreak')
					{
                      first_elementweight=2;
				      weightvalue=parseInt(weightvalue)-first_elementweight;
					  document.getElementById('weight').value = weightvalue;
					  
/* if($('.deviceheader').hasClass('edit'))
{
$('<div id="div" class="crdiv'+count+' header_sec breadcrumb sec col-md-12" elementweight="4"><label><h3>profile information</h3></label></div>').appendTo('#divfrm'+divcount+'');
			
					  var val = document.getElementById('weight').value
					  val = parseInt(val)-4;
					  document.getElementById('weight').value = val;
} */
$('<div id="div'+count+'" class="crdiv'+count+' first_sec breadcrumb sec col-md-12" elementweight="'+first_elementweight+'"><label class="labcheck col-md-4 control-label" name="section" id="Label'+count+'" name="Label[]" chkvalue='+chkvalue+' chkkeyvalue='+chkkeyvalue+' des=""></label><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a></div>').appendTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text(lang.app_section_name);
		
		//if blank App
		var blank_check= $('input[name=blank_app]:checked').val();
		if(blank_check == "yes")
		{
			$('.first_sec').addClass("hide");
		}
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput_ form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="hide checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="hide checkbox-inline"><input id="isakeyy'+count+'" name="" class="isakey" type="checkbox" value="">Is a key?</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea_" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
		count++;
					}
					
					
	       }
		};
		
if(fType == 'SBreak')
{

if(!$('#mainpage').children('div').children('div').hasClass("first_sec"))
{
					{

/* if($('.deviceheader').hasClass('edit'))
{
$('<div id="div" class="crdiv'+count+' header_sec breadcrumb sec col-md-12" elementweight="4"><label><h3>profile information</h3></label></div>').appendTo('#divfrm'+divcount+'');
			
					  var val = document.getElementById('weight').value
					  val = parseInt(val)-4;
					  document.getElementById('weight').value = val;
} */
$('<div id="div'+count+'" class="crdiv'+count+' first_sec breadcrumb sec col-md-12" elementweight="'+elementweight+'"><label class="labcheck col-md-4 control-label" name="section" id="Label'+count+'" name="Label[]" chkvalue='+chkvalue+' chkkeyvalue='+chkkeyvalue+' des=""></label><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a></div>').appendTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text(lang.app_section_name);
		//$('#Label'+count+'').text(fLabel);
		$('#intxt'+count+'').attr('type', fType);//sssssssss
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		//if blank App
		var blank_check= $('input[name=blank_app]:checked').val();
		if(blank_check == "yes")
		{
			$('.first_sec').addClass("hide");
		}
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput_ form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="hide checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="hide checkbox-inline"><input id="isakeyy'+count+'" name="" class="isakey" type="checkbox" value="">Is a key?</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea_" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
		//count++;
					}	
					$('#AddMoreFileBox').show();
}
else
{
//weightvalue=weightvalue-1;
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb sec col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck col-md-4 control-label" name="section" id="Label'+count+'" name="Label[]" chkvalue="TRUE" chkkeyvalue="TRUE" des=""></label><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text(lang.app_section_name);
		//$('#Label'+count+'').text(fLabel);
		$('#intxt'+count+'').attr('type', fType);//sssssssss
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput_ form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="hide checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea_" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
		$('#AddMoreFileBox').show();		
}
}//end of text
if(fType == 'wtext')
{

$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="2"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="wtext" chkvalue="FALSE" chkkeyvalue="FALSE"></label><hr class="widget col-md-4 hralign"><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
		$('#Label'+count+'').text(lang.app_field_name);
		$('#intxt'+count+'').attr('type',fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="" disabled>'+lang.app_req+'</label><label class="checkbox-inline"></label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min+' value="" disabled></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max+' value="" disabled></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
$('#AddMoreFileBox').show();		
				
}

if(fType == 'blocktext')
{

$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="2"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="blocktext" chkvalue="FALSE" chkkeyvalue="FALSE"></label><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
		$('#Label'+count+'').text("Field Name");
		$('#intxt'+count+'').attr('type',fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="" disabled>Required</label><label class="checkbox-inline"></label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value="" disabled></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value="" disabled></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
$('#AddMoreFileBox').show();		
				
}


if(fType == 'wtextarea')
{

$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="wtextarea" chkvalue="FALSE" chkkeyvalue="FALSE"></label><div class="col-md-5 hralign"><hr class="widget"><hr class="widget"><hr class="widget"></div></textarea><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text(lang.app_field_name);
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="" disabled>Required</label><label class="checkbox-inline"></label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value="" disabled></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value="" disabled></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
$('#AddMoreFileBox').show();
				
				
}
if(fType == 'imageElem')
{

$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="imageElem" chkvalue="FALSE" chkkeyvalue="FALSE"></label></textarea><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button><div class="col- hralign" style="clear: both;"><img src="http://35.154.116.243/PaaS/bootstrap/dist/img/image_preview.png" alt="" style="height: 250px;width: 500px;"></div></div>').appendTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text("Image field");
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline check_align"><input id="sameas'+count+'" name="" class="sameas" type="checkbox" value="">Same As</label><label class="checkbox-inline"></label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><select class="sameasfields col-md-6" id="sameasfields'+count+'" disabled="disabled"></select></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
$('#AddMoreFileBox').show();
				
				
}
if(fType == 'mblocktext')
{
$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="mblocktext" chkvalue="FALSE" chkkeyvalue="FALSE"></label><div class="col-md-6 hralign" style="padding: 0px;width: 300px;"><div class="sq_b_div"><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span></div><div class="sq_b_div"><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></div><div class="sq_b_div"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span></div></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text("Field Name");
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="" disabled>Required</label><label class="checkbox-inline"></label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value="" disabled></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value="" disabled></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
$('#AddMoreFileBox').show();
				
				
}

if(fType == 'freewriting')
{
$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="textarea" chkvalue='+chkvalue+' chkkeyvalue='+chkkeyvalue+'></label><textarea class="col-md-4 freewriting freewrite'+count+'" row="2" name="txt[]" id="intxt'+count+'" disabled></textarea><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text(lang.app_field_name);
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})

$('#AddMoreFileBox').show();

}

if ((fType == 'text') || (fType == 'password') || (fType == 'number'))
{
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue='+chkvalue+' chkkeyvalue='+chkkeyvalue+' notify="false" min="" max="" des=""></label><input type="" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text(lang.app_field_name);
		//$('#Labelmin'+count+'').text("Min");
		//$('#Labelmax'+count+'').text("Max");
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min+' value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max+' value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
		//$('#prop'+count+'').popover('show');
//		$('#prop'+count+'').trigger( "click" );
$('#AddMoreFileBox').show();


}//end of text
if(fType == 'mobile')
{
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue='+chkvalue+' chkkeyvalue='+chkkeyvalue+' notify="false" min="" max="" des=""></label><input type="" class="col-md-1 ialign " name="" id="intxt'+count+'" disabled style="padding-left: 0px;padding-right: 0px;margin-right: 2px;width: 49px;" placeholder="code"></input><input type="" class="col-md-3 ialign " name="" id="intxt'+count+'" disabled placeholder="Number"></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-3 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text(lang.app_field_name);
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min+' value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max+' value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
		//$('#prop'+count+'').popover('show');
//		$('#prop'+count+'').trigger( "click" );
$('#AddMoreFileBox').show();
}//end of text

if ((fType == 'date') || (fType == 'time') || (fType == 'color') || (fType == 'month'))
{
//weightvalue=weightvalue-1;
////alert(weightvalue);
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des="" notify="false"></label><input type="" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled style="height:20px"></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text(lang.app_field_name);
		//$('#Labelmin'+count+'').text("Min");
		//$('#Labelmax'+count+'').text("Max");
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
		//$('#prop'+count+'').popover('show');
//		$('#prop'+count+'').trigger( "click" );
$('#AddMoreFileBox').show();


}//end of date/time/color/month
if ((fType == 'retriever'))
{

$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="0"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des=""></label><a href="javascript:void(0);" id="prop'+count+'" class="popovr popovr_ret delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text(lang.app_field_name);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><select class="app_list" id="app_list'+count+'" idcount="'+count+'" style="max-width:182px;min-height:28px">'+applist_select_options+'</select></div><div class="form-inline check_align"><select class="sec_list section'+count+'" idcount="'+count+'" id="section'+count+'" style="width:182px;min-height:28px;margin-right:10px;"><option value="none">select section</option></select><select class="field_list" idcount="'+count+'" id="field'+count+'" style="width:182px;min-height:28px"><option value="none">select field</option></select></div><div class="row col-md-12"><select class="retrievelist multiselect" idcount="'+count+'" id="retrieve'+count+'" style="width:182px;min-height:28px" multiple="multiple"><option value="none">select field</option></select><input type="button" class="btn btn-default btn-sm popsaveret col-md-offset-4" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})/* .on('click',function () {
            $('#retrieve'+count+'').multiselect({buttonWidth: '182px',nonSelectedText:'Select Mappers',});
        }); 
 */
		/* $('#retrieve'+count+'').multiselect(); *//* {
	    	 includeSelectAllOption: true
			 });  */
		

$('#AddMoreFileBox').show();
}
if ((fType == 'mapper'))
{

$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="0"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des=""></label><a href="javascript:void(0);" id="prop'+count+'" cnt="'+count+'" class="mapbtn delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text(lang.app_field_name);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><div class="form-inline check_align"><select class="aplist" id="aplist'+count+'" idcount="'+count+'" style="width:182px;min-height:28px;margin-right:5px;"></select><select class="maplist" id="maplist'+count+'" idcount="'+count+'" style="width:182px;min-height:28px"></select></div><div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavemap popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')  
		})/* .on('click',function () {
            $('#maplist'+count+'').multiselect({buttonWidth: '182px',nonSelectedText:'Select Mappers',});
			//$('#aplist'+count+'').multiselect({buttonWidth: '182px',nonSelectedText:'Select App',});
        });  */

$('#AddMoreFileBox').show();
}
if ((fType == 'file'))
{

$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des=""></label><input type="file" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text(lang.app_field_name);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})


$('#AddMoreFileBox').show();
}//end of file

if ((fType == 'range'))
{
//weightvalue=weightvalue-1;
////alert(weightvalue);

$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des="" notify="false"></label><input type="range" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled style="width:176px"></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text(lang.app_field_name);
		//$('#Labelmin'+count+'').text("From");
		//$('#Labelmax'+count+'').text("To");
		//$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		//tAdd_Txtbox(fLabel,fType,"0","60",chkvalue, chkkeyvalue);//JSON Creation
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">notify</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
		
$('#AddMoreFileBox').show();
}//end of range

if(fType == 'description')
{
//weightvalue=weightvalue-1;
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb ins desc col-md-12 draggable" elementweight="6"><label class="labcheck col-md-4 control-label" name="instructions" id="Label'+count+'" name="Label[]" chkvalue="TRUE" chkkeyvalue="TRUE" des="" instruct=""></label><div class="inst lab"><p class="labelch labcheck" id="inss'+count+'"></p></div><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class="ins delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text(lang.app_instr_name);
		//$('#Label'+count+'').text(fLabel);
		//$('#intxt'+count+'').attr('type', fType);//sssssssss
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="hide checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label></div><div class="form-inline ins"><textarea class="ins'+count+' custom-scroll popinstextarea form-control col-md-5" id="ins'+count+'" type="text" value="" placeholder='+lang.app_instr+'></textarea><textarea class="des'+count+' custom-scroll popinstextarea form-control col-md-5" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsaveins popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		
		})
		$('#AddMoreFileBox').show();		
}//end of text

else if (fType == 'textarea')
{
	// console.log("%%%%%%%%%%%%%%%%%%%%%");
//weightvalue=weightvalue-1;
$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="textarea" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des="" notify="false"></label><weight style="visibility:hidden">textarea</weight><textarea class="col-md-4 textdisable" row="2" name="txt[]" id="intxt'+count+'" disabled></textarea><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');


		$('#Label'+count+'').text(lang.app_area_name);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min+' value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max+' value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
$('#AddMoreFileBox').show();
}//textarea end


else if (fType == 'photo')
{
$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="photo" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des="" notify="false"></label><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button><div class="col-md-3 photo_element"><i class="glyphicon glyphicon-user"></i></div></div>').appendTo('#divfrm'+divcount+'');


		$('#Label'+count+'').text(lang.app_photo_name);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline hide"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min_size+' value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max_size+' value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
$('#AddMoreFileBox').show();
}

else if (fType == 'newline')
{
//weightvalue=weightvalue-1;
console.log("nnnnnnnnnnnnnnnnn");
$('<div id="div'+count+'" class="crdiv'+count+' opac breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="hide" name="newline"></label><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

		//$('#Label'+count+'').text("Field Name");
		//$('#Labelmin'+count+'').text("Min");
		//$('#Labelmax'+count+'').text("Max");
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		//$('#prop'+count+'').popover({
		//html:true,
		//title: 'Properties',
		//content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		//})
		//$('#prop'+count+'').popover('show');
//		$('#prop'+count+'').trigger( "click" );
$('#AddMoreFileBox').show();


}//end of text
else if (fType == 'select')//user click on select
{
		//weightvalue=weightvalue-1;
	    tagval="value,value,value";
		
	$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4 control-label" name="select" id="Label'+count+'" chkvalue="TRUE" chkkeyvalue="TRUE" des="" tags="" notify="false"></label><div id="selectdiv'+count+'" class="col-md-5"><label class="select"><select id="select'+count+'"></select></label></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
	
		var tcount=1;
		$('#Label'+count+'').text(lang.app_select_name);	
		var selectvalue = tagval.split(",");//splitting tag values
		for (var i in selectvalue)// allocating the values to radio values
		{
		
		var selectlabel=selectvalue[i];
		selectvalue[i]=new Array();// Creatinga Array for JSON
		selectvalue[i].label = ''+selectlabel+'';
		selectvalue[i].value= ''+selectlabel+'';
		$('<option value="'+selectlabel+'">'+selectlabel+'</option>').appendTo('#select'+count+'');
		
		tcount++;
		}
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="selects'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><input type="button" class="btn btn-default btn-sm popsavesel popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div>')//$('#divprop'+count+'').html()
		})
		$('#prop'+count+'').on('click',function(e)
		{
			var addusr= $(this).attr('id');
			tempcount = addusr.replace(/\D/g,'');
			$('#selects'+tempcount+'').tagsinput({
			})
		})
		$('#AddMoreFileBox').show();
		
}//radio end

else if (fType == 'MChoice')//user click on select
{
		//weightvalue=weightvalue-1;
	    tagval="value,value,value";
		
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4 control-label" id="Label'+count+'" name="mchoice" chkvalue="TRUE" chkkeyvalue="TRUE" des="" notify="false" tags=""></label><div id="selectdiv'+count+'" class="col-md-5"><label class="select select-multiple"><select multiple class="custom-scroll" id="select'+count+'"></select></label></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
			//<div id="select'+count+'" class="col-md-5"></div><i id="selectspanadd'+count+'" class="addlabel addlabels glyphicon glyphicon-plus"></i>
		var tcount=1;
		$('#Label'+count+'').text(lang.app_Mselect_name);	
		var selectvalue = tagval.split(",");//splitting tag values
		for (var i in selectvalue)// allocating the values to radio values
		{
		
		var selectlabel=selectvalue[i];
		selectvalue[i]=new Array();// Creatinga Array for JSON
		selectvalue[i].label = ''+selectlabel+'';
		selectvalue[i].value= ''+selectlabel+'';
		$('<option value="'+selectlabel+'">'+selectlabel+'</option>').appendTo('#select'+count+'');
		tcount++;
		}
		
		//tAdd_Radio(fLabel, radiovalue, chkvalue, chkkeyvalue);//JSON Creation
		//$('#tagdiv').remove();
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">notify</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="selects'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="button" class="btn btn-default btn-sm popsavesel popsavebtn" id="savebtn'+count+'" value="Save"/></div>')//$('#divprop'+count+'').html()
		})
		$('#prop'+count+'').on('click',function(e)
		{
			// console.log("clicked");
			var addusr= $(this).attr('id');
			tempcount = addusr.replace(/\D/g,'');
			$('#selects'+tempcount+'').tagsinput({
			})
		})
		$('#AddMoreFileBox').show();
		//$('#prop'+count+'').trigger( "click" );
}
else if (fType == 'radio') // User Click on Radio button
{
		//weightvalue=weightvalue-1;
	    tagval="value,value,value";
		
	$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4 control-label" id="Label'+count+'" name="radio" chkvalue="TRUE" chkkeyvalue="TRUE" des="" tags="" notify="false"></label><div id="raddiv'+count+'" class="col-md-6 radd"></div><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
		
	//<div id="radiodiv'+count+'" class="col-md-5"></div><i id="radiospanadd'+count+'" class="addlabel addlabelr glyphicon glyphicon-plus"></i>
		var tcount=1;
		$('#Label'+count+'').text(lang.app_radio_name);	
		var radiovalue = tagval.split(",");//splitting tag values
		for (var i in radiovalue)// allocating the values to radio values
		{
		
		var radiolabel=radiovalue[i];
		radiovalue[i]=new Array();// Creatinga Array for JSON
		radiovalue[i].label = ''+radiolabel+'';
		radiovalue[i].value= ''+radiolabel+'';

		$('<label class="radio radio-inline radiomargin"><input type="radio" name="radio-inline" value="'+radiolabel+'" checked="checked"/>'+radiolabel+'</label>').appendTo('#raddiv'+count+'');
		
		tcount++;
		}
		
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="radioval'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea>'+'<input type="button" class="btn btn-default btn-sm popsaverad popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div>')//$('#divprop'+count+'').html()
		})
		$('#prop'+count+'').on('click',function(e)
		{
			var radioid= $(this).attr('id');
			tempcount = radioid.replace(/\D/g,'');
			$('#radioval'+tempcount+'').tagsinput({
			})
		})
		$('#AddMoreFileBox').show();
}//radio end

else if (fType == 'checkbox') // User Click on checkbox dddd
{
//weightvalue=weightvalue-1;

		tagval ="value,value,value";
		
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4 control-label" id="Label'+count+'" name="checkbox" chkvalue="TRUE" chkkeyvalue="TRUE" des="" tags="" notify="false"></label><div id="chkdiv'+count+'" class="col-md-6"></div><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
	
		//<div id="checkdiv'+count+'" class="col-md-5"></div><i id="checkspanadd'+count+'" class="addlabelc addlabel glyphicon glyphicon-plus"></i>
	
		$('#Label'+count+'').text(lang.app_checkbox_name);	
		var chekvalue = tagval.split(",");//splitting tag values
		var tcount=1;
		
		for (var i in chekvalue)// allocating the values to Checkbox
		{
		var chklabel=chekvalue[i];
		chekvalue[i]=new Array();// Creatinga Array for JSON
		chekvalue[i].label = ''+chklabel+'';
		chekvalue[i].value= ''+chklabel+'';
		$('<label class="checkbox checkbox-inline radiomargin"><input type="checkbox" name="checkbox-inline" value="'+chklabel+'" checked="checked"/>'+chklabel+'</label>').appendTo('#chkdiv'+count+'');
		}
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="chkval'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><input type="button" class="btn btn-default btn-sm popsavechk popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div>')//$('#divprop'+count+'').html()
		})
		$('#prop'+count+'').on('click',function(e)
		{
			var radioid= $(this).attr('id');
			tempcount = radioid.replace(/\D/g,'');
			$('#chkval'+tempcount+'').tagsinput({
			})
		})
		$('#AddMoreFileBox').show();
	}//check box end
}
//		if(weightvalue==0)
//		{
//		$('#divfrm'+divcount+'').hide();
//		divcount=divcount+1;
//			if($('#divfrm'+divcount+'').css('display') == 'none')
//			{
//				////alert("inside show")
//				$('#divfrm'+divcount+'').show();
//				var ccount = $('#divfrm'+divcount+'').children().length;
//				////alert(ccount)
//				var tweig=tempweight-ccount;
//				////alert(tweig)
//			    document.getElementById('weight').value = tweig;
//				document.getElementById('divcount').value = divcount;
//				currentdiv=divcount; 
//			}
//			else
//			{
//				totaldivcount=divcount;
//				$('<div id="divfrm'+divcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
//				document.getElementById('divcount').value = totaldivcount;
//				weigh=5;
//				document.getElementById('weight').value = weigh;
//				pageclear();
//				label = totaldivcount;
//				//tJasonPage[label] = {};
//				$(".divf").sortable({
//				forcePlaceholderSize: true,
//				axis: "y",
//				scroll: false,
//			    sort: function () {},
//			    placeholder: 'ui-state-highlight',
//			    receive: function () {},
//			    update: function (event, ui) {}
//				});
//			}
//		}
//		else
//		{
//			 //wwwwwwwwww
//			 
//			 console.log(weightvalue);
//			 ////alert(weightvalue);
//			 ////alert(divcount);
//			 document.getElementById('weight').value = weightvalue;
//			 console.log(weightvalue);
//			 document.getElementById('divcount').value = divcount;
//			 console.log(divcount);
//
//		}
function rearrange()
{
count+=1;
// console.log("**********************");
if(fType == 'wtext')
{
//weightvalue=//weightvalue-2;
////alert(weightvalue);
$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="wtext" chkvalue="FALSE" chkkeyvalue="FALSE"></label><hr class="widget col-md-4 hralign"><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');
//$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb row col-md-12"><label class="labcheck lalign col-md-4 control-label id="Label'+count+'" name="Label[]"></label><hr class="widget col-md-5 hralign"><div id="hiddiv" class="col-md-2 pull-right"><button type="button" id="hidbtn'+count+'" alt="Delete" class="removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus-sign"></span></button></div>').prependTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text(lang.app_field_name);
		$('#intxt'+count+'').attr('type',fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		//tAdd_Txtbox(fLabel,fType,"0","60",chkvalue, chkkeyvalue);//JSON Creation
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="" disabled>Required</label><label class="checkbox-inline"></label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value="" disabled></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value="" disabled></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
$('#AddMoreFileBox').show();		
				
}

if(fType == 'blocktext')
{
//weightvalue=//weightvalue-2;
////alert(weightvalue);
$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="2"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="blocktext" chkvalue="FALSE" chkkeyvalue="FALSE"></label><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
//$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb row col-md-12"><label class="labcheck lalign col-md-4 control-label id="Label'+count+'" name="Label[]"></label><hr class="widget col-md-5 hralign"><div id="hiddiv" class="col-md-2 pull-right"><button type="button" id="hidbtn'+count+'" alt="Delete" class="removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus-sign"></span></button></div>').prependTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text("Field Name");
		$('#intxt'+count+'').attr('type',fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		//tAdd_Txtbox(fLabel,fType,"0","60",chkvalue, chkkeyvalue);//JSON Creation
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="" disabled>Required</label><label class="checkbox-inline"></label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value="" disabled></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value="" disabled></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
$('#AddMoreFileBox').show();		
				
}

if(fType == 'wtextarea')
{
//weightvalue=weightvalue-4;
////alert(weightvalue);
$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="wtextarea" chkvalue="FALSE" chkkeyvalue="FALSE"></label><div class="col-md-5 hralign"><hr class="widget"><hr class="widget"><hr class="widget"></div></textarea><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');
//$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb row col-md-12 draggable"><label class="labcheck llalign col-md-4 control-label" id="Label'+count+'" name="Label[]"></label><div class="col-md-5 hralign"><hr class="widget"><hr class="widget"><hr class="widget"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="bmalign pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus-sign"></span></button></div>').prependTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text(lang.app_field_name);
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		//tAdd_Txtbox(fLabel,fType,"0","60",chkvalue, chkkeyvalue);//JSON Creation
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="" disabled>'+lang.app_req+'</label><label class="checkbox-inline"></label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min+' value="" disabled></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max+' value="" disabled></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
$('#AddMoreFileBox').show();
				
				
}

if(fType == 'imageElem')
{
//weightvalue=weightvalue-4;
////alert(weightvalue);
$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="imageElem" chkvalue="FALSE" chkkeyvalue="FALSE"></label></textarea><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button><div class="col- hralign" style="clear: both;"><img src="http://35.154.116.243/PaaS/bootstrap/dist/img/image_preview.png" alt="" style="height: 250px;width: 500px;"></div></div>').prependTo('#divfrm'+divcount+'');
//$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb row col-md-12 draggable"><label class="labcheck llalign col-md-4 control-label" id="Label'+count+'" name="Label[]"></label><div class="col-md-5 hralign"><hr class="widget"><hr class="widget"><hr class="widget"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="bmalign pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus-sign"></span></button></div>').prependTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text("Image field");
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		//tAdd_Txtbox(fLabel,fType,"0","60",chkvalue, chkkeyvalue);//JSON Creation
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline check_align"><input id="sameas'+count+'" name="" class="sameas" type="checkbox" value="">Same As</label><label class="checkbox-inline"></label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><select class="sameasfields col-md-6" id="sameasfields'+count+'" disabled="disabled"></select></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
$('#AddMoreFileBox').show();
				
				
}

if(fType == 'mblocktext')
{
//weightvalue=weightvalue-4;
////alert(weightvalue);
$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="mblocktext" chkvalue="FALSE" chkkeyvalue="FALSE"></label><div class="col-md-6 hralign" style="padding: 0px;width: 300px;"><div class="sq_b_div"><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span></div><div class="sq_b_div"><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></div><div class="sq_b_div"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span><span class="col-md-1 sq_wr"></span></div></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');
//$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb row col-md-12 draggable"><label class="labcheck llalign col-md-4 control-label" id="Label'+count+'" name="Label[]"></label><div class="col-md-5 hralign"><hr class="widget"><hr class="widget"><hr class="widget"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="bmalign pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus-sign"></span></button></div>').prependTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text("Field Name");
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		//tAdd_Txtbox(fLabel,fType,"0","60",chkvalue, chkkeyvalue);//JSON Creation
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="" disabled>'+lang.app_req+'</label><label class="checkbox-inline"></label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min+' value="" disabled></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max+' value="" disabled></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
$('#AddMoreFileBox').show();
				
				
}


if(fType == 'freewriting')
{
//weightvalue=weightvalue-1;
$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="textarea" chkvalue='+chkvalue+' chkkeyvalue='+chkkeyvalue+'></label><textarea class="col-md-4 freewriting freewrite'+count+'" row="2" name="txt[]" id="intxt'+count+'" disabled></textarea><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text(lang.app_field_name);
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})

$('#AddMoreFileBox').show();

}

if ((fType == 'text') || (fType == 'password') || (fType == 'number'))
{
//weightvalue=weightvalue-1;

$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des="" notify="false"></label><input type="" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text(lang.app_field_name);
		//$('#Labelmin'+count+'').text("Min");
		//$('#Labelmax'+count+'').text("Max");
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min+' value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max+' value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
		//$('#prop'+count+'').popover('show');
//		$('#prop'+count+'').trigger( "click" );
$('#AddMoreFileBox').show();


}//end of text

if (fType == 'newline')
{
//weightvalue=weightvalue-1;
console.log("nnnnnnnnnnnnnnnnn");
$('<div id="div'+count+'" class="crdiv'+count+' opac breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des=""></label><input type="" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

		//$('#Label'+count+'').text("Field Name");
		//$('#Labelmin'+count+'').text("Min");
		//$('#Labelmax'+count+'').text("Max");
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		//$('#prop'+count+'').popover({
		//html:true,
		//title: 'Properties',
		//content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		//})
		//$('#prop'+count+'').popover('show');
//		$('#prop'+count+'').trigger( "click" );
$('#AddMoreFileBox').show();


}//end of text

if(fType == 'mobile')
{
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue='+chkvalue+' chkkeyvalue='+chkkeyvalue+' notify="false" min="" max="" des=""></label><input type="" class="col-md-1 ialign " name="" id="intxt'+count+'" disabled style="padding-left: 0px;padding-right: 0px;margin-right: 2px;width: 49px;" placeholder="code"></input><input type="" class="col-md-3 ialign " name="" id="intxt'+count+'" disabled placeholder="Number"></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-3 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text(lang.app_field_name);
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min+' value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max+' value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
		//$('#prop'+count+'').popover('show');
//		$('#prop'+count+'').trigger( "click" );
$('#AddMoreFileBox').show();
}//end of text
if ((fType == 'date') || (fType == 'time') || (fType == 'color') || (fType == 'month'))
{
//weightvalue=weightvalue-1;
////alert(weightvalue);
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des="" notify="false"></label><input type="" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled style="height:20px"></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text(lang.app_field_name);
		//$('#Labelmin'+count+'').text("Min");
		//$('#Labelmax'+count+'').text("Max");
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
		//$('#prop'+count+'').popover('show');
//		$('#prop'+count+'').trigger( "click" );
$('#AddMoreFileBox').show();


}//end of date/time/color/month

if ((fType == 'file'))
{
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des="" notify="false"></label><input type="file" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text(lang.app_field_name);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value='+lang.app_desc+'/></div></div>')//$('#divprop'+count+'').html()
		})


$('#AddMoreFileBox').show();
}//end of file

if ((fType == 'range'))
{
//weightvalue=weightvalue-1;
////alert(weightvalue);

$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des=""></label><input type="range" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled style="width:176px"></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text(lang.app_field_name);
		//$('#Labelmin'+count+'').text("From");
		//$('#Labelmax'+count+'').text("To");
		//$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		//tAdd_Txtbox(fLabel,fType,"0","60",chkvalue, chkkeyvalue);//JSON Creation
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
		
$('#AddMoreFileBox').show();
}//end of range


if(fType == 'SBreak')
{
//weightvalue=weightvalue-1;
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb sec col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck col-md-4 control-label" name="section" id="Label'+count+'" name="Label[]" chkvalue="TRUE" chkkeyvalue="TRUE" des=""></label><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text(lang.app_section_name);
		//$('#Label'+count+'').text(fLabel);
		$('#intxt'+count+'').attr('type', fType);//sssssssss
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput_ form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="hide checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea_" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
		$('#AddMoreFileBox').show();		
}//end of text

if(fType == 'description')
{
//weightvalue=weightvalue-1;
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb ins desc col-md-12 draggable" elementweight="6"><label class="labcheck col-md-4 control-label" name="instructions" id="Label'+count+'" name="Label[]" chkvalue="TRUE" chkkeyvalue="TRUE" des="" instruct=""></label><div class="inst lab"><p class="labelch labcheck" id="inss'+count+'"></p></div><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class="ins delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text(lang.app_instr_name);
		//$('#Label'+count+'').text(fLabel);
		//$('#intxt'+count+'').attr('type', fType);//sssssssss
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="hide checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label></div><div class="form-inline ins"><textarea class="ins'+count+' custom-scroll popinstextarea form-control col-md-5" id="ins'+count+'" type="text" value="" placeholder='+lang.app_instr+'></textarea><textarea class="des'+count+' custom-scroll popinstextarea form-control col-md-5" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsaveins popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		
		})
		$('#AddMoreFileBox').show();		
}//end of text

else if (fType == 'textarea')
{
//weightvalue=weightvalue-1;
$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="textarea" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des=""></label><weight style="visibility:hidden">textarea</weight><textarea class="col-md-4 textdisable" row="2" name="txt[]" id="intxt'+count+'" disabled></textarea><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');


		$('#Label'+count+'').text(lang.app_area_name);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"></label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min+' value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max+' value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
$('#AddMoreFileBox').show();
}//textarea end

else if (fType == 'photo')
{
$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="photo" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des="" notify="false"></label><weight style="visibility:hidden">textarea</weight><div class="col-md-3 photo_element"><i class="glyphicon glyphicon-user"></i></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');


		$('#Label'+count+'').text(lang.app_photo_name);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline hide"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder='+lang.app_min_size+' value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder='+lang.app_max_size+' value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div></div>')//$('#divprop'+count+'').html()
		})
$('#AddMoreFileBox').show();
}

else if (fType == 'select')//user click on select
{
		//weightvalue=weightvalue-1;
	    tagval="value,value,value";
		
	$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4 control-label" name="select" id="Label'+count+'" chkvalue="TRUE" chkkeyvalue="TRUE" des="" tags="" notify="false"></label><div id="selectdiv'+count+'" class="col-md-5"><label class="select"><select id="select'+count+'"></select></label></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');
	
		var tcount=1;
		$('#Label'+count+'').text(lang.app_select_name);	
		var selectvalue = tagval.split(",");//splitting tag values
		for (var i in selectvalue)// allocating the values to radio values
		{
		
		var selectlabel=selectvalue[i];
		selectvalue[i]=new Array();// Creatinga Array for JSON
		selectvalue[i].label = ''+selectlabel+'';
		selectvalue[i].value= ''+selectlabel+'';
		$('<option value="'+selectlabel+'">'+selectlabel+'</option>').appendTo('#select'+count+'');
		
		tcount++;
		}
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="selects'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><input type="button" class="btn btn-default btn-sm popsavesel popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div>')//$('#divprop'+count+'').html()
		})
		$('#prop'+count+'').on('click',function(e)
		{
			var addusr= $(this).attr('id');
			tempcount = addusr.replace(/\D/g,'');
			$('#selects'+tempcount+'').tagsinput({
			})
		})
		$('#AddMoreFileBox').show();
		
}//radio end

else if (fType == 'MChoice')//user click on select
{
		//weightvalue=weightvalue-1;
	    tagval="value,value,value";
		
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4 control-label" id="Label'+count+'" name="mchoice" chkvalue="TRUE" chkkeyvalue="TRUE" des="" notify="false" tags=""></label><div id="selectdiv'+count+'" class="col-md-5"><label class="select select-multiple"><select multiple class="custom-scroll" id="select'+count+'"></select></label></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');
			//<div id="select'+count+'" class="col-md-5"></div><i id="selectspanadd'+count+'" class="addlabel addlabels glyphicon glyphicon-plus"></i>
		var tcount=1;
		$('#Label'+count+'').text(lang.app_Mselect_name);	
		var selectvalue = tagval.split(",");//splitting tag values
		for (var i in selectvalue)// allocating the values to radio values
		{
		
		var selectlabel=selectvalue[i];
		selectvalue[i]=new Array();// Creatinga Array for JSON
		selectvalue[i].label = ''+selectlabel+'';
		selectvalue[i].value= ''+selectlabel+'';
		$('<option value="'+selectlabel+'">'+selectlabel+'</option>').appendTo('#select'+count+'');
		tcount++;
		}
		
		//tAdd_Radio(fLabel, radiovalue, chkvalue, chkkeyvalue);//JSON Creation
		//$('#tagdiv').remove();
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="selects'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><input type="button" class="btn btn-default btn-sm popsavesel popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div>')//$('#divprop'+count+'').html()
		})
		$('#prop'+count+'').on('click',function(e)
		{
			// console.log("clicked");
			var addusr= $(this).attr('id');
			tempcount = addusr.replace(/\D/g,'');
			$('#selects'+tempcount+'').tagsinput({
			})
		})
		$('#AddMoreFileBox').show();
		//$('#prop'+count+'').trigger( "click" );
}
else if (fType == 'radio') // User Click on Radio button
{
		//weightvalue=weightvalue-1;
	    tagval="value,value,value";
		
	$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4 control-label" id="Label'+count+'" name="radio" chkvalue="TRUE" chkkeyvalue="TRUE" notify="false" des="" tags=""></label><div id="raddiv'+count+'" class="col-md-6 radd"></div><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');
		
	//<div id="radiodiv'+count+'" class="col-md-5"></div><i id="radiospanadd'+count+'" class="addlabel addlabelr glyphicon glyphicon-plus"></i>
		var tcount=1;
		$('#Label'+count+'').text(lang.app_radio_name);	
		var radiovalue = tagval.split(",");//splitting tag values
		for (var i in radiovalue)// allocating the values to radio values
		{
		
		var radiolabel=radiovalue[i];
		radiovalue[i]=new Array();// Creatinga Array for JSON
		radiovalue[i].label = ''+radiolabel+'';
		radiovalue[i].value= ''+radiolabel+'';

		$('<label class="radio radio-inline radiomargin"><input type="radio" name="radio-inline" value="'+radiolabel+'" checked="checked"/>'+radiolabel+'</label>').appendTo('#raddiv'+count+'');
		
		tcount++;
		}
		
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="radioval'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea>'+'<input type="button" class="btn btn-default btn-sm popsaverad popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div>')//$('#divprop'+count+'').html()
		})
		$('#prop'+count+'').on('click',function(e)
		{
			var radioid= $(this).attr('id');
			tempcount = radioid.replace(/\D/g,'');
			$('#radioval'+tempcount+'').tagsinput({
			})
		})
		$('#AddMoreFileBox').show();
}//radio end

else if (fType == 'checkbox') // User Click on checkbox dddd
{
//weightvalue=weightvalue-1;

		tagval ="value,value,value";
		
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4 control-label" id="Label'+count+'" name="checkbox" chkvalue="TRUE" chkkeyvalue="TRUE" des="" notify="false" tags=""></label><div id="chkdiv'+count+'" class="col-md-6"></div><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');
	
		//<div id="checkdiv'+count+'" class="col-md-5"></div><i id="checkspanadd'+count+'" class="addlabelc addlabel glyphicon glyphicon-plus"></i>
	
		$('#Label'+count+'').text(lang.app_checkbox_name);	
		var chekvalue = tagval.split(",");//splitting tag values
		var tcount=1;
		
		for (var i in chekvalue)// allocating the values to Checkbox
		{
		var chklabel=chekvalue[i];
		chekvalue[i]=new Array();// Creatinga Array for JSON
		chekvalue[i].label = ''+chklabel+'';
		chekvalue[i].value= ''+chklabel+'';
		$('<label class="checkbox checkbox-inline radiomargin"><input type="checkbox" name="checkbox-inline" value="'+chklabel+'" checked="checked"/>'+chklabel+'</label>').appendTo('#chkdiv'+count+'');
		}
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder='+lang.app_field_name+'/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">'+lang.app_req+'</label><label class="checkbox-inline"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">'+lang.app_notify+'</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="chkval'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder='+lang.app_desc+'></textarea><input type="button" class="btn btn-default btn-sm popsavechk popsavebtn" id="savebtn'+count+'" value='+lang.app_save+'></div>')//$('#divprop'+count+'').html()
		})
		$('#prop'+count+'').on('click',function(e)
		{
			var radioid= $(this).attr('id');
			tempcount = radioid.replace(/\D/g,'');
			$('#chkval'+tempcount+'').tagsinput({
			})
		})
		$('#AddMoreFileBox').show();
	}//check box end
}//function rearrange;
}//function adding
//}//else close for currentdiv>0
})//end of add class

	function create_options(current_type,current_id)
	{
		var options = "<option value='0'>Select field</option>";
		var current_section="";
		$('.mainpage').children('div').each(function(index, element) 
		{
			var Index = index+1;
			$(this).children('div').each(function()
			{
				if($(this).hasClass("sec"))
				{
					current_section = $(this).children('.labcheck').text();
					current_sec_id = $(this).children('.labcheck').attr("id");
					console.log(current_section)
				}
				var current_elem = $(this).children('.labcheck').attr("name") || "null";
				console.log(current_elem)
				var current_fieldName = $(this).children('.labcheck').text();
				console.log(current_fieldName)
				var current_fieldId = $(this).children('.labcheck').attr("id");
				if(current_elem == current_type && current_id != current_fieldId)
				{
					options += "<option sec_id='"+current_sec_id+"' sectn='"+current_section+"' page='page"+Index+"' value='"+current_fieldName+"' parentid="+current_fieldId+">"+current_fieldName+"</option>"
				}
			})
		})
		return options;
	}
	$('.cloned_true').on('shown.bs.popover', function () 
	{
		console.log("cloneeddddd")
		$(this).parents(".breadcrumb").find('.sameasfields').prop('disabled', false);
		$(this).parents(".breadcrumb").find('.sameas').prop('checked', true);
		var current_type = $(this).parents(".breadcrumb").find(".labcheck").attr("name")
		var current_id = $(this).parents(".breadcrumb").find(".labcheck").attr("id")
		console.log("current_type",current_type)
		var option_lists = create_options(current_type,current_id)
		$(this).parents(".breadcrumb").find('.sameasfields').html(option_lists)
		var seleceted_option = $(this).parents(".breadcrumb").find(".labcheck").attr("sameasfield");
		$(this).parents(".breadcrumb").find('.sameasfields').val(seleceted_option);
		$(this).removeClass("cloned_true");
	})
	$(document).on('click','.sameas', function (e) 
	{
		if($(this).is(':checked'))
		{
			console.log("Entered sameas click ")
			$(this).parents(".well").find('.sameasfields').prop('disabled', false);
			var current_type = $(this).parents(".breadcrumb").find(".labcheck").attr("name")
			var current_id = $(this).parents(".breadcrumb").find(".labcheck").attr("id")
			console.log("current_type",current_type)
			var option_lists = create_options(current_type,current_id)
			$(this).parents(".well").find('.sameasfields').html(option_lists)
		}
		else
		{
			$(this).parents(".well").find('.sameasfields').empty()
			$(this).parents(".well").find('.sameasfields').prop('disabled', true);
		}
	})
	$(document).on('click','.popsave', function (e) 
		{
			e.stopPropagation();
			e.preventDefault();
			////alert("clicked")
			var prop= $(this).attr('id');
			tempcount = prop.replace(/\D/g,'');
			// console.log("hiding"+tempcount);
			var nameval=$('#name'+tempcount+'').val();
			var minval=$('#min'+tempcount+'').val();
			var maxval=$('#max'+tempcount+'').val();
			var desval=$('#des'+tempcount+'').val();
			if($('#sameas'+tempcount+'').is(':checked'))
			{
				var sectn = $('#sameasfields'+tempcount+' option:selected').attr("sectn");
				var sectn_id = $('#sameasfields'+tempcount+' option:selected').attr("sec_id");
				var page = $('#sameasfields'+tempcount+' option:selected').attr("page");
				var parentid = $('#sameasfields'+tempcount+' option:selected').attr("parentid");
				var value = $('#sameasfields'+tempcount+'').val();
				console.log(sectn)
				console.log(page)
				console.log(value)
				//var index = $("div#mainpage div").index($('#Label'+tempcount+'').parents(".divf"));
				//console.log(index)
				//index = index+1;
				//var sectName = $('#Label'+tempcount+'').parent(".breadcrumb").prev(".sec").find(".labcheck").text()
				
				var pag = $('#Label'+tempcount+'').parents(".divf").attr("id")
				var pag_chil = pag.replace(/\D/g,'');
				$('#'+parentid+'').attr("sectn",sectn);
				$('#'+parentid+'').attr("sectn_id",sectn_id);
				$('#'+parentid+'').attr("page",'page'+pag_chil+'');
				$('#'+parentid+'').attr("cloned","true");
				$('#'+parentid+'').attr("sel_value",nameval);
				
				$('#Label'+tempcount+'').attr("sectn_id",sectn_id);
				$('#Label'+tempcount+'').attr("sectn",sectn);
				$('#Label'+tempcount+'').attr("page",page);
				$('#Label'+tempcount+'').attr("sel_value",value);
			}
			var req;
			if($('#notify'+tempcount+'').is(':checked'))
			{
				$('#Label'+tempcount+'').attr("notify","true");
			}
			else
			{
				$('#Label'+tempcount+'').attr("notify","false");
			}
			if($('#chkreqq'+tempcount+'').is(':checked'))
			{
				chkbxreq='';
				chkvalue="TRUE";
				req='*';
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
				if(!$('#Label'+tempcount+'').hasClass('required'))
				{
				$('#Label'+tempcount+'').addClass("required");
				}
	
			}
	
			else
			{
				chkbxreq='';
				chkvalue="FALSE"
				req='';
				if($('#Label'+tempcount+'').hasClass('required'))
				{
				$('#Label'+tempcount+'').removeClass("required");
				}
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
			}

			if($('#isakeyy'+tempcount+'').is(':checked'))
			{
				chkbxkey='key';
				chkkeyvalue="TRUE";
				chkvalue="TRUE";
				$('#Label'+tempcount+'').attr("chkkeyvalue",chkkeyvalue);
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
	
			}
			else
			{
				chkbxkey='';
				chkkeyvalue="FALSE"
				$('#Label'+tempcount+'').attr("chkkeyvalue",chkkeyvalue);
	
			}
			// console.log("hi"+nameval+minval+maxval+desval);
			if(nameval!='')
			{
			$('#Label'+tempcount+'').text(nameval);
			}
			else
			{
			$('#Label'+tempcount+'').text(lang.app_field_name);
			}
			//$('#name'+tempcount+'').attr("value",nameval);
			$('#Label'+tempcount+'').attr("min",minval);
			$('#Label'+tempcount+'').attr("max",maxval);
			$('#Label'+tempcount+'').attr("des",desval);
			//$('#fMin'+tempcount+'').attr("value",minval);
			//$('#fMax'+tempcount+'').attr("value",maxval);
			//$('#fDes'+tempcount+'').attr("value",desval);
			$('#prop'+tempcount+'').popover('hide')
	});	
	$(document).on('click','.popsavemap', function (e) 
	{
		e.stopPropagation();
		e.preventDefault();
		var prop= $(this).attr('id');
		var that = $(this).parents('.breadcrumb');
		divcount = $(this).parents('.divf').attr('id');
		divcount = divcount.replace(/\D/g,'');
		tempcount = prop.replace(/\D/g,'');
		$('#prop'+tempcount+'').removeClass("map_tocheck")
		var app_sel = $('#aplist'+tempcount+'').val() || "null";
		var prev_field = $('#Label'+tempcount+'').attr("prev_field") || '';
		var prev_app = $('#Label'+tempcount+'').attr("prev_app") || '';
		var maplist_sel = $('#maplist'+tempcount+' option:selected').text() || '';
		var maplist_type = $('#maplist'+tempcount+' option:selected').attr("type") || '';
		console.log("maplist_type",maplist_type)
		for(var i in app_chose)
		{
			if(i == app_sel)
			{
				app_chose[i]['is_used']=true;
				if($.inArray(maplist_sel, app_chose[i]['selected_fields']) === -1)
				{
					app_chose[i]['selected_fields'].push(maplist_sel);
				}
				$('#Label'+tempcount+'').attr("prev_app",app_sel)
				$('#Label'+tempcount+'').attr("prev_field",prev_field)
			}
			if(prev_app!='' && i == prev_app && i != app_sel)
			{
				console.log("inside prev_app")
				app_chose[i]['selected_fields'].splice( $.inArray(prev_field, app_chose[i]['selected_fields']), 1 );
				if(app_chose[i]['selected_fields'].length < 1)
				{
					app_chose[i]['is_used']=false;
				}
			}
		}
		$('#prop'+tempcount+'').popover('hide')
		var elementweight = getvalue(maplist_type)
		$('#div'+tempcount+'').attr("elementweight",elementweight)
		console.log("elementweight",typeof(elementweight))
		//divcount=currentdiv;
		var weightvalue=document.getElementById('weight').value; 
		weightvalue=parseInt(weightvalue)-elementweight;
		
		if(weightvalue < 0)
		{
			divcount = parseInt(divcount);
			checkval_(elementweight,divcount,that);
		}
		else
		{
			document.getElementById('weight').value = weightvalue
		}
		console.log(app_chose)
	});
	$(document).on('click','.popsaveret', function (e) 
	{
			console.log("popsaveretpopsaveretpopsaveret")
			e.stopPropagation();
			e.preventDefault();
			var prop= $(this).attr('id');
			tempcount = prop.replace(/\D/g,'');
			var mapp_options = '<option value="null">Select Field</option>';
			var selected_field = $('#retrieve'+tempcount+'').val() ||0;
			console.log("selected_field",selected_field);
			var ret_type = $('#field'+tempcount+' option:selected').attr("type") || '';
			console.log("ret_type",ret_type)
			$('#prop'+tempcount+'').removeClass("tocheck")
			$('#retrieve'+tempcount+' option:selected').each(function()
			{
				//console.log($(this).val());
				var val = $(this).val() || "null";
				//console.log($(this).attr("type"));		
				var typ = $(this).attr("type")
				var values = val.split("_");
				var section = values[1]
				var field = values[2]
				mapp_options +='<option type="'+typ+'" appd="'+$('#app_list'+tempcount+'').val()+'" value="'+val+'">'+field+'</option>'
			})
			var def_type = $('#Label'+tempcount+'').attr("retrieve_type");
			$('#Label'+tempcount+'').attr("retrieve_lists",selected_field);
			
			/* for(var i=0;i<selected_field.length;i++)
			{
				var values = selected_field[i].split("_");
				var section = values[1]
				var field = values[2]
				mapp_options +='<option appd="'+$('#app_list'+tempcount+'').val()+'" value="'+selected_field[i]+'">'+field+'</option>'
			} */
			var app_sel = $('#app_list'+tempcount+'').val() || "null";
			$('#Label'+tempcount+'').attr("id_ref",app_sel);
			if(app_sel != "null")
			{
				console.log(app_chose.hasOwnProperty(app_sel));
				if(!app_chose.hasOwnProperty(app_sel))
				{
					app_chose[app_sel]=[];
					app_chose[$('#app_list'+tempcount+'').val()]={label:$('#app_list'+tempcount+' option:selected').text(),value:$('#app_list'+tempcount+'').val(),is_used:false,fields:mapp_options,selected_fields:[]}
				}
				else
				{
					for(var i in app_chose)
					{
						if(i == app_sel)
						{
							app_chose[i]['fields']=mapp_options;
						}
					}
				}
				console.log(app_chose)
			}
			$('#prop'+tempcount+'').popover('hide')
			var elementweight = getvalue(ret_type)
			$('#div'+tempcount+'').attr("elementweight",elementweight)
			console.log("elementweight",typeof(elementweight))
			divcount=currentdiv;
			var weightvalue=document.getElementById('weight').value;
			weightvalue=parseInt(weightvalue)-elementweight;
			if(weightvalue < 0)
			{
				checkval_(elementweight);
			}
			else
			{
				document.getElementById('weight').value = weightvalue
			}
	});
	
	$(document).on('click','.popsavesel', function (e) 
	{
			e.stopPropagation();
			e.preventDefault();
			var prop= $(this).attr('id');
			tempcount = prop.replace(/\D/g,'');
			// console.log("hiding"+tempcount);
			var nameval=$('#name'+tempcount+'').val();
			var selecttag=$('#selects'+tempcount+'').val()
			var desval=$('#des'+tempcount+'').val();
			var tagsval=$('#selects'+tempcount+'').tagsinput('items')
			var req;
			if($('#notify'+tempcount+'').is(':checked'))
			{
				$('#Label'+tempcount+'').attr("notify","true");
			}
			else
			{
				$('#Label'+tempcount+'').attr("notify","false");
			}
			if($('#chkreqq'+tempcount+'').is(':checked'))
			{
				chkbxreq='';
				chkvalue="TRUE";
				req='*';
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
				if(!$('#Label'+tempcount+'').hasClass('required'))
				{
				$('#Label'+tempcount+'').addClass("required");
				}
	
			}
	
			else
			{
				req='';
				chkbxreq='';
				chkvalue="FALSE"
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
				if($('#Label'+tempcount+'').hasClass('required'))
				{
				$('#Label'+tempcount+'').removeClass("required");
				}
			}

			if($('#isakeyy'+tempcount+'').is(':checked'))
			{
				chkbxkey='key';
				chkkeyvalue="TRUE";
				chkvalue="TRUE";
				$('#Label'+tempcount+'').attr("chkkeyvalue",chkkeyvalue);
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
	
			}
			else
			{
				chkbxkey='';
				chkkeyvalue="FALSE"
				$('#Label'+tempcount+'').attr("chkkeyvalue",chkkeyvalue);
	
			}
			// console.log("hi"+nameval+desval);
			if(nameval!='')
			{
			$('#Label'+tempcount+'').text(nameval);
			}
			else
			{
			$('#Label'+tempcount+'').text(lang.app_select_name);
			}
			$('#select'+tempcount+'').empty();
			//$('#name'+tempcount+'').attr("value",nameval);
			
			// created for comma issue//
			var i;
			for(i=0;i<tagsval.length;i++)
			{
				$('<option value="'+tagsval[i]+'">'+tagsval[i]+'</option>').appendTo('#select'+tempcount+'');
			}
			tagsval = JSON.stringify(tagsval);
			// created for comma issue end//
			
			/*commented for comma issue
			var tagg = selecttag.split(",");
			for (var i in tagg)
			{
			$('<option value="'+tagg[i]+'">'+tagg[i]+'</option>').appendTo('#select'+tempcount+'');
			//$('#vpermintags'+tempcount+'').tagsinput('add', ''+tagg[i]+'');
			}
			commented for comma issue end*/
			
			$('#fDes'+tempcount+'').attr("value",desval);
			$('#Label'+tempcount+'').attr("des",desval);
			$('#Label'+tempcount+'').attr("tags",tagsval);
			$('#prop'+tempcount+'').popover('hide')
	});
	
	$(document).on('click','.popsaverad', function (e) 
		{
			e.stopPropagation();
			e.preventDefault();
			////alert("clicked")
			var prop= $(this).attr('id');
			tempcount = prop.replace(/\D/g,'');
			// console.log("hiding"+tempcount);
			var nameval=$('#name'+tempcount+'').val();
			var selecttag=$('#radioval'+tempcount+'').val()
			var desval=$('#des'+tempcount+'').val();
			var tagsval=$('#radioval'+tempcount+'').val();
			var req;
			if($('#notify'+tempcount+'').is(':checked'))
			{
				$('#Label'+tempcount+'').attr("notify","true");
			}
			else
			{
				$('#Label'+tempcount+'').attr("notify","false");
			}			
			if($('#chkreqq'+tempcount+'').is(':checked'))
			{
				chkbxreq='';
				req='*';
				chkvalue="TRUE";
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
				if(!$('#Label'+tempcount+'').hasClass('required'))
				{
				$('#Label'+tempcount+'').addClass("required");
				}
			}
	
			else
			{
				req='';
				chkbxreq='';
				chkvalue="FALSE"
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
				if($('#Label'+tempcount+'').hasClass('required'))
				{
				$('#Label'+tempcount+'').removeClass("required");
				}
			}

			if($('#isakeyy'+tempcount+'').is(':checked'))
			{
				chkbxkey='key';
				chkkeyvalue="TRUE";
				chkvalue="TRUE";
				$('#Label'+tempcount+'').attr("chkkeyvalue",chkkeyvalue);
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
	
			}
			else
			{
				chkbxkey='';
				chkkeyvalue="FALSE"
				$('#Label'+tempcount+'').attr("chkkeyvalue",chkkeyvalue);
	
			}
			if(nameval!='')
			{
			$('#Label'+tempcount+'').text(nameval);
			}
			else
			{
			$('#Label'+tempcount+'').text(lang.app_radio_name);
			}
			$('#raddiv'+tempcount+'').empty();
			var tagg = selecttag.split(",");
			for (var i in tagg)
			{
			$('<label class="radio radio-inline radiomargin"><input type="radio" name="radio-inline" value="'+tagg[i]+'" checked="checked"/>'+tagg[i]+'</label>').appendTo('#raddiv'+tempcount+'');
			}
			$('#Label'+tempcount+'').attr("des",desval);
			$('#Label'+tempcount+'').attr("tags",tagsval);
			$('#prop'+tempcount+'').popover('hide')
	});
	
	$(document).on('click','.popsavechk', function (e) 
		{
			e.stopPropagation();
			e.preventDefault();
			var prop= $(this).attr('id');
			tempcount = prop.replace(/\D/g,'');
			var nameval=$('#name'+tempcount+'').val();
			var selecttag=$('#chkval'+tempcount+'').val()
			var desval=$('#des'+tempcount+'').val();
			var tagsval=$('#chkval'+tempcount+'').val();
			var req;
			if($('#notify'+tempcount+'').is(':checked'))
			{
				$('#Label'+tempcount+'').attr("notify","true");
			}
			else
			{
				$('#Label'+tempcount+'').attr("notify","false");
			}
			if($('#chkreqq'+tempcount+'').is(':checked'))
			{
				chkbxreq='';
				req='*';
				chkvalue="TRUE";
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
				if(!$('#Label'+tempcount+'').hasClass('required'))
				{
				$('#Label'+tempcount+'').addClass("required");
				}
	
			}
	
			else
			{
				req='';
				chkbxreq='';
				chkvalue="FALSE"
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
				if($('#Label'+tempcount+'').hasClass('required'))
				{
				$('#Label'+tempcount+'').removeClass("required");
				}
			}

			if($('#isakeyy'+tempcount+'').is(':checked'))
			{
				chkbxkey='key';
				chkkeyvalue="TRUE";
				chkvalue="TRUE";
				$('#Label'+tempcount+'').attr("chkkeyvalue",chkkeyvalue);
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
	
			}
			else
			{
				chkbxkey='';
				chkkeyvalue="FALSE"
				$('#Label'+tempcount+'').attr("chkkeyvalue",chkkeyvalue);
	
			}
			// console.log("hi"+nameval+desval);
			if(nameval!='')
			{
			$('#Label'+tempcount+'').text(nameval);
			}
			else
			{
			$('#Label'+tempcount+'').text(lang.app_checkbox_name);
			}
			$('#chkdiv'+tempcount+'').empty();
			//$('#name'+tempcount+'').attr("value",nameval);
			var tagg = selecttag.split(",");
			for (var i in tagg)
			{
			$('<label class="checkbox checkbox-inline radiomargin"><input type="checkbox" name="checkbox-inline" value="'+tagg[i]+'" checked="checked"/>'+tagg[i]+'</label>').appendTo('#chkdiv'+tempcount+'');
			//$('#vpermintags'+tempcount+'').tagsinput('add', ''+tagg[i]+'');
			}
			$('#Label'+tempcount+'').attr("des",desval);
			$('#Label'+tempcount+'').attr("tags",tagsval);
			//$('#fDes'+tempcount+'').attr("value",desval);
			$('#prop'+tempcount+'').popover('hide')
	});

	$(document).on('click','.popsavesec', function (e) 
		{
			
			e.stopPropagation();
			e.preventDefault();
			////alert("clicked")
			var prop= $(this).attr('id');
			// console.log(prop);
			tempcount = prop.replace(/\D/g,'');
			// console.log("hiding"+tempcount);
			//var nameval=document.getElementById('#name'+tempcount+'').value;
			var nameval=$('#name'+tempcount+'').val();
			var desval=$('#des'+tempcount+'').val();
			var req;
			// console.log(nameval);
			// console.log(desval);
			if($('#chkreqq'+tempcount+'').is(':checked'))
			{
				chkbxreq='';
				chkvalue="TRUE";
				req='*';
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
				if(!$('#Label'+tempcount+'').hasClass('required'))
				{
					$('#Label'+tempcount+'').addClass("required");
				}
	
			}
	
			else
			{
				chkbxreq='';
				req='';
				chkvalue="FALSE"
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
				if($('#Label'+tempcount+'').hasClass('required'))
				{
					$('#Label'+tempcount+'').removeClass("required");
				}
			}

			if($('#isakeyy'+tempcount+'').is(':checked'))
			{
				chkbxkey='key';
				chkkeyvalue="TRUE";
				chkvalue="TRUE";
				$('#Label'+tempcount+'').attr("chkkeyvalue",chkkeyvalue);
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
	
			}
			else
			{
				chkbxkey='';
				chkkeyvalue="FALSE"
				$('#Label'+tempcount+'').attr("chkkeyvalue",chkkeyvalue);
	
			}
			// console.log("hi"+nameval+desval);
			if(nameval!='')
			{
				var check_len = $(".mainpage").find("[name='imageElem']").length;
				console.log(check_len)
				if(check_len > 1)
				{
					$(".mainpage").find("[sectn_id='Label"+tempcount+"']").attr("sectn",nameval)
				}
				$('#Label'+tempcount+'').text(nameval);
			}
			else
			{
				$('#Label'+tempcount+'').text(lang.app_section_name);
			}
			$('#Label'+tempcount+'').attr("des",desval);
			//$('#fDes'+tempcount+'').attr("value",desval);
			$('#prop'+tempcount+'').popover('hide')
	});
	
	$(document).on('click','.popsaveins', function (e) 
		{
			
			e.stopPropagation();
			e.preventDefault();
			////alert("clicked")
			var prop= $(this).attr('id');
			// console.log(prop);
			tempcount = prop.replace(/\D/g,'');
			// console.log("hiding"+tempcount);
			//var nameval=document.getElementById('#name'+tempcount+'').value;
			var nameval=$('#name'+tempcount+'').val();
			var insval=$('#ins'+tempcount+'').val();
			var desval=$('#des'+tempcount+'').val();
			//console.log(nameval);
			// console.log(insval);
			if($('#chkreqq'+tempcount+'').is(':checked'))
			{
				chkbxreq='';
				chkvalue="TRUE";
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
	
			}
	
			else
			{
				chkbxreq='';
				chkvalue="FALSE"
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);

			}

			if($('#isakeyy'+tempcount+'').is(':checked'))
			{
				chkbxkey='key';
				chkkeyvalue="TRUE";
				chkvalue="TRUE";
				$('#Label'+tempcount+'').attr("chkkeyvalue",chkkeyvalue);
				$('#Label'+tempcount+'').attr("chkvalue",chkvalue);
	
			}
			else
			{
				chkbxkey='';
				chkkeyvalue="FALSE"
				$('#Label'+tempcount+'').attr("chkkeyvalue",chkkeyvalue);
	
			}
			// console.log("hi"+nameval+desval);
			if(nameval!='')
			{
			$('#Label'+tempcount+'').text(nameval);
			}
			else
			{
			// console.log("section")
			$('#Label'+tempcount+'').text(lang.app_instr_name);
			}
			$('#inss'+tempcount+'').text(insval);
			$('#Label'+tempcount+'').attr("instruct",insval);
			$('#Label'+tempcount+'').attr("des",desval);
			//$('#fDes'+tempcount+'').attr("value",desval);
			$('#prop'+tempcount+'').popover('hide')
			var height = $('#inss'+tempcount+'').height();
			height=height+10;
			$('.crdiv'+tempcount+'').css('min-height',height);
			
	});

// dynamically changing the labels in the header part//
$(document).on('click','.companyname', function (e) 
	{
		var name_c = $(this).text();
		$(this).replaceWith('<input type="text" class="col-md-3 companyname_input" name="" id=""/>');
		$('.companyname_input').val(name_c);
		$('.companyname_input').focus();
		/*if ($(this).is(':checked')) {
			// the checkbox was checked 
			 //console.log($(this).attr("id"));
			 var id_ = $(this).attr("id");
			 var _temp = id_.replace(/\D/g,'');
			 $('#chkreqq'+_temp+'').prop('checked', true); 
		}*/ 
	 
	});
	
$(document).on('focusout','.companyname_input', function (e) 
	{	
		if($(this).val()!='')
		{
			var c_name = $(this).val();
			$(this).replaceWith('<label class="control-label col col-4 companyname" style="min-width: 140px; word-wrap: break-word"><i class="fa fa-edit"></i>'+c_name+'</label>');
			//$('.companyname').text(c_name);
			console.log($('.companyname').text())
		}
	})
	
// dynamically changing the labels in the header part address//
$(document).on('click','.address', function (e) 
	{
		var name_c = $(this).text();
		var id_ = $(this).attr('id');
		$(this).replaceWith('<input type="text" class="col-md-3 pull-right address_input" name="" id="'+id_+'"/>');
		$('.address_input').val(name_c);
		$('.address_input').focus();
 
	});
	
$(document).on('focusout','.address_input', function (e) 
	{	
		if($(this).val()!='')
		{
			var c_name = $(this).val();
			var id_ = $(this).attr('id');
			if(id_ == "address0")
			{
				$(this).replaceWith('<label class="control-label col col-4 pull-right address" name="" id="'+id_+'" alt="edit name"><i class="fa fa-edit"></i>'+c_name+'</label>');
			}
			else
			{
				$(this).replaceWith('<label class="control-label col col-4 pull-right address" name="" id="'+id_+'" alt="edit name">'+c_name+'</label>');
			}
			//$('.companyname').text(c_name);
			console.log($('.address').text())
		}
	})
	
//checking the required check box when notify clicked
$(document).on('click','.notify', function (e) 
	{
		if ($(this).is(':checked')) {
			// the checkbox was checked 
			 //console.log($(this).attr("id"));
			 var id_ = $(this).attr("id");
			 var _temp = id_.replace(/\D/g,'');
			 $('#chkreqq'+_temp+'').prop('checked', true); 
		} 
	 
	});

// check if required check box un-check and notify is checked//
$(document).on('click','.chkreq', function (e) 
	{
		if ($(this).is(':not(:checked)')) {
			 //console.log($(this).attr("id"));
			 var id_ = $(this).attr("id");
			 var _temp = id_.replace(/\D/g,'');
			 if ($('#notify'+_temp+'').is(':checked')) {
			 $('#chkreqq'+_temp+'').prop('checked', true); 
			 }
		} 
	 
	});

$("body").on("click",".removeclass", function(e){ //user click on remove text
        if( fFlag = 1 ) {
                $(this).parents('.col-md-12').remove(); //remove text box
                x--; //decrement textbox
                FieldCount--; //text box added increment
				fFlag=0;
				$('#tagdiv').remove();
				$('#FldCtrl').hide();
				$('div').remove('.ddd');
				$('div').remove('.tempdel');
				$('#AddMoreFileBox').show();
				
        }
return false;
})
$("#prev").on('click',function()
{
	
	$('#mainpage').children().each(function() 
	{
	        if($(this).hasClass('divf') && $(this).css('display') != 'none')
            {
			  var livediv= $(this).attr('id');
		      tempdivcount = livediv.replace(/\D/g,'');
			                       
			if(tempdivcount>1)
			{
				var divcountt=tempdivcount-1;
				$('#divfrm'+tempdivcount+'').hide();
				$('.divf').removeClass("active");
				$('#divfrm'+divcountt+'').addClass("active");
				$('#divfrm'+divcountt+'').show();
				var sel_temp=$('#mainpage').find('.active').children('.print_temp').val() ||'';
				if(sel_temp!='')
				{
					$('#template_name').val(sel_temp);
				}
				else
				{
					$('#template_name').val("");
				}
				var sel_img=$('#mainpage').find('.active').children('.print_temp').attr("img_src") || '';
				if(sel_img!='')
				{			
					$('.device').css("background-image", "url("+sel_img+")");	
				}
				else
				{
					$('.device').css("background-image", "none");
				}
				var prevweightvalue=0;
				//var chcount = 
				$('#divfrm'+divcountt+'').children('div').each(function(index, element) {
                var tempweightvalue=$(this).attr('elementweight');
				// console.log("{{{{{{{{{{{{{{{",tempweightvalue);
				tempweightvalue=parseInt(tempweightvalue)
				prevweightvalue=parseInt(prevweightvalue)+tempweightvalue;
				// console.log("}}}}}}}}}}}}}}}",prevweightvalue);
                });
				var tempwww=22;
				tempwww=parseInt(tempwww)-prevweightvalue
				document.getElementById('weight').value = tempwww;
				document.getElementById('divcount').value = divcountt;delete_disable()
				currentdiv=divcountt;
				//var currPage = $('#divcount').val();""""""""""
				//tDefLoad(currPage);		
			}
			else
			{
				////alert("Sorry No Previous Page Found");
			}
			
			}
	});
})
$("#nxt").on('click',function()
{
	$('#mainpage').children().each(function() 
	{ 
	        if($(this).hasClass('divf') && $(this).css('display') != 'none')
            {
              var livediv= $(this).attr('id');
		      tempdivcount = livediv.replace(/\D/g,'');
			  document.getElementById('divcount').value = tempdivcount; delete_disable()
			 }
					
	});
	if(tempdivcount < totaldivcount)
			{
				var divcounttt=tempdivcount;
				divcounttt++;
				$('#divfrm'+tempdivcount+'').hide();
				$('#divfrm'+divcounttt+'').show();
				$('.divf').removeClass("active");
				$('#divfrm'+divcounttt+'').addClass("active");
				var sel_temp=$('#mainpage').find('.active').children('.print_temp').val() ||'';
				if(sel_temp!='')
				{
					$('#template_name').val(sel_temp);
				}
				else
				{
					$('#template_name').val("");
				}
				var sel_img=$('#mainpage').find('.active').children('.print_temp').attr("img_src") || '';
				if(sel_img!='')
				{			
					$('.device').css("background-image", "url("+sel_img+")");	
				}
				else
				{
					$('.device').css("background-image", "none");
				}
				prevweightvalue=0;
				$('#divfrm'+divcounttt+'').children('div').each(function(index, element) {
                var tempweightvalue=$(this).attr('elementweight');
				// console.log("{{{{{{{{{{{{{{{",tempweightvalue);
				tempweightvalue=parseInt(tempweightvalue)
				prevweightvalue=parseInt(prevweightvalue)+tempweightvalue;
				// console.log("}}}}}}}}}}}}}}}",prevweightvalue);
                });
				var tempwww=22;
				tempwww=parseInt(tempwww)-prevweightvalue
				document.getElementById('weight').value = tempwww;
				//var ccount = $('#divfrm'+divcounttt+'').children().length;
//				var temwei=tempweight-ccount;
//			    document.getElementById('weight').value = temwei;
				document.getElementById('divcount').value = divcounttt; 
delete_disable()
				currentdiv=divcounttt;
				//var currPage = $('#divcount').val();
				//tDefLoad(currPage);	
			}
			else
			{
					////alert("Sorry No Page Found");				
			}	
})

    /*
	 * Delete a page
	 */

	function deletepage() 
	{
		var div_count = $('.mainpage').children('.divf').length;
		if(div_count == 1)
		{
			$('#divfrm'+div_count+'').remove();
			totaldivcount = div_count;
			$('.device').css('background-image', '');
			$('<div id="divfrm'+div_count+'" class="divf" align="center"></div>').appendTo('#mainpage');
			$('<input type="hidden" id="print_temp'+div_count+'" class="print_temp" value="" img_desc="" img_src="" start="" end="" file_name="" img_id="" relative_url=""></input>').appendTo('#divfrm'+div_count+'');
			$('#template_name').val("");
			$('.divf').removeClass("active");
			$('#divfrm'+div_count+'').addClass("active");
			document.getElementById('divcount').value = totaldivcount;delete_disable()
			document.getElementById('totaldivcount').value = totaldivcount;
			weightvalue=22;
			document.getElementById('weight').value = weightvalue;
			var sel_temp=$('#mainpage').find('.active').children('.print_temp').val() || '';
			if(sel_temp!='')
			{
				$('#template_name').val(sel_temp);
			}
			else
			{
				$('#template_name').val("");
			}
			var sel_img=$('#mainpage').find('.active').children('.print_temp').attr("img_src") || '';
			if(sel_img!='')
			{			
				$('.device').css("background-image", "url("+sel_img+")");	
			}
			else
			{
				$('.device').css("background-image", "none");
			}
		}
		else
		{
			var delpage=$('#divcount').val();
			delpage = parseInt(delpage);
			console.log("dellllll",delpage);
			var show_page = 0;
			$('#divfrm'+delpage+'').remove();
			console.log("dellllll",delpage);
			console.log("div_countdiv_countdiv_countdiv_count",div_count);
			if(delpage == div_count)
			{
				console.log("dellllllififififif",delpage);
				delpage = delpage - 1;
				console.log("dellllllififi22222",delpage);
				show_page = delpage
				console.log("show_pageshow_pageshow_pageshow_pageififififi",show_page);
				$('#divfrm'+show_page+'').show();
			}
			else
			{
				//delpage = delpage + 1;
				console.log("dellllllelselsesels",delpage);
				show_page = delpage
				console.log("show_pageelseleslelse",show_page);
				//$('#divfrm'+show_page+'').show();
			}
			var id = 1;
			$('.mainpage').children().each(function()
			{
				$(this).attr('id', 'divfrm' + id );
				id++;
				console.log("idididididididid",id);
			})
			document.getElementById('totaldivcount').value = $('.mainpage').children().length;
			totaldivcount = $('.mainpage').children().length;
			console.log("totaldivcounttotaldivcounttotaldivcounttotaldivcount",totaldivcount);
			var current_weight_value = 22;
			$('#divfrm'+show_page+'').children('div').each(function()
			{
				var current_element = $(this).attr('elementweight');
				current_element = parseInt(current_element);
				console.log("current_elementcurrent_elementcurrent_elementcurrent_element",current_element);
				current_weight_value = current_weight_value-current_element;
			})
			document.getElementById('divcount').value = show_page;delete_disable()
			console.log("show_pageshow_pageshow_pageshow_pagelastlaset",show_page);
			document.getElementById('weight').value = current_weight_value;
			console.log("current_weight_valuecurrent_weight_valuecurrent_weight_value",current_weight_value);
			$('#divfrm'+show_page+'').show();
			$('#divfrm'+show_page+'').addClass("active");
			currentdiv=show_page;
			var sel_temp=$('#mainpage').find('.active').children('.print_temp').val() || '';
			if(sel_temp!='')
			{
				$('#template_name').val(sel_temp);
			}
			else
			{
				$('#template_name').val("");
			}
			var sel_img=$('#mainpage').find('.active').children('.print_temp').attr("img_src") || '';
			if(sel_img!='')
			{			
				$('.device').css("background-image", "url("+sel_img+")");	
			}
			else
			{
				$('.device').css("background-image", "none");
			}
		}
	}
	
	
	$(document).on('click','#Deletepage',function()
	{
	  // ask verification
		$.SmartMessageBox({
			title : "<i class='fa fa-minus-square txt-color-orangeDark'></i> "+lang.app_delete_mgs,
			buttons : '['+lang.app_no+']['+lang.app_yes+']'

		}, function(ButtonPressed) {
			if (ButtonPressed == "lang.app_yes") {
				setTimeout(deletepage, 1000)
			}
			if (ButtonPressed == "lang.app_no") {
				
			}

		});
	})

	$(document).on("click","#print_template",function(e)
	{
		console.log("pg_no");
		$('#namme').modal('show');
	})
	$(document).on("click","#print_remove",function(e)
	{
		var pg_no = $('#divcount').val();
		console.log(pg_no);
		$('#template_name').val('');
		$('#print_temp'+pg_no+'').attr('img_desc','')
		$('#print_temp'+pg_no+'').attr('img_src','')
		$('#print_temp'+pg_no+'').attr('end','')
		$('#print_temp'+pg_no+'').attr('start','')
		$('#print_temp'+pg_no+'').attr('relative_url','')
		$('#print_temp'+pg_no+'').attr('value','')
		$('.device').css('background-image', 'none') 
	})

		var closeButton = document.querySelector("#closed");
        closeButton.addEventListener("click", function() {
			$('#namme').modal('hide');
        });
        
	$("body").on("click",".removediv", function(e)
		{ //user click on remove text
         if(count >= 1 ) {
				var flag_change = false;
				var current_name = $(this).parents('.col-md-12').find('.labcheck').attr("name")
				if(current_name == "retriever")
				{
					var sel_pre_app = $(this).parents('.col-md-12').find('.labcheck').attr("id_ref") || "null";
					for(var i in app_chose)
					{
						if(sel_pre_app !="null" && i == sel_pre_app)
						{
							if(app_chose[i]['is_used'] == true)
							{
								//alert("You cant Delete")
								var used = app_chose[i]['selected_fields'].toString();
								retrieve_alert("Sorry you cant delete as you used this fields in : "+used+"")
								flag_change = true;
								
							}							
						}
					}
				}
				if(current_name == "mapper")
				{
					var sel_pre_app = $(this).parents('.col-md-12').find('.labcheck').attr("prev_app") || "null";
					var field_ref = $(this).parents('.col-md-12').find('.labcheck').attr("mapped") || "";
					if(field_ref != "")
					{
						var values  = field_ref.split("_");
						var field   = values[2]
						for(var i in app_chose)
						{
							if(sel_pre_app !="null" && i == sel_pre_app)
							{
								//check the array and delete the value//
								//console.log(app_chose[i]['selected_fields'].length)
								app_chose[i]['selected_fields'].splice( $.inArray(field, app_chose[i]['selected_fields']), 1 );
								if(app_chose[i]['selected_fields'].length < 1)
								{
									app_chose[i]['is_used'] = false							
								}
							}
						}
					}
					//console.log(app_chose)
				}
				if(flag_change == false)
				{
					var labelremove=$(this).parents('.col-md-12').find('label').html()
					var weightremove=$(this).parents('.col-md-12').find('weight').html()
					var elemtwe=$(this).parents('.col-md-12').attr('elementweight');
					var check_sec = $(this).parents('.col-md-12').hasClass('sec');
					if(check_sec)
					{
						var label_id = 	$(this).parents('.col-md-12').find('.labcheck').attr("id");
						var previous_id = $(this).parents('.col-md-12').prev(".sec").find('.labcheck').attr("id");
						var previous_value = $(this).parents('.col-md-12').prev(".sec").find('.labcheck').text();
						$(".mainpage").find("[sectn_id='"+label_id+"']").attr("sectn",previous_value)
						$(".mainpage").find("[sectn_id='"+label_id+"']").attr("sectn_id",previous_id)
					}
					$(this).parents('.col-md-12').remove(); //remove text box
					var whtval=document.getElementById('weight').value;
					elemtwe=parseInt(elemtwe);
					whtval=parseInt(whtval)+elemtwe
					document.getElementById('weight').value = whtval;
					if($('.foraddbutton').height()>451)
					{
						var newheight=$('.device').height();
						newheight=newheight-50;
						$('.device').css('min-height',newheight);
						$('.device').css('min-height',800);
					}
				}
				
		 }
	return false;
	})
	
$(document).on('mouseover','.removediv',function(){
var change= $(this).attr('id');
var tempcount = change.replace(/\D/g,'');
$('#div'+tempcount+'').css('border','1px solid');
$('#div'+tempcount+'').css("borderColor",'red');
})

$(document).on('mouseleave','.removediv',function(){
var change= $(this).attr('id');
var tempcount = change.replace(/\D/g,'');
$('#div'+tempcount+'').css("border",'');
$('#div'+tempcount+'').css("borderColor","");
})

$(".divf").sortable({
	forcePlaceholderSize: true,
	cursor: "move",
	axis: "y",
	scroll: false,
    sort: function () {},
    placeholder: 'ui-state-highlight',
    receive: function () {},
    update: function (event, ui) {}
});

$( ".divf" ).disableSelection();
 
 function readURL(input) {
        if (input.files && input.files[0]) {
			//alert("success")
            var reader = new FileReader();
            
            reader.onload = function (e) {
                //$('.logo_img').attr('src', e.target.result);
				$('.logo_img').css("background-image","url("+e.target.result+")");
            }
            
            reader.readAsDataURL(input.files[0]);
        }
		else
		{
			console.log("fail");
		}
    }
	
//uploading the logo in app creation //
$(document).on('click','.logo',function()
	{
		$('.logo_file').trigger("click");
	});

$(document).on('mouseover','.logo',function(){
$('#click_upload').removeClass('hide');
})

$(document).on('mouseleave','.logo',function(){
if(!$('#click_upload').hasClass("hide"))
{
	$('#click_upload').addClass('hide');
}
})

//upload the logo when the user selects.//
$(document).on('change','.logo_file',function() 
{	
		readURL(this);
		var app = $('#controller_name').val();
		var appsplit = app.split('_')
		$('#app_id').val(appsplit[0]);
        var formData = new FormData($('.logo_form')[0]);

        $.ajax({
            url: "company_logo",
            type: "POST",
            data: formData,
            async: false,
            success: function (msg) {
				logo_file_path = msg;
				console.log(msg);
            },
            cache: false,
            contentType: false,
            processData: false
        });
   
});
//company header....//////////////////////////

var notify_doc=false;
//create json-------------------------
$(document).on('click','.submit',function()//create json
{
$('#divfrm1').children('#div1').first().nextUntil('.sec').each(function()
{
	var i_d = $(this).find('label').attr("notify");
	if(i_d == "true")
	{
		notify_doc = true;
		console.log("ssssssssssss")
	}
});

if(notify_doc == true)
{
header(logo_file_path)
var widgetCnt=0;
var sectionCnt=0;
var no_of_elements="0";
var appcompletestatus = $('#appcomplete').val();
$('.mainpage').children('div').each(function(index, element) 
{   
   var divid=$(this).attr('id');
   var change= $(this).attr('id');
   var pagenum = change.replace(/\D/g,'');
   var divlen=$(this).children('div').length;
   no_of_elements=parseInt(no_of_elements);
   no_of_elements=no_of_elements+divlen;
	
   tPagenumber(pagenum);

   var image_title=$(this).children('.print_temp').val();
   var image_desc=$(this).children('.print_temp').attr("img_desc");
   var image_src=$(this).children('.print_temp').attr("relative_url");
   
   tPrintTemplate(image_title,image_desc,image_src,pagenum);
   if(divlen == 0)
   {
	   tAdd_newline(widgetCnt)
   }
   $(this).children('div').each(function(index, element) {
   //no_of_elements+1;
   var diviid=$(this).attr('id');
   var iddd = diviid.replace(/\D/g,'');
   var secnam=$(this).find('label').attr('name');
   var labelname=$(this).children('label').text();
   var inputtype=$(this).find('input').attr('type');
   var checkvl,checkvlky,minn='',maxx='',textareamin,textareamax,des;
   checkvl=$(this).find('label').attr('chkvalue');
   checkvlky=$(this).find('label').attr('chkkeyvalue');
   minn = $(this).find('label').attr('min');
   maxx = $(this).find('label').attr('max');
   des = $(this).find('label').attr('des');
   var notify_val = $(this).find('label').attr('notify');
   var ins=$(this).find('label').attr('instruct');
   var tagsvalue = $(this).find('label').attr('tags');
   var notify_value = false;
   if(notify_val=="true")
   {
		notify_value =true;
		notify_fields(labelname,pagenum)
   }
   		
		if(secnam == 'textarea')
		{
			widgetCnt++
			if(typeof(maxx) == undefined || minn ==''){
			minn = "0";
			}
			if(typeof(maxx) == undefined || maxx==''){
			maxx = "250";
			}
			tAdd_Txtarea(labelname,minn,maxx,checkvl,"true", des, widgetCnt,notify_val);//JSON Creation
		}
		else if(secnam == 'imageElem')
		{
			widgetCnt++
			var page = $(this).find('label').attr('page')|| "" ;
			var section = $(this).find('label').attr('sectn')|| "" ;
			var cloned = $(this).find('label').attr('cloned')|| "" ;
			var sameasfield = $(this).find('label').attr('sel_value')|| "" ;
			
			tAdd_imageField(labelname, "true", des, widgetCnt, page, section, sameasfield, cloned)
		}
		else if(secnam == 'retriever')
		{
			widgetCnt++;
			coll_ref = $(this).find('label').attr("id_ref")
			var field_prop = $(this).find('label').attr("retrieve_def")
			field_prop = atob(field_prop);
			field_prop = JSON.parse(field_prop)
			var field_ref = $(this).find('label').attr("retrieve_ref")
			var retrieve_list = $(this).find('label').attr("retrieve_lists")
			var retrieve_list_arr=[];
			var retriv_split = retrieve_list.split(",") || 0;
			for(var i=0;i<retriv_split.length;i++)
			{
				retrieve_list_arr.push(retriv_split[i])
			}
			tAdd_retriever(labelname,secnam,widgetCnt,coll_ref,field_prop,retrieve_list_arr,field_ref)
		}
		else if(secnam == 'mapper')
		{
			widgetCnt++;
			var field_prop = $(this).find('label').attr("mapped_def")
			var coll_ref = $(this).find('label').attr("appid")
			field_prop = atob(field_prop);
			field_prop = JSON.parse(field_prop)
			var field_ref = $(this).find('label').attr("map_ref")
			tAdd_mapper(labelname,secnam,widgetCnt,field_prop,field_ref,coll_ref)
		}
		else if(secnam == 'wtextarea')
		{
			widgetCnt++;
			tAdd_TxtareaA(labelname,minn,maxx,checkvl, "false", des, widgetCnt);//JSON Creation
		}
		else if(secnam == 'blocktext')
		{
			widgetCnt++;
			tAdd_Txtareab(labelname,minn,maxx,checkvl, "false", des, widgetCnt);//JSON Creation
		}
		else if(secnam == 'mblocktext')
		{
			widgetCnt++;
			tAdd_Txtareamb(labelname,minn,maxx,checkvl, "false", des, widgetCnt);//JSON Creation
		}
		else if(secnam == 'newline')
		{
			console.log("neeeeeeeeeeeeeeeee")
			widgetCnt++;
			tAdd_newline(widgetCnt);//JSON Creation
		}
		else if (secnam == 'section')
		{
			widgetCnt=0;
			sectionCnt++;
			tAddSec(labelname,checkvlky ,des, sectionCnt);
		}
		else if(secnam == 'instructions')
		{
			widgetCnt++;
			tAdd_Instruction(labelname,checkvl, "true", ins, des, widgetCnt);
		}
		else if (secnam == 'select')//user click on select
		{
			widgetCnt++;
			// console.log("select");
			
			/* commented for comma issue 
			var optvalue;// Creating Array for JSON
			var tagssel=tagsvalue.replace(/"/g, "");
			//console.log(optvalue);
			optvalue = tagssel.split(",");//splitting tag values

			for (var i in optvalue)// allocating the values to Select Option
			{
			////alert(optvalue)
			var optlabel=optvalue[i];
			////alert(optlabel)
			optvalue[i]=new Array();
			optvalue[i].text =''+optlabel+'';
			optvalue[i].value= ''+optlabel+'';
			// console.log("llllllllllll"+optlabel);
			}
			commentd end */
			
			//created for comma issue
			try
			{
				var tags_value = JSON.parse(tagsvalue)
				var i;
				for(i=0;i<tags_value.length;i++)
				{
					var optlabel=tags_value[i];
					////alert(optlabel)
					tags_value[i]=new Array();
					tags_value[i].text =''+optlabel+'';
					tags_value[i].value= ''+optlabel+'';
				}
			}
			catch(e)
			{
				console.log("error: line 4490",e)
			}
			//created end
			
			tAdd_Select(labelname,tags_value,checkvl, "true", des, widgetCnt,notify_val);
		}

		else if (secnam == 'mchoice')//user click on select
		{
			widgetCnt++;
			// console.log("mchoice");
			var tagssel=tagsvalue.replace(/"/g, "");
			var optvalue = tagssel.split(",");//splitting tag values
			for (var i in optvalue)// allocating the values to Select Option
			{
			var optlabel=optvalue[i];
			optvalue[i]=new Array();
			optvalue[i].text =''+optlabel+'';
			optvalue[i].value= ''+optlabel+'';
			}
			tAdd_MChoice(labelname,optvalue,checkvl, "true", des, widgetCnt,notify_val);
		}

		else if (secnam == 'radio') // User Click on Radio button
		{
			widgetCnt++;
			// console.log("radio");
			var tagssel=tagsvalue.replace(/"/g, "");
			var optvalue = tagssel.split(",");//splitting tag values
			for (var i in optvalue)// allocating the values to Select Option
			{
			var optlabel=optvalue[i];
			optvalue[i]=new Array();
			optvalue[i].label =''+optlabel+'';
			optvalue[i].value= ''+optlabel+'';
			}
			tAdd_Radio(labelname,optvalue,checkvl, "true", des, widgetCnt,notify_val);//JSON Creation
		}

		else if (secnam == 'checkbox') // User Click on checkbox
		{
			widgetCnt++;
			// console.log("checkbox");
			var tagssel=tagsvalue.replace(/"/g, "");
			var optvalue = tagssel.split(",");//splitting tag values
			for (var i in optvalue)// allocating the values to Select Option
			{
			var optlabel=optvalue[i];
			optvalue[i]=new Array();
			optvalue[i].label =''+optlabel+'';
			optvalue[i].value= ''+optlabel+'';
			}
			tAdd_Chkbox2(labelname,optvalue,checkvl, "true", des, widgetCnt,notify_val);//JSON Creation
			}
			else if ((secnam == 'date') || (secnam == 'time') || (secnam == 'color') || (secnam == 'month'))
			{
				widgetCnt++;
			tAdd_date(labelname,secnam,checkvl, "true", des, widgetCnt,notify_val);
		}
		else if(secnam == 'range')
	   	{
			widgetCnt++;
			if(typeof(maxx) == undefined || minn ==''){
			minn = "0";
			}
			if(typeof(maxx) == undefined || maxx==''){
			maxx ="60";
			}
			tAdd_Ranbox(labelname,secnam,minn,maxx,checkvl, "true",des, widgetCnt);//JSON Creation
		}
	   	else if(secnam == 'file')
	   	{
			widgetCnt++;
			tAdd_File(labelname,checkvl, "true",des, widgetCnt);//JSON Creation
		}
		else if(secnam == 'photo')
		{
			widgetCnt++;
			if(typeof(minn) == undefined || minn =='')
			{
			  minn = "1024";
			}
			else
			{
			  var min_n = parseInt(minn);
			  min_n = min_n*1024;
			  minn = min_n.toString();
			}
			if(typeof(maxx) == undefined || maxx=='')
			{
				maxx = "5120";
			}
			else
			{
			   var max_x = parseInt(maxx);
			   max_x = max_x*1024;
			   maxx = max_x.toString();
			}
			
			tAdd_photo(labelname,checkvl, "true",des, widgetCnt,minn,maxx);//JSON Creation
		}
		else if ((secnam == 'text') || (secnam == 'password') || (secnam == 'number'))
		{
			widgetCnt++;
			if(typeof(maxx) == undefined || minn ==''){
			minn = "0";
			}
			if(typeof(maxx) == undefined || maxx==''){
			maxx = "60";
			}
			tAdd_Txtbox(labelname,secnam,minn,maxx,checkvl, "true", des, widgetCnt,notify_val);//JSON Creation
		}
		else if (secnam == 'mobile')
		{
			widgetCnt++;
			if(typeof(maxx) == undefined || minn ==''){
			minn = "10";
			}
			if(typeof(maxx) == undefined || maxx==''){
			maxx = "10";
			}
			tAdd_Txtbox(labelname,secnam,minn,maxx,checkvl, "true", des, widgetCnt,notify_val);//JSON Creation
		}		
		else if(secnam == 'wtext')
		{
			widgetCnt++;
			var input="text";
			tAdd_TxtboxA(labelname,input,minn,maxx,checkvl, "false", des, widgetCnt);//JSON Creation
		}
		
				
   });
   pageclear();
});//mainpage class .each end

if(appcompletestatus==0)
{
	draftsubmit();
	$('#no_of_elements').val(no_of_elements);
	//document.getElementById('no_of_elements').value=no_of_elements;
}
else
{
    formsubmit();
	$('#no_of_elements').val(no_of_elements);
	//document.getElementById('no_of_elements').value=no_of_elements;
	
}
}
else
{
  //alert 
  $.SmartMessageBox({
			title : "Info <i class='fa fa-info-circle txt-color-orangeDark'></i>",
			content : lang.app_notify_mgs,
			buttons : '['+lang.app_ok+']'

		});
}
})

function retrieve_alert(msg)
{
	  $.SmartMessageBox({
			title : "Info <i class='fa fa-info-circle txt-color-orangeDark'></i>",
			content : msg,
			buttons : '[Ok]'

		});
}
function formsubmit()
{
	var valuecheck=$('#scaffold_code').val();
	//console.log(valuecheck)
	var pagecnt=$('#totaldivcount').val();
    $('#pagenumber').val(pagecnt);
	
	if(valuecheck != '')
	{
	$('.tform').submit();
	}
	else
	{
	
	}
}

//============================================= DRAFT ==================================================================================================================//

function draftsubmit()
{
	var pagecnt=$('#totaldivcount').val();
    $('#pagenumber').val(pagecnt); 
	
	var formData = {
			'controller_name' 	    : $('#controller_name').val(),
			'model_name' 		   	: $('#model_name').val(),
			'appName'           	: $('#appName').val(),
			'appDescription'   	    : $('#appDescription').val(),
			'apptype'          	    : $('#apptype').val(), 
			'appprofile'            : $('#iappprofile').val(), 
			'appexpiry'        	    : $('#iappexpiry').val(),
			'appcategory'      	    : $('#appcategory').val(),
			'companyname'      	    : $('#icompany').val(),
			'companyaddress'    	: $('#iaddress').val(),
			'scaffold_code' 	    : $('#scaffold_code').val(),
			'scaffold_delete_bd'    : $('#scaffold_delete_bd').val(),
			'scaffold_bd'    		: $('#scaffold_bd').val(),
			'scaffold_routes'       : $('#scaffold_routes').val(),
			'scaffold_menu'   	    : $('#scaffold_menu').val(),
			'scaffold_model_type'   : $('#scaffold_model_type').val(),
			'create_controller'     : $('#create_controller').val(),
			'create_model'          : $('#create_model').val(),
			'create_view_create'    : $('#create_view_create').val(),
			'create_view_list'      : $('#create_view_list').val(),
			'updType'               : 'create',
			'pagenumber'            : $('#pagenumber').val(),
			'appcomplete'           : $('#appcomplete').val(),
			'print'                 : $('#print').val(),
			'headertype'            : $('#iheadertype').val(),
			'header_values'         : $('#header_values').val(),
			'notify_values'         : $('#notify_values').val()
		};


		// process the form
		$.ajax({
			type 		: 'POST', // define the type of HTTP verb we want to use (POST for our form)
			url 		: '../code_gen/index', // the url where we want to POST
			data 		: formData, // our data object
		})
			// using the done promise callback
			.done(function(data) {

				var navigate_to_url = $(draft).attr("href");
	            window.location.href = navigate_to_url;
				
                 $.smallBox({
						title : lang.app_message,
						content : "<i class='fa fa-clock-o'></i> <i>"+lang.app_draft_msg+"</i>",
						color : "#659265",
						iconSmall : "fa  fa-check-circle fa-2x fadeInRight animated",
						timeout : 3000
					});                                                           
				
			})
			
			.fail(function() {
           
  }); 

		
    
}
 
/*window.onbeforeunload = function(){
	var unloadclick = $(document).hasClass("menu-links");
	//alert(unloadclick);
    if($('#step2').hasClass('active') && !unloadclick)
   {
       return 'You must click "Buy Now" to make payment and finish your order. If you leave now your order will be canceled.';
    }
};
*/
$(document).on('click','a',function(e){
 if($('#step2').hasClass('active'))
 {	
 
 var clickedid = $(this).hasClass("menu-links");
 draft=$(this);
if(clickedid)
{
	$.SmartMessageBox({
				title   : lang.app_alert,
				content : lang.app_draft_not_saved_msg,
				buttons : '['+lang.app_cancel+']['+lang.app_no+']['+lang.app_yes+']'
			}, function(ButtonPressed) {
				if (ButtonPressed === lang.app_yes) 
				{
					$('.submit').trigger("click");
				}
				if (ButtonPressed === lang.app_no) {
					var navigate_to_url = $(draft).attr("href");
					window.location.href = navigate_to_url;
				}
				if (ButtonPressed === lang.app_cancel) {
				
				}
				
	       });
		   e.preventDefault();
			
}
}
})
//application list select box change
var selected_app_id = '';
var selected_sec_name = '';
var selected_field_name = '';
var selected_list = [];
$(document).on('change','.app_list',function()
{
	console.log("change_trigger")
	var flag_chang = false;
	var count_id = $(this).attr("idcount");
	//console.log(count_id)
	var sel_pre_app = $('#Label'+count_id+'').attr("id_ref")
	var selected_app = $('#app_list'+count_id+'').val()
	if(sel_pre_app != selected_app || $('#prop'+count_id+'').hasClass("tocheck"))
	{
		for(var i in app_chose)
		{
			if(i == sel_pre_app)
			{
				if(app_chose[i]['is_used'] == true)
				{
					var used = app_chose[i]['selected_fields'].toString();
					$('#app_list'+count_id+'').val(sel_pre_app)
					retrieve_alert("Sorry you cant Change as you used this fields in : "+used+"")
					flag_chang = true;
				}
				else
				{
					delete app_chose[i];
				}
				
			}
		}
		//console.log(section_list[selected_app])
		if(flag_chang != true)
		{
			$('#section'+count_id+'').empty();
			$('<option value="null">Select Section</option>').appendTo('#section'+count_id+'')
			$('#retrieve'+count_id+'').empty();
			selected_app_id = selected_app || '';
			//$('#Label'+count_id+'').attr("id_ref",selected_app_id);
			$('#retrieve'+count_id+'').attr("id_ref",selected_app_id);
			var current_sel = app_details[selected_app]
			var check_empty = true;
			for(var i=0;i<section_list[selected_app].length;i++)
			{
				check_empty = true
				$('<option value="'+section_list[selected_app][i]+'">'+section_list[selected_app][i]+'</option>').appendTo('#section'+count_id+'')
				
				$('<optgroup class="group'+i+'" label="'+section_list[selected_app][i]+'"></optgroup>').appendTo('#retrieve'+count_id+'')
				for(var j in app_details[selected_app][section_list[selected_app][i]])
				{
					check_empty = false
					$('<option type="'+app_details[selected_app][section_list[selected_app][i]][j]['element_def']['type']+'" appd="'+selected_app+'" value="'+app_details[selected_app][section_list[selected_app][i]][j]['element_ref']+'">'+j+'</option>').appendTo('.group'+i+'')
				}
				if(check_empty == true)
				{
					$('#retrieve'+count_id+'').find('.group'+i+'').remove();
				}
			}
			$('#retrieve'+count_id+'').multiselect('rebuild');
			var retrieve_lists = $('#Label'+count_id+'').attr("retrieve_lists")
			retrieve_lists =  retrieve_lists.split(",");
			$('#retrieve'+count_id+'').multiselect('select',retrieve_lists);
		}
		if($('#prop'+count_id+'').hasClass("tocheck"))
		{
			$('#Label'+count_id+'').attr("id_ref",selected_app)
		}
	}
})

//section_list change
$(document).on('change','.sec_list',function()
{
	var count_id = $(this).attr("idcount");
	$('#field'+count_id+'').empty();
	$('#field'+count_id+'').append('<option value="null" type="null">Select Field</option>')
	console.log(count_id)
	var selected_sec = $('#section'+count_id+'').val()
	console.log(selected_app_id)
	selected_sec_name = selected_sec;
	console.log(selected_sec)
	console.log(app_details[selected_app_id][selected_sec])
	var current_sel = app_details[selected_app_id][selected_sec]
	console.log("current_sel",current_sel)
	for(var i in current_sel)
	{
		console.log(current_sel[i])
		$('#field'+count_id+'').append('<option value="'+i+'" type="'+current_sel[i]['element_def']['type']+'">'+i+'</option>')
	}
})

//field_list change function
$(document).on('change','.field_list',function()
{
	var count_id = $(this).attr("idcount");
	console.log(count_id)
	var selected_field = $(this).val()
	console.log(selected_field)
	console.log(app_details[selected_app_id][selected_sec_name][selected_field])
	$('#Label'+count_id+'').attr("retrieve_ref",app_details[selected_app_id][selected_sec_name][selected_field]['element_ref']);
	var def_base = JSON.stringify(app_details[selected_app_id][selected_sec_name][selected_field]['element_def'])
	var old_type = app_details[selected_app_id][selected_sec_name][selected_field]['element_def']['type'];
	console.log("old_type",old_type)
	def_base = btoa(def_base)
	$('#Label'+count_id+'').attr("retrieve_def",def_base);
	$('#Label'+count_id+'').attr("retrieve_type",old_type);
	$('#Label'+count_id+'').text(selected_field);
	$('#name'+count_id+'').val(selected_field);
}) 
var mapp_list =[];
var mapp_options = '';
/* $(document).on('change','.retrievelist',function()
{
	var count_id = $(this).attr("idcount");
	var app = $(this).attr("id_ref");
	mapp_options = '<option value="null">Select Field</option>';
	var selected_field = $(this).val() ||0;
	//mapp_list = selected_field
	
	$('#Label'+count_id+'').attr("retrieve_lists",selected_field);
	for(var i=0;i<selected_field.length;i++)
	{
		//mapp_list[field]=[]
		//console.log(selected_field[i])
		var values = selected_field[i].split("_");
		var section = values[1]
		var field = values[2]
		mapp_options +='<option appd="'+selected_app_id+'" value="'+selected_field[i]+'">'+field+'</option>'
		console.log(app_details[selected_app_id][section][field]['element_def'])
		//console.log(section)
		//console.log(field)
	}
	mapp_list.push({[app]:mapp_options})
	console.log("///////////////////")
	console.log(mapp_list)
	console.log(mapp_options)
}) */
//popovr_ret
$(document).on('click','.popovr_ret',function()
{
	var current_id = $(this).attr("id");
	var i_d = current_id.replace(/\D/g,'');
		if($('#prop'+i_d+'').hasClass("tocheck"))
		{
			$('#'+current_id+'').on('shown.bs.popover', function () 
			{
				$('#retrieve'+i_d+'').multiselect({buttonWidth: '182px',nonSelectedText:'Select Mappers', maxHeight: 200,});
				if($('#prop'+i_d+'').hasClass("tocheck"))
				{
					$('#retrieve'+i_d+'').multiselect({buttonWidth: '182px',nonSelectedText:'Select Mappers',});
					var selected_app = $('#Label'+i_d+'').attr("id_ref")
					$('#Label'+i_d+'').attr("id_ref","")
					var selected_sec = $('#Label'+i_d+'').attr("retrieve_ref")
					var values  = selected_sec.split("_");
					var section = values[1]
					var field   = values[2]
					$('#app_list'+i_d+'').val(selected_app).trigger("change")
					$('#section'+i_d+'').val(section).trigger("change")
					$('#field'+i_d+'').val(field).trigger("change")
				}
			})
		}
		else
		{
			console.log("Else test")
		}
			
	
})
$('[rel="popover"]').on('hide.bs.popover', function () 
{
	//console.log($(this))
	if($(this).hasClass("popovr_ret") && $(this).hasClass("tocheck"))
	{
		console.log("inside Save")
		$(this).parents('.breadcrumb').find('.popsaveret').trigger("click")
	}
	if($(this).hasClass("mapbtn") && $(this).hasClass("map_tocheck"))
	{
		console.log("inside Save")
		$(this).parents('.breadcrumb').find('.popsavemap').trigger("click")
	}
})
$(document).on('change','.aplist',function()
{
	var field_values='';
	var count = $(this).attr("idcount")
	var currentapp = $('#aplist'+count+'').val();
	var pre_app = $('#Label'+count+'').attr("prev_app") || currentapp;
	console.log(currentapp)
	console.log("app_chose",app_chose)
	if(currentapp != "null")
	{
		for(var i in app_chose)
		{
			if(i == currentapp)
			{
				field_values = app_chose[i]['fields']
			}
		}
	}
	var apid = $('#aplist'+count+'').val()
	$('#Label'+count+'').attr("prev_app",pre_app);
	//$('#Label'+count+'').attr("prev_app",currentapp);
	$('#maplist'+count+'').html(field_values);
	var field_sel = $('#Label'+count+'').attr("mapped") || "null";
	if(field_sel!="null")
	{
		console.log("maplist updated")
		$('#maplist'+count+'').val(field_sel)
	}
	
})
$(document).on('change','.maplist',function()
{
	var count_id = $(this).attr("idcount");
	var colrf = $(this).find('option:selected').attr('appd');
	console.log(colrf)
	var mapped_field = $(this).val() ||0;
	console.log("mapped_field",mapped_field);
	var values  = mapped_field.split("_");
	var section = values[1]
	var field   = values[2]
	$('#Label'+count_id+'').attr("map_ref",mapped_field);
	var def_base = JSON.stringify(app_details[colrf][section][field]['element_def'])
	def_base = btoa(def_base)
	$('#Label'+count_id+'').attr("appid",colrf);
	$('#Label'+count_id+'').attr("mapped_def",def_base);
	$('#Label'+count_id+'').attr("mapped",mapped_field);
	$('#Label'+count_id+'').text(field);
}) 
$(document).on("click",".mapbtn",function()
{
	var pop_id = $(this).attr("id");
	var count = $(this).attr("cnt");
	var app_sel = $('#Label'+count+'').attr("appid") || "null"
		$('#'+pop_id+'').on('shown.bs.popover', function () 
		{
			$('#aplist'+count+'').empty()
			$('<option value="null">Select APP</option>').appendTo('#aplist'+count+'');
			for(var i in app_chose)
			{
				$('<option value="'+i+'">'+app_chose[i]['label']+'</option>').appendTo('#aplist'+count+'');
			}
			if(app_sel!="null")
			{
				$('#aplist'+count+'').val(app_sel)
			}
			if($(this).hasClass("map_tocheck"))
			{
				console.log("applist triggered")
				$('#aplist'+count+'').trigger("change")
				console.log("maplist triggered")
				$('#maplist'+count+'').trigger("change")
			}
		})
})
//function for getting the element weight values //  
function getvalue(fType)
{
	var element_weight = '';
	if ((fType == 'text') || (fType == 'password') || (fType == 'newline') || (fType == 'file') || (fType == 'number')|| (fType == 'month')|| (fType == 'time')|| (fType == 'color')|| (fType == 'range')|| (fType == 'SBreak')|| (fType == 'select')|| (fType == 'MChoice')|| (fType == 'wtext')|| (fType == 'blocktext')|| (fType == 'date') ||(fType == 'mobile'))
	{
		element_weight=2;
	}
	if ((fType == 'radio') || (fType == 'checkbox') )
	{
		element_weight=1
		
	}

	if ((fType == 'mblocktext') || (fType == 'wtextarea') || (fType == 'photo') || (fType == 'textarea') || (fType == 'description') || (fType == 'imageElem'))
	{
		element_weight=4
	}
	return element_weight;
}
function checkval_(elementweight,divcount,that)
	{
		var check_flag="false";
		console.log("divcount",divcount)
		$('#divfrm'+divcount+'').hide();
		divcount=divcount+1;
		document.getElementById('totaldivcount').value = totaldivcount;
        if($('#mainpage').children().is('#divfrm'+divcount+'')==true)
			{
				var prev_weightvalue=22;
				var curnt_div = divcount-1
				elementweight = 0;
				$('#divfrm'+curnt_div+'').children('div').each(function(index, element)
					{
						
						var tempweightvalue=$(this).attr('elementweight');
						tempweightvalue=parseInt(tempweightvalue)
						prev_weightvalue=parseInt(prev_weightvalue)-tempweightvalue;
						if(prev_weightvalue > 0)
						{
							that = $(this);
						}
						else
						{
							elementweight+=tempweightvalue;
							console.log("elementweight",elementweight)
						}
					})
				$('#divfrm'+divcount+'').show();
				$('.divf').removeClass("active");
				$('#divfrm'+divcount+'').addClass("active");
				var sel_temp=$('#mainpage').find('.active').children('.print_temp').val() ||'';
				if(sel_temp!='')
				{
					$('#template_name').val(sel_temp);
				}
				else
				{
					$('#template_name').val("");
				}
				var sel_img=$('#mainpage').find('.active').children('.print_temp').attr("img_src") || '';
				if(sel_img!='')
				{			
					$('.device').css("background-image", "url("+sel_img+")");	
				}
				else
				{
					$('.device').css("background-image", "none");
				}
                var divindex=$('#divfrm'+divcount+'').index();
				console.log(divindex);
				var totaldivindex=$('#mainpage').children('div').length-1;				
				weightvalue=document.getElementById('weight').value;
				weightvalue=parseInt(weightvalue)-elementweight;
				var prevweightvalue=22;
				prevweightvalue=parseInt(prevweightvalue)-elementweight				
				//console.log("weightvalue",weightvalue)
				if(weightvalue < 0)
				{
					//console.log("weightvalue------------if",weightvalue);
					//console.log("111111111111111111111111111111111111");
					$('#divfrm'+divcount+'').children('div').each(function(index, element)
					{
						var tempweightvalue=$(this).attr('elementweight');
						tempweightvalue=parseInt(tempweightvalue)
						prevweightvalue=parseInt(prevweightvalue)-tempweightvalue;
						document.getElementById('weight').value = prevweightvalue;
						document.getElementById('divcount').value = divcount;delete_disable()
						delete_disable();
						currentdiv=divcount;							
						console.log("prevweightvalue",prevweightvalue);
						check_flag="false";
						//console.log("22222222222222222222222222222222");
						if(prevweightvalue<0)
						{
							//console.log("333333333333333333333333333333333333333333");
							check_flag="true"
							//console.log("prevweightvalue------------if",prevweightvalue);
							var divcounttt=divcount;
							divcounttt++;
                            if($('#mainpage').children().is('#divfrm'+divcounttt+'')==true)
								{
                                    //$('.divf').removeClass("active");
									//$('#divfrm'+divcount+'').addClass("active");
									//console.log("4444444444444444444444444444444444444444444");
                                    var remdiv=$(this).detach().prependTo('#divfrm'+divcounttt+'');									//document.getElementById('divcount').value = divcounttt;
									$('#divfrm'+divcount+'').children('div').each(function(index, element)
									{
										var prevweightvalue_new = 22;
										var tempweight_value=$(this).attr('elementweight');
										tempweight_value=parseInt(tempweight_value)
										prevweightvalue_new=parseInt(prevweightvalue_new)-tempweight_value;
										document.getElementById('weight').value = prevweightvalue_new;
									})
									//document.getElementById('weight').value = prevweightvalue;
									//console.log("divcounttt------------",divcounttt);
									//currentdiv=divcounttt;	
									//console.log("detachhhhhhhhh",remdiv,currentdiv)
									/////////////////rearrange();
								}
								else
								{
									//console.log("55555555555555555555555555555555555555555");
									totaldivcount=divcounttt;
                                    document.getElementById('totaldivcount').value = totaldivcount;
									$('<div id="divfrm'+divcounttt+'" class="divf" align="center"></div>').appendTo('#mainpage');
                                    $('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value=""  img_desc="" img_src="" start="" end="" file_name="" img_id="" relative_url=""></input>').appendTo('#divfrm'+divcounttt+'');
									$('#template_name').val("");
									$('.device').css("background-image", "none");
									//$('.divf').removeClass("active");
									//$('#divfrm'+divcounttt+'').addClass("active");
                                 	$('#divfrm'+divcounttt+'').hide();
									totaldivindex++;
									document.getElementById('divcount').value = totaldivcount;delete_disable()
									weightvalue=22;
									var elweight=$(this).attr('elementweight');
									weightvalue=parseInt(weightvalue)-elweight;
									document.getElementById('weight').value = weightvalue;
									pageclear();
									label = totaldivcount;
									$(".divf").sortable({
									forcePlaceholderSize: true,
									axis: "y",
									scroll: false,
									sort: function () {},
									placeholder: 'ui-state-highlight',
									receive: function () {},
									update: function (event, ui) {}
									});
									var remdiv=$(this).detach().appendTo('#divfrm'+divcounttt+'');
									//document.getElementById('divcount').value = divcounttt;
									currentdiv=divcounttt;	
									////////////////////rearrange();		
										
								}
							for(i=divindex;i<=totaldivindex;i++)
							{
								//console.log("66666666666666666666666666666666666666666666666")
								var curdivid=$("#mainpage").children('div').eq(i).attr('id');
								var redivtempcount = curdivid.replace(/\D/g,'');
								var inprevweightvalue=22;
								$('#divfrm'+redivtempcount+'').children('div').each(function(index, element)
								{
									//console.log("77777777777777777777777777777777777777");
									var intempweightvalue=$(this).attr('elementweight');
									intempweightvalue=parseInt(intempweightvalue)
									inprevweightvalue=parseInt(inprevweightvalue)-intempweightvalue;	
										if(inprevweightvalue<0)
											{
												//console.log("8888888888888888888888888888888888");
												    var redivcount=redivtempcount;
												    redivcount++;
													var cchee=$('#mainpage').children().is('#divfrm'+redivcount+'');
													if($('#mainpage').children().is('#divfrm'+redivcount+'')==true)
														{
															//console.log("9999999999999999999999999999999999999")
															var remdivv=$(this).detach().prependTo('#divfrm'+redivcount+'');
                                                            //$('.divf').removeClass("active");
															//$('#divfrm'+redivcount+'').addClass("active");
															//document.getElementById('divcount').value = redivcount;
															document.getElementById('weight').value = inprevweightvalue;
															//currentdiv=divcounttt;
														}
													else
														{
															//console.log("101010101010101010101001111100000");
															totaldivcount=redivcount;
															$('<div id="divfrm'+redivcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
                                                            $('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value="" relative_url="" img_desc="" img_src="" start="" end="" file_name="" img_id=""></input>').appendTo('#divfrm'+redivcount+'');
															$('#template_name').val("");
															$('.device').css("background-image", "none");				
															//$('.divf').removeClass("active");
															//$('#divfrm'+redivcount+'').addClass("active");
                                                         	$('#divfrm'+redivcount+'').hide();
															totaldivindex++;
															//document.getElementById('divcount').value = totaldivcount;
															//weightvalue=22;
															var elweight=$(this).attr('elementweight');
															//weightvalue=parseInt(weightvalue)-elweight;
															//document.getElementById('weight').value = weightvalue;
															pageclear();
															label = totaldivcount;
															$(".divf").sortable({
															forcePlaceholderSize: true,
															axis: "y",
															scroll: false,
															sort: function () {},
															placeholder: 'ui-state-highlight',
															receive: function () {},
															update: function (event, ui) {}
															});
															var remdivvv=$(this).detach().appendTo('#divfrm'+redivcount+'');	
															currentdiv=redivcount;															
														}
											}
								})
								
							}
						}
						else
						{
							
						}

					}); //
					if(check_flag=="false" && prevweightvalue > 0)
						{
							console.log("prevweightvalue------------else__IFFFFF",prevweightvalue);
							that.nextAll('.breadcrumb').detach().prependTo('#divfrm'+divcount+'');
							///////////////////////rearrange();
							document.getElementById('weight').value = prevweightvalue;
							document.getElementById('divcount').value = divcount;delete_disable();
							currentdiv=divcount;
							check_flag="";
						}
						 
				}
				else
				{
							console.log("11111---111---11--11--11--11--11--11")
							/////////////////////////rearrange();
							that.nextAll('.breadcrumb').detach().prependTo('#divfrm'+divcount+'');
							document.getElementById('weight').value = weightvalue;
							document.getElementById('divcount').value = divcount;delete_disable();
							currentdiv=divcount; 
							
							
				}
				//currentdiv=divcount;
				
			}
			else
			{
				console.log("1212121212121212122121212121212121");
				totaldivcount=divcount;
                //$('.device').css('background-image', 'none');
				$('<div id="divfrm'+divcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
                $('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value=""  img_desc="" img_src="" start="" end="" file_name="" img_id="" relative_url=""></input>').appendTo('#divfrm'+divcount+'');
				$('#template_name').val("");
				$('.device').css("background-image", "none");
				$('.divf').removeClass("active");
				$('#divfrm'+divcount+'').addClass("active");
             	document.getElementById('divcount').value = totaldivcount;delete_disable()
				document.getElementById('totaldivcount').value = totaldivcount;
				weightvalue=22;
				weightvalue=parseInt(weightvalue)-elementweight;
				document.getElementById('weight').value = weightvalue;
				pageclear();
				label = totaldivcount;
				$(".divf").sortable({
				forcePlaceholderSize: true,
				axis: "y",
				scroll: false,
			    sort: function () {},
			    placeholder: 'ui-state-highlight',
			    receive: function () {},
			    update: function (event, ui) {}
				});
				//add();
				that.nextAll('.breadcrumb').detach().prependTo('#divfrm'+divcount+'');
				that.detach().prependTo('#divfrm'+divcount+'');
				currentdiv=divcount;
			}
		
	}
 $('.divf').sortable({items: '> div:not(.header_sec,.first_sec)'});
 //$('.divf').sortable({items: '> div:not(.first_sec)'});
});//end on ready
$('.divf').sortable({items: '> div:not(.header_sec,.first_sec)'});