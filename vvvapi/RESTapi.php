<?
function CallAPI($method, $urladdon, $data = false)
{
	$url  = 'http://vvv-vallei-evenementen.nl.84-38-229-61.eftwee.nl/api/events';
	$url .= $urladdon;
	
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

?>