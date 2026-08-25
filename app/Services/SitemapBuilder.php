<?php

namespace App\Services;

use App\Models\DonationCampaign;
use App\Models\Event;
use App\Models\Kelas;
use App\Models\Post;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapBuilder
{
    public static function build(): Sitemap
    {
        $sitemap = Sitemap::create()
            ->add(Url::create(route('home'))->setPriority(1.0))
            ->add(Url::create(route('kelas.index'))->setPriority(0.8))
            ->add(Url::create(route('kegiatan.index'))->setPriority(0.8))
            ->add(Url::create(route('donasi.index'))->setPriority(0.8))
            ->add(Url::create(route('blog.index'))->setPriority(0.8));

        Kelas::whereIn('status', ['dibuka', 'penuh', 'selesai'])->each(
            fn (Kelas $kelas) => $sitemap->add(
                Url::create(route('kelas.show', $kelas))
                    ->setLastModificationDate($kelas->updated_at)
                    ->setPriority(0.6)
            )
        );

        Event::each(
            fn (Event $event) => $sitemap->add(
                Url::create(route('kegiatan.show', $event))
                    ->setLastModificationDate($event->updated_at)
                    ->setPriority(0.6)
            )
        );

        DonationCampaign::each(
            fn (DonationCampaign $campaign) => $sitemap->add(
                Url::create(route('donasi.show', $campaign))
                    ->setLastModificationDate($campaign->updated_at)
                    ->setPriority(0.6)
            )
        );

        Post::where('status', 'publish')->each(
            fn (Post $post) => $sitemap->add(
                Url::create(route('blog.show', $post))
                    ->setLastModificationDate($post->updated_at)
                    ->setPriority(0.6)
            )
        );

        return $sitemap;
    }
}
