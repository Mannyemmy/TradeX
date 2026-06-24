<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Admin-editable knowledge base that the WealthWise Assistant (Gemini) uses
 * to answer questions about the site.
 */
class AddAssistantKnowledgeToSettings extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'assistant_knowledge')) {
                $table->longText('assistant_knowledge')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'assistant_knowledge')) {
                $table->dropColumn('assistant_knowledge');
            }
        });
    }
}
