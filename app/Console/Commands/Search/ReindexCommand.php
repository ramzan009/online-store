<?php

namespace App\Console\Commands\Search;

use App\Models\Adverts\Advert\Advert;
use App\Models\Banner\Banner;
use App\Services\Search\AdvertIndexer;
use App\Services\Search\BannerIndexer;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    protected $signature = 'search:reindex';
    private AdvertIndexer $indexer;
    private $banners;

//    public function __construct(AdvertIndexer $indexer, BannerIndexer $banners)
//    {
//        parent::__construct();
//        $this->indexer = $indexer;
//        $this->banners = $banners;
//    }

    public function handle(): bool
    {
        $this->indexer->clear();

        foreach (Advert::active()->orderBy('id')->cursor() as $advert) {
            $this->indexer->index($advert);
        }

        $this->indexer->clear();

        foreach (Banner::active()->orderBy('id')->cursor() as $banner) {
            $this->banners->index($banner);
        }
        return true;
    }
}
