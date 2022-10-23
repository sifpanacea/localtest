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
}


$(document).ready(function() {
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
document.getElementById('divcount').value = divcount;
document.getElementById('totaldivcount').value = divcount;
var optionvalue
$('#FldCtrl').hide();
weightvalue=$('#weight').val();
var currentdiv=0;
var url=window.location.href;
var base_url;//=url.lastIndexOf("/",0);
base_url=url.substring(0, url.lastIndexOf('.'));

//$('<div id="divfrm'+divcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
$('<div id="divfrm'+divcount+'" class="divf active" align="center"></div>').appendTo('#mainpage');//.droppable().appendTo('#mainpage');
$('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value="" img_desc="" img_src="" start="" end="" file_name="" relative_url="" img_id=""></input>').appendTo('#divfrm'+divcount+'');

var jsondef = $('#jsondef').val();
//$('#jsondef').val('');
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
	console.log("ssssssssssssssssssssssssssssssssssssssssssss");
	var first_section = 0;
	sections = Array();
	secindex = 0;
	//$('#appcategory').val(app_category);
	//$('#date').val(app_expiry);
	var design_obj = $.parseJSON(jsondef);
	//var print_temp = $.parseJSON(print_temp);
	var start=0,end=2;
	// console.log(design_obj);
	for(var i in design_obj){
	console.log("sssssssssssssssssssssss34333sssssssssssssssssssss");
	 for(var s in design_obj[i]){
	 console.log("sssssssssssssssssssssssttttttttsssssssssssssssssssss");
		if(($.inArray(s,sections) == -1) || (sections.length === 0))
			{
			console.log("ssssssssssssssssssssssssssssssssssssss2222222222ssssss");
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
						//$('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value="" img_desc="" img_src="" start="" end="" file_name="" img_id=""></input>').appendTo('#divfrm'+divcount+'');
						document.getElementById('divcount').value = totaldivcount;
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
						$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb sec col-md-12" elementweight="'+elementweight+'"><label class="labcheck col-md-4 control-label" name="section" id="Label'+count+'" name="Label[]" chkvalue='+chkvalue+' chkkeyvalue="'+key+'" des="'+desc+'"></label><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a></div>').appendTo('#divfrm'+divcount+'');
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
							content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox"  '+chkdK+'"  value="'+key+'">Required</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description">'+desc+'</textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
							});
				sections[secindex]=fLabel;
				 }
		 
		for(var j in design_obj[i][s]){
		
			var y = design_obj[i][s][j].type;
			
			if(j != 'dont_use_this_name'){
				fType=y;
			if ((fType == 'text') || (fType == 'password') || (fType == 'newline')  || (fType == 'file') || (fType == 'number')|| (fType == 'month')|| (fType == 'time')|| (fType == 'color')|| (fType == 'range')|| (fType == 'SBreak')|| (fType == 'select')|| (fType == 'MChoice')|| (fType == 'wtext'))
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
						//$('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value="" img_desc="" img_src="" start="" end="" file_name="" img_id=""></input>').appendTo('#divfrm'+divcount+'');
						document.getElementById('divcount').value = totaldivcount;
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
						document.getElementById('divcount').value = totaldivcount;
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
				
				if ((fType == 'wtextarea') || (fType == 'date') || (fType == 'textarea') || (fType == 'instruction'))
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
						document.getElementById('divcount').value = totaldivcount;
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
			if(design_obj[i][s][j].required == "TRUE"){
			chkdR = "checked";
			}
			var chkdK = "";
			if(design_obj[i][s][j].key == "TRUE"){
			chkdK = "checked";
			
			$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="2"><label class="labcheck lalign col-md-4" name="text" id="Label'+count+'" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' min="'+design_obj[i][s][j].minlength+'" max="'+design_obj[i][s][j].maxlength+'" des="'+design_obj[i][s][j].description+'"></label><input type="" class="col-md-4 ialign " name="txt[]" id="intxt'+count+'" disabled></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value="'+design_obj[i][s][j].maxlength+'"></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value="'+design_obj[i][s][j].description+'"></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
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
				content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+'  value="'+design_obj[i][s][j].required+'" '+dis+'>Required</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description">'+design_obj[i][s][j].description+'</textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value="'+design_obj[i][s][j].maxlength+'"></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
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
			$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="2"><label class="labcheck lalign col-md-4" name="number" id="Label'+count+'" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' min="'+design_obj[i][s][j].minlength+'" max="'+design_obj[i][s][j].maxlength+'" des="'+design_obj[i][s][j].description+'"></label><input type="" class="col-md-4 ialign " name="txt[]" id="intxt'+count+'" disabled></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value="'+design_obj[i][s][j].maxlength+'"></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value="'+design_obj[i][s][j].description+'"></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
			
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
				content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">Required</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description">'+design_obj[i][s][j].description+'</textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value="'+design_obj[i][s][j].maxlength+'"></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
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
				content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">Required</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description">'+design_obj[i][s][j].description+'</textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value="'+design_obj[i][s][j].maxlength+'"></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
				})
				//$('#prop'+count+'').popover('show');
		//		$('#prop'+count+'').trigger( "click" );
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
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">Required</label></div><div class="form-inline ins"><textarea class="ins'+count+' custom-scroll popinstextarea form-control col-md-5" id="ins'+count+'" type="text" value="" placeholder="Instructions">'+design_obj[i][s][j].instructions+'</textarea><textarea class="des'+count+' custom-scroll popinstextarea form-control col-md-5" id="des'+count+'" type="text" value="" placeholder="Description">'+design_obj[i][s][j].description+'</textarea></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsaveins popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		
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
				if(design_obj[i][s][j].required == "TRUE"){
				chkdR = "checked";
				}
				var chkdK = "";
				if(design_obj[i][s][j].key == "TRUE"){
				chkdK = "checked";
				$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="4"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="textarea" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' min="'+design_obj[i][s][j].minlength+'" max="'+design_obj[i][s][j].maxlength+'" des="'+design_obj[i][s][j].description+'"></label><weight style="visibility:hidden">textarea</weight><textarea class="col-md-4 textdisable" row="2" name="txt[]" id="intxt'+count+'" disabled></textarea><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value="'+design_obj[i][s][j].maxlength+'"></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value="">'+design_obj[i][s][j].description+'</input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
				}else{
					$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="4"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="wtextarea" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+'  min="'+design_obj[i][s][j].minlength+'" max="'+design_obj[i][s][j].maxlength+'" des="'+design_obj[i][s][j].description+'"></label><div class="col-md-5 hralign"><hr class="widget"><hr class="widget"><hr class="widget"></div></textarea><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value="'+design_obj[i][s][j].maxlength+'"></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value="">'+design_obj[i][s][j].description+'</input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
				}
			
			
			$('#Label'+count+'').text(fLabel);
			$('#FldCtrl').hide();
			$('div').remove('.tempdel');
			
			$('#prop'+count+'').popover({
			html:true,
			title: 'Properties',
			content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">Required</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description">'+design_obj[i][s][j].description+'</textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value="'+design_obj[i][s][j].minlength+'"></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value="'+design_obj[i][s][j].maxlength+'"></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
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
				tagval = (tagvalarr).toString();
				count ++;
		
				$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="2"><label class="labcheck lalign col-md-4 control-label" name="select" id="Label'+count+'" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' des="'+design_obj[i][s][j].description+'" tags="'+tagval+'"></label><div id="selectdiv'+count+'" class="col-md-5"><label class="select"><select id="select'+count+'"></select></label></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
					var tcount=1;
					$('#Label'+count+'').text(fLabel);	
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
					content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">Required</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="selects'+count+'" type="text" value="'+tagval+'" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description">'+design_obj[i][s][j].description+'</textarea><input type="button" class="btn btn-default btn-sm popsavesel popsavebtn" id="savebtn'+count+'" value="Save"/></div>')//$('#divprop'+count+'').html()
					})
					$('#prop'+count+'').on('click',function(e)
					{
						var addusr= $(this).attr('id');
						tempcount = addusr.replace(/\D/g,'');
						$('#selects'+tempcount+'').tagsinput({
						})
					})
					$('#AddMoreFileBox').show();

				break;
			case 'MChoice':
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
					$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="2"><label class="labcheck lalign col-md-4 control-label" id="Label'+count+'" name="mchoice" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' des="'+design_obj[i][s][j].description+'" tags="'+tagval+'"></label><div id="selectdiv'+count+'" class="col-md-5"><label class="select select-multiple"><select multiple class="custom-scroll" id="select'+count+'"></select></label></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
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
						content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">Required</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="selects'+count+'" type="text" value="'+tagval+'" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description">'+design_obj[i][s][j].description+'</textarea><input type="button" class="btn btn-default btn-sm popsavesel popsavebtn" id="savebtn'+count+'" value="Save"/></div>')//$('#divprop'+count+'').html()
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
			case 'radio':
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
						$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="1"><label class="labcheck lalign col-md-4 control-label" id="Label'+count+'" name="radio" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' des="'+design_obj[i][s][j].description+'" tags="'+tagval+'"></label><div id="raddiv'+count+'" class="col-md-6 radd"></div><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
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
						content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">Required</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="radioval'+count+'" type="text" value="'+tagval+'" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description">'+design_obj[i][s][j].description+'</textarea>'+'<input type="button" class="btn btn-default btn-sm popsaverad popsavebtn" id="savebtn'+count+'" value="Save"/></div>')//$('#divprop'+count+'').html()
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
			case 'checkbox':
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
						$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="1"><label class="labcheck lalign col-md-4 control-label" id="Label'+count+'" name="checkbox" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' des="'+design_obj[i][s][j].description+'" tags="'+tagval+'"></label><div id="chkdiv'+count+'" class="col-md-6"></div><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
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
							content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">Required</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="chkval'+count+'" type="text" value="'+tagval+'" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description">'+design_obj[i][s][j].description+'</textarea><input type="button" class="btn btn-default btn-sm popsavechk popsavebtn" id="savebtn'+count+'" value="Save"/></div>')//$('#divprop'+count+'').html()
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
					
							$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="2"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="file" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' min="" max="" des=""></label><input type="file" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

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
							content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value='+design_obj[i][s][j].required+'>Required</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description">'+design_obj[i][s][j].description+'</textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
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

							$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="2"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="range" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' min="'+design_obj[i][s][j].minrange+'" max="'+design_obj[i][s][j].maxrange+'" des=""></label><input type="range" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled style="width:176px"></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
							
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
				case "date":
							//weightvalue=weightvalue-1;
							//alert(weightvalue);
							fLabel = j;
							// console.log(design_obj[i][s][j]);
							fType = "date";
							//alert(fType);
							//console.log(design_obj[i][s][j]);
							count ++;

							$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="4"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue='+design_obj[i][s][j].required+' chkkeyvalue='+design_obj[i][s][j].key+' min="'+design_obj[i][s][j].minlength+'" max="'+design_obj[i][s][j].maxlength+'" des="'+design_obj[i][s][j].description+'"></label><input type="" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled style="height:20px"></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

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
				case "time":
							//weightvalue=weightvalue-1;
							//alert(weightvalue);
							fLabel = j;
							// console.log(design_obj[i][s][j]);
							fType = "time";
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
							content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="'+fLabel+'" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" '+chkdR+' value="'+design_obj[i][s][j].required+'">Required</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description">'+design_obj[i][s][j].description+'</textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
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
					
					$('<div id="div'+count+'" class="crdiv'+count+' opac breadcrumb col-md-12" elementweight="'+elementweight+'"><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
				
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
				
			}
			}
		}
	 }
	 
		
	 
	}
}


$(AddButton).click(function (e)  //on add input button click
{
	
	    if($('.foraddbutton').height()>440)
{
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
           $('<div class="col-md-12 breadcrumb ddd"><div class="startup"><label class="radio-inline"><input type="radio" class="form-input start" value="app" name="rad" id="intxt">Typable</label><label class="radio-inline"><input type="radio" class="form-input start" id="intxt" name="rad"value="widget">Writable</label><div class="col-md-1 pull-right"><button type="button" class="removeclass btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus-sign"></span></button></div></div></div>').appendTo('#FldCtrl');
		   
$('.start').change(function()
{
	var selectval=$('[name=rad]:checked').val();
	$('.ddd').hide();
	if(selectval=='app')
	{
    $('<div class="col-md-12 breadcrumb tempdel"><select class="selectpicker" id="typval"> <option value="SBreak">Section Break</option><option value="newline">New Line</option><option value="description">Instruction</option><option id="optdefalt" value="text">One Line Text</option> <option value="number">Number</option> <option value="radio">Radio</option><option value="textarea">Multi Line Text</option><option value="file">File upload</option><option value="select">DropDown</option> <option value="checkbox">Checkboxes</option><option value="MChoice">Multiple Choice</option><option value="date">Date</option> <option value="time" disabled>Time</option> <option value="range" disabled>Range Bar</option> <option value="color" disabled>Color Picker</option> <option value="month" disabled>Month</option> <option value="password" disabled>Password</option></select>&nbsp;&nbsp;<div class="col-md-2 pull-right"><button type="button" class="removeclass btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus-sign"></span></button>&nbsp;<button type="button" class="addclass btn btn-success btn-xs"><span class="glyphicon glyphicon-ok"></span></button></div></div>').appendTo('#FldCtrl');
	//$('<div class="col-md-12 breadcrumb tempdel"><select class="selectpicker" id="typval"> <option value="SBreak">Section Break</option><option value="description">Instruction</option><option id="optdefalt" value="text">One Line Text</option> <option value="number">Number</option> <option value="radio">Radio</option><option value="textarea">Multi Line Text</option> <option value="password">Password</option> <option value="select">DropDown</option> <option value="checkbox">Checkboxes</option><option value="MChoice">Multiple Choice</option> <option value="PBreak">Page Break</option></select>&nbsp;&nbsp;<div class="col-md-2 pull-right"><button type="button" class="removeclass btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus-sign"></span></button>&nbsp;<button type="button" class="addclass btn btn-success btn-xs"><span class="glyphicon glyphicon-ok"></span></button></div></div>').appendTo('#FldCtrl');
	//description<label class="checkbox-inline"><input id="chkreqq" name="" class="chkreq" type="checkbox" value="">Required</label><label class="checkbox-inline"><input id="isakeyy" name="" class="isakey" type="checkbox" value="">Is a key?</label>
	}
	else
	{
		$('<div class="col-md-12 breadcrumb tempdel"><select class="" id="typval"><option id="optdefalt" value="wtext">One Line Text</option><option value="wtextarea">Multi Line Text</option><option value="freewriting" disabled>Free Writing</option></select><div class="col-md-2 pull-right"><button type="button" class="removeclass btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus-sign"></span></button>&nbsp;<button type="button" class="addclass btn btn-success btn-xs"><span class="glyphicon glyphicon-ok"></span></button></div></div>').appendTo('#FldCtrl');
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
		$('#mytext').attr('placeholder','Text');//placing defualt text
		//////alert($('#optdefalt').val())
		$('#optdefalt').attr('selected','selected');//default option select

});// end of addbutton

$("body").on("click",".addclass", function(e){ //user click on add

if(currentdiv>0)//&& currentdiv!=totaldivcount::::::::::
{
        // console.log("sssssssssssssssss"+currentdiv);
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

fLabel = $('#mytext').val();
fType=$('#typval').val();		
if ((fType == 'text') || (fType == 'password') || (fType == 'newline') || (fType == 'file') || (fType == 'number')|| (fType == 'month')|| (fType == 'time')|| (fType == 'color')|| (fType == 'range')|| (fType == 'SBreak')|| (fType == 'select')|| (fType == 'MChoice')|| (fType == 'wtext'))
{
	elementweight=2;
	weightvalue=parseInt(weightvalue)-elementweight;
	// console.log("eeeeeeeeeeeeeeeee",weightvalue);
	if(weightvalue < 0)//<<<<<<<<<<<<
	{
		checkval(elementweight);
	}
	else
	{
		checkvall();
	}
}
if ((fType == 'radio') || (fType == 'checkbox'))
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

if ((fType == 'wtextarea') || (fType == 'date') || (fType == 'textarea') || (fType == 'description'))
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

function checkval(elementweight)
	{
		$('#divfrm'+divcount+'').hide();
		divcount=divcount+1;
		document.getElementById('totaldivcount').value = totaldivcount;
        if($('#mainpage').children().is('#divfrm'+divcount+'')==true)
			{
				$('#divfrm'+divcount+'').show();
                $('#divfrm'+divcount+'').addClass("active");
				var divindex=$('#divfrm'+divcount+'').index();
				var totaldivindex=$('#mainpage').children('div').length-1;				
				weightvalue=document.getElementById('weight').value;
				weightvalue=parseInt(weightvalue)-elementweight;
				var prevweightvalue=22;
				prevweightvalue=parseInt(prevweightvalue)-elementweight				
				if(weightvalue < 0)
				{
					$('#divfrm'+divcount+'').children('div').each(function(index, element)
					{
							var tempweightvalue=$(this).attr('elementweight');
							tempweightvalue=parseInt(tempweightvalue)
							prevweightvalue=parseInt(prevweightvalue)-tempweightvalue;
							document.getElementById('weight').value = prevweightvalue;
							document.getElementById('divcount').value = divcount;
							currentdiv=divcount;							
						
					 
						if(prevweightvalue<0)
						{
							var divcounttt=divcount;
							divcounttt++;
                            if($('#mainpage').children().is('#divfrm'+divcounttt+'')==true)
								{
                                    $('.divf').removeClass("active");
									$('#divfrm'+divcount+'').addClass("active");
                                    var remdiv=$(this).detach().prependTo('#divfrm'+divcounttt+'');
									document.getElementById('divcount').value = divcounttt;
									document.getElementById('weight').value = prevweightvalue;
									currentdiv=divcounttt;							
									rearrange();
								}
								else
								{
									totaldivcount=divcounttt;
                                    document.getElementById('totaldivcount').value = totaldivcount;
									$('<div id="divfrm'+divcounttt+'" class="divf" align="center"></div>').appendTo('#mainpage');
                                    $('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value=""  img_desc="" img_src="" start="" end="" file_name="" img_id="" relative_url=""></input>').appendTo('#divfrm'+divcounttt+'');
									$('#template_name').val("");
									$('.divf').removeClass("active");
									$('#divfrm'+divcounttt+'').addClass("active");
                                 	$('#divfrm'+divcounttt+'').hide();
									totaldivindex++;
									document.getElementById('divcount').value = totaldivcount;
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
									document.getElementById('divcount').value = divcounttt;
									currentdiv=divcounttt;	
									rearrange();		
										
								}
							for(i=divindex;i<=totaldivindex;i++)
							{
								var curdivid=$("#mainpage").children('div').eq(i).attr('id');
								var redivtempcount = curdivid.replace(/\D/g,'');
								var inprevweightvalue=20;
								$('#divfrm'+redivtempcount+'').children('div').each(function(index, element)
								{
									var intempweightvalue=$(this).attr('elementweight');
									intempweightvalue=parseInt(intempweightvalue)
									inprevweightvalue=parseInt(inprevweightvalue)-intempweightvalue;	
										if(inprevweightvalue<0)
											{
												    var redivcount=redivtempcount;
												    redivcount++;
													var cchee=$('#mainpage').children().is('#divfrm'+redivcount+'');
													if($('#mainpage').children().is('#divfrm'+redivcount+'')==true)
														{
															var remdivv=$(this).detach().prependTo('#divfrm'+redivcount+'');
                                                            $('.divf').removeClass("active");
															$('#divfrm'+redivcount+'').addClass("active");
															document.getElementById('divcount').value = redivcount;
															document.getElementById('weight').value = inprevweightvalue;
															currentdiv=divcounttt;
														}
													else
														{
															totaldivcount=redivcount;
															$('<div id="divfrm'+redivcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
                                                            $('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value="" relative_url="" img_desc="" img_src="" start="" end="" file_name="" img_id=""></input>').appendTo('#divfrm'+redivcount+'');
															$('#template_name').val("");
															$('.divf').removeClass("active");
															$('#divfrm'+redivcount+'').addClass("active");
                                                         	$('#divfrm'+redivcount+'').hide();
															totaldivindex++;
															document.getElementById('divcount').value = totaldivcount;
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

					});
						 
				}
				else
				{
							
							rearrange();
							document.getElementById('weight').value = weightvalue;
							document.getElementById('divcount').value = divcount;
							currentdiv=divcount; 
							
							
				}
				
			}
			else
			{
				totaldivcount=divcount;
                $('.device').css('background-image', 'none');
				$('<div id="divfrm'+divcount+'" class="divf" align="center"></div>').appendTo('#mainpage');
                	$('<input type="hidden" id="print_temp'+divcount+'" class="print_temp" value=""  img_desc="" img_src="" start="" end="" file_name="" img_id="" relative_url=""></input>').appendTo('#divfrm'+divcount+'');
				$('#template_name').val("");
				$('.divf').removeClass("active");
				$('#divfrm'+divcount+'').addClass("active");
             	document.getElementById('divcount').value = totaldivcount;
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
			 add();

		}
function add()
{
count+=1;
if($('#mainpage').children('div').children('div').length==0)
		{ 
		      
	            {
					if(fType != 'SBreak')
					{
                      elementweight=2;
				      weightvalue=parseInt(weightvalue)-elementweight;
					  document.getElementById('weight').value = weightvalue;
$('<div id="div'+count+'" class="crdiv'+count+' first_sec breadcrumb sec col-md-12" elementweight="'+elementweight+'"><label class="labcheck col-md-4 control-label" name="section" id="Label'+count+'" name="Label[]" chkvalue='+chkvalue+' chkkeyvalue='+chkkeyvalue+' des=""></label><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a></div>').appendTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text("Section Name");
		
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput_ form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="hide checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label><label class="hide checkbox-inline"><input id="isakeyy'+count+'" name="" class="isakey" type="checkbox" value="">Is a key?</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea_" id="des'+count+'" type="text" value="" placeholder="Description"></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
		count++;
					}
					
					
	       }
		};
		

if(fType == 'wtext')
{

$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="2"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="wtext" chkvalue="FALSE" chkkeyvalue="FALSE"></label><hr class="widget col-md-4 hralign"><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
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

		$('#Label'+count+'').text("Field Name");
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


$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue='+chkvalue+' chkkeyvalue='+chkkeyvalue+' notify="" min="" max="" des=""></label><input type="" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text("Field Name");
		//$('#Labelmin'+count+'').text("Min");
		//$('#Labelmax'+count+'').text("Max");
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
		//$('#prop'+count+'').popover('show');
//		$('#prop'+count+'').trigger( "click" );
$('#AddMoreFileBox').show();


}//end of text

if ((fType == 'date') || (fType == 'time') || (fType == 'color') || (fType == 'month'))
{
//weightvalue=weightvalue-1;
////alert(weightvalue);
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des="" notify=""></label><input type="" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled style="height:20px"></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text("Field Name");
		//$('#Labelmin'+count+'').text("Min");
		//$('#Labelmax'+count+'').text("Max");
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label><label class="checkbox-inline hide"><input id="notify'+count+'" name="" class="notify" type="checkbox" value="">notify</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
		//$('#prop'+count+'').popover('show');
//		$('#prop'+count+'').trigger( "click" );
$('#AddMoreFileBox').show();


}//end of date/time/color/month

if ((fType == 'file'))
{

$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des=""></label><input type="file" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text("Field Name");
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})


$('#AddMoreFileBox').show();
}//end of file

if ((fType == 'range'))
{
//weightvalue=weightvalue-1;
////alert(weightvalue);

$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des="" notify=""></label><input type="range" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled style="width:176px"></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text("Field Name");
		//$('#Labelmin'+count+'').text("From");
		//$('#Labelmax'+count+'').text("To");
		//$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		//tAdd_Txtbox(fLabel,fType,"0","60",chkvalue, chkkeyvalue);//JSON Creation
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
		
$('#AddMoreFileBox').show();
}//end of range


if(fType == 'SBreak')
{
//weightvalue=weightvalue-1;
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb sec col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck col-md-4 control-label" name="section" id="Label'+count+'" name="Label[]" chkvalue="TRUE" chkkeyvalue="TRUE" des=""></label><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text("Section Name");
		//$('#Label'+count+'').text(fLabel);
		$('#intxt'+count+'').attr('type', fType);//sssssssss
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput_ form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="hide checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea_" id="des'+count+'" type="text" value="" placeholder="Description"></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
		$('#AddMoreFileBox').show();		
}//end of text

if(fType == 'description')
{
//weightvalue=weightvalue-1;
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb ins desc col-md-12 draggable" elementweight="6"><label class="labcheck col-md-4 control-label" name="instructions" id="Label'+count+'" name="Label[]" chkvalue="TRUE" chkkeyvalue="TRUE" des="" instruct=""></label><div class="inst lab"><p class="labelch labcheck" id="inss'+count+'"></p></div><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class="ins delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text("Instruction");
		//$('#Label'+count+'').text(fLabel);
		//$('#intxt'+count+'').attr('type', fType);//sssssssss
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="hide checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div class="form-inline ins"><textarea class="ins'+count+' custom-scroll popinstextarea form-control col-md-5" id="ins'+count+'" type="text" value="" placeholder="Instructions"></textarea><textarea class="des'+count+' custom-scroll popinstextarea form-control col-md-5" id="des'+count+'" type="text" value="" placeholder="Description"></textarea></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsaveins popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		
		})
		$('#AddMoreFileBox').show();		
}//end of text

else if (fType == 'textarea')
{
	// console.log("%%%%%%%%%%%%%%%%%%%%%");
//weightvalue=weightvalue-1;
$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="textarea" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des="" notify=""></label><weight style="visibility:hidden">textarea</weight><textarea class="col-md-4 textdisable" row="2" name="txt[]" id="intxt'+count+'" disabled></textarea><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');


		$('#Label'+count+'').text("Textarea Name");
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
$('#AddMoreFileBox').show();
}//textarea end

else if (fType == 'newline')
{
//weightvalue=weightvalue-1;
console.log("nnnnnnnnnnnnnnnnn");
$('<div id="div'+count+'" class="crdiv'+count+' opac breadcrumb col-md-12" elementweight="'+elementweight+'"><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');

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
		
	$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4 control-label" name="select" id="Label'+count+'" chkvalue="TRUE" chkkeyvalue="TRUE" des="" tags="" notify=""></label><div id="selectdiv'+count+'" class="col-md-5"><label class="select"><select id="select'+count+'"></select></label></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
	
		var tcount=1;
		$('#Label'+count+'').text("SelectBox Name");	
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
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="selects'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="button" class="btn btn-default btn-sm popsavesel popsavebtn" id="savebtn'+count+'" value="Save"/></div>')//$('#divprop'+count+'').html()
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
		
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4 control-label" id="Label'+count+'" name="mchoice" chkvalue="TRUE" chkkeyvalue="TRUE" des="" notify="" tags=""></label><div id="selectdiv'+count+'" class="col-md-5"><label class="select select-multiple"><select multiple class="custom-scroll" id="select'+count+'"></select></label></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
			//<div id="select'+count+'" class="col-md-5"></div><i id="selectspanadd'+count+'" class="addlabel addlabels glyphicon glyphicon-plus"></i>
		var tcount=1;
		$('#Label'+count+'').text("Multiselect Name");	
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
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="selects'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="button" class="btn btn-default btn-sm popsavesel popsavebtn" id="savebtn'+count+'" value="Save"/></div>')//$('#divprop'+count+'').html()
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
		
	$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4 control-label" id="Label'+count+'" name="radio" chkvalue="TRUE" chkkeyvalue="TRUE" des="" tags="" notify=""></label><div id="raddiv'+count+'" class="col-md-6 radd"></div><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
		
	//<div id="radiodiv'+count+'" class="col-md-5"></div><i id="radiospanadd'+count+'" class="addlabel addlabelr glyphicon glyphicon-plus"></i>
		var tcount=1;
		$('#Label'+count+'').text("Radio Name");	
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
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="radioval'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea>'+'<input type="button" class="btn btn-default btn-sm popsaverad popsavebtn" id="savebtn'+count+'" value="Save"/></div>')//$('#divprop'+count+'').html()
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
		
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4 control-label" id="Label'+count+'" name="checkbox" chkvalue="TRUE" chkkeyvalue="TRUE" des="" tags="" notify=""></label><div id="chkdiv'+count+'" class="col-md-6"></div><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#divfrm'+divcount+'');
	
		//<div id="checkdiv'+count+'" class="col-md-5"></div><i id="checkspanadd'+count+'" class="addlabelc addlabel glyphicon glyphicon-plus"></i>
	
		$('#Label'+count+'').text("Checkbox Name");	
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
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="chkval'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="button" class="btn btn-default btn-sm popsavechk popsavebtn" id="savebtn'+count+'" value="Save"/></div>')//$('#divprop'+count+'').html()
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
		
		$('#Label'+count+'').text("Field Name");
		$('#intxt'+count+'').attr('type', fType);
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

if(fType == 'freewriting')
{
//weightvalue=weightvalue-1;
$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="textarea" chkvalue='+chkvalue+' chkkeyvalue='+chkkeyvalue+'></label><textarea class="col-md-4 freewriting freewrite'+count+'" row="2" name="txt[]" id="intxt'+count+'" disabled></textarea><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text("Field Name");
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

$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des="" notify=""></label><input type="" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text("Field Name");
		//$('#Labelmin'+count+'').text("Min");
		//$('#Labelmax'+count+'').text("Max");
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
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

if ((fType == 'date') || (fType == 'time') || (fType == 'color') || (fType == 'month'))
{
//weightvalue=weightvalue-1;
////alert(weightvalue);
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des="" notify=""></label><input type="" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled style="height:20px"></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text("Field Name");
		//$('#Labelmin'+count+'').text("Min");
		//$('#Labelmax'+count+'').text("Max");
		$('#intxt'+count+'').attr('type', fType);
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
		//$('#prop'+count+'').popover('show');
//		$('#prop'+count+'').trigger( "click" );
$('#AddMoreFileBox').show();


}//end of date/time/color/month

if ((fType == 'file'))
{

$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des="" notify=""></label><input type="file" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');

		$('#Label'+count+'').text("Field Name");
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
	
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline check_align"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})


$('#AddMoreFileBox').show();
}//end of file

if ((fType == 'range'))
{
//weightvalue=weightvalue-1;
////alert(weightvalue);

$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4" id="Label'+count+'" name="'+fType+'" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des=""></label><input type="range" class="col-md-4 ialign " name="" id="intxt'+count+'" disabled style="width:176px"></input><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text("Field Name");
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
		
		$('#Label'+count+'').text("Section Name");
		//$('#Label'+count+'').text(fLabel);
		$('#intxt'+count+'').attr('type', fType);//sssssssss
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput_ form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="hide checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea_" id="des'+count+'" type="text" value="" placeholder="Description"></textarea>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavesec popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
		$('#AddMoreFileBox').show();		
}//end of text

if(fType == 'description')
{
//weightvalue=weightvalue-1;
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb ins desc col-md-12 draggable" elementweight="6"><label class="labcheck col-md-4 control-label" name="instructions" id="Label'+count+'" name="Label[]" chkvalue="TRUE" chkkeyvalue="TRUE" des="" instruct=""></label><div class="inst lab"><p class="labelch labcheck" id="inss'+count+'"></p></div><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class="ins delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');
		
		$('#Label'+count+'').text("Instruction");
		//$('#Label'+count+'').text(fLabel);
		//$('#intxt'+count+'').attr('type', fType);//sssssssss
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="hide checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div class="form-inline ins"><textarea class="ins'+count+' custom-scroll popinstextarea form-control col-md-5" id="ins'+count+'" type="text" value="" placeholder="Instructions"></textarea><textarea class="des'+count+' custom-scroll popinstextarea form-control col-md-5" id="des'+count+'" type="text" value="" placeholder="Description"></textarea></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsaveins popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		
		})
		$('#AddMoreFileBox').show();		
}//end of text

else if (fType == 'textarea')
{
//weightvalue=weightvalue-1;
$('<div id="div'+count+'" class="crdiv'+count+' col-md-12 breadcrumb draggable" elementweight="'+elementweight+'"><label class="lalign labcheck col-md-4 control-label" id="Label'+count+'" name="textarea" chkvalue="TRUE" chkkeyvalue="TRUE" min="" max="" des=""></label><weight style="visibility:hidden">textarea</weight><textarea class="col-md-4 textdisable" row="2" name="txt[]" id="intxt'+count+'" disabled></textarea><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"><input type="text" class="col-md-4 ialign" id="fMin'+count+'"placeholder="Min" value=""></input><input type="text" class="col-md-4 ialign" id="fMax'+count+'"placeholder="Max" value=""></input><input type="text" class="col-md-4 ialign" id="fDes'+count+'" placeholder="Description" value=""></input></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');


		$('#Label'+count+'').text("Textarea Name");
		$('#FldCtrl').hide();
		$('div').remove('.tempdel');
		
		$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline check_align"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label><label class="checkbox-inline"></label></div><div class="form-inline"><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="text" class="col-md-4 form-control popminmax" id="min'+count+'"placeholder="Min Value" value=""></input><input type="text" class="col-md-4 form-control popminmax popmaxin" id="max'+count+'"placeholder="Max Value" value=""></input></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsave popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
		})
$('#AddMoreFileBox').show();
}//textarea end

else if (fType == 'select')//user click on select
{
		//weightvalue=weightvalue-1;
	    tagval="value,value,value";
		
	$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4 control-label" name="select" id="Label'+count+'" chkvalue="TRUE" chkkeyvalue="TRUE" des="" tags="" notify=""></label><div id="selectdiv'+count+'" class="col-md-5"><label class="select"><select id="select'+count+'"></select></label></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');
	
		var tcount=1;
		$('#Label'+count+'').text("SelectBox Name");	
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
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="selects'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="button" class="btn btn-default btn-sm popsavesel popsavebtn" id="savebtn'+count+'" value="Save"/></div>')//$('#divprop'+count+'').html()
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
		
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4 control-label" id="Label'+count+'" name="mchoice" chkvalue="TRUE" chkkeyvalue="TRUE" des="" notify="" tags=""></label><div id="selectdiv'+count+'" class="col-md-5"><label class="select select-multiple"><select multiple class="custom-scroll" id="select'+count+'"></select></label></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><div class="hide" id="divprop'+count+'"></div><div class="hide" id="properties'+count+'"></div><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');
			//<div id="select'+count+'" class="col-md-5"></div><i id="selectspanadd'+count+'" class="addlabel addlabels glyphicon glyphicon-plus"></i>
		var tcount=1;
		$('#Label'+count+'').text("Multiselect Name");	
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
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="selects'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="button" class="btn btn-default btn-sm popsavesel popsavebtn" id="savebtn'+count+'" value="Save"/></div>')//$('#divprop'+count+'').html()
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
		
	$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4 control-label" id="Label'+count+'" name="radio" chkvalue="TRUE" chkkeyvalue="TRUE" notify="" des="" tags=""></label><div id="raddiv'+count+'" class="col-md-6 radd"></div><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');
		
	//<div id="radiodiv'+count+'" class="col-md-5"></div><i id="radiospanadd'+count+'" class="addlabel addlabelr glyphicon glyphicon-plus"></i>
		var tcount=1;
		$('#Label'+count+'').text("Radio Name");	
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
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="radioval'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea>'+'<input type="button" class="btn btn-default btn-sm popsaverad popsavebtn" id="savebtn'+count+'" value="Save"/></div>')//$('#divprop'+count+'').html()
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
		
$('<div id="div'+count+'" class="crdiv'+count+' breadcrumb col-md-12 draggable" elementweight="'+elementweight+'"><label class="labcheck lalign col-md-4 control-label" id="Label'+count+'" name="checkbox" chkvalue="TRUE" chkkeyvalue="TRUE" des="" notify="" tags=""></label><div id="chkdiv'+count+'" class="col-md-6"></div><div class="hide" id="divprop'+count+'"></div><a href="javascript:void(0);" id="prop'+count+'"class=" delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').prependTo('#divfrm'+divcount+'');
	
		//<div id="checkdiv'+count+'" class="col-md-5"></div><i id="checkspanadd'+count+'" class="addlabelc addlabel glyphicon glyphicon-plus"></i>
	
		$('#Label'+count+'').text("Checkbox Name");	
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
		content:$('<div class="well"><div class="form-inline"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Field Name"/><label class="checkbox-inline"><input id="chkreqq'+count+'" name="" class="chkreq" type="checkbox" value="">Required</label></div><div id="tags'+count+'"><input class="selects'+count+' tagsinput" id="chkval'+count+'" type="text" value="" data-role="tagsinput"/></div><textarea class="des'+count+' custom-scroll form-control col-md-6 poptextarea" id="des'+count+'" type="text" value="" placeholder="Description"></textarea><input type="button" class="btn btn-default btn-sm popsavechk popsavebtn" id="savebtn'+count+'" value="Save"/></div>')//$('#divprop'+count+'').html()
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
			var req;
			if($('#notify'+tempcount+'').is(':checked'))
			{
				$('#Label'+tempcount+'').attr("notify",true);
			}
			else
			{
				$('#Label'+tempcount+'').attr("notify",false);
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
			$('#Label'+tempcount+'').text("Field Name");
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
	
	$(document).on('click','.popsavesel', function (e) 
		{
			e.stopPropagation();
			e.preventDefault();
			////alert("clicked")
			var prop= $(this).attr('id');
			tempcount = prop.replace(/\D/g,'');
			// console.log("hiding"+tempcount);
			var nameval=$('#name'+tempcount+'').val();
			var selecttag=$('#selects'+tempcount+'').val()
			var desval=$('#des'+tempcount+'').val();
			var tagsval=$('#selects'+tempcount+'').tagsinput('items')
			var req;
			// console.log("vvvvvvvvvvvvvvv"+tagsval);
			if($('#notify'+tempcount+'').is(':checked'))
			{
				$('#Label'+tempcount+'').attr("notify",true);
			}
			else
			{
				$('#Label'+tempcount+'').attr("notify",false);
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
			$('#Label'+tempcount+'').text("Select Name");
			}
			$('#select'+tempcount+'').empty();
			//$('#name'+tempcount+'').attr("value",nameval);
			var tagg = selecttag.split(",");
			for (var i in tagg)
			{
			$('<option value="'+tagg[i]+'">'+tagg[i]+'</option>').appendTo('#select'+tempcount+'');
			//$('#vpermintags'+tempcount+'').tagsinput('add', ''+tagg[i]+'');
			}
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
				$('#Label'+tempcount+'').attr("notify",true);
			}
			else
			{
				$('#Label'+tempcount+'').attr("notify",false);
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
			$('#Label'+tempcount+'').text("Radio Name");
			}
			$('#raddiv'+tempcount+'').empty();
			//$('#name'+tempcount+'').attr("value",nameval);
			var tagg = selecttag.split(",");
			for (var i in tagg)
			{
			$('<label class="radio radio-inline radiomargin"><input type="radio" name="radio-inline" value="'+tagg[i]+'" checked="checked"/>'+tagg[i]+'</label>').appendTo('#raddiv'+tempcount+'');
			//$('#vpermintags'+tempcount+'').tagsinput('add', ''+tagg[i]+'');
			}
			//$('#fDes'+tempcount+'').attr("value",desval);
			$('#Label'+tempcount+'').attr("des",desval);
			$('#Label'+tempcount+'').attr("tags",tagsval);
			$('#prop'+tempcount+'').popover('hide')
	});
	
	$(document).on('click','.popsavechk', function (e) 
		{
			e.stopPropagation();
			e.preventDefault();
			////alert("clicked")
			var prop= $(this).attr('id');
			tempcount = prop.replace(/\D/g,'');
			// console.log("hiding"+tempcount);
			var nameval=$('#name'+tempcount+'').val();
			var selecttag=$('#chkval'+tempcount+'').val()
			var desval=$('#des'+tempcount+'').val();
			var tagsval=$('#chkval'+tempcount+'').val();
			var req;
			if($('#notify'+tempcount+'').is(':checked'))
			{
				$('#Label'+tempcount+'').attr("notify",true);
			}
			else
			{
				$('#Label'+tempcount+'').attr("notify",false);
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
			$('#Label'+tempcount+'').text("CheckBox Name");
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
			$('#Label'+tempcount+'').text(nameval);
			}
			else
			{
			// console.log("section")
			$('#Label'+tempcount+'').text("Section name");
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
			$('#Label'+tempcount+'').text("Instruction name");
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
				var sel_temp=$('#mainpage').find('.active').children('.print_temp').val();
				$('#template_name').val(sel_temp);
				var sel_img=$('#mainpage').find('.active').children('.print_temp').attr("img_src");
				$('.device').css("background-image", "url("+sel_img+")");
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
				document.getElementById('divcount').value = divcountt;
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
			  document.getElementById('divcount').value = tempdivcount; 
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
				var sel_temp=$('#mainpage').find('.active').children('.print_temp').val();
				$('#template_name').val(sel_temp);
				var sel_img=$('#mainpage').find('.active').children('.print_temp').attr("img_src");
				$('.device').css("background-image", "url("+sel_img+")");
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

				currentdiv=divcounttt;
				//var currPage = $('#divcount').val();
				//tDefLoad(currPage);	
			}
			else
			{
					////alert("Sorry No Page Found");				
			}	
})

	$(document).on('click','#Deletepage',function()
	{
	var div_count = $('.mainpage').children('.divf').length;
	//console.log(div_count);
	if(div_count == 1)
	{
		$('#divfrm'+div_count+'').remove();
		totaldivcount = div_count
		$('.device').css('background-image', '');
		$('<div id="divfrm'+div_count+'" class="divf" align="center"></div>').appendTo('#mainpage');
		$('<input type="hidden" id="print_temp'+div_count+'" class="print_temp" value="" img_desc="" img_src="" start="" end="" file_name="" img_id="" relative_url=""></input>').appendTo('#divfrm'+div_count+'');
		$('#template_name').val("");
		$('.divf').removeClass("active");
		$('#divfrm'+div_count+'').addClass("active");
		document.getElementById('divcount').value = totaldivcount;
		document.getElementById('totaldivcount').value = totaldivcount;
		weightvalue=22;
		document.getElementById('weight').value = weightvalue;
	}
	else
	{
		var delpage=$('#divcount').val();
		//console.log(delpage);
		delpage = parseInt(delpage);
		var show_page = 0;//delpage + 1;
		//console.log("nnnnnnnnnnnnnnnnnnnnn",nxtpage);
		$('#divfrm'+delpage+'').remove();
		if(delpage == div_count)
		{
			delpage = delpage - 1;
			show_page = delpage
			$('#divfrm'+show_page+'').show();
		}
		else
		{
			delpage = delpage + 1;
			show_page = delpage
			$('#divfrm'+show_page+'').show();
		}
		var id = 1;
		$('.mainpage').children().each(function()
		{
			$(this).attr('id', 'divfrm' + id );
			id++;
		})
		document.getElementById('totaldivcount').value = id;
		totaldivcount = id
		var current_weight_value = 0;
		$('#divfrm'+show_page+'').children('div').each(function()
		{
			var current_element = $(this).attr('elementweight');
			current_element = parseInt(current_element);
			current_weight_value = current_element;
		})
		document.getElementById('divcount').value = show_page;
		document.getElementById('weight').value = current_weight_value;
		$('#divfrm'+show_page+'').addClass("active");
		}
	})
        
$("body").on("click",".removediv", function(e){ //user click on remove text
         if(count >= 1 ) {
				
				var labelremove=$(this).parents('.col-md-12').find('label').html()
				var weightremove=$(this).parents('.col-md-12').find('weight').html()
				var elemtwe=$(this).parents('.col-md-12').attr('elementweight');
				// console.log("%%%%%%%%%%",elemtwe);
				$(this).parents('.col-md-12').remove(); //remove text box
				var whtval=document.getElementById('weight').value;
				elemtwe=parseInt(elemtwe);
				// console.log("%%%%%%%%%%",typeof elemtwe);
				whtval=parseInt(whtval)+elemtwe
				document.getElementById('weight').value = whtval;
				
				if($('.foraddbutton').height()>440)
				{
				var newheight=$('.foraddbutton').height();
				// console.log("hhhhhhhhhhhhhhhhhhhhhhhhhh",newheight)
				newheight=newheight-300;
				// console.log("hhhhhhhhhhhhhhhhhhhhhhhhhh",newheight)
				$('.device').css('min-height',newheight);
				//$('.device').css('background-size',"100% 100%");
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
//alert("ssssssssss");
//header(logo_file_path)
var widgetCnt=0;
var sectionCnt=0;
var no_of_elements="0";

$('.mainpage').children('div').each(function(index, element) 
{
   
   var divid=$(this).attr('id');
   var change= $(this).attr('id');
   var pagenum = change.replace(/\D/g,'');
   var divlen=$(this).children('div').length;
	no_of_elements=parseInt(no_of_elements);
	no_of_elements=no_of_elements+divlen;
	
   tPagenumber(pagenum);

   $(this).children('div').each(function(index, element) {//divfrm1-div1,2,3,4,5
   //no_of_elements+1;
   var diviid=$(this).attr('id');
   var iddd = diviid.replace(/\D/g,'');
   var secnam=$(this).find('label').attr('name');
   // console.log("ggggggggggggggg"+secnam)
   var labelname=$(this).children('label').text();
   // console.log(labelname);
   var inputtype=$(this).find('input').attr('type');
   var checkvl,checkvlky,minn,maxx,textareamin,textareamax,des;
   checkvl=$(this).find('label').attr('chkvalue');
   checkvlky=$(this).find('label').attr('chkkeyvalue');
   minn = $(this).find('label').attr('min');
   maxx = $(this).find('label').attr('max');
   des = $(this).find('label').attr('des');
   var notify_val = $(this).find('label').attr('notify');
   var ins=$(this).find('label').attr('instruct');
   var tagsvalue = $(this).find('label').attr('tags');
   
   		
	if(secnam == 'textarea')
		{
			widgetCnt++;
			tAdd_Txtarea(labelname,minn,maxx,checkvl,"true", des, widgetCnt);//JSON Creation
		}
		else if(secnam == 'wtextarea')
		{
			widgetCnt++;
			tAdd_TxtareaA(labelname,minn,maxx,checkvl, "false", des, widgetCnt);//JSON Creation
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
		tAdd_Select(labelname, optvalue,checkvl, "true", des, widgetCnt);
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
		tAdd_MChoice(labelname,optvalue,checkvl, "true", des, widgetCnt);
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
		tAdd_Radio(labelname,optvalue,checkvl, "true", des, widgetCnt);//JSON Creation
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
		tAdd_Chkbox2(labelname,optvalue,checkvl, "true", des, widgetCnt);//JSON Creation
		}
		else if ((secnam == 'date') || (secnam == 'time') || (secnam == 'color') || (secnam == 'month'))
		{
			widgetCnt++;
		tAdd_Txtbox(labelname,secnam,0,60,checkvl, "true", des, widgetCnt);
		}
		else if(secnam == 'range')
	   	{
			widgetCnt++;
	   	if(typeof minn == 'undefined'){
		minn = 0;
		maxx = 100;
		}
		tAdd_Ranbox(labelname,secnam,minn,maxx,checkvl, "true",des, widgetCnt);//JSON Creation
		}
	   	else if(secnam == 'file')
	   	{
        widgetCnt++;
	   	tAdd_File(labelname,checkvl, "true",des, widgetCnt);//JSON Creation
		}
		if ((secnam == 'text') || (secnam == 'password') || (secnam == 'number'))
		{
			widgetCnt++;
		// console.log("input");
		tAdd_Txtbox(labelname,secnam,minn,maxx,checkvl, "true", des, widgetCnt);//JSON Creation
		}
		if(secnam == 'wtext')
		{
			widgetCnt++;
		// console.log("input");
		var input="text";
		tAdd_TxtboxA(labelname,input,minn,maxx,checkvl, "false", des, widgetCnt);//JSON Creation
		}
		
				
   });
   pageclear();
});//mainpage class .each end

formsubmit()

})


function formsubmit()
{
	//alert("asddsfasdadsf");
	var valuecheck=$('#event_code').val();
	var pagecnt=$('#totaldivcount').val();
	//console.log("pagecnnnnnnnt",pagecnt)
    $('#pagenumber').val(pagecnt);
	
	if(valuecheck!='')
	{
	  $('.tform').submit();
	}
	else
	{
	
	}
}
 

$(document).on('click','a',function(e){

var clickedid = $(this).hasClass("menu-links");
 draft=$(this);
if(clickedid)
{
	$.SmartMessageBox({
				title : "Alert !",
				content : "This Event application is not yet saved. Do you want to continue ?",
				buttons : '[No][Yes]'
			}, function(ButtonPressed) {
				if (ButtonPressed === "Yes") 
				{
					var navigate_to_url = $(draft).attr("href");
					window.location.href = navigate_to_url;
				}
				if (ButtonPressed === "No") {
					
				}
				
	       });
			e.preventDefault();
			
}
})
 $('.divf').sortable({items: '> div:not(.first_sec)'});
});//end on ready