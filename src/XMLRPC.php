<?php

namespace fize\xml;

/**
 * XMLRPC
 */
class XMLRPC
{
    /**
     * @var resource XMLRPC服务
     */
    private $server;

    /**
     * 将 XML 译码为 PHP 本身的类型
     * @param string      $xml      XMLRPC请求体
     * @param string      $method   返回方法名
     * @param string|null $encoding 指定编码
     * @return mixed 返回参数列表
     */
    public static function decodeRequest(string $xml, string &$method, string $encoding = null)
    {
        return xmlrpc_decode_request($xml, $method, $encoding);
    }

    /**
     * 将 XML 译码为 PHP 本身的类型
     * @param string $xml      待译码XML
     * @param string $encoding 指定编码
     * @return mixed
     */
    public static function decode(string $xml, string $encoding = "iso-8859-1")
    {
        return xmlrpc_decode($xml, $encoding);
    }

    /**
     * 为 PHP 的值生成 XML
     * @param string $method         方法名称
     * @param mixed  $params         参数
     * @param array  $output_options 输出选项
     * @return string 完整XML
     */
    public static function encodeRequest(string $method, $params, array $output_options = []): string
    {
        return xmlrpc_encode_request($method, $params, $output_options);
    }

    /**
     * 为 PHP 的值生成 XML
     * @param mixed $value 值
     * @return string 完整XML
     */
    public static function encode($value): string
    {
        return xmlrpc_encode($value);
    }

    /**
     * 为 PHP 的值获取 xmlrpc 的类型
     * @param mixed $value 值
     * @return string 值类型
     */
    public static function getType($value): string
    {
        return xmlrpc_get_type($value);
    }

    /**
     * 确定数组的值代表一个 XMLRPC 故障
     * @param array $arg
     * @return bool
     */
    public static function isFault(array $arg): bool
    {
        return xmlrpc_is_fault($arg);
    }

    /**
     * 将 XML 译码成方法描述的列表
     * @param $xml
     * @return array
     */
    public static function parseMethodDescriptions($xml): array
    {
        return xmlrpc_parse_method_descriptions($xml);
    }

    /**
     * 添加自我描述的文档
     * @param array $desc
     * @return int
     */
    public function addIntrospectionData(array $desc): int
    {
        return xmlrpc_server_add_introspection_data($this->server, $desc);
    }

    /**
     * 解析 XML 请求同时调用方法
     * @param       $xml
     * @param       $user_data
     * @param array $output_options
     * @return string
     */
    public function callMethod($xml, $user_data, array $output_options = []): string
    {
        return xmlrpc_server_call_method($this->server, $xml, $user_data, $output_options);
    }

    /**
     * 创建一个 xmlrpc 服务端
     * @return resource
     */
    public function create()
    {
        $this->server = xmlrpc_server_create();
        return $this->server;
    }

    /**
     * 销毁服务端资源
     * @return bool
     */
    public function destroy(): bool
    {
        return xmlrpc_server_destroy($this->server);
    }

    /**
     * 注册一个 PHP 函数用于生成文档
     * @param string $function
     * @return bool
     */
    public function registerIntrospectionCallback(string $function): bool
    {
        return xmlrpc_server_register_introspection_callback($this->server, $function);
    }

    /**
     * 注册一个 PHP 函数用于匹配 xmlrpc 方法名
     * @param string $method_name
     * @param string $function
     * @return bool
     */
    public function registerMethod(string $method_name, string $function): bool
    {
        return xmlrpc_server_register_method($this->server, $method_name, $function);
    }

    /**
     * 为一个 PHP 字符串值设置 xmlrpc 的类型、base64 或日期时间
     * @param mixed  $value
     * @param string $type
     * @return bool
     */
    public static function setType(&$value, string $type): bool
    {
        return xmlrpc_set_type($value, $type);
    }
}
