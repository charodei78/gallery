<?php
header('Content-Type: image/jpeg');
$name = $_GET['name'] ?? null;
$size = $_GET['size'] ?? null;
if (!$name || !$size)
    die($_GET['name']);

$sizes = json_decode(file_get_contents('http://gallery/get_sizes.php'));
$path = __DIR__."/gallery/$name.jpg";

if (!$sizes || !is_file($path))
    die('dir not found '.$path);

foreach ($sizes as $row) {
    if ($row->name == $size)
        return get_image($name, $row->size_x, $row->size_y, $row->name);
}

function get_image($name, $size_x, $size_y, $size)
{
    $src_path = __DIR__."/gallery/$name.jpg";
    $result_dir = __DIR__."/cache/$size";
    if (!is_dir($result_dir))
        mkdir($result_dir);
    $result_path = $result_dir."/$name.jpg";
    if (is_file($result_path))
    {
        echo file_get_contents($result_path);
        return ;
    }
    list($src_width, $src_height) = getimagesize($src_path);
    $scale = min($size_x / $src_width, $size_y / $src_height);
    $newwidth = $src_width * $scale;
    $newheight = $src_height * $scale;

    $thumb = imagecreatetruecolor($newwidth, $newheight);
    if (!$thumb)
        die('can\'t create thumb');
    $source = imagecreatefromjpeg($src_path);
    imagecopyresized($thumb, $source, 0, 0, 0, 0,
            $newwidth, $newheight, $src_width, $src_height);
    imagejpeg($thumb, $result_path);
    imagejpeg($thumb);
    imagedestroy($thumb);
}
