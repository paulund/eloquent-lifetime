<?php

namespace Paulund\EloquentLifetime\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Paulund\EloquentLifetime\Traits\EloquentLifetime;
use ReflectionClass;

class ModelLifetime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model:lifetime';

    /**
     * @var string
     */
    protected $description = 'Delete old data';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info('Deleting records that are past the model lifetime');

        $modelPath = config('eloquent-lifetime.models.folder');
        $modelFiles = File::allFiles($modelPath);
        $traitName = EloquentLifetime::class;

        foreach ($modelFiles as $file) {
            $className = $this->getClassNameFromFile($file);
            if ($className && $this->classUsesTrait($className, $traitName)) {
                $model = app($className);
                $records = $model->where('created_at', '<', $model->lifetime())->get();

                Log::info('Deleting '.$records->count().' records from '.$className);

                $model->where('created_at', '<', $model->lifetime())->delete();
            }
        }

        $this->info('Model lifetime command ran successfully.');
    }

    /**
     * Get the fully qualified class name from a file.
     *
     * @param  \SplFileInfo  $file
     */
    protected function getClassNameFromFile($file): ?string
    {
        $content = File::get($file->getRealPath());
        if (preg_match('/namespace\s+(.+?);/', $content, $namespaceMatch) &&
            preg_match('/class\s+(\w+)/', $content, $classMatch)) {
            return $namespaceMatch[1].'\\'.$classMatch[1];
        }

        return null;
    }

    /**
     * Check if a class uses a specific trait.
     */
    protected function classUsesTrait(string $className, string $traitName): bool
    {
        $reflectionClass = new ReflectionClass($className);

        return in_array($traitName, $reflectionClass->getTraitNames());
    }
}
