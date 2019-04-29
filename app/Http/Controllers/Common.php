<?php

use Illuminate\Support\Facades\URL;

function persianNumber($i) {

    $arr = ['اول', 'دوم', 'سوم', 'چهارم', 'پنجم', 'ششم', 'هفتم', 'هشتم', 'نهم'];

    return $arr[$i - 1];
}

function getValueInfo($key) {

    $values = [
        "adminLevel" => 1, 'hotel' => 4, 'amaken' => 1, 'majara' => 6, 'adab' => 8, 'restaurant' => 3,
        'hotelMode' => 1, 'aparteman' => 2, 'mehmansara' => 3, 'vila' => 4, 'motel' => 5, 'tafrihi' => 6, 'pansion' => 7,
        'restaurantMode' => 1, 'fastfood' => 2,
        'bom' => 8
    ];

    return $values[$key];

}

function getMainPic($place, $idx, $folderName) {

    switch ($idx) {

        case 1:
            if ($place->pic_1) {
                if (file_exists((__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/s-1.jpg'))) {
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
                if (file_exists((__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/s-2.jpg'))) {
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
                if (file_exists((__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/s-3.jpg'))) {
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
                if (file_exists((__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/s-4.jpg'))) {
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
                if (file_exists((__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/s-5.jpg'))) {
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
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        return false;
    }
}

function uploadCheck($target_file, $name, $section, $limitSize, $ext) {
    $err = "";
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

    $check = getimagesize($_FILES[$name]["tmp_name"]);
    $uploadOk = 1;

    if($check === false) {
        $err .= "فایل ارسالی در قسمت " . $section . " معتبر نمی باشد";
        $uploadOk = 0;
    }


    if ($uploadOk == 1 && $_FILES[$name]["size"] > $limitSize) {
        $limitSize /= 1000000;
        $limitSize .= "MB";
        $err .=  "حداکثر حجم مجاز برای بارگذاری تصویر " .  " <span>" . $limitSize . " </span>" . "می باشد" . "<br />";
    }

    $imageFileType = strtolower($imageFileType);

    if($ext != -1 && $imageFileType != $ext)
        $err .= "شما تنها فایل های $ext را می توانید در این قسمت آپلود نمایید";
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

function sendSMS($destNum, $text, $template, $token2 = "") {

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
