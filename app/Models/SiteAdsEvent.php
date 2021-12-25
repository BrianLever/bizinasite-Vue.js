<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteAdsEvent extends BaseModel
{
    protected $table = 'site_ads_events';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public $timestamps = false;

    const CUSTOM_VALIDATION_MESSAGE = [

    ];


    public function listing()
    {
        return $this->belongsTo(SiteAdsListing::class, 'listing_id')->withDefault();
    }
}
