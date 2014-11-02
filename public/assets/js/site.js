$(document).ready(function(){
  var small = false
  // $('div.jumbotron div.navbar').hide();
  $( window ).scroll(function(){
    // console.log($('body').scrollTop());
    if(small != ($(document).scrollTop() > 50)){
      small = $(document).scrollTop() > 50;
      if (small) {
        $('div.jumbotron').addClass('small');
        // $('div.jumbotron div.navbar').slideDown();
      }
      else{
        $('div.jumbotron').removeClass('small');
        // $('div.jumbotron div.navbar').slideUp();
      }
    }
  });
});
