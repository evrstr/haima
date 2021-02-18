<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use App\Models\Cards;
use App\Models\Products;
use App\Models\Classifys;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * 模板渲染.
     * @param string $tpl
     * @param array $data
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function view($tpl = "", $data = [])
    {
        $tpl = config('webset.tpl_sign') . '/' . $tpl;
        return view($tpl, $data);
    }

    /**
     * 错误模板渲染.
     * @param string $content
     * @param string $url
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function error($content = "content", $url = "")
    {
        $tpl = config('webset.tpl_sign') . '/errors/error';
        return view($tpl, ['title' => __('prompt.error_title'), 'content' => $content, 'url' => $url]);
    }

    /**添加参数校验
     *测试get请求
     * @param $name
     */
    public function test(Request $request, $name)
    {
        //$env = App::environment();        //环境变量
        $res =  ['name' => $name];
        return response()->json($res);
    }




    /**
     * 测试post请求
     * @param $name $age
     */
    public function checkV1(Request $request)
    {

        // $name = $request->input('name');
        // $age = $request->input('age');
        // $card = Cards::query()->where('id', 6)->first();
        // $result['name'] = $name . $age . '--';
        // $result['card'] = $card;
        // return $result;
        // return response()->json($result);

        //客户端发送过来的消息
        $card = $request->input('card');
        $uuid = $request->input('uuid');
        $clicent_time = $request->input('time');
        $sign = $request->input('sign');
        //return $request;
        // 判断是否为空参数
        if ($uuid != null) {
            //查询卡密
            $card = Cards::where('card_info', $card)->first();
            $nowtime = time();
            if ($card != null & abs($nowtime - $clicent_time) < 360) {

                $products = Products::where('id', $card['product_id'])->first();
                $classifys = Classifys::where('id', $products['pd_class'])->first();
                switch ($card['check_status']) {
                    case 0:
                        # code...
                        //构造返回数据
                        $result['msg'] = '验证失败！卡密过期';
                        $result['code'] = 2000;
                        $result['time'] = $nowtime;
                        $data['check'] = false;
                        $data['time'] = $clicent_time;
                        $data['status'] = $card['check_status'];
                        $result['data'] = $data;
                        //对数据签名
                        $result['sign'] = md5($card['card_info'] . $clicent_time . $classifys['appid'] . $data['check'] . $data['time'] . $data['status']);
                        //返回json数据
                        return response()->json($result);
                        break;
                    case 1: //使用中
                        # code...
                        if ($uuid == $card['machine_uuid']) {
                            //构造返回数据
                            $result['msg'] = '验证成功!';
                            $result['code'] = 2010;
                            $result['time'] = $nowtime;
                            $data['check'] = true;
                            $data['time'] = $clicent_time;
                            $data['status'] = $card['check_status'];
                            $result['data'] = $data;
                            //对数据签名
                            $result['sign'] = md5($card['card_info'] . $data['time'] . $classifys['appid'] . $data['check'] . $data['time'] . $data['status']);
                            //返回json数据
                            return response()->json($result);
                        } else {
                            //构造返回数据
                            $result['msg'] = '验证失败！解绑机器请联系管理员！';
                            $result['code'] = 2011;
                            $result['time'] = $nowtime;
                            $data['check'] = true;
                            $data['time'] = $clicent_time;
                            $data['status'] = $card['check_status'];
                            $result['data'] = $data;
                            //对数据签名
                            $result['sign'] = md5($card['card_info'] . $data['time'] . $classifys['appid'] . $data['check'] . $data['time'] . $data['status']);
                            //返回json数据
                            return response()->json($result);
                        }
                        break;
                    case 2: //未使用
                        # code...
                        //构造返回数据
                        $result['msg'] = '验证成功! 首次激活！';
                        $result['code'] = 2020;
                        $result['time'] = $nowtime;
                        $data['check'] = true;
                        $data['time'] = $clicent_time;
                        $data['status'] = $card['check_status'];
                        $result['data'] = $data;
                        //对数据签名
                        $result['sign'] = md5($card['card_info'] . $data['time'] . $classifys['appid'] . $data['check'] . $data['time'] . $data['status']);
                        //更新数据库
                        Cards::where('card_info', $card['card_info'])->update(['check_status' => 1, 'check_stime' => $nowtime, 'check_etime' => $nowtime + $products['usage_days'] * 24 * 60 * 60, 'machine_uuid' => $uuid]);
                        //返回json数据
                        return response()->json($result);
                        break;
                    case 3:
                        # code...
                        //构造返回数据
                        $result['msg'] = '验证失败！卡密异常，请联系管理员！';
                        $result['code'] = 2031;
                        $result['time'] = $nowtime;
                        $data['check'] = false;
                        $data['time'] = $clicent_time;
                        $data['status'] = $card['check_status'];
                        $result['data'] = $data;
                        //对数据签名
                        $result['sign'] = md5($card['card_info'] . $data['time'] . $classifys['appid'] . $data['check'] . $data['time'] . $data['status']);
                        //返回json数据
                        return response()->json($result);
                        break;
                    default:
                        # code...
                        break;
                }
            }
            //构造返回数据
            $result['msg'] = '客户端异常！请重新下载！';
            $result['code'] = 2100;
            $result['time'] = $nowtime;
            $data['check'] = false;
            $data['time'] = $clicent_time;
            $data['status'] = $card['check_status'];
            $result['data'] = $data;
            //对数据签名
            $result['sign'] = md5($card['card_info'] . $data['time']);
            //返回json数据
            return response()->json($result);
        }
        //构造返回数据
        $result['msg'] = '客户端异常！请重新下载！';
        $result['code'] = 2200;
        $result['time'] = time();
        $data = [];
        $result['data'] = $data;
        //对数据签名
        $result['sign'] = md5($result['time']);
        return response()->json($result);
        //查询数据库
    }
}
