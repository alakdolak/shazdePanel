
@extends('layouts.structure')

@section('header')
    @parent
    <link rel="stylesheet" href="{{URL::asset('css/cropper/cropper.css')}}">

    <script src="{{URL::asset('js/cropper/cropper.js')}}"></script>
@stop

@section('content')

    <div class="data-table-area mg-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline13-list shadow-reset">
                        <div class="sparkline13-hd">
                            <div style="direction: rtl" class="main-sparkline13-hd">
                                <div class="sparkline13-outline-icon">
                                </div>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="container-fluid">
                                <div class="row">

                                    <div class="container">
                                        <input type="file" id="input" name="image" accept="image/*">

                                        <button>
                                            اپلود
                                        </button>
                                        <img class="rounded" id="avatar" src="" alt="avatar">

                                        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel">Crop the image</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="img-container">
                                                            <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-primary" id="crop">Crop</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', function () {
            var avatar = document.getElementById('avatar');
            var image = document.getElementById('image');
            var input = document.getElementById('input');
            var $modal = $('#modal');
            var cropper;

            input.addEventListener('change', function (e) {
                var files = e.target.files;
                var done = function (url) {
                    input.value = '';
                    image.src = url;
                    $modal.modal('show');
                };
                var reader;
                var file;
                var url;

                if (files && files.length > 0) {
                    file = files[0];

                    if (URL) {
                        console.log(URL.createObjectURL(file));
                        done(URL.createObjectURL(file));
                    } else if (FileReader) {
                        reader = new FileReader();
                        reader.onload = function (e) {
                            done(reader.result);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });

            $modal.on('shown.bs.modal', function () {
                cropper = new Cropper(image, {
                    aspectRatio: 3/2,
                    viewMode: 4,
                });
            }).on('hidden.bs.modal', function () {
                cropper.destroy();
                cropper = null;
            });

            document.getElementById('crop').addEventListener('click', function () {
                var initialAvatarURL1;
                var initialAvatarURL2;
                var canvas1;
                var canvas2;

                $modal.modal('hide');

                if (cropper) {
                    // canvas1 = cropper.getCroppedCanvas({
                    //     width: 250,
                    //     height: 167,
                    // });
                    canvas1 = cropper.getCroppedCanvas();
                    
                    initialAvatarURL1 = avatar.src;
                    avatar.src = canvas1.toDataURL();

                    canvas1.toBlob(function (blob) {
                        console.log(blob)
                        var formData = new FormData();

                        formData.append('mainPic', blob, 'f-1.jpg');

                        console.log(formData);
                        $.ajax('{{route("getCrop")}}', {
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,

                            // xhr: function () {
                            //     var xhr = new XMLHttpRequest();
                            //
                            //     xhr.upload.onprogress = function (e) {
                            //         var percent = '0';
                            //         var percentage = '0%';
                            //
                            //         if (e.lengthComputable) {
                            //             percent = Math.round((e.loaded / e.total) * 100);
                            //             percentage = percent + '%';
                            //             $progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
                            //         }
                            //     };
                            //
                            //     return xhr;
                            // },

                            success: function () {
                                // $alert.show().addClass('alert-success').text('Upload success');
                            },

                            error: function () {
                                avatar.src = initialAvatarURL;
                                // $alert.show().addClass('alert-warning').text('Upload error');
                            },

                            complete: function () {
                                $progress.hide();
                            },
                        });
                    });
                }
            });
        });
    </script>


@stop
