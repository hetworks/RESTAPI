https://mail-attachment.googleusercontent.com/attachment/?ui=2&ik=a3ec173688&view=att&th=1419306d9f4d1ac8&attid=0.1&disp=inline&realattid=f_hmhpm0zu0&safe=1&zw&saduie=AG9B_P8c-JRtJl9O4uXhsfg0OLIv&sadet=1381151284514&sads=MWBKTviILoXn53peW41mcLVErGI





DE API IS BEREIKBAAR DOORMIDDEL VAN DE ONDERSTAANDE FUNCTIE:


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
	curl_setopt($curl, CURLOPT_HTTPHEADER,array ('Access-Token: APIKEY','Accept:text/xml'));
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    return curl_exec($curl);
}
$events = explode('<event>',$call);
foreach($events as &$event)
{
	echo $event.'<br /><br /><br />';
}