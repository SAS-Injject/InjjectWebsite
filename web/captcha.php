<?php

use App\Helpers\Tools\Captcha;
use App\Helpers\Tools\CaptchaType;

require_once "../autoload_web.php";

Captcha::generateImage(characters: CaptchaType::ALPHA_NUM);