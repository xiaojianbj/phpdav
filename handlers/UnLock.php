<?php
/**
 * Class Handler_UnLock
 * 处理客户端调用UNLOCK 方法发来的解锁指定资源的请求
 */
class Handler_UnLock extends HttpsDav_BaseHander
{
    protected $arrInput = [
        'locktoken' => []
    ];

    /**
     * 执行客户端调用UNLOCK方法发来的对请求资源解锁的任务，并返回数组格式化的执行结果
     * @return array
     * @throws Exception
     */
    protected function handler()
    {
        $objResource = Service_Data_Resource::getInstance(REQUEST_RESOURCE);
        if (empty($objResource) || $objResource->status == Service_Data_Resource::STATUS_FAILED) {
            return ['code' => 503];
        }
        if ($objResource->status == Service_Data_Resource::STATUS_DELETE) {
            return ['code' => 404];
        }
        $arrResult = $objResource->unlock($this->arrInput['locktoken']);
        if (isset(HttpsDav_StatusCode::$message[$arrResult['code']])) {
            return $arrResult;
        }
        return ['code' => 503];
    }

    /**
     * 获取并数组格式化的客户端发来的请求数据
     * @throws Exception
     */
    protected function getArrInput()
    {
        $this->arrInput['locktoken'] = HttpsDav_Request::getLockToken();
        if (empty($this->arrInput['locktoken'])) {
            $this->formatStatus = false;
        }
    }
}