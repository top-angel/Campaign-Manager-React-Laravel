<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CampaignBanner extends Model
{
    protected $guarded = ['id'];
    protected $appends = ['banner_url'];

    /**
     * Accessor to access banner's url.
     */
    public function getBannerUrlAttribute()
    {
        $url = Storage::url(config('app.campaign_banner_path').$this->id.'/'.$this->banner_file_name);

        return $url;
    }
}
