{!! Form::hidden('status_from','',['id'=>'status_from']) !!}
<?php
$delegation_desk = 1;
if (empty($from_delegation_desk))
    $delegation_desk = 0;
?>
        <!--<input type="text" name="from_delegation_desk" value="<?php echo $delegation_desk; ?>" />-->
{!! Form::button('<i class="fa fa-delicious"></i> Get Applicable Status', array('type' => 'button', 'value'=> 'applicable status',
'class' => 'btn btn-primary applicable_status','style'=>'display:none')) !!}

<div class="alert alert-info">
    <div class="loading" style="display: none"><h2><i class="fa fa-spinner fa-spin"></i> &nbsp;</h2></div>
    <span class="col-md-4 {{$errors->has('status_id') ? 'has-error' : ''}}">
        {!! Form::label('schedule','Assign to new Schedule (Select Schedule)') !!}
        {!! Form::select('status_id',[], null, ['class' => 'form-control required status_id', 'id' => 'status_id']) !!}
        {!! $errors->first('status_id','<span class="help-block">:message</span>') !!}
    </span>

    <span class="col-md-2">
        <label for="" style="width: 100%;height: 15px;"></label>
        {!! Form::button('<i class="fa fa-save"></i> Assign', array('type' => 'submit', 'value'=> 'Submit', 'class' => 'btn btn-primary send')) !!}
    </span>
    <br/><br/><br/>
</div>
