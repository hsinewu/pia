var debug;
$(document).ready(function(){
	//Issue: disable filter selector(html part) when is not relevant
	//ex: admin/person page
	var rows = $('tr')
	var select = 'ALL'	//default, consider using Session::
	var target = "";
	function filter(){
		for(var i=1; i<rows.length; i++){
			var row = rows.eq(i);
			var text = row.text();
            if( text.match(target) && (select == 'ALL' || text.match(select)))
                row.show();
            else
                row.hide();
		}
    }
	$('#filter-event').change(function(){
		select = $(this).val();
		filter();
	})
	$('#filter').keyup(function(){
		target = document.getElementById('filter').value;
        filter();
	})
})
