@extends('layouts.structure')

@section('header')
    @parent
    <style>
        .col-xs-12 {
            margin-top: 10px;
        }

        button {
            margin-right: 10px;
        }

        .row {
            direction: rtl;
        }

        .rtl {
            direction: rtl;
        }
        .sparkline-outline-iconRTL {
            left: 0;
            right: auto;
        }
    </style>
@stop

@section('content')

    @include('layouts.modal')

    <?php $i = 0; ?>

    <button id="addBtn" class="hidden btn btn-default transparentBtn" style="width: auto" data-toggle="modal" data-target="#InformationproModalalert"></button>

    @foreach($photos as $photo)

        <?php $j = 0; ?>
        <div class="col-lg-6">
            <div class="sparkline{{($i + 8)}}-list ct-map-b-mg-30 shadow-reset">
                <div class="sparkline{{($i + 8)}}-hd">
                    <div class="rtl main-sparkline{{($i + 8)}}-hd">
                        <h1>
                            <span>تگ alt</span>
                            <span>:</span>
                            <span>&nbsp;</span>
                            <span>{{$alts[$i]}}</span>
                        </h1>
                        <div class="sparkline-outline-iconRTL sparkline{{($i + 8)}}-outline-icon">
                            <span class="sparkline{{($i + 8)}}-collapse-link"><i class="fa fa-chevron-up"></i></span>
                            <span onclick="localCreateModal('{{$alts[$i]}}', '{{$i}}')"><i class="fa fa-wrench"></i></span>
                            @if($i != 0)
                                <span onclick="removeMainPic('{{$i}}')" class="sparkline{{($i + 8)}}-collapse-close"><i class="fa fa-times"></i></span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="sparkline{{($i + 8)}}-graph">
                    <div class="data-map-single">
                        <div>
                            @foreach($photo as $itr)
                                <p style="margin-top: 10px; direction: rtl">{{$metaPhoto[$i][$j]}}</p>
                                <span style="cursor: pointer" onclick="localCreateModalForPic('{{$i}}', '{{$j}}')"><i class="fa fa-wrench"></i></span>
                                <img src="{{$itr}}">
                                <?php $j++ ?>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $i++; ?>

    @endforeach

    @foreach($userPhotos as $photo)

        <?php $j = 0; ?>
        <div class="col-lg-6">
            <div class="sparkline{{($i + 8)}}-list ct-map-b-mg-30 shadow-reset">
                <div class="sparkline{{($i + 8)}}-hd">
                    <div class="rtl main-sparkline{{($i + 8)}}-hd">
                        <h1>
                            <span>تگ alt</span>
                            <span>:</span>
                            <span>&nbsp;</span>
                            <span>{{$photo->alt}}</span>
                        </h1>
                        <div class="sparkline-outline-iconRTL sparkline{{($i + 8)}}-outline-icon">
                            <span class="sparkline{{($i + 8)}}-collapse-link"><i class="fa fa-chevron-up"></i></span>
                            <span onclick="localCreateModalForUserPic('{{$photo->alt}}', '{{$photo->subject}}', '{{$photo->category}}', '{{$photo->id}}', '{{$photo->confirm}}')"><i class="fa fa-wrench"></i></span>
                            @if($i != 0)
                                <span onclick="removeUserPic('{{$photo->id}}')" class="sparkline{{($i + 8)}}-collapse-close"><i class="fa fa-times"></i></span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="sparkline{{($i + 8)}}-graph">
                    <div class="data-map-single">
                        <p style="direction: rtl"><span>توضیحات:</span><span>&nbsp;</span><span>{{$photo->subject}}</span></p>
                        <p style="direction: rtl"><span>دسته:</span><span>&nbsp;</span><span>{{$photo->category}}</span></p>
                        <p style="direction: rtl"><span>وضعیت:</span><span>&nbsp;</span><span>{{$photo->confirm}}</span></p>
                        <?php $j = 0; ?>
                        @foreach($photo->pics as $pic)
                            <div>
                                <span style="cursor: pointer" onclick="localCreateModalForChangeUserPic('{{$photo->id}}', '{{$j}}')"><i class="fa fa-wrench"></i></span>
                                <img src="{{$pic}}">
                            </div>
                            <?php $j++; ?>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <?php $i++; ?>

    @endforeach

    <script>

        function removeUserPic(id) {
            $.ajax({
                type: 'post',
                url: '{{route('removeUserPic')}}',
                data: {
                    'id': id
                }
            });
        }
        
        function removeMainPic(idx) {

            $.ajax({
                type: 'post',
                url: '{{route('removeMainPic', ['id' => $id, 'kindPlaceId' => $kindPlaceId])}}',
                data: {
                    'idx': idx
                }
            });
        }
        
        function localCreateModal(currAlt, idx) {
            createModal(
                '{{route('doChangeAlt', ['id' => $id, 'kindPlaceId' => $kindPlaceId])}}',
                [
                    {'name': 'alt', 'value': currAlt, 'class': [], 'type': 'text', 'label': 'تگ جدید'},
                    {'name': 'idx', 'value': idx, 'class': ['hidden'], 'type': 'text', 'label': 'تگ جدید'}
                ],
                'تغییر تگ alt',
                ''
            );

            setTimeout(function(){
                $("#addBtn").click();
            }, 1);
        }

        function localCreateModalForUserPic(currAlt, currSubject, currCategory, id, currConfirm) {
            createModal(
                    '{{route('doChangeAltForUserPic')}}',
                    [
                        {'name': 'alt', 'value': currAlt, 'class': [], 'type': 'text', 'label': 'تگ جدید'},
                        {'name': 'id', 'value': id, 'class': ['hidden'], 'type': 'text', 'label': 'تگ جدید'},
                        {'name': 'subject', 'value': currSubject, 'class': [], 'type': 'text', 'label': 'توضیحات جدید'},
                        {'name': 'category', 'value': currCategory, 'class': [], 'type': 'select', 'label': 'دسته جدید', 'options': '{!! json_encode($picItems) !!}'},
                        {'name': 'confirm', 'value': currConfirm, 'class': [], 'type': 'select', 'label': 'وضعیت جدید', 'options': '{!! json_encode($acceptance) !!}'}
                    ],
                    'تغییر تگ alt',
                    ''
            );

            setTimeout(function(){
                $("#addBtn").click();
            }, 1);
        }

        function localCreateModalForPic(idx, sizeIdx) {
            createModal(
                    '{{route('doChangePic', ['id' => $id, 'kindPlaceId' => $kindPlaceId])}}',
                    [
                        {'name': 'pic', 'value': '', 'class': [], 'type': 'file', 'label': 'فایل جدید'},
                        {'name': 'idx', 'value': idx, 'class': ['hidden'], 'type': 'text', 'label': 'تگ جدید'},
                        {'name': 'sizeIdx', 'value': sizeIdx, 'class': ['hidden'], 'type': 'text', 'label': 'تگ جدید'}
                    ],
                    'تغییر تصویر',
                    '',
                    true
            );

            setTimeout(function(){
                $("#addBtn").click();
            }, 1);
        }

        function localCreateModalForChangeUserPic(id, idx) {

            createModal(
                    '{{route('doChangeUserPic')}}',
                    [
                        {'name': 'pic', 'value': '', 'class': [], 'type': 'file', 'label': 'فایل جدید'},
                        {'name': 'id', 'value': id, 'class': ['hidden'], 'type': 'text', 'label': 'تگ جدید'},
                        {'name': 'idx', 'value': idx, 'class': ['hidden'], 'type': 'text', 'label': 'تگ جدید'}
                    ],
                    'تغییر تصویر',
                    '',
                    true
            );

            setTimeout(function(){
                $("#addBtn").click();
            }, 1);
        }

    </script>
@stop