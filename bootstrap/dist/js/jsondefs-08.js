var string = "";
var string_temp="";
var string_tempp="";
var notify_tmp="";
var weight,label,desc,count;
var current_section;
var notify_arr=[];
var notify_temp=[];
var string_address ='';

if (typeof tprint_temp !== 'object') {
    tprint_temp = {};
}
/*if (typeof notify_temp !== 'object') {
    notify_temp = {};
}*/
if (typeof tJson !== 'object') {
    tJason = {};
}
if (typeof tDef !== 'object') {
    tDef = {};
}
if (typeof tJasonPage !== 'object') {
tJasonPage = {};
}
if (typeof tJasonSec !== 'object') {
	tJasonSec = {};
	}
	
//////////////FUNCTION///////////////////////
tDefLoad = function (currPage){
	tDef = tJasonPage[currPage];
		
};

//////////////FUNCTION///////////////////////
tRem_Element = function (label, currPage) {
$.each(tDef, function (index, value){

if (index == label)
{
   
   delete tDef[index];
  
   
}     
});
	
var string = JSON.stringify (tDef);
tPrint(string);
if (jQuery.isEmptyObject(tDef)){
	
	label =document.getElementById('divcount').value;
	delete tJasonPage[label];
	var string = JSON.stringify (tJasonPage);
	var str = "";
	str = str + string;
	var subString = str.substring(1,string.length-1);
	document.getElementById('scaffold_code').value = subString;

}
};


//////////////FUNCTION///////////////////////
tPagenumber = function(pagenum)
{
	label=pagenum;
};


//////////////FUNCTION///////////////////////
tPrintTemplate = function (image_title,image_desc,image_src,pagenum)
{
	if (typeof tprint_tempObj !== 'object')
		{
			tprint_tempObj= {};
		}
		var imag = image_src || "";
		var device_file_path = "";
		if (imag!= "")
		{
			var device_path = imag.split("/");
			device_file_path = "./templates/"+device_path[device_path.length-1]+"";
		}
		tprint_tempObj.file_path        = imag;
		tprint_tempObj.device_file_path = device_file_path;
		tprint_tempObj.file_description = image_desc;
		tprint_tempObj.file_title       = image_title;
		
		tprint_temp[pagenum] = tprint_tempObj;
		console.log(tprint_temp)
		delete tprint_tempObj;
}

//company header....//////////////////////////
function header(logo_file_path)
{
if (typeof header_tempp !== 'object')
		{
			header_tempp= {};
		}
		var company = $('.companyname').text();
		$('.deviceheader').children('.address').each(function()
		{	
			var id = $(this).attr('id')
			if(id == "address0")
			{
				var address_line = $(this).text();
				string_address=string_address.concat(address_line);
			}
			else
			{
				var address_line = $(this).text();
				string_address=string_address.concat(",",address_line);				
			}
		})
		header_tempp.companyname  = company;
		header_tempp.address      = string_address;
		header_tempp.logo         = logo_file_path;
		
		var header_temp = {};
		header_temp['header_details'] = header_tempp;
		var notify_arrr = JSON.stringify (header_temp);
		document.getElementById('header_values').value = notify_arrr;
}
//////////////FUNCTION///////////////////////
notify_fields = function (name,page_num)
{
		//console.log(page_num,current_section,name);
        //var notify_value = 'page'+page_num+'.'+current_section+'.'+name+'';
		//notify_arr.push(notify_value);
		//console.log(notify_arr);
		//var notify_arrr = JSON.stringify (notify_arr);
		//document.getElementById('notify_values').value = notify_arrr;
		
		if (typeof notify_tempp !== 'object')
		{
			notify_tempp= {};
		}
		notify_tempp.field    = name;
		notify_tempp.page    = page_num;
		notify_tempp.section = current_section;
		
		//notify_temp[name] = notify_tempp;
		notify_temp.push(notify_tempp);
		//console.log(notify_temp);
		var notify_arrr = JSON.stringify (notify_temp);
		document.getElementById('notify_values').value = notify_arrr;
		delete notify_tempp;
		
}

//////////////FUNCTION///////////////////////
tAddSec = function(secname,chkkey,des,cnt)
{
	//tJasonSec = {};	
	count = "";
	labelsec = "";
	labelsec=secname;
	desc=des;
	count=cnt;
	tSecPropObj= {};
	current_section = secname;
	
	if (typeof tSecObj !== 'object') {
    	tSecObj= {};
	}
	tSecObj.type          = "SBreak";
	tSecObj.key			  = "TRUE";
	tSecObj.description   = desc;
	tSecObj.multilanguage = "FALSE";
	tSecObj.order         = count;
	
	if (typeof tSecPropObj !== 'object') {
		tSecPropObj= {};
	}

	tSecPropObj[labelsec] = {};
	tSecPropObj[labelsec]['dont_use_this_name'] = tSecObj;
	tDef = {};
	tSecObj = {};
};


//////////////FUNCTION///////////////////////
tPrint = function (string) {

	tJasonSec[labelsec] = eval ("(" + string + ")");
	$.extend(tJasonSec[labelsec],tSecPropObj[labelsec]);
	tJasonPage[label] = tJasonSec;
	var string = JSON.stringify (tJasonPage);
	console.log(string)
    var str = "";
    str = str + string;
	console.log(str)
    var subString = str.substring(1,string.length-1);
	console.log(subString)
    document.getElementById('scaffold_code').value = subString;
    weight = $('#weight').val();
	
    string_temp=JSON.stringify(tprint_temp);
	console.log(tprint_temp)
    var str_temp = "";
    str_temp = str_temp + string_temp;
    var subString_temp = str_temp.substring(1,string_temp.length-1);
    document.getElementById('print').value = subString_temp;
	
	/*string_tempp=JSON.stringify(notify_temp);
	console.log(notify_temp);
    var str_tempp = "";
    str_tempp = str_tempp + string_tempp;
    var subString_tempp = str_tempp.substring(1,string_tempp.length-1);
    document.getElementById('notify_values').value = subString_tempp;*/

};


//////////////FUNCTION///////////////////////
pageclear = function ()
{
	tDef = {};	
    tJasonSec = {};	
	string = "";
};


//////////////FUNCTION///////////////////////
tAdd_Txtbox = function (label, type, min, max, req, key, des, cnt,notify_value) {
if (typeof tTxtObj !== 'object') {
    	tTxtObj= {};
	}
tTxtObj.type          = type;
tTxtObj.minlength     = min;
tTxtObj.maxlength     = max;
tTxtObj.required      = req;
tTxtObj.key			  = "TRUE";
tTxtObj.description   = des;
tTxtObj.multilanguage = "FALSE";
tTxtObj.order         = cnt;
tTxtObj.notify        = notify_value;

tDef[label] = tTxtObj;
delete tTxtObj;
var string = JSON.stringify (tDef);
tPrint(string);
};


/*
 * Retriever - Typable
 */
tAdd_retriever = function (label, type, cnt,coll_ref,field_prop,retrieve_list,field_ref) {
if (typeof tTxtObj !== 'object') {
    	tTxtObj= {};
	}
tTxtObj.type             	= type;
tTxtObj.order 			 	= cnt;
tTxtObj.coll_ref         	= coll_ref;
tTxtObj.field_ref        	= field_ref;
tTxtObj.properties       	= field_prop;
tTxtObj.properties.order 	= cnt;
tTxtObj.properties.parent   = "retriever";
tTxtObj.retrieve_list    	= retrieve_list;

tDef[label] = tTxtObj;
delete tTxtObj;
var string = JSON.stringify (tDef);
tPrint(string);
};

//mapper
tAdd_mapper = function (label, type,cnt,field_prop,field_ref,coll_ref) {
if (typeof tTxtObj !== 'object') {
    	tTxtObj= {};
	}
tTxtObj.type          	 	= type;
tTxtObj.coll_ref          	= coll_ref;
tTxtObj.order 			 	= cnt;
tTxtObj.field_ref     	 	= field_ref;
tTxtObj.properties    	 	= field_prop;
tTxtObj.properties.order 	= cnt;
tTxtObj.properties.parent   = "mapper";

tDef[label] = tTxtObj;
delete tTxtObj;
var string = JSON.stringify (tDef);
tPrint(string);
};


//////////////FUNCTION///////////////////////
tAdd_date = function (label, type, req, key, des, cnt,notify_value) {
if (typeof tTxtObj !== 'object') {
    	tTxtObj= {};
	}
tTxtObj.type          = type;
tTxtObj.required      = req;
tTxtObj.key			  = "TRUE";
tTxtObj.description   = des;
tTxtObj.multilanguage = "FALSE";
tTxtObj.order         = cnt;
tTxtObj.notify        = notify_value;

tDef[label] = tTxtObj;
delete tTxtObj;
var string = JSON.stringify (tDef);
tPrint(string);
};


//////////////FUNCTION///////////////////////
tAdd_newline = function (cnt) {
if (typeof tTxtObjj !== 'object') {
    	tTxtObjj= {};
	}
tTxtObjj.type          = "newline";
tTxtObjj.key		   = "FALSE";
tTxtObjj.multilanguage = "FALSE";
tTxtObjj.order         = cnt;

tDef['newline'+cnt+''] = tTxtObjj;
delete tTxtObjj;
var string = JSON.stringify (tDef);
tPrint(string);
};


//////////////FUNCTION///////////////////////
tAdd_TxtboxA = function (label, type, min, max, req, key, des, cnt) {

if (typeof tTxtObjA !== 'object') {
    	tTxtObjA= {};
	}
tTxtObjA.type          = type;
tTxtObjA.minlength     = min;
tTxtObjA.maxlength     = max;
tTxtObjA.required      = req;
tTxtObjA.key		   = "FALSE";
tTxtObjA.description   = des;
tTxtObjA.multilanguage = "FALSE";
tTxtObjA.order         = cnt;

tDef[label] = tTxtObjA;
delete tTxtObjA;
var string = JSON.stringify (tDef);
tPrint(string);
};



//////////////FUNCTION///////////////////////
tAdd_Instruction = function(labelname,checkvl, checkvlky, ins, des, cnt) {
if (typeof tInsObj !== 'object') {
    	tInsObj= {};
	}
tInsObj.type          = "instruction";
tInsObj.required      = checkvl;
tInsObj.key			  = "TRUE";
tInsObj.instructions  = ins;
tInsObj.description   = des;
tInsObj.multilanguage = "FALSE";
tInsObj.order         = cnt;

tDef[labelname] = tInsObj;
delete tInsObj;
var string = JSON.stringify (tDef);
tPrint(string);
};



//////////////FUNCTION///////////////////////
tAdd_Ranbox = function (label, type, min, max, req, key,des, cnt) {
if (typeof tTxtObj !== 'object') {
    	tTxtObj= {};
	}
tTxtObj.type           = type;
tTxtObj.minrange       = min;
tTxtObj.maxrange       = max;
tTxtObj.required       = req;
tTxtObj.key			   = "TRUE";
tTxtObj.description	   = des;
tTxtObj.multilanguage  = "FALSE";
tTxtObj.order          = cnt;

tDef[label] = tTxtObj;
delete tTxtObj;
var string = JSON.stringify (tDef);
tPrint(string);
};

//////////////FUNCTION///////////////////////
tAdd_Section = function (label, type, min, max, req, key) {
	
if (typeof tSecObj !== 'object') {
    	tSecObj= {};
	}
tSecObj.type          = type;
tSecObj.minlength     = min;
tSecObj.maxlength     = max;
tSecObj.required      = req;
tSecObj.key			  = "TRUE";
tSecObj.multilanguage = "FALSE";

tDef[label] = tSecObj;
var string = JSON.stringify (tDef);
tPrint(string);
};


//////////////FUNCTION///////////////////////
tAdd_Txtarea = function (label, min, max, req, key, des, cnt, notify_value) {
if (typeof tTxtAObj !== 'object') {
    tTxtAObj= {};
}
tTxtAObj.type          = "textarea";
tTxtAObj.minlength     = min;
tTxtAObj.maxlength     = max;
tTxtAObj.required      = req;
tTxtAObj.key		   = "TRUE";
tTxtAObj.description   = des;
tTxtAObj.multilanguage = "FALSE";
tTxtAObj.order         = cnt;
tTxtAObj.notify     = notify_value;

tDef[label] = tTxtAObj;
delete tTxtAObj;
var string = JSON.stringify (tDef);
tPrint(string);
}


//////////////FUNCTION///////////////////////
tAdd_TxtareaA = function (label, min, max, req, key, des, cnt) {
if (typeof tTxtAObjA !== 'object') {
    tTxtAObjA= {};
}
tTxtAObjA.type          = "textarea";
tTxtAObjA.minlength     = min;
tTxtAObjA.maxlength     = max;
tTxtAObjA.required      = req;
tTxtAObjA.key		    = "FALSE";
tTxtAObjA.description   = des;
tTxtAObjA.multilanguage = "FALSE";
tTxtAObjA.order         = cnt;

tDef[label] = tTxtAObjA;
delete tTxtAObjA;
var string = JSON.stringify (tDef);
tPrint(string);
}



//////////////FUNCTION/////////////////////// JSON creation for Image Field
tAdd_imageField = function (label, req, des, cnt, page, section, sameasfield, cloned) {
if (typeof tTxtAObjA !== 'object') {
    tTxtAObjA= {};
}
tTxtAObjA.type          = "imageElem";
tTxtAObjA.page          = page;
tTxtAObjA.section       = section;
tTxtAObjA.sameasfield   = sameasfield;
tTxtAObjA.cloned      	= cloned;
tTxtAObjA.required      = req;
tTxtAObjA.key		    = "FALSE";
tTxtAObjA.description   = des;
tTxtAObjA.multilanguage = "FALSE";
tTxtAObjA.order         = cnt;

tDef[label] = tTxtAObjA;
delete tTxtAObjA;
var string = JSON.stringify (tDef);
tPrint(string);
}


//////////////FUNCTION///////////////////////
tAdd_Txtareab = function (label, min, max, req, key, des, cnt) {
if (typeof tTxtAObjA !== 'object') {
    tTxtAObjA= {};
}
tTxtAObjA.type          = "block";
tTxtAObjA.minlength     = min;
tTxtAObjA.maxlength     = max;
tTxtAObjA.required      = req;
tTxtAObjA.key		    = "FALSE";
tTxtAObjA.description   = des;
tTxtAObjA.multilanguage = "FALSE";
tTxtAObjA.order         = cnt;

tDef[label] = tTxtAObjA;
delete tTxtAObjA;
var string = JSON.stringify (tDef);
tPrint(string);
}

//////////////FUNCTION///////////////////////
tAdd_Txtareamb = function (label, min, max, req, key, des, cnt) {
	console.log("mmmmmmmmmmmmbbbbbbbbbbbbbb")
if (typeof tTxtAObjA !== 'object') {
    tTxtAObjA= {};
}
tTxtAObjA.type          = "multiblock";
tTxtAObjA.minlength     = min;
tTxtAObjA.maxlength     = max;
tTxtAObjA.required      = req;
tTxtAObjA.key		    = "FALSE";
tTxtAObjA.description   = des;
tTxtAObjA.multilanguage = "FALSE";
tTxtAObjA.order         = cnt;

tDef[label] = tTxtAObjA;
delete tTxtAObjA;
var string = JSON.stringify (tDef);
tPrint(string);
}

//////////////FUNCTION///////////////////////
tAdd_Chkbox = function (label, chkArray, des,cnt) {
	
var ii = 0;
if (typeof tChkBArr !== 'object') {
    tChkBArr= {};
}
$.each(chkArray, function (index, value) {
if (typeof tChkBObj !== 'object') {
    tChkBObj= {}; 
}

tChkBObj.type            = "checkbox";
tChkBObj.checked         = chkArray[ii].checked; 
tChkBObj.required        = chkArray[ii].required;
tChkBObj.key		     = "TRUE";
tChkBObj.description     = des;
tChkBObj.label           = chkArray[ii].label;
tChkBObj.order           = cnt;
tChkBObj.multilanguage = "FALSE";

tChkBArr[tChkBObj.label] = tChkBObj;
ii++;
delete tChkBObj;
});
tDef[label] = tChkBArr;
tChkBArr= {};
var string = JSON.stringify (tDef);
tPrint(string);
}


//////////////FUNCTION///////////////////////
tAdd_Select = function (label, sArray, required, key, des, cnt,notify_value) {
var ii = 0;
var opt = "options";

    tDef1 = {};

    tDef2 = {};

    tDef3 = {};

    tOpt= {};
	
if (typeof tSel1Arr !== 'object') {
    tSel1Arr= {};
}
tSel1Arr.type              = "select";
tSel1Arr.size              = "1";
tSel1Arr.required          = required;
tSel1Arr.key               = "TRUE";
tSel1Arr.description       = des;
tSel1Arr.option_choose_one = "TRUE";
tSel1Arr.with_translations = "FALSE";
tSel1Arr.order             = cnt;
tSel1Arr.multilanguage     = "FALSE";
tSel1Arr.notify     = notify_value;

tDef1[label] = tSel1Arr;
tSel1Arr= {};
var tAddSel1 = JSON.stringify (tDef1);
var tAddSel12 = tAddSel1.substring(2,tAddSel1.length-2);
var start = tAddSel12.search('"');
var tAddSel13 = tAddSel12.substring(start+3,tAddSel12.length);
tAddSel13 = tAddSel13+",";

$.each(sArray, function (index, value) {
if (typeof tSelObj !== 'object') {
    tSelBObj= {};
}
tSelBObj.text           = sArray[ii].text; 
if (ii == 0) {
tSelBObj.selected       = "TRUE";
} else {
tSelBObj.selected       = "FALSE";
}
tSelBObj.value          = sArray[ii].value; 
tOpt[ii] = tSelBObj;
ii++;
delete tSelBObj;
});

tDef2[opt] = tOpt;
tOpt= {};
var tAddSel2 = JSON.stringify (tDef2);
var tAddSel21 = tAddSel2.substring(1,tAddSel2.length-1);
var final1 = tAddSel13+tAddSel21;
tDeff3 = eval ( "(" + "{" + final1 + "}" + ")" );

tDef[label] = tDeff3;
var string = JSON.stringify (tDef);
tPrint(string);
}


//////////////FUNCTION///////////////////////
tAdd_MChoice = function (label, sArray, required, key, des, cnt,notify_value) {
var ii = 0;
var opt = "options";

    tDef1 = {};

    tDef2 = {};

    tDef3 = {};

    tOpt= {};
	
if (typeof tSel1Arr !== 'object') {
    tSel1Arr= {};
}
tSel1Arr.type              = "MChoice";
tSel1Arr.size              = "1";
tSel1Arr.required          = required;
tSel1Arr.key               = "TRUE";
tSel1Arr.description       = des;
tSel1Arr.option_choose_one = "FALSE";
tSel1Arr.with_translations = "FALSE";
tSel1Arr.order             = cnt;
tSel1Arr.multilanguage     = "FALSE";
tSel1Arr.notify     	   = notify_value ;

tDef1[label] = tSel1Arr;
tSel1Arr= {};
var tAddSel1 = JSON.stringify (tDef1);
var tAddSel12 = tAddSel1.substring(2,tAddSel1.length-2);
var start = tAddSel12.search('"');
var tAddSel13 = tAddSel12.substring(start+3,tAddSel12.length);
tAddSel13 = tAddSel13+",";

$.each(sArray, function (index, value) {
if (typeof tSelObj !== 'object') {
    tSelBObj= {};
}
tSelBObj.text           = sArray[ii].text; 
if (ii == 0) {
tSelBObj.selected       = "TRUE";
} else {
tSelBObj.selected       = "FALSE";
}
tSelBObj.value          = sArray[ii].value; 
tOpt[ii] = tSelBObj;
ii++;
delete tSelBObj;
});

tDef2[opt] = tOpt;
tOpt= {};
var tAddSel2 = JSON.stringify (tDef2);
var tAddSel21 = tAddSel2.substring(1,tAddSel2.length-1);
var final1 = tAddSel13+tAddSel21;
tDeff3 = eval ( "(" + "{" + final1 + "}" + ")" );

tDef[label] = tDeff3;
var string = JSON.stringify (tDef);
tPrint(string);
}


//////////////FUNCTION///////////////////////
tAdd_Radio = function (label, rArray, required, key, des, cnt,notify_value) {
var ii = 0;
var opt = "options";

    tDef1 = {};

    tDef2 = {};

    tDef3 = {};

    tOpt= {};
	
if (typeof tRadioArr !== 'object') {
    tRadioArr= {};
}
tRadioArr.type          = "radio";
tRadioArr.required      = required;
tRadioArr.key           = "TRUE";
tRadioArr.description   = des;
tRadioArr.order         = cnt;
tRadioArr.multilanguage = "FALSE";
tRadioArr.notify = notify_value;

tDef1[label] = tRadioArr;
tRadioArr= {};
var tRadioArr = JSON.stringify (tDef1);
var tRadio1 = tRadioArr.substring(2,tRadioArr.length-2);

var start = tRadio1.search('"');
var tRadio12 = tRadio1.substring(start+3,tRadio1.length);
tRadio12 = tRadio12+",";

$.each(rArray, function (index, value) {
if (typeof tRadioObj !== 'object') {
    tRadioObj= {};
}
tRadioObj.label         = rArray[ii].label; 

tRadioObj.value          = rArray[ii].value; 

tOpt[ii] = tRadioObj;
ii++;
delete tRadioObj;
});

tDef2[opt] = tOpt;
tOpt= {};
var tRadio2 = JSON.stringify (tDef2);
var tRadio21 = tRadio2.substring(1,tRadio2.length-1);
var final1 = tRadio12+tRadio21;
tDeff3 = eval ( "(" + "{" + final1 + "}" + ")" );

tDef[label] = tDeff3;
var string = JSON.stringify (tDef);
tPrint(string);
}

//////////////FUNCTION///////////////////////
tAdd_Chkbox2 = function (label, chkArray, required, key, des, cnt, notify_value) {
var ii = 0;
var opt = "options";

    tDef1 = {};

    tDef2 = {};

    tDef3 = {};

    tOpt= {};

if (typeof tChkBArr !== 'object') {
    tChkBArr= {};
}
tChkBArr.type          = "checkbox";
tChkBArr.required      = required;
tChkBArr.key    	   = "TRUE";
tChkBArr.description   = des;
tChkBArr.order         = cnt;
tChkBArr.multilanguage = "FALSE";
tChkBArr.notify = notify_value;

tDef1[label] = tChkBArr;
tChkBArr= {};
var tChkBArr = JSON.stringify (tDef1);
var tChkB1 = tChkBArr.substring(2,tChkBArr.length-2);

var start = tChkB1.search('"');
var tChkB12 = tChkB1.substring(start+3,tChkB1.length);
tChkB12 = tChkB12+",";

$.each(chkArray, function (index, value) {
if (typeof tChkBObj !== 'object') {
    tChkBObj= {}; 
}
tChkBObj.label      =    chkArray[ii].label;
tChkBObj.value      =    chkArray[ii].value;
//tChkBObj.multilanguage = "FALSE";
tOpt[ii] = tChkBObj;
ii++;
delete tChkBObj;
});

tDef2[opt] = tOpt;
tOpt= {};
var tChkB2 = JSON.stringify (tDef2);
var tChkB21 = tChkB2.substring(1,tChkB2.length-1);
var final1 = tChkB12+tChkB21;
tDeff3 = eval ( "(" + "{" + final1 + "}" + ")" );
tDef[label] = tDeff3;
var string = JSON.stringify (tDef);
tPrint(string);
}


//////////////FUNCTION///////////////////////
tAdd_Image = function (label, required, key) {
if (typeof tImgArr !== 'object') {
tImgArr = {};
}
tImgArr.type          = "image";
tImgArr.required      = required;
tImgArr.key           = "TRUE";
tImgArr.multilanguage = "FALSE";

if (typeof tNestArr !== 'object') {
var tNestArr = {};
}
tNestArr.allowed_types = "gif|jpg|png";
tNestArr.encrypt_name  = "TRUE"; 
tNestArr.max_width     = "2000"; 
tNestArr.max_height    = "1500"; 
tNestArr.max_size      = "2048"; 

tImgArr.upload = tNestArr;
delete tNestArr;

if (typeof tNestArr !== 'object') {
    tNestArr= {};
}
tNestArr.maintain_ratio = "FALSE";
tNestArr.master_dim     = "width"; 
tNestArr.width          = "100"; 
tNestArr.height         = "100"; 

tImgArr.thumbnail = tNestArr;
delete tNestArr;

tDef[label] = tImgArr;
tPrint(tDef);
var string = JSON.stringify (tDef);
tPrint(string);
}


//////////////FUNCTION///////////////////////
tAdd_File = function (label, required, key,des, cnt) {
if (typeof tImgArr !== 'object') {
    tImgArr= {};
}
tImgArr.type          = "file";
tImgArr.required      = required;
tImgArr.key           = "TRUE";
tImgArr.description   = des;
tImgArr.multilanguage = "FALSE";
tImgArr.order = cnt;
if (typeof tNestArr !== 'object') {
    tNestArr= {};
}
tNestArr.allowed_types = "*";
tNestArr.encrypt_name  = "TRUE"; 
tNestArr.max_size      = "2048"; 
tImgArr.upload = tNestArr;
delete tNestArr;
tDef[label] = tImgArr;
delete tImgArr;
var string = JSON.stringify (tDef);
tPrint(string);
}

tAdd_photo = function (label, required, key,des, cnt,min,max) {
if (typeof tImgArr !== 'object') {
    tImgArr= {};
}
tImgArr.type          = "photo";
tImgArr.required      = required;
tImgArr.key           = "TRUE";
tImgArr.description   = des;
tImgArr.multilanguage = "FALSE";
tImgArr.order = cnt;
if (typeof tNestArr !== 'object') {
    tNestArr= {};
}
tNestArr.allowed_types = "*";
tNestArr.encrypt_name  = "TRUE"; 
tNestArr.max_size      = max; 
tNestArr.min_size      = min; 
tImgArr.upload = tNestArr;
delete tNestArr;
tDef[label] = tImgArr;
delete tImgArr;
var string = JSON.stringify (tDef);
tPrint(string);
}
