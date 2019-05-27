/* Function to allow numeric entries only with decimal points (.) */
$(document).ready(function () {
    $('.onlyNumber').on('keydown', function (e) {
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
        else
        {
            $(this).addClass('error');
            return false;
        }
    }).on('paste', function (e) {
        var $this = $(this);
        setTimeout(function () {
            $this.val($this.val().replace(/[^0-9]/g, ''));
        }, 4);
    }).on('keyup', function (e) {
        var $this = $(this);
        setTimeout(function () {
            $this.val($this.val().replace(/[^0-9]/g, ''));
        }, 4);
    });
}); /***************Document ready ended***********/

/********************The functionalit to allow the applicants remove their attached documents***********************/
function makeBlank(id) {
    $('#validate_field_' + id).val('');
    $('.saved_file_' + id).html('');
    $('#other_doc_name_' + id).val('');
    //document.getElementById("other_doc_name_"+id).value='';



}

function ConfirmDeleteFile(id) {
    var sure_del = confirm("Are you sure you want to delete this file?");
    if (sure_del) {
        makeBlank(id);
    } else {
        return false;
    }
}
function makeBlank_value(id) {
    document.getElementById("validate_field_" + id).value = '';
    document.getElementById("file" + id).value = '';
    $('.saved_file_' + id).html('');
    $('.span_validate_field_' + id).html('');
}
function EmptyFile(id) {
    var sure_del = confirm("Are you sure you want to delete this file?");
    if (sure_del) {
        makeBlank_value(id);
    } else {
        return false;
    }
}
/*****************End of the functionality to allow the applicants to remove their attached documents*******************/

/****************For showing tooltips in the function list page*******************/
function toolTipFunction() {
    $('[data-toggle="tooltip"]').tooltip();
}

/*****************For advance search for getting industrial categories in each economic zone*******************/
function getIndustrialList(thisItem) {
    var thisVal = thisItem.value;
    var valid_applicant_name = $('#valid_applicant_name');
    var _token = $('input[name="_token"]').val();
    $.ajax({
        url: base_url + '/project-clearance/get-companies',
        type: 'post',
        data: {
            _token: _token,
            eco_zone_id: thisVal,
        },
        dataType: 'json',
        success: function (response) {
            valid_applicant_name.html('');
            // success
            var option = '<option value="">Select  a company</option>';
            if (response.responseCode == 1) {
                $.each(response.data, function (id, value) {
                    option += '<option value="' + id + '">' + value + '</option>';
                });
            }
            else {
                valid_applicant_name.append('<option value="">No approved company is found!</option>');
            }
            valid_applicant_name.html(option);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        },
        beforeSend: function (xhr) {
            //console.log('before send');
        },
        complete: function () {
            //completed
        }
    });
}