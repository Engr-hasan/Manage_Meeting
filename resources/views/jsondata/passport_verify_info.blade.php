<style>
    .magnify {position: relative;}
    .small {display: block;}
    .large {
        width: 400px;
        height: 400px;
        position: absolute;
        -webkit-border-radius: 100%;
        -moz-border-radius: 100%;
        border-radius: 100%;
        -webkit-box-shadow: 0 0 0 7px rgba(255, 255, 255, 0.85),  0 0 7px 7px rgba(0, 0, 0, 0.25),  inset 0 0 40px 2px rgba(0, 0, 0, 0.25);
        -moz-box-shadow: 0 0 0 7px rgba(255, 255, 255, 0.85),  0 0 7px 7px rgba(0, 0, 0, 0.25),  inset 0 0 40px 2px rgba(0, 0, 0, 0.25);
        box-shadow: 0 0 0 7px rgba(255, 255, 255, 0.85),  0 0 7px 7px rgba(0, 0, 0, 0.25),  inset 0 0 40px 2px rgba(0, 0, 0, 0.25);
        display: none;
    }
</style>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title">
                Passport Verification Form
            </div>
        </div>
        <div class="panel-body">
            <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">
                            Pilgrim Passport Information
                        </div>
                    </div>
                    <div class="panel-body">
                        <dl class="dl-horizontal">
                            <dt>Passport No:</dt>
                            <dd>
                                {!! $json_object->passport_no !!}
                            </dd>
                            <dt>Passport Type:</dt>
                            <?php
                            use App\Libraries\CommonFunction;use App\Modules\Registration\Models\PassVerifyReq;
                            $passType = '';
                                if ($json_object->passport_type == 'O')
                                {
                                    $passType = 'Ordinary';
                                }
                                else if($json_object->passport_type == 'G')
                                {
                                    $passType = 'Official';
                                }
                                else if($json_object->passport_type == 'D')
                                {
                                    $passType = 'Diplomatic';
                                }
                            ?>
                            <dd>
                                {!! $passType !!}
                            </dd>
                            <dt>Surname:</dt>
                            <dd>
                                {!! $json_object->surname !!}
                            </dd>
                            <dt>Given Name:</dt>
                            <dd>
                                {!! $json_object->given_name !!}
                            </dd>
                            <dt>Date of Birth:</dt>
                            <dd>
                                {!! date('d-M-Y',strtotime($json_object->birth_date)) !!}
                            </dd>
                            <dt>Personal Number:</dt>
                            <dd>
                                {!! $json_object->personal_number !!}
                            </dd>
                            <dt>Passport Input:</dt>
                            <dd>
                                @if(isset($json_object->is_pass_scanned))
                                    {!! $json_object->is_pass_scanned !!}
                                    @else
                                    NA
                                @endif
                            </dd>
                        </dl>
                        <?php
                            $pilgrimInfo = PassVerifyReq::getPilgrimInfo($json_object->tracking_no);
                            $passport_copy = CommonFunction::getPicture('reg_passport_verify',$pilgrimInfo->id);
                        ?>
                        <div class="magnify">
                            <div class="large" style="background:url('{{$passport_copy}}') no-repeat;"></div>
                            <img class="small img-responsive" src="{{ $passport_copy }}">
                        </div>
                        <br/>
                        <div class="text-center">
                            <a href="{{ $passport_copy }}" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-file-text-o"></i> View Passport </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">
                            Pilgrim Information (Pre-registration data)
                        </div>
                    </div>
                    <div class="panel-body" style="min-height: 862px;">
                        <?php
                            $requested_profile_pic = CommonFunction::getPicture('reg_profile_pic_tmp',$pilgrimInfo->id);
                            $profile_pic = ($requested_profile_pic != null) ? $requested_profile_pic : CommonFunction::getPicture('pilgrim',$pilgrimInfo->id);
                        ?>
                        <div class="panel-thumbnail">
                            <img class="img-responsive profile-user-img"
                                 src="{{ ($profile_pic == '')?url('assets/images/default_profile.jpg'):$profile_pic }}">
                        </div>
                        <br/>
                        <dl class="dl-horizontal">
                            <dt>Tracking No:</dt>
                            <dd>
                                {!! $json_object->tracking_no !!}
                            </dd>
                            <dt>Name:</dt>
                            <dd>
                                {!! $pilgrimInfo->full_name_english !!}
                            </dd>
                            <dt>Date of Birth:</dt>
                            <dd>
                                {!! date('d-M-Y',strtotime($pilgrimInfo->birth_date)) !!}
                            </dd>
                            <dt>Father Name:</dt>
                            <dd>
                                {!! $pilgrimInfo->father_name !!}
                            </dd>
                            <dt>Mother Name:</dt>
                            <dd>
                                {!! $pilgrimInfo->mother_name !!}
                            </dd>
                            @if($pilgrimInfo->identity == 'NID')
                                <dt>National Id:</dt>
                                <dd>
                                    {!! $pilgrimInfo->national_id !!}
                                </dd>
                                @else

                                <dt>Birth Certificate ID:</dt>
                                <dd>
                                    {!! $pilgrimInfo->birth_certificate !!}
                                </dd>
                            @endif
                            @if($pilgrimInfo->spouse_name != '')
                                <dt>Spouse Name:</dt>
                                <dd>
                                    {!! $pilgrimInfo->spouse_name !!}
                                </dd>
                            @endif
                            <dt>Permanent Address:</dt>
                            <dd>
                                <p>
                                    {!! $pilgrimInfo->per_village_ward !!},<br/>
                                    {!! $pilgrimInfo->per_police_station !!},<br>
                                    {!! $pilgrimInfo->per_district !!}-{!! $pilgrimInfo->per_post_code !!}
                                </p>
                            </dd>
                            <dt>Management Type:</dt>
                            <dd>
                                {!! $pilgrimInfo->is_govt !!}
                            </dd>
                        </dl>
                        @if($pilgrimInfo->national_id != '')
                            <div class="text-center">
                                <a href="javascript:void(0);"
                                   url="{!! url('pilgrim-profile/view-nid-source') !!}"
                                   nid="{!! Encryption::encode($pilgrimInfo->national_id) !!}"
                                   birthdate="{!! Encryption::encode($pilgrimInfo->birth_date) !!}"
                                   class="btn btn-xs btn-default nidinfo">
                                    <i class="fa fa-eye"></i>
                                    View NID
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="nidinfoModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        NID Source Information
                    </h4>
                </div>
                <div class="modal-body">
                    <div id="nid_pilgrim_info">

                    </div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="clearfix">&nbsp;</div>
                </div>
                <div class="modal-footer">
                    &nbsp;
                </div>
            </div>
        </div>
    </div>
@section('footer-script2')
    <script>
        /*
         * passport view magnifier
         */
        $(document).ready(function () {
            var native_width = 0;
            var native_height = 0;
            var loadLocker = true;
            var image_object = null;

            //Now the mousemove function
            $(".magnify").mousemove(function (e) {
                if (!native_width && !native_height) {
                    if (loadLocker) {
                        loadLocker = false;
                        image_object = new Image();
                        image_object.src = $(this).children(".small").attr("src");
                    }

                    native_width = image_object.width;
                    native_height = image_object.height;
                }
                else {
                    var magnify_offset = $(this).offset();
                    var mx = e.pageX - magnify_offset.left;
                    var my = e.pageY - magnify_offset.top;

                    if (mx < $(this).width() && my < $(this).height() && mx > 0 && my > 0) {
                        $(this).children(".large").fadeIn(100);
                    }
                    else {
                        $(this).children(".large").fadeOut(100);
                    }
                    if ($(this).children(".large").is(":visible")) {
                        var rx = Math.round(mx / $(this).children(".small").width() * native_width - $(this).children(".large").width() / 2) * -1;
                        var ry = Math.round(my / $(this).children(".small").height() * native_height - $(this).children(".large").height() / 2) * -1;
                        var bgp = rx + "px " + ry + "px";
                        var px = mx - $(this).children(".large").width() / 2;
                        var py = my - $(this).children(".large").height() / 2;
                        $(this).children(".large").css({left: px, top: py, backgroundPosition: bgp});
                    }
                }
            }).on("mouseleave", function () {
                native_width = 0;
                native_height = 0;
                loadLocker = true;
            });
        });
    </script>
@endsection