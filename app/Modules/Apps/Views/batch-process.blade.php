{!! Form::hidden('status_from','',['id'=>'status_from']) !!}
<?php
$delegation_desk = 1;
if(empty($from_delegation_desk))
    $delegation_desk = 0;
?>
        <!--<input type="text" name="from_delegation_desk" value="<?php echo $delegation_desk; ?>" />-->
{!! Form::button('<i class="fa fa-delicious"></i> Get Applicable Status', array('type' => 'button', 'value'=> 'applicable status', 'class' => 'btn btn-primary applicable_status','style'=>'display:none')) !!}



<div class="alert alert-info">
    <div class="loading" style="display: none"><h2><i class="fa fa-spinner fa-spin"></i> &nbsp;</h2></div>
    <span class="col-md-3 {{$errors->has('status_id') ? 'has-error' : ''}}">
        {!! Form::label('status_id','Apply Status ') !!}
        {!! Form::select('status_id',[], null, ['class' => 'form-control required status_id']) !!}
        {!! $errors->first('status_id','<span class="help-block">:message</span>') !!}
    </span>

    <span id="sendToDeskOfficer">
        <span class="col-md-3 {{$errors->has('desk_id') ? 'has-error' : ''}}">
            {!! Form::label('desk_id','Send to Desk') !!}
            {!! Form::select('desk_id', [''=>'Select Below'], '', ['class' => 'form-control dd_id required']) !!}
            {!! $errors->first('desk_id','<span class="help-block">:message</span>') !!}
        </span>
    </span>

    <span class="col-md-3 {{$errors->has('remarks') ? 'has-error' : ''}}">
		{!! Form::label('remarks','Remarks') !!}
        {!! Form::textarea('remarks',null,['class'=>'form-control','id'=>'remarks', 'placeholder'=>'Enter Remarks','maxlength' => 254, 'rows' => 1, 'cols' => 50]) !!}
        <small><b>(Maximum length 254)</b></small><br/>
        {!! $errors->first('remarks','<span class="help-block">:message</span>') !!}
    </span>
	<span class="col-md-2">
		<label for="" style="width: 100%;height: 15px;"></label>
        {!! Form::button('<i class="fa fa-save"></i> Process', array('type' => 'submit', 'value'=> 'Submit', 'class' => 'btn btn-primary send')) !!}
    </span>
    <span id="sendToFile" style="clear: both;display: block;">
        <span class="col-md-3 {{$errors->has('desk_id') ? 'has-error' : ''}}">
            {!! Form::label('attach_file','Attach file') !!}
            {!! Form::file('attach_file[]', ['id'=>'','multiple'=>true]) !!}
            {!! $errors->first('attach_file','<span class="help-block">:message</span>') !!}
            <span class="text-danger" style="font-size: 9px; font-weight: bold">
                [File Format: *.pdf | File size(75-125)KB]
            </span>
        </span>
    </span>
    <br/><br/><br/>
</div>
