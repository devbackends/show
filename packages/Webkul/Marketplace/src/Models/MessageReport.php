<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;


class MessageReport extends Model
{


    protected $table = 'marketplace_message_reports';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Get the item that belongs to the item.
     */
    /*  public function messageDetails()
      {
          return $this->belongsTo(Message::modelClass()::modelClass(), 'invoice_item_id');
      }*/
}