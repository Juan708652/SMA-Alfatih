<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\News;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShareButtonTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(SettingsSeeder::class);
    }

    protected function createNews(): News
    {
        $category = Category::create(['name' => 'Kegiatan', 'slug' => 'kegiatan']);

        return News::create([
            'title' => 'Kegiatan Peringatan Hari Guru',
            'slug' => 'kegiatan-hari-guru',
            'excerpt' => 'Kegiatan peringatan hari guru di sekolah.',
            'content' => 'Paragraf pertama. Paragraf kedua.',
            'category_id' => $category->id,
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
    }

    public function test_article_detail_page_shows_share_buttons(): void
    {
        $news = $this->createNews();

        $html = $this->get('/berita/'.$news->slug)->assertSuccessful()->getContent();

        // Widget Alpine untuk menyalin/membagikan tautan tersedia.
        $this->assertStringContainsString('shareLink(', $html);
        $this->assertStringContainsString('Bagikan artikel ini', $html);
        $this->assertStringContainsString('Salin Link', $html);

        // Tautan berbagi ke media sosial memuat URL artikel.
        $this->assertStringContainsString('https://wa.me/?text=', $html);
        $this->assertStringContainsString('facebook.com/sharer/sharer.php?u=', $html);
        $this->assertStringContainsString('https://t.me/share/url?url=', $html);
        $this->assertStringContainsString('twitter.com/intent/tweet?url=', $html);
    }

    public function test_news_index_cards_have_share_button(): void
    {
        $news = $this->createNews();

        $html = $this->get('/berita')->assertSuccessful()->getContent();

        $this->assertStringContainsString('shareLink(', $html);
        $this->assertStringContainsString(route('news.show', $news), $html);
        $this->assertStringContainsString('Bagikan tautan', $html);
    }

    public function test_home_news_cards_have_share_button(): void
    {
        $news = $this->createNews();

        $html = $this->get('/')->assertSuccessful()->getContent();

        $this->assertStringContainsString('shareLink(', $html);
        $this->assertStringContainsString(route('news.show', $news), $html);
        $this->assertStringContainsString('Bagikan tautan', $html);
    }
}
