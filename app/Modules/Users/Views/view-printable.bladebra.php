@extends('layouts.admin')
@section('content')
    <style>
        fieldset.scheduler-border {
            border: 1px groove #ddd !important;
            padding: 0 1.4em 1.1em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;
        }

        legend.scheduler-border {
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
            width:auto;
            padding:0 10px;
            border-bottom:none;
        }
    </style>
<?php use App\Libraries\ACL;use App\Libraries\CommonFunction;use App\Libraries\Encryption;$accessMode=ACL::getAccsessRight('user');if(!ACL::isAllowed($accessMode,'V')) { die('no access right!');};?>


<div class="col-lg-12" xmlns="http://www.w3.org/1999/html">

    {!! Form::open(array('url' => 'users/reject/'.\App\Libraries\Encryption::encodeId($user->id),'method' => 'post', 'class' => 'form-horizontal', 'id' => 'rejectUser')) !!}
    <!-- Modal -->
    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Reject User</h4>
                </div>
                <div class="modal-body">
                    <label class="required-star">Reject Reason : </label>
                    <textarea name="reject_reason" class="form-control" required ></textarea>
                </div>
                <div class="modal-footer">
                    @if(ACL::getAccsessRight('user','E'))
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{--modal end--}}

    {!! Form::close() !!}

    <section class="col-md-12" id="printDiv">
        <div class="row"><!-- Horizontal Form -->
            {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
            {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

            {!! Form::open(array('url' => '/users/approve/'.Encryption::encodeId($user->id),'method' => 'post', 'class' => 'form-horizontal',   'id'=> 'user_edit_form')) !!}
            <div class="panel">
                <div class="panel-body">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="panel-title" style="font-size: large;">Profile of : {!!$user->user_full_name!!}</div>
                        </div> <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="col-md-3">

                                @if (!empty($profile_pic))
                                    <img src="{{$profile_pic}}" alt="Auth letter" class="profile-user-img img-responsive img-circle" width="200"/>
                                @endif

                                @if (!empty($auth_file))
                                <a href="<?php echo $auth_file; ?>" target="_blank">
                                    FILE LINK
                                </a>
                                @else

                                    {{--<span class="text-danger">File Not Found</span>--}}
                                @endif
                            </div>
                            <div class="col-md-9">

                                <dl class="dls-horizontal">
                                    <dt>Full Name :</dt>
                                    <dd>{!!$user->user_full_name!!}&nbsp;</dd>
                                    <dt>Type :</dt>
                                    <dd>{!!$user->type_name!!}&nbsp;</dd>

                                    @if($user->passport_no == '')
                                    <dt>NID :</dt>
                                    <dd>{!!$user->user_nid!!}&nbsp;</dd>
                                    @else
                                        <dt>Passport No :</dt>
                                        <dd>{!!$user->passport_no!!}&nbsp;</dd>
                                    @endif
                                    <dt>Phone :</dt>
                                    <dd>{!!$user->user_phone!!}&nbsp;</dd>
                                    <dt>Email :</dt>
                                    <dd>{!!$user->user_email!!}&nbsp;</dd>
                                    @if($user->district_name)
                                    <dt>District :</dt>
                                    <dd>{!!$user->district_name!!}&nbsp;</dd>
                                    @endif
                                    @if($user->thana_name)
                                    <dt>Thana :</dt>
                                    <dd>{!!$user->thana_name!!}&nbsp;</dd>
                                    @endif
                                    @if($user->user_DOB)
                                        <dt>Date of Birth :</dt>
                                        <dd>
                                            {!!CommonFunction::changeDateFormat($user->user_DOB)!!}&nbsp;
                                        </dd>
                                    @endif
                                    <dd>
                                    @if ($user->is_approved != 1)
                                        <dt>Verification expire time :</dt>
                                        <dd>{!!$user->user_hash_expire_time!!}&nbsp;</dd>
                                    @endif
                                    {{--@if(in_array(Auth::user()->user_type,array("1x101")))--}}
                                        {{--@foreach($userMoreInfo as $key=>$info)--}}
                                            {{--<dt>{!!$key!!} :</dt>--}}
                                            {{--<dd>{!!$info!!}&nbsp;</dd>--}}
                                        {{--@endforeach--}}
                                    {{--@endif--}}
                                </dl>
                                @if($user->type_id=='4x404')
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Assigned Park</legend>
                                    <div class="control-group">
                                        <?php $i=1;?>
                                            @foreach($park as $desk_name)
                                                <dd>{{$i++}}. {!!$desk_name->park_name!!}</dd>
                                            @endforeach
                                    </div>
                                </fieldset>
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Assigned Desk</legend>
                                    <div class="control-group">
                                        <?php $i=1;?>
                                        @foreach($desk as $desk_name)
                                            <dd>{{$i++}}. {!!$desk_name->desk_name!!}</dd>
                                        @endforeach
                                    </div>
                                </fieldset>
                                @if($user->delegate_to_user_id>0)
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Delegation Information</legend>
                                    <div class="control-group">
                                        <b>Name : </b> {{ $delegationInfo->user_full_name }}<br/>
                                        <b>Designation : </b>{{ $delegationInfo->desk_name }}<br/>
                                        <b>Email : </b>{{ $delegationInfo->user_email }}<br/>
                                        <b>Mobile : </b>{{ $delegationInfo->user_phone }}<br/><br/>
                                        <a class="remove-delegation btn btn-primary" href="{{ url('/users/remove-deligation/'.Encryption::encodeId($user->id)) }}">
                                            <i class="fa fa-share-square-o"></i> Remove Delegation</a>
                                    </div>
                                </fieldset>
                                @endif

                               @endif

                                <?php
                                $approval = '';
                                $type = explode('x', $user->user_type);
                                if (substr($type[1], 2, 2) == 0) {
                                    echo Form::select('user_type', $user_types, $user->user_type, $attributes = array('class' => 'form-control required', 'required' => 'required',
                                        'placeholder' => 'Select One', 'id' => "user_type"));
                                }
                                $approval = '<button type="submit" class="btn btn-sm btn-success"> <i class="fa  fa-check "></i> Approve</button></form>';

                                 $approval.=' <a data-toggle="modal" data-target="#myModal2" class="btn btn-sm btn-danger addProjectModa2"><i class="fa fa-times"></i>&nbsp;Reject User</a> ';
                                ?>


                            </div>
                        </div><!-- /.box -->
                    </div>

                    <div class="col-md-12">
                        <div class="pull-left">
                            <a href="{{ url('users/lists') }}" class="btn btn-sm btn-default"><i class="fa fa-times"></i> Close</a>
                        </div>
                        <div class="pull-right">
                            <?php
                            $delegations='';
                                if($user->type_id=='4x404' && $user->delegate_to_user_id==0){
                            $delegations = '<a href="' . url('users/delegations/' . Encryption::encodeId($user->id)) . '" class="btn btn-sm btn-primary"><i class="fa fa-paper-plane"></i> Delegation</a>';
                            }
                            $edit = '<a href="' . url('users/edit/' . Encryption::encodeId($user->id)) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                            $reset_password = '<a href="' . url('users/reset-password/' . Encryption::encodeId($user->id)) . '" class="btn btn-sm btn-warning"'
                                    . 'onclick="return confirm(\'Are you sure?\')">'
                                    . '<i class="fa fa-refresh"></i> Reset password</a>';

                            $logged_in_user_type = Auth::user()->user_type;
                            $activate = '';
                            if ($logged_in_user_type == '1x101')
                            {
                                if ($user->user_status == 'inactive')
                                {
                                    $activate = '<a href="' . url('users/activate/' . Encryption::encodeId($user->id)) . '" class="btn btn-sm btn-success"><i class="fa fa-unlock"></i>  Activate</a>';
                                }
                                else
                                {
                                    $activate = '<a href="' . url('users/activate/' . Encryption::encodeId($user->id)) . '" class="btn btn-sm btn-danger"'
                                            . 'onclick="return confirm(\'Are you sure?\')">'
                                            . '<i class="fa fa-unlock-alt"></i> Deactivate</a>';
                                }
                            }
                            if ($user->is_approved == true)
                            {
                                if(CommonFunction::isAdmin())
                                {
                                    if(ACL::getAccsessRight('user','E'))
                                    {
                                        echo $delegations.'&nbsp;' .$edit;
                                    }

                                    if(ACL::getAccsessRight('user','R'))
                                    {
                                        if ($user->social_login != 1)
                                        echo '&nbsp;' . $reset_password;
                                    }

                                    if(ACL::getAccsessRight('user','E'))
                                    {
                                        echo '&nbsp;' . $activate;
                                    }
                                }
                            }
                            else
                            {
                                echo $approval;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </section>
    @endsection <!--content section-->