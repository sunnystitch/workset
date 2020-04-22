<?php
/**
 * created by liuanyuan
 * desc:
 * date: 2020/4/22
 * time: 10:00 上午
 */

/**
 * @param $type
 * @param $course_type
 * @param $term
 * @param $grade
 * @param $keyword
 * @param int $page  页码
 * @param int $pagesize  页码总数
 * @return array => array(
 *       "total"  总数
 *       "data"   数据
 *       "pageSize" 当前页码
 *       "total_page" 总页数
 *      );
 *   该代码就是展示 yii 框架 怎么连表  取出数据展示到页面
 */
function joinTableMethod($type, $course_type, $term, $grade, $keyword, $page = 1, $pagesize = 10)
{
    //直播课()
    $select = "ke_croom.id,ke_croom.kid,ke_croom.title,ke_keinfo.type,ke_keinfo.term,ke_keinfo.users,ke_keinfo.online,ke_keinfo.source";
    $query = CroomActiveR::find()
        ->select($select)
        ->where(['ke_clarom.status' => 1])
        ->join('left join', 'ke_kinfo', 'ke_croom.kid = ke_knfo.kid');

    if (!empty($keyword)) {
        $query->andFilterWhere(['like', 'ke_croom.title', $keyword]);
    }
    //添加类别course_type  学期term(1春季班，2暑假班，3秋季班，4寒假班)  grade年级（一年级 二年级....）
    if (!empty($course_type)) {
        $query->andFilterWhere(['ke_keinfo.type' => $course_type]);
    }

    if (isset($term) && ($term >= 0)) {
        $query->andFilterWhere(['ke_keinfo.terms' => $term]);
    }

    if (!empty($grade)) {
        $query->andFilterWhere(['like', 'ke_keinfo.users', $grade]);
    }


    $total = $query->count();
    $total_page = ceil($total / $pagesize);
    $offset = 2;  // 页数起始位置
    $limit = 30;  //取多少条数据
    $data = $query->offset($offset)->limit($limit)->orderBy('ke_classroom.id DESC')->asArray()->all();
    return [
        'total' => $total,
        'data' => $data,
        'page' => $page,
        'pagesize' => $pagesize,
        'total_page' => $total_page,
    ];
}
