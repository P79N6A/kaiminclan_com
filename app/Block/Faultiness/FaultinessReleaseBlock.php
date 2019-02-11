<?php

class FaultinessReleaseBlock extends Block {

    public function getdata($param){

        $releaseId = isset($param['releaseId'])?$param['releaseId']:0;
        $subjectId = isset($param['subjectId'])?intval($param['subjectId']):0;
        $status = isset($param['status'])?$param['status']:0;

        $perpage = isset($param['perpage'])?intval($param['perpage']):0;
        $start = isset($param['start'])?intval($param['start']):0;

        $mode = isset($param['mode'])?intval($param['mode']):0;

        $where = array();

        $allowedSubjectIds = $this->service('ProjectSubject')->getSubjectIdByUid($this->session('employee_identity'));
        if($subjectId && in_array($subjectId,$allowedSubjectIds)){
            $where['subject_identity'] = $subjectId;
        }else{
            $where['subject_identity'] = $allowedSubjectIds;
        }


        $listdata = $this->service('FaultinessRelease')->getReleaseList($where,$start,$perpage);
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