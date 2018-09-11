<?php
require_once("includes/config.php");
if (isset($_GET["img"])) {
    $img_parts = explode(" ", $_GET["img"]);
    $img = implode($config["path_sep"], $img_parts);
    $imgname = $config["image_dir"] . $config["path_sep"] . $img;
    if (file_exists($imgname)) {
        if ((isset($_GET["w"]) && ereg("^[0-9]{1,}$", $_GET["w"])) || (isset($_GET["h"]) && ereg("^[0-9]{1,}$", $_GET["h"]))) {
            $info = getImageSize($imgname);
            if (isset($_GET["w"])) {
                $dst_w = $_GET["w"];
                $dst_h = round($info[1] * ($_GET["w"] / $info[0]));
                $src_w = $info[0];
                $src_h = $info[1];
            } elseif (isset($_GET["h"])) {
                $dst_w = round($info[0] * ($_GET["h"] / $info[1]));
                $dst_h = $_GET["h"];
                $src_w = $info[0];
                $src_h = $info[1];
            }
            $topnm = $tothumb = $quant = $mime = '';
            switch($info[2]) {
            case 1:
                $topnm = $config["NetPBM"]["basepath"] . $config["NetPBM"]["giftopnm"];
                $tothumb = $config["NetPBM"]["basepath"] . $config["NetPBM"]["ppmtogif"];
                $quant = $config["NetPBM"]["basepath"] . $config["NetPBM"]["ppmquant"] . " 256";
                $mime = "image/gif";
                break;
            case 2:
                $topnm = $config["NetPBM"]["basepath"] . $config["NetPBM"]["jpegtopnm"];
                $tothumb = $config["NetPBM"]["basepath"] . $config["NetPBM"]["ppmtojpeg"];
                $quant = "";
                $mime = "image/jpeg";
                break;
            case 3:
                $topnm = $config["NetPBM"]["basepath"] . $config["NetPBM"]["pngtopnm"];
                $tothumb = $config["NetPBM"]["basepath"] . $config["NetPBM"]["pnmtopng"];
                $quant = $config["NetPBM"]["basepath"] . $config["NetPBM"]["ppmquant"] . " 256";
                $mime = "image/png";
                break;
            }
            $pnmscale = $config["NetPBM"]["basepath"] . $config["NetPBM"]["pnmscale"];
            // build the shell command
            $cmd = sprintf('%s "%s" | %s -xysize %d %d | ', $topnm, $imgname, $pnmscale, $dst_w, $dst_h );
            if( $quant != "" ) $cmd .= sprintf('%s | ', $quant);
            $cmd .= $tothumb;
            header("Content-Type: " . $mime);
            if ($config["imlib"] == "NetPBM") {
                passthru($cmd);
            } elseif ($config["imlib"] == "GD2") {
                switch($info[2]) {
                case 1:
                    $im = @ImageCreateFromGIF ($imgname);
                    $dst_img = imageCreate($dst_w, $dst_h);
                    if ($im && $dst_img) {
                        imageCopyResampled($dst_img, $im, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
                        imageGIF($dst_img);
                    }
                    break;
                case 2:
                    $im = @ImageCreateFromJPEG ($imgname);
                    $dst_img = imageCreateTrueColor($dst_w, $dst_h);
                    if ($im && $dst_img) {
                        imageCopyResampled($dst_img, $im, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
                        imageJPEG($dst_img);
                    }
                    break;
                case 3:
                    $im = @ImageCreateFromPNG ($imgname);
                    $dst_img = imageCreateTrueColor($dst_w, $dst_h);
                    if ($im && $dst_img) {
                        imageCopyResampled($dst_img, $im, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
                        imagePNG($dst_img);
                    }
                    break;
                }
            }
        }
    }
}
?>
