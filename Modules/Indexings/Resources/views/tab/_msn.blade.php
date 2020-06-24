<div class="row">
    <div class="col-lg-12">
        {!! Form::open(['route' =>'indexings.create', 'class' => 'bs-example form-horizontal ajaxifyForm']) !!}
        <section class="panel panel-default">
        <header class="panel-heading">
		<header class="btn btn-primary btn-sm" style="margin-left:20px;"> @icon('solid/exclamation-circle') @langapp('msntitle')  &nbsp;&nbsp;[ <span class="btn btn-warning btn-xs">MSN </span> ]</header></header>
        @php 
        $translations = Modules\Settings\Entities\Options::translations();
        $default_country = get_option('company_country');
		$disable = '';
        @endphp
		
		@if ($jobdata->publ == 'Yes' && $jobdata->abs == 'Yes')
			$disable = 'disabled';
		@endif
        <div class="panel-body">
		
		<div class="col-md-12">
            @if (count($translations) > 0)
            
            <div class="tab-content tab-content-fix">
                <div class="tab-pane fade in active" id="tab-english">
                    @endif
					
					
					<div class="col-md-6">
					
					
					<div class="form-group">
					<div class="col-lg-12">
						<label>@langapp('repositoryname')</label>
						 <select class="select2-option form-control" id="repository" name="repository">
						 <option selected="true" disabled="disabled">Select @langapp('repositoryname')</option>
                                @foreach($repositoryname as $repository)
                                <option value="{{ $repository->id }}">{{ $repository->name }}</option>
                                @endforeach
                            </select>
						</div>
					</div>
					
					<div class="form-group">
					<div class="col-lg-12">
                    <label>
                    <input type="radio" id="txtdrugmajor" name="fcttermindexing" value="1">
                    <span class="label-text text-info">A#####</span></label>
					<label>&nbsp;&nbsp;
                    <input type="radio" id="txtdrugminor" name="fcttermindexing" value="0">
                    <span class="label-text text-info">AA######</span></label>
					
					&nbsp;&nbsp;
                    <input type="radio" id="txtdrugminor" name="fcttermindexing" value="0">
                    <span class="label-text text-info">AA####</span></label>
					
					
					&nbsp;&nbsp;
                    <input type="radio" id="txtdrugminor" name="fcttermindexing" value="0">
                    <span class="label-text text-info">AA########</span></label>
					
					
					&nbsp;&nbsp;
                    <input type="radio" id="txtdrugminor" name="fcttermindexing" value="0">
                    <span class="label-text text-info">AAAAAA######</span></label>
					
					
					&nbsp;&nbsp;
                    <input type="radio" id="txtdrugminor" name="fcttermindexing" value="0">
                    <span class="label-text text-info">AAA#####</span></label>
					
					@langapp('msninfo')
                  </div>
					</div>
					
					
					
					<div class="form-group">
					<div class="col-lg-12">
						<label>@langapp('msntitle')</label>		
						<input type="text" id="txtmsntitle" class="form-control" placeholder="@langapp('msntitle')" name="txtmsntitle" disabled="disabled">
                                </div>
                     </div>
					
					<div class="form-group">
					<div class="col-lg-12">
								<div class="checkbox">
                                    <label class="padminus20">
                                        <input type="hidden" value="FALSE" name="msnrange"/>
                                        <input type="checkbox" name="msnrange" value="TRUE">
                                        <span class="label-text">@langapp('msnrange')  </span>
										
										<span><input type="text" id="txtmsnrange" class="form-control" placeholder="@langapp('msnrange')" name="txtmsnrange" disabled="disabled"></span>
                                    </label>
                                </div>
                                </div>
                     </div>
					 </div>
                    @if (count($translations) > 0)
                </div>
                
            </div>
            @endif
        </div>
		</div>
        <div class="panel-footer">
            {!!  renderAjaxButton('save')  !!}
        </div>
    </section>
    {!! Form::close() !!}
</div>

</div>

@push('pagestyle')
@include('stacks.css.form')
@endpush
@push('pagescript')
@include('stacks.js.form')

<script>
$("input[name='msnrange']").click(function () {
		if($(this).prop("checked") == true){
			$("#txtmsnrange").removeAttr("disabled");
			$("#txtmsnrange").focus();
		} else {
			$("#txtmsnrange").val('');
			$("#txtmsnrange").attr("disabled", "disabled");
		}
	});


</script>
@endpush