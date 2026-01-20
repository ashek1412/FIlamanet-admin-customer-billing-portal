<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            // id bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->id();

            // user_id bigint(20) unsigned DEFAULT NULL + Foreign Key
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            // name varchar(500) DEFAULT NULL
            $table->string('name', 500)->nullable();

            // icris varchar(250) DEFAULT NULL
            $table->string('icris', 250)->nullable();

            // code varchar(25) DEFAULT NULL
            $table->string('code', 25)->nullable();

            // created_by and updated_by bigint(20)
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // created_at and updated_at datetime DEFAULT NULL
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();

            // If you prefer Laravel's default timestamp (timestamp vs datetime)
            // you could use $table->timestamps(); instead of the two lines above.

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_general_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
