<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Nama class akan berbeda sesuai nama file Anda
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cek dulu apakah kolom sudah ada (untuk jaga-jaga)
        if (!Schema::hasColumn('users', 'google_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('google_id')->nullable()->unique()->after('is_admin');
            });
        }
         if (!Schema::hasColumn('users', 'avatar')) {
            Schema::table('users', function (Blueprint $table) {
                 $table->string('avatar')->nullable()->after('google_id');
            });
        }
         // Ubah password jadi nullable (jika belum)
         // Perlu doctrine/dbal: composer require doctrine/dbal
         try {
            Schema::table('users', function (Blueprint $table) {
                $table->string('password')->nullable()->change();
            });
         } catch (\Exception $e) {
             \Log::warning("Could not change password to nullable. Maybe already nullable or DBAL is missing? Error: " . $e->getMessage());
         }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         // Hanya lakukan drop jika kolom ada
         if (Schema::hasColumn('users', 'google_id')) {
             Schema::table('users', function (Blueprint $table) {
                  try {
                      $table->dropUnique('users_google_id_unique');
                 } catch (\Exception $e) { \Log::warning('Could not drop unique index on google_id: ' . $e->getMessage()); }
                $table->dropColumn('google_id');
             });
         }
         if (Schema::hasColumn('users', 'avatar')) {
              Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('avatar');
             });
         }

         // Kembalikan password jadi not nullable (hati-hati)
          try {
             Schema::table('users', function (Blueprint $table) {
                // Hanya ubah jika kolom ada dan saat ini nullable
                 if (Schema::hasColumn('users', 'password')) {
                     // Cek manual jika perlu sebelum mengubah non-nullable
                     $table->string('password')->nullable(false)->change();
                 }
             });
          } catch (\Exception $e) {
               \Log::warning("Could not change password to non-nullable. Error: " . $e->getMessage());
          }
    }
};