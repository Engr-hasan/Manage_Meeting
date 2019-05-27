<?php

namespace App\Libraries;

use App\Modules\Apps\Models\P2ProcessList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ACL
{

    public static function db_reconnect()
    {
        if (Session::get('DB_MODE') == 'PRODUCTION') {
//        DB::purge('mysql-main');
//        DB::setDefaultConnection('mysql-main');
//        DB::setDefaultConnection(Session::get('mysql_access'));
        }
    }

    public static function hasUserModificationRight($userType, $right, $id)
    {
        try {
            $userId=CommonFunction::getUserId();
            if($userType=='1x101')
                return true;

            if($userId==$id)
                return true;

            return false;
        } catch (\Exception $e) {
            dd(CommonFunction::showErrorPublic($e->getMessage()));
            return false;
        }
    }


    public static function hasApplicationModificationRight($processType, $user_type, $right, $id)
    {
        try {
            if ($right != 'E')
                return true;
            $company_id = CommonFunction::getUserSubTypeWithZero();
            $data = P2ProcessList::where([
                'ref_id' => $id,
                'process_type_id' => $processType,
            ])
                ->first(['p2_process_list.process_status_id', 'p2_process_list.created_by']);
            if ($data->company_id == $company_id && in_array($data->process_status_id, [-1, 5])) {
                return true;
            } else {
                return false;
            }


        } catch (\Exception $e) {
            dd(CommonFunction::showErrorPublic($e->getMessage()));
            return false;
        }
    }

    public static function hasCertificateModificationRight($right, $id)
    {
        try {
            if ($right != 'E')
                return true;
            $info = UploadedCertificates::where('uploaded_certificates.doc_id', $id)->first(['company_id']);
            if ($info->company_id == Auth::user()->user_sub_type) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            dd(CommonFunction::showErrorPublic($e->getMessage()));
            return false;
        }
    }

    public static function getAccsessRight($module, $right = '', $id = null)
    {
        $accessRight = '';
        if (Auth::user()) {
            $user_type = Auth::user()->user_type;
        } else {
            die('You are not authorized user or your session has been expired!');
        }
        switch ($module) {
            case 'settings':
                if ($user_type == '1x101') {
                    $accessRight = 'AVE';
                } elseif ($user_type == '13x303') {
                $accessRight = 'AVE';
            }
                break;
            case 'Training':
                if ($user_type == '1x101') {
                    $accessRight = 'AVE';
                }
                break;
            case 'dashboard':
                if ($user_type == '1x101') {
                    $accessRight = 'AVESERN';
                } elseif ($user_type == '5x505') {
                    $accessRight = 'AVESERNH';
                } elseif ($user_type == '13x131') {
                    $accessRight = 'AVESERNH';
                }
                break;
            case 'faq':
                if ($user_type == '1x101') {
                    $accessRight = 'AVE';
                }else if($user_type == '2x202' ||$user_type == '2x205'){
                    $accessRight = 'V';
                }
                break;
            case 'search':
                if ($user_type == '1x101' || $user_type == '2x202' || $user_type == '2x203') {
                    $accessRight = 'AVE';
                } else if ($user_type == '3x300' || $user_type == '3x305') {
                    $accessRight = 'V';
                } else {

                }
                break;

            case 'report':
                if ($user_type == '1x101') {
                    $accessRight = 'AVE';
                } else if ($user_type == '5x505' || $user_type == '6x606') {
                    $accessRight = 'V';
                } else {
                    $accessRight = 'V';
                }
                break;

            case 'user':
                if ($user_type == '1x101') {
                    $accessRight = '-A-V-E-R-';
                } else if ($user_type == '2x202') {
                    $accessRight = 'VER';
                } else if ($user_type == '4x404') {
                    $accessRight = '-V-R-';
                } else {
                    $accessRight = '-V-R-E';
                }
                if($right=="SPU"){
                    if (ACL::hasUserModificationRight($user_type, $right, $id))
                        return true;
                }

                break;

            case 'processPath':
                if ($user_type == '1x101') {
                    $accessRight = 'AVE';
                }
                break;

            case 'localPurchasePermit':


                if ($user_type == '1x101' || $user_type == '2x202' || $user_type == '7x707' || $user_type == '8x808') {
                    //7x707=Super and 8x808=Zone user, 9x909 Customs
                    $accessRight = '-V-';
                } else if ($user_type == '5x505') {
                    $accessRight = '-A-E-V-';
                    if ($id != null && !(strpos($accessRight, $right) === false)) {
                        if (ACL::hasApplicationModificationRight('11', $user_type, $right, $id) == false)
                            return false;
                    }
                } else if (in_array($user_type, ['3x303', '4x404', '6x606', '7x707', '9x909'])) {
                    $accessRight = '-V-UP-';
                }

                break;
            case 'spaceAllocation':

                if (in_array($user_type,['1x101','2x202','7x707','8x808'])) {
                    //7x707=Super and 8x808=Zone user, 9x909 Customs
                    $accessRight = '-V-';
                } else if ($user_type == '5x505') {
                    $accessRight = '-A-E-V-';
                    if ($id != null && !(strpos($accessRight, $right) === false)) {
                        if (ACL::hasApplicationModificationRight('11', $user_type, $right, $id) == false)
                            return false;
                    }
                } else if (in_array($user_type, ['3x303', '4x404', '6x606', '7x707', '9x909'])) {
                    $accessRight = '-V-UP-';
                }

                break;


            case 'generalApps':

                if (in_array($user_type,['1x101','2x202','7x707','8x808'])) {
                    //7x707=Super and 8x808=Zone user, 9x909 Customs
                    $accessRight = '-V-';
                } else if ($user_type == '5x505') {
                    $accessRight = '-A-E-V-';
                    if ($id != null && !(strpos($accessRight, $right) === false)) {
                        if (ACL::hasApplicationModificationRight('11', $user_type, $right, $id) == false)
                            return false;
                    }
                } else if (in_array($user_type, ['3x303', '4x404', '6x606', '7x707', '9x909'])) {
                    $accessRight = '-V-UP-';
                }

                break;
            case 'loanLocator':
                if (in_array($user_type,['1x101','2x202','7x707','8x808','13x303'])) {
                    $accessRight = '-V-';
                } else if (in_array($user_type, ['4x404'])) {
                    $accessRight = '-A-V-E-UP-';
                }else if (in_array($user_type, ['3x303', '6x606', '7x707', '9x909'])) {
                    $accessRight = '-V-UP-';
                }

                break;

            case 'MeetingForm':
                if (in_array($user_type,['1x101'])) {
                    $accessRight = '-V-';
                } else if (in_array($user_type, ['4x404'])) {
                    $accessRight = '-A-V-E-UP-';
                }else if (in_array($user_type, ['4x404', '6x606', '7x707', '9x909'])) {
                    $accessRight = '-V-UP-';
                }

                break;

            case 'CoBrandedCard':
                if (in_array($user_type,['1x101','7x707','8x808','13x303'])) {
                    $accessRight = '-V-';
                } else if (in_array($user_type, ['5x505'])) {
                    $accessRight = '-A-V-E-UP-';
                }else if (in_array($user_type, ['4x404', '6x606', '7x707', '9x909'])) {
                    $accessRight = '-V-UP-';
                }
            case 'limitRenewal':
                if (in_array($user_type,['1x101','7x707','8x808','13x303'])) {
                    $accessRight = '-V-';
                } else if (in_array($user_type, ['5x505'])) {
                    $accessRight = '-A-V-E-UP-';
                }else if (in_array($user_type, ['4x404', '6x606', '7x707', '9x909'])) {
                    $accessRight = '-V-UP-';
                }

                break;
            case 'BoardMeting':

                if (in_array($user_type,['1x101','2x202','7x707','8x808'])) {
                    $accessRight = '-V-';
                } else if ($user_type == '13x303') {
                    $accessRight = '-A-E-V-';
                } else if (in_array($user_type, ['3x303', '4x404'])) {
                    $accessRight = '-V-UP-';
                }

                break;

            case 'ExportPermit':

                if (in_array($user_type,['1x101','2x202','7x707','8x808'])) {
                    //7x707=Super and 8x808=Zone user, 9x909 Customs
                    $accessRight = '-V-';
                } else if ($user_type == '5x505') {
                    $accessRight = '-A-E-V-';
                    if ($id != null && !(strpos($accessRight, $right) === false)) {
                        if (ACL::hasApplicationModificationRight('11', $user_type, $right, $id) == false)
                            return false;
                    }
                } else if (in_array($user_type, ['3x303', '4x404', '6x606', '7x707', '9x909'])) {
                    $accessRight = '-V-UP-';
                }

                break;

            case 'ImportPermit':

                if (in_array($user_type,['1x101','2x202','7x707','8x808'])) {
                    //7x707=Super and 8x808=Zone user, 9x909 Customs
                    $accessRight = '-V-';
                } else if ($user_type == '5x505') {
                    $accessRight = '-A-E-V-';
                    if ($id != null && !(strpos($accessRight, $right) === false)) {
                        if (ACL::hasApplicationModificationRight('11', $user_type, $right, $id) == false)
                            return false;
                    }
                } else if (in_array($user_type, ['3x303', '4x404', '6x606', '7x707', '9x909'])) {
                    $accessRight = '-V-UP-';
                }

                break;

            case 'certificate':
                if ($user_type == '5x505' || $user_type == '6x606') { // 5x505 = Unit investors and 6x606 = VA users
                    $accessRight = 'AVE';
                    if ($id != null && !(strpos($accessRight, $right) === false)) {
                        if (ACL::hasCertificateModificationRight($right, $id) == false)
                            return false;
                    }
                } else if ($user_type == '1x101') {
                    $accessRight = 'AVE';
                }
                break;

            case 'QuestionBank':
                if ($user_type == '9x909') {
                    $accessRight = 'AVE';
                }
                break;
            case 'Scheduling':
                if ($user_type == '9x909') {
                    $accessRight = 'AVED';
                }
                break;
            case 'ExamList':
                if ($user_type != '9x909') {
                    $accessRight = 'AVE';
                }
                break;
            case 'ResultProcess':
                if ($user_type == '9x909') {
                    $accessRight = 'AV';
                }
                break;

            default:
                $accessRight = '';
        }
        if ($right != '') {
            if (strpos($accessRight, $right) === false) {
                return false;
            } else {
                return true;
            }
        } else {
            return $accessRight;
        }
    }

    public static function isAllowed($accessMode, $right)
    {
        if (strpos($accessMode, $right) === false) {
            return false;
        } else {
            return true;
        }
    }

    /*     * **********************************End of Class****************************************** */
}
