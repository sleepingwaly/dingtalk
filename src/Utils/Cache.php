<?php
namespace Dingtalk\Utils;

class Cache
{
    public static function set($key, $value, $file=dirname(__FILE__).'/filecache.php')
    {
        if($key && $value){
            $data = json_decode($this->get_file($file),true);
            $item = array();
            $item["$key"] = $value;

            $keyList = array('isv_corp_access_token','suite_access_token','js_ticket','corp_access_token');
            if(in_array($key,$keyList)){
                $item['expire_time'] = time() + 7000;
            }else{
                $item['expire_time'] = 0;
            }
            $item['create_time'] = time();
            $data["$key"] = $item;
            $this->set_file($file,json_encode($data));
        }
    }

    public static function get($key, $file=dirname(__FILE__).'/filecache.php')
    {
        if($key){
            $data = json_decode($this->get_file($file),true);
            if($data && array_key_exists($key,$data)){
                $item = $data["$key"];
                if(!$item){
                    return false;
                }
                if($item['expire_time']>0 && $item['expire_time'] < time()){
                    return false;
                }

                return $item["$key"];
            }
            return false;
        }
        return false;
    }

    private function get_file($filename)
    {
        if (!file_exists($filename)) {
            $fp = fopen($filename, "w");
            fwrite($fp, "<?php exit();?>" . '');
            fclose($fp);
            return false;
        }else{
            $content = trim(substr(file_get_contents($filename), 15));
        }
        return $content;
    }

    private function set_file($filename, $content)
    {
        $fp = fopen($filename, "w");
        fwrite($fp, "<?php exit();?>" . $content);
        fclose($fp);
    }
}