<input type="hidden" id="selectedmanuid" value="{{$data["selectedid"]}}"/>
<ol class="dd-list" id="ajaxtradename-listdata">
  @foreach ($data['devicetradename'] as $tradelink )
  <li class="dd-item dd3-item" data-id="{{ $tradelink }}" id="ajaxtradelink-{{ $tradelink }}" > <span class="pull-right m-xs"> <a href="#" class="deleteajaxtradelink" data-termsdata-id="{{$tradelink}}" title="@langapp('delete')"> @icon('solid/times', 'icon-muted fa-fw m-r-xs') </a> </span>
    <div class="dd3-content">
      <label><span class="label-text"> <span class="text-info" id="ajaxtradelink-id-{{ $tradelink }}"> {{ $tradelink }} </span></span></label>
    </div>
  </li>
  @endforeach
</ol>
