<?php
namespace Wechat\Model;
use Think\Model;
class CheckinConfigModel extends Model {
    public function getLocations($userId) {
        $locations = array();
        $where['userId'] = $userId;
        $ids = $this->where($where)->select();
        if($ids !== null) {
            $checkinLocation = M('CheckinLocation');
            foreach($ids as $id) {
                $location = $checkinLocation->find($id);
                if($location !== null) {
                    array_push($locations, $location);
                }
            }
        }
        return $locations;
    }
}
