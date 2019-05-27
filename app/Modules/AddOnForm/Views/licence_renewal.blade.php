<input type="hidden" name="form_id" value="{!! $form_id !!}">
<?php
    $agencyData = \App\Modules\AddOnForm\Models\AgencyRenewalForm::getAgencyRenewalFormData($process_list_id);
?>
<input type="hidden" name="agency_renew_frm_id" value="{{ \App\Libraries\Encryption::encodeId($agencyData->id) }}">
<div class="col-md-12">
    <div class="form-group {{ $errors->has('license_effective_date')?'has-error':'' }}">
        {!! Form::label('license_effective_date','License effective date',['class'=>'font-norm required-star col-sm-2']) !!}
        <div class="col-md-4">
            <?php
            $license_effective_date = isset($agencyData->license_effective_date) ? date('d-M-Y',strtotime($agencyData->license_effective_date)) : '';
            ?>
            <div class="input-group date datepicker">
                {!! Form::text('license_effective_date', $license_effective_date, ['class' => 'input-sm form-control col-md-12 col-xs-12 datepicker', 'placeholder' => 'লাইসেন্স কার্যকর তারিখ']) !!}
                <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
            </div>
            {!! $errors->first('license_effective_date','<small class="text-danger">:message</small>') !!}
        </div>
    </div>
</div>
<div style="clear: both;">&nbsp;</div>
<div class="col-md-12">
    <div class="form-group {{ $errors->has('license_expired_date')?'has-error':'' }}">
        {!! Form::label('license_expired_date','License expire date',['class'=>'font-norm required-star col-sm-2']) !!}
        <div class="col-md-4">
            <?php
            $license_expired_date = isset($agencyData->license_expired_date) ? date('d-M-Y',strtotime($agencyData->license_expired_date)) : '';
            ?>
            <div class="input-group date datepicker">
                {!! Form::text('license_expired_date', $license_expired_date, ['class' => 'input-sm form-control col-md-12 col-xs-12 datepicker', 'placeholder' => 'লাইসেন্স মেয়াদ উত্তীর্ন  তারিখ']) !!}
                <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
            </div>
        </div>
        {!! $errors->first('license_expired_date','<small class="text-danger">:message</small>') !!}
    </div>
</div>
<div style="clear: both;">&nbsp;</div>

