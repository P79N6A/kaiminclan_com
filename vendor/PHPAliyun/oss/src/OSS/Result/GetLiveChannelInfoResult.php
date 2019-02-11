<?php



require_once __ALIYUN_BASE__.'Model/GetLiveChannelInfo.php';
require_once __ALIYUN_BASE__.'Result/Result.php';

class GetLiveChannelInfoResult extends Result
{
    /**
     * @return
     */
    protected function parseDataFromResponse()
    {
        $content = $this->rawResponse->body;
        $channelList = new GetLiveChannelInfo();
        $channelList->parseFromXml($content);
        return $channelList;
    }
}
