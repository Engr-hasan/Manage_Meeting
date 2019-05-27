<div class="panel panel-default">
    <div class="panel-bdody">
        <div id="docTabs" style="margin:10px;">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <?php $i = 1; ?>
                @foreach($document as $row)
                    <li role="presentation" class="<?php if ($i == 1) {
                        echo 'active';
                    } ?>">
                        <a href="#tabs{{$i}}" data-toggle="tab">Doc {{$i}}</a>
                    </li>
                    <?php $i++; ?>
                @endforeach
                <li role="presentation" class="">
                    <a href="#tabs-auth" data-toggle="tab">Auth File</a>
                </li>
            </ul>


            <!-- Tab panes -->
            <div class="tab-content">
                <?php $i = 1; ?>
                @foreach($document as $row)
                    <div role="tabpanel" class="tab-pane <?php if ($i == 1) {
                        echo 'active';
                    }?>" id="tabs{{$i}}">
                        @if(!empty($clrDocuments[$row->doc_id]['file']))
                            <h4 style="text-align: left;">{{$clrDocuments[$row->doc_id]['doc_name']}}</h4>
                            <?php
                            $fileUrl = public_path() . '/uploads/' . $clrDocuments[$row->doc_id]['file'];

                            if(file_exists($fileUrl)) {
                            ?>
                            <object style="display: block; margin: 0 auto;" width="1000" height="1260"
                                    type="application/pdf"
                                    data="/uploads/<?php echo $clrDocuments[$row->doc_id]['file'] ?>#toolbar=1&amp;navpanes=0&amp;scrollbar=1&amp;page=1&amp;view=FitH"></object>
                            <?php } else { ?>
                            <div class="">No such file is existed!</div>
                            <?php } ?> {{-- checking file is existed --}}

                        @else
                            <div class="">No file found!</div>
                        @endif
                    </div>
                    <?php $i++; ?>
                @endforeach

                <div role="tabpanel" class="tab-pane" id="tabs-auth">
                    <h6 style="text-align: left;">Auth Letter</h6>
                    @if(!empty($applicantInfo->authorization_file))
                        <?php
                        $fileUrl = public_path() . '/uploads/' . $applicantInfo->authorization_file;
                        if(file_exists($fileUrl)) {
                        ?>
                        <object style="display: block; margin: 0 auto;" width="1000" height="1260"
                                type="application/pdf"
                                data="/uploads/<?php echo $applicantInfo->authorization_file; ?>#toolbar=1&amp;navpanes=0&amp;scrollbar=1&amp;page=1&amp;view=FitH"></object>
                        <?php } else { ?>
                        <div class="">No such file is existed!</div>
                        <?php } ?> {{-- checking file is existed --}}

                    @else
                        <div class="">No file found!</div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
