<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/style.css" rel="stylesheet" type="text/css"/>


    <title>Laravel</title>


</head>
<body >

<div class="pag top">{{$data->render('pagination::bootstrap-4')}}</div>
<h1 class="title_page">Объявления</h1>
<div class="cards">
    @foreach($data as $item)
        <div class="">
            <a class="card" href="{{ route('adv.show', $item->id) }}">
            <div class="card_block">
                @foreach ($item->images as $itemImage)
                    <img class="images" src="{{ asset('/images/' . $itemImage->image ) }}" />
                @endforeach
                <div class="price">{{$item->price}}</div>
            </div>
            <div class="card_content">
                <div class="card_info">
                    <div class="id">Номер: {{$item->external_id}}</div>
                    <div class="views">Просмотры: {{$item->views}}</div>
                    <div class="date">Дата: {{$item->date}}</div>
                </div>
                <div class="title"> <strong>{{$item->title}}</strong></div>
                <div class="location"> {{$item->location}}</div>
                <div class="info-large"> {{$item->info_large}}</div>
                <div class="phone">{{$item->phone}}</div>
            </div>
            </a>
        </div>

    @endforeach
</div>

        <div class="pag">{{ $data->render('pagination::bootstrap-4')}}</div>
        <div class="count">
           <div>Количество объявлений: {{$data->count()}}</div>
        </div>


</body>
</html>
