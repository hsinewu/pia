@section('footer_scripts')
    @parent
    <script>
    $(document).ready(function(){
        var level_dom = $('#level');
        var hidden_input = level_dom.find('input[type=hidden]');
        var val = parseInt(hidden_input.attr('value'));
        //if(val & 1)  $('#checkbox_1').prop("checked", true);
        //if(val & 2)  $('#checkbox_2').prop("checked", true);
        //if(val & 4)  $('#checkbox_4').prop("checked", true);

        function onChkboxClick(){
            var this_e = $(this);
            var this_val = parseInt(this_e.attr('value'));
            if(this_e.prop("checked")==true) val += this_val;
            else val -= this_val;
            hidden_input.attr('value', val);
        }

        level_dom.find("input[type=checkbox]").each(function(i,v){
            var this_e = $(v);
            var ele_val = parseInt(this_e.attr('value'));
            if(val & ele_val) this_e.prop("checked", true);
            this_e.click(onChkboxClick);
        });
        // for(var i=1; i<=4; i*=2){
        //     if(val & i)  $('#checkbox_'+i).prop("checked", true);
        // }
        // $('.level').click(function(){
        //     if($(this).prop("checked")==true) val+=parseInt($(this).attr('value'));
        //     else  val-=parseInt($(this).attr('value'));
        //     $('#level').find('input').first().attr('value', val);
        //     //console.log(val);
        // });
    });
    </script>
@stop

<?php
  $select_arr = (new PiaPerson())->getLevel_key_value();
?>
<div id="level">
<input type="hidden" name="{{ $name }}" value="{{ $value ? $value : 0 }}"/>
  @foreach($select_arr as $opt)
    <div class="col-md-6 row">
      <input type="checkbox" class="level" value="{{ $opt->value }}"> {{$opt->text}}
    </div>
  @endforeach
</div>
