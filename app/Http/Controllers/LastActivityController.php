<?php

namespace App\Http\Controllers;

use App\models\AdminLog;
use App\models\Post;
use App\models\User;
use Illuminate\Support\Facades\DB;

class LastActivityController extends Controller {

    public function lastActivities() {

        $logs = DB::connection('mysql2')->table('adminLog')->join('pro.users', 'uId', '=', 'users.id')->select('mode', 'adminLog.id', 'adminLog.created_at', 'adminLog.additional1', 'adminLog.additional2', 'adminLog.comment', 'pro.users.username')->orderBy('adminLog.created_at', 'DESC')->paginate(30);

        foreach ($logs as $log) {

            $log->date = convertDate($log->created_at);

            switch ($log->mode) {

                case getValueInfo('createPost'):
                    $log->mode = 'ایجاد پست';
                    $post = Post::whereId($log->additional1);
                    $log->additional1 = $post->title;
                    $log->redirect = route('editPost', ['id' => $post->id]);
                    break;

                case getValueInfo('editPost'):
                    $log->mode = 'ویرایش پست';
                    $post = Post::whereId($log->additional1);
                    $log->additional1 = $post->title;
                    $log->redirect = route('editPost', ['id' => $post->id]);
                    break;

                case getValueInfo('deletePost'):
                    $log->mode = 'حذف پست';
                    break;

                case getValueInfo('changePass'):
                    $log->mode = 'تغییر رمزعبور کاربران';
                    break;

                case getValueInfo('selfChangePass'):
                    $log->mode = 'تغییر رمزعبور خود';
                    break;

                case getValueInfo('changeNoFollow'):
                    $log->mode = 'تغییر مدیریت nofollow';
                    break;

                case getValueInfo('changeSeo'):
                    $log->mode = 'تغییر سئو صفحات';
                    break;

                case getValueInfo('changeUserContent'):
                    $log->mode = 'تغییر محتوای کاربر';
                    break;

                case getValueInfo('submitLog'):
                    $log->mode = 'تایید محتوای کاربر';
                    break;

                case getValueInfo('deleteLog'):
                    $log->mode = 'حذف محتوای کاربر';
                    break;

                case getValueInfo('unSubmitLog'):
                    $log->mode = 'رد کردن محتوای کاربر';
                    break;

                case getValueInfo('removeBackup'):
                    $log->mode = 'حذف پلن بک آپ';
                    break;

                case getValueInfo('addBackup'):
                    $log->mode = 'افزودن پلن جدید برای بک آپ';
                    break;

                case getValueInfo('manualBackup'):
                    $log->mode = 'گرفتن بک آپ دستی از دیتابیس';
                    break;

                case getValueInfo('imageBackup'):
                    $log->mode = 'گرفتن بک آپ دستی از تصاویر';
                    break;

                case getValueInfo('removeMainPic'):
                    $log->mode = 'حذف تصویر سیستمی محتوا';
                    break;

                case getValueInfo('login'):
                    $log->mode = 'ورود';
                    break;

                case getValueInfo('determineRadius'):
                    $log->mode = 'تعیین شعاع نزدیکی';
                    break;

                case getValueInfo('offCode'):
                    $log->mode = "ساخت کد تخفیف";
                    $log->additional1 = "تعداد ساخت " . $log->additional1;
                    $log->additional2 = "نوع کد " . ($log->additional2 == getValueInfo('staticOffer') ? 'مقداری' : 'درصدی');
                    break;

                case getValueInfo('mail'):
                    $log->mode = 'ارسال ایمیل';
                    break;

                case getValueInfo('messageBox'):
                    $log->mode = 'ارسال پیام به صندوق داخلی';
                    $log->additional1 = "کاربر مقصد " . User::whereId($log->additional1)->username;
                    break;

                case getValueInfo('phone'):
                    $log->mode = 'ارسال پیام به شماره همراه';
                    break;

            }

        }

        return view('report.lastActivity', ['logs' => $logs]);
    }


    public function deleteLogs() {

        if(isset($_POST["logs"])) {

            $logs = $_POST["logs"];

            try {
                DB::transaction(function () use ($logs) {

                    foreach ($logs as $log) {
                        AdminLog::destroy(makeValidInput($log));
                    }

                    echo "ok";
                });
            }
            catch (\Exception $x) {}
        }
    }

}
