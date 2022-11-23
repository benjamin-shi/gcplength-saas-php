<?php
function getArrayValByKeys($arr, $keys)
{
    $result = FALSE;

    if (isset($arr) && is_array($arr))
    {
        foreach ($keys as $key)
        {
            if (array_key_exists($key, $arr))
            {
                $result = $arr[$key];
                break;
            }
        }
    }

    return $result;
}

function getArrayVal($arr, $key)
{
    $result = '';

    if (isset($arr[$key]))
    {
        $result = $arr[$key];
    }

    return $result;
}

function checkSameServerSubmit()
{
    $result = false;

    $server1 = getArrayVal($_SERVER, "HTTP_REFERER");
    $server2 = getArrayVal($_SERVER, 'SERVER_NAME');

    if (preg_match('/^http:\/\//', $server1))
    {
        $server1 = substr($server1, 7, strlen($server2));
    }
    elseif (preg_match('/^https:\/\//', $server1))
    {
        $server1 = substr($server1, 8, strlen($server2));
    }
    else
    {
        $server1 = substr($server1, 0, strlen($server2));
    }

    //echo 'server1:' . $server1;
    //echo "\n";
    //echo 'server2:' . $server2;

    if ($server1 == $server2)
    {
        $result = true;
    }

    return $result;
}

function jsonPost($url, $jsonData)
{
    //Initiate cURL.
    $ch = curl_init($url);
    
    //Encode the array into JSON.
    $jsonDataEncoded = json_encode($jsonData);
    
    //Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);
    
    //Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
    
    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8')); 
    
    //Set the return
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    
    //Execute the request
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function jsonGet($url)
{
    //Initiate cURL.
    $ch = curl_init($url);
    
    //Tell cURL that we want to send a GET request.
    curl_setopt($ch, CURLOPT_POST, 0);
    
    //Set the return
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    
    //Execute the request
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function getIP()
{
    //print_r($_SERVER);
    
    $HTTP_CLIENT_IP = getArrayVal($_SERVER, 'HTTP_CLIENT_IP');
    $HTTP_X_FORWARDED_FOR = getArrayVal($_SERVER, 'HTTP_X_FORWARDED_FOR');
    $REMOTE_ADDR = getArrayVal($_SERVER, 'REMOTE_ADDR');
    if ('' != $HTTP_CLIENT_IP)
    {
        $ip = $HTTP_CLIENT_IP;
    }
    else if ('' != $HTTP_X_FORWARDED_FOR)
    {
        $ip = $HTTP_X_FORWARDED_FOR;
    }
    else
    {
        $ip = $REMOTE_ADDR;
    }

    return $ip;
}

function remove_utf8_bom($text)
{
    $bom = pack('H*','EFBBBF');
    $text = preg_replace("/^$bom/", '', $text);
    return $text;
}

function add_utf8_bom($text)
{
    $bom = pack('H*','EFBBBF');
    $text = $bom . $text;
    return $text;
}

function downloadFile($url, $downloaded_path)
{
    $fh = fopen ($downloaded_path, "w");
    $ch = curl_init ();
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_FILE, $fh);
    curl_exec ($ch);
    curl_close ($ch);
}

function getScriptName()
{
    $name = '';
    
    if ('' != $_SERVER['SCRIPT_NAME'])
    {
        $name = $_SERVER['SCRIPT_NAME'];
    }
    if (('' == $name) && ('' != $_SERVER['SCRIPT_FILENAME']))
    {
        $name = $_SERVER['SCRIPT_FILENAME'];
    }
    if (('' == $name) && ('' != $_SERVER['REQUEST_URI']))
    {
        $name = $_SERVER['REQUEST_URI'];
    }
    
    $name = preg_replace('/index\\.php$/i', '', $name);
    $name = preg_replace('/\\.php$/i', '', $name);
    $name = preg_replace('/\\/$/i', '', $name);
    $name = pathinfo($name, PATHINFO_BASENAME);

    return $name;
}

function str_to_array($text)
{
    return preg_split('/\s*[;,|]\s*/', $text);
}

function array_clone($arr)
{
    $result = array();
    if (is_array($arr))
    {
        foreach($arr as $key => $val)
        {
            $result[$key] = $val;
        }
    }
    else
    {
        $result[0] = $arr;
    }
    return $result;
}

function filepath_server($filepath)
{
    //echo '__DIR__='.__DIR__;
    //echo 'getCwd()='.;
    //echo '$_SERVER[\'DOCUMENT_ROOT\']='.$_SERVER['DOCUMENT_ROOT'];
    if (preg_match('/^\//', $filepath))
    {
        $root = $_SERVER['DOCUMENT_ROOT'];
        if (preg_match('/\/$/', $root))
        {
            $root = substr($root, 0, strlen($root) - 1);
        }
        $filepath = $root . $filepath;
    }
    else
    {
        $root = getcwd();
        if (preg_match('/\/$/', $root))
        {
            $root = substr($root, 0, strlen($root) - 1);
        }
        if (preg_match('/^[^\.]/', $filepath))
        {
            $filepath = '/' . $filepath;
        }
        $filepath = $root . $filepath;
    }
    return $filepath;
}

function list_all_images_in_dir($dir)
{
    $files = [];

    $absolute_dir = filepath_server($dir);
    if (!preg_match('/\/$/', $dir))
    {
        $dir = $dir . '/';
    }

    if (is_dir($absolute_dir))
    {
        if ($dh = opendir($absolute_dir))
        {
            while (($file = readdir($dh)) !== false)
            {
                if (!is_dir($file))
                {
                    if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg']))
                    {
                        array_push($files, $dir . $file);
                    }
                }
            }
            closedir($dh);
        }
    }
    
    return $files;
}

function list_all_files_in_dir($dir, $arrTypeExts)
{
    $files = [];

    $absolute_dir = filepath_server($dir);
    if (!preg_match('/\/$/', $dir))
    {
        $dir = $dir . '/';
    }
    
    if (is_dir($absolute_dir))
    {
        if ($dh = opendir($absolute_dir))
        {
            while (($file = readdir($dh)) !== false)
            {
                if (!is_dir($file))
                {
                    if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $arrTypeExts))
                    {
                        array_push($files, $dir . $file);
                    }
                }
            }
            closedir($dh);
        }
    }
    
    return $files;
}

function upload_image($fileinfo, $filepath)
{
    $isOK = false;

    $filepath = filepath_server($filepath);

    if ((0 === $fileinfo['error']) && ($fileinfo['size'] > 0)) {
        $upload_dir = pathinfo($filepath, PATHINFO_DIRNAME);
        $file_name = $fileinfo['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $temp_file = $fileinfo['tmp_name'];
        //$size = $fileinfo['size'];

        $support_types = ['png', 'gif', 'jpg', 'jpeg', 'bmp', 'wbmp'];

        if (!in_array($file_ext, $support_types)) {
            //$isOK = false;
            goto finish_upload_image;
        }

        //echo '$upload_dir = ' . $upload_dir;
        if (!is_dir($upload_dir))
        {
            //echo '$upload_dir=' . $upload_dir;
            @mkdir($upload_dir, 0766, true);
        }

        if (file_exists($filepath)) {
            if (!unlink($filepath))
            {
                //$isOK = false;
                goto finish_upload_image;
            }
        }
        if (!@move_uploaded_file($temp_file, $filepath))
        {
            //$isOK = false;
            goto finish_upload_image;
        }
        //echo "temp_file = $temp_file" . "(" . file_exists($temp_file) . ")";
        //echo "admin_image = $admin_image" . "(" . file_exists($admin_image) . ")";

        if (file_exists($filepath)) {
            $isOK = true;
        }
    }
finish_upload_image:
    return $isOK;
}

function remove_empty_paragraph($html)
{
    $html = trim($html);
    $html = preg_replace('/<p>\s*<\/p>/u', '', $html);
    return $html;
}

function find_first_image($html)
{
    $src = '';

    preg_match('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', $html, $match);
    
    //print_r($match);
    if (count($match) > 2)
    {
        $src = $match[2];
    }

    return $src;
}

function find_images($html)
{
    $images = array();
    
    preg_match_all('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', $html, $matches, PREG_SET_ORDER);
    //print_r($matches);
    foreach ($matches as $match)
    {
        $src = $match[2];
        array_push($images, $src);
    }

    return array_unique($images);
}
?>
