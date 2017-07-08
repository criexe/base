<?php


class tag
{

    function get($parent_id = null, $limit = null)
    {
        $params          = [];
        $params['type']  = 'tag';
        $params['limit'] = $limit;

        if($parent_id != null) $params['where'] = "(`parents` LIKE '%\"$parent_id\"%') OR (`parents` LIKE '%\'$parent_id\'%')";
        $get = item::get_all($params);
        return $get;
    }

    function get_by_name($tag_name = null)
    {
        $params          = [];
        $params['type']  = 'tag';
        $params['where'] = "`title` = '$tag_name'";

        return item::get($params);
    }

    function insert($parent_id = null, $tag = null)
    {
        try
        {
            if($parent_id == null || $tag == null) throw_exception('Empty params.');

            $get = $this->get_by_name($tag);

            if($get)
            {
                if(in_array($parent_id, $get['parents'])) return true;

                $parents   = $get['parents'] == null ? [] : $get['parents'];
                if(($key   = array_search($parent_id, $parents)) !== false) unset($parents[$key]);
                $parents[] = $parent_id;

                $udata = ['parents' => $parents];
                item::update($udata, ['where' => "`id` = {$get['id']}", 'limit' => 1]);
                return true;
            }
            else
            {
                $insert_data            = [];
                $insert_data['type']    = 'tag';
                $insert_data['parents'] = [$parent_id];
                $insert_data['title']   = $tag;

                item::insert($insert_data) or throw_exception('Error.');
                return true;
            }
        }
        catch(Exception $e)
        {
            return false;
        }
    }

}


?>