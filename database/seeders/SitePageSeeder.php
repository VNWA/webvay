<?php

namespace Database\Seeders;

use App\Models\SitePage;
use Illuminate\Database\Seeder;

class SitePageSeeder extends Seeder
{
    public function run(): void
    {
        SitePage::query()->updateOrCreate(
            ['slug' => 'gioi-thieu'],
            [
                'title' => 'Giới thiệu FinVay',
                'is_published' => true,
                'body' => <<<'HTML'
<h2>Về chúng tôi</h2>
<p>FinVay là nền tảng cho vay trực tuyến tại findvay.net: quy trình đăng ký có hướng dẫn, xác minh hỗ trợ bởi AI và hợp đồng điện tử trong ứng dụng.</p>
<h2>Sứ mệnh</h2>
<p>Mang lại trải nghiệm ngân hàng số hiện đại — minh bạch về khoản trả, an toàn với OTP và lưu trữ tài liệu riêng tư.</p>
HTML,
            ],
        );

        SitePage::query()->updateOrCreate(
            ['slug' => 'dich-vu'],
            [
                'title' => 'Dịch vụ',
                'is_published' => true,
                'body' => <<<'HTML'
<h2>Đăng ký vay trực tuyến</h2>
<p>Hoàn thành hồ sơ theo từng bước: thông tin cá nhân, việc làm &amp; ngân hàng, tải giấy tờ, xét duyệt AI và nhận đề xuất.</p>
<h2>Xác minh &amp; hợp đồng</h2>
<p>Đăng nhập bằng mã OTP qua email, ký hợp đồng điện tử và tải PDF hợp đồng.</p>
<h2>Hỗ trợ</h2>
<p>Liên hệ qua trang <strong>Liên hệ</strong> hoặc email hỗ trợ được công bố trên findvay.net.</p>
HTML,
            ],
        );
    }
}
