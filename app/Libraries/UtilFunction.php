<?php
/**
 * Created by Zaman
 * Date: 2/7/2017
 * Time: 9:51 PM
 */

namespace App\Libraries;

use App\Modules\Dashboard\Models\DataEntrySession;
use App\Modules\GroupPayment\Models\GroupPayment;
use App\Modules\Guides\Models\FlightSlots;
use App\Modules\Guides\Models\GuidesModel;
use App\Modules\Monazzem\Models\Monazzem;
use App\Modules\Monazzem\Models\MonazzemRequest;
use App\Modules\Passport\Models\PassportInterchangeDetails;
use App\Modules\Pilgrim\Models\PassportPlace;
use App\Modules\Pilgrim\Models\Pilgrim;
use App\Modules\Pilgrim\Models\PilgrimNid;
use App\Modules\Pilgrim\Models\Replacement;
use App\Modules\ProcessHmis\Models\ChangePassport;
use App\Modules\ProcessHmis\Models\Houses;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Registration\Models\PassVerifyReq;
use App\Modules\Registration\Models\Registration;
use App\Modules\RegistrationVoucher\Models\RegVoucher;
use App\Modules\RegistrationVoucher\Models\RegVoucherDetails;
use App\Modules\Reports\Models\FavReports;
use App\Modules\Reports\Models\Reports;
use App\Modules\Reports\Models\ReportsMapping;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\HajjSessions;
use App\Modules\TransferPilgrim\Controllers\TransferPilgrimController;
use App\Modules\TransferPilgrim\Models\TransferPilgrim;
use App\Modules\TransferPilgrim\Models\TransferPilgrimRecords;
use App\Modules\Users\Models\AreaInfo;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;
use Session;

class UtilFunction
{
    public static function parsePassportData($passport_data, $pp_user_existing_dob = '')
    {
        $original_data = $passport_data;
        $passport_data = explode(PHP_EOL, $passport_data);
        if (!isset($passport_data[1])) {
            $passport_data[0] = substr($original_data, 0, 44);
            $passport_data[1] = substr($original_data, 44, 88);


            $passport_data[0] = trim($passport_data[0]);
            $passport_data[1] = trim($passport_data[1]);

        }
        if (!isset($passport_data[0]) || !isset($passport_data[1])) {
            return false;
        }

        $nameArr = explode('<<', substr($passport_data[0], 5, 30));
        $birth_date_str = substr($passport_data[1], 13, 6);


        $passport_expire_str = substr($passport_data[1], 21, 6);
        $personal_number = str_replace('<', '', substr($passport_data[1], 28, 14));
        #$birth_year = substr($passport_expire_str, 0, 2);
        #$birth_month = substr($passport_expire_str, 2, 2);
        #$birth_day = substr($passport_expire_str, 4, 2);

        $birth_date = UtilFunction::getPassportFullYear($birth_date_str, $pp_user_existing_dob);


        $return = [
            #'type' => substr($passport_data[0],0,1),
            'type' => (substr($passport_data[0], 1, 1) == '<') ? 'O' : substr($passport_data[0], 1, 1),
            'country_code' => substr($passport_data[0], 2, 3),
            'surname' => $nameArr[0],
            'given_name' => str_replace('<', ' ', $nameArr[1]),
            'passport_no' => substr($passport_data[1], 0, 9),
            'nationality' => substr($passport_data[1], 10, 3),
            'birth_date' => $birth_date,
            'gender' => substr($passport_data[1], 20, 1),
            'passport_expire_date' => date('Y-m-d', strtotime(substr($passport_expire_str, 0, 2) . '-' . substr($passport_expire_str, 2, 2) . '-' . substr($passport_expire_str, 4, 2))),
            'personal_number' => $personal_number,
        ];

        return $return;
    }

    /*
     * Checking that the passport is eligible for sending KSA embassy for visa
     */
    public static function isPassportEligibleForKsaVisa($passport_no)
    {
        // DO check related condition
        $hajj_id = DB::table(env("HMIS_DB") . '.do_request_details')
            ->where('passport_no', '=', $passport_no)
            ->pluck('hajj_id');

        if ($hajj_id != null) {
            return true;
        }
        return false;
    }

    public static function obj2ArrayForPartialNID($nidData)
    {
        try {
            $parAddress = UtilFunction::explodeAddress(CommonFunction::convertUTF8(UtilFunction::parsePartialNID($nidData, 'permanentAddress')));
            $preAddress = UtilFunction::explodeAddress(CommonFunction::convertUTF8(UtilFunction::parsePartialNID($nidData, 'presentAddress')));
            $date_birth = explode('T', UtilFunction::parsePartialNID($nidData, 'dob'));

            $response = [
                'full_name_bangla' => CommonFunction::convertUTF8(UtilFunction::parsePartialNID($nidData, 'name')),
                'full_name_english' => UtilFunction::parsePartialNID($nidData, 'nameEn'),
                'father_name' => CommonFunction::convertUTF8(UtilFunction::parsePartialNID($nidData, 'father')),
                'mother_name' => CommonFunction::convertUTF8(UtilFunction::parsePartialNID($nidData, 'mother')),
                'birth_date' => $date_birth[0],
                'per_village_ward' => $parAddress['village_ward'],
                'per_police_station' => $parAddress['thana'],
                'per_district' => $parAddress['district'],
                'per_post_code' => $parAddress['post_code'],
                'village_ward' => $preAddress['village_ward'],
                'police_station' => $preAddress['thana'],
                'district' => $preAddress['district'],
                'post_code' => $preAddress['post_code'],
                'national_id' => UtilFunction::parsePartialNID($nidData, 'nid'),
                'gender' => UtilFunction::parsePartialNID($nidData, 'gender'),
                'spouse_name' => CommonFunction::convertUTF8(UtilFunction::parsePartialNID($nidData, 'spouse')),
                'photo' => UtilFunction::parsePartialNID($nidData, 'photo'),
                'marital_status' => '', //$nidData->maritialStatus
                'mobile' => '', //$nidData->mobileNo,
                'occupation' => '', //CommonFunction::convertUTF8($nidData->occupation),
            ];
            if ($response['full_name_english'] != '' && $response['full_name_bangla'] != '' && $response['mother_name'] != '' && $response['father_name'] != '' && $response['birth_date'] != '' && $response['national_id'] != '') {

                return $response;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function obj2ArrayForNID($nidData)
    {
        try {
            $parAddress = UtilFunction::explodeAddress(CommonFunction::convertUTF8($nidData->permanentAddress));
            $preAddress = UtilFunction::explodeAddress(CommonFunction::convertUTF8($nidData->presentAddress));
            $date_birth = explode('T', $nidData->dob);
            $response = [
                'full_name_bangla' => CommonFunction::convertUTF8($nidData->name),
                'full_name_english' => $nidData->nameEn,
                'father_name' => CommonFunction::convertUTF8($nidData->father),
                'mother_name' => CommonFunction::convertUTF8($nidData->mother),
                'birth_date' => $date_birth[0],
                'per_village_ward' => $parAddress['village_ward'],
                'per_police_station' => $parAddress['thana'],
                'per_district' => $parAddress['district'],
                'per_post_code' => $parAddress['post_code'],
                'village_ward' => $preAddress['village_ward'],
                'police_station' => $preAddress['thana'],
                'district' => $preAddress['district'],
                'post_code' => $preAddress['post_code'],
                'national_id' => $nidData->nid,
                'gender' => $nidData->gender,
                'spouse_name' => isset($nidData->spouse) ? CommonFunction::convertUTF8($nidData->spouse) : '',
                'marital_status' => '', //$nidData->maritialStatus
                'mobile' => '', //$nidData->mobileNo,
                'occupation' => '', //CommonFunction::convertUTF8($nidData->occupation),
            ];
            return $response;
        } catch (ValidationException $e) {
            echo $e;
            return null;
        } catch (\Exception $e) {
            echo $e;
            return null;
        }
    }

    public static function parsePartialNID($data, $key)
    {
        $tag = '"' . $key . '":"';
        $i = strpos($data, $tag);
        if ($i > 0) {
            $k = 0;
            while ($k < 100) {
                $j = strpos($data, '"', $i + 1 + strlen($tag));
                if ($j > 0) {
                    if (substr($data, $j - 1, 1) == '\\') {
                        $k++;
                        continue;
                    } else {
                        return substr($data, $i + strlen($tag), $j - ($i + strlen($tag)));
                    }
                } else {
                    break;
                }
            }
        }
        return '';
    }

    public static function explodeAddress($address)
    {
        $adl = 4;
        $permanentAddress = explode(',', $address);
        if (count($permanentAddress) <= 1) {
            return [
                'village_ward' => $address,
                'thana' => '',
                'district' => '',
                'post_code' => '',
            ];
        }
        if (count($permanentAddress) > 1) {
            $data['district'] = trim($permanentAddress[count($permanentAddress) - 1]);
        } else {
            $data['district'] = '';
        }
        if (count($permanentAddress) > 3) {
            $data['thana'] = trim($permanentAddress[count($permanentAddress) - 2]);
            $per_post_office = trim($permanentAddress[count($permanentAddress) - 3]);
            $per_post_codes = explode('-', $per_post_office);
            if (count($per_post_codes) > 1) {
                $data['post_code'] = trim($per_post_codes[count($per_post_codes) - 1]);
                if (!is_numeric(CommonFunction::convert2English($data['post_code'])) && count($permanentAddress) > 4) {

                    $per_post_office = trim($permanentAddress[count($permanentAddress) - 4]);
                    $per_post_codes = explode('-', $per_post_office);
                    if (is_numeric(CommonFunction::convert2English(trim($per_post_codes[count($per_post_codes) - 1])))) {
                        $data['post_code'] = trim($per_post_codes[count($per_post_codes) - 1]);
                        $data['thana'] = trim($permanentAddress[count($permanentAddress) - 3]);
                        $adl = 5;
                    }
                }
                $data['post_code'] = CommonFunction::convert2English($data['post_code']);
            } else {
                $data['post_code'] = '';
            }
        } else {
            $data['thana'] = '';
            $data['post_code'] = '';
        }
        $data['village_ward'] = $permanentAddress[0];
        for ($i = 1; $i <= count($permanentAddress) - $adl; $i++) {
            $data['village_ward'] .= ', ' . $permanentAddress[$i];
        }
        if ($adl == 5) {
            $data['village_ward'] .= ', ' . trim($permanentAddress[count($permanentAddress) - 2]);
        }
        return $data;
    }

    public static function getNidData($object)
    {
        $request = Encryption::decode($object);
        $request = json_decode($request);
        $nid = $request->nid;
        $dob = CommonFunction::changeDateFormat($request->dob, true);

        $management_type = Session::get('management_type');
        $created_by = CommonFunction::getUserId();

        $result = PilgrimNid::where(function ($query) use ($management_type, $created_by) {
            if ($management_type == 'Private') {
                $query->where('created_by', $created_by);
            }
            $query->whereIn('verification_flag', [1, 2]);
        })
            ->where('nid', $nid)
            ->where('dob', $dob)
            ->first(['responses']);

        if ($result != null) {
            $nidDataDecoded = json_decode($result->responses);
            if ($nidDataDecoded != null && $nidDataDecoded->return) {
                $nidData = (object)UtilFunction::obj2ArrayForNID($nidDataDecoded->return);

                if ($nidData == null || !isset($nidData->full_name_bangla)) {
                    PilgrimNid::where(function ($query) use ($management_type, $created_by, $nid, $dob) {
                        if ($management_type == 'Private') {
                            $query->where('created_by', $created_by);
                        }
                        $query->where('nid', $nid);
                        $query->where('dob', $dob);
                    })->update(['verification_flag' => '0', 'no_of_try' => '0']);

                    return ['responseCode' => 0, 'msg' => 'NID Data is Invalid, we have re-send NID for verification! (2090)', 'nidData' => '', 'object' > '', 'nidPicture' => '', 'pilgrim' => '', 'gender' => ''];
                } else {
                    $nidPicture = 'data:image/jpeg;base64,' . $nidDataDecoded->return->photo;
                    $gender = $request->gender;
                    return ['responseCode' => 1, 'msg' => '', 'nidData' => $nidData, 'object' > $object, 'nidPicture' => $nidPicture, 'pilgrim' => '', 'gender' => $gender];
                }
            } else {
                $nidData = (object)UtilFunction::obj2ArrayForPartialNID($result->responses);
                if ($nidData == null) {
                    return ['responseCode' => 0, 'msg' => 'NID Data Not Valid', 'nidData' => '', 'object' > '', 'nidPicture' => '', 'pilgrim' => '', 'gender' => ''];
                } else {
                    $nidPicture = '';
                    if ($nidData->photo) {
                        $nidPicture = 'data:image/jpeg;base64,' . $nidData->photo;
                    }
                    $gender = $request->gender;
                    return ['responseCode' => 1, 'msg' => '', 'nidData' => $nidData, 'object' > $object, 'nidPicture' => $nidPicture, 'pilgrim' => '', 'gender' => $gender];
                }
            }
        } else {
            return ['responseCode' => 0, 'msg' => 'NID Data Not Found', 'nidData' => '', 'object' > '', 'nidPicture' => '', 'pilgrim' => '', 'gender' => ''];
        }
    }

    public static function verifyNid($request)
    {
        $nid = trim($request->get('nid'));
        $dob = trim($request->get('dob'));


        $request_data = $request->all();
        $date_birth = CommonFunction::changeDateFormat($dob, true);

        if (strlen($nid) == 13) {
            $birth = explode('-', $date_birth);
            $nid = $birth[0] . $nid;
        }
        $request_data['nid'] = $nid;
        $object = Encryption::encode(json_encode($request_data));


        if (Session::get('management_type') == 'Private') {
            //condition work for NID only
            $response = PilgrimNid::where(['nid' => $nid, 'dob' => $date_birth])
                ->where('created_by', CommonFunction::getUserId())
                ->first(['verification_flag', 'responses']);
        } else {
            //condition work for NID only
            $response = PilgrimNid::where(['nid' => $nid, 'dob' => $date_birth])->first(['verification_flag', 'responses']);
        }

        if (!$response) {   //No response, so insert new NID
            //if not found the nid than insert the NID
            $newNid = array('nid' => $nid, 'dob' => $date_birth, 'verification_flag' => 0, 'is_govt' => Session::get('management_type'));
            try {
                PilgrimNid::create($newNid);
            } catch (\Exception $e) {
                $data = ['responseCode' => -99, 'data' => '', 'identity' => 'NID', 'flash_msg' => $e->getMessage() . '-' . $e->getLine()];
            }

            $data = ['responseCode' => -1, 'data' => '', 'identity' => 'NID', 'flash_msg' => trans('messages.wait_for_nid_verify')];
        } else {
            if ($response->verification_flag == 0) {
                // Just inserted
                $data = ['responseCode' => 0, 'data' => '', 'identity' => 'NID', 'flash_msg' => trans('messages.wait_for_nid_verify')];
            } elseif ($response->verification_flag == 1 || $response->verification_flag == 2) {
                // Valid NID
                $res = 'get-nid-data/' . $object;
                $data = ['responseCode' => 1, 'data' => $res, 'identity' => 'NID', 'flash_msg' => trans('messages.nid_found_info_is_being_procssed')];
            } elseif ($response->verification_flag == -1) {
                // Sent to EC Server
                $data = ['responseCode' => -1, 'data' => '', 'identity' => 'NID', 'flash_msg' => trans('messages.nid_found_info_is_being_procssed')];
            } else {
                // Invalid NID
                PilgrimNid::where('id', $response->id)->update(['verification_flag' => 0, 'no_of_try' => 0]);
                $message = trans('messages.invalid_nid_message');
                $data = ['responseCode' => -9, 'data' => '', 'identity' => 'NID', 'flash_msg' => $message];
            }
        }
        return response()->json($data);
    }

    public static function isMonazzemEntryAllow($agency_id)
    {
        $count = MonazzemRequest::where('agency_id', $agency_id)->where('status', 2)->count();
        return ($count == 1) ? true : false;
    }

    public static function canUpdateMonazzemRequestData($agency_id, $monazzem_id = 0)
    {
        /*
         * If any request in initiated state no way to edit the request before cancel the request
         */
        $monazzemRequestCount = MonazzemRequest::where('agency_id', $agency_id)->where('status', 2)->count();
        $is_request_initiated = ($monazzemRequestCount > 0) ? true : false;


        $is_request_recommend = false;
        if ($monazzem_id > 0) {
            /*
             * If count > 0 then no way to edit request by agency
             */
            $processListCount = ProcessList::whereIn('status_id', [2])->where('ref_id', $monazzem_id)->where('process_type_id', 3)->count();
            $is_request_recommend = ($processListCount > 0) ? true : false;
        }
        return ['is_request_initiated' => $is_request_initiated, 'is_request_recommend' => $is_request_recommend];
    }

    public static function canUpdatePassportRequestData($ref_id)
    {
        $processListCount = ProcessList::whereIn('status_id', [2])->where('ref_id', $ref_id)->where('process_type_id', 5)->count();
        return ($processListCount > 0) ? true : false;
    }

    public static function passportValidationForPilgrimRegistration($passportData, $tracking_no)
    {
        $score = 0;
        $pp_name1 = trim($passportData['surname']);
        $pp_name2 = trim($passportData['given_name']);
        $pp_dob = $passportData['birth_date'];
        $pp_nid = $passportData['personal_number'];
        $pp_birth_certificate_id = $passportData['personal_number'];
        $passport_full_name = '';
        if ($pp_name1 != '') {
            $passport_full_name = $pp_name1;
        }
        if ($pp_name2 != '') {
            $passport_full_name = $passport_full_name . ' ' . $pp_name2;
        }

        $name_score = 0;
        $dob_score = 0;
        $nid_score = 0;
        $bcrt_score = 0;
        $name_compare_percent = 0;


        $pilgrim = Pilgrim::where('tracking_no', $tracking_no)->where('is_archived', 0)
            ->first(
                [
                    'full_name_english',
                    'birth_date',
                    'national_id',
                    'birth_certificate'
                ]);

        if ($pilgrim == null) {
            return false;
        }


        if ($passport_full_name != '' && UtilFunction::nameCompareForPercentage($pilgrim->full_name_english, $passport_full_name) >= 25) {
            $score = 30;
            $name_score = 30;
            $name_compare_percent = UtilFunction::nameCompareForPercentage($pilgrim->full_name_english, $passport_full_name);
        }
        if ($pp_dob != '' && $pp_dob == $pilgrim->birth_date) {
            $score += 30;
            $dob_score = 30;
        }
        if ($pp_nid != '' && strpos($pilgrim->national_id, $pp_nid) !== false) {
            $score += 40;
            $nid_score = 40;
        } else if ($pp_nid != '' && (strlen($pp_nid) == 17 && strlen($pilgrim->national_id) == 17) && (substr($pp_nid, 4) == substr($pilgrim->national_id, 4))) {
            $score += 40;
            $nid_score = 40;
        } else if ($pp_birth_certificate_id != '' && strpos($pilgrim->birth_certificate, $pp_birth_certificate_id) !== false) {
            $score += 40;
            $bcrt_score = 40;
        }


        //return ($score >= 60);

        return [
            'responseCode' => ($score >= 60) ? 1 : 0,
            'name_compare_percent' => $name_compare_percent,
            'name_score' => $name_score,
            'dob_score' => $dob_score,
            'nid_score' => $nid_score,
            'bcrt_score' => $bcrt_score,
        ];
    }

    /*
     * $action can be 'approve' or 'reject'
     */
    public static function processRequest($ref_id, $process_type_id, $action = 'approve')
    {
        $response = ['responseCode' => 0];
        switch ($process_type_id) {
            case 1: // User Activation

                break;

            case 2: // Agency Activation

                break;

            case 3: // Monazzem Update
                $obj = new MonazzemRequest();
                $response['responseCode'] = $obj->updateMonazzemRequest($ref_id, $action); // approve / reject
                break;
            case 4: // Guide Request
                $obj = new GuidesModel();
                $response['responseCode'] = $obj->updateGuideRequest($ref_id, $action); // approve / reject
                break;

            case 5: // Verify passport for pilgrim registration
                $obj = new PassVerifyReq();
                $response['responseCode'] = $obj->updatePilgrimRequest($ref_id, $action);
                break;
            case 6: // Pilgrim Transfer By Bank

                if ($action == 'reject') {
                    $flag = 0;
                } else if ($action == 'approve') {
                    $flag = 1;
                } else {
                    $flag = null;
                }
                $transferController = new TransferPilgrimController ();
                $transferController->approvedRejectProcess($ref_id, $flag);
                break;

            case 7: // Passport Change
                $obj = new ChangePassport();
                $response = $obj->updateProcessChangeRequest($ref_id, $action);
                break;

            case 8: // House Entry
                $obj = new Houses();
                $response = $obj->updateHouseRequest($ref_id, $action);
                break;

            case 9: // House Entry
                $obj = new Replacement();
                $response = $obj->updatePilgrimReplaceRequest($ref_id, $action);
                break;
        }
        return $response;
    }

    public static function generateRequest($ref_id, $process_type_id, $action = 'submit')
    {
        $response = ['responseCode' => 0];

        switch ($process_type_id) {
            case 1: // User Activation

                break;

            case 2: // Agency Activation

                break;

            case 3: // Monazzem Update
                $obj = new MonazzemRequest();
                $response['responseCode'] = $obj->processMonazzemRequest($ref_id, $action); // submit / cancel / rejectsubmit
                break;
            case 4: // Guide Request
                $obj = new GuidesModel();
                $response = $obj->processGuideRequest($ref_id, $action); // submit / cancel
                break;
            case 5: // Verify passport for pilgrim registration
                $obj = new PassVerifyReq();
                $response['responseCode'] = $obj->processPassportVerifyRequest($ref_id, $action); // submit / cancel
                break;
            case 6: // Transfer Request initiate
                $obj = new TransferPilgrim();
                $response['responseCode'] = $obj->processInitiate($ref_id, $action); // submit / cancel
                break;
        }

        return $response;
    }

    public static function processSubMenu()
    {
        echo '<ul class="nav nav-second-level">';
        $type = Auth::user()->user_type;
        $menus = ProcessType::where('active_menu_for', 'like', "%$type%")->where('status',1)->orderBy('order')->get(['menu_name', 'id']);
        foreach ($menus as $menu)
            echo '<li class="">
                <a href="' . url('/process/list/' . Encryption::encodeId($menu->id)) . '"><i class="fa  fa-hand-o-right"></i>
                    ' . $menu->menu_name . '
                </a>
             </li>';

        echo '</ul>';
    }

    public static function processManagingUsers()
    {
        $processType = ProcessType::where('status', 1)->select(DB::raw('GROUP_CONCAT(active_menu_for) as active_menu_for'))->first(['active_menu_for']);
        $active_menu_for = '';
        if ($processType->active_menu_for != '') {
            $active_menu_for = str_replace(' ', '', $processType->active_menu_for);
        }
        return ($active_menu_for == '') ? [] : explode(',', $active_menu_for);
    }

    public static function processVerifyData($applicationInfo)
    {
        $data = '#D' . $applicationInfo->desk_id . '#R' . $applicationInfo->id . '#S' . $applicationInfo->status_id . '#T' . $applicationInfo->updated_at;
        return $data;
    }

    public static function isAgencyMonazzemExist($agency_id)
    {
        $monazzem = Monazzem::where('agency_id', $agency_id)->where('is_archived', 0)->first(['id']);
        return $monazzem != null;
    }

    public static function checkAvailablePilgrimForRegVoucher($flight_slot_id, $request_pilgrim_total = 0, $except_voucher_id = 0)
    {
        $slot_occupied_total_pilgrim = 0;
        $vouchers = RegVoucher::where('flight_slot_id', $flight_slot_id)
            ->where('is_govt', 'Government')
            ->where('is_archived', 0);
        if ($except_voucher_id > 0) {
            $vouchers = $vouchers->where('id', '!=', $except_voucher_id);
        }
        $vouchers = $vouchers->lists('id', 'id')->all();
        if (count($vouchers) > 0) {
            $slot_occupied_total_pilgrim = RegVoucher::
            join('pilgrims as p', 'registration_voucher.id', '=', 'p.reg_voucher_id')
                ->join('pilgrim_listing as pl', 'pl.id', '=', 'p.pilgrim_listing_id')
                ->join('hajj_sessions as hs', 'hs.id', '=', 'pl.session_id')
                ->where([
                    'p.is_archived' => 0,
                    'registration_voucher.is_archived' => 0,
                    'p.deleted' => '0',
                    'flight_slot_id' => $flight_slot_id,
                    'registration_voucher.is_govt' => 'Government',
                    'hs.state' => 'active',
                ])->count();


            // select * from registration_voucher
        }

        $slotObj = FlightSlots::where('id', $flight_slot_id)->where('status', 1)->where('is_archived', 0)->first(['max_pilgrim']);
        if ($slotObj == null) {
            $response = [
                'responseCode' => 0,
                'occupied_total_slot' => 0,
                'remaining_total_slot' => 0,
                'max_slot_limit' => 0,
                'status' => false,
                'msg' => 'Invalid request'
            ];

            return $response;
        }

        $status = ($slotObj->max_pilgrim - ($slot_occupied_total_pilgrim + $request_pilgrim_total)) >= 0 ? true : false;

        $response = [
            'responseCode' => 1,
            'occupied_total_slot' => $slot_occupied_total_pilgrim,
            'remaining_total_slot' => ($slotObj->max_pilgrim - $slot_occupied_total_pilgrim),
            'max_slot_limit' => $slotObj->max_pilgrim,
            'status' => $status,
            'msg' => $status ? 'Can be added in this slot' : 'Can not be added in this slot'
        ];

        return $response;
    }

    /**
     * @param string $str +ing_1
     * @param string $string_2
     * @return float Percentage of Comparison rate
     */
    public static function nameCompareForPercentage($string_1 = '', $string_2 = '')
    {
        $src1a = UtilFunction::name2ArrayMeta($string_1);
        $src2a = UtilFunction::name2ArrayMeta($string_2);
        if (count($src1a) > count($src2a)) {
            $t = count($src1a);
            $d = count(array_diff($src1a, $src2a));
        } else {
            $t = count($src2a);
            $d = count(array_diff($src2a, $src1a));
        }

        return (($t - $d) / $t) * 100;
    }

    private static function name2ArrayMeta($name)
    {
        $string1 = preg_split("/[\s,.-]+/", strtolower($name));
        $names = array();

        foreach ($string1 as $row):
            if ($row == "md") {
                $names[] = metaphone('mohammad');
            } else if ($row == "mst" || $row == "mrs" || $row == "mss" || $row == "miss") {
                $names[] = metaphone('moshammat');
            } else if (strlen($row) > 2) {
                if (substr($row, 0, 1) == "j") {
                    $row = "z" . substr($row, 1);
                }

                $names[] = metaphone($row);
            }
        endforeach;

        return $names;
    }

    public static function isPassportScanInValid($scan_time = 0)
    {
        $pass_scan_max_duration = Configuration::where('caption', 'PASSPORT_SCAN_MAX_DURATION')->pluck('value');
        return ($scan_time > $pass_scan_max_duration);
    }

    /*
     * Configuration system of passport scan system plus. Checking passport scan system system plus is enable or not
     */

    public static function isPassportScanSystemPlusEnable()
    {
        return Configuration::where('caption', 'PASSPORT_SCAN_SYSTEM_PLUS')->pluck('value') > 0;
    }


    /*
     * Configuration of passport validation system of Registration
     */

    /**
     * @return bool
     */
    public static function regPassportMode()
    {
        $passportMode = Configuration::where('caption', 'REG_PASSPORT_VALIDATION')->first();
        return $passportMode->value == '1' ? true : false;
    }

    /*
     * Place name return function
     */
    public static function placeName($model_name, $id)
    {
        $area_name = '';
        if ($id != '') {
            if ($model_name == 'PassportPlace') {
                $area_name = PassportPlace::where('id', $id)->pluck('place');
            } else if ($model_name == 'AreaInfo') {
                $area_name = AreaInfo::where('area_id', $id)->pluck('area_nm');
            }
        }
        return $area_name;
    }

    public static function trackingNoExistProcessStatus($tracking_no)
    {
        $pilgrim = PassVerifyReq::join('process_list as pl', 'pl.ref_id', '=', 'passport_verify_request.id')
            ->where(
                [
                    'passport_verify_request.tracking_no' => $tracking_no,
                    'passport_verify_request.status' => 1,
                    'passport_verify_request.is_archived' => 0,
                    'passport_verify_request.is_deleted' => 0,
                    'pl.process_type_id' => 5,
                ]
            )
            ->whereIn('pl.status_id', [1, 2])
            ->first(
                [
                    'pl.status_id',
                    'pl.ref_id',
                    'pl.id'
                ]
            );

        return $pilgrim;
    }

    public static function isPassportInfoCorrect($data)
    {
        $tracking_no = $data['tracking_no'];
        $passport_no = $data['passport_no'];
        $birth_date = $data['birth_date'];
        $passport_expire_date = $data['passport_expire_date'];
        $pass_verify_type = $data['pass_verify_type'];
        $created_by = $data['created_by'];

        if ($pass_verify_type == "process") {
            $pilgrim = PassVerifyReq::join('process_list as pl', 'pl.ref_id', '=', 'passport_verify_request.id')
                ->where(
                    [
                        'passport_verify_request.tracking_no' => $tracking_no,
                        'passport_verify_request.status' => 2,
                        'passport_verify_request.is_archived' => 0,
                        'passport_verify_request.is_deleted' => 0,
                        'pl.process_type_id' => 5,
                    ]
                )
                ->where('pl.status_id', 3)
                ->first(
                    [
                        'pl.json_object'
                    ]
                );

            if ($pilgrim == null) {
                return false;
            }


        } else if ($pass_verify_type == "automated") {

        } else if ($pass_verify_type == "none") {
            return false;
        }
    }

    public static function getPassportFullYear($birth_date_str, $pp_user_existing_dob)
    {
        $without_year_prefix = (substr($birth_date_str, 0, 2) . '-' . substr($birth_date_str, 2, 2) . '-' . substr($birth_date_str, 4, 2));
        $year_prefix = substr(trim($pp_user_existing_dob), 0, 2);
        if (strlen(trim($year_prefix . $without_year_prefix)) != 10) {
            return '0000-00-00';
        }
        return trim($year_prefix . $without_year_prefix);
    }


    public static function isEligableForMahram($tracking_no, $management_type, $user_type, $user_sub_type, $reg_created_by, $self_tracking_no)
    {
        $selfPilgrim = Pilgrim::join('pilgrim_listing as pl', 'pl.id', '=', 'pilgrims.pilgrim_listing_id')
            ->join('hajj_sessions as hs', function ($join) {
                $join->on('hs.id', '=', 'pl.session_id');
            })
            ->where('pilgrims.tracking_no', trim($self_tracking_no))
            ->where('pilgrims.pilgrim_listing_id', '>', 0)
            ->where('hs.state', '=', 'active')
            ->where('pilgrims.payment_status', 12)
            ->where('pilgrims.is_archived', 0)
            ->where('pilgrims.deleted', '0')
            ->first(['pl.purpose']);


        if ($selfPilgrim == null) {
            $data = ['responseCode' => 0, 'data' => 'This selected pilgrim is info has problem [SELPIL101]'];
            return $data;
        }
        $purpose = $selfPilgrim->purpose;
        $type_exp = explode('x', $user_type);
        $type = $type_exp[0];

        $pilgrim = Registration::join('pilgrim_listing as pl', 'pl.id', '=', 'pilgrims.pilgrim_listing_id')
            ->join('hajj_sessions as hs', function ($join) {
                $join->on('hs.id', '=', 'pl.session_id');
            })
            ->where('pilgrims.tracking_no', trim($tracking_no))
            ->where(function ($query) use ($management_type, $user_type, $user_sub_type, $reg_created_by, $type) {
                if ($management_type == 'Private') {
                    $query->where('pilgrims.is_govt', 'Private');
                    $query->where('pilgrims.reg_user_type', $user_type);
                    $query->where('pilgrims.reg_agency_id', $user_sub_type);
                } else {
                    if ($type == 4) {
                        $query->where('pilgrims.reg_user_type', 'like', '4x%');
                    } else {
                        $query->where('pilgrims.reg_created_by', $reg_created_by);
                    }
                    $query->where('pilgrims.is_govt', 'Government');
                }
            })
            ->where(function ($query) use ($purpose) {
                if ($purpose == 'special') {
                    $query->where('pilgrims.reg_payment_status', '>=', 0);
                } else {
                    $query->where('pilgrims.reg_payment_status', '=', 0);
                }
            })
            ->where('pilgrims.pilgrim_listing_id', '>', 0)
            ->where('hs.state', '=', 'active')
            ->where('pilgrims.payment_status', 12)
            ->where('pilgrims.is_archived', 0)
            ->where('pilgrims.deleted', '0')
            ->first([
                'pilgrims.id',
                'pilgrims.is_registrable',
                'pilgrims.gender',
                'pilgrims.birth_date',
                'pilgrims.full_name_english',
                'pilgrims.national_id'
            ]);


        $responseCode = 1;
        if ($pilgrim == null) {
            $responseCode = 0;
            $msg = "Pilgrim not found.";
        } elseif ($pilgrim->is_registrable == 0) {
            $responseCode = 0;
            $msg = "The pilgrim is not registered.";
        } elseif ($pilgrim->gender == 'female' || Carbon::createFromFormat('Y-m-d', $pilgrim->birth_date)->diffInYears() < 18) {
            $responseCode = 0;
            $msg = "Female and minor pilgrim can not be assign as Maharam.";
        } else {
            $responseCode = 1;
            $msg = ['pilgrim_name' => 'Full Name: <b> ' . $pilgrim->full_name_english . '</b>', 'pilgrimID' => Encryption::encodeId($pilgrim->id)];
        }
        $data = ['responseCode' => $responseCode, 'data' => $msg];
        return $data;
    }

    public static function isMinimumQuotaOver($agency_id_from, $job_id, $session_id)
    {
        $listed_pilgrim_info = HajjSessions::totalListedPilgrims($agency_id_from);
        $listed_pilgrim_info_curr_job = HajjSessions::totalListedPilgrimsOfTransferJob($agency_id_from, $job_id);
        $session_data = HajjSessions::findOrFail($session_id);

        $responseCode = 1;
        $message = '';

        if (($listed_pilgrim_info->total_listed - $listed_pilgrim_info_curr_job->total_listed) < $session_data->minimum_pilgrim_listing_quota) {
            $responseCode = 0;
            $message = 'Agency minimum listing quota is: ' . $session_data->minimum_pilgrim_listing_quota . ', you have total ' . $listed_pilgrim_info->total_listed . ' listed pilgrims';
        }
        return ['responseCode' => $responseCode, 'message' => $message];
    }

    /*
    * area id to area name conversion function
    */
    public static function areaIdToNameConvert($id)
    {
        return AreaInfo::where('area_id', $id)->pluck('area_nm_ban');
    }

    public static function isReportAdmin()
    {
        return ['1x101', '15x151'];
    }

    public static function isAllowedToViewFvrtReport($report_id)
    {
        if (in_array(Auth::user()->user_type, ['1x101', '15x151'])) // report admin
        {
            return true;
        }
        $is_fvrt = FavReports::where('report_id', $report_id)
            ->where('user_id', Auth::user()->id)
            ->count();
        if ($is_fvrt > 0) {
            $is_publish = Reports::where([
                'report_id' => $report_id,
                'status' => 1
            ])->count();
            $is_assigned = ReportsMapping::where([
                'report_id' => $report_id,
                'user_type' => Auth::user()->user_type
            ])->count();
            if ($is_publish == 0 || $is_assigned == 0) {
                return false;
            }
        }
        return true;
    }

    public static function isPilgrimPaymentOrTransferPendingStatus($from, $job_id_OR_voucherID, $payment_type = '')
    {
        // When checking from transfer
        $data = ['status'=>false,'tracking_nos'=>''];
        if ($from == 'transfer') {
            $query = TransferPilgrimRecords::join('pilgrims', 'transfer_pilgrim_records.pilgrim_id', '=', 'pilgrims.id')
                ->where('transfer_pilgrim_records.transfer_id', $job_id_OR_voucherID)
                ->where(function ($query) {
                    $query->where('payment_status', 11);
                    $query->orWhere('reg_payment_status', 11);
                });
                if($query->count() > 0){
                    $result = $query->first([DB::raw('group_concat(pilgrims.tracking_no) as tracking_nos')]);
                    $data = [
                        'status'=>true,
                        'tracking_nos'=>$result->tracking_nos
                    ];
                }
        } elseif ($from == 'payment') {
            $query = Pilgrim::
            where(function ($query) use ($payment_type, $job_id_OR_voucherID) {
                if ($payment_type == 'pre_reg') {
                    $query->where('pilgrims.group_payment_id', $job_id_OR_voucherID);
                } elseif ($payment_type == 'reg') {
                    $query->where('pilgrims.reg_voucher_id', $job_id_OR_voucherID);
                }
            })
                ->where('pilgrims.transfer_id','>',0);
            if($query->count() > 0){
                $result = $query->first([DB::raw('group_concat(pilgrims.tracking_no) as tracking_nos')]);
                $data = [
                    'status'=>true,
                    'tracking_nos'=>$result->tracking_nos
                ];
            }
        }
        return $data;
    }

}