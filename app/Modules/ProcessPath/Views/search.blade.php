{!! Form::open(['url' => '#process/search','method' => 'post','id' => ''])!!}
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="col-md-6 ">
                <label for="">Process Type: </label>
                {!! Form::select('ProcessType', ['' => 'Select One'] + $ProcessType, session('active_process_list'), ['class' => 'form-control search_type']) !!}
            </div>
            <div class="col-md-6 ">
                <label for="">Status: </label>

                {!! Form::select('status', $status,(isset($status_id) ? $status_id : '') , ['class' => 'form-control search_status']) !!}
            </div>
        </div>
        <div class="col-md-3">
            <label for="">Search Text: </label>
            {!! Form::text('search_text', '', ['class' => 'form-control search_text', 'placeholder'=>'Write something']) !!}
        </div>
        <div class="col-md-5">
            <div class="col-md-5 ">
                <label for="">Date within: </label>
                {!! Form::select('searchTimeLine', $searchTimeLine, '', ['class' => 'form-control search_time']) !!}
            </div>
            <div class="col-md-5">
                <label for="">of</label>
                    <div class="date_within input-group date">
                        {!! Form::text('date_within', '', ['class' => 'form-control search_date']) !!}
                        <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                    </div>
            </div>
            <div class="col-md-1">
                <label for="">&nbsp;</label>
                <input type="button" id="search_process" class="btn btn-primary" value="Search">
            </div>

        </div>
    </div>
</div>
{!! Form::close()!!}
<div id="list_search" class="" style="margin-top: 20px;">
    <table id="table_search" class="table table-striped display" style="width: 100%">
        <thead>
        <tr>
            <th>Current Desk</th>
            <th>Tracking No</th>
            <th>Process Type</th>
            <th style="width: 35%">Reference Data</th>
            <th>Status</th>
            <th>Modified</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

@section('footer-script2')
    <script language="javascript">
        $(function () {
            $('.date_within').datetimepicker({
                viewMode: 'days',
                format: 'DD-MMM-YYYY',
                maxDate: (new Date())
            });

            $('.ProcessType').change(function () {
                $.get('{{route("process.setProcessType")}}',
                    {
                        _token: $('input[name="_token"]').val(),
                        data: $(this).val()
                    }, function(data) {
                        if(data == 'success'){
                            table_desk.ajax.reload();
                            var len = table.length;
                            for (var i = 0; i < len; i++) {
                                table[i].ajax.reload();
                            }
                        }
                    });
            });
            $('#table_search').hide();
            var search_list = '';
            $('#search_process').click(function (e, process_type_id, status_id) {

                if($('.search_type').val() ==''){
                    alert ('Please select the Process Type');
                    return  false;
                }
                $('#table_search').show();

                var searchStatus='';
                var searchType ='';
                if(typeof(status_id) != "undefined") {
                    searchType =process_type_id;
                    searchStatus=status_id;
                }else{
                    searchType=$('.search_type').val();
                    searchStatus=$('.search_status').val();
                }
                $('#table_search').DataTable({
                    destroy: true,
                    iDisplayLength: 25,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        url: '{{route("process.getList")}}',
                        method: 'get',
                        data: function (d) {
                            d.process_search = true;
                            d.search_type = searchType;
                            d.search_time = $('.search_time').val();
                            d.search_text = $('.search_text').val();
                            d.search_date = $('.search_date').val();
                            d.search_status = searchStatus;
                        }
                    },
                    columns: [
                        {data: 'desk', name: 'desk'},
                        {data: 'tracking_no', name: 'tracking_no',searchable: false},
                        {data: 'process_name', name: 'process_name',searchable: false},
                        {data: 'json_object', name: 'json_object'},
                        {data: 'status_name', name: 'status_name', searchable: false},
                        {data: 'updated_at', name: 'updated_at', searchable: false},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    "aaSorting": []
                });
            });
//            $("#search_process").trigger('click');

            $('.search_type').change(function () {
                $.get('{{route("process.searchProcessType")}}',
                    {
                        _token: $('input[name="_token"]').val(),
                        data: $(this).val()
                    }, function(data) {
                        $('.search_text').attr('placeholder',data);
                    });
            });

            function openCity(evt, cityName) {
                var i, tabcontent;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }

                document.getElementById(cityName).style.display = "block";
                evt.currentTarget.className += " active";

            }
            $('.statusWiseList').click(function () {

                $('#list_desk').removeClass('active');
                $('#tab1').removeClass('active');
                $('#tab2').removeClass('active');
                $('#tab4').removeClass('active');
                $('#desk_user_application').removeClass('active');
                $('#list_delg_desk').removeClass('active');
                $('#tab3').addClass('active');
                $('#list_search').addClass('active');


                var data = $(this).attr("data-id");
                var typeAndStatus=data.split(",");
                var process_type_id=typeAndStatus[0];
                var statusId=typeAndStatus[1];

                $("#search_process").trigger('click',[process_type_id,statusId]);
            });


        });
    </script>
@endsection