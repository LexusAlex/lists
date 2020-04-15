<?php

declare(strict_types=1);

namespace Lists;

class Lists
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
                $value['meta'] = [];
                $combine_array[$value['parent_id']]['children'][$item] = &$value;
            }
        }

        // возвращаем нужное поддерево
        return array_filter($combine_array, function($val) use ($parent_id) {
            return $val['parent_id'] == $parent_id;
        });
    }
}
