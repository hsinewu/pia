<?php
  $is_head = 0;
  $select_arr = Config::get('pia_report');
?>
<select class="select" name="{{ $name }}" value="{{ $value }}" data-live-search="true">
    <option value="">未選擇</option>
  @foreach($select_arr as $opt)
    @if( $is_head++ == 0)
    <optgroup label="{{$opt->value}} {{$opt->text}}">
    @elseif( strlen($opt->value)==2 )
    </optgroup>
    <optgroup label="{{$opt->value}} {{$opt->text}}">
    @else
    <option value="{{$opt->value}}">{{$opt->value}} {{$opt->text}}</option>
    @endif
  @endforeach
</select>

