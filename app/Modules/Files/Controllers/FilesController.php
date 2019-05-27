<?php namespace App\Modules\Files\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Files\Models\ImgUserProfile;
use DB;
use Illuminate\Http\Request;

class FilesController extends Controller {

	public function getFile($param)
	{
		if ($param['type'] == 'user') {
			$info = ImgUserProfile::where(['ref_id' => $param['ref_id']])->first();
		}

		if (!$info || $info == null) {
			$data = ['responseCode' => 0, 'data' => null];
		} else {
			$data = ['responseCode' => 1, 'data' => $info->details];
		}
		return response()->json($data);
	}

	public function putPicture($img_obj, $files_data = array())
	{
		$status = false; // 1=inserted, 2=updated, false=anything wrong
		if (!is_object($img_obj)) {
			return false;
		}
		try {
			DB::beginTransaction();
			$status = isset($img_obj->created_by) ? 2 : 1;// 1=inserted, 2=updated
			foreach ($files_data as $field => $value) {
				$img_obj->$field = $value;
			}
			$img_obj->save();
			DB::commit();
			return $status;
		} catch (\Exception $e) {
			DB::rollback();
			return $status;
		}
	}

	public function store($type, Request $request, ImgUserProfile $ImgUserProfile)
	{


		if ($type) {
			$err = array();
			$base64_img = $request->get('base64_img');
			$ing_data = getimagesize($base64_img);
			$img_mime_type = $ing_data['mime'];
			$img_width = $ing_data['0'];
			$img_height = $ing_data['1'];
			extract(CommonFunction::getImageDocConfig());

			// picture dimension and size validation
			if ($img_mime_type != 'image/jpeg' && $img_mime_type != 'image/png') {
				$err[] = "Please select JPEG, PNG file<br/>";
			}

			// checking image size and dimension
			if ($type == 'user' || $type == 'profile_pic_tmp') {
				if (!($img_width >= $IMAGE_MIN_WIDTH && $img_width <= $IMAGE_MAX_WIDTH) || !($img_height >= $IMAGE_MIN_HEIGHT && $img_height <= $IMAGE_MAX_HEIGHT)) {
					$err[] = "Image width  between ($IMAGE_MIN_WIDTH-$IMAGE_MAX_WIDTH)px <br/>& height  between ($IMAGE_MIN_HEIGHT-$IMAGE_MAX_HEIGHT)px required<br/>";
				}
			}
			if ($type == 'pilgrim' || $type == 'pilgrim_pic_tmp') {
				if (!($img_width >= $IMAGE_MIN_WIDTH && $img_width <= $IMAGE_MAX_WIDTH) || !($img_height >= $IMAGE_MIN_HEIGHT && $img_height <= $IMAGE_MAX_HEIGHT)) {
					$err[] = "Image width  between ($IMAGE_MIN_WIDTH-$IMAGE_MAX_WIDTH)px <br/>& height  between ($IMAGE_MIN_HEIGHT-$IMAGE_MAX_HEIGHT)px required<br/>";
				}
			}

			if ($type == 'auth_file' || $type == 'auth_file_tmp' || $type == 'birth_crt_tmp' || $type == 'pilgrim_nrb_tmp') {
				if (!($img_width >= $DOC_MIN_WIDTH && $img_width <= $DOC_MAX_WIDTH) || !($img_height >= $DOC_MIN_HEIGHT && $img_height <= $DOC_MAX_HEIGHT)) {
					$err[] = "File width  between ($DOC_MIN_WIDTH-$DOC_MAX_WIDTH)px <br/>& height  between ($DOC_MIN_HEIGHT-$DOC_MAX_HEIGHT)px required<br/>";
				}
			}
			if (isset($err) && count($err) > 0) {
				$data = ['responseCode' => 0, 'data' => $err];
			} else {
				if ($type != 'auth_file_tmp') {
					$ref_id = Encryption::decodeId($request->get('ref_id'));
				} else {
					$ref_id = $request->get('ref_id');
				}

				$files_data = array();
				if ($type == 'user') {
					$files_data = array(
						'ref_id' => $ref_id,
						'details' => $base64_img
					);
					$img_obj = ImgUserProfile::firstOrNew(['ref_id' => $ref_id]);
					$this->putPicture($img_obj, $files_data);
				} elseif ($type == 'auth_file') {
					$files_data = array(
						'ref_id' => $ref_id,
						'details' => $base64_img
					);
					$img_obj = ImgAuthFile::firstOrNew(['ref_id' => $ref_id]);
					$this->putPicture($img_obj, $files_data);
				} elseif ($type == 'auth_file_tmp' || $type == 'birth_crt_tmp' || $type == 'profile_pic_tmp' || $type == 'pilgrim_pic_tmp' || $type == "pilgrim_nrb_tmp") {
					$files_data = array(
						'nid' => $ref_id,
						'type' => $type,
						'details' => $base64_img
					);
					$img_obj = FilesTmp::firstOrNew(['nid' => $ref_id, 'type' => $type]);
					$this->putPicture($img_obj, $files_data);
				} elseif ($type == 'pilgrim') {
					$tracking_no = $request->get('tracking_no');
					$files_data = array(
						'ref_id' => $ref_id,
						'tracking_no' => $tracking_no,
						'details' => $base64_img
					);
					$img_obj = ImgPilgrim::firstOrNew(['ref_id' => $ref_id]);
					$this->putPicture($img_obj, $files_data);
				}

				$data = ['responseCode' => 1, 'data' => $files_data];
			}
			return json_encode($data);
		}
	}
}
