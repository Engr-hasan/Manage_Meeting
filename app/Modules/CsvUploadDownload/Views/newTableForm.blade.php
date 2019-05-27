<div class="panel panel-primary">
    <div class="panel-heading"><strong> New table info</strong></div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">

                    {!! Form::open(array('url' => 'csv-upload/new-table/create-table','method' => 'post', 'class' => 'form-horizontal', 'id' => 'newFieldForm',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <input name="path" type="hidden" value="{{ $path }}">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group {{$errors->has('table_name') ? 'has-error' : ''}}">
                                {!! Form::label('table_name','Table Name',['class'=>'col-md-4 text-left']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('table_name', '' ,['class'=>'form-control input-sm required','placeholder'=>'Eg. table_name']) !!}
                                    {!! $errors->first('table_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <table id="newTableTbl" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                        <thead class="alert alert-info">
                        <tr>
                            <th valign="top" class="text-center valigh-middle">Column Name <span class="required-star"></span></th>
                            <th valign="top" class="text-center valigh-middle">Type <span class="required-star"></span> </th>
                            <th width="10%" valign="top" class="text-center valigh-middle">Length <span class="required-star"></span></th>
                            <th width="5%" class="valigh-middle text-center">
                                {{--<a class="btn btn-xs btn-primary addTableRows" onclick="addTableRow('newTableTbl', 'rowTblCount0');">--}}
                                    {{--<i class="fa fa-plus"></i></a>--}}
                                <span class="hashs">#</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($csvFields) > 0)
                            <?php $srl=0; ?>
                            @foreach($csvFields as $field)
                                <tr id="rowTblCount{{ $srl }}">
                                    <td>
                                        {!! Form::text('column['.$srl.']',$field, ['maxlength'=>'100','class' => 'form-control input-sm ',
                                        'placeholder' => 'Eg. column_name']) !!}
                                        {!! $errors->first('column','<span class="help-block">:message</span>') !!}
                                    </td>
                                    <td>
                                        {!! Form::select('type['.$srl.']', $type,'', ['maxlength'=>'40', 'class' => 'form-control input-sm typeSelect required',
                                        'placeholder' => 'Select One']) !!}
                                        {!! $errors->first('type','<span class="help-block">:message</span>') !!}
                                    </td>
                                    <td>
                                        {!! Form::text('length['.$srl.']','', ['maxlength'=>'100', 'class' => 'form-control input-sm required onlyNumber',
                                        'placeholder' => 'Eg. 11']) !!}
                                        {!! $errors->first('length','<span class="help-block">:message</span>') !!}
                                    </td>

                                    <td>
                                        @if($srl==0)
                                            <a class="btn btn-xs btn-primary addTableRows" onclick="addTableRow('newTableTbl', 'rowTblCount{{ $srl }}');">
                                                <i class="fa fa-plus"></i></a>
                                        @else
                                            <a class="btn btn-xs addTableRows btn-danger" onclick="removeTableRow('newTableTbl','rowTblCount{{ $srl }}')">
                                                <i class="fa fa-times"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                <?php $srl++; ?>
                            @endforeach
                        @else
                            <tr id="rowTblCount" style="display: none;">
                                <td>
                                    {!! Form::text('column[]','', ['maxlength'=>'100','class' => 'form-control input-sm required',
                                    'placeholder' => 'Eg. column_name']) !!}
                                    {!! $errors->first('column','<span class="help-block">:message</span>') !!}
                                </td>
                                <td>
                                    {!! Form::select('type[]', $type,'', ['maxlength'=>'40', 'class' => 'form-control input-sm required typeSelect',
                                    'placeholder' => 'Select One']) !!}
                                    {!! $errors->first('type','<span class="help-block">:message</span>') !!}
                                </td>
                                <td>
                                    {!! Form::text('length[]','', ['maxlength'=>'100', 'class' => 'form-control input-sm required onlyNumber',
                                    'placeholder' => 'Eg. 11']) !!}
                                    {!! $errors->first('length','<span class="help-block">:message</span>') !!}
                                </td>

                                <td>
                                    <a class="btn btn-xs btn-primary addTableRows" onclick="addTableRow('newTableTbl', 'rowTblCount');">
                                    <i class="fa fa-plus"></i></a>
                                    {{--<a class="btn btn-xs addTableRows btn-danger" onclick="removeTableRow('newTableTbl','rowTblCount0')">--}}
                                        {{--<i class="fa fa-times"></i></a>--}}
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>

                    <div class="modal-footer clearfix">
                        <button onclick="saveAction()" type="submit" class="btn btn-sm btn-primary pull-right">Create Table</button>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>

@section('footer-script')
    @include('partials.datatable-scripts')

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <script>
        $(document).ready(function(){
            $("#newFieldForm").find('input[name^="column"]').removeClass('engOnly').addClass('required');
        });

        function saveAction(){
            $('#newTableTbl').find('#rowTblCount').remove();
            //$("#newFieldForm").find('input').removeClass('engOnly').addClass('required');

        }
        function addTableRow(tableID, templateRow) {
            //rowCount++;
            //Direct Copy a row to many times
            var x = document.getElementById(templateRow).cloneNode(true);
            x.id = "";
            x.style.display = "";
            var table = document.getElementById(tableID);
            var rowCount = $('#' + tableID).find('tr').length - 1;
            var lastTr = $('#' + tableID).find('tr').last().attr('data-number');
            var production_desc_val = $('#' + tableID).find('tr').last().find('.production_desc_1st').val();
            if (lastTr != '' && typeof lastTr !== "undefined") {
                rowCount = parseInt(lastTr) + 1;
            }
            //var rowCount = table.rows.length;
            //Increment id
            var rowCo = rowCount;
            var idText = 'rowCount' + tableID + rowCount;
            x.id = idText;
            $("#" + tableID).append(x);
            //get select box elements
            var attrSel = $("#" + tableID).find('#' + idText).find('select');
            //edited by ishrat to solve select box id auto increment related bug
            for (var i = 0; i < attrSel.length; i++) {
                var nameAtt = attrSel[i].name;
                var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
                attrSel[i].name = repText;
            }
            attrSel.val(''); //value reset
            // end of  solving issue related select box id auto increment related bug by ishrat

            //get input elements
            var attrInput = $("#" + tableID).find('#' + idText).find('input');
            for (var i = 0; i < attrInput.length; i++) {
                var nameAtt = attrInput[i].name;
                //increment all array element name
                var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
                attrInput[i].name = repText;
            }
            attrInput.val(''); //value reset
            //edited by ishrat to solve textarea id auto increment related bug
            //get textarea elements
            var attrTextarea = $("#" + tableID).find('#' + idText).find('textarea');
            for (var i = 0; i < attrTextarea.length; i++) {
                var nameAtt = attrTextarea[i].name;
                //increment all array element name
                var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
                attrTextarea[i].name = repText;
                $('#' + idText).find('.readonlyClass').prop('readonly', true);
            }
            attrTextarea.val(''); //value reset
            // end of  solving issue related textarea id auto increment related bug by ishrat
            attrSel.prop('selectedIndex', 0);
            if ((tableID === 'machinaryTbl' && templateRow === 'rowMachineCount0') || (tableID === 'machinaryTbl' && templateRow === 'rowMachineCount')) {
                $("#" + tableID).find('#' + idText).find('select.m_currency').val("107");  //selected index reset
            } else {
                attrSel.prop('selectedIndex', 0);  //selected index reset
            }
            //$('.m_currency ').prop('selectedIndex', 102);
            //Class change by btn-danger to btn-primary
            $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
                    .attr('onclick', 'removeTableRow("' + tableID + '","' + idText + '")');
            $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
            $('#' + tableID).find('tr').last().attr('data-number', rowCount);

            $("#" + tableID).find('#' + idText).find('.onlyNumber').on('keydown', function (e) {
                //period decimal
                if ((e.which >= 48 && e.which <= 57)
                            //numpad decimal
                        || (e.which >= 96 && e.which <= 105)
                            // Allow: backspace, delete, tab, escape, enter and .
                        || $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
                            // Allow: Ctrl+A
                        || (e.keyCode == 65 && e.ctrlKey === true)
                            // Allow: Ctrl+C
                        || (e.keyCode == 67 && e.ctrlKey === true)
                            // Allow: Ctrl+V
                        || (e.keyCode == 86 && e.ctrlKey === true)
                            // Allow: Ctrl+X
                        || (e.keyCode == 88 && e.ctrlKey === true)
                            // Allow: home, end, left, right
                        || (e.keyCode >= 35 && e.keyCode <= 39))
                {
                    var thisVal = $(this).val();
                    if (thisVal.indexOf(".") != -1 && e.key == '.') {
                        return false;
                    }
                    $(this).removeClass('error');
                    return true;
                }
                else {
                    $(this).addClass('error');
                    return false;
                }
            });
        } // end of addTableRow() function


        function removeTableRow(tableID, removeNum) {
            $('#' + tableID).find('#' + removeNum).remove();
        }


        $(document).ready(function () {
            $("#newFieldForm").validate({
                rules: {

                }
            });
        });
        $("body").on('change','.typeSelect',function(){
            var selectValue = $(this).val();
            if(selectValue == 'text' || selectValue == 'int' || selectValue == 'float'){
                var inp = $(this).closest('tr').find('input[name^="length"]').removeClass('required error');
                inp.attr('readonly',true);
                inp.val('');
            }else{
                var inp = $(this).closest('tr').find('input[name^="length"]').addClass('required');
                inp.attr('readonly',false);
            }
        });
        $("body").find('.typeSelect').trigger('change');


    </script>

@endsection