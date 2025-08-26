<?php

namespace App\UseCases\Adverts;

use App\Http\Requests\Admin\Cabinet\Adverts\CreateRequest;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\EditRequest;
use App\Http\Requests\Adverts\PhotosRequest;
use App\Http\Requests\Adverts\RejectRequest;
use App\Models\Adverts\Advert\Advert;
use App\Models\Adverts\Category;
use App\Models\Region;
use App\Models\User;
use App\Services\Search\AdvertIndexer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdvertService
{
    public function create($userId, $categoryId, $regionId, CreateRequest $request)
    {
        $user = User::query()->findOrFail($userId);
        $category = Category::query()->findOrFail($categoryId);
        $region = $regionId ? Region::query()->findOrFail($regionId) : null;

        return DB::transaction(function () use ($user, $request, $category, $region) {

            $advert = Advert::query()->make([
                'title' => $request['title'],
                'content' => $request['content'],
                'price' => $request['price'],
                'address' => $request['address'],
                'status' => Advert::STATUS_DRAFT,
            ]);

            $advert->user()->associate($user);
            $advert->category()->associate($category);
            $advert->region()->associate($region);

            $advert->saveOrFail();

            foreach ($category->attributes() as $attribute) {
                $value = $request->input("attributes.{$attribute->id}");
                if (!empty($value)) {
                    $advert->values()->create([
                        'value' => $value,
                        'attribute_id' => $attribute->id,
                    ]);
                }
            }

            return $advert;

        });
    }

    public function addPhotos($id, PhotosRequest $request)
    {
        $advert = $this->getAdvert($id);

        Db::transaction(function () use ($advert, $request) {
            foreach ($request['file'] as $file) {
                $advert->photos()->create([
                    'file' => $file->store('adverts'),
                ]);
            }
            $advert->update();
        });
    }

    public function sendToModeration($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->sendToModeration();
    }

    public function edit($id, EditRequest $request)
    {
        $advert = $this->getAdvert($id);
        $advert->update($request->only(
            [
                'title',
                'content',
                'price',
                'address',
            ]
        ));
    }

    public function moderate($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->moderate(Carbon::now());
    }

    public function reject($id, RejectRequest $request): void
    {
        $advert = $this->getAdvert($id);
        $advert->reject($request['reason']);
    }

    public function editAttributes($id, AttributesRequest $request): void
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($advert, $request) {
            $advert->values()->delete();
            foreach ($advert->category->attributes() as $attribute) {
                $value = $request->input("attributes.{$attribute->id}") ?? null;
                if (!empty($value)) {
                    $advert->values()->create([
                        'value' => $value,
                        'attribute_id' => $attribute->id,
                    ]);
                }
            }
            $advert->update();
        });
    }

    public function remove($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->delete();
    }

    private function getAdvert($id): Advert
    {
        return Advert::query()->findOrFail($id);
    }

    public function expire(Advert $advert): void
    {
       $advert->expire();
    }

    public function close($id): void
    {
        $advert = $this->getAdvert($id);
        $advert->close();
    }
}
