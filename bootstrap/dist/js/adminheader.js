$(document).ready(function()
{
var url=window.location.href;
var base_url;
base_url=url.substring(0, url.lastIndexOf('.'));
$.ajax({
url: base_url+'.php/dashboard/adminusername',
type: 'POST',

success: function (data) {

users=data;
div = "";
user = jQuery.parseJSON(data);
div = div + "<div>"+user+"</div>";

$('#adminusername').html(div);
},
error: function (XMLHttpRequest, textStatus, errorThrown)
{
console.log('error', errorThrown);
}
})

})//document end






