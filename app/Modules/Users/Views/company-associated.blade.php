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
                Associated Company
            </div>
            <div class="panel-body">
                <div class="col-lg-10">
                    {!! Form::open(array('url' => 'users/company-associated-save', 'method' => 'post')) !!}
                    <div class="form-group col-md-12">
                        {!! Form::label('email', 'User Email:', ['class' => 'col-md-3']) !!}
                        <input type="hidden" name="user_id" value="{{ $user_id }}">
                        <div class="col-md-9">
                            {!! Form::text('email', $user_exist_company->user_email, $attributes = array('class'=>'form-control',
                                 'id'=>"user_full_name",'readonly', 'data-rule-maxlength'=>'100')) !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('assign_desk', 'Select Company to Associated:', ['class' => 'col-md-3']) !!}
                     <input type="hidden" name="user_id" value="{{ $user_id }}">
                        <div class="col-md-9">
                            <select name="company_associated[]" class="city form-control limitedNumbSelect2" data-placeholder="Select Desk to assign" style="width: 100%;" >
                                @foreach($company_list as $data)
                                    @if(in_array( $data->id, $select))
                                        <option value="{{ $data->id }}" selected="true">{{ $data->company_info }}</option>
                                    @else
                                        <option value="{{ $data->id }}">{{ $data->company_info }}</option>
                                    @endif
                                @endforeach
                            </select>
                            {{--{!! Form::select('user_types[]', $desk_list, $select, ['class' => 'form-control input-sm limitedNumbSelect2','multiple'=>'true', 'placeholder' => 'Select Desk to assign']) !!}--}}
                            {!! $errors->first('company_associated','<span class="help-block">:message</span>') !!}
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