<?php

class BaseDAO
{
    /**
     * @return instance
     */
    public function getPDO()
    {
        return DBManager::getInstance()->getPDO();
    }
}