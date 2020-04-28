<?php

use PHPUnit\Framework\TestCase;
use fize\xml\Rss;


class TestRss extends TestCase
{

    public function testDisplay()
    {
        $cmd = 'start cmd /k "cd /d %cd%/website &&php -S localhost:8123"';
        $pid = popen($cmd, 'r');
        pclose($pid);
        sleep(3);  //待服务器启动

        $cmd = 'start http://localhost:8123/rss_display.php';
        $pid = popen($cmd, 'r');
        pclose($pid);

        self::assertTrue(true);
    }

	public function testBuild()
    {
        $rss = new Rss('测试百度', 'http://www.baidu.com', '这一次我来测试下百度RSS！');
        $rss->setChannelCategory('RSS', 'syndic8');
        $rss->setChannelCloud([
            'domain' => 'www.runoob.com',
            'port' => 80,
            'path' => '/RPC',
            'registerProcedure' => 'NotifyMe',
            'protocol' => 'xml-rpc'
        ]);
        $rss->setChannelImage('http://www.runoob.com', 'http://www.runoob.com/images/logo.png', '菜鸟教程');
        $rss->setChannelLanguage('zh-cn');
        $rss->addChannelSkipDay('星期天');
        $rss->addChannelSkipHour(0);
        $rss->addChannelSkipHour(1);
        $rss->addChannelSkipHour(2);
        $rss->addChannelSkipHour(3);
        $rss->addChannelSkipHour(4);
        $rss->setChannelTextInput('s', '搜索', 'https://www.runoob.com/', '搜索内容');

        $rss->addItem('RSS 教程', 'http://www.runoob.com/rss', '菜鸟教程 Rss 教程');
        $rss->addItem('XML 教程', 'http://www.runoob.com/xml', '菜鸟教程 XML 教程', null, [
            'author' => '411370875@qq.com'
        ]);
        $rss->addItem('HTML 教程', 'http://www.runoob.com/html', '菜鸟教程 HTML 教程', time(), [
            'author' => '411370875@qq.com',
            'category' => [null, ['domain' => 'syndic8']]
        ]);

        $rss->build('./data/rss.xml');

        self::assertTrue(true);
	}
}
