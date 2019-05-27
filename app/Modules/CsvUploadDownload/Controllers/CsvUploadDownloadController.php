<?php

namespace App\Modules\CsvUploadDownload\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use App\Modules\CsvUploadDownload\Models\CsvUploadLog;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Excel;
use yajra\Datatables\Datatables;

class CsvUploadDownloadController extends Controller
{
    public function __construct() {
        if (Session::has('lang'))
            App::setLocale(Session::get('lang'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(){
        return view("CsvUploadDownload::list");
    }

    public function csvList(){
        $csvList = CsvUploadLog::getCsvList();
        return Datatables::of($csvList)
            ->editColumn('upload_date',function($data){
                return Carbon::parse($data->upload_date)->format('d-M-Y');
            })
            ->addColumn('action', function ($data) {
                $link = '<a href="' . $data->file_path .
                    '" download class="btn btn-xs btn-info"><i class="fa fa-folder-open-o"></i> Open</a>' . ' ';
                return $link;
            })
            ->make(true);
    }

    public function importRequest()
    {
        $tables = [];
        $allTables = DB::select('SHOW TABLES');
        foreach ($allTables as $table) {
            $tables[$table->Tables_in_ocpl_base_new] = $table->Tables_in_ocpl_base_new;
        }
        return view("CsvUploadDownload::import", compact('tables'));
    }

    public function uploadCsvFile(Request $request)
    {
//        dd($request->get('table_type'));
        $this->validate($request, [
            'import_request' => 'required'
        ]);
        $tableType = $request->get('table_type');
        try {
            $data = $request->all();
            $file = $data['import_request'];
            $file_mime = $file->getMimeType();
            $mimes = array(
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.oasis.opendocument.spreadsheet',
                'application/vnd.ms-excel',
                'text/plain',
                'text/csv',
                'text/tsv'
            );
            if (in_array($file_mime, $mimes)) {
                if($tableType == 'list_of_table'){
                    $prefix = 'FCF_';
                }else{
                    $prefix = 'FCT_';
                }

                $rand = rand(111, 999);
                $onlyFileName = $prefix.date("Ymd_").$rand.time();
                $savedPath = 'csv-upload/'; // upload path
                $extension = $file->getClientOriginalExtension(); // getting extension
                $fileName = $onlyFileName . '.' . $extension; // renameing
                $path = public_path($savedPath);
                $file->move($path, $fileName);
                $uploadingLog = new CsvUploadLog();
                $uploadingLog->file_name = $onlyFileName;
                $uploadingLog->file_path = '/'.$savedPath.$fileName;
                $uploadingLog->save();
                $filePath = Encryption::encode($savedPath . $fileName);

                $table=$request->get('table_type');
                if($tableType != 'new_table')
                    $table = $request->get('tables');
                $tableName = Encryption::encode($table);
                return redirect('/csv-upload/request/' . $filePath.'/'.$tableName);
            } else {
                Session::flash('error', 'csv or xls or xlsx file supported only!');
                return redirect('csv-upload/list');
            }
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong!');
            return redirect('csv-upload/list');
        }
    }

    public function previewDataFromCsv($path,$table,Excel $excel)
    {
        $getFilePath = Encryption::decode($path);
        $tableName = Encryption::decode($table);
        try {
            if (!file_exists($getFilePath)) {
                Session::flash('error', 'Sorry! File does not exist.');
                return redirect('/csv-upload/list');
            }
            $excelData = $excel->selectSheetsByIndex(0)->load($getFilePath)->get();

            if (empty($excelData)) {
                Session::flash('error', 'Your file is empty, please upload a valid file');
                return redirect('/csv-upload/list');
            }

            $firstrow = ($excelData->first() != null)?$excelData->first()->toArray():$excelData->first();
            if(count($firstrow)==0){ // Condition for blank data sheet checking
                Session::flash('error', 'This is not a valid data sheet at least the first row of sheet will not be empty.');
                return redirect('/csv-upload/list');
            }


            $type = [
                'int' => 'int',
                'float' => 'float',
                'varchar' => 'varchar',
                'text' => 'text'
            ];
            if($tableName == 'new_table'){ //If new table type selected
                $excelData = json_decode($excelData, true);
                $csvFields = [];
                foreach($firstrow as $key=>$value){
                    $csvFields[] = $key;
                }
                $alterStatus = 'create';
                return view("CsvUploadDownload::upload-request", compact('excelData','type','path', 'data','tableName','alterStatus','csvFields'));
            }else{//If existing table selected from list
                $tableFields = Schema::getColumnListing($tableName);
                $newFields = []; // Array for new fields which are not exist in table
                $existFields = []; // Array for already exist fields in the table
                foreach ($firstrow as $csvColumnName => $csvColumnValue) {
                    if (!in_array($csvColumnName, $tableFields)) {
                        $newFields[] = $csvColumnName;
                    } else {
                        $existFields[] = $csvColumnName;
                    }
                }
                if (count($newFields) > 0) {
                    $excelData = json_decode($excelData, true);
                    $alterStatus = 'edit';
                    return view("CsvUploadDownload::upload-request", compact('excelData', 'path', 'data', 'type', 'newFields', 'tableName', 'alterStatus'));
                }
            }

            $excelData = json_decode($excelData, true);
            $alterStatus = 'off';
            return view("CsvUploadDownload::upload-request", compact('excelData', 'path', 'data', 'alterStatus', 'tableName'));
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong!');
            return redirect('/csv-upload/list');
        }
    }

    public function addFieldToTable(Request $request)
    {
        try {
            $tableName = $request->get('table_name');
            $columns = $request->get('column');
            $types = $request->get('type');
            $length = $request->get('length');

            // need to custom required validate.
            foreach ($columns as $columnName) {
                if ($types[$columnName] == 'varchar') {
                    if ($length[$columnName] == '' || $types[$columnName] == '') {
                        Session::flash('error', 'Please check empty fields.');
                        return redirect()->back()->withInput();
                    }
                }else{
                    if(in_array($types[$columnName],['int','text','float']) && $length[$columnName]!=''){
                        Session::flash('error','Invalid input for length.');
                        return redirect()->back()->withInput();
                    }
                }
            }

            DB::beginTransaction();
            Schema::table($tableName, function(Blueprint $table) use ($columns,$types,$length){
                foreach($columns as $column){
                    if($types[$column] == 'int'){
                        $table->integer($column);
                    }elseif($types[$column] == 'varchar'){
                        $table->string($column,$length[$column]);
                    }elseif($types[$column] == 'text'){
                        $table->text($column);
                    }elseif($types[$column] == 'float'){
                        $table->float($column, 8, 2);
                    }
                }
            });
            DB::commit();
            Session::flash('success', 'Field created in your table');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            $errorCode = $e->getCode();
            if ($errorCode == 42000) {
                Session::flash('error', 'Invalid input for length');
                return redirect()->back()->withInput();
            }
            Session::flash('error', 'Something went wrong!! Please provide valid information.');
            return redirect()->back();
        }
    }

    public function saveDataFromCsv(Request $request)
    {
//        dd($request->all());
        $tableName = $request->get('table_name');
        $dbTableFields = Schema::getColumnListing($tableName);
        $csvTableFields = $request->get('table_field');
        $targetTable = DB::table($tableName);
        $arrayData = [];
        $countFieldElements = [];

        foreach ($csvTableFields as $field) {
            $countFieldElements[$field] = count($request->get($field));
            $arrayData[$field] = $request->get($field);
        }
        if (count(array_unique($countFieldElements)) != 1) {
            Session::flash('error', 'Please make correction on your file with data and then upload again.');
            return redirect()->back();
        }

        $dataArray = [];
        $i = 0;
        foreach (current($arrayData) as $valueIndex => $info) {
            foreach($arrayData as $fieldName=>$values){
                if(in_array($values[$valueIndex],['n/a','N/A','n/A','N/a']))
                    $values[$valueIndex] = '';

                if(in_array('created_at',$dbTableFields))
                    $dataArray[$i]['created_at'] = Carbon::now();

                if(in_array('updated_at',$dbTableFields))
                    $dataArray[$i]['updated_at'] = Carbon::now();

                $dataArray[$i][$fieldName] = $values[$valueIndex];
            }
            $i++;
        }

        $targetTable->insert($dataArray);
        Session::flash('success','Your data saved successfully');
        return redirect('csv-upload/list');
    }




    public function createNewTable(Request $request){
        try{
            $tableName = $request->get('table_name');
            $filePath = $request->get('path');
            $field = $request->get('column');
            $type = $request->get('type');
            $length = $request->get('length');
            $fieldInfos=[];
            foreach($field as $key=>$value){
//                if( $tableName == '' || $value=='' || ($type[$key] != 'text' && $length[$key] == '') || ($type[$key] == 'text' && $length[$key] != '') ){
//                    Session::flash('error','Please Enter Table name , every column name, every type and length. If your type is \'text\' then length field must be empty');
//                    return redirect()->back()->withInput();
//                }


                if($tableName == ''){
                    Session::flash('error','Table name is required.');
                    return redirect()->back()->withInput();
                }

                if($type[$key]=='varchar'){
                    if(($value=='') || ($type[$key]=='') || ($length[$key]=='')){
                        Session::flash('error','Please check empty fields.');
                        return redirect()->back()->withInput();
                    }
                }else{
                    if(in_array($type[$key],['int','text','float']) && $length[$key]!=''){
                        Session::flash('error','Invalid input for length.');
                        return redirect()->back()->withInput();
                    }
                }

                $fieldInfos[]=array(
                    'field'=>$value,
                    'type'=>$type[$key],
                    'length'=>$length[$key]
                );
            }
//        dd($fieldInfos);
            if(Schema::hasTable($tableName)){
                Session::flash('error','This table name is not available.');
                return redirect()->back()->withInput();
            }else{
                $this->newTableSchema($tableName,$fieldInfos);
                Session::flash('success','Table created successfully with your given field');
                $encodedTableName = Encryption::encode($tableName);
                return redirect('/csv-upload/request/' . $filePath.'/'.$encodedTableName);
            }
        } catch (\Exception $e) {
            $errorCode = $e->getCode();
            if ($errorCode == 42000) {
                Session::flash('error', 'Invalid input for length');
                return redirect()->back()->withInput();
            }
            Session::flash('error', 'Something went wrong!!');
            return redirect()->back();
        }
    }


    public function newTableSchema($tableName,$fieldInfos){
        Schema::create($tableName,function(Blueprint $table) use ($fieldInfos){
            $table->increments('id');
            foreach($fieldInfos as $fieldInfo){
                if($fieldInfo['type'] == 'int'){
                    $table->integer($fieldInfo['field']);
                }elseif($fieldInfo['type'] == 'varchar'){
                    $table->string($fieldInfo['field'],$fieldInfo['length']);
                }elseif($fieldInfo['type'] == 'text'){
                    $table->text($fieldInfo['field']);
                }elseif($fieldInfo['type'] == 'float'){
                    $table->float($fieldInfo['field'], 8, 2);
                }
            }
            $table->timestamps();
        });

    }

//*****************************************End of Class********************************************
}
