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
            ->create('esplora_goals', function (Blueprint $table) {
                $table->string('id');
                $table->string('visitor_id');
                $table->string('name');
                $table->json('parameters')->nullable();
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
        Schema::connection(config('esplora.connection'))->dropIfExists('esplora_goals');
    }
};
