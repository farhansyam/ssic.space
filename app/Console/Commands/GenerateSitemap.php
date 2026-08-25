<?php

namespace App\Console\Commands;

use App\Services\SitemapBuilder;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'app:generate-sitemap';

    protected $description = 'Generate the public sitemap.xml covering all indexable public pages';

    public function handle(): int
    {
        $sitemap = SitemapBuilder::build();
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated at public/sitemap.xml with '.count($sitemap->getTags()).' URLs.');

        return self::SUCCESS;
    }
}
