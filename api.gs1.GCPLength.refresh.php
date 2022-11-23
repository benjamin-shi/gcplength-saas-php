<?php
require_once './inc/function.inc.php';
include_once './inc/settings.inc.php';

$err = '';
$errors = [];
$response_data = [];

//0.
/*if (!checkSameServerSubmit())
{
    $err = 'Please submit the request from same server!';
}*/

$downloaded_path = filepath_server($downloaded_path);


//1.Check if data file exists
if ((!file_exists($downloaded_path)) || ('yes' == getArrayValByKeys($_REQUEST, ['refresh', 'r'])))
{
    downloadFile($url_gcplength_json, $downloaded_path);
}

if (!file_exists($downloaded_path))
{
    $err = 'Download file not exists';
}

//2.Read file and parse
$TableGCPLength = [];
if ('' == $err)
{
    $data = @file_get_contents($downloaded_path);
    $json_data = @json_decode($data, true);
    unset($data);
    if ($json_data)
    {
        foreach ($json_data['GCPPrefixFormatList']['entry'] as $value)
        {
            $TableGCPLength[$value['prefix']] = $value['gcpLength'];
            //echo "'{$value['prefix']}' : {$value['gcpLength']} <br />";
        }
    }

    if (count($TableGCPLength) <= 0)
    {
        $err = 'Parse error';
    }
}

if ('' == $err)
{
    $data = serialize($TableGCPLength);
    if (!file_put_contents(filepath_server($data_path), $data))
    {
        $err = 'Write file error';
    }
    unset($data);
}
unset($TableGCPLength);

if ('' == $err)
{
    $response = ['isOK' => true, 'status' => 'OK', 'message' => '', 'errors' => $errors, 'data' => $data_path];
}
else
{
    $response = ['isOK' => false, 'status' => 'Error', 'message' => $err, 'errors' => $errors, 'data' => null];
}

//Return
header('Content-Type: application/json;charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>