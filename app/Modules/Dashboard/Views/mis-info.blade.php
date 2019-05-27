<style>
    .hero-widget { text-align: center; padding-top: 20px; padding-bottom: 10px; }
    .hero-widget .icon { display: block; font-size: 30px; line-height: 20px; margin-bottom: 5px; text-align: center; }
    .hero-widget var { display: block; height: 64px; font-size: 64px; line-height: 64px; font-style: normal; }
    .hero-widget label { font-size: 17px; }
    .hero-widget .options { margin-top: 10px; }
    .panel-body .btn:not(.btn-block) { width:120px;margin-bottom:10px; }
</style>
<div class="container-fluid">
    <div class="row-fluid">
        @if($dashboardObject)
            @foreach ($dashboardObject as $row)
            <?php
            $div = 'dbobj_' . $row->id;
            ?>
            <div class="col-md-6">
                <?php
                switch ($row->db_obj_type) {
                case 'SCRIPT':
                ?>
                <div id="<?php echo $div; ?>" style="width: 470px; float:left; height: 300px; text-align:center; margin: -.5em auto;"><br /><br /><br />Chart will be loading in 5 sec...</div>
                <?php
                $script = $row->DB_OBJ_PARA2;
                $datav['charttitle'] = $row->DB_OBJ_TITLE;
                $datav['chartdata'] = json_encode($this->db->query(ConvParaEx($row->DB_OBJ_PARA1, $_SESSION))->result());
                $datav['baseurl'] = site_url();
                $datav['chartediv'] = $div;
                echo '<script type="text/javascript">' . updateScriptPara($script, $datav) . '</script>';
                break;
                case 'LIST':
//                                echo setSqlPara($row->DB_OBJ_PARA1); die(); DB::select(DB::raw($sql));
                    $sql = $row->db_obj_para1;
                    $data = DB::select(DB::raw($sql));
                    report_gen($row->id, $data, $row->db_obj_title, $row->db_obj_para2);
                    break;
                default:
                    break;
                }
                ?>
            </div>
            @endforeach
        @endif
    </div>
</div>