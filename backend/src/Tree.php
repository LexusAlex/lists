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

    public function flatToTree2(array $flat_array, $parent_id = 0, &$result = []) {

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
    }

    public function createTree(&$list, $parent){
        $tree = array();
        foreach ($parent as $k => $l){
            if(isset($list[$l['id']])){
                $l['children'] = $this->createTree($list, $list[$l['id']]);
            }
            $tree[] = $l;
        }
        return $tree;
    }

    public function build_tree($flat, $pidKey, $idKey = null)
    {
        $grouped = array();
        foreach ($flat as $sub) {
            $grouped[$sub[$pidKey]][] = $sub;
        }

        $fnBuilder = function ($siblings) use (&$fnBuilder, $grouped, $idKey) {
            foreach ($siblings as $k => $sibling) {
                $id = $sibling[$idKey];
                if (isset($grouped[$id])) {
                    $sibling['children'] = $fnBuilder($grouped[$id]);
                }
                $siblings[$k] = $sibling;
            }
            return $siblings;
        };
        $tree = $fnBuilder($grouped[null]);
        return $tree;
    } // end of build_tree.

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

