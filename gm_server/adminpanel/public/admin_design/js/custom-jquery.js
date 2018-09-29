/*$(document).on("click", '.change_status', function(e) { 
	e.preventDefault();
			
	var thisBtn = this;
	
	console.log(this);
	
	var status = $(this).attr('data-status');
	var id = $(this).attr('data-id');
	var token = $('#token').val();
	var data = {'id':id,
				'status':status,
				'token':token
			  };
	 $.ajax({
	 	url: "/admin/change_tag_status/"+id, 
	 	data : $('#status_change_form_'+id).serialize(),
	 	method: "PUT",
	 	success: function(result){
	 		$('#status_'+id).val((($('#status_'+id).val()) == 0)?1:0);
        	$(thisBtn).toggleClass('btn-success btn-danger');
        		
        	$(thisBtn).text(($(thisBtn).text() == 'Active' )?'Inactive':'Active');
        	
    	}
    });
});*/