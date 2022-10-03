<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Marketplace\Models\UserHelpRequest;

class UserHelpRequestController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    public function __construct()
    {
        $this->_config = request('_config');
    }

    public function handle($id)
    {
        $message = UserHelpRequest::find($id);
        $statuses = UserHelpRequest::STATUSES;

        return view($this->_config['view'], compact('message', 'statuses'));
    }

    public function update($id, Request $request)
    {
        $data = $request->validate([
            'status' => 'required'
        ]);
        $statuses = UserHelpRequest::STATUSES;
        if (in_array($data['status'], $statuses)) {
            $message = UserHelpRequest::find($id);
            $message->status = $data['status'];
            $message->save();
            session()->flash('success', __('marketplace::app.admin.user-help-requests.success-update'));
        } else {
            session()->flash('error', __('marketplace::app.admin.user-help-requests.error-update'));
        }

        return response()->redirectToRoute('admin.user-help-requests.index');
    }

}