<?php

/**
 * email：<gaozhiqiang@kejishidai.cn>
 * Date： 2017-04-12
 */

namespace common\saas\lib\barcodegen;

include_once "BarcodeAutoloader.php";

class Barcode
{
    const TEMP_PATH = "@app/runtime/image/"; // 存储地址;

    /**
     * 临时生成文件名
     * @return string
     */
    private static function createTempFile()
    {
        $filedir = \Yii::getAlias(self::TEMP_PATH);
        $filename = 'barcode_';
        $filename .= mt_rand(1000000, 9999999);
        $filename .= '.png';
        // 文件路径
        $filepath = $filedir.'/'.$filename;
        if (!file_exists($filedir)) {
            mkdir($filedir, 0755, true);
        }
        return $filepath;
    }

    /**
     * 手动删除临时文件
     * @param $tempFile
     */
    private static function deleteTempFile($tempFile)
    {
        @unlink($tempFile);
    }

    /**
     * 生成并获取条形码内容
     * @param $text
     * @return string
     */
    public static function genBarCode($text,$codeHeight)
    {
        $filepath = self::createTempFile();
        // 加载字体大小
        $fontpath = dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.'lib/barcodegen/font/Arial.ttf';
        $font = new \BCGFontFile($fontpath, 18);
        // 条形码颜色
        $color_black = new \BCGColor(0, 0, 0);
        $color_white = new \BCGColor(255, 255, 255);
        $drawException = null;
        $code = new \BCGcode128();
        try {
            $code->setScale(2);      // 条形码分辨率
            $code->setThickness($codeHeight); // 条形码的厚度
            $code->setForegroundColor($color_black); // 条形码颜色
            $code->setBackgroundColor($color_white); // 空白间隙颜色
            $code->setFont($font); // 条形码字体
            $code->parse($text);   // 条形码需要的数据内容
        } catch(\Exception $exception) {
            $drawException = $exception;
        }
        $drawing = new \BCGDrawing($filepath, $color_white);
        if ($drawException) {
            $drawing->drawException($drawException);
        } else {
            $drawing->setBarcode($code);
            $drawing->draw();
        }
        $drawing->finish(\BCGDrawing::IMG_FORMAT_PNG);
        $content = file_get_contents($filepath);
        self::deleteTempFile($filepath);

        // 生成png条形码图片
        header('Content-Type: image/png');
        header('Content-Disposition: inline; filename="barcode.png"');
        echo $content;
        exit;
    }
}






