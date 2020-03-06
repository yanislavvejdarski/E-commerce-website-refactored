<?php


namespace model;


class Filter
{
private $filter=[];
public $filterValues;


    public function setFilterNames($filterNames)
    {
        $localArr=[];
        foreach ($filterNames as $filterName) {

            if(!in_array($filterName->name,$localArr)){
                $localArr[]=$filterName->name;
                $filter[]=$filterName->name;
            }
        }
        $this->filter=$localArr;
    }


    public function setFilterValues($filterValues)
    {
        $this->filterValues = $filterValues;
    }

    public function getFilter()
    {
        return $this->filter;
    }


    public function getFilterValues()
    {
        return $this->filterValues;
    }
}