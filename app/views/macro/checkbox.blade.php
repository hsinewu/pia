<?php
  $select_arr = (new PiaPerson())->getLevel_key_value();
  $i=1;
?>
<div id="level" name="{{ $name }}" value="{{$value}}">
  @foreach($select_arr as $opt)
    <div class="col-md-6 row">
      <input type="checkbox" id="checkbox_{{ $i }}" value="{{ $i }}"> {{$opt->text}}
    </div>
    <?php $i*=2; ?>
  @endforeach
</div>
