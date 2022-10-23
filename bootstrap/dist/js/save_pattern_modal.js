$(document).ready(function() {
$('#pattern123').on('click',function(){

                        var title = $('#pattern_title').val();
						var des = $('#pattern_description').val();
						var dataidpattern=$('#queryid').val();
						var appname = $('#appname').val();
						$('#myModal1').modal('hide');
						labelarraypattern=[];
						labelname = [];
						queryvalue=[];
						optionarray=[];
						var pattern='';
						$('#analytics').children('label').each(function(){
							var labeltext1=$(this).text();
							// console.log(labeltext1);
							var queryvalue1=$('.'+labeltext1+'').val();
							// console.log(queryvalue1);
							var opt1=$('#'+labeltext1+' option:selected').text();
							if(opt1 != ""){
							labelname.push(labeltext1);
							queryvalue.push(queryvalue1);
							optionarray.push(opt1);
					        labelarraypattern.push({labelname:labeltext1,value:queryvalue1,option:opt1});
							}else{
							labelname.push(labeltext1);
							queryvalue.push(queryvalue1);
							labelarraypattern.push({labelname:labeltext1,value:queryvalue1});}							
						});
						pattern=JSON.stringify(labelarraypattern);
						$.ajax({
						url: 'savepattern',
						type: 'POST',
						dataType:"json",
						data: {"pattern" : pattern,"dataid" : dataidpattern,"appname" : appname,"title" : title,"des" : des,"label" : labelname,"queryvalue" : queryvalue,"option" : optionarray},
						success: function (data) 
						  {
						    // console.log('success',data);
							$('#pattern').hide();
							$('#ajaxdata').html(data);
							},
						    error:function(XMLHttpRequest, textStatus, errorThrown)
							{
							 // console.log('error', errorThrown);
						    }
							});
						});
						
				
						});
					
			