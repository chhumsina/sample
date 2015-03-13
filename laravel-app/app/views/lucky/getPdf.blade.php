
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel PHP Framework</title>

    <style>
        @page
        {
            size: auto;   /* auto is the initial value */

            /* this affects the margin in the printer settings */
            margin: 0px;
        }

        body
        {
            /* this affects the margin on the content before sending to printer */
            margin: 0px;
        }

        .note {
            position: relative;
            display: inline;
            float: left;
            width: 555px;
            margin-bottom: 1px;
        }
        .break {
            page-break-after: always;
        }
        span {
            color: #000000;
            font-family: arial, sans-serif;
            font-weight: bold;
        }

        .back {
            width: 555px;
            height: 264px;
            display: inline-block;
            background: url("{{URL::asset('images/background/'.$icon.'.png')}}");
        }
        span {
            color: #000000;
            font-family: arial, sans-serif;
            font-weight: bold;
        }
        .drawDate1 {
            top: 22px;
            left: 455px;
            position: absolute;
        }
        .drawDate2 {
            top: 95px;
            left: 310px;
            position: absolute;
        }
        .tsn {
            top: 128px;
            left: 440px;
            position: absolute;
        }
        .did {
            top: 162px;
            left: 440px;
            position: absolute;
        }
        .barcode {
            top: 184px;
            left: 421px;
            position: absolute;
        }
        .barcode1 {
            top: 184px;
            left: 417px;
            position: absolute;
        }
        .number1 {
            top: 150px;
            left: 310px;
            position: absolute;
        }


    </style>
</head>
<body>
<?php
$count = 1;
?>
@foreach ($notes as $note)

    <div class="note">
        <div class="back">
            @if($count % 2 === 0)
                <div class="tsn">{{$note->khan__code}}</div>
                <div class="barcode"><?php echo '<img style="width:95px; height: 33px;" src="data:image/png;base64,' . DNS1D::getBarcodePNG("9876543210", "EAN13") . '" alt="barcode"   />';?></div>
                <div class="number1">"{{$note->khan__code}}</div>
            {{--<div class="number1">{{ '<img src="data:image/png;base64, ' . DNS1D::getBarcodePNG("1234", "C39+"). '" alt="barcode"   />'}}</div>--}}
            {{--<div class="drawDate1" style="">{{date('Y-m-d', strtotime($note->drawDate))}}</div>--}}
            {{--<div class="drawDate2">{{date('Y-m-d', strtotime($note->drawDate))}}</div>--}}
            @else
                <div class="number1">"{{$note->khan__code}}</div>
                <div class="tsn">{{$note->khan__code}}</div>
                <div class="barcode1"><?php echo '<img style="width:95px; height: 33px;" src="data:image/png;base64,' . DNS1D::getBarcodePNG("464546", "EAN13") . '" alt="barcode"   />';?></div>
            @endif
        </div>

        {{--<span class="did">{{$note->did}}</span>--}}
        {{--<span class="number1">{{$note->number}}</span>--}}
        {{--<span class="number2">{{$note->number}}</span>--}}
        {{--<span class="number2Background">{{HTML::image('images/back.png')}}</span>--}}
        {{--<span class="price">{{HTML::image('images/1000.png')}}</span>--}}
        {{--<span class="powered">Powered by easysolutions</span>--}}
    </div>

    @if($count%2 === 0)
        <br/>
    @endif

    {{--<span class="drawDate2New">{{date('Y-m-d', strtotime($note->drawDate))}}</span>--}}
    {{--<span class="tsn">{{$note->tsn}}</span>--}}
    {{--<span class="did">{{$note->did}}</span>--}}
    {{--<span class="number1">{{$note->number}}</span>--}}
    {{--<span class="number2">{{$note->number}}</span>--}}
    {{--<span class="number2Background">{{HTML::image('images/back.png')}}</span>--}}
    {{--<span class="price">{{HTML::image('images/1000.png')}}</span>--}}
    {{--<span class="powered">Powered by easysolutions</span>--}}
    {{--</div>--}}
    {{--@endif--}}

    @if($count%6 === 0)
        <span class="break"></span>
    @endif
    <?php $count++; ?>
@endforeach
</body>
</html>