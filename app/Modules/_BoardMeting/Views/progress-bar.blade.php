<div class="col-lg-12">
    <div class="row">
        <div class="col-md-12">
            <div  class="btn-group btn-breadcrumb steps">
                <?php
                    if(!isset($board_meeting_id)){
                        $board_meeting_id = \App\Libraries\Encryption::encodeId(1);
                    }
                $getSequence = CommonFunction::getSequenceNo($board_meeting_id);
                ?>
                <a  href="#" class="btn btn-{{$getSequence == 1?'success':'info'}}"
                   style="{{$getSequence == 1?'background-color:#358e35;':'background-color:#9dc8e2;color: #fff;'}} cursor: not-allowed">Initiate</a>
                <a href="#" class="btn btn-{{$getSequence == 2?'success':'info'}}"
                   style="{{$getSequence == 2?'background-color:#358e35;':'background-color:#9dc8e2;color: #fff;'}} cursor: not-allowed">Committee</a>
                <a href="#" class="btn btn-{{$getSequence == 4?'success':'info'}}"
                   style="{{$getSequence == 4?'background-color:#358e35;':'background-color:#9dc8e2;color: #fff;'}} cursor: not-allowed">Notice</a>
                <a href="#" class="btn btn-{{$getSequence == 3?'success':'info'}}"
                   style="{{$getSequence == 3?'background-color:#358e35;':'background-color:#9dc8e2;color: #fff;'}} cursor: not-allowed">Agenda</a>
                <a href="#" class="btn btn-{{$getSequence == 5?'success':'info'}}"
                   style="{{$getSequence == 5?'background-color:#358e35;':'background-color:#9dc8e2;color: #fff;'}} cursor: not-allowed">Decision</a>
                <a href="#" class="btn btn-{{$getSequence == 6?'success':'info'}}"
                   style="{{$getSequence == 6?'background-color:#358e35;':'background-color:#9dc8e2;color: #fff;'}} cursor: not-allowed">Accomplishment</a>
            </div>
        </div>

    </div>
</div>
<div class="col-lg-12"><br></div>

