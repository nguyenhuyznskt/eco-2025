<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeServiceCommand extends Command
{
    /**
     * Tên và signature của command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * Mô tả command.
     *
     * @var string
     */
    protected $description = 'Tạo một service mới trong app/Services';

    /**
     * Thực thi command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Services/{$name}.php");

        // Nếu chưa có folder Services thì tạo
        if (! is_dir(app_path('Services'))) {
            mkdir(app_path('Services'));
        }

        // Nếu file đã tồn tại thì báo lỗi
        if (file_exists($path)) {
            $this->error("Service {$name} đã tồn tại!");
            return;
        }

        // Nội dung file service mặc định
        $template = <<<PHP
<?php

namespace App\Services;

class {$name}
{
    //
}

PHP;

        file_put_contents($path, $template);

        $this->info("Service {$name} created successfully at app/Services/{$name}.php");
    }
}
