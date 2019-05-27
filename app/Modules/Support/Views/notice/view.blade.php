@extends('layouts.admin')

@section('page_heading',trans('messages.notice'))
<link rel="stylesheet" href="{{ asset("assets/stylesheets/AdminLTE.min.css") }}" />

@section('content')
    <style>
        /*        .direct-chat-messages{
                    overflow: visible !important;
                }*/
    </style>
    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                {!!$data->heading!!}
            </div>
            <!-- /.panel-heading trans('messages.notice_view') -->
            <div class="panel-body">
                <div class="col-md-8">
                    <span class="text-{!!$data->importance!!}">{!!$data->details!!}</span>
                </div>
                <div class="col-md-4">
                    {!! CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
                    @if(ACL::getAccsessRight('settings','V'))
                        <a href="{!! url('settings/edit-notice/'. Encryption::encodeId($data->id)) !!}" class="btn btn-xs btn-default"><i class="fa fa-edit "></i>Edit</a>
                    @endif
                </div>
            <!--
            {!! Form::open(array('url' => '/settings/update-notice/'.$encrypted_id,'method' => 'patch', 'class' => 'form-horizontal smart-form', 'id' => 'notice-info',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

                    <div class="form-group col-md-8 {{$errors->has('heading') ? 'has-error' : ''}}">
                {!! Form::label('heading','Heading: ',['class'=>'col-md-3']) !!}
                    <div class="col-md-7">
                        {!! Form::text('heading',  $data->heading, ['class'=>'form-control bnEng required', 'size' => "10x5"]) !!}
            {!! $errors->first('heading','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-8 {{$errors->has('details') ? 'has-error' : ''}}">
                {!! Form::label('details','Details: ',['class'=>'col-md-3']) !!}
                    <div class="col-md-7">
                        {!! Form::textarea('details',  $data->details, ['class'=>'form-control bnEng required', 'size' => "10x5"]) !!}
            {!! $errors->first('details','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                -->

            <!--{!! Form::close() !!}
                    <!-- /.form end -->
                <div class="overlay" style="display: none;">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div><!-- /.box -->
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">More Notice & Instructions:</div>
            <div class="panel-body">
                @if($notice)
                    <div class="col-md-12">


                        <?php
                        $arr = $notice;

                        echo '<table class="table basicDataTable">';
                        echo "<caption></caption><tbody>";
                        foreach ($arr as $value) {
                            if($value->id<>$data->id){
                                echo "<tr><td width='150px'>$value->Date</td><td><span class='text-$value->importance'><a href='".url('support/view-notice/'.\App\Libraries\Encryption::encodeId($value->id))."'> <b>$value->heading</b></a></span></td></tr>";
                            }
                        }
                        echo '</tbody></table>';
                        ?>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection


@section('footer-script')
    <script>
        $('#notice-info').find('input:not([type=checkbox], [type=radio], [type=hidden], [type=button])').each(function () {
            $(this).replaceWith("<span><b>" + this.value + "</b></span>");
        });
        $('#notice-info').find('textarea').each(function () {
            $(this).replaceWith("<span class=\"span3\"><b>" + this.value + "</b></span>");
        });
        $("#notice-info").find('select').replaceWith(function () {
            return $(this).find('option:selected').text();
        });
    </script>
@endsection <!--- footer script--->