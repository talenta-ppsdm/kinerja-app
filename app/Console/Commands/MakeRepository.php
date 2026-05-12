<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';
    protected $description = 'Create a new repository class';

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $className = "{$name}";
        $modelName = str_replace('Repository', '', $className);
        $repositoryPath = app_path("Repositories/{$className}.php");

        if (file_exists($repositoryPath)) {
            $this->error("Repository {$className} already exist");
            return;
        }

        file_put_contents($repositoryPath, "<?php
    
namespace App\Repositories;
    
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\{$modelName};
    
class {$className} extends BaseRepository
{
    /**
     * Specify the model class name.
     *
     * @return string
     */
    public function model()
    {
        return {$modelName}::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        // Add your boot logic here
    }
}");

        $this->info("Repository class {$className} sucssesfully created");
    }
}