<?php

namespace Lysice\Region;

use GuzzleHttp\Client;
use Lysice\Region\Exceptions\InvalidArgumentException;
use Lysice\Region\Exceptions\HttpException;
use Illuminate\Support\Facades\DB;

class Region {
    protected $url = 'https://restapi.amap.com/v3/config/district';

    protected $data = [];
    public function __construct()
    {
        $data = config('region');
        if (empty($data)) {
            throw new Exception("please publish the config file first");
        }

        $this->data = $data;
    }

    /**
     * 重新加载数据
     */
    public function region() {
        set_time_limit(10000);

        if (!isset($this->data['key']) or empty($this->data['key'])) {
            throw new InvalidArgumentException('Invalid argument key: not exists!');
        }
        if (!isset($this->data['table']) or empty($this->data['table']) {
            throw new InvalidArgumentException('Invalid argument table: not exists!');
        }
        if (!isset($this->data['connection']) or empty($this->data['connection'])) {
            throw new InvalidArgumentException('Invalid argument connection: not exists!');
        }

        $params = [
            'query' => [
                'key' => $this->data['key'],
                'keywords' => '中国',
                'subdistrict' => 4,
            ]
        ];

        $client = new Client();
        $promise = $client->request('GET', $this->url, $params);
        $response = $promise->getBody()->getContents();

        $datas = json_decode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $time = date("Y-m-d H:i:s");
        $table_name = $this->data['table'];
        $insertData = [];
        $area_path = '';
        foreach ($datas['districts'] as $cindex => $country) {
            $area_path = '中国';
            $id = $cindex + 1;

            $insertData[] = [
                'id' => $id,
                'area_name' => '中国',
                'area_parent_id' => 0,
                'area_level' => 0,
                'area_path' => $area_path,
                'status' => 1,
                'created_at' => $time,
                'updated_at' => $time
            ];;
            $parentId = $cindex + 1;

            foreach ($country['districts'] as $dindex => $district) {
                $dist_area_path = $area_path . '/' . $district['name'];
                $did = $parentId * 100 + $dindex;
                $params =
                $insertData[] = [
                    'id' => $did,
                    'area_name' => $district['name'],
                    'area_parent_id' => $parentId,
                    'area_level' => 1,
                    'area_path' => $dist_area_path,
                    'status' => 1,
                    'created_at' => $time,
                    'updated_at' => $time
                ];

                $dparentId = $did;

                foreach ($district['districts'] as $cityIndex => $city) {
                    $city_area_path = $dist_area_path . '/' . $city['name'];
                    $cid = $dparentId * 100 + $cityIndex;

                    $insertData[] = [
                        'id' => $cid,
                        'area_name' => $city['name'],
                        'area_parent_id' => $dparentId,
                        'area_level' => 2,
                        'area_path' => $city_area_path,
                        'status' => 1,
                        'created_at' => $time,
                        'updated_at' => $time
                    ];
                    $cityParentId =$cid;

                    foreach ($city['districts'] as $blockindex => $block) {
                        $block_area_path = $city_area_path . '/' . $block['name'];

                        $insertData[] = [
                            'id' => $cityParentId * 100 +  $blockindex,
                            'area_name' => $block['name'],
                            'area_parent_id' => $cityParentId,
                            'area_level' => 3,
                            'area_path' => $block_area_path,
                            'status' => 1,
                            'created_at' => $time,
                            'updated_at' => $time
                        ];
                    }
                }
            }
        }

        $table = $this->data['prefix'] . $this->data['table'];
        $chunk_datas = array_chunk($insertData, 100);
        DB::connection($data['connection'])->table($table)->trucate();
        foreach($chunk_datas as $chunk) {
            DB::connection($data['connection'])->table($table)->insert($data);
        }
    }
}
