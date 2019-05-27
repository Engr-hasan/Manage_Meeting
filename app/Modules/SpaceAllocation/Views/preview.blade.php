@extends('layouts.front')
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Preview Form</title>
    <link rel="stylesheet" href="/assets/stylesheets/styles.css" media="all"/>
    <script src="https://code.jquery.com/jquery-1.6.2.js"></script>
    <script src="/assets/scripts/bootstrap.min.js"></script>
    <script language="javascript"> var jQuery = jQuery.noConflict(true);</script>
</head>
<body>
<div align="right">
    <input type="button" value="&nbsp;&nbsp;&nbsp; Close &nbsp; &nbsp;&nbsp;" align="right" onClick="CloseMe()" id="closeBtn"  class="btn-submit-1 btn btn-danger" style="position: fixed;right: 0;z-index:999;"/>
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

    jQuery(function () {
        jQuery('#submitFromPreviewBtn').click(function (e) {

            window.opener.document.getElementById("appClearenceForm").setAttribute("target", "_self");
            window.opener.jQuery("#appClearenceForm").submit();
            window.opener.jQuery("#appClearenceForm").submit();
            window.close();
        });
    });

    // Get Data from Select Box
    window.opener.$('select').each(function (index) {
        var text = jQuery(this).find('option:selected').text();
        var id = jQuery(this).attr("id");
        var val = jQuery(this).val();
        jQuery(this).find('option:selected').replaceWith("<option value='" + val + "' selected>" + text + "</option>");
//        window.opener.jQuery('#' + id + ' option:[value="' + val + '"]').replaceWith("<option value='" + val + "' selected>" + text + "</option>");
    });

    // Get Data from Text Box
    window.opener.jQuery("#inputForm :input[type=text]").each(function (index) {
        jQuery(this).attr("value", jQuery(this).val());
    });


    // Get Data from Textarea
    window.opener.jQuery("textarea").each(function (index) {
        jQuery(this).text(jQuery(this).val());
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
        print();
    }
    jQuery('#showPreview').remove();
    jQuery('#save_btn').remove();
    jQuery('#save_draft_btn').remove();
    jQuery('.stepHeader,.calender-icon,.pss-error').remove();
    jQuery('.required-star').removeClass('required-star');
//    jQuery('input[type=hidden]').not("input[type=hidden][name=identificationValue]").remove();
//    jQuery('input[type=hidden]').not("input[type=hidden][name=eiaCertValue]").remove();
    jQuery('input[type=hidden]').not("input[type=hidden][name=eiaCertValue],input[type=hidden][name=eia_cer_fileValue], input[type=hidden][name=identificationValue], input[type=hidden][name=tb_file0Value], input[type=hidden][name=tb_file1Value]").remove();
    jQuery('.panel-orange > .panel-heading').css('margin-bottom', '10px');
    jQuery('.input-group-addon').css({"visibility": "hidden"});
    jQuery('.hiddenDiv').css({"visibility": "hidden"});
    jQuery('#invalidInst').html('');
    //    jq_my("#docTabs").tab('show');
    jQuery('#previewDiv .btn').each(function () {
        jQuery(this).replaceWith("");
    });

    jQuery('#appClearenceForm :input').attr('disabled', true);

    jQuery('#previewDiv').find('input:not([type=radio][type=hidden][type=file][name=acceptTerms]), textarea').each(function ()
    {
        var allClass = jQuery(this).attr('class');
        if (allClass.match("onlyNumber")) {
            if (allClass.match("nocomma")) {
                var thisVal = this.value;
            }
            else {
                var thisVal = commaSeparateNumber(this.value);
            }

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

    jQuery(".hashs").replaceWith("");

    ///Change in opener
    window.opener.jQuery('#home').fadeOut("slow");
    //home is the id of body in template page. It may be an id of div or any element
    jQuery(window).unload(function () {
        //window.opener.jq_my('#home').css({"display": "none"});
    });



    //checked identification type
    jQuery('#previewDiv').find('#identificationValue').each(function () {
        var location = jQuery(this).val();
        if(location == 'nid'){
            jQuery('#previewDiv').find('.nid').prop('checked',true);
            jQuery('#previewDiv').find('.passport').prop('checked',false);
        }else{
            jQuery('#previewDiv').find('.passport').prop('checked',true);
            jQuery('#previewDiv').find('.nid').prop('checked',false);
        }
    });


    //checked EIA
    jQuery('#previewDiv').find('#eiaCertValue').each(function () {

        var eiaValue = jQuery(this).val();
        if(eiaValue == 'yes'){
            jQuery('#previewDiv').find('#eia_cer_exist_yes').prop('checked',true);
            jQuery('#previewDiv').find('#eia_cer_exist_no').prop('checked',false);
        }else{
            jQuery('#previewDiv').find('#eia_cer_exist_no').prop('checked',true);
            jQuery('#previewDiv').find('#eia_cer_exist_yes').prop('checked',false);
        }
    });


    //show EIA cert name
    jQuery('#previewDiv').find('#eia_cer_fileValue').each(function(){
        jQuery('#previewDiv').find('.showEiaCert').text(this.value.split(/[\\]+/).pop());
    });


    // Trade body file view
    jQuery('#previewDiv').find('#tb_file0Value').each(function(){
        jQuery('#previewDiv').find('#showFileName0').text(this.value.split(/[\\]+/).pop());
    });
    jQuery('#previewDiv').find('#tb_file1Value').each(function(){
        jQuery('#previewDiv').find('#showFileName1').text(this.value.split(/[\\]+/).pop());
    });

    function CloseMe()
    {
        window.opener.jQuery("fieldset").css({"display": "none"});
        window.opener.jQuery(".actions").css({"display": "block"});
        window.opener.jQuery(".steps").css({"display": "block"});
        window.opener.jQuery(".draft").css({"display": "block"});
        window.opener.jQuery(".title ").css({"display": "block"});
        window.opener.jQuery('.input-group-addon').css({"visibility": "visible"});
        window.opener.jQuery("#appClearenceForm-p-3").css({"display": "block"});
        window.opener.jQuery(".last").addClass('current');
        window.opener.jQuery('body').css({"display": "block"});
        window.opener.jQuery("select").css({
            "border": '1px solid #ccc',
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