@extends('layouts.admin')

@section('page_heading',trans('messages.area_form'))

@section('content')
<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'E')) {
    die('You have no access right! For more information please contact system admin.');
}
?>
<div class="col-lg-12">

    @include('partials.messages')

    <div class="panel panel-primary">
        <div class="panel-heading">
            <b> {!!trans('messages.area_edit')!!} </b>
        </div><!-- /.panel-heading -->

        <?php
//        echo $data->pare_id.$data->division_id;;
        $district_id = $division_id = null;
        if ($data->area_type == 2) { // for district
            $division_id = $data->pare_id;
        } elseif ($data->area_type == 3) { //for thana
            $district_id = $data->pare_id;
            $division_id = $data->division_id;
//            $division = Area::where('area_id', $district_id)->pluck('pare_id');
        }
        ?>


        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/update-area/'.$id,'method' => 'patch', 'class' => 'form-horizontal', 'id' => 'area-info',

            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

            <div class="form-group col-md-12 {{$errors->has('area_type') ? 'has-error' : ''}}">
                {!! Form::label('area_type','Area Type: ',['class'=>'col-md-3 control-label']) !!}
                <div class="col-md-5">
                    <label>{!! Form::radio('area_type',  3,  $data->area_type == 3, ['class' => ' required area_type']) !!} Thana  </label>&nbsp;&nbsp;
                    <label>{!! Form::radio('area_type', 2, $data->area_type == 2, ['class' => 'required area_type']) !!} District </label>&nbsp;&nbsp;
                    <label> {!! Form::radio('area_type', 1,  $data->area_type == 1, ['class' => 'required area_type']) !!} Division</label>

                    {!! $errors->first('area_type','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('division') ? 'has-error' : ''}}" id="division_div">
                {!! Form::label('division','Division: ',['class'=>'col-md-3 control-label']) !!}
                <div class="col-md-5">
                    {!! Form::select('division', $divisions, $division_id, ['class' => 'form-control required']) !!}
                    {!! $errors->first('division','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('district') ? 'has-error' : ''}}"  id="district_div">
                {!! Form::label('district','District: ',['class'=>'col-md-3 control-label']) !!}
                <div class="col-md-5">
                    {!! Form::select('district', [], $district_id, ['class' => 'form-control required']) !!}
                    {!! $errors->first('district','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('area_nm') ? 'has-error' : ''}}">
                {!! Form::label('area_nm','Area Name (English): ',['class'=>'col-md-3 control-label required-star']) !!}
                <div class="col-md-5">
                    {!! Form::text('area_nm', $data->area_nm, ['class' => 'form-control required']) !!}
                    {!! $errors->first('area_nm','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('area_nm_ban') ? 'has-error' : ''}}">
                {!! Form::label('area_nm_ban','Area Name (Bangla): ',['class'=>'col-md-3 control-label required-star']) !!}
                <div class="col-md-5">
                    {!! Form::text('area_nm_ban', $data->area_nm_ban, ['class' => 'form-control required']) !!}
                    {!! $errors->first('area_nm_ban','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="col-md-12">
                <a href="{{ url('/settings/area-list') }}">
                    {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                </a>
                @if(ACL::getAccsessRight('settings','E'))
                <button type="submit" class="btn btn-primary pull-right">
                    <i class="fa fa-chevron-circle-right"></i> Save</button>
                @endif
            </div><!-- /.box-footer -->

            {!! Form::close() !!}<!-- /.form end -->

            <div class="overlay" style="display: none;">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div><!-- /.box -->
    </div>
</div>

@endsection


@section('footer-script')

<script>
    var _token = $('input[name="_token"]').val();

    var age = -1;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        $("#area-info").validate({
            errorPlacement: function () {
                return false;
            }
        });

        $("#division").change(function () {
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            var divisionId = $('#division').val();
            $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url(); ?>/public/assets/images/ajax-loader.gif' alt='loading' />");
            $.ajax({
            type: "GET",
                    url: "<?php echo url(); ?>/settings/get-district-by-division-id",
                    data: {
                    divisionId: divisionId
                    },
                    success: function (response) {
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                        var district_id = 0;
                                @if ($district_id)
                                district_id = {{ $district_id }};
                            @endif
                            $.each(response.data, function (id, value) {
                                var selected = id == district_id ? 'selected="selected"' : '';
                                option += '<option value="' + id + '" ' + selected + '>' + value + '</option>';
                            });
                    }
                    $("#district").html(option);
                    self.next().hide();
                    }
        });
        });
                $('.area_type').change(function () {
            var type = $('.area_type:checked').val();
            if (type == 1) {
                $('#division_div').hide();
                $('#division').removeClass('required');
                $('#district_div').hide();
                $('#district').removeClass('required');
            }
            else if (type == 2) {
                $('#division_div').show();
                $('#division').addClass('required');
                $('#district_div').hide();
                $('#district').removeClass('required');
            }
            else if (type == 3) {
                $('#division_div').show();
                $('#division').addClass('required');
                $('#district_div').show();
                $('#district').addClass('required');
            }
        });
        $('.area_type').trigger('change');
        $("#division").trigger('change');
    });
</script>

<style>
    input[type="radio"].error{
        outline: 1px solid red
    }
</style>
@endsection <!--- footer script--->