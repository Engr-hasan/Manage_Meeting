@extends('layouts.admin')
@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('CoBrandedCard');
    if (!ACL::isAllowed($accessMode, $mode)) {
        die('You have no access right! Please contact with system admin if you have any query.');
    }

    $user_type = CommonFunction::getUserType();

    //    $allRequestVal = old();
    //    if(count($allRequestVal)>0){
    //        dd($allRequestVal);
    //    }
    ?>
    @include('partials.modal')
    <style>
        input.error[type="radio"]{
            outline: 2px solid red;
        }
    </style>
    <section class="content" id="LoanLocator">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
                    {{--{!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}--}}

                    @include('ProcessPath::batch-process')

    </section>


@endsection
@section('footer-script')

    <script type="text/javascript">
        $("#appClearenceForm").validate();
        $("#pr_division").change(function () {
            var divisionId = $(this).val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/users/get-district-by-division",
                data: {
                    divisionId: divisionId
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#pre_distract").html(option);
                    $(self).next().hide();
                }
            });
        });
        // get thana list by district id for Office Address

        $("#pre_distract").change(function () {
            var self = $(this);
            var districtId = $(this).val();
            if (districtId !== '') {
                $(this).after('<span class="loading_data">Loading...</span>');
                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url(); ?>/public/assets/images/ajax-loader.gif' alt='loading' />");
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                    data: {
                        districtId: districtId
                    },
                    success: function (response) {
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                // for edit list , applicant thana
                                {{--if (id == '{{$users->thana}}'){--}}
                                {{--option += '<option value="'+ id + '" selected>' + value + '</option>';--}}
                                {{--}--}}
                                //                                    else {
                                option += '<option value="' + id + '">' + value + '</option>';
//                                    }
                            });
                        }
                        $("#pre_thana").html(option);
                        self.next().hide();
                    }
                });
            }
        });



        $("#per_division").change(function () {
            var divisionId = $(this).val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/users/get-district-by-division",
                data: {
                    divisionId: divisionId
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#per_distract").html(option);
                    $(self).next().hide();
                }
            });
        });
        $("#per_distract").change(function () {
            var self = $(this);
            var districtId = $(this).val();
            if (districtId !== '') {
                $(this).after('<span class="loading_data">Loading...</span>');
                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url(); ?>/public/assets/images/ajax-loader.gif' alt='loading' />");
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                    data: {
                        districtId: districtId
                    },
                    success: function (response) {
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#per_thana").html(option);
                        self.next().hide();
                    }
                });
            }
        });



        $("#bank").change(function () {
            var bank_id = $(this).val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/loan-locator/get-branch-by-bank",
                data: {
                    bank_id: bank_id
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#branch").html(option);
                    $(self).next().hide();
                }
            });

        });
        function uploadDocument(targets, id, vField, isRequired) {
            var inputFile = $("#" + id).val();
            if (inputFile == '') {
                $("#" + id).html('');
                document.getElementById("isRequired").value = '';
                document.getElementById("selected_file").value = '';
                document.getElementById("validateFieldName").value = '';
                document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
                if ($('#label_' + id).length)
                    $('#label_' + id).remove();
                return false;
            }

            try {
                document.getElementById("isRequired").value = isRequired;
                document.getElementById("selected_file").value = id;
                document.getElementById("validateFieldName").value = vField;
                document.getElementById(targets).style.color = "red";
                var action = "{{url('/loan-locator/upload-document')}}";
                $("#" + targets).html('Uploading....');
                var file_data = $("#" + id).prop('files')[0];
                var form_data = new FormData();
                form_data.append('selected_file', id);
                form_data.append('isRequired', isRequired);
                form_data.append('validateFieldName', vField);
                form_data.append('_token', "{{ csrf_token() }}");
                form_data.append(id, file_data);
                $.ajax({
                    target: '#' + targets,
                    url: action,
                    dataType: 'text', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function (response) {
                        $('#' + targets).html(response);
                        var fileNameArr = inputFile.split("\\");
                        var l = fileNameArr.length;
                        if ($('#label_' + id).length)
                            $('#label_' + id).remove();
                        var doc_id = parseInt(id.substring(4));
                        var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + ' <a href="javascript:void(0)" onclick="EmptyFile(' + doc_id + ')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
//                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                        $("#" + id).after(newInput);
                        //check valid data
                        var validate_field = $('#' + vField).val();
                        if (validate_field == '') {
                            document.getElementById(id).value = '';
                        }
                    }
                });
            } catch (err) {
                document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
            }
        }

        <?php if ($viewMode == 'on') { ?>
        $(".MoreInfo").click(function () {
            $(this).closest("tr").next().show();

        });

        $('#inputForm select').each(function (index) {
            var text = $(this).find('option:selected').text();
            var id = $(this).attr("id");
            var val = $(this).val();
            $('#' + id + ' option:selected').replaceWith("<option value='" + val + "' selected>" + text + "</option>");
        });
        $("#inputForm :input[type=text]").each(function (index) {
            $(this).attr("value", $(this).val());
        });
        $("#inputForm textarea").each(function (index) {
            $(this).text($(this).val());
        });

        $("#inputForm select").css({
            "border": "none",
            "background": "#fff",
            "pointer-events": "none",
            "box-shadow": "none",
            "-webkit-appearance": "none",
            "-moz-appearance": "none",
            "appearance": "none"
        });

        $("#inputForm .actions").css({"display": "none"});
        $("#inputForm .draft").css({"display": "none"});
        $("#inputForm .title ").css({"display": "none"});
        //document.getElementById("previewDiv").innerHTML = document.getElementById("projectClearanceForm").innerHTML;

        $('#inputForm #showPreview').remove();
        $('#inputForm #save_btn').remove();
        $('#inputForm #save_draft_btn').remove();
        $('#inputForm .stepHeader, #inputForm .calender-icon,#inputForm .pss-error,#inputForm .hiddenDiv, #inputForm .input-group-addon').remove();
        $('#inputForm .required-star').removeClass('required-star');
        $('#inputForm input[type=hidden], #inputForm input[type=file]').remove();
        $('#inputForm .panel-orange > .panel-heading').css('margin-bottom', '10px');
        $('#invalidInst').html('');

        $('#inputForm').find('input:not(:checked),textarea').each(function () {
            if (this.value != '') {
                var displayOp = ''; //display:block
            } else {
                var displayOp = 'display:none';
            }

            if ($(this).hasClass("onlyNumber") && !$(this).hasClass("nocomma")) {
                var thisVal = commaSeparateNumber(this.value);
                $(this).replaceWith("<span class='onlyNumber " + this.className +
                    "' style='background-color:#ddd !important;border-radius:3px;padding:6px; height:auto; margin-bottom:2px;"
                    + displayOp + "'>" + thisVal + "</span>");
            } else {
                $(this).replaceWith("<span class='" + this.className + "' style='background-color:#ddd;padding:6px; height:auto; margin-bottom:2px;"
                    + displayOp + "'>" + this.value + "</span>");
            }
        });

        $('#inputForm').find('textarea').each(function () {
            var displayOp = '';
            if (this.value != '') {
                displayOp = ''; //display:block
            } else {
                displayOp = 'display:none';
            }
            $(this).replaceWith("<span class='" + this.className + "' style='background-color:#ddd;height:auto;padding:6px;margin-bottom:2px;"
                + displayOp + "'>" + this.value + "</span>");
        });


        $('#inputForm .btn').not('.show-in-view,.documentUrl').each(function () {
            $(this).replaceWith("");
        });

        $('#inputForm').find('input[type=radio]').each(function () {
            jQuery(this).attr('disabled', 'disabled');
        });

        $("#inputForm select").replaceWith(function () {
            var selectedText = $(this).find('option:selected').text().trim();
            var displayOp = '';
            if (selectedText != '' && selectedText != 'Select One') {
                displayOp = ''; //display:block
            } else {
                displayOp = 'display:none';
            }

            return "<span class='" + this.className + "' style='background-color:#ddd;height:auto;padding:6px;margin-bottom:2px;"
                + displayOp + "'>" + selectedText + "</span>";
        });

        $("#inputForm select").replaceWith(function () {
            var selectedText = $(this).find('option:selected').text();
            return "<span style='background-color:#ddd;width:68%; height:auto; margin-bottom:2px;padding:6px;display:block;'>"
                + selectedText + "</span>";
        });

        function commaSeparateNumber(val) {
            while (/(\d+)(\d{3})/.test(val.toString())) {
                val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
            }
            return val;
        }

        <?php } ?> /* viewMode is on */
    </script>
@endsection