@extends('layouts.admin')

@section('content')

    <?php
    $accessMode = ACL::getAccsessRight('BoardMeting');
    if (!ACL::isAllowed($accessMode, 'A')) {
        die('You have no access right! Please contact with system admin for more information.');
    }
    ?>
 <div class="col-lg-12">
        <div class="panel-body">
            <div class="panel panel-info">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4  col-md-offset-4 " style="border-bottom: 1px solid #c7bebe">
                            <label style="font-size: 15px;color: #478fca;padding: 0px" for="infrastructureReq"
                                   class="text-success col-md-6">Share Document Info
                            </label>
                            <br>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-4 col-md-offset-4">
                            <table>
                                <tbody>
                                <tr>
                                    <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">
                                        Document Name :
                                    </td>
                                    <td style="font-size: 13px;color: #5f5f5f;">
                                        &nbsp;&nbsp;{{$doc->doc_name}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">
                                        Tag:
                                    </td>
                                    <td style="font-size: 13px;color: #5f5f5f;">
                                        &nbsp;&nbsp;
                                        @if($doc->tag == 3)
                                            <span class="btn btn-danger btn-xs">Height</span>
                                        @elseif($doc->tag == 2)
                                            <span class="btn btn-info btn-xs">Moderate</span>
                                        @elseif($doc->tag == 1)
                                            <span class="btn btn-success btn-xs"
                                                 >Normal</span>
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="col-md-12">
                        <div id="docTabs" style="margin:10px;">
                            <!-- Nav tabs -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="tabs1">
                                    @if(!empty($doc->file))
                                        <h4 style="text-align: left;"></h4>
                                        <?php
                                        $support_type = array('xls','xlsx', 'ppt','pptx','docx','doc');
                                        $http =URL::to('/').'/'.$doc->file;
                                        ?>
                                        @if (in_array(pathinfo($doc->file, PATHINFO_EXTENSION), $support_type))
                                            <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{$http}}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                                        @elseif(pathinfo($doc->file, PATHINFO_EXTENSION) == 'pdf')
                                        <?php
                                        $fileUrl = public_path() . '/' . $doc->file;

                                        if(file_exists($fileUrl)) {
                                        ?>
                                        <object style="display: block; margin: 0 auto;" width="1000" height="1260"
                                                type="application/pdf"
                                                data="/<?php echo $doc->file ?>#toolbar=1&amp;navpanes=0&amp;scrollbar=1&amp;page=1&amp;view=FitH"></object>
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
@endsection <!--- footer script--->