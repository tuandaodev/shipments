$(document).ready(function(){
	
    console.log('shipments.js loaded');
    
    $("#shipments-list-table").DataTable({
        "order": [[ 0, "desc" ]]
    });
});
