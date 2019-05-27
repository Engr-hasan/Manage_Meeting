{!! Form::open(['url' => 'reports/show-report/'.$report_id, 'method' => 'post', 'id'=>'report_form', 'class' => 'form', 'role' => 'form']) !!}
@foreach($reportParameter as $input=>$value)
    <div class="col-md-12">
        <?php $data = explode('|', $value); ?>
        @if (substr($input, 0, 5) == 'sess_')
            {!! Form::hidden($input,Session::get($input)) !!}
        @else
            <div class="form-group col-md-6 {{$errors->has($value) ? 'has-error' : ''}}">
                {!! Form::label($data[0],count($data)>1?($data[1]?$data[1]:$data[0]):$data[0]) !!}
                    <?php
                    if(count($data)==1)
                        $data[1]=$data[0];
                    if(count($data)==2)
                        $data[2]='text';
                    ?>
                @if(count($data)>2)
                    @if($data[2]=='numeric')
                        {!! Form::number($data[0],Session::get($data[0]),['class'=>'form-control']) !!}
                    @elseif($data[2]=='date')
                        <div class="datepicker input-group date" data-date="12-03-2015" data-date-format="dd-mm-yyyy">
                            {!! Form::text($data[0],Session::get($data[0]),['class'=>'form-control datepicker']) !!}
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    @elseif($data[2]=='list' && count($data)>3)
{{--                        {!! Form::select($data[0],getBank(),Session::get($data[0]),['class'=>'form-control']) !!}--}}
                        {!! Form::select($data[0],getList($data[3]),Session::get($data[0]),['class'=>'form-control']) !!}
                    @elseif($data[2]=='bank')
                        {!! Form::select($data[0],getBank(),Session::get($data[0]),['class'=>'form-control']) !!}
                    @elseif($data[2]=='agency')
                        {!! Form::select($data[0],getAgency(),Session::get($data[0]),['class'=>'form-control']) !!}
                    @else
                        {!! Form::text($data[0],Session::get($data[0]),['class'=>'form-control']) !!}
                    @endif
                @else
                    {!! Form::text($data[0],Session::get($data[0]),['class'=>'form-control']) !!}
                @endif
                {!! $errors->first($input,'<span class="help-block">:message</span>') !!}
            </div>
        @endif
    </div>
@endforeach
{!! Form::submit('Show',['class'=>'btn btn-primary','name'=>'show_report']) !!}
{{--{!! Form::submit('Reload',['class'=>'btn btn-success','name'=>'show_report']) !!}--}}
{!! Form::submit('Download CSV',['class'=>'btn btn-primary','name'=>'export_csv']) !!}
{{--{!! Form::submit('Download ZIP',['class'=>'btn btn-warning','name'=>'export_csv_zip']) !!}--}}
{!! Form::close() !!}


@section('footer-script')
    <script>
        $(function () {
            $(".datepicker").datetimepicker({
                viewMode: 'years',
                format: 'YYYY-MM-DD'
            });

        });
    </script>
@endsection