<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShouldNotifyColumnToShareableLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shareable_links', function (Blueprint $table) {
            $table->boolean('should_notify')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shareable_links', function (Blueprint $table) {
            $table->dropColumn('should_notify');
        });
    }
}
