<?php $row_sl = 0; ?>
@foreach($getList as $row)
    <?php $row_sl++ ?>
    <tr>
        <td>&nbsp;</td>
        <td>{!! $row_sl !!}</td>
        <td>{!! $row->track_no !!}</td>
        <td>{!! $row->application_title !!}</td>
        <td>{!! $row->applicant_name !!} - {!! $row->nationality !!}</td>
        @if (!in_array($desk_id, array(3, 4, 6, 7,9,10)))
            <td>
                @if($row->desk_name == '')
                    Applicant
                @else
                    {!! $row->desk_name !!}
                @endif
            </td>
        @else
            <td>
                @if($row->desk_name == '')
                    Applicant
                @else
                    {!! $row->desk_name !!}
                @endif
            </td>
        @endif

        <td>
            @if(!empty($row->status_name))
                <span style="background-color:<?php echo $row->color;  ?>;color: #fff; font-weight: bold;" class="label btn-sm">
                                        {!! $row->status_name !!}
                                            </span>
            @else
                <span style="background-color:#dd4b39;color: #fff; font-weight: bold;" class="label btn-sm">
                                                   Draft
                                            </span>
            @endif
            @if(in_array($desk_id,array(3,4,9,10)) )

                <?php
                $status_id = CommonFunction::getResultStatus($row->record_id, $desk_id);

                if ($status_id > 0)
                    echo "<br/><small><b>" . $resultList[$status_id] . " from Verifier</b></small>";
                ?>
            @endif
            @if((in_array(Auth::user()->desk_id, array(1,2,3,9,4,10))) || (!empty($delegated_desk) && ($desk_id == 1 || $desk_id == 2 || $desk_id == 8)))

                <span class="btn-toolbar">
            @if(Auth::user()->user_type != '5x505')


        </span>
                @endif

                @endif

                        <!--                                    @if(Auth::user()->user_type != 5)
                @if($row->status_id == 9) <br/><br/>
                                    @if($row->result_status == 2 || $row->result_status == 3)
                        <span style="color: #fff; font-weight: bold;" class="label label-warning btn-sm">
                        <?php $results = array('2' => 'Completed', '3' => 'Rejected'); ?>
                {{  $results["$row->result_status"] }}
                        </span>
                        @endif
                @endif
                @endif-->

    </td>
    <td>{!! CommonFunction::updatedOn($row->created_at) !!}</td>
    <td>
     <a href="{{url('project-clearance/view/'.Encryption::encodeId($row->record_id))}}" class="btn btn-xs btn-primary open" ><i class="fa fa-folder-open-o"></i> View</a>
    </td>
</tr>
@endforeach



