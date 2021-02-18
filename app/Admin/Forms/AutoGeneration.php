<?php

namespace App\Admin\Forms;

use App\Models\Cards;
use App\Models\Products;
use Encore\Admin\Widgets\Form;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Classifys;

class AutoGeneration extends Form
{
    /**
     * The form title.
     *
     * @var string
     */
    public $title = '生成卡密';

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        $data = $request->all();
        $rules = array(
            'product_id' => 'required',
            'num' => 'required',
        );
        $messages = ['product_id.required' => '请选择商品', 'num' => '请输入需要生成的卡密数量'];
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return admin_error('提醒', $validator->errors()->first());
        } else {
            $kamiList = [];
            $product_id = $data['product_id'];
            //$created_at = date('Y-m-d H:i:s');
            for ($i = 0; $i < $data['num']; $i++) {
                # code...
                $kamiList[$i]['card_info'] = Uuid::uuid();
                $kamiList[$i]['product_id'] = $product_id;
                $kamiList[$i]['created_at'] = date('Y-m-d H:i:s');
            }
            ksort($kamiList);
            reset($kamiList);

            if ($data['checkm'] == 2) {
                $kamiList = assoc_unique($kamiList, 'card_info');
            }
            $posts = Cards::insert($kamiList);
            if (!$posts) {
                return admin_error('提醒', '导入失败，请检查格式');
            }
        }
        // 增加库存
        Products::where('id', '=', $data['product_id'])->increment('in_stock', count($kamiList));
        admin_success('提醒', '操作成功本次共生成:' . count($kamiList) . '条卡密');

        return redirect(config('admin.route.prefix') . '/cards');
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        //加载所有商品
        // $commodity = Products::where('pd_type', 1)->get(['id', 'pd_name'])->toArray();
        // $commodClass = [];
        // foreach ($commodity as $val) {
        //     $commodClass[$val['id']] = $val['pd_name'];
        // }

        //加载所属类
        $name = Classifys::get(['id', 'name']);
        $nameClass = [];
        foreach ($name as $val) {
            $nameClass[$val['id']] = $val['name'];
        }
        $this->select('tmp_pd_id', __('Belongs to the class'))->options($nameClass)->load('product_id', 'commodity')->rules('required', ['请选择类']);

        //$this->select('product_id', __('Product id'))->options($commodClass)->rules('required', ['请选择商品'])->default(key($commodClass));
        $this->select('product_id', __('Product id'))->rules('required', ['请选择商品']);

        $this->number('num', __('Number of generations'))->rules('required', ['请输入需要生成的卡密数量'])->default(1);
        //$this->textarea('card_info', __('Card info'))->rules('required', ['请输入卡密内容'])->rows(20)->help('一行一个，回车分隔');
        $this->radio('checkm', '是否去掉重复卡密')->options([1 => '否', 2 => '是'])->default(1);
        return $this;
    }

    /**
     * The data of the form.
     *
     * @return array $data
     */
    public function data()
    {
        return [];
    }
}
