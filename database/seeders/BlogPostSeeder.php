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
                'title' => 'Chào mừng đến với FinVay',
                'excerpt' => 'Tổng quan nền tảng cho vay trực tuyến tại findvay.net và những điểm nổi bật.',
                'cover_image' => 'images/blog/covers/welcome.svg',
                'author_avatar' => 'images/blog/authors/editor.svg',
                'author_display_name' => 'Phạm Anh',
                'author_title' => 'Biên tập viên FinVay',
                'body' => '<p>FinVay hỗ trợ bạn hoàn tất luồng đăng ký vay: từ OTP, biểu mẫu, tài liệu đến kết quả và hợp đồng điện tử.</p><p>Nội dung này có thể chỉnh sửa trong trang quản trị mục <strong>Tin tức</strong>, kèm ảnh bìa và avatar tác giả.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'author_id' => $authorId,
            ],
        );

        BlogPost::query()->updateOrCreate(
            ['slug' => 'bao-mat-va-otp'],
            [
                'title' => 'Bảo mật và đăng nhập OTP',
                'excerpt' => 'Cách FinVay xử lý xác thực email và phiên làm việc an toàn.',
                'cover_image' => 'images/blog/covers/security.svg',
                'author_avatar' => 'images/blog/authors/security.svg',
                'author_display_name' => 'Anh Tuấn',
                'author_title' => 'Chuyên viên bảo mật',
                'body' => '<p>Khách hàng không cần mật khẩu cố định: mỗi lần đăng nhập nhận mã OTP qua email, có giới hạn tần suất gửi và xác thực.</p><p>Quản trị viên vẫn dùng tài khoản Filament riêng để quản lý nội dung và hồ sơ.</p>',
                'is_published' => true,
                'published_at' => now()->subDay(),
                'author_id' => $authorId,
            ],
        );

        BlogPost::query()->updateOrCreate(
            ['slug' => 'huong-dan-dang-ky-vay-online'],
            [
                'title' => 'Hướng dẫn đăng ký vay trực tuyến từng bước',
                'excerpt' => 'Từ chọn số tiền, xác thực Gmail đến tải CCCD và ký hợp đồng — tất cả trên findvay.net.',
                'cover_image' => 'images/blog/covers/guide.svg',
                'author_avatar' => 'images/blog/authors/team.svg',
                'author_display_name' => 'Đội ngũ FinVay',
                'author_title' => 'Hỗ trợ khách hàng',
                'body' => '<h2>Bước 1: Bắt đầu hồ sơ</h2><p>Chọn số tiền dự kiến, nhập Gmail và số điện thoại, xác nhận mã OTP.</p><h2>Bước 2: Thông tin &amp; giấy tờ</h2><p>Điền thông tin cá nhân, công việc và tải ảnh CCCD theo hướng dẫn trên màn hình.</p><h2>Bước 3: Kết quả &amp; hợp đồng</h2><p>Sau khi hồ sơ được duyệt, bạn xem đề xuất, ký điện tử và tải PDF hợp đồng.</p>',
                'is_published' => true,
                'published_at' => now()->subHours(6),
                'author_id' => $authorId,
            ],
        );
    }
}
