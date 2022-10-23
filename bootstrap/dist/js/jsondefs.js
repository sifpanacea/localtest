var string = "";
var weight;

if (typeof tJson !== 'object') {
    tJason = {};
}
if (typeof tDef !== 'object') {
    tDef = {};
}
if (typeof tJasonPage !== 'object') {
tJasonPage = {};
}

//////////////FUNCTION///////////////////////
tDefLoad = function (currPage){
	// alert(currPage)
	tDef = tJasonPage[currPage];
	// console.log(tDef);	
}

//////////////FUNCTION///////////////////////
tRem_Element = function (label, currPage) {
$.each(tDef, function (index, value){

if (index == label)
{
   
   delete tDef[index];
  
   
}     
});



////tJasonPage[currPage] = tDef;
//
	
var string = JSON.stringify (tDef);
tPrint(string);
//tDef = {};
if (jQuery.isEmptyObject(tDef)){
	label = document.getElementById('divcount').value;
	delete tJasonPage[label];
	var string = JSON.stringify (tJasonPage);
	var str = "";
	str = str + string;
	var subString = str.substring(1,string.length-1);
	document.getElementById('scaffold_code').value = subString;
	
}

// console.log(tJasonPage);


}

//////////////FUNCTION///////////////////////
tPrint = function (string) {
//var string = JSON.stringify (Obj);


	label = document.getElementById('divcount').value;
	tJasonPage[label] = eval ("(" + string + ")");;
	var string = JSON.stringify (tJasonPage);
	// console.log(tJasonPage);
	

var str = "";
str = str + string;
var subString = str.substring(1,string.length-1);
//alert(subString);
//document.getElementById('controller_name').value = con;
//document.getElementById('model_name').value = mod;
document.getElementById('scaffold_code').value = subString;
weight = $('#weight').val();
// alert(subString);

//if (weight == 0)
//{
//	//alert("Limit over for this page. Add new page to continue.");
//	//var $dvObj = $('#divfrm');
//	//alert($dvObj.eq(0).html());
//	//$dvObj.eq(0).html().appendTo("#page");
//	//$("#divfrm1").hide();
//	//$('<div id="divfrm2" class="divf" align="center"></div>').appendTo('#mainpage');
//	//$("#weight").val("9");
//	//weight = 4;
//	//document.getElementById('weight').value = weight;
//	//document.getElementById('weight').value = 5;
//	
//	//label = document.getElementById('divcount').value;
////	tJasonPage[label] = tDef;
////	
////	var string = JSON.stringify (tJasonPage);
////	tPrint(string);
//	
//	
//	//$("#weight").attr("value","9");
//
//}

}

pageclear = function ()
{
	tDef = {};	
	string = "";
	// console.log(tJasonPage);
}

//////////////FUNCTION///////////////////////
tAdd_Txtbox = function (label, type, min, max, req, key) {
	
if (typeof tTxtObj !== 'object') {
    	tTxtObj= {};
	}
tTxtObj.type          = type;
tTxtObj.minlength     = min;
tTxtObj.maxlength     = max;
tTxtObj.required      = req;
tTxtObj.key			  = key;
tTxtObj.multilanguage = "FALSE";

tDef[label] = tTxtObj;
delete tTxtObj;
var string = JSON.stringify (tDef);
//weight = weight -1;
tPrint(string);
}

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
delete tSecObj;
//tPrint(tDef);
var string = JSON.stringify (tDef);
//weight = weight -1;
tPrint(string);
}

//////////////FUNCTION///////////////////////
tAdd_Txtarea = function (label, min, max, req, key) {
if (typeof tTxtAObj !== 'object') {
    tTxtAObj= {};
}
tTxtAObj.type          = "textarea";
tTxtAObj.minlength     = min;
tTxtAObj.maxlength     = max;
tTxtAObj.required      = req;
tTxtAObj.key		   = key;
tTxtAObj.multilanguage = "FALSE";

tDef[label] = tTxtAObj;
delete tTxtAObj;
//tPrint(tDef);
var string = JSON.stringify (tDef);
//weight = weight -1;
tPrint(string);
}

//////////////FUNCTION///////////////////////
tAdd_Chkbox = function (label, chkArray) {
	
var ii = 0;
if (typeof tChkBArr !== 'object') {
    tChkBArr= {};
}
$.each(chkArray, function (index, value) {
if (typeof tChkBObj !== 'object') {
    tChkBObj= {}; 
}

tChkBObj.type          = "checkbox";
tChkBObj.checked       = chkArray[ii].checked; 
tChkBObj.required      = chkArray[ii].required;
tChkBObj.key		   = chkArray[ii].key;
tChkBObj.label      =    chkArray[ii].label;
//tChkBObj.multilanguage = "FALSE";
tChkBArr[tChkBObj.label] = tChkBObj;
ii++;
delete tChkBObj;
});

tDef[label] = tChkBArr;
tChkBArr= {};
//tPrint(tDef);
var string = JSON.stringify (tDef);
//weight = weight -1;
tPrint(string);
}

//////////////FUNCTION///////////////////////
tAdd_Select = function (label, sArray, required, key) {
var ii = 0;
var opt = "options";

    tDef1 = {};

    tDef2 = {};

    tDef3 = {};

    tOpt= {};
	
if (typeof tSel1Arr !== 'object') {
    tSel1Arr= {};
}
tSel1Arr.type = "select";
tSel1Arr.size= "1";
tSel1Arr.required = required;
tSel1Arr.key= key;
tSel1Arr.option_choose_one = "TRUE";
tSel1Arr.with_translations = "FALSE";

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
//tPrint (tDef);
var string = JSON.stringify (tDef);
//weight = weight -1;
tPrint(string);
}

//////////////FUNCTION///////////////////////
tAdd_MChoice = function (label, sArray, required, key) {
var ii = 0;
var opt = "options";

    tDef1 = {};

    tDef2 = {};

    tDef3 = {};

    tOpt= {};
	
if (typeof tSel1Arr !== 'object') {
    tSel1Arr= {};
}
tSel1Arr.type = "MChoice";
tSel1Arr.size= "1";
tSel1Arr.required = required;
tSel1Arr.key = key;
tSel1Arr.option_choose_one = "FALSE";
tSel1Arr.with_translations = "FALSE";

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
//tPrint (tDef);
var string = JSON.stringify (tDef);
//weight = weight -1;
tPrint(string);
}


//////////////FUNCTION///////////////////////
tAdd_Radio = function (label, rArray, required, key) {
var ii = 0;
var opt = "options";

    tDef1 = {};

    tDef2 = {};

    tDef3 = {};

    tOpt= {};
	
if (typeof tRadioArr !== 'object') {
    tRadioArr= {};
}
tRadioArr.type = "radio";
tRadioArr.required = required;
tRadioArr.checked = "FALSE"; 
tRadioArr.key = key;
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
//tPrint (tDef);
var string = JSON.stringify (tDef);
//weight = weight -1;
tPrint(string);
}

//////////////FUNCTION///////////////////////
tAdd_Chkbox2 = function (label, chkArray, required, key) {
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
tChkBArr.checked = "FALSE"; 
tChkBArr.key    	   = key;
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
//tPrint (tDef);
var string = JSON.stringify (tDef);
//weight = weight -1;
tPrint(string);
}
//////////////FUNCTION///////////////////////
tAdd_Image = function (label, required, key) {
if (typeof tImgArr !== 'object') {
tImgArr = {};
}
tImgArr.type = "image";
tImgArr.required = required;
tImgArr.key = key;
tImgArr.multilanguage = "FALSE";

if (typeof tNestArr !== 'object') {
var tNestArr = {};
}
tNestArr.allowed_types = "gif|jpg|png";
tNestArr.encrypt_name  = "TRUE"; 
tNestArr.max_width    = "2000"; 
tNestArr.max_height    = "1500"; 
tNestArr.max_size      = "2048"; 

tImgArr.upload = tNestArr;
delete tNestArr;

if (typeof tNestArr !== 'object') {
    tNestArr= {};
}
tNestArr.maintain_ratio = "FALSE";
tNestArr.master_dim  = "width"; 
tNestArr.width     = "100"; 
tNestArr.height    = "100"; 

tImgArr.thumbnail = tNestArr;
delete tNestArr;

tDef[label] = tImgArr;
tPrint(tDef);
var string = JSON.stringify (tDef);
//weight = weight -1;
tPrint(string);
}
//////////////FUNCTION///////////////////////
tAdd_File = function (label, required, key) {
if (typeof tImgArr !== 'object') {
    tImgArr= {};
}
tImgArr.type = "radio";
tImgArr.required = required;
tImgArr.key = key;
tImgArr.multilanguage = "FALSE";

if (typeof tNestArr !== 'object') {
    tNestArr= {};
}
tNestArr.allowed_types = "gif|jpg|png";
tNestArr.encrypt_name  = "TRUE"; 
tNestArr.max_size      = "2048"; 

tImgArr.upload = tNestArr;
delete tNestArr;

tDef[label] = tImgArr;
//tPrint(tDef);
var string = JSON.stringify (tDef);
//weight = weight -1;
tPrint(string);
}
