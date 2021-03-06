<?php
/**
 * 获取banner图.
 * User: Jett
 * Date: 2019/5/17
 * Time: 9:56 AM
 */
require_once __DIR__ . '/base.php';

class app_dapp_index extends app_dapp_base
{


    public function doRequest()
    {

        $cacheName = "onchain:app_dapp_index:{$this->lang}";
        //$cache = $this->redis()->get( $cacheName );

        if ($cache) {
            $result = $cache;
        } else {
            $mdl_banner = $this->db('index', 'dapps_banner');
            $mdl_games = $this->db('index', 'dapps_games');
            $banner = $mdl_banner->getList(array('title', 'url', 'img', 'flag', 'gid'), array('status' => 1), 'sort desc', 5);

            foreach ($banner as $k => $item) {
                $tmp = unserialize($item['img']);
                $banner[$k]['img'] = $tmp[$this->lang]["img"] ? $this->ossUrl . $tmp[$this->lang]["img"] : $this->ossUrl . 'onchain.default.png';

                //获取游戏列表
                if ($item['gid']) {
                    $game = $mdl_games->get($item['gid']);
                    $banner[$k]['logo'] = $game['ico'] ? $this->ossUrl . $game['ico'] : $this->ossUrl . 'onchain.default.png';

                    $tmp2 = unserialize($game['desc']);
                    $banner[$k]['desc'] = $tmp2[$this->lang];
                    $tmp2 = unserialize($game['name']);
                    $banner[$k]['name'] = $tmp2[$this->lang];
                } else {
                    $banner[$k]['logo'] = $this->ossUrl . 'onchain.default.png';
                    $banner[$k]['desc'] = '';
                    $banner[$k]['name'] = '';
                }
            }
            !$banner && $banner = array();

            $games = $this->getAllGameData();
            $toolGames = $this->getToolData();

            //获取交易所
            $group = $this->getExchange();

            $result["banner"] = $banner;
            $result["dapp"] = $games;
            $result["group"] = $group;
            $result["tool"] = $toolGames;

            $this->redis()->set($cacheName, $result, 1 * 60);
        }

        $this->returnSuccess('', $result);
    }


    private function getGameData($cat)
    {

        $mdl_games = $this->db('index', 'dapps_games');
        $games = $mdl_games->getList(array('id', 'ico', 'coin', 'tag', 'name', 'desc', 'cat', 'url', 'isindex'), array('status' => 1, 'cat' => $cat), 'sort desc', 10);
        $games = $this->handleData($games);
        return $games;
    }

    //首页推荐的dapp 除去工具类
    private function getAllGameData()
    {

        $mdl_games = $this->db('index', 'dapps_games');
        $games = $mdl_games->getList(array('id', 'ico', 'coin', 'name', 'desc', 'cat', 'url', 'isindex'), array('status' => 1, 'isindex' => 1, 'cat<>3'), 'sort desc', 100);
        $games = $this->handleData($games);

        !$games && $games = (object)array();
        return $games;
    }

    //获取工具类dapp
    private function getToolData()
    {
        $mdl_games = $this->db('index', 'dapps_games');
        $games = $mdl_games->getList(array('id', 'ico', 'coin', 'name', 'desc', 'cat', 'url', 'isindex'), array('status' => 1, 'isindex' => 1, 'cat' => 3), 'sort desc', 100);
        $games = $this->handleData($games);
        !$games && $games = (object)array();
        return $games;
    }

    //
    private function getExchange()
    {
        $mdl_exchange = $this->db('index', 'exchange');
        $group = $mdl_exchange->getList(['name', 'fullName', 'logo', 'website'], ['status' => 1, 'inindex' => 1], 'sortnum AES', 10);
        array_walk($group, function (&$item, $key, $lang) {
            foreach ($item as $key => $val) {
                if ($key == 'name' || $key == 'fullName') {
                    $item[$key] = call_user_func(function ($data, $lang) {
                        return $data[$lang];
                    }, json_decode($item[$key], true), $lang);
                } elseif ($key == 'logo') {
                    $item[$key] = $item[$key] ? $this->ossUrl . $item[$key] : $this->ossUrl . 'onchain.default.png';
                }
            }
        }, $this->lang);
        return $group;
    }

}