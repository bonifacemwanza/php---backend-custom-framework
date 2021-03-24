<?php
require_once('./assets/init.php');
$page = 'home';
if (isset($_GET['link1'])) {
    $page = $_GET['link1'];
}

if (IS_LOGGED == true) {
    if ($user->last_active < (time() - 60)) {
        $update = $db->where('id', $user->id)->update('users', array(
            'last_active' => time()
        ));
    }
    
}

if (file_exists("./sources/$page/content.php")) {
    include("./sources/$page/content.php");
}

if (empty($kd->content)) {
    include("./sources/404/content.php");
}

$data['title'] = $kd->title;
$data['description'] = $kd->description;
$data['keyword'] = $kd->config->keyword;
$data['page'] = $kd->page;
$data['url'] = $kd->page_url_;
?>
    <input type="hidden" id="json-data" value='<?php echo str_replace("'","&apos;", htmlspecialchars(json_encode($data)));?>'>
<?php
echo $kd->content;
$db->disconnect();
unset($kd);
