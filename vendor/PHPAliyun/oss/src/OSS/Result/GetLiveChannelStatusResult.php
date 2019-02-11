<?php



require_once __ALIYUN_BASE__.'Model/GetLiveChannelStatus.php';
require_once __ALIYUN_BASE__.'Result/Result.php';

class GetLiveChannelStatusResult extends Result
{
    /**
     * @return
     */
    protected function parseDataFromResponse()
    {
        $content = $this->rawResponse->body;
        $channelList = new GetLiveChannelStatus();
        $channelList->parseFromXml($content);
        return $channelList;
    }
}
