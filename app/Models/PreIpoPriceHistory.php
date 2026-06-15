<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreIpoPriceHistory extends Model
{
    public $timestamps = false;

    protected $table = 'pre_ipo_price_history';

    protected $fillable = [
        'pre_ipo_company_id', 'price', 'changed_by', 'note', 'created_at',
    ];

    protected $casts = [
        'price' => 'float',
        'created_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(PreIpoCompany::class, 'pre_ipo_company_id');
    }
}
