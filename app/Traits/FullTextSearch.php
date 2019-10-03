<?php

namespace App\Traits;
use DB;

trait FullTextSearch
{
    /**
     * Replaces spaces with full text search wildcards
     *
     * @param string $term
     * @return string
     */
    protected function fullTextWildcards($term,$operator)
    {
        $operator = strtolower($operator);
        // removing symbols used by MySQL
        $reservedSymbols = ['-',
            '+',
            '<',
            '>',
            '@',
            '(',
            ')',
            '~',
            '（',
            '）',
            '・',
            '*',
            //'ー',
            '－',
            '&',
            '＆',
            '　',
        ];
        #$reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~','・','*','－'];

        if($operator =='exact'){
            return $this->exact_search(trim($term));
        }

        $term = trim(str_replace($reservedSymbols, ' ', $term));
        if($operator == 'or'){
            return $this->or_search($term);
        }


        return $this->default_search($term);
    }

    private function exact_search($term)
    {
        return '"'.$term.'"';
    }

    private function or_search($term)
    {
        //replace 2 byte comma character to 1 byte comma character
        $term = str_replace('，',',',$term);
        $term = str_replace('、',',',$term);

        $terms = explode(',',$term);
        if(count($terms) == 1){
            return '"'.$term.'"';
        }

        $searchTerm = [];
        foreach($terms as $search_term){
            $searchTerm[] = '"'.$search_term.'"';
        }
        return implode( ' ', $searchTerm);
    }

    private function default_search($term)
    {
        $words = array_filter(explode(' ', $term));
        if(count($words) == 1){
            return '"'.$term.'"';
        }

        foreach($words as $key => $word) {
            if(strlen($word) == 0){
                continue;
            }
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if(strlen($word) > 3) {
                $word = '+"'.$word.'"';
            }
            $words[$key] = $word;
        }

        return implode(' ', $words);
    }

    /**
     * Scope a query that matches a full text search of term.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFullText($query, $term,$searchables,$operator='plus')
    {
        $columns = implode(',',$searchables);

        //$query->whereRaw("MATCH ({$columns}) AGAINST (".$this->fullTextWildcards($term,$operator)." IN BOOLEAN MODE)");
        $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)" , DB::RAW( $this->fullTextWildcards($term,$operator)));

        return $query;
    }

    public function relevanceToSql($name,$term,$searchables,$operator='plus')
    {
        $columns = implode(',',$searchables);

        $clause = "MATCH ({$columns}) AGAINST ('".$this->fullTextWildcards($term,$operator)."') as `${name}`";
        return $clause;
    }
}
