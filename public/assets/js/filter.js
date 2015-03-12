var debug;
$(document).ready(function(){
	//Issue: disable filter selector(html part) when is not relevant
	//ex: admin/person page
	var rows = $('tr:not(.sub_item)')
	var select = 'ALL'	//default, consider using Session::
	var target = "";
	function filter(){
		for(var i=1; i<rows.length; i++){
			var row = rows.eq(i);
			var text = row.text();
            if( text.match(target) && (select == 'ALL' || text.match(select)))
                row.show();
            else{
                row.hide();
				var expand = row.find('.expand');
				if(expand.is('a'))
					$("." + expand.attr('for')).removeClass("showing");
			}
		}
    }
	$('#filter-event').change(function(){
		select = $(this).val();
		filter();
	});
	$('#filter').keyup(function(){
		target = document.getElementById('filter').value;
        filter();
	});

	$('.expand').click(function(){
		$("." + $(this).attr('for')).toggleClass("showing");
		return false;
	});
})
