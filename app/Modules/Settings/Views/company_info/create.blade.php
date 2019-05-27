@extends('layouts.admin')

@section('content')
<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'A')) {
    die('You have no access right! Please contact system admin for more information');
}
?>

@include('partials.messages')

<div class="col-lg-12">

    <div class="panel panel-primary">
        <div class="panel-heading">
            <b>New Company</b>
        </div>

        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/company-store','method' => 'post', 'class' => 'form-horizontal', 'id' => 'formId',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            <div class="col-md-12">
                <div class="form-group {{$errors->has('name') ? 'has-error' : ''}}">
                    {!! Form::label('name','Name of Company: ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('company_name', null, ['class'=>'form-control textOnly required input-sm', 'placeholder' => 'Company Name']) !!}
                        {!! $errors->first('company_name','<span class="help-block" style="color:red">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group ">
                    {!! Form::label('division', 'Division:', ['class' => 'col-md-3 required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::select('division', $divisions, null, $attributes = array('class'=>'form-control required',
                      'id'=>"division")) !!}
                        {!! $errors->first('division','<p class="text-danger pss-error">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('district', 'District:', ['class' => 'col-md-3 required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::select('district', array(), '', $attributes = array('class'=>'form-control required', 'placeholder' => 'Select Division first',
                       'data-rule-maxlength'=>'40','id'=>"district")) !!}
                        {!! $errors->first('district','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('thana', 'Thana:', ['class' => 'col-md-3 required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::select('thana', array(), '', $attributes = array('class'=>'form-control required', 'placeholder' => 'Select division first',
                    'data-rule-maxlength'=>'50','id'=>"thana")) !!}
                        {!! $errors->first('thana','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <a href="{{ url('/settings/company-info') }}">
                    {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                </a>
                @if(ACL::getAccsessRight('settings','A'))
                <button type="submit" class="btn btn-success pull-right">
                    <i class="fa fa-chevron-circle-right"></i> <b>Save</b></button>
                @endif
            </div>

            {!! Form::close() !!}<!-- /.form end -->

            <div class="overlay" style="display: none;">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>

</div>
@endsection

@section('footer-script')

<script>
    var _token = $('input[name="_token"]').val();

    $(document).ready(function () {
        $("#formId").validate({
            errorPlacement: function () {
                return false;
            }
        });

        $("#division").change(function () {
            var divisionId = $('#division').val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/users/get-district-by-division",
                data: {
                    divisionId: divisionId
                },
                success: function (response) {
                    var option = '<option value="">Select district</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#district").html(option);
                    $(self).next().hide();
                }
            });
        });
        $("#district").change(function () {
            var districtId = $('#district').val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                data: {
                    districtId: districtId
                },
                success: function (response) {
                    var option = '<option value="">Select thana</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#thana").html(option);
                    $(self).next().hide();
                }
            });
        });
    });
</script>
@endsection <!--- footer script--->