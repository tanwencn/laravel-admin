<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOperationLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('operation_logs', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('user_id')->unsigned();
			$table->string('method', 10)->index('method');
			$table->string('uri', 255);
			$table->string('ip', 255);
			$table->text('body');
			$table->integer('status');
            $table->timestamps();
            $table->index(['user_id', 'uri']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('operation_logs');
	}

}
