<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 🧩 Thuộc tính
        Schema::table('attributes', function (Blueprint $table) {
            if (!Schema::hasColumn('attributes', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });

        // 🧱 Giá trị thuộc tính
        Schema::table('attribute_values', function (Blueprint $table) {
            if (!Schema::hasColumn('attribute_values', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });

        // 📂 Danh mục
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });

        // 📦 Sản phẩm
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });

        // 🏪 Nhà cung cấp
        Schema::table('vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('vendors', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });

        // 🎟️ Voucher
        Schema::table('vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('vouchers', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });

        // 🎫 Ticket hỗ trợ
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });

        // 👥 Người dùng (nếu chưa có)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down(): void
    {
        $tables = [
            'attributes',
            'attribute_values',
            'categories',
            'products',
            'vendors',
            'vouchers',
            'tickets',
            'users'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                if (Schema::hasColumn($t->getTable(), 'deleted_at')) {
                    $t->dropSoftDeletes();
                }
            });
        }
    }
};
