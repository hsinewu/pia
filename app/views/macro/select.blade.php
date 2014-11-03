<?php
  switch ($type) {
    case 'dept':
      $select_arr = (new PiaDept())->gen_key_value();
      break;
    case 'person':
      $select_arr = (new PiaPerson())->gen_key_value();
      break;
    case 'event':
      $select_arr = (new PiaEvent())->gen_key_value();
      break;
  }
?>
<select class="selectpicker" name="{{ $name }}" value="{{ $value }}" data-live-search="true">
    <option value="">未選擇</option>
  @foreach($select_arr as $opt)
    <option value="{{$opt->value}}">{{$opt->text}}</option>
  @endforeach
</select>

