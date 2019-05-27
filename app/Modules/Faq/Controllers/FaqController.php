<?php

namespace App\Modules\Faq\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Requisition\Models\RequisitionVoucher;
use App\Modules\Payment\Models\BankBranch;
use App\Modules\Payment\Models\Config;
use App\Modules\Pilgrim\Models\Pilgrim;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Faq\Models\Faq;
use App\Modules\Faq\Models\FaqTypes;
use App\Modules\Faq\Models\FaqMultiTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use DB;
use Carbon\Carbon;
use yajra\Datatables\Datatables;

class FaqController extends Controller
{

    public function __construct()
    {
        if (Session::has('lang'))
            App::setLocale(Session::get('lang'));
    }


    /* Start of FAQ related functions */

    public function faqCat() {
        return view("Faq::faq_category.list");
    }

//    Create a faq category
    public function createFaqCat() {
        if (!ACL::getAccsessRight('settings', 'A'))
            die('no access right!');
        $faq_types = FaqTypes::lists('name', 'id');
        return view("Faq::faq_category.create", compact('faq_types'));
    }

    public function getFaqCatDetailsData() {
        $mode = ACL::getAccsessRight('faq', 'V');
        $faq_types = FaqTypes::leftJoin('faq_multitypes', 'faq_types.id', '=', 'faq_multitypes.faq_type_id')
            ->leftJoin('faq', 'faq.id', '=', 'faq_multitypes.faq_id')
            ->groupBy('faq_types.id')
            ->get(['faq_types.id', 'faq_types.name', 'faq.status as faq_status',
                DB::raw('count(distinct faq_multitypes.faq_id) noOfItems, '
                    . 'sum(case when faq.status="unpublished" then 1 else 0 end) Unpublished,'
                    . 'sum(case when faq.status="draft" then 1 else 0 end) Draft,'
                    . 'sum(case when faq.status="private" then 1 else 0 end) Private')]);

        return Datatables::of($faq_types)
            ->addColumn('action', function ($faq_types) use ($mode) {
                if ($mode) {
                    return '<a href="/faq/edit-faq-cat/' . Encryption::encodeId($faq_types->id) .
                    '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a> '
                    . '<a href="/faq/index?q=&faqs_type=' . $faq_types->id .
                    '" class="btn btn-xs btn-info"><i class="fa fa-folder-open-o"></i> Articles</a>';
                } else {
                    return 'aaa';
                }
            })
            ->editColumn('Draft', function ($faq_types) {
                if ($faq_types->Draft > 0) {
                    return '<a href="/faq/index?q=&faqs_type=' . $faq_types->id . "&status=draft" .
                    '" class="">' . $faq_types->Draft . '</a>';
                } else {
                    return $faq_types->Draft;
                }
            })
            ->editColumn('Unpublished', function ($faq_types) {
                if ($faq_types->Unpublished > 0) {
                    return '<a href="/faq/index?q=&faqs_type=' . $faq_types->id . "&status=unpublished" .
                    '" class="">' . $faq_types->Unpublished . '</a>';
                } else {
                    return $faq_types->Unpublished;
                }
            })
            ->editColumn('Private', function ($faq_types) {
                if ($faq_types->Private > 0) {
                    return '<a href="/faq/index?q=&faqs_type=' . $faq_types->id . "&status=private" .
                    '" class="">' . $faq_types->Private . '</a>';
                } else {
                    return $faq_types->Private;
                }
            })
            ->removeColumn('id')
            ->make(true);
    }

    //    Store a new faq category
    public function storeFaqCat(Request $request) {
        if (!ACL::getAccsessRight('settings', 'A'))
            die('no access right!');
        $this->validate($request, [
            'name' => 'required',
        ]);

        $insert = FaqTypes::create(
            array(
                'name' => $request->get('name'),
                'created_by' => CommonFunction::getUserId()
            ));

        Session::flash('success', 'Data is stored successfully!');
        return redirect('/faq/edit-faq-cat/' . Encryption::encodeId($insert->id));
    }

//    edit a faq category
    public function editFaqCat($encrypted_id) {

        if (!ACL::getAccsessRight('settings', 'E'))
            die('no access right!');
        $id = Encryption::decodeId($encrypted_id);
        $data = FaqTypes::where('id', $id)->first();

        return view("Faq::faq_category.edit", compact('data', 'encrypted_id'));
    }

//    update a faq category
    public function updateFaqCat($id, Request $request) {
        if (!ACL::getAccsessRight('settings', 'E'))
            die('no access right!');
        $faq_id = Encryption::decodeId($id);

        $this->validate($request, [
            'name' => 'required',
        ]);

        FaqTypes::where('id', $faq_id)->update([
            'name' => $request->get('name'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/faq/edit-faq-cat/' . $id);
    }

    /* End of FAQ Category related functions */

    /* FAQ article related functions */

//    Single view of a FAQ topic
    public function index(Request $request) {

        $category = $request->get('faqs_type');
        $search = $request->get('q');
        $status = $request->get('status');
        $faqs_type = ['' => 'All'] + FaqTypes::orderBy('name')->lists('name', 'id')->all();


        $faqs = Faq::leftJoin('faq_multitypes', 'faq.id', '=', 'faq_multitypes.faq_id')
            ->leftJoin('faq_types', 'faq_multitypes.faq_type_id', '=', 'faq_types.id')
            ->where(function($query) use ($category, $search, $status) {
                if ($category) {
                    $query->where('faq_types.id', $category);
                }
                if ($search) {
                    $query->where('question', 'like', "%$search%");
                }
                if ($status) {
                    $query->where('faq.status', $status);
                } else {
                    $query->where('status', 'public');
                    if ($category) {
                        $query->orWhere(['faq_types.id'=> $category,'faq.status'=>'draft','faq.updated_by'=> Auth::user()->id]);
                    } else {
                        $query->orWhere(['faq.status'=>'draft','faq.updated_by'=> Auth::user()->id]);
                    }
                }
            })
            ->groupBy('faq.id')
            ->get(['faq.id', 'question', 'answer', 'status', 'faq_type_id as types', DB::raw('group_concat(distinct name) as faq_type_name'),
                'faq.created_by', 'faq.updated_by', 'faq.updated_at']);

        return view("Faq::faq_category.article", compact('faqs', 'faqs_type'));
    }

//    Create a New FAQ Article
    public function createFaqArticle() {
        $faq_types = FaqTypes::orderBy('name')->lists('name', 'id');
        return view("Faq::faq_article_create", compact('faq_types'));
    }

//    Store the FAQ Article
    public function storeFaqArticle(Request $request) {
        if (!ACL::getAccsessRight('search', 'A')) {
            die('no access right!');
        }
        $this->validate($request, [
            'question' => 'required',
            'answer' => 'required',
            'status' => 'required',
        ]);
        try{
            DB::beginTransaction();
            $insert = Faq::create(
                array(
                    'question' => $request->get('question'),
                    'answer' => $request->get('answer'),
                    'status' => $request->get('status'),
                    'created_by' => CommonFunction::getUserId()
                ));

            $faq_id = $insert->id;

            $types = $request->get('type');

            foreach ($types as $type) {
                FaqMultiTypes::create(
                    array(
                        'faq_id' => $faq_id,
                        'faq_type_id' => $type,
                        'created_by' => CommonFunction::getUserId()
                    ));
            }

            DB::commit();
            Session::flash('success', 'Data is stored successfully!');
            return redirect('/faq/edit-faq-article/' . Encryption::encodeId($insert->id));
        }catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Sorry! Somthing Wrong.');
            return Redirect::back()
                ->withInput();
        }
    }

//    Edit the FAQ Article
    public function editFaqArticle($encrypted_id) {
        if (!ACL::getAccsessRight('search', 'E'))
            die('no access right!');
        $id = Encryption::decodeId($encrypted_id);
        $data = Faq::where('id', $id)->first();

        $faq_types = FaqTypes::orderBy('name')->lists('name', 'id');
        $multitypes = FaqMultiTypes::where('faq_id', $id)->get(['faq_type_id as id']);
        $selec = array();
        foreach ($multitypes as $row) {
            $selec[] = $row->id;
        }
        //dd($selec);
        return view("Faq::faq_article_edit", compact('data', 'encrypted_id', 'faq_types', 'selec'));
    }

//    Update the FAQ Article
    public function updateFaqArticle($id, Request $request) {
        if (!ACL::getAccsessRight('search', 'E'))
            die('no access right!');
        $faq_id = Encryption::decodeId($id);

        $this->validate($request, [
            'question' => 'required',
            'answer' => 'required',
            'status' => 'required',
        ]);

        Faq::where('id', $faq_id)->update([
            'question' => $request->get('question'),
            'answer' => $request->get('answer'),
            'status' => $request->get('status'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        FaqMultiTypes::where('faq_id', $faq_id)->delete();

        $types = $request->get('type');

        foreach ($types as $type) {
            FaqMultiTypes::create(
                array(
                    'faq_id' => $faq_id,
                    'faq_type_id' => $type,
                    'created_by' => CommonFunction::getUserId()
                ));
        }

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/faq/edit-faq-article/' . $id);
    }








    /*     * ******************************** End of Faq  Controller Class *********************** */
}
