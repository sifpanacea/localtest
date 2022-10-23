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
	document.getElementById('event_code').value = subString;
	

}
//alert("dddddddddddddddddddddddddd");
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
		tprint_tempObj.file_path        = image_src;
		tprint_tempObj.file_description = image_desc;
		tprint_tempObj.file_title       = image_title;
		
		tprint_temp[pagenum] = tprint_tempObj;
		delete tprint_tempObj;
}

//////////////FUNCTION///////////////////////
tAddSec = function(secname,chkkey,des,cnt)
{
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
    var str = "";
    str = str + string;
    var subString = str.substring(1,string.length-1);
    document.getElementById('event_code').value = subString;
    weight = $('#weight').val();
};


//////////////FUNCTION///////////////////////
pageclear = function ()
{
	tDef = {};	
    tJasonSec = {};	
	string = "";
};


//////////////FUNCTION///////////////////////
tAdd_Txtbox = function (label, type, min, max, req, key, des, cnt) {
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
tAdd_Txtarea = function (label, min, max, req, key, des, cnt) {
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
tAdd_Select = function (label, sArray, required, key, des, cnt) {
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
tAdd_MChoice = function (label, sArray, required, key, des, cnt) {
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
tAdd_Radio = function (label, rArray, required, key, des, cnt) {
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
tAdd_Chkbox2 = function (label, chkArray, required, key, des, cnt) {
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
