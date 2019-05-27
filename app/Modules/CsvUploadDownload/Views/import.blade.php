<style>
    .font-ok {font-weight: normal !important;}
</style>

<div class="modal-header" style="background: #D5EDF7">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel">Upload Form</h4>
</div>

{!! Form::open(array('url' => '/csv-upload/upload-csv-file','method' => 'post', 'class' => 'form-horizontal', 'id' => 'bulkUpload',
'enctype' =>'multipart/form-data', 'files' => 'true')) !!}

<div class="modal-body">
    <div class="row">

        <div class="col-lg-12">
            <div class="form-group {{$errors->has('tables') ? 'has-error' : ''}}">
                {!! Form::label('tables','Select Table',['class'=>'col-sm-3 text-left required-star']) !!}
                <div class="col-md-9 col-sm-9">

                    <div class="col-md-8">
                        <label class="font-ok">
                            <div class="col-md-1">
                                {!! Form::radio('table_type', 'list_of_table', true, ['class'=>' ']) !!} 
                            </div>
                            <div class="col-md-10">
                                {!! Form::select('tables', $tables , '', ['class'=>'form-control input-sm tables','id'=>'tables','placeholder'=>'Select One']) !!}
                                {!! $errors->first('tables','<span class="help-block">:message</span>') !!}
                            </div>
                        </label>
                    </div>
                    <br/>

                    <div class="col-md-8">
                        <label class="font-ok">
                            <div class="col-md-1">
                                {!! Form::radio('table_type', 'new_table', '', ['class'=>' ']) !!} 
                            </div>
                            <div class="col-md-10 text-left"> Create New Table </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group {{$errors->has('import_request') ? 'has-error' : ''}}">
                {!! Form::label('import_request','File',['class'=>'col-sm-3 text-left required-star']) !!}
                <div class="col-md-9 col-sm-9">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            {!! Form::file('import_request', '' ,['class'=>'form-control required ','placeholder'=>'Import Request']) !!}
                            {!! $errors->first('import_request','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="alert alert-danger" style="font-size:13px;">
                <strong>Note:</strong> Upload only .csv, .xls or .xlsx file. For any blank column, write <b style="color:red">N/A</b>,  otherwise data 
                will be mismatched. To follow the given sample file, you can <a href="#" title="Sample file">
                    <strong>click here</strong></a>.
            </div>
        </div>

    </div>
</div>

<div class="modal-footer clearfix" style="background: #eee">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary pull-right">Upload</button>
</div>

{!! Form::close() !!}

<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>
<script>

    $(document).ready(function () {

        $.validator.addMethod("tbl_type",function(tableType){
            var selectedTable = $('#tables').val();
            if(tableType == 'new_table'){
                return true;
            }else{
                if(selectedTable != ''){
                    return true;
                }
            }
            return false;
        });

        $("#bulkUpload").validate({
            rules:{
                import_request:"required",
                table_type:{
                    required:true,
                    tbl_type:true
                }
            }
        });
    });
</script>