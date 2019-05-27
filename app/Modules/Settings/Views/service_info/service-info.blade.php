@extends('layouts.admin')
@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('user');
    if (!ACL::isAllowed($accessMode, 'V'))
        die('no access right!');
    ?>
    <div class="col-lg-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane table-responsive active" id="tab_1">
                    <br>

                    <div class="col-lg-12">
                        @include('partials.messages')
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="pull-left">
                                    <h5><i class="fa fa-list"></i> {!! trans('messages.service_list') !!}</h5>
                                </div>
                                <div class="pull-right">
                                    <a class="" href="{{ url('/settings/create-service-info-details') }}">
                                        {!! Form::button('<i class="fa fa-plus"></i><b> ' .trans('messages.new_service').'</b>', array('type' => 'button', 'class' => 'btn btn-info')) !!}
                                    </a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Type Name</th>
                                            <th>Details</th>
                                            <th>Terms And Conditions</th>
                                            <th>status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 1; ?>
                                        @foreach($getList as $row)
                                            <tr>
                                                <td>{!! $i++ !!}</td>
                                                <td>{!! $row->name !!}</td>
                                                <td>{!!($row->description) !!}</td>
                                                <td>{!!($row->terms_and_conditions) !!}</td>
                                                <td>
                                                    @if($row->status = '1')
                                                        <span class="text-success">Active</span>
                                                    @else
                                                        <span class="text-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{--@if(ACL::isAllowed($accessMode,'E'))--}}
                                                    @if(ACL::getAccsessRight('settings','E'))
                                                        <a href="{!! url('settings/edit-service-info-details/'. Encryption::encodeId($row->id)) !!}" class="btn btn-xs btn-primary">
                                                            <i class="glyphicon glyphicon-edit"></i> Edit
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div><!-- /.table-responsive -->
                            </div><!-- /.panel-body -->
                        </div><!-- /.panel -->
                    </div><!-- /.col-lg-12 -->

                </div>

            </div>
        </div>
    </div>
@endsection
@section('footer-script')

    <link rel="stylesheet" href="{{ asset("assets/stylesheets/bootstrap3-wysihtml5.min.css") }}">
    <script src="{{ asset("assets/scripts/bootstrap3-wysihtml5.all.min.js") }}" type="text/javascript"></script>

    <script>
        $(document).ready(function(){
            $(function () {
                var _token = $('input[name="_token"]').val();
                $("#reg_form").validate({
                    errorPlacement: function () {
                        return false;
                    }
                });
                var _token = $('input[name="_token"]').val();
                $("#reg_form1").validate({
                    errorPlacement: function () {
                        return false;
                    }
                });
            });
            //Select2
        });

    </script>
    <script>

        $(document).ready(function () {
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
                        var option = '<option value="">Select Thana</option>';
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

    <script>
        var _token = $('input[name="_token"]').val();

        var age = -1;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $("#faq-info").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

        $(function () {
            $(".wysihtml5-editor").wysihtml5();
        });
    </script>
    <style>
        ul, ol {
            list-style-type: none;
        }
    </style>
@endsection