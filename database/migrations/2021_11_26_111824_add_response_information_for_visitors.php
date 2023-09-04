<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('esplora.connection'))
            ->table('esplora_visits', function (Blueprint $table) {
                $table->integer('response_code')->nullable()->after('referer');
                $table->float('response_time')->nullable()->after('response_code');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('esplora.connection'))
            ->table('esplora_visits', function (Blueprint $table) {
                $table->dropColumn(['response_code', 'response_time']);
            });
    }
};
