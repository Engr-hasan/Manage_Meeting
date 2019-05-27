<input type="hidden" name="form_id" value="{!! $form_id !!}">
<div class="col-md-12">
    <div class="form-group {{$errors->has('voucher_url') ? 'has-error' : ''}}">
        {!! Form::label('voucher_url','Voucher Url',['class'=>'col-md-2 control-label required-star']) !!}
        <div class="col-md-4">
            {!! Form::text('voucher_url','',['class'=>'form-control input-sm required font-text','placeholder'=>'Voucher Url']) !!}
            {!! $errors->first('voucher_url','<span class="help-block">:message</span>') !!}
        </div>
    </div>
</div>
<div style="clear: both;">&nbsp;</div>