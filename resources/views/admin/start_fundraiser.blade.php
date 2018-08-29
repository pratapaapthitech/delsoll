@extends('layouts.app')

@section('title') @if(! empty($title)) {{$title}} @endif - @parent @endsection

@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datetimepicker.css')}}">
@endsection

@section('content')


    <div class="dashboard-wrap">
        <div class="container">
            <div id="wrapper">

                @include('admin.menu')

                <div id="page-wrapper">

                    @if( ! empty($title))
                        <div class="row">
                            <div class="col-lg-12">
                                <h1 class="page-header"> {{ $title }}  </h1>
                            </div> <!-- /.col-lg-12 -->
                        </div> <!-- /.row -->
                    @endif

                    @include('admin.flash_msg')

                    <div class="row">
                        <div class="col-md-10 col-xs-12">

                            {{ Form::open(['id'=>'startFundraiserForm', 'class' => 'form-horizontal', 'files' => true]) }}
							
                            <legend>@lang('app.fundraiser_info')</legend>

                            <div class="form-group  {{ $errors->has('fund_category_id')? 'has-error':'' }}">
                                <label for="fund_category_id" class="col-sm-4 control-label">@lang('app.category') <span class="field-required">*</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" name="fund_category_id" id="fund_category_id">
                                        <option value="">@lang('app.select_a_category')</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('fund_category_id')? '<p class="help-block">'.$errors->first('fund_category_id').'</p>':'' !!}
                                </div>
                            </div>
							
							<div class="alert alert-info">
                                <h3> @lang('app.name_basic_information')</h3>
                            </div>
							
							<div class="form-group {{ $errors->has('fund_title')? 'has-error':'' }}">
                                <label for="fund_title" class="col-sm-4 control-label">@lang('app.title_name_fundraiser') <span class="field-required">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="fund_title" value="{{ old('fund_title') }}" name="fund_title" placeholder="@lang('app.title_name_fundraiser')">
                                    {!! $errors->has('fund_title')? '<p class="help-block">'.$errors->first('fund_title').'</p>':'' !!}
                                </div>
                            </div>
							
							<div class="form-group {{ $errors->has('fund_sub_title')? 'has-error':'' }}">
                                <label for="fund_sub_title" class="col-sm-4 control-label">@lang('app.sub_title_fundraiser') <span class="field-required">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="fund_sub_title" value="{{ old('fund_sub_title') }}" name="fund_sub_title" placeholder="@lang('app.sub_title_fundraiser')">
									{!! $errors->has('fund_sub_title')? '<p class="help-block">'.$errors->first('fund_sub_title').'</p>':'' !!}
                                </div>
                            </div>
							
							<div class="form-group {{ $errors->has('fund_goal_ammount')? 'has-error':'' }}">
                                <label for="fund_goal_ammount" class="col-sm-4 control-label">@lang('app.goal_amount') </label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="fund_goal_ammount" value="{{ old('fund_goal_ammount') }}" name="fund_goal_ammount" placeholder="@lang('app.goal_amount')">
                                </div>
                            </div>
							
							<div class="form-group">
                                <label for="fund_tax_exempt" class="col-sm-4 control-label">
								@lang('app.fundraiser_tax_exempt') </label>
								<div class="col-sm-8">
                                    <input type="checkbox" name="fund_tax_exempt"  id="fund_tax_exempt">
                                </div>
                            </div>

							<div class="alert alert-info">
                                <h3> @lang('app.donor_access')</h3>
                            </div>
							
							<div class="form-group">
                                <label for="fund_begin_type" class="col-sm-4 control-label">@lang('app.fundraiser_begin')</label>
                                <div class="col-sm-8">
                                    <label>
                                        <input type="radio" name="fund_begin_type"  value="1" onclick="showHideDiv(1);" @if( ! old('fund_begin_type') || old('fund_begin_type') == '1') checked="checked" @endif > @lang('app.start_immediately')
                                    </label> <br />
                                    <label>
                                        <input type="radio" name="fund_begin_type" value="2"  onclick="showHideDiv(2);" @if(old('fund_begin_type') == '2') checked="checked" @endif > @lang('app.start_date_specify')
                                    </label>
                                </div>
                            </div>
							
							<div class="form-group" id="begin_date_div" style="display:none">
                                <label for="fund_begin_date" class="col-sm-4 control-label">@lang('app.select_date_specify') </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="fund_begin_date" value="{{ old('fund_begin_date') }}" name="fund_begin_date" placeholder="@lang('app.select_date_specify')">
                                </div>
                            </div>
							
							<div class="alert alert-info">
                                <h3> @lang('app.main_images')</h3>
                            </div>
							
							<div class="form-group">
                                <div class="col-sm-12">
                                    <div class=""> @lang('app.fundraisers_images') </div>
                                </div>
                                <div class="col-sm-12">
									<input type="file" class="form-control" name="fund_logo_image" id="fund_logo_image">
                                    <p class="text-info"> <b>@lang('app.fundraisers_images_text')</b></p>
                                    <p class="text-info"> @lang('app.fundraisers_images_note')</p>
                                </div>
                            </div>
							
							<div class="form-group">
                                <div class="col-sm-12">
                                    <div class=""> @lang('app.fundraisers_banner') </div>
                                </div>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" name="fund_banner_image" id="fund_banner_image">
                                    <p class="text-info"> <b>@lang('app.fundraisers_banner_text')</b></p>
                                    <p class="text-info"> @lang('app.fundraisers_banner_note')</p>
                                </div>
                            </div>
							
							<div class="form-group">
                                <div class="col-sm-12">
                                    <div class=""> @lang('app.fundraisers_own_image') </div>
                                </div>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" name="fund_own_image" id="fund_own_image">
                                    <p class="text-info"> <b>@lang('app.fundraisers_own_image_text')</b></p>
                                    <p class="text-info"> @lang('app.fundraisers_own_image_note')</p>
                                </div>
                            </div>
							
							<div class="alert alert-info">
                                <h3> @lang('app.slider_images')</h3>
                            </div>
							
							<div class="form-group">
                                <div class="col-sm-12">
                                    <div class=""> @lang('app.fundraisers_image_slider') </div>
                                </div>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" name="fi_image_slider[]" multiple>
                                    <p class="text-info"> <b>@lang('app.fundraisers_image_slider_text')</b></p>
                                    <p class="text-info"> @lang('app.fundraisers_image_slider_note')</p>
                                </div>
                            </div>
							
							<div class="alert alert-info">
                                <h3> @lang('app.add_fileds_descriptions')</h3>
								<input type="hidden" id="hid_td_count" value="0">
                            </div>
							
							<div id="title_desc_divs">
								<div class="form-group">
									<div class="col-sm-12">
										<label for="description" class="control-label"><b>Description</b></label>
										<input type="text" class="form-control" name="fund_description_title[]" placeholder="@lang('app.fund_description_title')"><br/>
										<textarea name="fund_description_description[]" class="form-control description" rows="5"></textarea>
									</div>
								</div>
							</div>
							
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="button" class="btn btn-danger" onclick="addDescriptionFiled();">Add field</button>
                                    <button type="submit" class="btn btn-primary">@lang('app.submit_step_1')</button>
                                </div>
                            </div>

                            {{ Form::close() }}

                        </div>
                    </div>
					
                </div>

            </div>
        </div>
    </div>


@endsection

@section('page-js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datetimepicker.min.js')}}"></script>

    <script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        $(document).ready(function() {
            CKEDITOR.replaceClass = 'description';
        });

        $(function () {
            $('#fund_begin_date').datetimepicker({format: 'YYYY-MM-DD'});
        });

		function addDescriptionFiled(){
			var currentCount = parseInt($('#hid_td_count').val());
			var addDivCount = currentCount + 1;
			var addDivHtml = '<div class="form-group" id="td_'+addDivCount+'">'+
								'<div class="col-sm-12">'+
									'<label for="description" class="control-label"><b>Description</b> <a href="javascript:void(0);" onclick="removeDescriptionField('+addDivCount+')">Remove</a></label>'+
									'<input type="text" class="form-control" name="fund_description_title[]" placeholder="Title"><br/>'+
									'<textarea name="fund_description_description[]" id="description'+addDivCount+'" class="form-control" rows="5"></textarea>'+
								'</div>'+
							'</div>';
							
			$('#title_desc_divs').append(addDivHtml);
			$('#hid_td_count').val(addDivCount);
			$.getScript( "/delsol/assets/plugins/ckeditor/ckeditor.js" ).done(function() {
				CKEDITOR.replace( 'description'+addDivCount );
			});
		}
		function removeDescriptionField(divId){
			$('#td_'+divId).remove();
		}

		function showHideDiv(type){
			if(type == 1){
				$('#begin_date_div').hide();
			}else{
				$('#begin_date_div').show();
			}
		}
    </script>
@endsection@extends('layouts.app')

@section('title') @if(! empty($title)) {{$title}} @endif - @parent @endsection

@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datetimepicker.css')}}">
@endsection

@section('content')


    <div class="dashboard-wrap">
        <div class="container">
            <div id="wrapper">

                @include('admin.menu')

                <div id="page-wrapper">

                    @if( ! empty($title))
                        <div class="row">
                            <div class="col-lg-12">
                                <h1 class="page-header"> {{ $title }}  </h1>
                            </div> <!-- /.col-lg-12 -->
                        </div> <!-- /.row -->
                    @endif

                    @include('admin.flash_msg')

                    <div class="row">
                        <div class="col-md-10 col-xs-12">

                            {{ Form::open(['id'=>'startFundraiserForm', 'class' => 'form-horizontal', 'files' => true]) }}
							
                            <legend>@lang('app.fundraiser_info')</legend>

                            <div class="form-group  {{ $errors->has('fund_category_id')? 'has-error':'' }}">
                                <label for="fund_category_id" class="col-sm-4 control-label">@lang('app.category') <span class="field-required">*</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control select2" name="fund_category_id" id="fund_category_id">
                                        <option value="">@lang('app.select_a_category')</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->has('fund_category_id')? '<p class="help-block">'.$errors->first('fund_category_id').'</p>':'' !!}
                                </div>
                            </div>
							
							<div class="alert alert-info">
                                <h3> @lang('app.name_basic_information')</h3>
                            </div>
							
							<div class="form-group {{ $errors->has('fund_title')? 'has-error':'' }}">
                                <label for="fund_title" class="col-sm-4 control-label">@lang('app.title_name_fundraiser') <span class="field-required">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="fund_title" value="{{ old('fund_title') }}" name="fund_title" placeholder="@lang('app.title_name_fundraiser')">
                                    {!! $errors->has('fund_title')? '<p class="help-block">'.$errors->first('fund_title').'</p>':'' !!}
                                </div>
                            </div>
							
							<div class="form-group {{ $errors->has('fund_sub_title')? 'has-error':'' }}">
                                <label for="fund_sub_title" class="col-sm-4 control-label">@lang('app.sub_title_fundraiser') <span class="field-required">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="fund_sub_title" value="{{ old('fund_sub_title') }}" name="fund_sub_title" placeholder="@lang('app.sub_title_fundraiser')">
									{!! $errors->has('fund_sub_title')? '<p class="help-block">'.$errors->first('fund_sub_title').'</p>':'' !!}
                                </div>
                            </div>
							
							<div class="form-group {{ $errors->has('fund_goal_ammount')? 'has-error':'' }}">
                                <label for="fund_goal_ammount" class="col-sm-4 control-label">@lang('app.goal_amount') </label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="fund_goal_ammount" value="{{ old('fund_goal_ammount') }}" name="fund_goal_ammount" placeholder="@lang('app.goal_amount')">
                                </div>
                            </div>
							
							<div class="form-group">
                                <label for="fund_tax_exempt" class="col-sm-4 control-label">
								@lang('app.fundraiser_tax_exempt') </label>
								<div class="col-sm-8">
                                    <input type="checkbox" name="fund_tax_exempt"  id="fund_tax_exempt">
                                </div>
                            </div>

							<div class="alert alert-info">
                                <h3> @lang('app.donor_access')</h3>
                            </div>
							
							<div class="form-group">
                                <label for="fund_begin_type" class="col-sm-4 control-label">@lang('app.fundraiser_begin')</label>
                                <div class="col-sm-8">
                                    <label>
                                        <input type="radio" name="fund_begin_type"  value="1" onclick="showHideDiv(1);" @if( ! old('fund_begin_type') || old('fund_begin_type') == '1') checked="checked" @endif > @lang('app.start_immediately')
                                    </label> <br />
                                    <label>
                                        <input type="radio" name="fund_begin_type" value="2"  onclick="showHideDiv(2);" @if(old('fund_begin_type') == '2') checked="checked" @endif > @lang('app.start_date_specify')
                                    </label>
                                </div>
                            </div>
							
							<div class="form-group" id="begin_date_div" style="display:none">
                                <label for="fund_begin_date" class="col-sm-4 control-label">@lang('app.select_date_specify') </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="fund_begin_date" value="{{ old('fund_begin_date') }}" name="fund_begin_date" placeholder="@lang('app.select_date_specify')">
                                </div>
                            </div>
							
							<div class="alert alert-info">
                                <h3> @lang('app.main_images')</h3>
                            </div>
							
							<div class="form-group">
                                <div class="col-sm-12">
                                    <div class=""> @lang('app.fundraisers_images') </div>
                                </div>
                                <div class="col-sm-12">
									<input type="file" class="form-control" name="fund_logo_image" id="fund_logo_image">
                                    <p class="text-info"> <b>@lang('app.fundraisers_images_text')</b></p>
                                    <p class="text-info"> @lang('app.fundraisers_images_note')</p>
                                </div>
                            </div>
							
							<div class="form-group">
                                <div class="col-sm-12">
                                    <div class=""> @lang('app.fundraisers_banner') </div>
                                </div>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" name="fund_banner_image" id="fund_banner_image">
                                    <p class="text-info"> <b>@lang('app.fundraisers_banner_text')</b></p>
                                    <p class="text-info"> @lang('app.fundraisers_banner_note')</p>
                                </div>
                            </div>
							
							<div class="form-group">
                                <div class="col-sm-12">
                                    <div class=""> @lang('app.fundraisers_own_image') </div>
                                </div>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" name="fund_own_image" id="fund_own_image">
                                    <p class="text-info"> <b>@lang('app.fundraisers_own_image_text')</b></p>
                                    <p class="text-info"> @lang('app.fundraisers_own_image_note')</p>
                                </div>
                            </div>
							
							<div class="alert alert-info">
                                <h3> @lang('app.slider_images')</h3>
                            </div>
							
							<div class="form-group">
                                <div class="col-sm-12">
                                    <div class=""> @lang('app.fundraisers_image_slider') </div>
                                </div>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" name="fi_image_slider[]" multiple>
                                    <p class="text-info"> <b>@lang('app.fundraisers_image_slider_text')</b></p>
                                    <p class="text-info"> @lang('app.fundraisers_image_slider_note')</p>
                                </div>
                            </div>
							
							<div class="alert alert-info">
                                <h3> @lang('app.add_fileds_descriptions')</h3>
								<input type="hidden" id="hid_td_count" value="0">
                            </div>
							
							<div id="title_desc_divs">
								<div class="form-group">
									<div class="col-sm-12">
										<label for="description" class="control-label"><b>Description</b></label>
										<input type="text" class="form-control" name="fund_description_title[]" placeholder="@lang('app.fund_description_title')"><br/>
										<textarea name="fund_description_description[]" class="form-control description" rows="5"></textarea>
									</div>
								</div>
							</div>
							
                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <button type="button" class="btn btn-danger" onclick="addDescriptionFiled();">Add Fields</button>
                                    <button type="submit" class="btn btn-primary">@lang('app.submit_step_1')</button>
                                </div>
                            </div>

                            {{ Form::close() }}

                        </div>
                    </div>
					
                </div>

            </div>
        </div>
    </div>


@endsection

@section('page-js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datetimepicker.min.js')}}"></script>

    <script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        $(document).ready(function() {
            CKEDITOR.replaceClass = 'description';
        });

        $(function () {
            $('#fund_begin_date').datetimepicker({format: 'YYYY-MM-DD'});
        });

		function addDescriptionFiled(){
			var currentCount = parseInt($('#hid_td_count').val());
			var addDivCount = currentCount + 1;
			var addDivHtml = '<div class="form-group" id="td_'+addDivCount+'">'+
								'<div class="col-sm-12">'+
									'<label for="description" class="control-label"><b>Description</b> <a href="javascript:void(0);" onclick="removeDescriptionField('+addDivCount+')">Remove</a></label>'+
									'<input type="text" class="form-control" name="fund_description_title[]" placeholder="Title"><br/>'+
									'<textarea name="fund_description_description[]" id="description'+addDivCount+'" class="form-control" rows="5"></textarea>'+
								'</div>'+
							'</div>';
							
			$('#title_desc_divs').append(addDivHtml);
			$('#hid_td_count').val(addDivCount);
			$.getScript( "/delsol/assets/plugins/ckeditor/ckeditor.js" ).done(function() {
				CKEDITOR.replace( 'description'+addDivCount );
			});
		}
		function removeDescriptionField(divId){
			$('#td_'+divId).remove();
		}

		function showHideDiv(type){
			if(type == 1){
				$('#begin_date_div').hide();
			}else{
				$('#begin_date_div').show();
			}
		}
    </script>
@endsection