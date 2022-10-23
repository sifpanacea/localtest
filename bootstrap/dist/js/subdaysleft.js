$.ajax({
      		url: 'dateDifference',
		    type: 'POST',
			
	  	    success: function (data) {
						
						
						div  = "";
			            user = jQuery.parseJSON(data);
					    div  = div + "<div>"+user+" days left</div>";
					    $('#subdaysleft').html(div);
				     },
            error: function (XMLHttpRequest, textStatus, errorThrown)
			{
	        
    	    }
}) 





