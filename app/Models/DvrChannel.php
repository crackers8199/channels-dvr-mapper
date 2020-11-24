<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DvrChannel
 * @package App\Models
 *
 */

class DvrChannel extends Model
{
    use HasFactory;

    protected $fillable = [
        'dvr_lineup_id',
        'guide_number',
        'mapped_channel_number',
    ];
}
