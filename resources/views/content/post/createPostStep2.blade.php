@extends('layouts.structure')

@section('header')
    @parent

    <style>

        th, td {
            min-width: 100px;
            text-align: right;
        }

    </style>

@stop

@section('content')

    <div class="col-md-1"></div>

    <div class="col-md-10">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>افزودن پست جدید</h1>
                </div>
            </div>

            <div style="height: auto !important;" class="sparkline8-graph dashone-comment  dashtwo-messages">
                <center class="row">

                    <div class="col-xs-12">
                        <div class="datatable-dashv1-list custom-datatable-overright">
                            <table style="direction: rtl" id="table" data-toggle="table" data-key-events="true" data-resizable="true">
                                <thead>
                                    <tr>
                                        <th data-field="temp"  class="hidden" data-editable="false"></th>
                                        <th data-field="tag1" data-editable="true">تگ 1</th>
                                        <th data-field="tag2" data-editable="true">تگ 2</th>
                                        <th data-field="tag3" data-editable="true">تگ 3</th>
                                        <th data-field="tag4" data-editable="true">تگ 4</th>
                                        <th data-field="tag5" data-editable="true">تگ 5</th>
                                        <th data-field="tag6" data-editable="true">تگ 6</th>
                                        <th data-field="tag7" data-editable="true">تگ 7</th>
                                        <th data-field="tag8" data-editable="true">تگ 8</th>
                                        <th data-field="tag9" data-editable="true">تگ 9</th>
                                        <th data-field="tag10" data-editable="true">تگ 10</th>
                                        <th data-field="tag11" data-editable="true">تگ 11</th>
                                        <th data-field="tag12" data-editable="true">تگ 12</th>
                                        <th data-field="tag13" data-editable="true">تگ 13</th>
                                        <th data-field="tag14" data-editable="true">تگ 14</th>
                                        <th data-field="tag15" data-editable="true">تگ 15</th>
                                        <th data-field="h1" data-editable="true">h1</th>
                                        <th data-field="keyword" data-editable="true">keyword</th>
                                        <th data-field="meta" data-editable="true">meta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td>{{($post->tag1)}}</td>
                                        <td>{{($post->tag2)}}</td>
                                        <td>{{($post->tag3)}}</td>
                                        <td>{{($post->tag4)}}</td>
                                        <td>{{($post->tag5)}}</td>
                                        <td>{{($post->tag6)}}</td>
                                        <td>{{($post->tag7)}}</td>
                                        <td>{{($post->tag8)}}</td>
                                        <td>{{($post->tag9)}}</td>
                                        <td>{{($post->tag10)}}</td>
                                        <td>{{($post->tag11)}}</td>
                                        <td>{{($post->tag12)}}</td>
                                        <td>{{($post->tag13)}}</td>
                                        <td>{{($post->tag14)}}</td>
                                        <td>{{($post->tag15)}}</td>
                                        <td>{{($post->h1)}}</td>
                                        <td>{{($post->keyword)}}</td>
                                        <td>{{($post->meta)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </center>
            </div>

            <center style="padding: 10px">
                <input type="submit" onclick="document.location.href = '{{route('createPostStep3', ['id' => $post->id])}}'" value="ذخیره" class="btn btn-success">
            </center>

        </div>
    </div>

    <div class="col-md-1"></div>

    <script>

        var whichTag;

        function handleClick(id, placeId, mode) {
            whichTag = mode;
        }

        function handleSubmitFormDataTable() {

            $.ajax({
                type: 'post',
                url: '{{route('editPostTag')}}',
                data: {
                    'id': '{{$post->id}}',
                    'whichTag': whichTag,
                    'val': $(".myDynamicInput").val()
                }
            });
        }

    </script>

@stop