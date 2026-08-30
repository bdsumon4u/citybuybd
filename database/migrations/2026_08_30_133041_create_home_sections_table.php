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
        Schema::create('home_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('section_type'); // banner_slider, trust_badges, categories_grid, hot_deals, best_selling, latest_products, category_products, all_products, custom_products
            $table->unsignedBigInteger('category_id')->nullable();
            $table->json('product_ids')->nullable();
            $table->string('product_sort')->default('latest'); // latest, oldest, price_low_high, price_high_low, discount_high_low, random
            $table->integer('product_limit')->default(12);
            $table->string('display_style')->default('grid'); // grid, highlight_box, slider
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default sections to preserve current homepage look
        $defaultSections = [
            [
                'title' => 'হিরো ব্যানার স্লাইডার',
                'subtitle' => 'প্রধান ব্যানার ও প্রমোশনাল স্লাইডার',
                'section_type' => 'banner_slider',
                'category_id' => null,
                'product_ids' => null,
                'product_sort' => 'latest',
                'product_limit' => 5,
                'display_style' => 'slider',
                'order_index' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'ট্রাস্ট ও সার্ভিস ব্যাজ',
                'subtitle' => 'ডেলিভারি এবং সার্ভিস তথ্য',
                'section_type' => 'trust_badges',
                'category_id' => null,
                'product_ids' => null,
                'product_sort' => 'latest',
                'product_limit' => 4,
                'display_style' => 'grid',
                'order_index' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'জনপ্রিয় ক্যাটাগরি',
                'subtitle' => 'সকল ক্যাটাগরি ব্রাউজ করুন',
                'section_type' => 'categories_grid',
                'category_id' => null,
                'product_ids' => null,
                'product_sort' => 'latest',
                'product_limit' => 12,
                'display_style' => 'grid',
                'order_index' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'রেকম্যান্ডেবল পণ্য',
                'subtitle' => 'আমাদের সর্বাধিক বিক্রিত এবং সেরা পছন্দের কালেকশন',
                'section_type' => 'best_selling',
                'category_id' => null,
                'product_ids' => null,
                'product_sort' => 'latest',
                'product_limit' => 12,
                'display_style' => 'grid',
                'order_index' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'হট ডিল ও বিশেষ অফার',
                'subtitle' => 'সীমিত সময়ের জন্য বিশেষ মূল্যছাড়ের সুযোগ নিন',
                'section_type' => 'hot_deals',
                'category_id' => null,
                'product_ids' => null,
                'product_sort' => 'latest',
                'product_limit' => 8,
                'display_style' => 'highlight_box',
                'order_index' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'আমাদের নতুন প্রোডাক্টস',
                'subtitle' => 'সদ্য যুক্ত হওয়া ট্রেন্ডিং পণ্যসমূহ',
                'section_type' => 'latest_products',
                'category_id' => null,
                'product_ids' => null,
                'product_sort' => 'latest',
                'product_limit' => 12,
                'display_style' => 'grid',
                'order_index' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'ক্যাটাগরি ভিত্তিক পণ্য',
                'subtitle' => 'শীর্ষ ক্যাটাগরির পণ্যসমূহ',
                'section_type' => 'category_products',
                'category_id' => null,
                'product_ids' => null,
                'product_sort' => 'latest',
                'product_limit' => 6,
                'display_style' => 'grid',
                'order_index' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($defaultSections as $section) {
            \Illuminate\Support\Facades\DB::table('home_sections')->insert(array_merge($section, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_sections');
    }
};
