<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class InstallDuJiaoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dujiao {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '独角数卡安装命令';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $action = $this->argument('action');
        switch ($action) {
            case 'install' :
                $this->installDujiao();
                break;
            case 'update' :
                $this->updateDujiao();
                break;
        }
    }

    /**
     * 导入sql方法.
     */
    public function installDujiao()
    {
        $this->info("=====独角数卡安装环境检测开始=====");
        $sqlPath = database_path() . '/sql/install.sql';
        $this->info("正在导入数据库...");
        DB::unprepared(file_get_contents($sqlPath));
        $this->info("导入成功...");
    }

    /**
     * 更新版本sql.
     */
    public function updateDujiao()
    {
        $filename = "update.sql";
        $sqlPath = database_path() . '/sql/' . $filename;
        if (!file_exists($sqlPath)) return $this->error('更新文件不存在！');
        try {
            DB::unprepared(file_get_contents($sqlPath));
        } catch (QueryException $queryException) {
            if ($queryException->getCode() == "42S21") {
                return $this->info("更新成功...");
            }
            return $this->error($queryException->getMessage());
        }

        $this->info("更新成功...");
    }



}
