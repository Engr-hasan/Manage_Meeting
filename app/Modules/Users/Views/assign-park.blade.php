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
        <div class="panel panel-primary">
            <div class="panel-heading">
                Assign Park
            </div>
            <div class="panel-body">
                <div class="col-lg-10">
                    {!! Form::open(array('url' => 'users/assign-park-save', 'method' => 'post')) !!}
                    <div class="form-group col-md-12">
                        {!! Form::label('email', 'User Email:', ['class' => 'col-md-3']) !!}
                        <input type="hidden" name="user_id" value="{{ $user_id }}">
                        <div class="col-md-9">
                            {!! Form::text('email', $user_exist_park->user_email, $attributes = array('class'=>'form-control',
                                 'id'=>"user_full_name",'readonly', 'data-rule-maxlength'=>'100')) !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('assign_park', 'Select Park to Assign:', ['class' => 'col-md-3']) !!}
                     <input type="hidden" name="user_id" value="{{ $user_id }}">
                        <div class="col-md-9">
                            <select name="park_name[]" class="city form-control limitedNumbSelect2" data-placeholder="Select Desk to assign" style="width: 100%;" multiple="multiple">
                                @foreach($park_list as $city)
                                    @if(in_array( $city->id, $select))
                                        <option value="{{ $city->id }}" selected="true">{{ $city->park_name }}</option>
                                    @else
                                        <option value="{{ $city->id }}">{{ $city->park_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            {{--{!! Form::select('user_types[]', $desk_list, $select, ['class' => 'form-control input-sm limitedNumbSelect2','multiple'=>'true', 'placeholder' => 'Select Desk to assign']) !!}--}}
                            {!! $errors->first('user_types','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-md-3">
                            <a href="{{ url('users/lists') }}" class="btn btn-sm btn-default"><i class="fa fa-close"></i> Close</a>
                        </div>
                        <div class="col-md-9">
                            @if(ACL::getAccsessRight('user','E'))
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
                            @endif
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer-script')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2.min.css") }}">
    <script src="{{ asset("assets/plugins/select2.min.js") }}"></script>
    <script>
        $(document).ready(function(){
            //Select2
            $(".limitedNumbSelect2").select2({
                //maximumSelectionLength: 1
            });
        });
    </script>
@endsection