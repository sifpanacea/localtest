$(document).ready(function() {
		var btnUpload=$('#me');
		new AjaxUpload(btnUpload, {
			action:'profile_pic_change',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				 // alert("submitted");
				
			},
			onComplete: function(file, response){
				//On completion clear the status
				// alert('Image imported Sucessfully!');
				//On completion clear the status
				//Add uploaded file to list 
			}
		});
		
	
});	