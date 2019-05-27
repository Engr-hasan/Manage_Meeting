<style>
    .statusBox{
        float: left;
        width: 90px;
        margin: 5px 3px;
        height: 80px;
    }
    .statusBox-inner {
        padding: 3px !important;
        font-weight: bold !important;
        height: 100%;
    }

</style>
<?php
$appsInDesk = \App\Libraries\CommonFunction::statuswiseAppInDesks($process_type_id); // 2 is the service ID of registration
$user = explode('x', Auth::user()->user_type);
?>

@if($appsInDesk) {{-- Desk Officers --}}
@foreach($appsInDesk as $row)

    <div class="statusBox">
        <div class="panel panel-success statusBox-inner" style="border-color: #347ab6">
            <a href="#" class="statusWiseList" data-id="{{$row->process_type_id.','.$row->id}}" style="background: #008000">
            <div class="panel-heading" style="background:#347ab6;color: white; padding: 5px !important; alignment-adjust: central;height: 100%"
                 title="{{ !empty($row->status_name) ? $row->status_name :'N/A'}}">

                <div class="row">
                    <div class="col-xs-12 text-center">
                        <div class="h3" style="margin-top:0;margin-bottom:0;font-size:16px;" id="{{ !empty($row->status_name) ? $row->status_name :'N/A'}}">
                            {{ !empty($row->totalApplication) ? $row->totalApplication : '0' }}
                        </div>
                    </div>
                </div>

                <div class="row" style=" text-decoration: none !important">
                    <div class="col-xs-12 text-center">
                        <div class="h3" style="margin-top:0;margin-bottom:0;font-size:11px; font-weight: bold">
                            {{ !empty($row->status_name) ? $row->status_name :'N/A'}}
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>

@endforeach
@endif {{--checking not empty $appsInDesk --}}

