<?

$call =  CallAPI('GET', 'http://vvv-vallei-evenementen.nl.84-38-229-61.eftwee.nl/api/events?city=veenendaal');
function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();
    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
	curl_setopt($curl, CURLOPT_HTTPHEADER,array ('Access-Token: XE9e9WWjRyzDn7aW','Accept:application/json'));
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    return curl_exec($curl);
}

$decoded = json_decode($call,TRUE);

//$decoded['events'][0];

//print_r(array_keys($decoded['events'][0]));

 print_r($decoded['events'][0]);

/*
//next example will recieve all messages for specific conversation
$service_url = 'http://vvv-vallei-evenementen.nl.84-38-229-61.eftwee.nl/api/events?city=veenendaal';
$curl = curl_init($service_url);
curl_setopt($curl, CURLOPT_HTTPHEADER,array ('Access-Token: XE9e9WWjRyzDn7aW','Accept:text/xml'));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$curl_response = curl_exec($curl);
if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('error occured during curl exec. Additioanl info: ' . var_export($info));
}
curl_close($curl);
$decoded = json_decode($curl_response);
//echo $decoded->response->status;

if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
    die('error occured: ' . $decoded->response->errormessage);
}
//echo 'response ok!';
var_export($decoded->response);
*/
//print_r($decoded->response);
?>
