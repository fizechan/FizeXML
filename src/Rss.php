<?php

namespace fize\xml;

use DOMDocument;
use DOMElement;
use Exception;
use fize\io\File;

/**
 * RSS生成类
 * @package fize\xml
 */
class Rss
{
    /**
     * @var array channel节点支持的子节点名称
     */
    private static $CHANNEL_KEYS = [
        'category', 'cloud', 'copyright', 'description', 'docs',
        'generator', 'image', 'language', 'lastBuildDate', 'link',
        'managingEditor', 'pubDate', 'rating', 'skipDays', 'skipHours',
        'textInput', 'title', 'ttl', 'webMaster'
    ];

    /**
     * @var array item节点支持的子节点名称
     */
    private static $ITEM_KEYS = [
        'author', 'category', 'comments', 'description', 'enclosure',
        'guid', 'link', 'pubDate', 'source', 'title'
    ];

    /**
     * @var DOMDocument RSS文档对象
     */
    private $doc;

    /**
     * @var DOMElement rss节点
     */
    private $rss;

    /**
     * @var DOMElement channel节点
     */
    private $channel;

    /**
     * @var DOMElement channel的skipDays节点
     */
    private $channelSkipDays;

    /**
     * @var DOMElement channel的skipHours节点
     */
    private $channelSkipHours;

    /**
     * 构造函数
     * @param string $title 定义频道的标题
     * @param string $link 定义指向频道的超链接
     * @param string $description 描述频道
     * @throws Exception
     */
    public function __construct($title, $link, $description)
    {
        $this->doc = new DOMDocument('1.0', 'UTF-8');

        $this->rss = $this->doc->createElement('rss');
        $this->rss->setAttribute('version', '2.0');

        $this->channel = $this->doc->createElement('channel');

        $this->setChannel('title', $title);
        $this->setChannel('link', $link);
        $this->setChannel('description', $description);
        $this->setChannel('language', '	zh-cn');
        $this->setChannel('lastBuildDate', date('Y-m-d H:i:s'));
        $this->setChannel('pubDate', date('Y-m-d H:i:s'));

        $this->channelSkipDays = $this->doc->createElement('skipDays');
        $this->channelSkipHours = $this->doc->createElement('skipHours');
    }

    /**
     * 设置channel子节点
     * @param string $key 子节点名称
     * @param mixed $value 为null时不添加，为DOMElement则插入该节点，为字符串时则写入该字符串
     * @param array $attrs 该节点属性
     * @throws Exception
     */
    public function setChannel($key, $value, array $attrs = [])
    {
        if (!in_array($key, self::$CHANNEL_KEYS)) {
            throw new Exception("channel节点不支持子节点{$key}");
        }

        if ($value instanceof DOMElement) {
            $dom = $this->doc->createElement($key);
            $dom->appendChild($value);
        } else {
            $dom = $this->doc->createElement($key, $value);
        }
        if ($attrs) {
            foreach ($attrs as $k => $v) {
                $dom->setAttribute($k, $v);
            }
        }

        $elt = $this->channel->getElementsByTagName($key);
        if ($elt && $elt->length > 0) {
            $this->channel->replaceChild($dom, $elt[0]);
        } else {
            $this->channel->appendChild($dom);
        }
    }

    /**
     * 设置channel子节点category
     * @param string $category
     * @param string $domain category的domain属性，字符串或 URL，标识分类的分类法
     */
    public function setChannelCategory($category, $domain = null)
    {
        $attrs = [];
        if (!is_null($domain)) {
            $attrs['domain'] = $domain;
        }
        $this->setChannel('category', $category, $attrs);
    }

    /**
     * 设置channel子节点cloud
     * @param array $attrs 属性数组
     */
    public function setChannelCloud(array $attrs)
    {
        $this->setChannel('cloud', null, $attrs);
    }

    /**
     * 设置channel子节点image
     * @param string $link 定义提供该频道的网站的超连接
     * @param string $url 定义图像的 URL
     * @param string $title 定义当图片不能显示时所显示的替代文本
     * @param string $description 规定图片链接的 HTML 标题属性中的文本
     * @param int $width 定义图像的宽度。默认是 88。最大值是 144。
     * @param int $height 定义图像的高度。默认是 31。最大值是 400。
     */
    public function setChannelImage($link, $url, $title, $description = null, $width = null, $height = null)
    {
        $image = $this->doc->createElement('image');

        $link = $this->doc->createElement('link', $link);
        $image->appendChild($link);
        $url = $this->doc->createElement('url', $url);
        $image->appendChild($url);
        $title = $this->doc->createElement('title', $title);
        $image->appendChild($title);
        if (!is_null($description)) {
            $description = $this->doc->createElement('description', $description);
            $image->appendChild($description);
        }
        if (!is_null($width)) {
            $width = $this->doc->createElement('width', $width);
            $image->appendChild($width);
        }
        if (!is_null($height)) {
            $height = $this->doc->createElement('height', $height);
            $image->appendChild($height);
        }
        $this->channel->appendChild($image);
    }

    /**
     * 设置channel子节点language
     * @see http://www.rssboard.org/rss-language-codes
     * @param string $lang 对应语言简写
     * @throws Exception
     */
    public function setChannelLanguage($lang)
    {
        $langs = [
            'af', 'sq', 'eu', 'be', 'bg', 'ca', 'zh-cn', 'zh-tw', 'hr', 'cs',
            'da', 'nl', 'nl-be', 'nl-nl', 'en', 'en-au', 'en-bz', 'en-ca', 'en-ie', 'en-jm',
            'en-nz', 'en-tt', 'en-gb', 'en-us', 'en-zw', 'et', 'fo', 'fi', 'fr', 'fr-be',
            'fr-ca', 'fr-fr', 'fr-lu', 'fr-mc', 'fr-ch', 'gl', 'gd', 'de', 'de-at', 'de-de',
            'de-li', 'de-lu', 'de-ch', 'el', 'haw', 'hu', 'is', 'in', 'ga', 'it',
            'it-it', 'it-ch', 'ja', 'ko', 'mk', 'no', 'pl', 'pt', 'pt-br', 'pt-pt',
            'ro', 'ro-mo', 'ro-ro', 'ru', 'ru-mo', 'ru-ru', 'sr', 'sk', 'sl', 'es',
            'es-ar', 'es-bo', 'es-cl', 'es-co', 'es-cr', 'es-do', 'es-ec', 'es-sv', 'es-gt', 'es-hn',
            'es-mx', 'es-ni', 'es-pa', 'es-py', 'es-pe', 'es-pr', 'es-es', 'es-uy', 'es-ve', 'sv',
            'sv-fi', 'sv-se', 'tr', 'uk'
        ];
        if (!in_array($lang, $langs)) {
            throw new Exception("channel子节点language不支持该语言");
        }
        $this->setChannel('language', $lang);
    }

    /**
     * 添加规定在那些天，聚合器忽略更新feed
     * @param string $day 哪些天
     */
    public function addChannelSkipDay($day)
    {
        $dom = $this->doc->createElement('day', $day);
        $this->channelSkipDays->appendChild($dom);
    }

    /**
     * 规定在那些小时，聚合器忽略更新feed。最多可以用24个 <skipHours> 元素。
     * @param int $hour 0 表示午夜。
     */
    public function addChannelSkipHour($hour)
    {
        $dom = $this->doc->createElement('hour', $hour);
        $this->channelSkipHours->appendChild($dom);
    }

    /**
     * 设置channel子节点language,规定应当与 feed 一同显示的文本输入域。
     * @param string $name 定义在文本输入域中的文本对象的名称。
     * @param string $title 定义文本输入域中的提交按钮的标注 (label)
     * @param string $link 定义处理文本输入的 CGI 脚本的 URL
     * @param string $description 定义对文本输入域的描述
     */
    public function setChannelTextInput($name, $title, $link, $description)
    {
        $text_input = $this->doc->createElement('textInput');

        $name = $this->doc->createElement('name', $name);
        $text_input->appendChild($name);
        $title = $this->doc->createElement('title', $title);
        $text_input->appendChild($title);
        $link = $this->doc->createElement('link', $link);
        $text_input->appendChild($link);
        $description = $this->doc->createElement('description', $description);
        $text_input->appendChild($description);

        $this->channel->appendChild($text_input);
    }

    /**
     * 添加item项
     * @param string $title
     * @param string $link
     * @param string $description
     * @param mixed $pubDate 可以是时间戳或者时间字符串
     * @param array $addns 其他属性
     * @throws Exception
     */
    function addItem($title, $link, $description, $pubDate = null, array $addns = [])
    {
        $item = $this->doc->createElement('item');

        $title = $this->doc->createElement('title', $title);
        $item->appendChild($title);
        $link = $this->doc->createElement('link', $link);
        $item->appendChild($link);
        $description = $this->doc->createElement('description', $description);
        $item->appendChild($description);

        if (is_null($pubDate)) {
            $pubDate = date('Y-m-d H:i:s');
        }
        if (is_numeric($pubDate)) {
            $pubDate = date('Y-m-d H:i:s', $pubDate);
        }
        $pubDate = $this->doc->createElement('pubDate', $pubDate);
        $item->appendChild($pubDate);

        foreach ($addns as $k => $v) {
            if (!in_array($k, self::$ITEM_KEYS)) {
                throw new Exception("item节点不支持子节点{$k}");
            }

            if (is_array($v)) {
                $value = $v[0];
                $attrs = $v[1];
                $dom = $this->doc->createElement($k, $value);
                foreach ($attrs as $key => $val) {
                    $dom->setAttribute($key, $val);
                }
            } else {
                $dom = $this->doc->createElement($k, $v);
            }
            $item->appendChild($dom);
        }

        $this->channel->appendChild($item);
    }

    /**
     * 返回RSS的XML为字符串
     * @param bool $format 是否格式化
     * @return string
     */
    public function fetch($format = true)
    {
        if ($this->channelSkipDays->getElementsByTagName('day')->length > 0) {
            $this->channel->appendChild($this->channelSkipDays);
        }
        if ($this->channelSkipHours->getElementsByTagName('hour')->length > 0) {
            $this->channel->appendChild($this->channelSkipHours);
        }
        $this->rss->appendChild($this->channel);
        $this->doc->appendChild($this->rss);
        $this->doc->formatOutput = $format;
        return $this->doc->saveXML();
    }

    /**
     * 输出RSS的XML到浏览器
     * @param bool $format 是否格式化
     */
    public function display($format = true)
    {
        header("Content-Type: text/xml; charset=utf-8");
        echo $this->fetch($format);
    }

    /**
     * 保存RSS到指定文件，注意该文件后缀必须为xml
     * @param string $path 要保存的文件路径
     * @param bool $format 是否格式化
     */
    public function build($path, $format = true)
    {
        $file = new File($path, 'w+');
        $file->write($this->fetch($format));
    }
}