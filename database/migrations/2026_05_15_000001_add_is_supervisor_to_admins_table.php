<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->boolean('is_supervisor')->default(false)->after('user_id');
        });

        // Designate test@example.com as the supervisor
        $user = DB::table('users')->where('email', 'test@example.com')->first();
        if ($user) {
            DB::table('admins')->where('user_id', $user->id)->update(['is_supervisor' => true]);
        }
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('is_supervisor');
        });
    }
};
