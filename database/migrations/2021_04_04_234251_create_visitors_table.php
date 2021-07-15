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
            ->create('esplora_visits', function (Blueprint $table) {
                $table->string('id');

                $table->string('url')->nullable();
                $table->string('ip', 40);

                $table->string('device')->nullable();             // Mobile, Desktop, Tablet, Robot
                $table->string('platform')->nullable();           // Ubuntu, Windows, OS X
                $table->string('browser')->nullable();            // Chrome, IE, Safari, Firefox,
                $table->string('preferred_language')->nullable(); // en/jp/ru/de

                $table->string('referer')->nullable();

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
        Schema::connection(config('esplora.connection'))->dropIfExists('visits');
    }
};
