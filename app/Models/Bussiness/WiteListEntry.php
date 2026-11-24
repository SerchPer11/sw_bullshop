<?php

namespace App\Models\Bussiness;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class WiteListEntry extends Model
{
    use HasUlids;
    protected $table = 'wite_list_entries';

    protected $fillable = [
        'email',
        'item_id',
        'item_type',
        'notified_at',
    ];
}
