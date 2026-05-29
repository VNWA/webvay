<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_home_still_loads(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_static_pages_and_blog_are_public(): void
    {
        $this->get('/gioi-thieu')->assertOk()->assertSee('Giới thiệu FinVay', false);
        $this->get('/dich-vu')->assertOk()->assertSee('Dịch vụ', false);
        $this->get('/tin-tuc')->assertOk()->assertSee('Chào mừng đến với FinVay', false);

        $this->get('/tin-tuc/chao-mung-den-finvay')->assertOk()->assertSee('Chào mừng đến với FinVay', false);
    }

    public function test_blog_shows_cover_and_author_avatars(): void
    {
        $this->get('/tin-tuc')
            ->assertOk()
            ->assertSee('Phạm Anh', false)
            ->assertSee('images/blog/covers/welcome.svg', false);

        $this->get('/tin-tuc/chao-mung-den-finvay')
            ->assertOk()
            ->assertSee('Biên tập viên FinVay', false)
            ->assertSee('images/blog/authors/editor.svg', false);
    }

    public function test_blog_without_media_shows_placeholders(): void
    {
        BlogPost::query()->updateOrCreate(
            ['slug' => 'bai-khong-anh'],
            [
                'title' => 'Bài viết không ảnh',
                'excerpt' => 'Kiểm tra placeholder.',
                'body' => '<p>Nội dung thử.</p>',
                'cover_image' => null,
                'author_avatar' => null,
                'author_display_name' => 'Khách mời',
                'is_published' => true,
                'published_at' => now(),
            ],
        );

        $this->get('/tin-tuc/bai-khong-anh')
            ->assertOk()
            ->assertSee(__('blog_no_cover_image'), false)
            ->assertSee(__('blog_no_avatar_image'), false)
            ->assertSee('Tác giả chưa tải ảnh đại diện', false);
    }

    public function test_contact_form_accepts_message(): void
    {
        $response = $this->post('/lien-he', [
            'name' => 'Nguyễn A',
            'email' => 'a@example.com',
            'phone' => '0900000000',
            'subject' => 'Hỏi về dịch vụ',
            'message' => 'Nội dung test liên hệ.',
        ]);

        $response->assertRedirect(route('contact.create'));
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'a@example.com',
        ]);
    }
}
