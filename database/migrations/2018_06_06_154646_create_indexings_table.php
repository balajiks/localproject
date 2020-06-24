<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration auto-generated by Sequel Pro Laravel Export
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indexings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('source')->nullable();
            $table->integer('stage_id')->nullable();
            $table->string('job_title', 150)->nullable();
			$table->string('userrole', 150)->nullable();
            $table->string('company')->nullable();
            $table->string('phone', 150)->nullable();
            $table->string('mobile', 150)->nullable();
            $table->string('email', 191)->unique();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city', 150)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('zip_code', 64)->nullable();
            $table->string('country', 150)->nullable();
            $table->string('timezone', 150)->nullable();
            $table->string('website', 191)->nullable();
            $table->string('skype', 191)->nullable();
            $table->string('facebook', 191)->nullable();
            $table->string('twitter', 191)->nullable();
            $table->string('linkedin', 191)->nullable();
            $table->integer('sales_rep')->nullable();
            $table->integer('indexing_score')->default(10);
            $table->dateTime('due_date')->nullable();
            $table->decimal('indexing_value', 10, 2)->default(0.00);
            $table->string('computed_value', 32)->default('$0.00');
            $table->decimal('todo_percent', 10, 2)->default(0.00);
            $table->longText('message')->nullable();
            $table->tinyInteger('has_email')->default(0);
            $table->tinyInteger('has_activity')->default(0);
            $table->dateTime('next_followup')->nullable();
            $table->dateTime('unsubscribed_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->text('token')->nullable();
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
        Schema::dropIfExists('indexings');
    }
}