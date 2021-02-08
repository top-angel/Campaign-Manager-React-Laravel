<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',255);
            $table->double('daily_budget',8,2); // amount
            $table->double('total_budget', 8, 2); // amount
            $table->date('from_date'); // campaign start date
            $table->date('to_date'); // campaign end date
            $table->boolean('active')->default(1); // flag if campaign is active or not
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
}
