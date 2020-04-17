<?php

declare(strict_types=1);

namespace Lists;

class Tree
{
    /**
     * @param array $flat_array
     * @param int $parent_id
     * @return array
     */
    public function flatToTree(array $flat_array, int $parent_id = 0) {

        $combine_array = array_combine(array_column($flat_array, 'id'), array_values($flat_array));

        // меняем исходный массив добавляя дочерние элементы
        foreach ($combine_array as $item => &$value) {
            if (isset($combine_array[$value['parent_id']])) {
                $combine_array[$value['parent_id']]['children'][$item] = &$value;
            }
        }

        // возвращаем нужное поддерево
        return array_filter($combine_array, function($val) use ($parent_id) {
            return $val['parent_id'] == $parent_id;
        });
    }

    public function flatToTree2(array $flat_array, $parent_id = 0) {

        /*
        return array_map(function (&$key ,$item) {
            $key = $item['id'];
            return $item;
        },array_keys($flat_array),$flat_array);
        */

        /*
        return array_reduce($flat_array, function ($acc, &$item){

            return $acc;
        },[]);
        */

        $tree = array();

        foreach ($flat_array as $id => &$node) {
            if ($node['parent_id'] == ''){
                $tree[$node['parent_id']] =&$node;
            }  else {
                $tree[$node['parent_id']]['children'][$node['id']]  = &$node;
            }
        }
        return $tree;
    }
}
