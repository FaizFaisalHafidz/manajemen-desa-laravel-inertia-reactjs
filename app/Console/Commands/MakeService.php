<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected Filesystem $files;


    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Services/{$name}.php");

        if ($this->files->exists($path)) {
            $this->error("Service {$name} already exists!");
            return Command::FAILURE;
        }

        $this->files->ensureDirectoryExists(dirname($path));

        $stub = $this->getStub($name);

        $this->files->put($path, $stub);

        $this->info("Service {$name} created successfully.");
        return Command::SUCCESS;
    }

    protected function getStub(string $name): string
    {
        return <<<EOT
<?php

namespace App\Services;

class {$name}
{
    // Add service methods here
}
EOT;
    }
}
