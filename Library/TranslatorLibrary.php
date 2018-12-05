<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-5-18 0018
 * Time: 18:21
 */
class TranslatorLibrary{

    //翻译
    public function make_translator($user_lang,$word)
    {
        $en = (int)$user_lang;
        if($en != 0)
        {
            $en = 1;
        }else{
            $en = 0;
        }

        $translatorCollect = config('Translator');
        if(!$translatorCollect && !is_array($translatorCollect))
        {
            return '';
        }

        $makeYield = function()  use($translatorCollect)
        {
            foreach ($translatorCollect as $key=>$value)
            {
                yield $value;
            }
        };

        foreach ($makeYield() as $key=>$value)
        {
            if($value[0] == $word)
            {
                return $value[$en];
            }
        }
        return '';
    }

}