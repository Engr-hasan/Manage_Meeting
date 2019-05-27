@extends('layouts.admin')
@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('Training');
        if (!ACL::isAllowed($accessMode,'V'))
        die('no access right!');
    ?>
    <div class="col-lg-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
        <section class="col-md-12">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-group panel-primary">
                        <div class="panel-heading">
                            <b>Trainee Information</b>
                        </div>
                        <div class="panel-body">
                            <div class="col-md-12">
                                {!! Form::label('name','Participant Name: ',['class'=>'col-md-2']) !!}
                                <?php (!empty($traineeInfo->user_id)) ? $name=$traineeInfo->user_full_name : $name=$traineeInfo->name;?>
                                <div class="col-md-6"> {{ $name }}</div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('email','Participant Email: ',['class'=>'col-md-2']) !!}
                                <div class="col-md-6">{!! $traineeInfo->email !!}</div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('phone','Phone: ',['class'=>'col-md-2']) !!}
                                <?php (!empty($traineeInfo->user_id)) ? $phone=$traineeInfo->user_mobile : $phone=$traineeInfo->mobile;?>
                                <div class="col-md-6"> {{ $phone }}</div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('trainee_nid','NID No.: ',['class'=>'col-md-2']) !!}
                                <?php (!empty($traineeInfo->trainee_nid)) ? $nid=$traineeInfo->trainee_nid : $nid=$traineeInfo->user_nid;?>
                                <div class="col-md-6"> {{ $nid }}</div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('trainee_dob','Date of Birth: ',['class'=>'col-md-2']) !!}
                                <?php (!empty($traineeInfo->dob)) ? $dateOfBirth=date("d-M-Y", strtotime($traineeInfo->dob)) : $dateOfBirth=date("d-M-Y", strtotime($traineeInfo->user_DOB));?>
                                <div class="col-md-6"> {{ $dateOfBirth }}</div>
                            </div>
                            <?php
                            if (!empty($traineeInfo->bank)){
                                $organization = $traineeInfo->bank;
                            }
                            else if($traineeInfo->agency_license){
                                $organization = "<span>". $traineeInfo->agency_name ."<b> [" . $traineeInfo->agency_license ."]</b></span>";
                            }
                            else {
                                $organization = '';
                            }
                            ?>
                            <div class="col-md-12">
                                {!! Form::label('organization','Organization : ',['class'=>'col-md-2']) !!}

                                <div class="col-md-6"> {!! $organization !!}</div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('address','Address: ',['class'=>'col-md-2']) !!}
                                <?php
                                if (!empty($traineeInfo->user_id)) {
                                    $district = $traineeInfo->district_name;
                                    $address = $district;
                                }
                                else
                                {
                                    $address = $traineeInfo->district;
                                }
                                ?>
                                <div class="col-md-6"> {{ $address }}</div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <a href="{{ url ('training-schedule/view/'.\App\Libraries\Encryption::encodeId($traineeInfo->training_schedule_id)) }}">
                                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-info')) !!}
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    @if(strtotime($traineeInfo->training_start_time) <= time())
                                        <span id="certificate-div">
                                        <div class="col-md-5">
                                            @if($traineeInfo->certificate !=  '')
                                                <a target="_blank" href="{{ $traineeInfo->certificate }}" class="btn btn-primary download_crt" id="dl_{{ Encryption::encodeId($traineeInfo->id) }}">
                                                    &nbsp; Download Certificate
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col-md-5">
                                            @if($traineeInfo->certificate !=  '')
                                                <span id="ddl_{{Encryption::encodeId($traineeInfo->id)}}"><a href="javascript:void(0);" class="btn btn-warning get_crt " id="{{Encryption::encodeId($traineeInfo->id)}}">Re Generate</a></span>
                                            @else
                                                <span id="ddl_{{Encryption::encodeId($traineeInfo->id)}}"><a href="javascript:void(0);" class="btn btn-primary get_crt " id="{{Encryption::encodeId($traineeInfo->id)}}">Get Certificate</a></span>
                                            @endif
                                        </div>
                                        <div class="col-md-2"></div>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
@section('footer-script')
    @include('partials.datatable-scripts')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="pdfurl" value="<?php echo env('PDF_API_BASE_URL'); ?>">
    <script>
        $(document).on('click','.get_crt',function(e){
            btn = $(this);
            btn_content = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;'+btn_content);
            btn.prop('disabled', true);

            training_participant_id = $(this).attr('id');
            pdfurl = $('input[name="pdfurl"]').val();


            $.ajax({
                url: '/training/ajax-certificate-letter',
                type: 'post',
                data: {
                    _token: $('input[name="_token"]').val(),
                    pdfurl: pdfurl,
                    training_participant_id: training_participant_id
                },
                success: function (response) {

                    if(response.responseCode == 1)
                    {
                        checkgenerator(training_participant_id);
                    }
                    //btn.html(btn_content);
                }
            });
        });
        function checkgenerator(training_participant_id)
        {
            pdfurl = $('input[name="pdfurl"]').val();

            $.ajax({
                url: '/training/ajax-certificate-feedback',
                type: 'POST',
                data: {
                    training_participant_id: training_participant_id,
                    pdfurl: pdfurl,
                    _token: $('input[name="_token"]').val()},
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.responseCode == 1)
                    {
                        if(response.flag == 1)
                        {
                            // Need to show download & regenerate link
                            showDownloadPanel(response.id,response.certificate);
                        }
                        else if (response.flag == -1) {
                            $('.msg').html('Whoops there was some problem please contact with system admin.');
                        }
                        else if (response.flag == 2) {
                            myVar = setTimeout(checkgenerator,5000,training_participant_id);
                        }
                    }
                    else
                    {
                        $('.msg').html('Some thing is wrong! code 1001');
                        return false;
                    }
                }
            });
        }
        function showDownloadPanel(training_participant_id,certificate)
        {
            pdfurl = $('input[name="pdfurl"]').val();

            $.ajax({
                url: '/training/update-download-panel',
                type: 'post',
                data: {
                    _token: $('input[name="_token"]').val(),
                    pdfurl: pdfurl,
                    training_participant_id: training_participant_id,
                    certificate: certificate
                },
                success: function (response) {
                    //alert(training_participant_id);
                    //$('#ddl_'+training_participant_id).html(response);
                    $("#certificate-div").html(response);
                }
            });
        }
    </script>
@endsection