<?php

function buildCategoryTree(array $categories, ?int $parentId = null, string $prefix = '') : string {
    $html = '';

    foreach ($categories as $category)
    {
        if ($category->parent_id === $parentId)
        {
            $select = old('parent_id') == $category->id ? 'selected' : '';
            $html .= "<option " . $select . " class='text-xs md:text-md' value=\"{$category->id}\">{$prefix}{$category->name}</option>";
            

            $html .= buildCategoryTree(
                $categories,
                $category->id,
                $prefix . '-- '
            );
        }
    }

    return $html;
}

function errorClass($name) {
    return errorExist($name) ? ['class_error' => '', 'message_error' => error($name)] : ['class_error' => 'hidden', 'message_error' => ''];
}

function buildCategoryTreeSelect($categories, $pi) {

    foreach ($categories as $category)
    {
        if ($category->id == $pi)
        {
            return '<option selected value="' . $category->id . '" class="text-xs lg:text-md">' . $category->name . '</option>';
        }
    }

}

function buildCategoryTreeEdit($pid , $mid , array $categories, ?int $parentId = null, string $prefix = '') : string {
    $html = '';

    foreach ($categories as $category)
    {
        if ($category->id != $mid AND $category->id != $pid )
        {
            if ($category->parent_id === $parentId)
            {
                $select = old('parent_id') == $category->id ? 'selected' : '';
                $html .= "<option \"{$select}\ class='text-xs md:text-lg' value=\"{$category->id}\">{$prefix}{$category->name}</option>";

                $html .= buildCategoryTreeEdit(
                    $pid,
                    $mid,
                    $categories,
                    $category->id,
                    $prefix . '-- '
                );
            }
        }else{
            continue;
        }
    }

    return $html;
}