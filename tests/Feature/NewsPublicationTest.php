<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\News;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsPublicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(SettingsSeeder::class);
    }

    private function makeNews(array $attributes = []): News
    {
        $category = Category::create(['name' => 'Kegiatan', 'slug' => 'kegiatan']);

        return News::create(array_merge([
            'title' => 'Berita Uji Publikasi Waktu WIB',
            'slug' => 'berita-uji-publikasi-waktu-wib',
            'excerpt' => 'Ringkasan berita uji.',
            'content' => 'Isi berita uji.',
            'category_id' => $category->id,
            'is_published' => true,
            'published_at' => now(),
        ], $attributes));
    }

    public function test_application_timezone_is_wib(): void
    {
        $this->assertSame('Asia/Jakarta', config('app.timezone'));
    }

    public function test_news_published_now_appears_on_index_and_home(): void
    {
        $news = $this->makeNews();

        $this->get('/berita')->assertSuccessful()->assertSee($news->title);
        $this->get('/')->assertSuccessful()->assertSee($news->title);
    }

    public function test_future_dated_news_is_hidden_until_its_scheduled_time(): void
    {
        $news = $this->makeNews(['published_at' => now()->addDay()]);

        $this->get('/berita')->assertSuccessful()->assertDontSee($news->title);
        $this->get('/')->assertSuccessful()->assertDontSee($news->title);
    }
}
