<?php
namespace App\Modules\Reports\Controllers;

use App\Http\Requests\ReportsRequest;
use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\UtilFunction;
use App\Modules\Reports\Models\FavReports;
use App\Modules\Reports\Models\Reports;
use App\Modules\Reports\Models\ReportsMapping;
use App\Modules\Users\Models\UserTypes;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
//use App\Libraries\ReportHelper;
use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Libraries\Encryption;
use Illuminate\Support\Facades\DB;
use App\Modules\Reports\Models\ReportHelperModel;
use App\Modules\Reports\Models\HelperModel;

class ReportsController extends Controller {

    public function __construct(){
        if (Session::has('lang'))
            App::setLocale(Session::get('lang'));

    }

    public function index()
    {
        $getList['result'] = Reports::leftJoin('custom_reports_mapping as rm','rm.report_id','=','custom_reports.report_id')
            ->where(function($query){
                //Sys Admin and MIS user will get all lists
                if(Auth::user()->user_type != '1x101' and Auth::user()->user_type != '15x151'){
                    $query->where('rm.user_type', Auth::user()->user_type)
                            ->where('custom_reports.status',1);
                }
            })
            ->groupBy('custom_reports.report_id')
            ->get(['custom_reports.report_id','report_title','status']);
        $getFavouriteList['fav_report'] = FavReports::join('custom_reports','custom_reports.report_id','=','custom_favorite_reports.report_id')
                                        ->where('custom_favorite_reports.user_id', Auth::user()->id)
                                        ->where('custom_favorite_reports.status',1)
                                        ->get(['custom_reports.report_id','report_title','custom_reports.status']);

        return view("Reports::list", compact('getList', 'getFavouriteList'));
    }

    public function create()
    {
        $usersList = UserTypes::orderBy('type_name')->lists('type_name','id');
        return view("Reports::create", compact('usersList'));
    }

    public function store(ReportsRequest $request)
    {
        try{
            DB::beginTransaction();
            if(!ACL::getAccsessRight('report','A')) die ('no access right!');
            $reports = Reports::create([
                'report_title' => $request->get('report_title'),
                'report_para1' => Encryption::dataEncode($request->get('report_para1')),
                'status' => $request->get('status'),
                'user_id' => 0,
                'updated_by' => 1
            ]);

            if($request->get('user_id')) {
                foreach ($request->get('user_id') as $user_id) {
                    ReportsMapping::create([
                        'user_type' => $user_id,
                        'report_id' => $reports->id,
                    ]);
                }
            }
            DB::commit();
            Session::flash('success', 'Successfully Saved the Report.');
            return $request->redirect_to_new == 1 ? redirect('/reports/view/' . Encryption::encodeId($reports->id)) : redirect('/reports/edit/' . Encryption::encodeId($reports->id));
        }catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Sorry! Somthing Wrong.');
            return Redirect::back()
//                ->withMessage($message_fail)
//                ->withErrors($validator)
                ->withInput();
        }
    }


    public function edit($id, Request $request)
    {
        if(!ACL::getAccsessRight('report','E')) die ('no access right!');
        $report_id = Encryption::decodeId($id);
        $report_data = Reports::where('report_id', $report_id)->first();
        $usersList = UserTypes::orderBy('type_name')->lists('type_name','id');
        $selected_user = ReportsMapping::where('report_id',$report_id)->lists('user_type')->all();
        return view("Reports::edit",compact('report_data','usersList','selected_user'));
    }


    public function update($id, ReportsRequest $request)
    {
        if(!ACL::getAccsessRight('report','E')) die ('no access right!');
        $report_id = Encryption::decodeId($id);

        Reports::where('report_id', $report_id)->update([
            'report_title' => $request->get('report_title'),
            'report_para1' => Encryption::dataEncode($request->get('report_para1')),
            'status' => $request->get('status'),
            'user_id' => 0,
            'updated_by' => CommonFunction::getUserId()
        ]);

        ReportsMapping::where('report_id',$report_id)->delete();
        foreach($request->get('user_id') as $user_id){
            ReportsMapping::create([
                'user_type' => $user_id,
                'report_id' => $report_id,
            ]);
        }
        Session::flash('success', 'Successfully Updated the Report.');
        return $request->redirect_to_new == 1 ? redirect('/reports/view/' . Encryption::encodeId($report_id)) : redirect('/reports/edit/' . Encryption::encodeId($report_id));
    }


    public function reportsVerify(Request $request) {

        $obj = new HelperModel();
        $sql = $request->get('sql');

        $sql = preg_replace('/&gt;/','>',$sql);
        $sql = preg_replace('/&lt;/','<',$sql);

        echo '<hr /><code>'.$sql.'</code><hr />';
        $sql = $this->sqlSecurityGate($sql);
        $result=null;
        try {
            $result = DB::select(DB::raw($sql));
        } catch(QueryException $e) {
            echo $e->getMessage();
        }

        if($result){
            $result2 = array();
            foreach ($result as $value):
                $result2[] = $value;
                if (count($result2) > 99){
                    break;
                }
            endforeach;
            echo '<p></p><pre>';
            echo $obj->createHTMLTable($result2);
            echo '</pre>';
            echo 'showing ' . count($result2) . ' of '.count($result);
            echo '</p>';
        }
    }

    public function sqlSecurityGate($sql) {
        $sql = trim($sql);
        if(strlen($sql)<8){
            dd('Sql is not Valid: ' . $sql);
        }
        $select_keyword = strtoupper(substr($sql, 0, 7));
        $semicolon = strpos($sql, ';');
        if (($select_keyword == 'SELECT ') AND $semicolon == '') {
            return $sql;
        }elseif ((substr($select_keyword,0,5) == 'SHOW ' OR $select_keyword== 'EXPLAIN' OR substr($select_keyword,0,5) == 'DESC ')
            AND $semicolon == '' AND (Auth::user()->user_type=='1x101' OR Auth::user()->user_type=='15x151')) {
            return $sql;
        } else {
            dd('Sql is not Valid: ' . $sql);
        }
    }

    public function showTables(Request $request) {

        if($request->session()->has('db_tables')){
            echo $request->session()->get('db_tables');
        } else {
            $tables = DB::select(DB::raw('show tables'));
            $count = 1;
            $ret = '<ul class="table_lists">';
            foreach ($tables as $table) {
                $table2 = json_decode(json_encode($table), true);

                $ret .= '<li class="table_name table_' . $count . '"><strong>' . $table2[key($table2)] .'</strong><br/>';
                $fields = DB::select(DB::raw('show fields from ' . $table2[key($table2)]));

                $fileds='';
                foreach ($fields as $field) {
                    $fileds .=  strlen($fileds)>0? ', '.$field->Field:''.$field->Field;
                }
                $ret .= $fileds;

                $ret .= '</li>';
                $count++;
            }
            $ret .= '</ul>';
            $request->session()->put('db_tables', $ret);
            echo $ret;
        }
    }
    public function view($report_id = '')
    {
        $objRh = new ReportHelperModel();
        $report_id2 = Encryption::decodeId($report_id);
        $fav_report_info = FavReports::where('report_id', $report_id2)
            ->where('user_id',Auth::user()->id)
            ->first();

        // Report Admins are out of this check
        // check that the favourite report is published or not
        // check that the favourite report is assigned or not
        if (in_array(Auth::user()->user_type,UtilFunction::isReportAdmin()) != true)
        {
            if ($fav_report_info != null)
            {
                $is_publish = Reports::where([
                    'report_id' => $report_id2,
                    'status' => 1
                ])->count();
                $is_assigned = ReportsMapping::where([
                    'report_id' => $report_id2,
                    'user_type' => Auth::user()->user_type
                ])->count();
                if ($is_publish == 0 || $is_assigned == 0)
                {
                    Session::flash('error', 'Sorry, This Report is unpublished or unassigned to your user type.');
                    return redirect('reports');
                }
            }
        }
        $report_data = Reports::where('report_id', $report_id2)->first();
        $reportParameter=$objRh->getSQLPara(Encryption::dataDecode($report_data->report_para1));
        return view('Reports::reportInputForm',compact('reportParameter','report_id','report_data','fav_report_info'));
    }

    public function showReport($report_id, Request $request)
    {
        $objRh = new ReportHelperModel();

        if (!$request->all()) {
            return redirect('reports/view/' . $report_id);
        }
        $reportId = Encryption::decodeId($report_id);
        $reportId = is_numeric($reportId) ? $reportId : null;
        if (!$reportId) {
            return redirect('dashboard');
        }

        $data = array();
        foreach ($request->all() as $key => $row) {
            if (substr($key, 0, 4) == 'rpt_') {
                $data[$key] = $request->get($key);
                $request->session()->put($key, $request->get($key));
            }
            elseif (substr($key, 0, 5) == 'sess_') {
                $data[$key] = session($key);
            } else {
                $data[$key] = $request->get($key);
            }
        }

        if ($request->get('export_csv')) {
            $this->exportCSV($reportId, $data);
        } elseif ($request->get('export_csv_zip')) {
            $this->exportCSV_Zip($reportId, $data);
        } else{
            $report_data = Reports::where('report_id', $reportId)->first();
            $reportParameter = $objRh->getSQLPara(Encryption::dataDecode($report_data->report_para1));
            $SQL = $objRh->ConvParaEx(Encryption::dataDecode($report_data->report_para1), $data);
            try {
                $recordSet = DB::select(DB::raw($SQL));
                return view('Reports::reportGenerate', compact('recordSet', 'report_id', 'report_data', 'reportParameter'));
            } catch (QueryException $e) {
                Session::flash('error', $e->getMessage());
                return redirect('reports');
            }
        }
    }

    public function addToFavourite($id)
    {
        $report_id = Encryption::decodeId($id);
        try
        {
            $existing_fav_report = FavReports::where('report_id',$report_id)
                                        ->where('user_id',Auth::user()->id)
                                        ->count();
            if ($existing_fav_report > 0)
            {
                FavReports::where('report_id',$report_id)
                    ->where('user_id',Auth::user()->id)
                    ->update([
                        'status' => 1,
                        'updated_by' => CommonFunction::getUserId()
                    ]);
            }
            else{
                FavReports::create([
                    'user_id' => Auth::user()->id,
                    'report_id' => $report_id,
                    'status' => 1
                ]);
            }
            return Redirect::back();
        }
        catch (\Exception $e)
        {
            Session::flash('error', 'Sorry Something went wrong');
            return Redirect::back();
        }
    }

    public function removeFavourite($id)
    {
        $report_id = Encryption::decodeId($id);
        try
        {
            FavReports::where('report_id',$report_id)
                        ->where('user_id',Auth::user()->id)
                        ->update([
                            'status' => 0,
                            'updated_by' => CommonFunction::getUserId()
                        ]);
            return Redirect::back();
        }
        catch (\Exception $e)
        {
            Session::flash('error', 'Sorry Something went wrong');
            return Redirect::back();
        }
    }

    public function exportCSV($id, $data) {

        $objRh = new ReportHelperModel();
        $reportData = DB::select(DB::raw("SELECT * FROM custom_reports WHERE REPORT_ID='$id'"));
        $reportData = json_decode(json_encode($reportData));
        $name = $reportData['0']->report_title.'-'.$id.'-'.Carbon::now().'.csv';
        $report_name = str_replace(' ','_',$name) ;
        try {
            $SQL = base64_decode($reportData['0']->report_para1);
            $SQL = $objRh->ConvParaEx($SQL, $data);
            $data = DB::select(DB::raw($SQL));


            header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
            header("Content-Disposition: attachment; filename=$report_name");

            if ($data && count($data[0]) > 0) {
                $rc = 0;
                foreach ($data[0] as $key => $value) {
                    if ($rc > 0) {
                        echo ',';
                    } $rc++;
                    echo "$key";
                }
                echo "\r\n";
                foreach ($data as $row):
                    $rc = 0;
                    foreach ($row as $key => $field_value):
                        if ($rc > 0) {
                            echo ',';
                        } $rc++;
                        if (empty($field_value)) {
                            //
                        } else if (strlen($field_value) > 10) {
                            echo '"' . addslashes($field_value) . '"';
                        } else if (is_numeric($field_value)) {
                            echo $field_value;
                        } else {
                            echo '"' . addslashes($field_value) . '"';
                        }
                    endforeach;
                    echo "\r\n";
                endforeach;
            } else {
                echo "Data Not Found!";
            }

            // This exit will remaining
            exit();
        } catch (QueryException $e) {
            echo "CSV can't generate for following error: ";
            dd($e->getMessage());
            return redirect('re');

        }
    }
    public function exportCSV_Zip($id, $data) {

        $this->exportCSV($id, $data);
        exit();
        dd('zip library not found');
        $objRh = new ReportHelperModel();
        $this->load->library('zip');


        $reportData = $this->db->query("SELECT REPORT_PARA1 FROM custom_reports WHERE REPORT_ID='$id'")->result_array();

        $SQL = $reportData['0']['REPORT_PARA1'];
        $SQL = $objRh->ConvParaEx($SQL, $data);
        $data = $this->db->query($SQL)->result_array();
        $csv_data = '';
        if ($data && count($data[0]) > 0) {
            $rc = 0;
            foreach ($data[0] as $key => $value) {
                if ($rc > 0) {
                    $csv_data .= ',';
                } $rc++;
                $csv_data .= "$key";
            }
            $csv_data .= "\r\n";
            foreach ($data as $row):
                $rc = 0;
                foreach ($row as $key => $field_value):
                    if ($rc > 0) {
                        $csv_data .= ',';
                    } $rc++;
                    if (empty($field_value)) {
                        //
                    } else if (strlen($field_value) > 10) {
                        $csv_data .= '"' . addslashes($field_value) . '"';
                    } else if (is_numeric($field_value)) {
                        $csv_data .= $field_value;
                    } else {
                        $csv_data .= '"' . addslashes($field_value) . '"';
                    }
                endforeach;
                $csv_data .= "\r\n";
            endforeach;
        } else {
            $csv_data .= "Data Not Found!";
        }

        $folder_name = "reports_of_$id";
        $name = "report_$id.csv";
        $this->zip->add_data($name, $csv_data);

// Write the zip file to a folder on your server. Name it "report_id.zip"
//        $this->zip->archive(base_url()."csv/$name.zip");
// Download the file to your desktop. Name it "report_id.zip"
        $this->zip->download("$folder_name.zip");
//        redirect(site_url('reports/index' . $this->encryption->encode($id)));
    }
}
