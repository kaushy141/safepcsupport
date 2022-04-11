<?php
$links = array
(
"https://www.hypstar.com/share/video/6514697724233780481/?tag=0&share_ht_uid=6513945141722890242&did=6513947025002939905&utm_medium=huoshan_android&tt_from=&iid=6513947030954690306&app=live_i18n",
"https://www.hypstar.com/share/video/6514955286950710530/?tag=0&share_ht_uid=6513565120710082562&did=6513565715197380097&utm_medium=huoshan_android&tt_from=&iid=6513565706687252226&app=live_i18n",
"https://www.hypstar.com/share/video/6514943408140717314/?tag=0&share_ht_uid=6513565120710082562&did=6513565715197380097&utm_medium=huoshan_android&tt_from=&iid=6513565706687252226&app=live_i18n",
"https://www.hypstar.com/share/video/6514961924088663298/?tag=0&share_ht_uid=6513565120710082562&did=6513565715197380097&utm_medium=huoshan_android&tt_from=&iid=6513565706687252226&app=live_i18n",
"https://www.hypstar.com/share/video/6514868009880784130/?tag=0&share_ht_uid=6513565120710082562&did=6513565715197380097&utm_medium=huoshan_android&tt_from=&iid=6513565706687252226&app=live_i18n",
"https://www.hypstar.com/share/video/6514707033357290754/?tag=0&share_ht_uid=6513565120710082562&did=6513565715197380097&utm_medium=huoshan_android&tt_from=&iid=6513565706687252226&app=live_i18n",
"https://www.hypstar.com/share/video/6514707938320321794/?tag=0&share_ht_uid=6513565120710082562&did=6513565715197380097&utm_medium=huoshan_android&tt_from=&iid=6513565706687252226&app=live_i18n",
"https://www.hypstar.com/share/video/6514702334948281601/?tag=0&share_ht_uid=6513565120710082562&did=6513565715197380097&utm_medium=huoshan_android&tt_from=&iid=6513565706687252226&app=live_i18n",
"https://www.hypstar.com/share/video/6514704371408702722/?tag=0&share_ht_uid=6513565120710082562&did=6513565715197380097&utm_medium=huoshan_android&tt_from=&iid=6513565706687252226&app=live_i18n",
"https://www.hypstar.com/share/video/6514676082724048130/?tag=0&share_ht_uid=6513565120710082562&did=6513565715197380097&utm_medium=huoshan_android&tt_from=&iid=6513565706687252226&app=live_i18n",
"https://www.hypstar.com/share/video/6514568905007041794/?tag=0&share_ht_uid=6513565120710082562&did=6513565715197380097&utm_medium=huoshan_android&tt_from=&iid=6513565706687252226&app=live_i18n",
"https://www.hypstar.com/share/video/6513926838450916609/?tag=0&share_ht_uid=6513565120710082562&did=6513565715197380097&utm_medium=huoshan_android&tt_from=&iid=6513565706687252226&app=live_i18n",
"https://www.hypstar.com/share/video/6513568412696841474/?tag=0&share_ht_uid=6513565120710082562&did=6513565715197380097&utm_medium=huoshan_android&tt_from=&iid=6513565706687252226&app=live_i18n"
);
$time = round(time()/10000);
foreach($links as $link)
{
$linkData = parse_url($link);
//print_r($linkData);
parse_str($linkData['query'], $query);	
//print_r($query);
$query['share_ht_uid'] += $time;
$query['iid'] += $time;
$query['did'] += $time;
$url = $linkData['scheme']."://".$linkData['host'].$linkData['path']."?tag=0&share_ht_uid=".number_format($query['share_ht_uid'], 0, '', '')."&did=".number_format($query['did'], 0, '', '')."&utm_medium=huoshan_android&tt_from=&iid=".number_format($query['iid'], 0, '', '')."&app=live_i18n";
file_get_contents($url);

$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0'
));
// Send the request & save response to $resp
curl_exec($curl);
// Close request to clear up some resources
curl_close($curl);
}
echo "success";
?>