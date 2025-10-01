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
        $name = $this->argument('name'); // ví dụ: Admin/ProductService
        $path = app_path("Services/{$name}.php");

        // Tạo folder cha nếu chưa có
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        // Nếu file đã tồn tại thì báo lỗi
        if (file_exists($path)) {
            $this->error("Service {$name} đã tồn tại!");
            return;
        }

        // Xử lý namespace & class
        $namespace = 'App\\Services';
        $subNamespace = str_replace('/', '\\', dirname($name));
        if ($subNamespace !== '.' && $subNamespace !== '') {
            $namespace .= '\\' . $subNamespace;
        }
        $class = basename($name);

        // Nội dung file service mặc định
        $template = <<<PHP
<?php

namespace {$namespace};

class {$class}
{
    //
}

PHP;

        file_put_contents($path, $template);

        $this->info("Service {$class} created successfully at {$path}");
    }
}
