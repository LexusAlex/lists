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
    public function flatToTree1(array $flat_array, int $parent_id = 0) {

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

    /**
     * $new = array();
        foreach ($db->all() as $a){
        $new[$a['parent_id']][] = $a;
        }
        $t = $tree->flatToTree2($new, $new[1]);
     * @param $list
     * @param $parent
     * @return array
     */
    public function flatToTree2(&$list, $parent){
        $tree = array();
        foreach ($parent as $k => $l){
            if(isset($list[$l['id']])){
                $l['children'] = $this->flatToTree2($list, $list[$l['id']]);
            }
            $tree[] = $l;
        }
        return $tree;
    }

    public function buildTree(array $flat,
                              string $pidKey = 'parent_id',
                              string $idKey = 'id',
                              string $sibKey = 'children',
                              string $typeKey = 'type',
                              $parent_id_start = null
    ) : array
    {
        // Группируем по родительским элементам
        // при необходимости в качестве ключей можно выставить id элемента в качестве ключа $sub['id']
        $grouped = [];
        foreach ($flat as $value) {
            $grouped[$value[$pidKey]][] = $value;
        }

        // Воспользуемся функцией высшего порядка вызывая ее на каждом элементе, которая будет рекурсивной
        $fnBuilder = function ($siblings) use (&$fnBuilder, $grouped, $idKey, $sibKey, $typeKey) {
            foreach ($siblings as $k => $sibling) {
                $id = $sibling[$idKey];
                if (isset($grouped[$id])) {
                    $sibling[$sibKey] = $fnBuilder($grouped[$id]);
                } else {
                    if ($sibling[$typeKey] == 0) {
                        $sibling[$sibKey] = [];
                    }
                }
                $siblings[$k] = $sibling;
            }
            return $siblings;
        };
        // Вызываем функцию с нужного ключа
        return $fnBuilder($grouped[$parent_id_start]);
    }

    function reduce($func, $tree, $accumulator)
    {
        $reduce = function ($f, $node, $acc) use (&$reduce) {
            $children = $node['children'] ?? [];
            $newAcc = $f($acc, $node);

            //if ($node['type'] == 1) {
            //    return $newAcc;
            //}

            return array_reduce(
                $children,
                function ($iAcc, $n) use (&$reduce, &$f) {
                    return $reduce($f, $n, $iAcc);
                },
                $newAcc
            );
        };

        return $reduce($func, $tree, $accumulator);
    }
}

