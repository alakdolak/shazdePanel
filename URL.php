<?php

namespace Illuminate\Support\Facades;

/**
 * @method static string current()
 * @method static string full()
 * @method static string previous($fallback = false)
 * @method static string to(string $path, $extra = [], bool $secure = null)
 * @method static string secure(string $path, array $parameters = [])
 * @method static string asset(string $path, bool $secure = null)
 * @method static string route(string $name, $parameters = [], bool $absolute = true)
 * @method static string action(string $action, $parameters = [], bool $absolute = true)
 * @method static \Illuminate\Contracts\Routing\UrlGenerator setRootControllerNamespace(string $rootNamespace)
 * @method static string signedRoute(string $name, array $parameters = [], \DateTimeInterface|\DateInterval|int $expiration = null)
 * @method static string temporarySignedRoute(string $name, \DateTimeInterface|\DateInterval|int $expiration, array $parameters = [])
 * @method static string hasValidSignature(\Illuminate\Http\Request $request, bool $absolute)
 * @method static void defaults(array $defaults)
 *
 * @see \Illuminate\Routing\UrlGenerator
 */
class URL extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'url';
    }

    static function asset($path, $secure = null) {

        $tmp = explode('/', $path);

        if(count($tmp) > 0) {

            $stub = "";
            for($i = 1; $i < count($tmp); $i++) {
                if($i != count($tmp) - 1)
                    $stub .= $tmp[$i] . '/';
                else
                    $stub .= $tmp[$i];
            }

            switch ($tmp[0]) {
                case "_images":
                    return self::cdn('_image_CDN') . $stub;
                case "defaultPic":
                    return 'http://localhost/defaultPic/' . $stub;
                case "userPhoto":
                    return 'http://localhost/userPhoto/' .$stub;
                case "ads":
                    return 'http://localhost/ads/' .$stub;
                case "css":
                    return 'http://localhost/ads/' .$stub;
            }
        }

        return app('url')->asset($path, $secure);
    }

    private static function cdn($key) {
//    '_image_CDN' => 'http://79.175.133.206:8080/_images/'
//        $arr = ['imageCDN' => 'https://shazdemosafer.com/images/',
//            '_image_CDN' => 'https://shazdemosafer.com/_images/',
//            'cssCDN' => 'https://shazdemosafer.com/css/',
//            'jsCDN' => 'https://shazdemosafer.com/js/',
//            'fontCDN' => 'https://shazdemosafer.com/fonts/'];
//        return $arr[$key];
        $arr = ['imageCDN' => 'https://shazdemosafer.com/images/',
            '_image_CDN' => 'http://localhost/_images/',
            'cssCDN' => 'https://shazdemosafer.com/css/',
            'jsCDN' => 'https://shazdemosafer.com/js/',
            'fontCDN' => 'https://shazdemosafer.com/fonts/'];
        return $arr[$key];
    }

}
