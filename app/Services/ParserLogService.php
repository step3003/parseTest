<?php

namespace App\Services;


use App\Models\Parse;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;


class ParserLogService
{
    //TODO: 1) Валидация
    //      2) Обработка исключений
    //      3) Репорт (сколько создано, сколько обновлено, сколько с ошибкой и т.д.)
    //      4) Ну и еще там по заданию повыебываться я бы посоветовал
    //      5) Мое решение не притендует на истину(далеко не самое красивое и эффективное. узкие места найди сам), потрачено очень мало рабочего(!) времени =)
    //      6) Good luck!

    public function insertInDb($page)
    {

        // 1. выбираем контент постранично
        for ($i = 0; $i <= $page; $i++) {

            $content = $this->getContent($i);

            // 2. здесь выбираем те объявки, что нужно обновить
            Parse::whereIn('external_id', $content->keys())->chunk(100, function ($toUpdates) use ($content) {
                DB::transaction(function () use ($toUpdates, $content) {
                    $toUpdates->each(function (Parse $item) use ($content) {
                        $advert = $content->pull($item->external_id);
                        $item->images()->update($advert);
                    });
                });
            });

            // 3. оставшиеся распаршеные объявления создаем
            DB::transaction(function () use ($content) {
                $content->each(function ($item) {
                    Parse::create($item)->images()->create($item);
                });
            });
        }
    }


    private function getContent($page): Collection
    {

        $client = new Client();

        if ($page == 0) {
            $uri = 'https://realt.by/rent/flat-for-day';
        } else {
            $uri = 'https://realt.by/rent/flat-for-day/?page=' . $page;
        }


        $crawler = $client->request('GET', $uri);

        $content = collect();





        $crawler->filter('.listing-item')->each(function (Crawler $node, $i) use ($content, $client) {

            if ($node->filter('.teaser-title')->count() > 0) {

                $id = $node->filter('.flex-grow-1')->text();
                $image = $node->filter('.lazy')->attr('data-original');

                $code = substr($id, 3);
                $inAdvertContent = 'https://realt.by/rent/flat-for-day/object/' . $code;
                $newCrawler = $client->request('GET', $inAdvertContent);
                if($newCrawler->filter('p')->count() > 0) {
                    for ($i = 0; $i <= 1; $i++) {
                        $desc  = $newCrawler->filter('p')->eq($i)->text('Описание');
                    }
                }


//               $newCrawler->filter('.object-gallery-item')->each(function (Crawler $node, $i) use ($innerContent) {
//                    $innerContent = $node->filter('img')->attr('data-src');
//
//                });


                $content->put($id, [
                    'external_id' => $id,
                    'title' => $node->filter('.teaser-title')->text(),
                    'views' => $node->filter('.views')->text(),
                    'location' => $node->filter('.location')->text(),
                    'info_large' => $node->filter('.info-large')->text(),
                    'date' => $node->filter('.info-mini > span')->eq(2)->text('Дата'),
                    'price' => $node->filter('.color-black > strong')->text('Договорная цена'),
                    'phone' => $node->filter('.phone-preview > .color-black')->text('Телефон'),
                    'desc' => $desc,
                    'image' => $this->handleDownloadImage($image, $i)

                ]);

            }

        });

        //TODO самостоятельная работа
//            $link = $crawler->filter('.uni-paging > span > span > a')->link()->getUri();
//            $countPage = $crawler->filter('.uni-paging > a')->link()->getNode()->textContent;
//        $client = new Goutte\Client ();
//        $client->getClient->get($img_url, array('save_to' => $img_url_save_name));

        return $content;
    }




    public function fileGetContentCurl($url)
    {
        // Throw Error if the curl function does'nt exist.
        if (!function_exists('curl_init'))
        {
            die('CURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function handleDownloadImage($url, $i): string
    {
        $img = $this->fileGetContentCurl($url);
        $hash = uniqid();
        $path = $hash . '_photo_' . date('dmYHis',time()) . $i . '.jpg';
        file_put_contents('public/images/' . $path, $img);
        return $path;
    }






}
