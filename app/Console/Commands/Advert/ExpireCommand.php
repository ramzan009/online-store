<?php

namespace App\Console\Commands\Advert;

use App\Models\Adverts\Advert\Advert;
use App\UseCases\Adverts\AdvertService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ExpireCommand extends Command
{
    protected $signature = 'advert:expire';

    protected $description = 'Expire advert';

    public function __construct(AdvertService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle(): bool
    {
        $success = true;

        foreach (Advert::active()->where('expire_at', '<', Carbon::now())->cursor() as $advert) {
            try {
                $this->service->expire($advert->id);
            } catch (\DomainException $e) {
                $success = false;
            }
        }
        return $success;
    }
}
