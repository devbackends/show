<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Webkul\Marketplace\Mail\UserHelpNotification;
use Webkul\Marketplace\Contracts\UserHelpRequest as UserHelpRequestContract;

class UserHelpRequest extends Model implements UserHelpRequestContract
{
    const STATUSES = ['waiting_for_response', 'answered'];

    protected $table = 'user_help_requests';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function create($data)
    {

        // Some validation
        $requiredFields = ['name', 'email', 'text'];
        if (!is_array($data)) return false;
        foreach ($requiredFields as $key) {
            if (!isset($data[$key])) return false;
            if ($data[$key] === '') return false;
        }

        // Store data in db
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->text = $data['text'];
        $this->status = 'waiting_for_response';
        $this->save();

        // Send email
        $this->sendMail($data);

        return true;

    }

    protected function sendMail($message)
    {
        try {
            Mail::send(new UserHelpNotification($message));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}