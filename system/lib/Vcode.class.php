<?php

/**
 *  Verification Code Class
 *
 *  Used to anti-spam base at PHP GD Lib
 *  @author Eric,<wangyingei@yeah.net>
 *  @version 1.0
 *  @copyright  Ereesoft Inc. 2009
 *  @update 2009-05-14 22:32:05
 *  @example
 *      session_sratr();
 *      $vcode = new Vcode();
 *      $vcode->setLength(5);
 *      $_SESSION['vcode'] = $vcode->paint();// To be encrypted by MD5
 */
class Vcode {

    /**
     *  @var $width The width of the image,auto Calculated 验证图片宽度，程序自动计算
     */
    private $width;

    /**
     *  @var $height Image height 验证图片高度
     */
    private $height;

    /**
     *  @var $length Verification Code lenght 验证码长度
     */
    private $length;

    /**
     *  @var $bgColor Image background color default random 验证图片背景色
     */
    private $bgColor;

    /**
     *  @var $fontColor The text color 验证码颜色
     */
    private $fontColor;

    /**
     *  @var $dotNoise The number of noise 噪点数量
     */
    private $dotNoise;

    /**
     *  @var $lineNoise The number of noise lines 干扰线数量
     */
    private $lineNoise;

    /**
     *  @var $im image resource GD图像操作资源
     */
    private $im;

    /**
     *  void Vcode::Vcode()
     *
     *  The constructor
     */
    public function Vcode() {
        $this->dotNoise = 20; //初始化噪点数量
        $this->lineNoise = 3; //初始化干扰线数量
    }

    /**
     *  void Vcode::setLength(integer $length)
     *
     *  Set Verification Code length
     *  @access public
     *  @param integer $length;
     *  @return void
     */
    public function setLength($length) {
        $this->length = $length;
    }

    /**
     *  void Vcode::setBgColor(string $bgColor)
     *
     *  Set background color of the Verification Image
     *  @access public
     *  @param string $bgColor e.g.: #ffffff;可以直接使用css书写中的16进制写法，但不可简写
     *  @return void
     */
    public function setBgColor($bgColor) {
        $this->bgColor = sscanf($bgColor, '#%2x%2x%2x');
    }

    /**
     *  void Vcode::setFontColor(string $fontgColor)
     *
     *  Set text color of the Verification Image
     *  @access public
     *  @param string $fontColor e.g.: #ffffff;可以直接使用css书写中的16进制写法，但不可简写
     *  @return void
     */
    public function setFontColor($fontColor) {
        $this->fontColor = sscanf($fontColor, '#%2x%2x%2x');
    }

    /**
     *  void Vcode::setDotNoise(integer $num)
     *
     *  How many noise dots want to draw
     *  @access public
     *  @param integer $num Too much will lower performance
     *  @return void
     */
    public function setDotNoise($num) {
        $this->dotNoise = $num; //手动设置噪点数量后，会覆盖初始设置
    }

    /**
     *  void Vcode::setLineNoise(integer $num)
     *
     *  How many noise lines want to draw
     *  @access public
     *  @param integer $num Too much will lower performance
     *  @return void
     */
    public function setLineNoise($num) {
        $this->lineNoise = $num; //手动设置干扰线数量后，会覆盖初始设置
    }

    /**
     *  String Vcode::randString()
     *
     *  Create Random characters 生成随机字符串
     *  @access private
     *  @return String
     */
    private function randString() {
        $string = strtoupper(md5(microtime() . mt_rand(0, 9)));
        return substr($string, 0, $this->length);
    }

    /**
     *  void Vcode::drawDot()
     *
     *  Draw dots noise 根据制定的数量随机画噪点，噪点颜色也随机
     *  @access private
     */
    private function drawDot() {
        for ($i = 0; $i < $this->dotNoise; $i++) {
            $color = imagecolorallocate($this->im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)); //生成随机颜色
            imagesetpixel($this->im, mt_rand(0, $this->width), mt_rand(0, $this->height), $color); //在随机生成的坐标上画噪点
        }
    }

    /**
     *  void Vcode::drawLine()
     *
     *  Draw line noise 随机颜色随机画干扰线
     *  @access private
     */
    private function drawLine() {
        for ($i = 0; $i < $this->lineNoise; $i++) {
            $color = imagecolorallocate($this->im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)); //随机生成颜色
            imageline($this->im, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color); //在随机生成的坐标上画干扰线
        }
    }

    /**
     *  String Vcode::paint()
     *
     *  Create image and output
     *  @access public
     *  @return string  The Verification Code to be encrypted by MD5
     */
    public function paint() {

        if (empty($this->length))
            $this->length = 4; //验证码默认长度为4

        $this->width = $this->length * 17 + 4; //计算验证图片宽度
        $this->height = 30; //制定验证码图片高度
        $this->im = imagecreate($this->width, $this->height); //创建画布

        if (empty($this->bgColor) || empty($this->fontColor)) {//如果没有设置前景色和背景色则全部随机
            //避免前景色和背景色过于接近，背景色（0-130）的随机范围与前景色（131-255）分开
            imagecolorallocate($this->im, mt_rand(0, 130), mt_rand(0, 130), mt_rand(0, 130));

            $randString = $this->randString();

            for ($i = 0; $i < $this->length; $i++) {
                $fontColor = imagecolorallocate($this->im, mt_rand(131, 255), mt_rand(131, 255), mt_rand(131, 255));
                imagestring($this->im, 5, $i * 15 + 8, mt_rand(0, 8), $randString{$i}, $fontColor);
                //单个验证码字符高度随机，避免被OCR
            }
        } else {//如果设置了背景色和前景色，则不使用随机颜色
            imagecolorallocate($this->im, $this->bgColor[0], $this->bgColor[1], $this->bgColor[2]);
            $randString = $this->randString();
            $fontColor = imagecolorallocate($this->im, $this->fontColor[0], $this->fontColor[1], $this->fontColor[2]);
            for ($i = 0; $i < $this->length; $i++) {
                imagestring($this->im, 5, $i * 15 + 8, 6, $randString{$i}, $fontColor); //每个验证码字符高度仍然随机
            }
        }
        $this->drawDot(); //绘制噪点
        $this->drawLine(); //绘制干扰线
        imagepng($this->im);
        imagedestroy($this->im);
        return md5($randString); //返回MD5加密后的验证码，可直接放入session
    }

}

?>