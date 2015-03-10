<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 10.03.15
 * @time 5:42
 * Created by JetBrains PhpStorm.
 */

namespace app\models\library;

trait BooksTrait
{
    public function getBooksList()
    {
        $out = [];
        foreach($this->books as $book){
            $out[] = ['id'=>$book->id,'text'=>$book->name];
        }
        return $out;
    }
}
