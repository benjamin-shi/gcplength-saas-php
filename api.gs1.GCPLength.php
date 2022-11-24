<?php
require_once './inc/function.inc.php';
include_once './inc/settings.inc.php';

$prefix = '';

$err = '';
$errors = [];
$response_data = [];

//0.
/*if (!checkSameServerSubmit())
{
    $err = 'Please submit the request from same server!';
}*/

//1.Get input
if ('' == $err)
{
    //1.1.see json
    $requestBody = @file_get_contents('php://input');
    $json_data = @json_decode($requestBody, true);
    
    $prefix = getArrayValByKeys($json_data, ['GCP', 'gcp', 'GTIN', 'gtin','prefix', 'p', 'key', 'k', 'gs1', 'id', 'Code', 'code']);
    if ((!$prefix) || ('' == $prefix))
    {
        $prefix = getArrayValByKeys($_REQUEST, ['GCP', 'gcp', 'GTIN', 'gtin','prefix', 'p', 'key', 'k', 'gs1', 'id', 'Code', 'code']);

        if (!$prefix) $prefix = '';
    }

    if ((!$prefix) || ('' == $prefix))
    {
        $err = 'Input Error, no necessary input parameter(s).';
        array_push($errors, 'Prefix');
    }
    if (!preg_match('/^\d+/', $prefix))
    {
        $err = 'Input Error.';
        array_push($errors, 'Prefix Length');
    }

    if (14 == strlen($prefix))
    {
        $prefix = substr($prefix, 1);
    }
    
    if (strlen($prefix) > 12)
    {
        $prefix = substr($prefix, 0, 12);
    }
}

//3.look
if ('' == $err)
{
    $data = @file_get_contents(filepath_server($data_path));
    $TableGCPLength = @unserialize($data);
    unset($data);

    $GCP = '';
    $GCPLength = 0;

    if (is_array($TableGCPLength))
    {
        for ($ii = 1; $ii < strlen($prefix);++$ii)
        {
            $GCP = substr($prefix, 0, $ii);
            if (array_key_exists($GCP, $TableGCPLength))
            {
                $GCPLength = intval($TableGCPLength[$GCP]);
                break;
            }
        }
    }
    if (('' == $GCP) || ($GCPLength <= 0) || ($GCPLength > 12))
    {
        $err = 'GCP Length cannot be found in register.';
    }
    else
    {
        $response_data = [
            'GCP' => $GCP,
            'Length' => $GCPLength
        ];
    }
}

if ('' == $err)
{
    $response = ['isOK' => true, 'status' => 'OK', 'message' => '', 'errors' => $errors, 'data' => $response_data];
}
else
{
    $response = ['isOK' => false, 'status' => 'Error', 'message' => $err, 'errors' => $errors, 'data' => null];
}

//Return
header('Content-Type: application/json;charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>