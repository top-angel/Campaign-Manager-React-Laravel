<?php

namespace App\Http\Controllers\API;

use App\Campaign;
use App\CampaignBanner;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public $response = [];
    public $response_code = 200;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $campaigns = Campaign::with('banners')->paginate(config('app.per_page'));

            if ($campaigns->count()) {
                $this->response = [
                    'success' => 1,
                    'msg' => __('messages.data_fetched'),
                    'data' => $campaigns->items(),
                ];
            } else {
                $this->response = [
                    'success' => 0,
                    'msg' => __('messages.data_not_found'),
                    'data' => [],
                ];
            }
        } catch (\Throwable $th) {
            Log::error(__FILE__.'::'.$th->getMessage());
            $this->response = [
                'success' => 0,
                'msg' => __('messages.server_error'),
                'data' => [],
            ];
        }

        return response()->json($this->response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request\StoreCampaignRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCampaignRequest $request)
    {
        try {
            $campaign = Campaign::create($request->all());
            if ($campaign) {
                // Setting response for campaign saved, if there is any creative uploads it will be replaced according to it or else remains same
                $this->response = [
                    'success' => 1,
                    'msg' => __('messages.data_saved'),
                    'data' => $campaign,
                ];

                // Campaign created , check if banners exists upload banners
                if ($request->hasFile('creative_uploads')) {
                    $creative_uploads = $request->file('creative_uploads');
                    $imgNotUploaded = 0;
                    $imgUploaded = 0;
                    foreach ($creative_uploads as $creative_upload) {
                        $campaign_banner = new CampaignBanner();
                        $campaign_banner->banner_file_name = '';
                        $campaign_banner->campaign_id = $campaign->id;
                        $campaign_banner->save();

                        $fileName = pathinfo($creative_upload->getClientOriginalName(), PATHINFO_FILENAME);
                        $fileName = Str::slug($fileName).'.'.$creative_upload->getClientOriginalExtension();

                        // Creating the directory related to particular advertising id with proper permissions
                        Storage::makeDirectory(config('app.campaign_banner_path').$campaign_banner->id.'/', 0755, true, true);

                        // Using putFileAs of the storage facade to manipulate the file name to a easy to understand name
                        Storage::putFileAs(config('app.campaign_banner_path').$campaign_banner->id.'/', $creative_upload, $fileName);

                        // NOTE :: We can also use Image intervention library to make the banners different size but for a basic purpose i've used simple direct image upload

                        $campaign_banner->banner_file_name = $fileName;
                        // Storing and Noting the number of banners that are uploaded and not uploaded, so later on user can check before leaving the data as it is
                        if (
                            $campaign_banner->save()
                        ) {
                            $imgUploaded++;
                        } else {
                            $imgNotUploaded++;
                        }
                    }
                    if ($imgUploaded > 0) {
                        $this->response = [
                            'success' => 1,
                            'msg' => __('messages.data_saved'),
                            'data' => Campaign::with('banners')->findOrFail($campaign->id),
                        ];

                        // All medias are uploaded but what if some of the media is not uploaded
                        if ($imgNotUploaded > 0) {
                            $this->response['msg'] = __('messages.data_saved_partially');
                        }
                    } else {
                        $this->response = [
                            'success' => 0,
                            'msg' => __('messages.data_saved_media_error'),
                            'data' => Campaign::with('banners')->findOrFail($campaign->id),
                        ];
                    }
                }
            } else {
                $this->response = [
                    'success' => 0,
                    'msg' => __('messages.data_save_error'),
                    'data' => [],
                ];
            }
        } catch (\Throwable $th) {
            Log::error(__FILE__.'::'.$th->getMessage());
            $this->response = [
                'success' => 0,
                'msg' => __('messages.server_error'),
                'data' => [],
            ];
        }

        return response()->json($this->response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $campaign = Campaign::with('banners')->findOrFail($id);

            if ($campaign) {
                $this->response = [
                    'success' => 1,
                    'msg' => __('messages.data_fetched'),
                    'data' => $campaign,
                ];
            } else {
                $this->response = [
                    'success' => 0,
                    'msg' => __('messages.data_not_found'),
                    'data' => [],
                ];
            }
        } catch (\Throwable $th) {
            Log::error(__FILE__.'::'.$th->getMessage());
            $this->response = [
                'success' => 0,
                'msg' => __('messages.server_error'),
                'data' => [],
            ];
        }

        return response()->json($this->response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCampaignRequest $request, $id)
    {
        try {
            $campaign = Campaign::findOrFail($id);

            if ($campaign->update($request->all())) {
                // Setting response for campaign saved, if there is any creative uploads it will be replaced according to it or else remains same
                $this->response = [
                    'success' => 1,
                    'msg' => __('messages.data_saved'),
                    'data' => $campaign,
                ];

                // Campaign created , check if banners exists upload banners
                if ($request->hasFile('creative_uploads')) {
                    $creative_uploads = $request->file('creative_uploads');
                    $imgNotUploaded = 0;
                    $imgUploaded = 0;
                    foreach ($creative_uploads as $creative_upload) {
                        $campaign_banner = new CampaignBanner();
                        $campaign_banner->banner_file_name = '';
                        $campaign_banner->campaign_id = $campaign->id;
                        $campaign_banner->save();

                        $fileName = pathinfo($creative_upload->getClientOriginalName(), PATHINFO_FILENAME);
                        $fileName = Str::slug($fileName).'.'.$creative_upload->getClientOriginalExtension();

                        // Creating the directory related to particular advertising id with proper permissions
                        Storage::makeDirectory(config('app.campaign_banner_path').$campaign_banner->id.'/', 0755, true, true);

                        // Using putFileAs of the storage facade to manipulate the file name to a easy to understand name
                        Storage::putFileAs(config('app.campaign_banner_path').$campaign_banner->id.'/', $creative_upload, $fileName);

                        // NOTE :: We can also use Image intervention library to make the banners different size but for a basic purpose i've used simple direct image upload

                        $campaign_banner->banner_file_name = $fileName;
                        // Storing and Noting the number of banners that are uploaded and not uploaded, so later on user can check before leaving the data as it is
                        if (
                            $campaign_banner->save()
                        ) {
                            $imgUploaded++;
                        } else {
                            $imgNotUploaded++;
                        }
                    }
                    if ($imgUploaded > 0) {
                        $this->response = [
                            'success' => 1,
                            'msg' => __('messages.data_saved'),
                            'data' => Campaign::with('banners')->findOrFail($campaign->id),
                        ];

                        // All medias are uploaded but what if some of the media is not uploaded
                        if ($imgNotUploaded > 0) {
                            $this->response['msg'] = __('messages.data_saved_partially');
                        }
                    } else {
                        $this->response = [
                            'success' => 0,
                            'msg' => __('messages.data_saved_media_error'),
                            'data' => Campaign::with('banners')->findOrFail($campaign->id),
                        ];
                    }
                }
            } else {
                $this->response = [
                    'success' => 0,
                    'msg' => __('messages.data_save_error'),
                    'data' => [],
                ];
            }
        } catch (\Throwable $th) {
            Log::error(__FILE__.'::'.$th->getMessage());
            $this->response = [
                'success' => 0,
                'msg' => __('messages.server_error'),
                'data' => [],
            ];
        }

        return response()->json($this->response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /*
         * Here in destroy method i've just simply deleted the campaign entity ,
         * instead we can also implement model observer in order to check if any model is being deleted and then ,
         * all it's related data like files/temp db records will be also destroyed
         */
        try {
            $campaign = Campaign::findOrFail($id);
            $campaign->destroy();

            $this->response = [
                'success' => 1,
                'msg' => __('messages.data_removed'),
                'data' => [],
            ];
        } catch (\Throwable $th) {
            Log::error(__FILE__.'::'.$th->getMessage());
            $this->response = [
                'success' => 0,
                'msg' => __('messages.server_error'),
                'data' => [],
            ];
        }
    }
}
