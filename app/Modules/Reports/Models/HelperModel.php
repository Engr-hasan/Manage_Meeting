<?php namespace App\Modules\Reports\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class HelperModel extends Model {


//Helper functions add here
    function getDbUserName() {
        return 'user004';
//    $user='mysql-main';
//    echo \Illuminate\Support\Facades\Session::has('mysql_access');
//    echo 'session=';
//        echo session('mysql_access');
//    die();
//    if(session('mysql_access')){
//        $user=session('mysql_access');
//    }
//    return '';
    }

    function getDbUserPassword() {
        return 'Alter@!@#';
    }

    function getList($sql){
        $values=Array();
        $i=0;
        if(strtoupper(substr($sql.'      ',0,7))=='SELECT ' && strpos(';',$sql)<=1){
            $rs=DB::select($sql);
            $fields=array_keys((array)$rs[0]);
            if(count($fields)>1) $i=1;
            foreach($rs as $row){
                $values[$row->$fields[0]]=$row->$fields[$i];
            }
        } else{
            $val=explode(',',$sql);
            for($i=0;$i<count($val);$i++){
                $values[$val[$i]]=$val[$i];
            }
        }
        $data = collect($values);
        return $data;
    }

    function getBank(){
        $data = \App\Modules\Users\Models\Bank::orderBy('name')->lists('name','id');
        //dd($data);
        return $data;
    }

    function getAgency(){
        $data = \App\Modules\Settings\Models\Agency::orderBy('license_no')
            ->select(\Illuminate\Support\Facades\DB::raw("concat(license_no,' ',name) as name"),'id')
            ->lists('name','id');
        return $data;
    }

    function createHTMLTable($arr,$max_length = 25) {
        $arr = json_decode(json_encode($arr), true);
        $table = '<table class="table basicDataTable dataTable" id="dTable_'.time().'"><thead>';
        if(isset($arr[0])) {
            $keys = array_keys($arr[0]);
            $table .= '<tr>';
            foreach ($keys as $value) {
                $table .= '<th>' . $value . '</th>';
            }
            $table .= '</tr></thead><tbody>';
            foreach ($arr as $value) {
                $table .= '<tr>';
                foreach ($value as $value2) {
                    $table .= '<td>' . $this->formatTDValue($value2,$max_length) . '</td>';
                }
                $table .= '</tr>';
            }
        }
        $table .= '</tbody></table>';
        return $table;
    }

    function formatTDValue($cell,$maxwd=25){
        if($cell) {
            if(strlen($cell)>$maxwd) {
                return '<span title="' . $cell . '">' . substr($cell, 0, $maxwd-2) . '...</span>';
            }elseif(strlen($cell)>12){
                return $cell;
            } else if(is_float($cell)){
                return '<span style="text-align:right;" title="'.$cell.'">'.number_format($cell,2).'</span>';
            }elseif(is_numeric($cell)){
                if($cell<10000) {
                    return $cell;
                }elseif($cell<1000000){
                    return '<span style="text-align:right;" title="'.$cell.'">'.number_format($cell,0).'</span>';
                } else {
                    return '<span style="text-align:right;" title="'.$cell.'">'.number_format($cell/1000000,2).'M</span>';
                }
            } else {
                return $cell;
            }
        } else {
            return '&nbsp;';
        }
    }

    function report_gen($id, $data, $report_title, $link = '', $heading = '') {
        $dataTablePara = '';
        $showaction = false;
        $hiddenFields = array();
        $cols = array();
        $count = 0;
        if ($link) {
            $json_data = json_decode($link);
            if (!empty($json_data)) {
                foreach ($json_data as $jrow) {
                    if ($jrow->type == 'link') {
                        $showaction = true;
                    } else if ($jrow->type == 'dataTable') {
                        $dataTablePara = $jrow->properties;
                    } else if ($jrow->type == 'column') {
                        $cols[$jrow->ID]['caption'] = $jrow->caption;
                        $cols[$jrow->ID]['style'] = $jrow->style;
                    } else if ($jrow->type == 'hidden') {
                        $hiddenFields = explode(',', $jrow->fields);
                    } else {
                        $showaction = true;
                    }
                }
            }
        }
        ?>
        <div class="hero-widget well well-sm">
            <?php if ($heading) { ?>
                <div class="report_heading">
                    <div><?php echo $heading; ?></div>
                </div>
            <?php } ?>
            <div id="report_title">
                <h4>
                    <?php echo $report_title . ''; ?>
                </h4>
            </div>
            <?php if (count($data) > 0) { ?>
                <link rel="stylesheet" href="<?php echo url(); ?>assets/css/dataTables.tableTools.css" />
                <script src="<?php echo url(); ?>assets/js/dataTables.tableTools.js"></script>
                <table id="dbt_<?php echo $id; ?>" class="datatable table-rpt-border table table-responsive table-condensed" id="tblQInfo">
                    <thead>
                    <tr>
                        <?php
                        foreach ($data[0] as $key => $value) {
                            if (in_array($key, $hiddenFields)) {
                                continue;
                            }
                            echo '<th';
                            if (isset($cols[$key]['style']))
                                echo ' style="' . $cols[$key]['style'] . '"';
                            echo '>';
//                        echo isset($cols[$key]['caption']) ? $cols[$key]['caption'] : getFieldTitle($key);
//                        echo $cols[$key]['caption'];
                            echo isset($cols[$key]['caption']) ? $cols[$key]['caption'] : ucfirst(str_replace('_', ' ', $key));
                            echo '</th>';
                        }
                        if ($showaction) {
                            echo '<th>Action</th>';
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sl = 0;
                    foreach ($data as $row):
                        $rowdata = array();
                        if ($sl % 2 == 0) {
                            $row_bg_color = 'style="background-color:#FAFAFA"';
                        } else {
                            $row_bg_color = 'style=""';
                        }
//                            if ($count >= $CI->config->item('MAX_DATA')) {
//                                echo '<tfoot><tr><td colspan="5"><b>Showing ' . $CI->config->item('MAX_DATA') . ' rows out of total ' . count($data) . '! Please export as CSV to show all data.</b></td></tr></tfoot>';
//                                break;
//                            } $count++;
                        ?>
                        <tr <?php echo $row_bg_color; ?>>
                            <?php
                            foreach ($row as $key => $field_value):
                                if (in_array($key, $hiddenFields)) {
                                    $rowdata[$key] = $field_value;
                                    continue;
                                }
                                //echo '<td>';
                                echo '<td';
                                if (isset($cols[$key]['style']))
                                    echo ' style="' . $cols[$key]['style'] . ';"';
                                echo '>';
                                if (is_numeric($field_value)) {
                                    echo '<span style="text-align:center;width:100%;float: left;">' . $field_value . '&nbsp;</span>';
                                } else {
                                    echo '<span style="text-align:left;width:100%;float: left;">' . $field_value . '&nbsp;</span>';
                                }
                                echo '</td>';
                                if ($link) {
                                    $rowdata[$key] = $field_value;
                                }
                            endforeach;
                            if ($showaction) {
                                echo '<td>';
                                foreach ($json_data as $jrow) {
                                    if ($jrow->type == 'link') {
                                        $rowdata['baseurl'] = base_url();
                                        echo '<a href="' . ConvPara($jrow->url, $rowdata) . '">' . $jrow->caption . '</a>&nbsp;';
                                    } else if ($jrow->type == 'dataTable') {

                                    } else {
                                        //print_r($jrow);
                                    }
                                }
                                echo '</td>';
                            }
                            ?>
                        </tr>
                        <?php
                        $sl++;
                    endforeach;
                    ?>
                    </tbody>
                </table>

                <?php
            } else {
                echo '<h4 style="text-align: center;color: gray">Data Not Found!</h4>';
            }
            ?>
        </div>

        <?php
        return $count;
    }


    function getWebLink($website){
        if(substr($website,0,4) !='http'){
            $website = 'http://'.$website;
        }
        return $website;
    }

    function mobileMask($mobile){
        if(strlen($mobile)>13) {
            $mobile = substr($mobile, 0, 9) . '***' . substr($mobile, 12);
        }elseif(strlen($mobile)>10) {
            $mobile = substr($mobile, 0, 6) . '***' . substr($mobile, 9);
        }
        return $mobile;
    }

}