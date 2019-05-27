<?php
use \App\Libraries\Encryption;
use \App\Libraries\CommonFunction;
?>

<div class="form-group col-md-12">
    {!! Form::label('message','Message / বার্তা : ',['class'=>'col-md-12']) !!}
    <div class="col-md-12">
        {{ $sms_content }}
    </div>
</div>
@if(count($pilgrim_under_guide) > 0)
    <input type="hidden" name="sender_tracking_no" value="{{ Encryption::encodeId($guideInfo->tracking_no) }}">
    <input type="hidden" name="sms_content" value="{{ $sms_content }}">
    <input type="hidden" name="nmbr_type" value="{{ $nmbr_type }}">
    <div class="form-group col-md-12">
        {!! Form::label('receiver_list','List of Pilgrims under guide : ',['class'=>'col-md-12']) !!}
        <?php foreach($pilgrim_under_guide as $pilgrim){
        $number = $nmbr_type == 'bd_no' ? $pilgrim->mobile : $pilgrim->ksa_mobile_no;
        if ($number == null) {
            continue;
        }
        ?>
        <div class="col-md-12">
            {!! Form::checkbox('receiver_list[]', Encryption::encodeId($pilgrim->tracking_no), null, ['class' => '']) !!}
            &nbsp;
            {!! $pilgrim->full_name_bangla.' ('.CommonFunction::ageYear($pilgrim->birth_date).') - '.$number !!}
        </div>
        <?php } ?>
    </div>
    <div class="form-group col-md-12">
        <div class="col-md-12">
            <button type="button" class="btn btn-sm btn-success send_sms">
                Send <i class="fa fa-chevron-circle-right"></i>
            </button>
        </div>
    </div>
@endif