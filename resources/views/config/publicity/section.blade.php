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
        td {
            min-width: 80px;
            padding: 8px;
        }
    </style>
@stop

@section('content')

    @include('layouts.modal')

    <div class="col-md-1"></div>

    <div class="col-md-10">

        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>مدیریت بخش های تبلیغاتی</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">

                    @if(count($section) == 0)
                        <div class="col-xs-12">
                            <h4 class="warning_color">بخشی وجود ندارد</h4>
                        </div>
                    @else
                        <form method="post" action="{{route('deleteSection')}}">
                            {{csrf_field()}}
                            <center class="col-xs-12">
                                <table style="max-width: 100%; overflow-x: auto; display: block">
                                    <tr>
                                        <td>نام بخش</td>
                                        <td>top</td>
                                        <td>left</td>
                                        <td>right</td>
                                        <td>bottom</td>
                                        <td>عرض</td>
                                        <td>ارتفاع</td>
                                        <td>background-size</td>
                                        <td>نمایش در حالت گوشی</td>
                                        <td>mobile top</td>
                                        <td>mobile left</td>
                                        <td>mobile right</td>
                                        <td>mobile bottom</td>
                                        <td>عملیات</td>
                                    </tr>
                                    @foreach($section as $itr)
                                        <tr>
                                            <td>
                                                {{$itr->name}}
                                            </td>

                                            <td>
                                                {{$itr->top_}}
                                            </td>

                                            <td>
                                                {{$itr->left_}}
                                            </td>

                                            <td>
                                                {{$itr->right_}}
                                            </td>

                                            <td>
                                                {{$itr->bottom_}}
                                            </td>

                                            <td>
                                                {{$itr->width}}
                                            </td>

                                            <td>
                                                {{$itr->height}}
                                            </td>

                                            <td>
                                                {{($itr->backgroundSize) ? 'contain' : 'cover'}}
                                            </td>

                                            <td>
                                                {{($itr->mobileHidden == 1) ? 'بله' : 'خیر'}}
                                            </td>

                                            <td>
                                                {{$itr->mobileTop}}
                                            </td>

                                            <td>
                                                {{$itr->mobileLeft}}
                                            </td>

                                            <td>
                                                {{$itr->mobileRight}}
                                            </td>

                                            <td>
                                                {{$itr->mobileBottom}}
                                            </td>

                                            <td>
                                                <button name="deleteSection" value="{{$itr->id}}" class="sparkline9-collapse-close transparentBtn" data-toggle="tooltip" title="حذف سطح" style="width: auto">
                                                    <span ><i class="fa fa-times"></i></span>
                                                </button>

                                                <button type="button" name="editSection" onclick="updateSection('{{$itr->id}}', '{{$itr->name}}', '{{$itr->top_}}', '{{$itr->left_}}', '{{$itr->right_}}', '{{$itr->bottom_}}', '{{$itr->width}}', '{{$itr->height}}', '{{$itr->mobileHidden}}', '{{$itr->backgroundSize}}', '{{$itr->mobileTop}}', '{{$itr->mobileLeft}}', '{{$itr->mobileRight}}', '{{$itr->mobileBottom}}')" class="sparkline9-collapse-close transparentBtn" style="width: auto" data-toggle="modal" data-target="#InformationproModalalert">
                                                    <span ><i class="fa fa-wrench"></i></span>
                                                </button>

                                                <button type="button" name="editSection" onclick="document.location.href = '{{route('sectionStep2', ['sectionId' => $itr->id])}}'" class="sparkline9-collapse-close transparentBtn" style="width: auto">ویرایش  صفحات</button>

                                            </td>
                                        </tr>

                                    @endforeach
                                </table>
                            </center>
                        </form>
                    @endif

                    <div class="col-xs-12">
                        <button id="addBtn" onclick="createSection()" class="btn btn-default transparentBtn" style="width: auto" data-toggle="modal" data-target="#InformationproModalalert">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </div>

                </center>
            </div>
        </div>
    </div>

    <div class="col-md-1"></div>

    <script>

        function createSection() {
            createModal('{{route('section')}}',
                    [{'name': 'name', 'class': [], 'type': 'text', 'label': 'نام قسمت', 'value': ''},
                    {'name': 'top', 'class': [], 'type': 'text', 'label': 'top', 'value': ''},
                    {'name': 'left', 'class': [], 'type': 'text', 'label': 'left', 'value': ''},
                    {'name': 'right', 'class': [], 'type': 'text', 'label': 'right', 'value': ''},
                    {'name': 'bottom', 'class': [], 'type': 'text', 'label': 'bottom', 'value': ''},
                    {'name': 'width', 'class': [], 'type': 'text', 'label': 'عرض', 'value': ''},
                    {'name': 'height', 'class': [], 'type': 'text', 'label': 'ارتفاع', 'value': ''},
                    {'name': 'mobileHidden', 'class': [], 'type': 'select',  'label': 'نمایش در حالت گوشی', 'options': JSON.stringify([
                        {'id': 1, 'name': 'ندارد'},
                        {'id': 0, 'name': 'دارد'}
                    ]), 'value': ''},
                    {'name': 'backgroundSize', 'class': [], 'type': 'select',  'label': 'background-size', 'options': JSON.stringify([
                        {'id': 1, 'name': 'contain'},
                        {'id': 0, 'name': 'cover'}
                    ]), 'value': ''},
                    {'name': 'mobileTop', 'class': [], 'type': 'text', 'label': 'mobile top', 'value': ''},
                    {'name': 'mobileLeft', 'class': [], 'type': 'text', 'label': 'mobile left', 'value': ''},
                    {'name': 'mobileRight', 'class': [], 'type': 'text', 'label': 'mobile right', 'value': ''},
                    {'name': 'mobileBottom', 'class': [], 'type': 'text', 'label': 'mobile bottom', 'value': ''}],
                    'افزودن قسمت جدید'
                    , '{{(isset($msg) ? $msg : '')}}');
        }

        function updateSection(id, name, top, left, right, bottom, width, height, mobileHidden, backgroundSize, mobileTop,
                               mobileLeft, mobileRight, mobileBottom) {
            createModal('{{route('editSection')}}',
                    [
                        {'name': 'id', 'class': ['hidden'], 'type': 'hidden', 'label': '', 'value': id},
                        {'name': 'name', 'class': [], 'type': 'text', 'label': 'نام قسمت', 'value': name},
                        {'name': 'top', 'class': [], 'type': 'text', 'label': 'top', 'value': top},
                        {'name': 'left', 'class': [], 'type': 'text', 'label': 'left', 'value': left},
                        {'name': 'right', 'class': [], 'type': 'text', 'label': 'right', 'value': right},
                        {'name': 'bottom', 'class': [], 'type': 'text', 'label': 'bottom', 'value': bottom},
                        {'name': 'width', 'class': [], 'type': 'text', 'label': 'عرض', 'value': width},
                        {'name': 'height', 'class': [], 'type': 'text', 'label': 'ارتفاع', 'value': height},
                        {'name': 'mobileHidden', 'class': [], 'type': 'select',  'label': 'نمایش در حالت گوشی', 'options': JSON.stringify([
                            {'id': 1, 'name': 'ندارد'},
                            {'id': 0, 'name': 'دارد'}
                        ]), 'value': mobileHidden},
                        {'name': 'backgroundSize', 'class': [], 'type': 'select',  'label': 'background-size', 'options': JSON.stringify([
                            {'id': 1, 'name': 'contain'},
                            {'id': 0, 'name': 'cover'}
                        ]), 'value': backgroundSize},
                        {'name': 'mobileTop', 'class': [], 'type': 'text', 'label': 'mobile top', 'value': mobileTop},
                        {'name': 'mobileLeft', 'class': [], 'type': 'text', 'label': 'mobile left', 'value': mobileLeft},
                        {'name': 'mobileRight', 'class': [], 'type': 'text', 'label': 'mobile right', 'value': mobileRight},
                        {'name': 'mobileBottom', 'class': [], 'type': 'text', 'label': 'mobile bottom', 'value': mobileBottom}],
                    'افزودن قسمت جدید'
                    , '');
        }

    </script>

@stop