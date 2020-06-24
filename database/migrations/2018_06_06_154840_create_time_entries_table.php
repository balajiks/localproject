<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTimeEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('timeable');
            $table->integer('user_id')->nullable();
            $table->integer('task_id')->nullable();
            $table->bigInteger('start')->nullable();
            $table->bigInteger('end')->nullable();
            $table->tinyInteger('billable')->default(1);
            $table->bigInteger('total')->nullable();
            $table->string('notes')->nullable();
            $table->tinyInteger('is_started')->default(0);
            $table->integer('started_by')->nullable();
            $table->tinyInteger('billed')->default(0);
            $table->timestamp('archived_at')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('time_entries');
    }
}
