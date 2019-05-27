<?php namespace App\Modules\Support\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Faq\Models\Faq;
use App\Modules\Faq\Models\FaqTypes;
use App\Modules\Settings\Models\Notice;
use App\Modules\Support\Models\Feedback;
use App\Modules\Support\Models\FeedbackTopics;
use App\Modules\Users\Models\UserTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use yajra\Datatables\Datatables;

class SupportController extends Controller {

	/*
	 * Need Help function
	 */
	public function help($module = '') {
		$faqs = Faq::leftJoin('faq_multitypes', 'faq.id', '=', 'faq_multitypes.faq_id')
			->leftJoin('faq_types', 'faq_multitypes.faq_type_id', '=', 'faq_types.id')
			->where('status', 'public')
			->where('faq_types.name', $module)
			->get(['question', 'answer', 'status', 'faq_type_id as types', 'name as faq_type_name', 'faq.id as id']);

		$existedFaqType = FaqTypes::where('name', $module)->pluck('name');
		if (empty($existedFaqType)) {
			FaqTypes::create(
				array(
					'name' => ucfirst($module),
					'created_by' => CommonFunction::getUserId()
				));
		}

		$logged_in_user_type = Auth::user()->user_type;
		$user_manual = UserTypes::where('id', $logged_in_user_type)
			->pluck('user_manual_name');
		if ($faqs == null) {
			Session::flash('error', 'Sorry, there is no help available for this module!');
		}

		return view("Support::help.index", compact('faqs', 'user_manual'));
	}

	/*
	 * FEEDBACK list
	 */
	public function feedback() {
		return view("Support::feedback.list");
	}

	/*
	 * create a new feedback
	 */
	public function createFeedback() {
		$topics = FeedbackTopics::lists('name', 'id');
		$sysAdmin_email = [(object) ['user_email' => 'prp@hajj.gov.bd']];

		return view("Support::feedback.create", compact('topics', 'sysAdmin_email'));
	}

	/*
	 * get feedback details data
	 */
	public function getFeedbackDetailsData() {
		$feedbacks = Feedback::leftJoin('feedback_topics', 'feedback.topic_id', '=', 'feedback_topics.id')
			->where('created_by', Auth::user()->id)
			->where('parent_id', 0)
			->orderBy('feedback.created_at', 'desc')
			->get(['feedback.id as feedback_id', 'feedback_topics.name as topic_name', 'description', 'status', 'priority',
				'feedback.created_by as feedbackCreator', 'feedback.created_at as created', 'feedback.updated_at as updated']);

		$functionUrl = '';

		return Datatables::of($feedbacks)
			->addColumn('action', function ($feedbacks) {
				global $functionUrl;
				if ($feedbacks->status == 'draft' && $feedbacks->feedbackCreator == Auth::user()->id) {
					$functionUrl = 'edit-feedback';
				} else {
					$functionUrl = 'view-feedback';
				}
				return '<a href="/support/' . $functionUrl . '/' . Encryption::encodeId($feedbacks->feedback_id) .
				'" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a>';
			})
			->editColumn('status', function ($feedbacks) {
				return ucfirst($feedbacks->status);
			})
			->editColumn('created', function ($feedbacks) {
				return CommonFunction::changeDateFormat(substr($feedbacks->created, 0, 10));
			})
			->editColumn('updated', function ($feedbacks) {
				return CommonFunction::changeDateFormat(substr($feedbacks->updated, 0, 10)) . ' ' . substr($feedbacks->updated, -8);
			})
			->editColumn('description', function ($feedbacks) {
				global $functionUrl;
				return mb_substr($feedbacks->description, 0, 100) . "... "
				. "<a href='/support/" . $functionUrl . "/" . Encryption::encodeId($feedbacks->feedback_id) . "'>"
				. "See more"
				. "</a>";
			})
			->removeColumn('feedback_id')
			->make(true);
	}

	/*
	 * Uncategorized details data
	 */
	public function getUncategorizedFeedbackData($flag) {
		if ($flag == 'submitted_to') {
			$assigned_to = Auth::user()->id;
		} elseif ($flag == 'unassigned') {
			$assigned_to = 0;
		}
		$feedbacks = Feedback::leftJoin('feedback_topics', 'feedback.topic_id', '=', 'feedback_topics.id')
			->where('assigned_to', $assigned_to)
			->where('parent_id', 0)
			->where('status', '!=', 'draft')
			->where('status', '!=', 'closed')
			->where('status', '!=', 'rejected')
			->where('status', '!=', 'canceled')
			->orderBy('feedback.created_at', 'desc')
			->get(['feedback.id as feedback_id', 'feedback_topics.name as topic_name', 'description', 'status', 'priority',
				'feedback.created_by as feedbackCreator', 'feedback.created_at as created', 'feedback.updated_at as updated']);

		$functionUrl = '';

		return Datatables::of($feedbacks)
			->addColumn('action', function ($feedbacks) {
				global $functionUrl;
				if ($feedbacks->status == 'draft' AND $feedbacks->feedbackCreator == Auth::user()->id) {
					$functionUrl = 'edit-feedback';
				} else {
					$functionUrl = 'view-feedback';
				}
				return '<a href="/support/' . $functionUrl . '/' . Encryption::encodeId($feedbacks->feedback_id) .
				'" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a>';
			})
			->editColumn('status', function ($feedbacks) {
				return ucfirst($feedbacks->status);
			})
			->editColumn('created', function ($feedbacks) {
				return CommonFunction::changeDateFormat(substr($feedbacks->created, 0, 10));
			})
			->editColumn('updated', function ($feedbacks) {
				return CommonFunction::changeDateFormat(substr($feedbacks->updated, 0, 10)) . ' ' . substr($feedbacks->updated, -8);
			})
			->editColumn('description', function ($feedbacks) {
				global $functionUrl;
				return mb_substr($feedbacks->description, 0, 100) . "... "
				. "<a href='/support/" . $functionUrl . "/" . Encryption::encodeId($feedbacks->feedback_id) . "'>"
				. "See more"
				. "</a>";
			})
			->removeColumn('feedback_id')
			->make(true);
	}

    /* Start of Notice related functions */

    public function viewNotice($encrypted_id) {
        $id = Encryption::decodeId($encrypted_id);
        $data = Notice::where('id', $id)->first();
        $notice = CommonFunction::getNotice(1);
        return view("Support::notice.view", compact('data', 'encrypted_id', 'notice'));
    }

    /* End of Notice related functions */
}
