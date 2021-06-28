<?php
/*
 * Copyright (c) FSCharter Ltd. - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 * Last modified 29/05/2021, 18:18.
 */

namespace AlexGodbehere\LaravelPremiumFeatures\Console;

use AlexGodbehere\LaravelPremiumFeatures\Model\Feature;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class FeatureMakeCommand extends Command
{

    protected $name = 'make:feature';

    protected $signature = 'make:feature {name} {domain} {title} {description} {cancel} {check}';

    protected $description = 'Create a new premium feature';

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    private Filesystem $files;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {

        $this->files = $files;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // Generate the feature class
        Artisan::call('make:feature-class', [
          'name'        => $this->argument('name'),
          'domain'      => $this->argument('domain'),
          'title'       => $this->argument('title'),
          'description' => $this->argument('description'),
          'cancel'      => $this->argument('cancel'),
          'check'       => $this->argument('check'),
        ]);

        // Add the feature to the database
        Feature::create([
          'name'           => $this->argument('title'),
          'description'    => $this->argument('description'),
          'cancel_warning' => $this->argument('cancel'),
          'check_string'   => $this->argument('check'),
          'class_name'     => 'App\Domain\\'.$this->argument('domain').'\Features\\'.$this->argument('name'),
        ]);

        return true;
    }

    protected function makeDirectory($path)
    {

        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

}
