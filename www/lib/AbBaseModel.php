<?php
use \Phalcon\Mvc\Model;

/**
 * Class AbBaseModel
 */
class AbBaseModel extends Model
{
    /**
     * @param $search
     * @param bool|false $order
     * @return mixed
     */
    public static function search($search, $order=false)
    {
        $clz = get_called_class();
        $query = new AbBaseQuery($clz);
        $count = $query->count();
        $binds = array();
        foreach ($search as $key => $value)
        {
            if ($key == '_url') {
                continue;
            }

            $cb = self::getCondition($key, $value);
            $condition = $cb[0];
            $bind = $cb[1];
            $query->addCondition($condition);

            $binds = array_merge($binds, $bind);

        }

        if (method_exists($clz, 'beforeSearch'))
        {
            $clz::onSearch($query);
        }

        $params = array('binds' => $binds);
        if ($order) {
            $params['order'] = $order;
        }

        $items = $query->execute($params);
        return array(
            'count' => $count,
            'items' => $items
        );
    }

    public static function getCondition($key, $value)
    {
        return array("{$key}=:{$key}:", array($key => $value));
    }
}