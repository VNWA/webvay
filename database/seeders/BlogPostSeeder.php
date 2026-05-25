<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $authorId = User::query()->where('role', UserRole::Admin)->value('id');

        BlogPost::query()->updateOrCreate(
            ['slug' => 'chao-mung-den-finvay'],
            [
                'title' => 'Chào mừng đến với FinVay ',
                'excerpt' => 'Tổng quan nền tảng cho vay số và những điểm nổi bật trong bản trình diễn.',
                'body' => '<p>FinVay giúp bạn trải nghiệm đầy đủ luồng đăng ký vay: từ OTP, biểu mẫu, tài liệu đến kết quả và hợp đồng.</p><p>Nội dung này có thể chỉnh sửa trong trang quản trị mục <strong>Tin tức</strong>.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'author_id' => $authorId,
            ],
        );

        BlogPost::query()->updateOrCreate(
            ['slug' => 'bao-mat-va-otp'],
            [
                'title' => 'Bảo mật và đăng nhập OTP',
                'excerpt' => 'Cách demo xử lý xác thực email và phiên làm việc.',
                'body' => '<p>Khách hàng không cần mật khẩu cố định: mỗi lần đăng nhập nhận mã OTP qua email, có giới hạn tần suất gửi và xác thực.</p><p>Quản trị viên vẫn dùng tài khoản Filament riêng để quản lý nội dung và hồ sơ.</p>',
                'is_published' => true,
                'published_at' => now()->subDay(),
                'author_id' => $authorId,
            ],
        );
    }
}
