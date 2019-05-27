@extends('layouts.admin')

@section('content')

    <?php
    $accessMode = ACL::getAccsessRight('BoardMeting');
    if (!ACL::isAllowed($accessMode, 'V')) {
        die('You have no access right! Please contact with system admin for more information.');
    }
    ?>
    @include('BoardMeting::progress-bar')
 <div class="col-lg-12">
     @include('BoardMeting::board-meeting-info')
        <div class="panel-body">
            @include('partials.messages')
            <div class="panel panel-info">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3  col-md-offset-5 " >
                            <label style="font-size: 16px;color: #478fca;padding: 0px;border-bottom: 1px solid #c7bebe" for="infrastructureReq"
                                   class="text-success col-md-6 text-center">{!! trans('messages.notice') !!}
                            </label>
                            <br>
                        </div>
                        <div class="col-md-12" >
                        </div>
                        <div class="col-md-2 pull-right ">
                            <label>
                                @if($board_meeting_data->notice_publish > 0)
                                    <a href="#" onclick="return myFunction('{{$board_meeting_id}}')" class="btn btn-primary">{!! trans('messages.publish') !!}</a>
                                @else
                                    <a href="{{ url('board-meting/committee/notice-publish/'.$board_meeting_id) }}" class="btn btn-primary">{!! trans('messages.publish') !!}</a>
                                @endif

                            </label>
                            <br>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div id="docTabs" style="margin:10px;">
                            <!-- Nav tabs -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="tabs1">
                                    @if(!empty($board_meeting_data->meting_notice))
                                        <h4 style="text-align: left;"></h4>
                                        <?php
                                        $support_type = array('xls','xlsx', 'ppt','pptx','docx','doc');
                                        $http =URL::to('/').'/'.$board_meeting_data->meting_notice;
                                        ?>
                                        @if (in_array(pathinfo($board_meeting_data->meting_notice, PATHINFO_EXTENSION), $support_type))
                                            <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{$http}}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                                        @elseif(pathinfo($board_meeting_data->meting_notice, PATHINFO_EXTENSION) == 'pdf')
                                        <?php
                                        $fileUrl = public_path() . '/' . $board_meeting_data->meting_notice;

                                        if(file_exists($fileUrl)) {
                                        ?>
                                        <object style="display: block; margin: 0 auto;" width="1000" height="1260"
                                                type="application/pdf"
                                                data="/<?php echo $board_meeting_data->meting_notice ?>#toolbar=1&amp;navpanes=0&amp;scrollbar=1&amp;page=1&amp;view=FitH"></object>
                                        <?php } else { ?>
                                        <div class="">No such file is existed!</div>
                                        <?php } ?> {{-- checking file is existed --}}

                                    @else
                                        <div class="">No file found!</div>
                                    @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer-script')
    <script>
        function myFunction(id) {
            var result = confirm('Are you sure? You want to notice publish again?');
            if(result == true){
                location.href = "/board-meting/committee/notice-publish/'"+id+"";
            }else{
                location.href = "/board-meting/committee/notice-publish/'"+id+"/r";
            }
        }
    </script>
@endsection <!--- footer script--->