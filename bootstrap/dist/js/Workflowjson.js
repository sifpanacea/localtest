if (typeof tJson !== 'object') {
    tJson = {};
}
if (typeof tDef !== 'object') {
    tDef = {};
}

if (typeof approve !== 'object') {
    approve = {};
}


if (typeof disapprove !== 'object') {
    disapprove = {};
}

if (typeof branch !== 'object') {
    branch = {};
}

if (typeof branches !== 'object') {
    branches = {};
}
if (typeof branching !== 'object') {
    branching = {};
}
if (typeof condition_object !== 'object') {
    condition_object = {};
}

if (typeof current_object !== 'object') {
    current_object = {};
}

function createNestedProperties(obj, array, value) {
    var result = {};
    var result_tmp = obj;
    var i = 0;
    while (i < array.length) {
        if (i < (array.length - 1))
            result_tmp[array[i]] = {};
        else
            result_tmp[array[i]] = value;
        result_tmp = result_tmp[array[i]];
        i++;
    }
    return obj;
}

var condition_history = [];
var current_condition ='';
var current_parallel = '';
//////////////FUNCTION///////////////////////branching device/back
tPrint = function (Obj) {
console.log(Obj);
var string = JSON.stringify (Obj);
// alert(string);
$('#wrkjdata').val(string);
}

//////////////FUNCTION///////////////////////
tAdd_Stage = function (stagename, users, stagetype, epermissions, vpermissions,index,print,sms,groups) {
	console.log("jsonnnn")
if (typeof tStage !== 'object') {
    	tStage= {};
	}
tStage.UsersList	          = users;
tStage.groupList	          = groups;
tStage.Workflow_Type          = "single";
tStage.Stage_Type	          = stagetype;
tStage.Edit_Permissions       = epermissions;
tStage.View_Permissions 	  = vpermissions;
tStage.index				  = index;
tStage.print				  = print;
tStage.sms				      = sms;
tDef[stagename] = tStage;
delete tStage;
tPrint(tDef);
}

tAdd_Stage_api = function (stagename, users, stagetype, comid, vpermissions,index) {
	
if (typeof tStage !== 'object') {
    	tStage= {};
	}
tStage.UsersList	          = users;
tStage.Stage_Type	          = stagetype;
tStage.company_name           = comid;
tStage.View_Permissions 	  = vpermissions;
tStage.index				  = index;
tDef[stagename] = tStage;
delete tStage;
tPrint(tDef);
}
                       //condition name, field, operator, value 
tAdd_Stage_cond = function (conditionname, field, operator, value) {
console.log("condition")
current_condition = conditionname;
if(condition_history.length>1)
{
	var pattern = /^para/
	if(condition_history[condition_history.length-1].match(pattern))
	{
	console.log("truuuuuuuuuuuuuueeeeeeeeeeee")
	condition_history.pop();
	}
}
condition_history.push(current_condition);
console.log("hhhhhhhhhhhhhhhhhiiiiiiiiiiisssssssssssssssssttttttttttoooooorrrrrrrrryyyyyyyyy",condition_history);
if (typeof tStage !== 'object') {
    	tStage= {};
	}
tStage.field	          	  = field;
tStage.operator		          = operator;
tStage.value       			  = value;
tStage.Workflow_Type		  = "conditional";
tStage.approved				  = {};//approve;
tStage.disapproved			  = {};//disapprove;
console.log("llllllllllllllllllllllllllllllllllllllllllllllllllllllllllllll",condition_history.length)
if(condition_history.length < 2)
{
	console.log("iiiiffffffffffffffffffffffffffffffffffffff");
	tDef[current_condition] = tStage;
}
else
{	
	console.log("eeeeeeeeeeeeeeeeeeeeeelllllllllllllllllllll",tDef);
		var i = 0;
		function recursiveIteration(tDef) {
			for (var property in tDef) {
					if (property == condition_history[i])
					{
						i++;
						if(i < condition_history.length-1)
						{					
							recursiveIteration(tDef[property]);
						}
						else
						{
							console.log("hhhhhhhhhhhhhhhhhiiiiiiiiiiisssssssssssssssssttttttttttoooooorrrrrrrrryyyyyyyyy",condition_history);
							tDef[property][current_condition] = tStage;
						}
					}
					else
					{
						console.log("found a property which is not an object, check for your conditions here")
					}
			}
		}
		recursiveIteration(tDef);
}
delete tStage;
tPrint(tDef)
}

tAdd_Stage_approve = function (stagename, users, stagetype, epermissions, vpermissions, sub_index,printval) {
// console.log("approve");	
if (typeof tStage !== 'object') {
    	tStage= {};
	}
tStage.UsersList	          = users;
tStage.Stage_Type	          = stagetype;
tStage.Workflow_Type          = "single";
tStage.Edit_Permissions       = epermissions;
tStage.View_Permissions 	  = vpermissions;
tStage.index				  = sub_index;
tStage.print				  = printval;
console.log("ccccccccccccccccccccooooooooo",current_condition)
//console.log(current_object);


if(condition_history.length < 2)
{
	console.log("iiiiffffffffffffffffffffffffffffffffffffff");
	tDef[current_condition].approved[stagename] = tStage;
}
else
{	
	console.log("eeeeeeeeeeeeeeeeeeeeeelllllllllllllllllllll",tDef);
		var i = 0;
		function recursiveIteration(tDef) {
			for (var property in tDef) {
					if (property == condition_history[i])
					{
						i++;
						if(i < condition_history.length)
						{	
							recursiveIteration(tDef[property]);
						}
						else
						{
							console.log("hhhhhhhhhhhhhhhhhiiiiiiiiiiisssssssssssssssssttttttttttoooooorrrrrrrrryyyyyyyyy",condition_history);
							tDef[property][stagename] = tStage;
						}
					}
			}
		}
		recursiveIteration(tDef);
}
delete tStage;
tPrint(tDef)
}


tAdd_Stage_disapprove = function (stagename, users, stagetype, epermissions, vpermissions,sub_index,printval) {
// console.log("disapprove");
if (typeof tStage !== 'object') {
    	tStage= {};
	}
tStage.UsersList	          = users;
tStage.Stage_Type	          = stagetype;
tStage.Workflow_Type          = "single";
tStage.Edit_Permissions       = epermissions;
tStage.View_Permissions 	  = vpermissions;
tStage.index				  = sub_index;
tStage.print				  = printval;

if(condition_history.length < 2)
{
	console.log("iiiiffffffffffffffffffffffffffffffffffffff");
	tDef[condition_history[0]].disapproved[stagename] = tStage;
}
else
{	
	console.log("eeeeeeeeeeeeeeeeeeeeeelllllllllllllllllllll",tDef);
		var i = 0;
		function recursiveIteration(tDef) 
		{
			for (var property in tDef) 
			{					
					if (property == condition_history[i])
					{
						i++;
						
						if(i < condition_history.length)
						{						
							recursiveIteration(tDef[property]);
						}
						else
						{
							tDef[property][stagename] = tStage;
						}
					}
			}
		}
		recursiveIteration(tDef);
}
delete tStage;
tPrint(tDef)
}

tAdd_Stage_parallel = function (parallelname,branches) {
console.log("111111111111111111111111111111111");
current_parallel = parallelname;
if(condition_history.length>1)
{
	var pattern = /^para/
	if(condition_history[condition_history.length-1].match(pattern))
	{
	console.log("truuuuuuuuuuuuuueeeeeeeeeeee")
	condition_history.pop();
	}
}
condition_history.push(current_parallel);
if (typeof tStage !== 'object') {
    	tStage= {};
	}
// console.log("_________________");
// console.log(branches);
tStage.Workflow_Type		  = "parallel";

for(var i=0;i < branches.length;i++)
{
console.log("iiiiiiiiiiiiiiiiiiiiiiiii",branches[i])
var branch_ = {};
branch_[branches[i]]={}
console.log("bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb",branch_)
$.extend(tStage,branch_);
}
if(condition_history.length < 2)
{
	console.log("iiiiffffffffffffffffffffffffffffffffffffff");
	tDef[current_parallel] = tStage;
}
else
{	
	console.log("eeeeeeeeeeeeeeeeeeeeeelllllllllllllllllllll",tDef);
		var i = 0;
		function recursiveIteration(tDef) {
			for (var property in tDef) {
					if (property == condition_history[i])
					{
						i++;
						if(i < condition_history.length-1)
						{					
							recursiveIteration(tDef[property]);
						}
						else
						{
							console.log("hhhhhhhhhhhhhhhhhiiiiiiiiiiisssssssssssssssssttttttttttoooooorrrrrrrrryyyyyyyyy",condition_history);
							tDef[property][current_parallel] = tStage;
						}
					}
					else
					{
						console.log("found a property which is not an object, check for your conditions here")
					}
			}
		}
		recursiveIteration(tDef);
}
console.log("oooooooooooooooo",tDef)
delete tStage;
tPrint(tDef);
}


tAdd_Stage_branch = function (branchnum) {
console.log("2222222222222222222222222222222222")
// console.log("11111111111111111");
condition_history.push(branchnum);
//console.log("hhhhhhhhhhh",condition_history)
//console.log("hhhhhhhhhhh",condition_history.length)
//if (typeof branchnum !== 'object') {
//    	branchnum = {};
//	}
// console.log("_________________");
// console.log(branches);
//branching[branchnum]=branches;
//$.extend(tDef[current_parallel],branchnum);
console.log("condition_historycondition_historycondition_historycondition_history",condition_history[condition_history.length-1])
//branches={};
//delete branchnum;
//tPrint(tDef);
}

tAdd_Stage_branches = function (stagename, users, stagetype, epermissions, vpermissions,sub_index,printval) {
//console.log("33333333333333333333333333333333333")
// console.log("approve");
//console.log("1111111111111111111",condition_history[condition_history.length-2]);
//console.log("2222222222222222222",condition_history[condition_history.length-1]);
if (typeof tStage !== 'object') {
    	tStage= {};
	}

tStage.UsersList	          = users;
tStage.index				  = sub_index;
tStage.Stage_Type	          = stagetype;
tStage.Edit_Permissions       = epermissions;
tStage.View_Permissions 	  = vpermissions;
tStage.Workflow_Type          = "single";
tStage.print          		  = printval;
//tDef[condition_history[condition_history.length-2]][condition_history[condition_history.length-1]] = stagename;
if(condition_history.length <= 2)
{
	console.log("iiiiffffffffffffffffffffffffffffffffffffff");
	tDef[current_parallel][condition_history[condition_history.length-1]][stagename] = tStage;
}
else
{	
	console.log("eeeeeeeeeeeeeeeeeeeeeelllllllllllllllllllll",tDef);
		var i = 0;
		function recursiveIteration(tDef) {
			for (var property in tDef) {
					if (property == condition_history[i])
					{
						i++;
						console.log("propertypropertypropertypropertyproperty",property)
						if(i < condition_history.length)
						{					
							recursiveIteration(tDef[property]);
						}
						else
						{
							console.log("hhhhhhhhhhhhhhhhhiiiiiiiiiiisssssssssssssssssttttttttttoooooorrrrrrrrryyyyyyyyy",condition_history);
							tDef[property][stagename] = tStage;
						}
					}
					else
					{
						console.log("found a property which is not an object, check for your conditions here")
					}
			}
		}
		recursiveIteration(tDef);
}
//tDef[condition_history[condition_history.length-2]][condition_history[condition_history.length-1]][stagename] = tStage;
//branches[stagename] = tStage;
delete tStage;
// console.log("-------------------");
// console.log(branches);
tPrint(tDef)
}

//tAdd_Stage_parallel = function (parallelname) {
//console.log("11111111111111111111111111111111111");
//if (typeof tStage !== 'object') {
//    	tStage= {};
//	}
//tDef[parallelname]			  = tDef[branchnum];
//branches.workflow_type		  = "parallel";
//delete tStage;
//console.log(tDef);
//tPrint(tDef);
//}
//
//tAdd_Stage_branches = function (branchnum) 
//{
//console.log("2222222222222222222222222222222222");
//if (typeof tStage !== 'object') {
//    	tStage= {};
//}
//tDef[branchnum]			  = branches;
//delete tStage;
//branches={};
//console.log(tDef);
//tPrint(tDef);
//}
//
//tAdd_Stage_branch = function (stagename, users, stagetype, epermissions, vpermissions,branchnum) {
//console.log("3333333333333333333333333333333333333");	
//if (typeof tStage !== 'object') {
//    	tStage= {};
//	}
//tStage.UsersList             = users;
//tStage.Stage_Type	          = stagetype;
//tStage.Edit_Permissions       = epermissions;
//tStage.View_Permissions 	  = vpermissions;
//branch[stagename] = tStage;
//branches[branchnum] = branch;
//console.log(tStage);
//console.log(branch);
//console.log(branches);
//delete tStage;
//branch={};
//}



