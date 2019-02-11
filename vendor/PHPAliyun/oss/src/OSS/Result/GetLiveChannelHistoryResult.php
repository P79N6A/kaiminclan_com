<?php



require_once __ALIYUN_BASE__.'Model/GetLiveChannelHistory.php';
require_once __ALIYUN_BASE__.'Result/Result.php';

class GetLiveChannelHistoryResult extends Result
{
    /**
     * @return
     */
    protected function parseDataFromResponse()
    {
        $content = $this->rawResponse->body;
        $channelList = new GetLiveChannelHistory();
        $channelList->parseFromXml($content);
        return $channelList;
    }
}
