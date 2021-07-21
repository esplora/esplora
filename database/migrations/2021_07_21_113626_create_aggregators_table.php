<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('esplora.connection'))
            ->create('esplora_aggregators', function (Blueprint $table) {
                $table->uuid('id');
                $table->json('data');

                $table->dateTime('start_at')->comment('start of the time interval');
                $table->dateTime('finish_at')->comment('end of the time interval');
                $table->timestamp('created_at')->useCurrent();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('esplora.connection'))->dropIfExists('esplora_aggregators');
    }
};
