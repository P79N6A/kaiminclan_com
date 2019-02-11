<?php

class FaultinessExampleService extends Service {


    /**
     *
     * 反馈信息
     *
     * @return array $where 条件;
     * @return int $start 当前页;
     * @return int $perpage 单页数量;
     * @return string $orderby 排序;
     *
     * @return array 反馈列表;
     */
    public function getExampleList($where = array(),$start = 1,$perpage = 10,$orderby = 'identity desc'){

        $count = $this->model('FaultinessExample')->where($where)->count();
        if($count){
            $selectHandle = $this->model('FaultinessExample')->where($where);
            if($perpage > 0){
                $selectHandle->limit($start,$perpage,$count);
            }
            if($orderby){
                $selectHandle ->order($orderby);
            }
            $listdata = $selectHandle->select();

            $liabilitySubscriberIdentity = $subjectIds = $platformIds = array();
            foreach($listdata as $key=>$data){
                $listdata[$key]['status'] = array(
                    'value'=>$data['status'],
                    'label'=>FaultinessExampleModel::getStatusTitle($data['status'])
                );
                $subjectIds[] = $data['subject_identity'];
                $platformIds[] = $data['platform_identity'];
                $liabilitySubscriberIdentity[] = $data['liability_subscriber_identity'];
                $liabilitySubscriberIdentity[] = $data['subscriber_identity'];
            }

            $platformData = $this->service('ProductionPlatform')->getPlatformInfo($platformIds);
            $subjectData = $this->service('ProjectSubject')->getSubjectInfo($subjectIds);

            $subjectIds = $platformIds = array();
            foreach($listdata as $key=>$data){
                $listdata[$key]['platform'] = isset($platformData[$data['platform_identity']])?$platformData[$data['platform_identity']]:array();
                $listdata[$key]['subject'] = isset($subjectData[$data['subject_identity']])?$subjectData[$data['subject_identity']]:array();

            }

        }

        return array('total'=>$count,'list'=>$listdata);
    }

    /**
     *
     * 反馈信息
     *
     * @param $exampleId 反馈ID
     *
     * @reutrn array;
     */
    public function getExampleInfo($exampleId){

        $exampleData = array();

        $where = array(
            'identity'=>$exampleId
        );

        $exampleList = $this->model('FaultinessExample')->where($where)->select();
        if($exampleList){
            if(!is_array($exampleId)){
                $exampleData = current($exampleList);
            }else{
                $exampleData = $exampleList;
            }

        }

        return $exampleData;
    }

    /**
     *
     * 反馈信息
     *
     * @param $exampleId 反馈ID
     *
     * @reutrn array;
     */
    public function checkExampleTitle($title){


        $where = array(
            'title'=>$title
        );

        return $this->model('FaultinessExample')->where($where)->count();
    }

    /**
     *
     * 删除反馈
     *
     * @param $exampleId 反馈ID
     *
     * @reutrn int;
     */
    public function removeExampleId($exampleId){

        $output = 0;

        $where = array(
            'identity'=>$exampleId
        );

        $exampleData = $this->model('FaultinessExample')->where($where)->select();
        if($exampleData){
            $output = $this->model('FaultinessExample')->where($where)->delete();
        }

        return $output;
    }

    /**
     *
     * 反馈修改
     *
     * @param $exampleId 反馈ID
     * @param $exampleNewData 反馈数据
     *
     * @reutrn int;
     */
    public function update($exampleNewData,$exampleId){
        $where = array(
            'identity'=>$exampleId
        );

        $exampleData = $this->model('FaultinessExample')->where($where)->find();
        if($exampleData){

            $exampleNewData['lastupdate'] = $this->getTime();
            $result = $this->model('FaultinessExample')->data($exampleNewData)->where($where)->save();

            if($exampleNewData['status'] == FaultinessExampleModel::PRODUCTION_DEMAND_STATUS_DEVELOP){
                $exampleNewData['identity'] = $exampleId;
                $this->send($exampleNewData);
            }

        }
        return $result;
    }

    public function getCode($content){
        return md5($content.$this->getClientIp().$this->getDeviceCode());
    }

    /**
     *
     * 检测消息码是否存在
     *
     * @param $code 识别码
     *
     * @reutrn int;
     */
    public function checkCode($code){
        $where = array();
        $where['code'] = $code;
        return $this->model('FaultinessExample')->where($where)->count();
    }

    /**
     *
     * 新反馈
     *
     * @param $exampleNewData 反馈信息
     *
     * @reutrn int;
     */
    public function insert($exampleNewData){
        $exampleNewData['subscriber_identity'] =$this->session('uid');
        $exampleNewData['dateline'] = $this->getTime();

        $exampleNewData['lastupdate'] = $exampleNewData['dateline'];

        $exampleNewData['sn'] = date('Ymd').'-'.mt_rand(1,1000);

        $exampleId = $this->model('FaultinessExample')->data($exampleNewData)->add();
        return $exampleId;
    }
}