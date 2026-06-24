<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tables for the WealthWise Assistant (AI chat + human handoff).
 * Supports both logged-in users (user_id) and anonymous visitors (guest_id).
 */
class CreateAssistantTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('assistant_conversations')) {
            Schema::create('assistant_conversations', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->nullable();      // logged-in user
                $table->string('guest_id', 64)->nullable();             // anonymous browser token
                $table->string('guest_name')->nullable();               // captured on escalation
                $table->string('guest_email')->nullable();
                $table->enum('status', ['bot', 'pending', 'answered', 'closed'])->default('bot');
                $table->boolean('handed_off')->default(false);          // true once a human is requested
                $table->timestamp('last_message_at')->nullable();
                $table->timestamps();
                $table->index('user_id');
                $table->index('guest_id');
                $table->index('status');
            });
        }

        if (!Schema::hasTable('assistant_messages')) {
            Schema::create('assistant_messages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('conversation_id');
                $table->enum('sender_type', ['user', 'assistant', 'admin', 'system']);
                $table->unsignedBigInteger('sender_id')->nullable();    // user or admin id
                $table->text('message');
                $table->timestamps();
                $table->index(['conversation_id', 'id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('assistant_messages');
        Schema::dropIfExists('assistant_conversations');
    }
}
