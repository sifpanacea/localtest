$(document).ready(function() {
var count=0;
//var $popover = $('[rel=popover]').popover();
var stagelabel,tempcount,btnwidth;
var flagval=0;
var usersss = [];
var userss = [];
var users=[];
var sections = [],sectionss = [];
var sectionnames;
var groupname=[];
var grup=[];
var grupss=[];
var groupnamess=new Array();
var workflowstagetype = ['web','device','API'];
var grouplist={};
var userlist=[];
var apiuserlist=[];
var seclist=[];
var app={};
var companylist={};
var apicompany=[];
var checkempty=0;
var draftworkflow;
var app_con = $('#app_con').val();
var app_mod = $('#app_mod').val();
var app_name = $('#app_name').val();
var app_des = $('#app_des').val();
var app_type = $('#app_type').val();
var comp_name = $('#comp_name').val();
var comp_addr = $('#comp_addr').val();
var workflow_mode = $('#workflow_mode').val();
var draft_trigger = "false";

$('.clickline').popover({
		html:true,
		trigger:'hover',
		content:'Click On The Line to Add Stage'

})//end of popover

$.ajax({
	url: 'get_group_list',
	type: 'POST',
	async:false,
	dataType:"json",
	success: function (data) {
		groupnamess=data;
	
	for(var i in groupnamess)
	{
	 grup[i]=groupnamess[i];
	 userss.push({
            label:groupnamess[i],
            value:groupnamess[i]
   		  });
	grupss.push(groupnamess[i]);
	}
	userss.push({
            label:"PANACEA Admin",
            value:"PANACEA Admin"
   		  });
		  
	userss.push({
            label:"PANACEA CC",
            value:"PANACEA CC"
   		  });
	userss.push({
            label:"PANACEA Health Supervisors",
            value:"PANACEA Health Supervisors"
   		  });
	userss.push({
            label:"PANACEA Doctors",
            value:"PANACEA Doctors"
   		  });
	userss.push({
            label:"TTWREIS Admin",
            value:"TTWREIS Admin"
   		  });
		  
	userss.push({
            label:"TTWREIS CC",
            value:"TTWREIS CC"
   		  });
	userss.push({
            label:"TTWREIS Health Supervisors",
            value:"TTWREIS Health Supervisors"
   		  });
	userss.push({
            label:"TTWREIS Doctors",
            value:"TTWREIS Doctors"
   		  });
		  
	userss.push({
            label:"TMREIS Admin",
            value:"TMREIS Admin"
   		  });
		  
	userss.push({
            label:"TMREIS CC",
            value:"TMREIS CC"
   		  });
	userss.push({
            label:"TMREIS Health Supervisors",
            value:"TMREIS Health Supervisors"
   		  });
	userss.push({
            label:"TMREIS Doctors",
            value:"TMREIS Doctors"
   		  });
		  
	
		  
	
		  
	for(var i in grupss)

	{
		 $.ajax({
			url: 'get_user_list',
			type: 'POST',
			async:false,
			dataType:"json",
			data:{'name':groupnamess[i]},	
  			success: function (data) {
  				console.log("55555555555555555555"+data);
				if(data!='[]')
				{
					var group=groupnamess[i];
					
					grouplist[group]=new Array();
					
					var usrs=data;
					for(var j in usrs)
					{
						grouplist[group].push(usrs[j]);
						
					}
					
															
				}
			},
    		error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 	console.log('error', errorThrown);
    		}
		});	
		
	}	

	users=userss;
	
	},
    error:function(XMLHttpRequest, textStatus, errorThrown)
	{
	 console.log('error', errorThrown);
    }
});

// Health Supervisors
$.ajax({
			url: 'get_panacea_hs_list',
			type: 'POST',
			async:false,
			dataType:"json",
  			success: function (data) {
				if(data!='[]')
				{
					var group="PANACEA Health Supervisors";
					
					grouplist[group]=new Array();
					
					var usrs=data;
					for(var j in usrs)
					{
						grouplist[group].push(usrs[j]);
						
					}
				}
			},
    		error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 	console.log('error', errorThrown);
    		}
})

// PANACEA Doctors
$.ajax({
		url: 'get_panacea_doctors_list',
		type: 'POST',
		async:false,
		dataType:"json",
  		success: function (data) {
			if(data!='[]')
			{
				var group="PANACEA Doctors";
				
				grouplist[group]=new Array();
				
				var usrs=data;
				for(var j in usrs)
				{
					grouplist[group].push(usrs[j]);
					
				}
			}
			},
    		error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 	console.log('error', errorThrown);
    		}
})



// PANACEA Admin
$.ajax({
		url: 'get_panacea_admin_list',
		type: 'POST',
		async:false,
		dataType:"json",
  		success: function (data) {
			if(data!='[]')
			{
				var group="PANACEA Admin";
				
				grouplist[group]=new Array();
				
				var usrs=data;
				for(var j in usrs)
				{
					grouplist[group].push(usrs[j]);
					
				}
			}
			},
    		error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 	console.log('error', errorThrown);
    		}
})

// PANACEA Admin
$.ajax({
		url: 'get_panacea_cc_list',
		type: 'POST',
		async:false,
		dataType:"json",
  		success: function (data) {
			if(data!='[]')
			{
				var group="PANACEA CC";
				
				grouplist[group]=new Array();
				
				var usrs=data;
				for(var j in usrs)
				{
					grouplist[group].push(usrs[j]);
					
				}
			}
			},
    		error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 	console.log('error', errorThrown);
    		}
})

// TTWREIS Admin
$.ajax({
		url: 'get_ttwreis_admin_list',
		type: 'POST',
		async:false,
		dataType:"json",
  		success: function (data) {
			if(data!='[]')
			{
				var group="TTWREIS Admin";
				
				grouplist[group]=new Array();
				
				var usrs=data;
				for(var j in usrs)
				{
					grouplist[group].push(usrs[j]);
					
				}
			}
			},
    		error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 	console.log('error', errorThrown);
    		}
})

// TTWREIS Admin
$.ajax({
		url: 'get_ttwreis_cc_list',
		type: 'POST',
		async:false,
		dataType:"json",
  		success: function (data) {
			if(data!='[]')
			{
				var group="TTWREIS CC";
				
				grouplist[group]=new Array();
				
				var usrs=data;
				for(var j in usrs)
				{
					grouplist[group].push(usrs[j]);
					
				}
			}
			},
    		error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 	console.log('error', errorThrown);
    		}
})

// Health Supervisors
$.ajax({
			url: 'get_ttwreis_hs_list',
			type: 'POST',
			async:false,
			dataType:"json",
  			success: function (data) {
			console.log("TTWREIS==HS===",data);
				if(data!='[]')
				{
					var group="TTWREIS Health Supervisors";
					
					grouplist[group]=new Array();
					
					var usrs=data;
					for(var j in usrs)
					{
						grouplist[group].push(usrs[j]);
						
					}
				}
			},
    		error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 	console.log('error', errorThrown);
    		}
})

// TTWREIS Doctors
$.ajax({
		url: 'get_ttwreis_doctors_list',
		type: 'POST',
		async:false,
		dataType:"json",
  		success: function (data) {
			if(data!='[]')
			{
				var group="TTWREIS Doctors";
				
				grouplist[group]=new Array();
				
				var usrs=data;
				for(var j in usrs)
				{
					grouplist[group].push(usrs[j]);
					
				}
			}
			},
    		error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 	console.log('error', errorThrown);
    		}
})

// tmreis Admin
$.ajax({
		url: 'get_tmreis_admin_list',
		type: 'POST',
		async:false,
		dataType:"json",
  		success: function (data) {
			if(data!='[]')
			{
				var group="TMREIS Admin";
				
				grouplist[group]=new Array();
				
				var usrs=data;
				for(var j in usrs)
				{
					grouplist[group].push(usrs[j]);
					
				}
			}
			},
    		error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 	console.log('error', errorThrown);
    		}
})

// tmreis cc
$.ajax({
		url: 'get_tmreis_cc_list',
		type: 'POST',
		async:false,
		dataType:"json",
  		success: function (data) {
			if(data!='[]')
			{
				var group="TMREIS CC";
				
				grouplist[group]=new Array();
				
				var usrs=data;
				for(var j in usrs)
				{
					grouplist[group].push(usrs[j]);
					
				}
			}
			},
    		error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 	console.log('error', errorThrown);
    		}
})

// Health Supervisors
$.ajax({
			url: 'get_tmreis_hs_list',
			type: 'POST',
			async:false,
			dataType:"json",
  			success: function (data) {
			//console.log("TTWREIS==HS===",data);
				if(data!='[]')
				{
					var group="TMREIS Health Supervisors";
					
					grouplist[group]=new Array();
					
					var usrs=data;
					for(var j in usrs)
					{
						grouplist[group].push(usrs[j]);
						
					}
				}
			},
    		error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 	console.log('error', errorThrown);
    		}
})

// TMREIS Doctors
$.ajax({
		url: 'get_tmreis_doctors_list',
		type: 'POST',
		async:false,
		dataType:"json",
  		success: function (data) {
			if(data!='[]')
			{
				var group="TMREIS Doctors";
				
				grouplist[group]=new Array();
				
				var usrs=data;
				for(var j in usrs)
				{
					grouplist[group].push(usrs[j]);
					
				}
			}
			},
    		error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 	console.log('error', errorThrown);
    		}
})
		
//get_api_details
$.ajax({
	url: 'get_api_details',
	type: 'POST',
	async:false,
	dataType:"json",
	success: function (data) {
		var apii=[];
		apii=data;
		
	 if(apii!=null)
	 {
		for(var ll in apii)
		{
				var colname=apii[ll].collection;
				var col=apii[ll]._id.$id;
				apicompany.push({
					label:colname,
					value:col
				});
		}
		console.log(apicompany);
		for(var lll in apii)
		{
			var collname=apii[lll]._id.$id;
			var coll=apii[lll].collection;
		
		 $.ajax({
			url: 'get_api_users',
			type: 'POST',
			async:false,
			dataType:"json",
			data:{'colname':coll},	
  			success: function (apiusrlist) {
				console.log(apiusrlist);
				if(apiusrlist!='[]')
				{
					var apinames=collname;
					companylist[apinames]=new Array();
					var apiusrs=apiusrlist;
					for(var apij in apiusrs)
					{
						companylist[apinames].push(apiusrs[apij].email);
						
					}
								
				}
				console.log(companylist)
			},
    		error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 	console.log('error', errorThrown);
    		}
		});	
		
		}//2nd for ajax
	 }
	// console.log('successddddddd');
	},
    error:function(XMLHttpRequest, textStatus, errorThrown)
	{
	 console.log('error', errorThrown);
    }
});

$.ajax({
	
	url: 'get_section_list',
	type: 'POST',
	data: { app_id : app_con},
	
  	success: function (data) {
  	// console.log(data);
	sectionnames=data.replace("[","").replace(/"/g, "").replace("]","").split(',');
	
	for(var i in sectionnames)
	{
	
	sectionss.push({
            label:sectionnames[i],
            value:sectionnames[i]
   	});
	}
	sections=sectionss;
	// console.log('success', sections);
	},
    error:function(XMLHttpRequest, textStatus, errorThrown)
	{
	 console.log('error', errorThrown);
    }
});	

$.ajax({
	url: 'get_app_con',
	type: 'POST',
	data: { app_id : app_con},
	dataType:"json",
	success: function (data) {
	app=data;
  	// console.log(app);
	for(pag in data)
	{
		for(sec in data[pag])
		{
			for(elem in data[pag][sec])
			{
			$.each(data[pag][sec][elem], function(key, value) {
			});
			}
		}
	}//for pag in data
	},
    error:function(XMLHttpRequest, textStatus, errorThrown)
	{
	 console.log('error', errorThrown);
    }
});	

var workflow = $('#workflow').val();
$('#workflow').val('');
console.log(workflow)
if(workflow!='' && typeof(workflow) != "undefined")
{
var work_flow = JSON.parse(workflow)
var flow = work_flow;//[0].workflow;


	for(var i in flow)
	{
		var wrkflwtyp = flow[i].Workflow_Type;
		if(wrkflwtyp =="single")	
		{
			var user = flow[i].UsersList;
			var userslst=user.toString();
			var grps_usr = flow[i].groupList;
			var grps=grps_usr.toString();
			var Eper = flow[i].Edit_Permissions;
			var eperms=Eper.toString();
			var stagtyp = flow[i].Stage_Type;
			var print=flow[i].print;
			var sms  = flow[i].sms;
			var Vper = flow[i].View_Permissions;
			var vperms=Vper.toString();
			checkempty++;
			count+=1;
			$('<div id="div'+count+'" class="crdiv'+count+' create selectcate"><div class="breadcrumb startups"><label class="labcheck control-label" name="section" id="Label'+count+'" name="Label[]" stagetype="'+stagtyp+'" grups="'+grps+'" usrs="'+userslst+'" editper="'+eperms+'" viewper="'+vperms+'" print="'+print+'" sms="'+sms+'" usrss="" previous="">'+i+'</label><a href="javascript:void(0);" id="prop'+count+'"class="prop delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div></div><div id="clickline'+count+'" class="clickline active"><div id="line'+count+'" class="vertical-line"></div></div>').appendTo('#dynamicdiv');
			
			$('#prop'+count+'').popover({
				html:true,
				title: 'Properties',
				content:$('<div class="well"><div class="popalign"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Stage Name"/><select class="type multiselect'+count+'" id="typval select'+count+'"><option value="multiselect-all">Select-Type</option><option value="web">Web</option><option value="device">Device</option><option value="hybrid">Hybrid</option></select></div><div class="popalign"><select class="type  groupnames'+count+'" id="typval groupnames'+count+'" multiple="multiple"><option value="multiselect-all">Select-all-Users</option></select><select class="type userlist'+count+'" id="typval userlist'+count+'" multiple="multiple"><option value="multiselect-all">Select-all-Users</option></select></div><div class="popalign"><select class="type edit'+count+'" id="typval edit'+count+'" multiple="multiple"><option value="multiselect-all">Select-all</option></select><select class="type view'+count+'" id="typval view'+count+'" multiple="multiple"><option value="multiselect-all">Select-all</option></select></div><div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavestage popsavebtn" id="savebtn'+count+'" value="Save"/></div><div style="margin-left: 330px;"><label class="print checkbox"><input type="checkbox" class="print'+count+'" name="checkbox"><i></i>Print</label></div></div>')
			})
		}
	}
}
$("body").on("click",".clickline", function(e) //user click on add ||||
{ 
	var clicklineid=$(this).attr('id');
	
	var lincount = clicklineid.replace(/\D/g,'');
	
	if($('#clickline'+lincount+'').hasClass("active"))
	{
//count+=1;	
	
		if(flagval==0)
		{
		flagval=1;
		count+=1;	
	$('<div id="div'+count+'" class="crdiv'+count+' create selectcate"><div class="breadcrumb startup"><label class="radio-inline"><input type="radio" class="form-input start" value="app" name="rad" id="intxt">Stage</label><label class="radio-inline"><input type="radio" class="form-input start" id="intxt" name="rad" value="conbranch">Conditional Branch</label><label class="radio-inline"><input type="radio" class="form-input start" id="intxt" name="rad" value="parbranch">Parallel Branch</label><label class="radio-inline"><input type="radio" class="form-input start" id="intxt" name="rad" value="api">API</label><div class="col-md-1 pull-right"><button type="button" id="hidbtn'+count+'" class="removedivv btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus-sign"></span></button></div></div></div><div id="clickline'+count+'" class="clickline active"><div id="line'+count+'" class="vertical-line"></div></div>').insertAfter('#clickline'+lincount+'');//.appendTo('#clickline'+lincount+'');//('#dynamicdiv');
	   // console.log("ssssssssssssssssssssssssssssssssssssssss",$('#dynamicdiv').children('.create').length)
		if($('#dynamicdiv').children('.create').length == 1)
		{
			$("input[type=radio][value='conbranch']").attr("disabled",true);
			$("input[type=radio][value='parbranch']").attr("disabled",true);
			$("input[type=radio][value='api']").attr("disabled",true);
		}
		}
	$('input:radio[name=rad]').change(function()
	{
	$('#div'+count+'').empty();
	if(this.value == 'app')
	{
		if($('#dynamicdiv').children('.create').length == 1)
		{
			flagval=0;
			checkempty++;
			$('<div class="breadcrumb startups"><label class="labcheck control-label" name="section" id="Label'+count+'" name="Label[]" stagetype="" grups="" usrs="" editper="" viewper="" sms="false" print="false" usrss="" previous="">Stage Name'+count+'</label><a href="javascript:void(0);" id="prop'+count+'"class="prop delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a></div>').appendTo('#div'+count+'');
	
	$('#prop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="popalign"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Stage Name"/><select class="type multiselect'+count+'" id="typval select'+count+'"><option value="multiselect-all" selected>Select-Type</option><option value="web">Web</option><option value="device">Device</option><option value="hybrid">Hybrid</option></select></div><div class="popalign"><select class="type  groupnames'+count+'" id="typval groupnames'+count+'" multiple="multiple"><option value="multiselect-all">Select-all-Users</option></select><select class="type userlist'+count+'" id="typval userlist'+count+'" multiple="multiple"><option value="multiselect-all">Select-all-Users</option></select></div><div class="popalign"><select class="type edit'+count+'" id="typval edit'+count+'" multiple="multiple"><option value="multiselect-all">Select-all</option></select><select class="type view'+count+'" id="typval view'+count+'" multiple="multiple"><option value="multiselect-all">Select-all</option></select></div><div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavestage popsavebtn" id="savebtn'+count+'" value="Save"/></div><div style="margin-left:275px;"><label class="print checkbox"><input type="checkbox" class="print'+count+'" name="checkbox"><i></i>Print</label><label class="sms checkbox"><input type="checkbox" class="sms'+count+'" name="checkbox"><i></i>SMS</label></div></div>')//$('#divprop'+count+'').html()
	})
		}
		else
		{		
			flagval=0;
			checkempty++;
			$('<div class="breadcrumb startups"><label class="labcheck control-label" name="section" id="Label'+count+'" name="Label[]" stagetype="" grups="" usrs="" editper="" viewper="" sms="false" print="false" usrss="" previous="">Stage Name'+count+'</label><a href="javascript:void(0);" id="prop'+count+'"class="prop delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#div'+count+'');
	
			$('#prop'+count+'').popover({
				html:true,
				title: 'Properties',
				content:$('<div class="well"><div class="popalign"><input class="name'+count+' popinput form-control col-md-6" id="name'+count+'" type="text" value="" placeholder="Stage Name"/><select class="type multiselect'+count+'" id="typval select'+count+'"><option value="multiselect-all">Select-Type</option><option value="web">Web</option><option value="device">Device</option><option value="hybrid">Hybrid</option></select></div><div class="popalign"><select class="type  groupnames'+count+'" id="typval groupnames'+count+'" multiple="multiple"><option value="multiselect-all">Select-all-Users</option></select><select class="type userlist'+count+'" id="typval userlist'+count+'" multiple="multiple"><option value="multiselect-all">Select-all-Users</option></select></div><div class="popalign"><select class="type edit'+count+'" id="typval edit'+count+'" multiple="multiple"><option value="multiselect-all">Select-all</option></select><select class="type view'+count+'" id="typval view'+count+'" multiple="multiple"><option value="multiselect-all">Select-all</option></select></div><div class="row col-md-8"><input type="button" class="btn btn-default btn-sm popsavestage popsavebtn" id="savebtn'+count+'" value="Save"/></div><div style="margin-left:275px;"><label class="print checkbox"><input type="checkbox" class="print'+count+'" name="checkbox"><i></i>Print</label><label class="sms checkbox"><input type="checkbox" class="sms'+count+'" name="checkbox"><i></i>SMS</label></div></div>')//$('#divprop'+count+'').html()
			})
			
		}
	
	}
	else if(this.value =='api')
	{
		flagval=0;
				checkempty++;
	$('<div class="breadcrumb api"><label class="labcheck control-label" name="section" id="Label'+count+'" name="Label[]" comid="" stagetype="" usrs="" viewper="">Stage API'+count+'</label><a href="javascript:void(0);" id="apiprop'+count+'"class="apiprop delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="pull-right removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#div'+count+'');
	
	$('#apiprop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="popalign"><select class="type multiselect'+count+'" id="typval select'+count+'"><option value="web">Web</option></select><select class="type companyid'+count+'" id="typval companyid'+count+'" multiple="multiple"></select><select class="type  groupnames'+count+'" id="typval groupnames'+count+'" multiple="multiple"><option value="multiselect-all">Select-all-Users</option></select><select class="type view'+count+'" id="typval view'+count+'" multiple="multiple"><option value="multiselect-all">Select-all</option></select></div><div class="row col-md-8"><input type="button" class="btn btn-default btn-sm apipopsavestage popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')//$('#divprop'+count+'').html()
	})
	
	
	}
	else if(this.value =='conbranch')
	{//^^^^^^^^^^^^
	flagval=0;
			checkempty++;
			
			$('<div id="branch'+count+'" class="conmain"><div class="rowone"><div class="ver-line-"></div><div class="hor-line-left"></div><div id="square" class="breadcrumb"><label class="hide" id="cond'+count+'" sectnlst="'+seclist+'" elementlist="" opr="" vval=""></label></div><div><a href="javascript:void(0);" id="conprop'+count+'" class="conprop delbtn pull-right btn btn-danger btn-xs" rel="popover"><span class="glyphicon glyphicon-wrench"></span></a><button type="button" id="hidbtn'+count+'" alt="Delete" class="removediv pull-right btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button><div class="hor-line-left"></div></div><div class="ver-line-"></div></div><div class="rowtwo"><div class="breadcrumb startups app"><label class="labcheck control-label" name="section" id="Label'+count+'" name="Label[]" stagetype="" grups="" usrs="" editper="" viewper="" usrss="">Approved</label></div><div class="breadcrumb startups disapp"><label class="labcheck control-label" name="section" id="Label'+count+'" name="Label[]" stagetype="" grups="" usrs="" editper="" viewper="" usrss="">Disapproved</label></div></div><div class="rowthree"><div class="ver-line-bottom"></div><div class="hor-line-left-bottom"></div><div class="hor-line-center"></div><div class="hor-line-left-bottom"></div><div class="ver-line-bottom"></div></div>').appendTo('#div'+count+'');
			
				
	$('#conprop'+count+'').popover({
		html:true,
		title: 'Properties',
		content:$('<div class="well"><div class="form-inline"><div class="conpr"><select class="type elemnt'+count+'" id="typval elemnt'+count+'"></select></div><div class="conpr"><select class="type operator'+count+'" id="typval operator'+count+'"><option value="==">Is Equal To</option><option value="!=">Is Not Equal To</option><option value=">=">Is Greater Than OR Equal To</option><option value="<=">Is Less Than OR Equal To</option><option value=">">Is Greater Than</option><option value="<">Is Less Than</option></select></div><div class="conpr"><input class="value'+count+' popinput form-control col-md-6" id="value'+count+'" type="text" value="" placeholder="value"/></div></div>'+'<div class="row col-md-8"><input type="button" class="btn btn-default btn-sm consavestage popsavebtn" id="savebtn'+count+'" value="Save"/></div></div>')
	})
		
	}
	else if(this.value == 'parbranch')
	{	
	flagval=0;	
	checkempty++;
//`````
$('<div id="branch'+count+'" class="parmain"><div id="rowone'+count+'" class="rowone"><div id="ver-line-'+count+'1" class="ver-line-"></div><div id="hor-line-left'+count+'1" class="hor-line-left"></div><div id="square" class="breadcrumb parbr"></div><div id="hor-line-left'+count+'2" class="hor-line-left"><button type="button" id="hidbtn'+count+'" alt="Delete" class="removediv pull-right btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div><div id="ver-line-'+count+'2" class="ver-line-"></div></div><div id="rowtwo'+count+'" class="rowtwo rowpar"><div id="parallel'+count+'1" class="breadcrumb startups parallel"><label class="labcheck control-label" name="section" id="Label'+count+'" name="Label[]" stagetype="" grups="" usrs="" editper="" viewper="" usrss="">Branch</label></div><div id="parallel'+count+'2" class="breadcrumb startups parallel"><label class="labcheck control-label" name="section" id="Label'+count+'" name="Label[]" stagetype="" grups="" usrs="" editper="" viewper="" usrss="">Branch</label></div></div><div id="rowthree'+count+'" class="rowthree"><div id="ver-line-bottom'+count+'1" class="ver-line-bottom"></div><div  id="hor-line-left-bottom'+count+'1" class="hor-line-left-bottom"></div><div class="hor-line-center"></div><div id="hor-line-left-bottom'+count+'2" class="hor-line-left-bottom"></div><div id="ver-line-bottom'+count+'2" class="ver-line-bottom"></div></div>').appendTo('#div'+count+'');


$('#parallel'+count+'2').css('margin-left',85);
			
	}

});

}//end of count
})//end of vertical-line class
		$(function() {
    	$.fn.extend({
    	    popoverClosable: function (options) {
    	        var defaults = {
    	            template:
    	                '<div class="popover">\
    	<div class="arrow"></div>\
    	<div class="popover-header">\
    	<button type="button" class="close" data-dismiss="popover" aria-hidden="true">&times;</button>\
    	<h3 class="popover-title"></h3>\
    	</div>\
    	<div class="popover-content"></div>\
    	</div>'
    	        };
    	        options = $.extend({}, defaults, options);
    	        var $popover_togglers = this;
    	        $popover_togglers.popover(options);
    	        $popover_togglers.on('click', function (e) {
    	            e.preventDefault();
    	            $popover_togglers.not(this).popover('hide');
    	        });
    	        $('html').on('click', '[data-dismiss="popover"]', function (e) {
    	            $popover_togglers.popover('hide');
    	        });
    	    }
    	});

    	$(function () {
    	    $('[data-toggle="popover"]').popoverClosable();
    	});
    });

$(document).on('click','.prop',function(e)
	{ 
	    
		var addusr= $(this).attr('id');
		tempcount = addusr.replace(/\D/g,'');
		$('#prop'+tempcount+'').on('shown.bs.popover', function () 
		{
			 userlist=[] // changed on 28-05-2016 for user list issue
			 var stage_nam = $('#Label'+tempcount+'').text();
			 $('#name'+tempcount+'').val(stage_nam);
			$('.view'+tempcount+'').multiselect({
	    	  nonSelectedText:'View Permissions',
			  //maxHeight: 300,
			  buttonWidth: '190px'
			 });
			$('.userlist'+tempcount+'').multiselect({
	    	  nonSelectedText:'Select Users',
			  buttonWidth: '190px'
			 });
			$('.edit'+tempcount+'').multiselect({
	    	  nonSelectedText:'Edit Permissions',
			  buttonWidth: '190px'
			 });
			
			$('.groupnames'+tempcount+'').multiselect('dataprovider', users);
			$('.userlist'+tempcount+'').multiselect();
			$('.edit'+tempcount+'').multiselect('dataprovider', sections);//'dataprovider', sections);
			$('.view'+tempcount+'').multiselect('dataprovider', sections);//'dataprovider', sections);
			var stage_ = $('#Label'+tempcount+'').attr("stagetype");
			$('.multiselect'+tempcount+'').val(stage_)

				var selegrups=$('#Label'+tempcount+'').attr("grups");
				if(selegrups!=null && selegrups!='' && selegrups!=undefined)
				{
					
					var sel=selegrups.split(",");
					for (var i in sel)
					{
					
					$('.groupnames'+tempcount+'').multiselect('select', sel[i],true);
					//$('.groupnames'+tempcount+'').trigger('click');
					
					}
				}
		
				var seleusers=$('#Label'+tempcount+'').attr("usrs");
				if(seleusers!=null && seleusers!='' && seleusers!=undefined)
				{
					
					var sel=seleusers.split(",");
					for (var i in sel)
					{
					
					$('.userlist'+tempcount+'').multiselect('select', sel[i]);
					}
				}
				var seleperm=$('#Label'+tempcount+'').attr("editper");
				if(seleperm!=null && seleperm!='' && seleperm!=undefined)
				{
					
					var esel=seleperm.split(",");
					for (var i in esel)
					{
				
					$('.edit'+tempcount+'').multiselect('select', esel[i]);
					}
				}
				var selvperm=$('#Label'+tempcount+'').attr("viewper");
				if(selvperm!=null && selvperm!='' && selvperm!=undefined)
				{
					
					var vsel=selvperm.split(",");
					for (var i in vsel)
					{
					
					$('.view'+tempcount+'').multiselect('select', vsel[i]);
					}
				}
			})
				$('.groupnames'+tempcount+'').multiselect({
				nonSelectedText:'Select Groups',
				buttonWidth: '190px',
		    	onChange: function(option, checked,select) 
				{
		      	var values = [];
				if(checked===true)
				{
				$('.groupnames'+tempcount+' option:selected')//.each(function()
				{
					var vvv=option.val();
					values.push(grouplist[vvv]);
					//userlist=[];  // changed on 28-05-2016 for user list issue
					var uservalues=values.toString();
					uservalues=$.trim(uservalues);
					if(uservalues!='' && uservalues!=undefined && uservalues!=null)
					{
						var usrval=uservalues.split(",");
						for(var i in usrval){
						userlist.push({
							label:usrval[i],
							value:usrval[i]
						    });
						}
					}
				}
				
				var makeuniquelist=[];
				makeuniquelist=$.unique(userlist);
				userlist=makeuniquelist
				$('.userlist'+tempcount+'').multiselect('dataprovider', userlist);
				}
				if(checked===false)
				{
					$('.groupnames'+tempcount+' option:selected')//.each(function()
				
					var delval=option.val();
					
					var removelist=grouplist[delval];
					var remvlist=removelist.toString();
					remvlist=$.trim(remvlist);
					
					var remlsit=remvlist.split(",");
					for(var i in remlsit)
					{
					var xRemove=remlsit[i];
					
					userlist = $.grep(userlist, function(val){
				    return val.value !== xRemove;
					})//.get(0);
					}
				
				$('.userlist'+tempcount+'').multiselect('dataprovider', userlist);
				}
				var seleusrs=$('#Label'+tempcount+'').attr("usrs");
				if(seleusrs!=null && seleusrs!='' && seleusrs!=undefined)
				{
					
					var sel=seleusrs.split(",");
					for (var i in sel)
					{
					
					$('.userlist'+tempcount+'').multiselect('select', sel[i]);
					}
				}
		      }
			})
				
});	

$(document).on('click','.popsavestage', function (e) 
		{
			e.stopPropagation();
			e.preventDefault();
			
			var prop= $(this).attr('id');
			tempcount = prop.replace(/\D/g,'');
			
            $('#Label'+tempcount+'').attr("grups",'');
			$('#Label'+tempcount+'').attr("usrs",'');
			
			var nameval = $('#name'+tempcount+'').val();
			var stype   = $('.multiselect'+tempcount+'').val();
			var userss  = $('.groupnames'+tempcount+'').val();
			var ussers  = $('.userlist'+tempcount+'').val();
			var editt   = $('.edit'+tempcount+'').val();
			var vieww	= $('.view'+tempcount+'').val();
			var print	= $('.print'+tempcount+'').is(':checked');
			var sms		= $('.sms'+tempcount+'').is(':checked');
			
			var secval=editt;//.split(",");
			for(var i in secval)
			{
				seclist.push(secval[i]);
			}
			
			var secvalv=vieww;//.split(",");
			for(var i in secvalv)
			{
				seclist.push(secvalv[i]);
			}
			
			var makeunique=[];
			$.each(seclist, function(i, el){
    		if($.inArray(el, makeunique) === -1) makeunique.push(el);
			});
			seclist=makeunique;
			
			if(nameval!='')
			{
			$('#Label'+tempcount+'').text(nameval);
			}
			else
			{
			$('#Label'+tempcount+'').text("Stage Name");
			}
			if(ussers!=null)
			{
			$('#Label'+tempcount+'').attr("usrs",ussers);
			}
			if(userss!=null)
			{
			$('#Label'+tempcount+'').attr("grups",userss);
			}
			if(stype!=null)
			{
			$('#Label'+tempcount+'').attr("stagetype",stype);
			}
			if(editt!=null)
			{
			$('#Label'+tempcount+'').attr("editper",editt);
			}
			if(vieww!=null)
			{
			$('#Label'+tempcount+'').attr("viewper",vieww);
			}
			if(print!=null)
			{
			$('#Label'+tempcount+'').attr("print",print);
			}
			if(sms!=null)
			{
			$('#Label'+tempcount+'').attr("sms",sms);
			}
			$('#prop'+tempcount+'').popover('hide')
	});



$(document).on('click','.conprop',function(e)
	{ 
		var addusr= $(this).attr('id');
		var elelist=[];
		var templist=[];
		
		tempcount = addusr.replace(/\D/g,'');
		$('#conprop'+tempcount+'').on('shown.bs.popover', function () 
		{
			
			var secthis=$('#cond'+tempcount+'').attr("sectnlst");
			
			secthis=$.trim(secthis);
			var secllst=[];
			var secthiss=secthis.split(",");
			for(var sss in secthiss)
			{
				secllst.push(secthiss[sss]);
			}				
			 
				for(pag in app)
				{
					var page_current=pag;
					
					for(sec in app[pag])
					{
						
					 	for(elem in app[pag][sec])
						{
							
							if($.inArray(elem,secllst) >= 0)
					 		{
							
							$.each(app[pag][sec][elem], function(key, value) 
							{
							
							if(key!="dont_use_this_name")
							{
								var key_new = 'page'+sec+'.'+elem+'.'+key+'';
								templist.push(key_new);
							}
							});
							}
					  	}
					}
				}//for pag in data
			var makeunique=[];
			$.each(templist, function(i, el){
    		if($.inArray(el, makeunique) === -1) makeunique.push(el);
			});
			var lssstt=makeunique.toString();
			lssstt=$.trim(lssstt);
			var lssst=lssstt.split(",")
			for(var lst in lssst)
			{
				if(lssst[lst]!="dont_use_this_name")
				{
				//label_split = lssst[lst].substring(lssst[lst].lastIndexOf('.'));
				var arr = lssst[lst].split('.');
				
				label_split = arr[arr.length-1];
				
				elelist.push({
				label:label_split,
				value:lssst[lst]
				})
				}
			}
			
		var firstelement=elelist[0].value;
			$('.elemnt'+tempcount+'').multiselect('dataprovider', elelist);

				var seleusers=$('#cond'+tempcount+'').attr("elementlist");
				if(seleusers!=null && seleusers!='' && seleusers!=undefined)
				{
					
					$('.elemnt'+tempcount+'').multiselect('deselect', firstelement);
					$('.elemnt'+tempcount+'').multiselect('select', seleusers);
				}
			$('.operator'+tempcount+'').multiselect();//'dataprovider', sections);
		})
});	


$(document).on('click','.consavestage', function (e) 
		{
			e.stopPropagation();
			e.preventDefault();
			var prop= $(this).attr('id');
			tempcount = prop.replace(/\D/g,'');
			// console.log("hiding"+tempcount);
			var elemnt=$('.elemnt'+tempcount+'').val();
			var opr=$('.operator'+tempcount+'').val();
			var vval=$('.value'+tempcount+'').val();
			$('#cond'+tempcount+'').attr("elementlist",elemnt);
			$('#cond'+tempcount+'').attr("opr",opr);
			if(vval!=null)
			{
			$('#cond'+tempcount+'').attr("vval",vval);
			}
			// console.log(userss);
			$('#conprop'+tempcount+'').popover('hide')
});

$(document).on('click','.parprop',function(e)
	{ 
	    // console.log("cccccccccccccccccccccccccc");
		var addusr= $(this).attr('id');
		// console.log(addusr);
		tempcount = addusr.replace(/\D/g,'');
		$('#parprop'+tempcount+'').on('shown.bs.popover', function () 
		{
			// console.log("dddddddddddddddddddddd");
			$('.groupnames'+tempcount+'').multiselect({
	    	  nonSelectedText:'Select Groups',
			  buttonWidth: '190px'
			 });
			$('.view'+tempcount+'').multiselect({
	    	  nonSelectedText:'View Permissions',
			  buttonWidth: '190px'
			 });
			$('.userlist'+tempcount+'').multiselect({
	    	  nonSelectedText:'Select Users',
			  includeSelectAllOption: true,
			  buttonWidth: '190px'
			 });
			$('.edit'+tempcount+'').multiselect({
	    	  nonSelectedText:'Edit Permissions',
			  buttonWidth: '190px'
			 });
			$('.groupnames'+tempcount+'').multiselect('dataprovider', users);
			$('.userlist'+tempcount+'').multiselect();
			$('.edit'+tempcount+'').multiselect('dataprovider', sections);//'dataprovider', sections);
			$('.view'+tempcount+'').multiselect('dataprovider', sections);//'dataprovider', sections);
						
				var seleusers=$('#Label'+tempcount+'').attr("usrs");
				if(seleusers!=null && seleusers!='' && seleusers!=undefined)
				{
					// console.log("333333333333333"+seleusers);
					var sel=seleusers.split(",");
					for (var i in sel)
					{
					// console.log("vvvvvvvvvvdddddddd"+sel[i]);
					$('.groupnames'+tempcount+'').multiselect('select', sel[i]);
					}
				}
				var seleperm=$('#Label'+tempcount+'').attr("editper");
				if(seleperm!=null && seleperm!='' && seleperm!=undefined)
				{
					// console.log("333333333333333"+seleperm);
					var esel=seleperm.split(",");
					for (var i in esel)
					{
					// console.log("vvvvvvvvvvdddddddd"+esel[i]);
					$('.edit'+tempcount+'').multiselect('select', esel[i]);
					}
				}
				var selvperm=$('#Label'+tempcount+'').attr("viewper");
				if(selvperm!=null && selvperm!='' && selvperm!=undefined)
				{
					// console.log("333333333333333"+selvperm);
					var vsel=selvperm.split(",");
					for (var i in vsel)
					{
					// console.log("vvvvvvvvvvdddddddd"+vsel[i]);
					$('.view'+tempcount+'').multiselect('select', vsel[i]);
					}
				}
		})
				$('.groupnames'+tempcount+'').multiselect({////////////////////
				nonSelectedText:'Select Groups',
				buttonWidth: '190px',
		    	onChange: function(option, checked) {
		      	var values = [];
				if(checked===true)
				{
				$('.groupnames'+tempcount+' option:selected')//.each(function()
				{
					var vvv=option.val();
					//console.log("000000000000000000000000000000000"+asd);
					//var vvv=$('.groupnames'+tempcount+'').val();
					// console.log("pppppppppppppppppppppppp"+values);
					// console.log("qqqqqqqqqqqqqqqqqqqqqqqq"+vvv);
					// console.log(JSON.stringify(grouplist));
					values.push(grouplist[vvv]);
					// console.log("rrrrrrrrrrrrrrrrrrrrrrrr"+grouplist[vvv]);
					// console.log("llllllllll"+values.length);
					userlist=[];
					var uservalues=values.toString();
					uservalues=$.trim(uservalues);
					// console.log("sssssssssssssssssssssss"+uservalues);
					var usrval=uservalues.split(",");
					for(var i in usrval){
						// console.log("9999999999999999"+usrval[i]);
						userlist.push({
							label:usrval[i],
							value:usrval[i]
						    });
					}
				
                }
			   	// console.log("333333333333333333333"+JSON.stringify(userlist));
				$('.userlist'+tempcount+'').multiselect('dataprovider', userlist);
				}
				if(checked===false)
				{
					$('.groupnames'+tempcount+' option:selected')//.each(function()
				
					var delval=option.val();
					// console.log("pppppppppppppppppppppppp"+values);
					// console.log("xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"+vvv);
					// console.log(JSON.stringify(grouplist));
					// console.log("rrrrrrrrrrrrrrrrrrrrrrrr"+grouplist[delval]);
					var removelist=grouplist[delval];
					var remvlist=removelist.toString();
					remvlist=$.trim(remvlist);
					// console.log("sssssssssssssssssssssss"+remvlist);
					var remlsit=remvlist.split(",");
					for(var i in remlsit)
					{
					var xRemove=remlsit[i];
					// console.log("jjjjjjjjjjjjjjjjj"+xRemove)
					userlist = $.grep(userlist, function(val){
				    return val.value !== xRemove;
					})//.get(0);
					}
				// console.log("333333333333333333333"+JSON.stringify(userlist));
				$('.userlist'+tempcount+'').multiselect('dataprovider', userlist);
				}
				
		      }
			})
				
});	


$(document).on('click','.parsavestage', function (e) 
		{
			e.stopPropagation();
			e.preventDefault();
			////alert("clicked")stagetype="" users="" editper="" viewper=""
			var prop= $(this).attr('id');
			tempcount = prop.replace(/\D/g,'');
			// console.log("hiding"+tempcount);
			var nameval=$('#name'+tempcount+'').val();
			var stype=$('.multiselect'+tempcount+'').val();
			var userss=$('.groupnames'+tempcount+'').val();
			var ussers=$('.userlist'+tempcount+'').val();
			var editt=$('.edit'+tempcount+'').val();
			var vieww=$('.view'+tempcount+'').val();
			
			var secval=editt;//.split(",");
			for(var i in secval)
			{
				seclist.push(secval[i]);
			}
			// console.log("1111111111111"+seclist);
			var secvalv=vieww;//.split(",");
			for(var i in secvalv)
			{
				seclist.push(secvalv[i]);
			}
			// console.log("222222222222"+seclist);
			var makeunique=[];
			$.each(seclist, function(i, el){
    		if($.inArray(el, makeunique) === -1) makeunique.push(el);
			});
			seclist=makeunique;
			
			if(nameval!='')
			{
			$('#prLabel'+tempcount+'').text(nameval);
			}
			else
			{
			$('#prLabel'+tempcount+'').text("Stage Name");
			}
			if(ussers!=null)
			{
			$('#prLabel'+tempcount+'').attr("usrs",ussers);
			}
			if(userss!=null)
			{
			$('#prLabel'+tempcount+'').attr("grups",userss);
			}
			if(stype!=null)
			{
			$('#prLabel'+tempcount+'').attr("stagetype",stype);
			}
			if(editt!=null)
			{
			$('#prLabel'+tempcount+'').attr("editper",editt);
			}
			if(vieww!=null)
			{
			$('#prLabel'+tempcount+'').attr("viewper",vieww);
			}
			$('#parprop'+tempcount+'').popover('hide')
	});

	$(document).on('click','.apiprop',function(e)
	{ 
	    // console.log("cccccccccccccccccccccccccc");
		var addusr= $(this).attr('id');
		tempcount = addusr.replace(/\D/g,'');
		$('#apiprop'+tempcount+'').on('shown.bs.popover', function () 
		{
			// console.log("dddddddddddddddddddddd");
			$('.companyid'+tempcount+'').multiselect({
	    	  nonSelectedText:'Company',
			  buttonWidth: '190px'
			 });
			$('.groupnames'+tempcount+'').multiselect({
	    	  nonSelectedText:'Select Users',buttonWidth: '190px'
			 });
			$('.view'+tempcount+'').multiselect({
	    	  nonSelectedText:'View Permissions',buttonWidth: '190px'
			 });
			$('.groupnames'+tempcount+'').multiselect();
			$('.companyid'+tempcount+'').multiselect('dataprovider',apicompany);//'dataprovider', sections);
			$('.view'+tempcount+'').multiselect('dataprovider', sections);//'dataprovider', sections);
						
//				var seleusers=$('#Label'+tempcount+'').attr("usrs");
//				if(seleusers!=null && seleusers!='' && seleusers!=undefined)
//				{
//					console.log("333333333333333"+seleusers);
//					var sel=seleusers.split(",");
//					for (var i in sel)
//					{
//					console.log("vvvvvvvvvvdddddddd"+sel[i]);
//					$('.groupnames'+tempcount+'').multiselect('select', sel[i]);
//					}
//				}
//				var seleperm=$('#Label'+tempcount+'').attr("comid");
//				if(seleperm!=null && seleperm!='' && seleperm!=undefined)
//				{
//					console.log("333333333333333"+seleperm);
//					var esel=seleperm.split(",");
//					for (var i in esel)
//					{
//					console.log("vvvvvvvvvvdddddddd"+esel[i]);
//					$('.companyid'+tempcount+'').multiselect('select', esel[i]);
//					}
//				}
				var selvperm=$('#Label'+tempcount+'').attr("viewper");
				if(selvperm!=null && selvperm!='' && selvperm!=undefined)
				{
					// console.log("333333333333333"+selvperm);
					var vsel=selvperm.split(",");
					for (var i in vsel)
					{
					// console.log("vvvvvvvvvvdddddddd"+vsel[i]);
					$('.view'+tempcount+'').multiselect('select', vsel[i]);
					}
				}
		})
				$('.companyid'+tempcount+'').multiselect({////////////////////
				nonSelectedText:'Select Company',buttonWidth: '190px',
		    	onChange: function(option, checked) {
		      	var values = [];
				if(checked===true)
				{
				$('.groupnames'+tempcount+' option:selected')//.each(function()
				{
					var vvvv=option.val();
					//console.log("000000000000000000000000000000000"+asd);
					//var vvv=$('.groupnames'+tempcount+'').val();
					// console.log("pppppppppppppppppppppppp"+values);
					// console.log("qqqqqqqqqqqqqqqqqqqqqqqq"+vvvv);
					// console.log(JSON.stringify(companylist));
					values.push(companylist[vvvv]);
					// console.log("rrrrrrrrrrrrrrrrrrrrrrrr"+companylist[vvvv]);
					// console.log("llllllllll"+values.length);
					var uservalues=values.toString();
					// console.log("llllllllll"+uservalues);
					uservalues=$.trim(uservalues);
					// console.log("sssssssssssssssssssssss"+uservalues);
					var usrval=uservalues.split(",");
					for(var i in usrval){
						// console.log("9999999999999999"+usrval[i]);
						apiuserlist.push({
							label:usrval[i],
							value:usrval[i]
						    });
					}
				
                }
			   	// console.log("333333333333333333333"+JSON.stringify(apiuserlist));
				$('.groupnames'+tempcount+'').multiselect('dataprovider', apiuserlist);
				}
				if(checked===false)
				{
					$('.groupnames'+tempcount+' option:selected')//.each(function()
				
					var delval=option.val();
					// console.log("pppppppppppppppppppppppp"+values);
					// console.log("xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"+vvv);
					// console.log(JSON.stringify(grouplist));
					// console.log("rrrrrrrrrrrrrrrrrrrrrrrr"+grouplist[delval]);
					var removelist=grouplist[delval];
					var remvlist=removelist.toString();
					remvlist=$.trim(remvlist);
					// console.log("sssssssssssssssssssssss"+remvlist);
					var remlsit=remvlist.split(",");
					for(var i in remlsit)
					{
					var xRemove=remlsit[i];
					// console.log("jjjjjjjjjjjjjjjjj"+xRemove)
					userlist = $.grep(userlist, function(val){
				    return val.value !== xRemove;
					})//.get(0);
					}
				// console.log("333333333333333333333"+JSON.stringify(userlist));
				$('.userlist'+tempcount+'').multiselect('dataprovider', userlist);
				}
				
		      }
			})
				
});	

$(document).on('click','.apipopsavestage', function (e) 
		{
			e.stopPropagation();
			e.preventDefault();
			////alert("clicked")stagetype="" users="" editper="" viewper=""
			var prop= $(this).attr('id');
			tempcount = prop.replace(/\D/g,'');
			// console.log("hiding"+tempcount);
			var stype=$('.multiselect'+tempcount+'').val();
			var userss=$('.groupnames'+tempcount+'').val();
			var editt=$('.companyid'+tempcount+'').val();
			var vieww=$('.view'+tempcount+'').val();
			
			//var secval=editt;//.split(",");
//			for(var i in secval)
//			{
//				seclist.push(secval[i]);
//			}
//			console.log("1111111111111"+seclist);
			var secvalv=vieww;//.split(",");
			for(var i in secvalv)
			{
				seclist.push(secvalv[i]);
			}
			// console.log("222222222222"+seclist);
			var makeunique=[];
			$.each(seclist, function(i, el){
    		if($.inArray(el, makeunique) === -1) makeunique.push(el);
			});
			seclist=makeunique;
			if(userss!=null)
			{
			$('#Label'+tempcount+'').attr("usrs",userss);
			}
			if(stype!=null)
			{
			$('#Label'+tempcount+'').attr("stagetype",stype);
			}
			if(editt!=null)
			{
			$('#Label'+tempcount+'').attr("comid",editt);
			}
			if(vieww!=null)
			{
			$('#Label'+tempcount+'').attr("viewper",vieww);
			}
			$('#apiprop'+tempcount+'').popover('hide')
	});






$(':not(#anything)').on('click', function (e) {
    $('.closeover').each(function () {
        //the 'is' for buttons that trigger popups
        //the 'has' for icons and other elements within a button that triggers a popup
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
            return;
        }
    });
});

//````


$(document).on('click','#square',function (e)//.unbind("click").on('click','.clicktdvr',function (ev)
		{
			if($(this).hasClass("parbr"))
			{
			// console.log("!!!!!!!!!!!!!!!!!!!");
			var tableid=$(this).parents('.parmain').attr('id');
			// console.log(tableid);
			tempcount = tableid.replace(/\D/g,'');
			// console.log("mainnnnnnn"+tempcount);
			var checkk=$('#branch'+tempcount+'').find('.parallel').last().attr('id');
			// console.log("laaast"+checkk);
			checkk = checkk.replace(/\D/g,'');
			// console.log(checkk);
			checkk=parseInt(checkk)+1;
			// console.log("newwwwwwwwwwwwww"+checkk);
			var parchildcnt=$('#branch'+tempcount+'').find('.rowpar').children('.parallel').length;
			// console.log("$$$$$$$$$$$$$$$$$"+parchildcnt);
			parchildcnt=parseInt(parchildcnt)+1;
			
//			if(parchildcnt==3)
//			{
//				
//			}
//			else if(parchildcnt==4)
//			{
//				
//			}
//			else if(parchildcnt==5)
//			{
//				
//			}
//			else
//			{
//				
//			}
			if(parchildcnt<=4)
			{
				$('<div id="hor-line-left'+checkk+'" class="hor-line-left"></div><div id="ver-line-'+checkk+'" class="ver-line-"></div>').appendTo('#rowone'+tempcount+'');
			
				$('<div id="parallel'+checkk+'" class="breadcrumb startups parallel"><label class="labcheck control-label" name="section" id="Label'+checkk+'" name="Label[]" stagetype="" grups="" usrs="" editper="" viewper="" usrss="">Branch</label><button type="button" id="mainnvrr'+checkk+'" alt="'+tempcount+'" class="pull-right mainnvrr removediv btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></div>').appendTo('#rowtwo'+tempcount+'');
			
				$('<div id="hor-line-left-bottom'+checkk+'" class="hor-line-left-bottom"></div><div id="ver-line-bottom'+checkk+'" class="ver-line-bottom"></div>').appendTo('#rowthree'+tempcount+'');
			
				$('#hor-line-left'+checkk+'').css('width',150);		
				$('#parallel'+checkk+'').css('margin-left',20);		
				$('#hor-line-left-bottom'+checkk+'').css('width',150);
			}
		}

});


$(document).on('click','.app',function (e)
{
	if(!$(this).hasClass("disable"))
	{
	appcount=10;
	$('.prop').attr('disabled','disabled');
	$('.conprop').attr('disabled','disabled');
	$('.apiprop').attr('disabled','disabled');
	$('.removediv').attr('disabled','disabled');
	$('.app').addClass("disable");
	$('.disapp').addClass("disable");
	$('.parallel').addClass("disable");		
	// console.log("clicked approved")
	var mainid=$(this).parents('.conmain').attr('id');
	// console.log(mainid);
	tempcount = mainid.replace(/\D/g,'');
	// console.log("mainnnnnnn"+tempcount);
	$('#branch'+tempcount+'').hide();
	
	if($('#approved'+tempcount+''+appcount+'').css('display') == 'none')
	{
		$('#approved'+tempcount+''+appcount+'').show();
		$('.clickline').removeClass("active");
		$('.well').css("border-color",'#ddd');
		$('#approved'+tempcount+''+appcount+'').css("border-color",'#75DAA3');//ACFCD2-75DAA3-C7FFE1
		$('#approved'+tempcount+''+appcount+'').children('.clickline').each(function(index, element) {
            $(this).addClass("active");

        });
			$('.closewell').removeClass("activeclose");
			$('.closewelld').removeClass("activeclose");
			$('.closewellpar').removeClass("activeclose");
			$('#approved'+tempcount+''+appcount+'').find('.closewell').addClass("activeclose");
			$('#approved'+tempcount+''+appcount+'').find('.prop').removeAttr("disabled");
			$('#approved'+tempcount+''+appcount+'').find('.conprop').removeAttr("disabled");
			$('#approved'+tempcount+''+appcount+'').find('.removediv').removeAttr("disabled");
			$('#approved'+tempcount+''+appcount+'').find('.app').removeClass("disable");
			$('#approved'+tempcount+''+appcount+'').find('.disapp').removeClass("disable");
			$('#approved'+tempcount+''+appcount+'').find('.parallel').removeClass("disable");				
	}
	else
	{	
		$('.closewell').removeClass("activeclose");
		$('.closewelld').removeClass("activeclose");
		$('.closewellpar').removeClass("activeclose");
		$('.clickline').removeClass("active");
		$('<div id="approved'+tempcount+''+appcount+'" class="well well-lg approved"><div id="clickline'+tempcount+''+appcount+'" class="clickline active"><div id="line'+tempcount+''+appcount+'" class="vertical-line"></div></div><div class="pull-right"><div id="close'+tempcount+'" class="btn btn-default btn-xs closewell activeclose">close</div></div></div>').appendTo('#div'+tempcount+'');
		$('.well').css("border-color",'#ddd');
		$('#approved'+tempcount+''+appcount+'').css("border-color",'#75DAA3');
	}
	}//main if----
})

$(document).on('click','.disapp',function (e)
{
	if(!$(this).hasClass("disable"))
	{
	appcount=10;
	$('.prop').attr('disabled','disabled');
	$('.conprop').attr('disabled','disabled');
	$('.removediv').attr('disabled','disabled');
	$('.apiprop').attr('disabled','disabled');
	$('.app').addClass("disable");
	$('.disapp').addClass("disable");
	$('.parallel').addClass("disable");		
	// console.log("clicked disapproved");
	var mainid=$(this).parents('.conmain').attr('id');
	// console.log(mainid);
	tempcount = mainid.replace(/\D/g,'');
	// console.log("mainnnnnnn"+tempcount);
	$('#branch'+tempcount+'').hide();
	if($('#disapproved'+tempcount+''+tempcount+''+appcount+'').css('display') == 'none')
	{
		$('#disapproved'+tempcount+''+tempcount+''+appcount+'').show();
		$('.clickline').removeClass("active");
		$('#disapproved'+tempcount+''+tempcount+''+appcount+'').children('.clickline').each(function(index, element) {
            $(this).addClass("active");
        });
		$('.closewell').removeClass("activeclose");
		$('.closewelld').removeClass("activeclose");
		$('.closewellpar').removeClass("activeclose");
		//$('#disapproved'+tempcount+''+tempcount+''+appcount+'').children('#close'+tempcount+'').addClass("activeclose");
		$('#disapproved'+tempcount+''+tempcount+''+appcount+'').find('.closewelld').addClass("activeclose");
		$('#disapproved'+tempcount+''+tempcount+''+appcount+'').find('.prop').removeAttr("disabled");
		$('#disapproved'+tempcount+''+tempcount+''+appcount+'').find('.conprop').removeAttr("disabled");
		$('#disapproved'+tempcount+''+tempcount+''+appcount+'').find('.removediv').removeAttr("disabled");
		$('#disapproved'+tempcount+''+tempcount+''+appcount+'').find('.app').removeClass("disable");
		$('#disapproved'+tempcount+''+tempcount+''+appcount+'').find('.disapp').removeClass("disable");
		$('#disapproved'+tempcount+''+tempcount+''+appcount+'').find('.parallel').removeClass("disable");		
		$('.well').css("border-color",'#ddd');
		$('#disapproved'+tempcount+''+tempcount+''+appcount+'').css("border-color",'#FF8181');//F5D8D8-FF8181-FFDDDD
	}
	else
	{	
		$('.closewell').removeClass("activeclose");
		$('.closewelld').removeClass("activeclose");
		$('.closewellpar').removeClass("activeclose");
		$('.clickline').removeClass("active");
		$('<div id="disapproved'+tempcount+''+tempcount+''+appcount+'" class="well well-lg disapproved"><div id="clickline'+tempcount+''+tempcount+''+appcount+'" class="clickline active"><div id="line'+tempcount+''+tempcount+''+appcount+'" class="vertical-line"></div></div><div class="pull-right"><div id="close'+tempcount+'" class="btn btn-default btn-xs closewelld activeclose">close</div></div></div>').appendTo('#div'+tempcount+'');
		$('.well').css("border-color",'#ddd');
		$('#disapproved'+tempcount+''+tempcount+''+appcount+'').css("border-color",'#FF8181');//FFC0C0
	}
	}
})

$(document).on('click','.closewell',function (e)
{
	if($(this).hasClass("activeclose"))
	{
	appcount=10;
	// console.log("clicked closewell");
	var mainid=$(this).attr('id');
	// console.log(mainid);
	tempcount = mainid.replace(/\D/g,'');
	// console.log("mainnnnnnn"+tempcount);
	$('#branch'+tempcount+'').show();
	$('.closewell').removeClass("activeclose");
	var activediv=$('#branch'+tempcount+'').parent('div').parent('div').attr('id');
	// console.log("|||||||||||||||",'#'+activediv+'');
	$('#'+activediv+'').children('.clickline').addClass("active");
	$('#'+activediv+'').find('.prop').removeAttr("disabled");
	$('#'+activediv+'').find('.conprop').removeAttr("disabled");
	$('#'+activediv+'').find('.apiprop').removeAttr("disabled");
	$('#'+activediv+'').find('.removediv').removeAttr("disabled");
	$('#'+activediv+'').find('.app').removeClass("disable");
	$('#'+activediv+'').find('.disapp').removeClass("disable");
	$('#'+activediv+'').find('.parallel').removeClass("disable");		
	
	if($('#'+activediv+'').hasClass('approved'))
	{
		$('#'+activediv+'').find('.closewell').addClass("activeclose");
		$('#'+activediv+'').css("border-color",'#75DAA3');	
	}
	else if($('#'+activediv+'').hasClass('disapproved'))
	{
		$('#'+activediv+'').find('.closewelld').addClass("activeclose");
		$('#'+activediv+'').css("border-color",'#FF8181');
	}
	else if($('#'+activediv+'').hasClass('parallelbranch'))
	{
		// console.log("check close");
		$('#'+activediv+'').find('.closewellpar').addClass("activeclose");
		$('#'+activediv+'').css("border-color",'#F0CFC5');
	}

	$('#approved'+tempcount+''+appcount+'').hide();
	$('#approved'+tempcount+''+appcount+'').children('.clickline').each(function(index, element) {
            $(this).removeClass("active");
        });
	//$('#approved'+tempcount+''+appcount+'').children('#close'+tempcount+'').removeClass("activeclose");
	//$('#approved'+tempcount+''+appcount+'').css("border-color",'#75DAA3');
	}
})

$(document).on('click','.closewelld',function (e)
{
	if($(this).hasClass("activeclose"))
	{
	appcount=10;
	// console.log("clicked closewelld");
	var mainid=$(this).attr('id');
	// console.log(mainid);
	tempcount = mainid.replace(/\D/g,'');
	// console.log("mainnnnnnn"+tempcount);
	$('#branch'+tempcount+'').show();
	$('.closewelld').removeClass("activeclose");
	var activediv=$('#branch'+tempcount+'').parent('div').parent('div').attr('id');
	// console.log("_____________",'#'+activediv+'');
	$('#'+activediv+'').children('.clickline').addClass("active");
	$('#'+activediv+'').find('.prop').removeAttr("disabled");
	$('#'+activediv+'').find('.conprop').removeAttr("disabled");
	$('#'+activediv+'').find('.apiprop').removeAttr("disabled");
	$('#'+activediv+'').find('.removediv').removeAttr("disabled");
	$('#'+activediv+'').find('.app').removeClass("disable");
	$('#'+activediv+'').find('.disapp').removeClass("disable");
	$('#'+activediv+'').find('.parallel').removeClass("disable");		

	if($('#'+activediv+'').hasClass('approved'))
	{
		$('#'+activediv+'').find('.closewell').addClass("activeclose");
		$('#'+activediv+'').css("border-color",'#75DAA3');	
	}
	else if($('#'+activediv+'').hasClass('disapproved'))
	{
		$('#'+activediv+'').find('.closewelld').addClass("activeclose");
		$('#'+activediv+'').css("border-color",'#FF8181');
	}
	else if($('#'+activediv+'').hasClass('parallelbranch'))
	{
		// console.log("check close");
		$('#'+activediv+'').find('.closewellpar').addClass("activeclose");
		$('#'+activediv+'').css("border-color",'#F0CFC5');
	}

	$('#disapproved'+tempcount+''+tempcount+''+appcount+'').hide();
	$('#disapproved'+tempcount+''+tempcount+''+appcount+'').children('.clickline').each(function(index, element) {
            $(this).removeClass("active");
        });
	//$('#disapproved'+tempcount+''+tempcount+''+appcount+'').children('#close'+tempcount+'').removeClass("activeclose");
	//$('#disapproved'+tempcount+''+tempcount+''+appcount+'').css("border-color",'#FF8181');//FFC0C0
	}
})

//````
$(document).on('click','.parallel',function (e)
{
	//appcount=1;
	if(!$(this).hasClass("disable"))
	{
	var bran_id=$(this).attr('id');
	var branchcount=bran_id.replace(/\D/g,'');
	$('.prop').attr('disabled','disabled');
	$('.conprop').attr('disabled','disabled');
	$('.removediv').attr('disabled','disabled');
	$('.apiprop').attr('disabled','disabled');
	$('.app').addClass("disable");
	$('.disapp').addClass("disable");
	$('.parallel').addClass("disable");		
	// console.log("clicked approved")
	var mainid=$(this).parents('.parmain').attr('id');
	// console.log(mainid);
	tempcount = mainid.replace(/\D/g,'');
	// console.log("mainnnnnnn"+tempcount);
	$('#branch'+tempcount+'').hide();
	
	if($('#parallelbranch'+branchcount+'').css('display') == 'none')
	{
		$('#parallelbranch'+branchcount+'').show();
		$('.clickline').removeClass("active");
		$('.well').css("border-color",'#ddd');
		$('#parallelbranch'+branchcount+'').css("border-color",'#F0CFC5');//ACFCD2-75DAA3-C7FFE1
		$('#parallelbranch'+branchcount+'').children('.clickline').each(function(index, element) {
            $(this).addClass("active");

        });
			$('.closewell').removeClass("activeclose");
			$('.closewelld').removeClass("activeclose");
			$('.closewellpar').removeClass("activeclose");
			//$('#approved'+tempcount+''+appcount+'').children('#close'+tempcount+'').addClass("activeclose");
			$('#parallelbranch'+branchcount+'').find('.closewellpar').addClass("activeclose");
			$('#parallelbranch'+branchcount+'').find('.prop').removeAttr("disabled");
			$('#parallelbranch'+branchcount+'').find('.conprop').removeAttr("disabled");
			$('#parallelbranch'+branchcount+'').find('.removediv').removeAttr("disabled");
			$('#parallelbranch'+branchcount+'').find('.app').removeClass("disable");
			$('#parallelbranch'+branchcount+'').find('.disapp').removeClass("disable");
			$('#parallelbranch'+branchcount+'').find('.parallel').removeClass("disable");		
			
	}
	else
	{	
		$('.closewell').removeClass("activeclose");
		$('.closewelld').removeClass("activeclose");
		$('.closewellpar').removeClass("activeclose");
		$('.clickline').removeClass("active");
		$('<div id="parallelbranch'+branchcount+'" class="well well-lg parallelbranch"><div id="clickline'+branchcount+'" class="clickline active"><div id="line'+branchcount+'" class="vertical-line"></div></div><div class="pull-right"><div id="close'+tempcount+'" class="btn btn-default btn-xs closewellpar activeclose">close</div></div></div>').appendTo('#div'+tempcount+'');
		$('.well').css("border-color",'#ddd');
		$('#parallelbranch'+branchcount+'').css("border-color",'#F0CFC5');
	}
	}
})

$(document).on('click','.closewellpar',function (e)
{
	if($(this).hasClass("activeclose"))
	{
	//appcount=1;
	var closediv=$(this).parents('.parallelbranch').attr('id');
	// console.log("clicked closewellpar");
	var mainid=$(this).attr('id');
	// console.log(mainid);
	tempcount = mainid.replace(/\D/g,'');
	// console.log("mainnnnnnn"+tempcount);
	$('#branch'+tempcount+'').show();
	$('.closewellpar').removeClass("activeclose");
	var activediv=$('#branch'+tempcount+'').parent('div').parent('div').attr('id');
	// console.log("~~~~~~~~~~~~~~~",'#'+activediv+'');
	$('#'+activediv+'').children('.clickline').addClass("active");
	$('#'+activediv+'').find('.apiprop').removeAttr("disabled");
	$('#'+activediv+'').find('.prop').removeAttr("disabled");
	$('#'+activediv+'').find('.conprop').removeAttr("disabled");
	$('#'+activediv+'').find('.removediv').removeAttr("disabled");
	$('#'+activediv+'').find('.app').removeClass("disable");
	$('#'+activediv+'').find('.disapp').removeClass("disable");
	$('#'+activediv+'').find('.parallel').removeClass("disable");		
	if($('#'+activediv+'').hasClass('approved'))
	{
		$('#'+activediv+'').find('.closewell').addClass("activeclose");
		$('#'+activediv+'').css("border-color",'#75DAA3');	
	}
	else if($('#'+activediv+'').hasClass('disapproved'))
	{
		$('#'+activediv+'').find('.closewelld').addClass("activeclose");
		$('#'+activediv+'').css("border-color",'#FF8181');
	}
	else if($('#'+activediv+'').hasClass('parallelbranch'))
	{
		// console.log("check close");
		$('#'+activediv+'').find('.closewellpar').addClass("activeclose");
		$('#'+activediv+'').css("border-color",'#F0CFC5');
	}
	$('#'+closediv+'').hide();//$('#parallelbranch'+tempcount+''+appcount+'')
	$('#'+closediv+'').children('.clickline').each(function(index, element) {
            $(this).removeClass("active");
        });
	}
})

$("body").on("click",".removediv", function(e)//user click on remove text
	{
		e.preventDefault();
		e.stopPropagation();
         if(count > 0 ) 
		 {
			 if($(this).hasClass("mainnvrr"))
			 {
				 var divvid= $(this).attr('id');
				 var tmpcnt = divvid.replace(/\D/g,''); 
				 $('#parallel'+tmpcnt+'').remove();
				 $('#hor-line-left'+tmpcnt+'').remove();
				 $('#ver-line-'+tmpcnt+'').remove();
				 $('#hor-line-left-bottom'+tmpcnt+'').remove();
				 $('#ver-line-bottom'+tmpcnt+'').remove();
			 }
			 else
			 {
				checkempty--;
				var deldiv= $(this).attr('id');
				var tmpcnt = deldiv.replace(/\D/g,'');	
				$('#div'+tmpcnt+'').remove(); //remove text box
				$('#line'+tmpcnt+'').remove();
			 }
		 }
	})

$("body").on("click",".removedivv", function(e)//user click on remove text
	{
			
			flagval=0;
			checkempty--;
			var deldiv= $(this).attr('id');
			var tmpcnt = deldiv.replace(/\D/g,'');	
			$('#div'+tmpcnt+'').remove(); //remove text box
			//$('.startup').remove();
			$('#line'+tmpcnt+'').remove();
		
	})
	
	//&&&&&&&&
	var tmpcnt='';
	var index_s=0;
	$(document).on("click",'#jsonsave', function(e)
		{ 
		tDef = {};
		// console.log("kkkkkkkkkkkkkkkkkkkkkk");
		//if(checkempty>0)
		//{
				
		 var firstid = $('#dynamicdiv').children('.create').first().attr('id')
		 console.log("sssssssfirstidfirstidssssssssssssssss",firstid);
		 var sib = $('#dynamicdiv').children('.create').length;
		 console.log("sssssssssssssssibsibsibsssssssss",sib);
		 var nxtall = $('#dynamicdiv').nextAll();
		 $('#dynamicdiv').children('.create').each(function()
		 {
			 index_s++;
			 var divvid= $(this).attr('id');
			 tmpcnt = divvid.replace(/\D/g,'');
			 if($(this).children('div').hasClass("startups"))
			 {
				var stageid=$(this).attr('id');
				stage(stageid,index_s);
			 }
			 else if($(this).children('div').hasClass("api"))
			 {
				var apiid=$(this).attr('id');
				api(apiid,index_s);
			 }			 
			 else if($(this).children('div').hasClass("conmain"))
			 {
 			 	var conid=$(this).children('.conmain').attr('id');
				console.log('#########',conid)
				condition(conid,index_s);

			 }
			 else if($(this).children('div').hasClass("parmain"))
			 {
				var paralid=$(this).children('.parmain').attr('id');
			 	branching(paralid,index_s);
			 }
			 
			 
		 });
		//}//check empty
		
				var jsonval=$('#wrkjdata').val();
				console.log(jsonval);
				console.log(draft_trigger);
				if(jsonval!='' && draft_trigger=="false")
				{
				$.ajax({
						url: 'getpost',
						type: 'POST',
						data:{'jsonval':jsonval,'app_id':app_con,'comp_name':comp_name,'comp_addr':comp_addr,'workflow_mode':workflow_mode},
						
						success: function(data)
						{
							window.location.href = 'todashnew?app_id=' + app_con + '&comp_name=' + comp_name + '&comp_addr=' + comp_addr + '&app_name=' + app_name + '&app_des=' + app_des + '&app_mod=' + app_mod;
						},
					});   
				}
				else
				{
					
				}	

})//save workflow


//
//functions for creating the json
//

function stage(stageid,index_s)// single stage
	 {
		console.log("stageidstageidstageidstageidstageidstageidstageidstageid")
		var viewperms='';
		var usrlst=[];
		var grouplst = [];
		var epermlst=[];
		var vpermlst=[];
		var lablname=$('#'+stageid+'').find('label').html();
		var stagetyp=$('#'+stageid+'').find('label').attr('stagetype');
		var grouplist=$('#'+stageid+'').find('label').attr('grups');
		var userslist=$('#'+stageid+'').find('label').attr('usrs');
		var editperms=$('#'+stageid+'').find('label').attr('editper');
		var pre_name = $('#'+stageid+'').find('label').attr('previous');
		viewperms=$('#'+stageid+'').find('label').attr('viewper');
		var printval=$('#'+stageid+'').find('label').attr('print');
		var smsval=$('#'+stageid+'').find('label').attr('sms');
			if(userslist!=null && userslist!=undefined && userslist!='')
			{
			var usrval=userslist.split(",");
			for(var i in usrval)
			{
				usrlst.push(usrval[i]);
			}
			}
			
			if(grouplist!=null && grouplist!=undefined && grouplist!='')
			{
			var grpval=grouplist.split(",");
			for(var i in grpval)
			{
				grouplst.push(grpval[i]);
			}
			}
			
			if(editperms!=null && editperms!=undefined && editperms!='')
			{
			var epermval=editperms.split(",");
			for(var i in epermval)
			{
				epermlst.push(epermval[i]);
			}
			}
				
			if((viewperms!=null) && (typeof viewperms!=undefined) && (viewperms!=''))
			{
			var vpermval=viewperms.split(",");
			for(var i in vpermval)
			{
				vpermlst.push(vpermval[i]);
			}
			}

	  tAdd_Stage(lablname,usrlst,stagetyp,epermlst,vpermlst,index_s,printval,smsval,grouplst);
	 }//
			 
function api(apiid,index_s)// api stage
	 {
		var usrlst=[];
		var vpermlst=[];
		var stagetyp=$('#'+apiid+'').find('label').attr('stagetype');
		var userslist=$('#'+apiid+'').find('label').attr('usrs');
		var editperms=$('#'+apiid+'').find('label').attr('comid');
		var viewperms=$('#'+apiid+'').find('label').attr('viewper');
		if(userslist!=null && userslist!=undefined && userslist!='')
		{
			var usrval=userslist.split(",");
			for(var i in usrval)
			{
			
				usrlst.push(usrval[i]);
			}
		}
			
			if(viewperms!=null && viewperms!=undefined && viewperms!='')
			{
			var vpermval=viewperms.split(",");
			for(var i in vpermval)
			{
				
				vpermlst.push(vpermval[i]);
			}
			}
			
			
		var apiname;
		apiname='api'+tmpcnt;
		var comcol=editperms.toLowerCase();
	
		var col_name=comcol.replace(" ","_");

	tAdd_Stage_api(apiname,usrlst,stagetyp,col_name,vpermlst,index_s);
	 
	 }			 

function condition(conid,index_s)//conditional branching
			 {		
					 console.log("conidconidconidconidconidconidconidconidconidconidconid")
			 		 var element=$('#cond'+tmpcnt+'').attr('elementlist');
					 var operator=$('#cond'+tmpcnt+'').attr('opr');
					 var value=$('#cond'+tmpcnt+'').attr('vval');
					 var condname;
					 condname='condition'+tmpcnt+''
					 tAdd_Stage_cond(condname, element, operator, value)
					 var sub_index=1;//index_s;
				     var app_sub_index=sub_index;
				     var dis_app_sub_index=sub_index;
				 //$('.crdiv'+tmpcnt+'').children('.approved').
				 console.log("ttttttttttt",tmpcnt)
				 var lenapp = $('.crdiv'+tmpcnt+'').children('.approved').children('.create').length;
				 $('.crdiv'+tmpcnt+'').children('.approved').children('.create').each(function()//approve
		 			{
						//condition_history.push("approved");
						if(condition_history[condition_history.length-1] != "approved")
						{
						condition_history.push("approved");
						}
						console.log("lllllllllleeeeeeaaaaaaaaaaaaa",lenapp)
						var chrdivvid= $(this).attr('id');
						var chrtmpcnt = chrdivvid.replace(/\D/g,'');
						console.log("iiiiiididididididididi",chrdivvid);	
							if($(this).children('div').hasClass("startups"))
							{
								console.log("sssssssssssssssssiiiiiiiiiiiiiiiiiiiiinnnnnnnnggggggggglllll");
								var usrlst=[];
								var epermlst=[];
								var vpermlst=[];
								var lablname=$(this).find('label').html();
								var stagetyp=$(this).find('label').attr('stagetype');
								var userslist=$(this).find('label').attr('usrs');
								var editperms=$(this).find('label').attr('editper');
								viewperms=$(this).find('label').attr('viewper');
								var printval=$(this).find('label').attr('print');
								if(userslist!=null && userslist!=undefined && userslist!='')
								{
								var usrval=userslist.split(",");
								for(var i in usrval)
								{
									usrlst.push(usrval[i]);
								}
								}
								if(editperms!=null && editperms!=undefined && editperms!='')
								{
								var epermval=editperms.split(",");
								for(var i in epermval)
								{
									epermlst.push(epermval[i]);
								}
								}
								if(viewperms!=null && viewperms!=undefined && viewperms!='')
								{
								var vpermval=viewperms.split(",");
								for(var i in vpermval)
								{
									vpermlst.push(vpermval[i]);
								}
								}
								var index_app=''+index_s+'.'+app_sub_index+'';
								index_app=parseFloat(index_app)
								lenapp = lenapp-1;
								console.log("lllllllllleeeeeeaaaaaaaaaaaaa",lenapp)
								tAdd_Stage_approve(lablname,usrlst,stagetyp,epermlst,vpermlst,index_app,printval);
								if(lenapp == 0){condition_history.pop();}
								//console.log("hhhhhhhhhhhhhhhhhiiiiiiiiiiisssssssssssssssssttttttttttoooooorrrrrrrrryyyyyyyyy",condition_history);
								app_sub_index++;
							}//end if single stage
							if($(this).children('div').hasClass("conmain"))
							{
								console.log("cccccccccccooooooooonnnnnddddddddddd");
								var conid=$(this).children('.conmain').attr('id');
								console.log('#########',conid)
								var divvid= $(this).attr('id');
								tmpcnt = divvid.replace(/\D/g,'');
								condition(conid,index_s);
								//console.log("hhhhhhhhhhhhhhhhhiiiiiiiiiiisssssssssssssssssttttttttttoooooorrrrrrrrryyyyyyyyy",condition_history);
								lenapp = lenapp-1;
								if(lenapp == 0){condition_history.pop();}
								console.log("lllllllllleeeeeeaaaaaaaaaaaaa",lenapp)
							}							
							if($(this).children('div').hasClass("parmain"))
							{
								var paralid=$(this).children('.parmain').attr('id');
								branching(paralid,index_s);
								lenapp = lenapp-1;
								if(lenapp == 0){condition_history.pop();}
							}
							

					})
				 var get_id_disapproved = condition_history[condition_history.length-1];
				 var tmp_cnt = get_id_disapproved.replace(/\D/g,'');
				 var lendisapp = $('.crdiv'+tmp_cnt+'').children('.disapproved').children('.create').length;
				 if(lendisapp == 0)
				 {
				    if(condition_history[condition_history.length-1] != "disapproved" && "approved")
					{
						condition_history.pop();
					}
				 }
				 console.log("ttttttttttt",tmp_cnt)		 
				 $('.crdiv'+tmp_cnt+'').children('.disapproved').children('.create').each(function()//disapprove
		 			{
						//condition_history.push("disapproved");
						if(condition_history[condition_history.length-1] != "disapproved")
						{
						condition_history.push("disapproved");
						}
						console.log("llllllllleeeeeeennnnnddddddddddiiiiiisssssssppp",lendisapp);
						var chrdivvid= $(this).attr('id');
						var chrtmpcnt = chrdivvid.replace(/\D/g,'');
						console.log("iiiiiididididididididi",chrdivvid);
						
						if($(this).children('div').hasClass("startups"))
							{
								var usrlst=[];
								var epermlst=[];
								var vpermlst=[];
								var lablname=$(this).find('label').html();
								var stagetyp=$(this).find('label').attr('stagetype');
								var userslist=$(this).find('label').attr('usrs');
								var editperms=$(this).find('label').attr('editper');
								var printval=$(this).find('label').attr('print')
								viewperms=$(this).find('label').attr('viewper');
								if(userslist!=null && userslist!=undefined && userslist!='')
								{
								var usrval=userslist.split(",");
								for(var i in usrval)
								{
									usrlst.push(usrval[i]);
								}
								}

								if(editperms!=null && editperms!=undefined && editperms!='')
								{
								var epermval=editperms.split(",");
								for(var i in epermval)
								{
									epermlst.push(epermval[i]);
								}
								}
								if(viewperms!=null && viewperms!=undefined && viewperms!='')
								{
								var vpermval=viewperms.split(",");
								for(var i in vpermval)
								{
									vpermlst.push(vpermval[i]);
								}
								}
							 var index_app=''+index_s+'.'+dis_app_sub_index+'';
							 index_app=parseFloat(index_app)
							 tAdd_Stage_disapprove(lablname,usrlst,stagetyp,epermlst,vpermlst,index_app,printval);
							 dis_app_sub_index++;
							 lendisapp = lendisapp-1;
							 if(lendisapp == 0){condition_history.pop();condition_history.pop();}
							 //console.log("hhhhhhhhhhhhhhhhhiiiiiiiiiiisssssssssssssssssttttttttttoooooorrrrrrrrryyyyyyyyy",condition_history);
							 console.log("llllllllleeeeeeennnnnddddddddddiiiiiisssssssppp",lendisapp);
						 }
						 if($(this).children('div').hasClass("conmain"))
							{
								console.log("cccccccccccooooooooonnnnnddddddddddd");
								var conid=$(this).children('.conmain').attr('id');
								console.log('#########',conid)
								var divvid= $(this).attr('id');
								tmpcnt = divvid.replace(/\D/g,'');
								condition(conid,index_s);
								//console.log("hhhhhhhhhhhhhhhhhiiiiiiiiiiisssssssssssssssssttttttttttoooooorrrrrrrrryyyyyyyyy",condition_history);
								lendisapp = lendisapp-1;
								if(lendisapp == 0){condition_history.pop();condition_history.pop();}
								console.log("lllllllllleeeeeeaaaaaaaaaaaaa",lendisapp)
							}
						 if($(this).children('div').hasClass("parmain"))
							{
								var paralid=$(this).children('.parmain').attr('id');
								branching(paralid,index_s);
								lendisapp = lendisapp-1;
								if(lendisapp == 0){condition_history.pop();condition_history.pop();}
							}
					})
				//tAdd_Stage_cond(condname, element, operator, value)
			 }
function branching(paralid,index_s)//parallel branching
	{
		var parallelname;
		tmpcnt = paralid.replace(/\D/g,'');
		parallelname='parallelname'+tmpcnt;
		var branch_names = [];
		$('.crdiv'+tmpcnt+'').children('.parallelbranch').each(function()//branches in parallel branching
		{
			var chrdivvid = $(this).attr('id')
			var chrtmpcnt = chrdivvid.replace(/\D/g,'');
			var branchnum='branchnum'+chrtmpcnt+'';
			branch_names.push(branchnum);
		})
		tAdd_Stage_parallel(parallelname,branch_names)
		var sub_index=1;
		var reset_index=sub_index;
		var tot_parllel_len = $('.crdiv'+tmpcnt+'').children('.parallelbranch').length;
		$('.crdiv'+tmpcnt+'').children('.parallelbranch').each(function()//branches in parallel branching
			{
				var chrdivvid= $(this).attr('id');
				var parllel_len = $('#'+chrdivvid+'').children('.create').length;
				var chrtmpcnt = chrdivvid.replace(/\D/g,'');
				var branchnum='branchnum'+chrtmpcnt+'';
				
				tAdd_Stage_branch(branchnum)
				sub_index=reset_index;
				
				$(this).children('.create').each(function()
				{
					if($(this).children('div').hasClass("startups"))
					{
						var usrlst=[];
						var epermlst=[];
						var vpermlst=[];
						var lablname=$(this).find('label').html();
						var stagetyp=$(this).find('label').attr('stagetype');
						var userslist=$(this).find('label').attr('usrs');
						var editperms=$(this).find('label').attr('editper');
						var printval=$(this).find('label').attr('print')
						viewperms=$(this).find('label').attr('viewper');
						if(userslist!=null && userslist!=undefined && userslist!='' && userslist!='multiselect-all')
						{
						var usrval=userslist.split(",");
						for(var i in usrval)
						{
			
							usrlst.push(usrval[i]);
						}
						}

						if(editperms!=null && editperms!=undefined && editperms!='')
						{
						var epermval=editperms.split(",");
						for(var i in epermval)
						{
						
							epermlst.push(epermval[i]);
						}
						}

						if(viewperms!=null && viewperms!=undefined && viewperms!='')
						{
						var vpermval=viewperms.split(",");
						for(var i in vpermval)
						{

							vpermlst.push(vpermval[i]);
						}
						}

						var index_branch=''+index_s+'.'+sub_index+'';
						index_branch=parseFloat(index_branch)
						parllel_len = parllel_len-1;
						tAdd_Stage_branches(lablname, usrlst, stagetyp, epermlst, vpermlst,index_branch,printval);
						sub_index++;
						if(parllel_len == 0)
						{
							
							condition_history.pop();
							console.log(condition_history);
							tot_parllel_len = tot_parllel_len-1
							if(tot_parllel_len == 0)
							{
								condition_history.pop();
							}
						}
					}
					if($(this).children('div').hasClass("conmain"))
					{
						
						var conid=$(this).children('.conmain').attr('id');
						console.log('#########',conid)
						var divvid= $(this).attr('id');
						tmpcnt = divvid.replace(/\D/g,'');
						condition(conid,index_s);
						
						parllel_len = parllel_len-1;
						if(parllel_len == 0)
						{
							condition_history.pop();
							console.log(condition_history);
							tot_parllel_len = tot_parllel_len-1
							if(tot_parllel_len == 0)
							{
								condition_history.pop();
								console.log(condition_history);
							}
						}
					}							
					if($(this).children('div').hasClass("parmain"))
					{
						var paralid=$(this).children('.parmain').attr('id');
						branching(paralid,index_s);
						parllel_len = parllel_len-1;
						if(parllel_len == 0)
						{
							console.log("ddddddddddddddlllllllllllllllllll");
							condition_history.pop();
							console.log(condition_history);
							tot_parllel_len = tot_parllel_len-1
							if(tot_parllel_len == 0)
							{
								console.log("ppppppppppppppppppppppppp");
								condition_history.pop();
								console.log(condition_history);
							}
						}
					}
				})

			})

	}

//creating json end

var workflow_draft = function()
{
$('#jsonsave').trigger("click");
var work_flow=$('#wrkjdata').val();
if(work_flow!='')
{	
	$.ajax({
			type 		: 'POST', // define the type of HTTP verb we want to use (POST for our form)
			url 		: 'workflow_stage_draft', // the url where we want to POST
			data 		: {'controller_name':app_con,'workflow':work_flow}, // our data object
		})
			// using the done promise callback
			.done(function(data) {

				var navigate_to_url = $(draftworkflow).attr("href");
	            window.location.href = navigate_to_url;
				
                 $.smallBox({
						title : "Message",
						content : "<i class='fa fa-clock-o'></i> <i>Application saved as Draft</i>",
						color : "#659265",
						iconSmall : "fa  fa-check-circle fa-2x fadeInRight animated",
						timeout : 3000
					});                                                           
				
			})
			
			.fail(function() {
            //alert( "error" );
			});
	
	}
	else
	{
		 $.smallBox({
						title : "Message",
						content : "<i class='fa fa-clock-o'></i> <i>Workflow is empty</i>",
						color : "#659265",
						iconSmall : "fa  fa-check-circle fa-2x fadeInRight animated",
						timeout : 3000
					});     
	}
}

var delete_app = function(event)
{
	
	$.ajax({
			type 		: 'POST', // define the type of HTTP verb we want to use (POST for our form)
			url 		: 'workflow_app_delete', // the url where we want to POST
			data 		: {'controller_name':app_con,'app_type':app_type}, // our data object
		})
			// using the done promise callback
			.done(function(data) {

				var navigate_to_url = $(draftworkflow).attr("href");
	            window.location.href = navigate_to_url;
				
                 $.smallBox({
						title : "Message",
						content : "<i class='fa fa-clock-o'></i> <i>Application Deleted</i>",
						color : "#006600",
						iconSmall : "fa  fa-check-circle fa-2x fadeInRight animated",
						timeout : 3000
					});                                                           
				
			})
			
			.fail(function() {
            //alert( "error" );
  });

		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();
	
}

$(document).on('click','a',function(e)
{	
 var clickedid = $(this).hasClass("menu-links");

if(clickedid)
{
	draftworkflow=$(this);
	draft_trigger="true"
	console.log(draft_trigger);
 
	$.SmartMessageBox({
				title : "Alert !",
				content : "Workflow is not yet created. This application will be incomplete. Press Yes to save this app as draft or press No to delete this app.",
				buttons : '[Cancel][No][Yes]'
			}, function(ButtonPressed) {
				if (ButtonPressed === "Yes") 
				{
					workflow_draft();
				}
				if (ButtonPressed === "No")
				{
					delete_app();
				}
				if (ButtonPressed === "Cancel")
				{
				
				}
				
	       });
			e.preventDefault();
}
})

});//end document on ready....
// JavaScript Document


