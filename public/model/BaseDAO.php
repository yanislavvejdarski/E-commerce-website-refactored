<?php


class BaseDAO
{
    public function getPDO()
    {
        return DBManager::getInstance()->getPDO();
    }
}
