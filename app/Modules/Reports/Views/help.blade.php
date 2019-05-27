<div class="col-md-12">
    <pre>
    "{$" =variable start mark
    "}" =variable end mark
    "sess_" =access data from session user
    "rpt_" =report variable to be remembered
    "|" =additional format information for input form (2nd =Caption, 3rd=data type eg. date,number,list,bank,agency)
        *In case of <strong>list</strong> you can use SQL or Comma Seperated value as 4th parameter
    </pre>
</div>
<table class="table table-bordered table-striped">
    <tr>
        <th>Variable Name</th>
        <th>SQL</th>
        <th>Remarks</th>
    </tr>
    <tr>
        <td>sess_user_id</td>
        <td></td>
        <td>User session variable</td>
    </tr>
    <tr>
        <td>sess_user_type</td>
        <td></td>
        <td>User session variable</td>
    </tr>
    <tr>
        <td>sess_user_sub_type</td>
        <td></td>
        <td>User session variable for bank_id or agency_id or udc_id</td>
    </tr>
    <tr>
        <td>sess_code</td>
        <td></td>
        <td>User session variable</td>
    </tr>
    <tr>
        <td>sess_district</td>
        <td></td>
        <td>User session variable</td>
    </tr>
    <tr>
        <td>sess_thana</td>
        <td></td>
        <td>User session variable</td>
    </tr>
    <tr>
        <td>rpt_*</td>
        <td>'{$rpt_*}'</td>
        <td>=,>,<,Like</td>
    </tr>
    <tr>
        <td>rpt_*</td>
        <td>'{$rpt_*}'</td>
        <td>{$rpt_bank_id|Select Bank|bank}</td>
    </tr>
    <tr>
        <td>rpt_*</td>
        <td>'{$rpt_*}'</td>
        <td>{$rpt_agencyid|Select Agency|agency}</td>
    </tr>
</table>

<table class="table table-bordered table-striped">
    <caption>SMS Template Variable</caption>

    <tr>
        <td>pilgrim</td>
        <td>{$pilgrim}</td>
        <td>full_name_english</td>
    </tr>
    <tr>
        <td>token_no</td>
        <td>{$token_no}</td>
        <td>Pilgrim Token no</td>
    </tr>
    <tr>
        <td>bank_name</td>
        <td>{$bank_name}</td>
        <td>bank_name</td>
    </tr>
    <tr>
        <td>serial_no</td>
        <td>{$serial_no}</td>
        <td>serial_no</td>
    </tr>
    <tr>
        <td>birth_date</td>
        <td>{$birth_date}</td>
        <td>birth_date</td>
    </tr>
    <tr>
        <td>validity</td>
        <td>{$validity}</td>
        <td>validity</td>
    </tr>
    <tr>
        <td>Note</td>
        <td colspan="2">All of variables may not be available for all template</td>
    </tr>
</table>