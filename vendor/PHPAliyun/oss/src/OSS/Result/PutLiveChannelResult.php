<?php



require_once __ALIYUN_BASE__.'Model/LiveChannelInfo.php';

class PutLiveChannelResult extends Result
{
    protected function parseDataFromResponse()
    {
        $content = $this->rawResponse->body;
        $channel = new LiveChannelInfo();
        $channel->parseFromXml($content);
        return $channel;
    }
}
