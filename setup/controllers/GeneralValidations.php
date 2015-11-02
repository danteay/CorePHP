<?php


class GeneralValidations{

    public function isNumeric($case){
        return (preg_match("/int/",$case) || preg_match("/dec/",$case) || preg_match("/numeric/",$case) || preg_match("/real/",$case) || preg_match("/float/",$case) || preg_match("/double/",$case) || preg_match("/precision/",$case));
    }

}
