<?php

namespace App\Http\Controllers;

use App\models\AdminLog;
use App\models\Backup;
use App\models\ImageBackUp;
use FilesystemIterator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use mysqli;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class BackupController extends Controller {

    public function backup() {

        $backups = Backup::all();

        foreach ($backups as $backup) {

            switch ($backup->_interval_) {
                case getValueInfo('5_min'):
                    $backup->interval = "هر 5 دقیقه";
                    break;
                case getValueInfo('10_min'):
                    $backup->interval = "هر 10 دقیقه";
                    break;
                case getValueInfo('15_min'):
                    $backup->interval = "هر 15 دقیقه";
                    break;
                case getValueInfo('30_min'):
                    $backup->interval = "هر 30 دقیقه";
                    break;
                case getValueInfo('hour'):
                    $backup->interval = "هر ساعت";
                    break;
                case getValueInfo('day'):
                    $backup->interval = "هر روز";
                    break;
                case getValueInfo('week'):
                    $backup->interval = "هر هفته";
                    break;
                case getValueInfo('month'):
                    $backup->interval = "هر ماه";
                    break;
            }

            if(empty($backup->url))
                $backup->kind = "روی هارد دیسک";
            else {
                $backup->kind = "روی هاست دیگر";

                if(empty($backup->username))
                    $backup->username = "دیفالت";

                if(empty($backup->password))
                    $backup->password = "دیفالت";
            }
        }

        $intervals = [
            ["id" => getValueInfo('5_min'), 'name' =>  'هر 5 دقیقه'],
            ["id" => getValueInfo('10_min'), 'name' =>  'هر 10 دقیقه'],
            ["id" => getValueInfo('15_min'), 'name' =>  'هر 15 دقیقه'],
            ["id" => getValueInfo('30_min'), 'name' =>  'هر 30 دقیقه'],
            ["id" => getValueInfo('hour'), 'name' =>  'هر ساعت'],
            ["id" => getValueInfo('day'), 'name' =>  'هر روز'],
            ["id" => getValueInfo('week'), 'name' =>  'هر هفته'],
            ["id" => getValueInfo('month'), 'name' =>  'هر ماه']
        ];

        return view('config.backup', ['backups' => $backups, 'intervals' => $intervals]);
    }

    public function removeBackup() {

        if(isset($_POST["id"])) {

            try {
                Backup::destroy(makeValidInput($_POST["id"]));

                $tmp = new AdminLog();
                $tmp->uId = Auth::user()->id;
                $tmp->mode = getValueInfo('removeBackup');
                $tmp->additional1 = makeValidInput($_POST["id"]);
                $tmp->save();

            }
            catch (\Exception $x) {}

        }

    }

    public function addBackup() {

        if(isset($_POST["url"]) && isset($_POST['interval']) && isset($_POST["username"]) && isset($_POST["password"])) {

            $backup = new Backup();
            $backup->_interval_ = makeValidInput($_POST["interval"]);
            $backup->username = makeValidInput($_POST["username"]);
            $backup->password = makeValidInput($_POST["password"]);
            $backup->url = makeValidInput($_POST["url"]);

            try {
                $backup->save();
                $tmp = new AdminLog();
                $tmp->uId = Auth::user()->id;
                $tmp->mode = getValueInfo('addBackup');
                $tmp->additional1 = $backup->id;
                $tmp->save();
            }
            catch (\Exception $x) {

            }


        }

        return Redirect::route('backup');
    }

    private function Export_Database($host,$user,$pass,$name, $tables=false) {

        $mysqli = new mysqli($host, $user, $pass, $name);
        $mysqli->select_db($name);
        $mysqli->query("SET NAMES 'utf8'");

        $queryTables = $mysqli->query('SHOW TABLES');

        while($row = $queryTables->fetch_row())
            $target_tables[] = $row[0];

        if($tables !== false)
            $target_tables = array_intersect( $target_tables, $tables);

        foreach($target_tables as $table) {
            $result         =   $mysqli->query('SELECT * FROM '.$table);
            $fields_amount  =   $result->field_count;
            $rows_num=$mysqli->affected_rows;
            $res            =   $mysqli->query('SHOW CREATE TABLE '.$table);
            $TableMLine     =   $res->fetch_row();
            $content        = (!isset($content) ?  '' : $content) . "\n\n".$TableMLine[1].";\n\n";

            for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0)
            {
                while($row = $result->fetch_row())
                { //when started (and every after 100 command cycle):
                    if ($st_counter%100 == 0 || $st_counter == 0 )
                    {
                        $content .= "\nINSERT INTO ".$table." VALUES";
                    }
                    $content .= "\n(";
                    for($j=0; $j<$fields_amount; $j++)
                    {
                        $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) );
                        if (isset($row[$j]))
                        {
                            $content .= '"'.$row[$j].'"' ;
                        }
                        else
                        {
                            $content .= '""';
                        }
                        if ($j<($fields_amount-1))
                        {
                            $content.= ',';
                        }
                    }
                    $content .=")";
                    //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                    if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num)
                    {
                        $content .= ";";
                    }
                    else
                    {
                        $content .= ",";
                    }
                    $st_counter=$st_counter+1;
                }
            } $content .="\n\n\n";
        }

        //$backup_name = $backup_name ? $backup_name : $name."___(".date('H-i-s')."_".date('d-m-Y').")__rand".rand(1,11111111).".sql";

        return $content;
    }

    public function autoBackup($id) {

        $db = Backup::whereId($id);
        if(empty($db)) {
            Storage::delete('backup:all ' . $id);
            return;
        }

        $url = $db->url;
        $username = $db->username;
        $password = $db->password;

        $mysqlUserName      = "administrator_persoulio";
        $mysqlPassword      = "yGrn65~6";
        $mysqlHostName      = "127.0.0.1";
        $DbName             = "pro";
        $tables             = false;

        $content = $this->Export_Database($mysqlHostName, $mysqlUserName, $mysqlPassword, $DbName,  $tables);

        $mysqlUserName      = "administrator_persoulio";
        $mysqlPassword      = "yGrn65~6";
        $mysqlHostName      = "127.0.0.1";
        $DbName             = "panel_shazde";
        $tables             = false;

        $content .= $this->Export_Database($mysqlHostName, $mysqlUserName, $mysqlPassword, $DbName,  $tables);

        if(empty($url)) {
            $backup_name = time() . "mybackup_panel.sql";
            $backup_name = __DIR__ . '/../../../../assets/backups/' . $backup_name;
            $fp = fopen($backup_name, 'w');
            fwrite($fp, $content);
            fclose($fp);
        }

        else {

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_BUFFERSIZE, 84000); // curl buffer size in bytes

            $postData = array(
                'content' => $content,
                'username' => (empty($username)) ? 'shazde_panel' : $username,
                'password' => (empty($password)) ? '!!Mg22743823!!' : $password
            );

            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_close ($ch);
        }
        exit();
    }

    public function manualBackup() {

//        $mysqlUserName      = env("DB_USERNAME");
//        $mysqlPassword      = env("yGrn65~6");
//        $mysqlHostName      = env("DB_HOST");
//        $DbName             = env("DB_DATABASE");

        $mysqlUserName      = "administrator_persoulio";
        $mysqlPassword      = "yGrn65~6";
        $mysqlHostName      = "127.0.0.1";
        $DbName             = "pro";
        $tables             = false;

        $tmp = new AdminLog();
        $tmp->uId = Auth::user()->id;
        $tmp->mode = getValueInfo('manualBackUp');
        $tmp->save();

        $content = $this->Export_Database($mysqlHostName, $mysqlUserName, $mysqlPassword, $DbName,  $tables);

        $mysqlUserName      = "administrator_persoulio";
        $mysqlPassword      = "yGrn65~6";
        $mysqlHostName      = "127.0.0.1";
        $DbName             = "panel_shazde";
        $tables             = false;

        $content .= $this->Export_Database($mysqlHostName, $mysqlUserName, $mysqlPassword, $DbName,  $tables);

        $backup_name = "mybackup_panel.sql";
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"".$backup_name."\"");

        echo $content;
        exit();
    }

    function scan_dir($path){

        $ite = new RecursiveDirectoryIterator($path);
        $nbfiles = 0;

        foreach (new RecursiveIteratorIterator($ite) as $filename => $cur) {
            if($cur->isFile())
                $nbfiles++;
        }

        return $nbfiles;
    }

    public function initialImageBackUp() {

        $DelFilePath = "images.zip";
        $DelFilePath = __DIR__ ."/../../../../imageBackUps/". $DelFilePath;

        if(file_exists($DelFilePath))
            unlink ($DelFilePath);

        $oldFiles = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(__DIR__ ."/../../../../imageBackUps"),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($oldFiles as $name => $file) {
            if($file->isFile())
                unlink($file);
        }

        $rootPath1 = __DIR__ ."/../../../../ads";
        $rootPath2 = __DIR__ ."/../../../../_images";
        $rootPath3 = __DIR__ ."/../../../../defaultPic";
        $rootPath4 = __DIR__ ."/../../../../badges";
        $rootPath5 = __DIR__ ."/../../../../userPhoto";
        $rootPath6 = __DIR__ ."/../../../../userProfile";

        $total = 0;

        $total += $this->scan_dir($rootPath1);
        $total += $this->scan_dir($rootPath2);
        $total += $this->scan_dir($rootPath3);
        $total += $this->scan_dir($rootPath4);
        $total += $this->scan_dir($rootPath5);
        $total += $this->scan_dir($rootPath6);

        DB::connection('mysql2')->delete('delete from imageBackUp WHERE 1');

        $imageBackUp = new ImageBackUp();
        $imageBackUp->total = $total;
        $imageBackUp->done = 0;
        $imageBackUp->save();
    }

    public function getDonePercentage() {

        $tmp = ImageBackUp::first();
        if($tmp == null) {
            echo 0;
            return;
        }

        $out = json_encode(['percent' => round($tmp->done * 100 / $tmp->total, 2), 'url' => ($tmp->flag == 0) ? '' : route('getImageBackup', ['idx' => $tmp->flag])]);
        if($tmp->flag != 0) {
            $tmp->flag = 0;
            $tmp->save();
        }

        echo $out;
    }

    public function imageBackup() {

        $tmp = new AdminLog();
        $tmp->uId = Auth::user()->id;
        $tmp->mode = getValueInfo('imageBackup');
        $tmp->save();

        $root = __DIR__ ."/../../../../imageBackUps/";
        $chunkNo = 1;
        $fileName = "images.zip";

        $zip = new ZipArchive();
        if ($zip->open($root . $chunkNo . $fileName, ZipArchive::CREATE) != TRUE) {
            die ("Could not open archive");
        }

        $rootPath = [
            __DIR__ ."/../../../../ads",
            __DIR__ ."/../../../../_images",
            __DIR__ ."/../../../../defaultPic",
            __DIR__ ."/../../../../badges",
            __DIR__ ."/../../../../userPhoto",
            __DIR__ ."/../../../../userProfile"
        ];

        $relativePathes = [
            "ads",
            "_images",
            "defaultPic",
            "badges",
            "userPhoto",
            "userProfile"
        ];

        $files = [];

        for ($i = 0; $i < count($rootPath); $i++) {
            $files[$i] = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath[$i]),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
        }

        $old_counter = 0;
        $curr_counter = 0;
        $old_old_counter = 0;

        for ($i = 0; $i < count($rootPath); $i++) {

            foreach ($files[$i] as $name => $file)  {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $filePathTmp = explode('\\', $filePath);

                    $start = false;
                    $relativePath = "";
                    for($j = 0; $j < count($filePathTmp); $j++) {
                        if(!$start && $filePathTmp[$j] != $relativePathes[$i])
                            continue;
                        $start = true;
                        $relativePath .= $filePathTmp[$j] . '/';
                    }

                    $relativePath = substr($relativePath, 0, strlen($relativePath) - 1);

                    $curr_counter++;
                    if($old_counter + 100 < $curr_counter) {
                        $old_counter = $curr_counter;
                        $tmp = ImageBackUp::first();
                        $tmp->done = $curr_counter;
                        $tmp->save();
                    }
                    if($old_old_counter + 4000 < $curr_counter) {
                        $old_old_counter = $curr_counter;
                        $zip->close();

                        $tmp = ImageBackUp::first();
                        $tmp->flag = $chunkNo;
                        $tmp->save();

                        $chunkNo++;

                        $zip = new ZipArchive();
                        if ($zip->open($root . $chunkNo . $fileName, ZipArchive::CREATE) != TRUE) {
                            die ("Could not open archive");
                        }

                    }

                    $zip->addFile($filePath, $relativePath);
                }
            }

        }

        $tmp = ImageBackUp::first();
        $tmp->flag = $chunkNo;
        $tmp->done = $curr_counter;
        $tmp->save();

        $zip->close();
    }

    public function getImageBackup($idx) {

        $DelFilePath = __DIR__ ."/../../../../imageBackUps/" . $idx . "images.zip";

        if (file_exists($DelFilePath)) {

            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private",false);
            header("Content-Type: application/zip");
            header("Content-Disposition: attachment; filename=".basename($DelFilePath).";" );
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: ".filesize($DelFilePath));
            $chunkSize = 1024 * 1024;
            $handle = fopen($DelFilePath, 'rb');
            while (!feof($handle))  {
                $buffer = fread($handle, $chunkSize);
                echo $buffer;
                ob_flush();
                flush();
            }
            fclose($handle);
            unlink($DelFilePath);
            exit();
        }
    }

}
