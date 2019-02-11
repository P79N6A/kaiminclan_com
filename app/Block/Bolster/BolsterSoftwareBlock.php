<?php

class BolsterSoftwareBlock extends Block {
	
	public function getdata($param){

        $keyword = isset($param['kw'])?$param['kw']:0;

        $softwareId = isset($param['softwareId'])?$param['softwareId']:0;
        $subjectId = isset($param['subjectId'])?intval($param['subjectId']):0;

        $perpage = isset($param['perpage'])?$param['perpage']:0;
        $start = isset($param['start'])?$param['start']:0;
        $status = isset($param['status'])?$param['status']:0;

        $mode = isset($param['mode'])?intval($param['mode']):0;

        $order = 'identity desc';
        $where = array();

        $allowedSubjectIds = $this->service('ProjectSubject')->getSubjectIdByUid($this->session('employee_identity'));
        if($subjectId && in_array($subjectId,$allowedSubjectIds)){
            $where['subject_identity'] = $subjectId;
        }else{
            $where['subject_identity'] = $allowedSubjectIds;
        }
        if($status != -1){
            $where['status'] = $status;
        }
        if($softwareId){
            $where['identity'] = $softwareId;
        }


        $listdata = $this->service('BolsterSoftware')->getSoftwareList($where,$start,$perpage,$order);
        if($listdata['total'] > 0 && $perpage == 1){
            $listdata['list'] = current($listdata['list']);
        }

        return array(
            'data'=>$listdata['list'],
            'total'=>$listdata['total'],
            'start'=>$start,
            'perpage'=>$perpage
        );
	}
}