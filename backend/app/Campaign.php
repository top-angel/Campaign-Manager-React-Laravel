<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the banners for the campaign.
     */
    public function banners()
    {
        return $this->hasMany(CampaignBanner::class);
    }
}
