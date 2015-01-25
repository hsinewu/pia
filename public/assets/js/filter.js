$(document).ready(function(){
  $("#filter").keyup(function(){
    var target=document.getElementById('filter').value;
    for(var i=1; i<$('tr').length; i++){
      var cur=$('tr').eq(i).find('td');
      var flag=0;
      for(var j=0; j<cur.length-1; j++){
        if(cur.eq(j).text().match(target)!=null)
          ++flag;
      }
      if(flag==0) cur.css('display', 'none');
      else  cur.css('display', '');
    }
  });
});
