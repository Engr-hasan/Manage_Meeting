@extends('layouts.admin')


@section('content')

    <div class="col-lg-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <b>Create Resource</b>
                </div>
                <div class="panel-body">
                    <div class="col-lg-10">
                        {!! Form::open(array('url' => '/Training/resource-store/'.$id,'method' => 'post', 'class' => 'form-horizontal', 'id' => 'agency-info',
                    'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

                        <div class="form-group col-md-12 {{$errors->has('resource_title') ? 'has-error' : ''}}">
                            {!! Form::label('resource_title','Resource Title: ',['class'=>'col-md-3  required-star']) !!}
                            <div class="col-md-6">
                                {!! Form::text('resource_title', '', ['class'=>'form-control bnEng required', 'data-rule-maxlength'=>'150', 'id'=>'title']) !!}
                                {!! $errors->first('resource_title','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group col-md-12 {{$errors->has('resource_type') ? 'has-error' : ''}}">
                            {!! Form::label('resource_type','Resource Type: ',['class'=>'col-md-3  required-star']) !!}
                            <div class="col-md-6">
                                {!! Form::select('resource_type', $resource_types,'', ['class' => 'form-control input-sm', 'placeholder' => 'Select One', 'id'=>'status']) !!}
                                {!! $errors->first('resource_type','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group col-md-12 {{$errors->has('resource_link') ? 'has-error' : ''}}">
                            {!! Form::label('resource_link','Resource URL: ',['class'=>'col-md-3  required-star']) !!}
                            <div class="col-md-6">
                                {!! Form::text('resource_link', '', ['class'=>'form-control bnEng required', 'data-rule-maxlength'=>'150', 'id'=>'title']) !!}
                                {!! $errors->first('resource_link','<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="col-md-3 youtube_note" style="font-size: 12px; display: none;">
                                <p class="text-info">[Note: Please enter 11 character code for youtube video]</p>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="col-md-offset-2 col-md-10">
                                <button type="submit" class="btn btn-primary pull-right">
                                    <i class="fa fa-chevron-circle-right"></i> Save
                                </button>
                            </div>
                        </div>

                        {!! Form::close() !!}<!-- /.form end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <b>Resources List :</b>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="resource_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead class="alert alert-info">
                                <tr>
                                    <th>Resource Title</th>
                                    <th>Types</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer-script')
    @include('partials.datatable-scripts')
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="training_id" value="{{ $id }}">
    <script>
        $(document).ready(function(){
            $('#resource_list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ url('training/get-training-resource-data') }}',
                    method: 'post',
                    data: function (e) {
                        e._token = $('input[name="_token"]').val();
                        e.training_id = $('input[name="training_id"]').val();
                    }
                },
                columns: [
                    {data: 'resource_title', name: 'resource_title'},
                    {data: 'resource_type', name: 'resource_type'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
        });
    </script>
    <script>
        $("#status").change(function () {
           if($(this).val()==3){
               $(".youtube_note").show();
           }
            else{
               $(".youtube_note").hide();
           }
        });
    </script>
@endsection