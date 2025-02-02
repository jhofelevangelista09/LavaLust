<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');/**
 * ------------------------------------------------------------------
 * LavaLust - an opensource lightweight PHP MVC Framework
 * ------------------------------------------------------------------
 *
 * MIT License
 * 
 * Copyright (c) 2020 Ronald M. Marasigan
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package LavaLust
 * @author Ronald M. Marasigan <ronald.marasigan@yahoo.com>
 * @copyright Copyright 2020 (https://ronmarasigan.github.io)
 * @version Version 1
 * @link https://lavalust.pinoywap.org
 * @license https://opensource.org/licenses/MIT MIT License
 */

class Lang {

	//current language
    public $current = '';

    public $default;

    public $languages = array();

    public function __construct() {
        $this->default = config_item('language');
        $this->current = $this->clientlanguage();
    }

    public function translate($key, $params = array()) {

        $this->lang_loaded();

        if (is_string($key) && !empty($this->languages[$this->current][$key]))
        {
            $text = $this->languages[$this->current][$key];

            if (!empty($params) && is_array($params))
            {
                foreach ($params as $param => $replacement)
                {
                    $text = str_replace('{' . $param . '}', $replacement, $text);
                }
            }

            return $text;
        }

        return null;
    }

    public function lang_loaded()
    {
        if (empty($this->languages[$this->current]))
        {
            if (!file_exists(APP_DIR . 'language/' . $this->current . '.php'))
            {
                $this->current = $this->default;
            }

            $this->languages[$this->current] = require(SYSTEM_DIR .'language/' . $this->current . '.php');
        }
    }

    public function language($lang)
    {
        $this->current = (!empty($lang) && is_string($lang)) ? $lang : $this->clientlanguage();
        $this->lang_loaded();
    }

    public function clientlanguage()
    {
        return !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']) : null;
    }

}

?>