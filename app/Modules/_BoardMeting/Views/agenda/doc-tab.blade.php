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
                        <a href="#tabs{{$i}}" data-toggle="tab"> {{ trans('messages.doc') }} {{$i}}</a>
                    </li>
                    <?php $i++; ?>
                @endforeach
            </ul>


            <!-- Tab panes -->
            <div class="tab-content">
                <?php $i = 1; ?>
                @foreach($document as $row)




                    <div role="tabpanel" class="tab-pane <?php if ($i == 1) {
                        echo 'active';
                    }?>" id="tabs{{$i}}">
                        @if(!empty($row->file))

                            <?php
                            $support_type = array('xls','xlsx', 'ppt','pptx','docx','doc');
                            $http =URL::to('/').'/'.$row->file;
                            ?>
                            @if (in_array(pathinfo($row->file, PATHINFO_EXTENSION), $support_type))
                                    <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{$http}}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                            @elseif(pathinfo($row->file, PATHINFO_EXTENSION) == 'pdf')

                            <h4 style="text-align: left;"></h4>
                            <?php
                            $fileUrl = public_path() . '/' . $row->file;

                            if(file_exists($fileUrl)) {
                            ?>
                            <object style="display: block; margin: 0 auto;" width="1000" height="1260"
                                    type="application/pdf"
                                    data="/<?php echo $row->file ?>#toolbar=1&amp;navpanes=0&amp;scrollbar=1&amp;page=1&amp;view=FitH"></object>
                            <?php } else { ?>
                            <div class="">No such file is existed!</div>
                            <?php } ?> {{-- checking file is existed --}}
                                @endif






                        @else
                            <div class="">No file found!</div>
                        @endif
                    </div>
                    <?php $i++; ?>
                @endforeach

            </div>
        </div>
    </div>
</div>
