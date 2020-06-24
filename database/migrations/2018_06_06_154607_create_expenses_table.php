<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration auto-generated by Sequel Pro Laravel Export
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 150);
            $table->integer('billable')->default(1);
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->decimal('before_tax', 10, 2)->default(0.00);
            $table->string('amount_formatted', 64)->default('$0.00');
            $table->date('expense_date')->nullable();
            $table->text('notes')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->tinyInteger('invoiced')->default(0);
            $table->integer('invoiced_id')->nullable();
            $table->integer('category')->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('tax2', 10, 2)->nullable();
            $table->decimal('taxed', 10, 2)->nullable();
            $table->decimal('todo_percent', 10, 2)->default(0.00);
            $table->string('currency', 12)->default('USD');
            $table->tinyInteger('is_recurring')->default(0);
            $table->integer('recurred_from')->nullable();
            $table->decimal('exchange_rate', 10, 5)->default(1.00000);
            $table->string('vendor')->nullable();
            $table->tinyInteger('is_visible')->default(1);
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('expenses');
    }
}