<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class CustomerController extends Controller {
    public function index($draw, $columns, $start, $length, $order, $search) {
        $column = $order[0]['column'];
        $order_name = $columns[$column]['data'];
        $dir = $order[0]['dir'];
        $customers = D('Customer')->getCustomer($start, $length, $order_name.' '.$dir);
        $data['draw'] = $draw;
        $data['recordsTotal'] = D('Customer')->getCount();
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $customers;
        $this->ajaxReturn($data);
    }

    public function crud($action = 'none', $data = null, $id = null) {
        $result['fieldErrors'] = array();
        switch($action) {
        case 'create':
            $c = D('Customer')->where('name=\''.$data['name'].'\'')->find();
            if($c != null) {
                array_push($result['fieldErrors'], array('name' => 'name', 'status' => '客户名称已存在'));
            }
        case 'edit':
            if(!is_numeric($data['longitude'])) {
                array_push($result['fieldErrors'], array('name' => 'longitude', 'status' => '经度错误'));
            }
            if(!is_numeric($data['latitude'])) {
                array_push($result['fieldErrors'], array('name' => 'latitude', 'status' => '纬度错误'));
            }
            if(!empty($result['fieldErrors'])) {
                $this->ajaxReturn($result);
            }
            break;
        default:
            break;
        }
        switch($action) {
        case 'edit':
            D('Customer')->where('id='.$id)->save($data);
            $data['id'] = $id;
            $result['row'] = $data;
            $this->ajaxReturn($result);
            break;
        case 'create':
            $id = D('Customer')->add($data);
            $data['id'] = $id;
            $result['row'] = $data;
            $this->ajaxReturn($result);
            break;
        case 'remove':
            D('Customer')->delete(join(',', $id));
            $result['id'] = $id;
            $this->ajaxReturn($result);
            break;
        default:
            break;
        }
    }
}
