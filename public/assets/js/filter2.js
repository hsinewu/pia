$(document).ready(function(){
	//Issue: disable filter selector(html part) when is not relevant
	//ex: admin/person page
	var rows = $('tr')
	var select = 'ALL'	//default, consider using Session::
	$('#filter-event').change(function(){
		select = $(this).value()
	})
	$('#filter').keyup(function(){
		var target = document.getElementById('filter').value
		for(var i=1; i<rows.length; i++){
			var row = rows.eq(i)
			var cells = row.find('td')
			var visible = false, eventFlag = false
			for(var j=0; j < cells.length-1; j++){
				var text = cells.eq(j).text()
				//content filter
				if(text.match(target) != null)
					visible = true
				//event filter
				if(select=='ALL' || text.match(select))
					eventFlag = true
			}
			if(visible && eventFlag){
				row.hide()
			}else{
				row.show()
			}
		}
	})
})