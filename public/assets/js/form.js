var debug;

$(document).ready(function(){
    $('button[type=submit]').click(function(){
        var submitBtn = $(this);
        var oritext = submitBtn.text();
        submitBtn.addClass('disabled').text("送出中...");
        var form = $(this).parents('form');
        var data = form.serializeArray();
        if(submitBtn.attr("name"))
            data.push({name:submitBtn.attr("name"),value:submitBtn.val()});
        jQuery.ajax(form.attr("action"),{
            method:form.attr("method"),
            data:data,
            dataType:"text",
            success:function(result){
                try{
                    result = JSON.parse(result);
                    if(result["redirect"]){
                        window.location.href = result["redirect"];
                        return;
                    }
                    for(r in result)
                        form.find("[name=" + r + "]").tooltip({title:result[r]}).tooltip("show");
                    alert("資料有誤，請檢查！");
                }catch(e){
                    console.log(e);
                    alert(result);
                }
            },
            error:function(e){
                console.log(e);
                alert("ERROR: " + e.statusText);
            },
            complete:function(){
                submitBtn.removeClass('disabled').text(oritext);
            }
        });
        return false;
    })
});
