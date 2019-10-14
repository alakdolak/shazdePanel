@extends('layouts.structure')

@section('header')
    @parent

@stop

@section('content')

    <div class="col-md-1"></div>

    <div class="col-md-10">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>نتایج بررسی</h1>
                </div>
            </div>

            <div style="height: auto !important;" class="sparkline8-graph dashone-comment  dashtwo-messages">
                <center class="row">
                    @foreach($results as $key => $value)
                        <div class="col-xs-12" style="margin-top: 20px">
                            <h1>{{$key}}</h1>
                            <table class="table table-bordered table-adminpro">
                                <thead>
                                <tr>
                                    <th class="code-adminpro-center">key</th>
                                    <th>value</th>
                                </tr>
                                </thead>

                                @if($key == "wordDensity")
                                    <tr>
                                        <td>
                                            تعداد کل کلمات
                                        </td>
                                        <td>{{$totalWord}}</td>
                                    </tr>
                                @endif

                                <tbody>
                                    @foreach($value as $key2 => $value2)

                                        @if(is_array($value2))
                                            <tr>
                                                <td>
                                                    <center>
                                                        {{$key2}}
                                                    </center>
                                                </td>
                                                <td></td>
                                            </tr>

                                            @foreach($value2 as $key3 => $value3)

                                                @if(is_array($value3))
                                                    <tr>
                                                        <td>
                                                            <center>
                                                                {{$key3}}
                                                            </center>
                                                        </td>
                                                        <td></td>
                                                    </tr>

                                                    @foreach($value3 as $key4 => $value4)

                                                        @if(is_array($value4))

                                                        @else
                                                            <tr>
                                                                <td>{{$key4}}</td>
                                                                <td>{{$value4}}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach

                                                @else
                                                    @if($key3 != 'html')
                                                        <tr>
                                                            <td>{{$key3}}</td>
                                                            <td>{{$value3}}</td>
                                                        </tr>
                                                    @endif
                                                @endif

                                            @endforeach
                                        @else
                                            <tr>
                                                <td>{{$key2}}</td>
                                                <td>{{($key == "wordDensity") ? $value2 . '  -  ' . round($value2 * 100 / $totalWord, 2) . '%': $value2}}</td>
                                            </tr>
                                        @endif

                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    @endforeach
                </center>
            </div>
        </div>
    </div>

    <div class="col-md-1"></div>

@stop