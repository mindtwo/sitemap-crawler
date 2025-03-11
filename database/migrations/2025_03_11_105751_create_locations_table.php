<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('checksum');
            $table->text('location');
            $table->dateTime('last_modified_at')->nullable()->index();
            $table->string('change_frequency')->nullable();
            $table->double('priority')->nullable();
            $table->string('status');
            $table->foreignId('domain_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('domain_id')->references('id')->on('domains')->cascadeOnDelete();
            $table->unique(['checksum', 'domain_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
