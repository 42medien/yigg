<?php
class Category extends BaseCategory
{
    public function getCategorySlug()
    {
        // replace all non letters or digits by -
        $text = preg_replace('/\W+/', '-', $this->getName());

        // trim and lowercase
        $text = strtolower(trim($text, '-'));

        return $text;
    }
}