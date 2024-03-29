<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasChatsToIndexings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indexings', function (Blueprint $table) {
            $table->tinyInteger('has_chats')->default(0);
            $table->tinyInteger('whatsapp_optin')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indexings', function (Blueprint $table) {
            $table->dropColumn('has_chats');
            $table->dropColumn('whatsapp_optin');
        });
    }
}
