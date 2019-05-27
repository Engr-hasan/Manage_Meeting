@extends('layouts.front')
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Preview Form</title>
    <!--    <link  rel="stylesheet" type="text/css" href="assets/css/style.css" media="all">-->
    <link rel="stylesheet" href="{{ asset("assets/stylesheets/styles.css") }}" media="all"/>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="{{ asset("assets/scripts/bootstrap.min.js") }}"></script>
    <script language="javascript"> //var jq_my = jQuery.noConflict(true);</script>

</head>
<body>
<div align="right">
    <input type="button" value="&nbsp;&nbsp;&nbsp; Close &nbsp; &nbsp;&nbsp;" align="right" onClick="CloseMe()" id="closeBtn"  class="btn-submit-1 btn btn-danger custom_update" style="position: fixed;right: 15px;top: 4px;;z-index:999;"/>
</div>
<div id="previewDiv"></div>
<div align="center">
    <input type="button" style="font-size: 18px;"value="Go Back" id="backBtn" onclick="CloseMe()" class="btn-submit-1 btn btn-danger" />
    <input type="button"  style="font-size: 18px;"value="Submit" id="submitFromPreviewBtn" onclick="" class="btn-submit-1 btn btn-primary" />
</div>
</body>
</html>
<script language="javascript">
    function commaSeparateNumber(val){
        while (/(\d+)(\d{3})/.test(val.toString())){
            val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
        }
        return val;
    }
    $(function () {
        $('#submitFromPreviewBtn').click(function (e) {
            window.opener.document.getElementById("app-form").setAttribute("target", "_self");
            window.opener.jQuery("#app-form").submit();
            window.close();
        });
    });
    window.opener.$('select').each(function (index) {
        var text = $(this).find('option:selected').text();
        var id = $(this).attr("id");
        var val = $(this).val();
        window.opener.jQuery().replaceWith("<option value='" + val + "' selected>" + text + "</option>");
    });
    //'#' + id + ' option:[value="' + val + '"]'
    window.opener.jQuery("#inputForm :input[type=text]").each(function (index) {

        $(this).attr("value", $(this).val());
    });
    window.opener.jQuery("textarea").each(function (index) {
        $(this).text($(this).val());
    });

    window.opener.jQuery("select").css({
        "border": "none",
        "background": "#fff",
        "pointer-events": "none",
        "box-shadow": "none",
        "-webkit-appearance": "none",
        "-moz-appearance": "none",
        "appearance": "none"
    });

    window.opener.jQuery("fieldset").css({"display": "block"});
    window.opener.jQuery("#full_same_as_authorized").css({"display": "none"});
    window.opener.jQuery(".actions").css({"display": "none"});
    window.opener.jQuery(".steps").css({"display": "none"});
    window.opener.jQuery(".draft").css({"display": "none"});
    window.opener.jQuery(".title ").css({"display": "none"});
    //    window.opener.jq_my("select").prop('disabled', true);
    document.getElementById("previewDiv").innerHTML = window.opener.document.getElementById("inputForm").innerHTML;
    //   JavaScript Document
    function printThis(ob) {
        //$("#closeBtn").hide();
        //$(ob).hide();
        print();
    }
    $('#showPreview').remove();
    $('#save_btn').remove();
    $('#save_draft_btn').remove();
    $('.stepHeader,.calender-icon,.pss-error').remove();
    $('.required-star').removeClass('required-star');
    $('input[type=hidden]').remove();
    $('.panel-orange > .panel-heading').css('margin-bottom', '10px');
    $('.input-group-addon').css({"visibility": "hidden"});
    $('.hiddenDiv').css({"visibility": "hidden"});
    $('#invalidInst').html('');
    $('#previewDiv .btn').each(function (){
        $(this).replaceWith("");
    });
    jQuery('#previewDiv').find('input:not([type=radio][type=hidden][type=file][name=acceptTerms]), textarea').each(function ()
    {
        var allClass = jQuery(this).attr('class');
        if(allClass.match("onlyNumber")){
            var thisVal = commaSeparateNumber(this.value);
        } else {
            var thisVal = this.value;
        }
        jQuery(this).replaceWith('<span>' + thisVal + '</span>');
    });

    jQuery('#previewDiv').find('input:[type=file]').each(function ()
    {
        jQuery(this).replaceWith("<span>" + this.value + "</span>");
    });

    jQuery('#previewDiv #acceptTerms-2').attr("onclick", 'return false').prop("checked", true).css('margin-left', '5px');
    jQuery('#previewDiv').find('input:[type=radio]').each(function ()
    {
        jQuery(this).attr('disabled', 'disabled');
    });


    jQuery("select").replaceWith(function ()
    {
        return jQuery(this).find('option:selected').text();
    });
    $(".hashs").replaceWith("");

    ///Change in opener
    window.opener.jQuery('#home').fadeOut("slow");
    //home is the id of body in template page. It may be an id of div or any element
    jQuery(window).unload(function () {
        //window.opener.jq_my('#home').css({"display": "none"});
    });


    function CloseMe()
    {
        window.opener.jQuery("fieldset").css({"display": "none"});
        window.opener.jQuery(".actions").css({"display": "block"});
        window.opener.jQuery(".steps").css({"display": "block"});
        window.opener.jQuery(".draft").css({"display": "block"});
        window.opener.jQuery(".title ").css({"display": "block"});
        window.opener.jQuery('.input-group-addon').css({"visibility": "visible"});
        window.opener.jQuery("#app-form-p-3").css({"display": "block"});
        window.opener.jQuery("#steps-uid-0-p-3").css({"display": "block"});
        window.opener.jQuery(".last").addClass('current');
        window.opener.jQuery('#home').css({"display": "block"});
        window.opener.jQuery("select").css({
            "border": 'inherit',
            "background": '#fff',
            "pointer-events": 'inherit',
            "box-shadow": 'inherit',
            "-webkit-appearance": 'menulist',
            "-moz-appearance": 'menulist',
            "appearance": 'menulist'
        });
        window.close();
    }
</script>