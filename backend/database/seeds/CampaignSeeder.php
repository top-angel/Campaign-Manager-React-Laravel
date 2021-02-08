<?php

use App\Campaign;
use App\CampaignBanner;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Campaign seeders without banners

        $campaign = new Campaign;
        $campaign->name = 'The Movie Campaign';
        $campaign->from_date = Carbon::now()->format('Y-m-d');
        $campaign->to_date = Carbon::now()->addDays(30)->format('Y-m-d');
        $campaign->daily_budget = 10; // budget in usd
        $campaign->total_budget = 300; // budget in usd
        $campaign->active = 1;
        $campaign->Save();

        // Store 2 demo banners to the campaign
        $campaign_banner = new CampaignBanner;
        $campaign_banner->banner_file_name = '150x150.png';
        $campaign_banner->campaign_id = $campaign->id;
        $campaign_banner->save();

        File::copyDirectory(base_path('dummy-data/campaigns/'), base_path('storage/app/public/campaigns/'));
    }
}
