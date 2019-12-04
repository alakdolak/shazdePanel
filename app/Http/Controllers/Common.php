<?php

use App\models\Adab;
use App\models\Amaken;
use App\models\Hotel;
use App\models\Majara;
use App\models\Restaurant;
use Illuminate\Support\Facades\URL;
use PHPMailer\PHPMailer\PHPMailer;

function getPostCategories() {

    return [
        [
            'super' => "اماکن گردشگری",
            'childs' => [
                ['id' => 1, 'key' => 'اماکن تاریخی'],
                ['id' => 2, 'key' => 'اماکن مذهبی'],
                ['id' => 3, 'key' => 'اماکن تفریحی'],
                ['id' => 4, 'key' => 'طبیعت گردی'],
                ['id' => 5, 'key' => 'مراکز خرید'],
                ['id' => 6, 'key' => 'موزه ها']
            ]
        ],
        [
            'super' => "هتل و رستوران",
            "childs" => [
                ['id' => 7, 'key' => 'هتل'],
                ['id' => 8, 'key' => 'رستوران'],
            ]
        ],
        [
            'super' => "حمل و نقل",
            'childs' => [
                ['id' => 9, 'key' => 'هواپیما'],
                ['id' => 10, 'key' => 'اتوبوس'],
                ['id' => 11, 'key' => 'سواری'],
                ['id' => 12, 'key' => 'فطار']
            ]
        ],
        [
            'super' => "آداب و رسوم",
            "childs" => [
                ['id' => 13, 'key' => 'سوغات محلی'],
                ['id' => 14, 'key' => 'صنایع دستی'],
                ['id' => 15, 'key' => 'اماکن تفریحی'],
                ['id' => 16, 'key' => 'غذای محلی'],
                ['id' => 17, 'key' => 'لباس محلی'],
                ['id' => 18, 'key' => 'گویش محلی'],
                ['id' => 19, 'key' => 'اصطلاحات محلی'],
            ]
        ],
        [
            'super' => "جشنواره و آیین",
            "childs" => [
                ['id' => 20, 'key' => 'رسوم محلی'],
                ['id' => 21, 'key' => 'جشنواره'],
                ['id' => 22, 'key' => 'تور'],
                ['id' => 23, 'key' => 'کنسرت']
            ]
        ]
    ];
}

function getMessages() {
    return [

        'firstName.required' => 'لطفا نام خود را وارد نمایید',
        'lastName.required' => 'لطفا نام خانوادگی خود را وارد نمایید',
        'email.required' => 'لطفا ایمیل مورد نظر خود را وارد نمایید',
        'phone.required' => 'لطفا شماره همراه خود را وارد نمایید',
        'username.required' => 'لطفا نام کاربری مورد نظر خود را وارد نمایید',
        'password.require' => 'لطفا رمزعبور خود را وارد نمایید',
        'confirm_password.required' => 'لطفا تکرار رمزعبور خود را وارد نمایید',
        'cityId.required' => 'لطفا شهر را مشخص نمایید',

        'username.unique' => 'نام کاربری مورد نظر در سامانه موجود است',
        'phone.unique' => 'شماره همراه مورد نظر در سامانه موجود است',
        'email.unique' => 'ایمیل مورد نظر در سامانه موجود است',

        'username.min' => 'حداقل طول مورد نیاز برای نام کاربری 8 می باشد',
        'password.min' => 'حداقل طول مورد نیاز برای رمزعبور 8 می باشد',
        'phone.min' => 'شماره همراه مورد نظر معتبر نمی باشد',
        'email.min' => 'ایمیل مورد نظر معتبر نمی باشد',
    ];
}

function persianNumber($i) {

    $arr = ['اول', 'دوم', 'سوم', 'چهارم', 'پنجم', 'ششم', 'هفتم', 'هشتم', 'نهم'];

    return $arr[$i - 1];
}

function getValueInfo($key) {

    $values = [
        "superAdminLevel" => 1, 'adminLevel' => 2, 'userLevel' => 0,
        'hotel' => 4, 'amaken' => 1, 'majara' => 6, 'adab' => 8, 'restaurant' => 3,
        'hotelMode' => 1, 'aparteman' => 2, 'mehmansara' => 3, 'vila' => 4, 'motel' => 5, 'tafrihi' => 6, 'pansion' => 7,
        'restaurantMode' => 1, 'fastfood' => 2,
        'bom' => 8,
        'ghaza' => 3, 'soghat' => 1, 'sanaye' => 6,
        '5_min' => 1, '10_min' => 2, '15_min' => 3, '30_min' => 4, 'hour' => 5, 'day' => 6, 'week' => 7, 'month' => 8,
        
        
        'editPost' => 1, 'deletePost' => 2, 'createPost' => 3, 'changePass' => 4, 'changeNoFollow' => 5, 'changeSeo' => 6,
        'changeUserContent' => 7, 'submitLog' => 8, 'deleteLog' => 9, 'unSubmitLog' => 10, 'removeBackup' => 11,
        'addBackup' => 12, 'manualBackup' => 13, 'imageBackup' => 14, 'removeMainPic' => 15, 'login' => 16, 'determineRadius' => 17,
        'selfChangePass' => 18, 'mail' => 19, 'phone' => 20, 'messageBox' => 21, 'offCode' => 22, 'submitPost' => 23,
        'unSubmitPost' => 24,


        'hotel-detail' => 1, 'adab-detail' => 2, 'amaken-detail' => 3, 'majara-detail' => 4, 'restaurant-detail' => 5,
        'hotel-list' => 6, 'adab-list' => 7, 'amaken-list' => 8, 'majara-list' => 9, 'restaurant-list' => 10,
        'main_page' => 11,

        'staticOffer' => 1, 'dynamicOffer' => 2
    ];

    return $values[$key];

}

function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

function recurse_copy($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function getPlaceAndFolderName($kindPlaceId, $placeId) {

    switch ($kindPlaceId) {
        case getValueInfo('hotel'):
        default:
            $place = Hotel::whereId($placeId);
            $folderName = "hotels";
            break;
        case getValueInfo('amaken'):
            $place = Amaken::whereId($placeId);
            $folderName = "amaken";
            break;
        case getValueInfo('restaurant'):
            $place = Restaurant::whereId($placeId);
            $folderName = "restaurant";
            break;
        case getValueInfo('majara'):
            $place = Majara::whereId($placeId);
            $folderName = "majara";
            break;
        case getValueInfo('adab'):
            $place = Adab::whereId($placeId);
            $folderName = "adab/";

            if($place != null) {

                switch ($place->category) {
                    case getValueInfo('ghaza'):
                        $folderName .= 'ghazamahali';
                        break;
                    default:
                        $folderName .= 'soghat';
                        break;
                }
            }
            break;
    }
    
    return [$place, $folderName];

}

function getFolderName($kindPlaceId) {

    $folderName = "tmp";

    switch ($kindPlaceId) {

        case getValueInfo('hotel'):
            $folderName = "hotels";
            break;
        case getValueInfo('majara'):
            $folderName = "majara";
            break;
        case getValueInfo('amaken'):
            $folderName = "amaken";
            break;
        case getValueInfo('restaurant'):
            $folderName = "restaurant";
            break;
    }

    return $folderName;

}

function getMainPic($place, $idx, $folderName) {

    switch ($idx) {

        case 1:
            if ($place->pic_1) {
                if (file_exists((__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/s-1.jpg'))) {
                    return [
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/s-1.jpg',
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/l-1.jpg',
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/t-1.jpg',
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/f-1.jpg'
                    ];
                }

                return [
                    URL::asset('_images/nopic/blank.jpg'),
                    URL::asset('_images/nopic/blank.jpg'),
                    URL::asset('_images/nopic/blank.jpg'),
                    URL::asset('_images/nopic/blank.jpg')
                ];

            }
            return [
                URL::asset('_images/nopic/blank.jpg'),
                URL::asset('_images/nopic/blank.jpg'),
                URL::asset('_images/nopic/blank.jpg'),
                URL::asset('_images/nopic/blank.jpg')
            ];

        case 2:
            if ($place->pic_2) {
                if (file_exists((__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/s-2.jpg'))) {
                    return [
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/s-2.jpg',
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/l-2.jpg',
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/t-2.jpg',
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/f-2.jpg'
                    ];
                }

                return [
                    URL::asset('_images/nopic/blank.jpg'),
                    URL::asset('_images/nopic/blank.jpg'),
                    URL::asset('_images/nopic/blank.jpg'),
                    URL::asset('_images/nopic/blank.jpg')
                ];

            }
            return [
                URL::asset('_images/nopic/blank.jpg'),
                URL::asset('_images/nopic/blank.jpg'),
                URL::asset('_images/nopic/blank.jpg'),
                URL::asset('_images/nopic/blank.jpg')
            ];

        case 3:
            if ($place->pic_3) {
                if (file_exists((__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/s-3.jpg'))) {
                    return [
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/s-3.jpg',
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/l-3.jpg',
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/t-3.jpg',
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/f-3.jpg'
                    ];
                }

                return [
                    URL::asset('_images/nopic/blank.jpg'),
                    URL::asset('_images/nopic/blank.jpg'),
                    URL::asset('_images/nopic/blank.jpg'),
                    URL::asset('_images/nopic/blank.jpg')
                ];

            }
            return [
                URL::asset('_images/nopic/blank.jpg'),
                URL::asset('_images/nopic/blank.jpg'),
                URL::asset('_images/nopic/blank.jpg'),
                URL::asset('_images/nopic/blank.jpg')
            ];

        case 4:
            if ($place->pic_4) {
                if (file_exists((__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/s-4.jpg'))) {
                    return [
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/s-4.jpg',
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/l-4.jpg',
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/t-4.jpg',
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/f-4.jpg'
                    ];
                }

                return [
                    URL::asset('_images/nopic/blank.jpg'),
                    URL::asset('_images/nopic/blank.jpg'),
                    URL::asset('_images/nopic/blank.jpg'),
                    URL::asset('_images/nopic/blank.jpg')
                ];

            }
            return [
                URL::asset('_images/nopic/blank.jpg'),
                URL::asset('_images/nopic/blank.jpg'),
                URL::asset('_images/nopic/blank.jpg'),
                URL::asset('_images/nopic/blank.jpg')
            ];

        case 5:
            if ($place->pic_5) {
                if (file_exists((__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/s-5.jpg'))) {
                    return [
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/s-5.jpg',
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/l-5.jpg',
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/t-5.jpg',
                        URL::asset('_images') . '/' . $folderName . '/' . $place->file . '/f-5.jpg'
                    ];
                }

                return [
                    URL::asset('_images/nopic/blank.jpg'),
                    URL::asset('_images/nopic/blank.jpg'),
                    URL::asset('_images/nopic/blank.jpg'),
                    URL::asset('_images/nopic/blank.jpg')
                ];

            }
            return [
                URL::asset('_images/nopic/blank.jpg'),
                URL::asset('_images/nopic/blank.jpg'),
                URL::asset('_images/nopic/blank.jpg'),
                URL::asset('_images/nopic/blank.jpg')
            ];
    }
}

function jalaliToGregorian($time){
    include_once 'jdate.php';

    $date = explode('/', $time);
    $date = jalali_to_gregorian($date[0], $date[1], $date[2]);
    return $date;
}

function getPast($past) {

    include_once 'jdate.php';

    $jalali_date = jdate("c", $past);

    $date_time = explode('-', $jalali_date);

    $subStr = explode('/', $date_time[0]);

    $day = $subStr[0] . $subStr[1] . $subStr[2];

    $time = explode(':', $date_time[1]);

    $time = $time[0] . $time[1];

    return ["date" => $day, "time" => $time];
}

function _custom_check_national_code($code) {

    if(!preg_match('/^[0-9]{10}$/',$code))
        return false;

    for($i=0;$i<10;$i++)
        if(preg_match('/^'.$i.'{10}$/',$code))
            return false;
    for($i=0,$sum=0;$i<9;$i++)
        $sum+=((10-$i)*intval(substr($code, $i,1)));
    $ret=$sum%11;
    $parity=intval(substr($code, 9,1));
    if(($ret<2 && $ret==$parity) || ($ret>=2 && $ret==11-$parity))
        return true;
    return false;
}

function makeValidInput($input) {
    $input = addslashes($input);
    $input = trim($input);
    if(get_magic_quotes_gpc())
        $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

function sendMail($text, $recipient, $subject) {


    require_once __DIR__ . '/../../../vendor/autoload.php';
    
    $mail = new PHPMailer(true);                           // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->CharSet = "UTF-8";
        //Recipients
        $mail->setFrom('info@shazdemosafer.com', 'Mailer');
//    $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
        $mail->addAddress($recipient);               // Name is optional
//        $mail->addReplyTo('ghane@shazdemosafer.com', 'Information');
//    $mail->addCC('cc@example.com');
//    $mail->addBCC('bcc@example.com');

        //Attachments
//    $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $text;
        $mail->AltBody = $text;

        $mail->send();
        return true;
    } catch (Exception $e) {
//        echo 'Message could not be sent.';
//        echo 'Mailer Error: ' . $mail->ErrorInfo;
        return false;
    }
}

function uploadCheck($target_file, $name, $section, $limitSize, $ext, $ext2 = "") {

    $err = "";
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    if ($_FILES[$name]["size"] > $limitSize) {
        $limitSize /= 1000000;
        $limitSize .= "MB";
        $err .=  "حداکثر حجم مجاز برای بارگذاری تصویر " .  " <span>" . $limitSize . " </span>" . "می باشد" . "<br />";
    }

    $imageFileType = strtolower($imageFileType);

    if(!empty($ext2)) {
        if ($ext != -1 && $imageFileType != $ext && $imageFileType != $ext2)
            $err .= " شما تنها فایل های $ext را می توانید در این قسمت آپلود نمایید";
    }
    else {
        if ($ext != -1 && $imageFileType != $ext)
            $err .= " شما تنها فایل های $ext را می توانید در این قسمت آپلود نمایید";
    }
    return $err;
}

function upload($target_file, $name, $section) {

    try {
        move_uploaded_file($_FILES[$name]["tmp_name"], $target_file);
    }
    catch (Exception $x) {
        return "اشکالی در آپلود تصویر در قسمت " . $section . " به وجود آمده است" . "<br />";
    }
    return "";
}

function formatDate($date) {
    return $date[0] . $date[1] . $date[2] . $date[3] . '/'
    . $date[4] . $date[5] . '/' . $date[6] . $date[7];
}

function convertDate($created) {

    include_once 'jdate.php';

    if(count(explode(' ', $created)) == 2)
        $created = explode('-', explode(' ', $created)[0]);
    else
        $created = explode('-', $created);

    $created = gregorian_to_jalali($created[0], $created[1], $created[2]);
    return $created[0] . '/' . $created[1] . '/' . $created[2];
}

function getToday() {

    include_once 'jdate.php';

    $jalali_date = jdate("c");

    $date_time = explode('-', $jalali_date);

    $subStr = explode('/', $date_time[0]);

    $day = $subStr[0] . $subStr[1] . $subStr[2];

    $time = explode(':', $date_time[1]);

    $time = $time[0] . $time[1];

    return ["date" => $day, "time" => $time];
}

function getCurrentYear() {

    include_once 'jdate.php';

    $jalali_date = jdate("c");

    $date_time = explode('-', $jalali_date);

    $subStr = explode('/', $date_time[0]);

    return $subStr[0];
}

function convertDateToString($date) {
    $subStrD = explode('/', $date);
    if(count($subStrD) == 1)
        $subStrD = explode(',', $date);

    if(strlen($subStrD[1]) == 1)
        $subStrD[1] = "0" . $subStrD[1];

    if(strlen($subStrD[2]) == 1)
        $subStrD[2] = "0" . $subStrD[2];

    return $subStrD[0] . $subStrD[1] . $subStrD[2];
}

function convertTimeToString($time) {
    $subStrT = explode(':', $time);
    return $subStrT[0] . $subStrT[1];
}

function convertStringToTime($time) {
    return $time[0] . $time[1] . ":" . $time[2] . $time[3];
}

function convertStringToDate($date, $spliter = '/') {
    if($date == "")
        return $date;
    return $date[0] . $date[1] . $date[2] . $date[3] . $spliter . $date[4] . $date[5] . $spliter . $date[6] . $date[7];
}

function sendSMS($destNum, $text, $template, $token2 = "", $token3 = "") {

    require_once __DIR__ . '/../../../vendor/autoload.php';

    try{
        $api = new \Kavenegar\KavenegarApi("4836666C696247676762504666386A336846366163773D3D");
        $result = $api->VerifyLookup($destNum, $text, $token2, '', $template);
        if($result){
            foreach($result as $r){
                return $r->messageid;
            }
        }
    }
    catch(\Kavenegar\Exceptions\ApiException $e){
        // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
        echo $e->errorMessage();
        return -1;
    }
    catch(\Kavenegar\Exceptions\HttpException $e){
        // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
        echo $e->errorMessage();
        return -1;
    }
}

function compressImage($source, $destination, $quality){
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);
    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);
}

function deletePlacePicFiles($location, $picNumber){
    $locationF = $location . '/f-' . $picNumber;
    $locationS = $location .  '/s-' . $picNumber;
    $locationL = $location .  '/l-' . $picNumber;
    $locationT = $location .  '/t-' . $picNumber ;
    $locationMain = $location .  '/' . $picNumber ;

    if (file_exists($locationF))
        unlink($locationF);
    if (file_exists($locationS))
        unlink($locationS);
    if (file_exists($locationL))
        unlink($locationL);
    if (file_exists($locationT))
        unlink($locationT);
    if (file_exists($locationMain))
        unlink($locationMain);

    return true;
}
