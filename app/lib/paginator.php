<?php


namespace Framework\lib;


class Paginator
{
    private $_page;
    private $_per_page;
    private $_total;

    public function __construct($page, $per_page, $total)
    {
        $this->_page = $page;
        $this->_per_page = $per_page;
        $this->_total = $total;
    }

    public function paginate() {
        $links = 4;


        $last = ceil( $this->_total / $this->_per_page );

        $start = ( ( $this->_page - $links ) > 0 ) ? $this->_page - $links : 1;
        $end = ( ( $this->_page + $links ) < $last ) ? $this->_page + $links : $last;

        $html = "<nav class='pagination-nav'>";
        $html .= "<span class=''>Showing ".(((($this->_page * $this->_per_page) - $this->_per_page) == 0) ? '1' : ($this->_page * $this->_per_page) - $this->_per_page).' to '.((($this->_page * $this->_per_page) > $this->_total) ? $this->_total : ($this->_page * $this->_per_page)).' of '.$this->_total." entries</span>";

        $html   .= "<ul class=\"pagination\">";


        if ($this->_page != 1) {
            $html   .= "<li class=\"page-item\">
                            <a class='page-link' href=\"?limit=".$this->_per_page."&page=".($this->_page - 1)."\" tabindex='-1'>Previous</a>
                        </li>";
        }
        if ($start > 1) {
            $html   .= '<li class="page-item"><a class="page-link" href="?limit=' . $this->_per_page . '&page=1">1</a></li>';
            $html   .= '<li class="disabled"><a class="page-link" href="#">...</a></li>';
        }
        for ($i = $start ; $i <= $end; $i++) {
            $html   .= '<li class="page-item '.(( $this->_page == $i ) ? "active" : "").'">
                            <a class="page-link" href="?limit=' . $this->_per_page . '&page=' . $i . '">' . $i .(( $this->_page == $i ) ? "<span class=\"sr-only\">(current)</span>" : "").'</a></li>
                        </li>';
        }
        if ($end < $last) {
            $html   .= '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
            $html   .= '<li class="page-item"><a class="page-link" href="?limit=' . $this->_per_page . '&page=' . $last . '">' . $last . '</a></li>';
        }

        if ($this->_page == $last) {
            $html   .= '<li class="page-item"><a class="page-link" href="'.(( $this->_page == $last ) ? "#" : '?limit='.$this->_per_page.'&page='.( $this->_page + 1 )).'">Next</a></li>';
        }

        $html       .= '</nav>';

        return $html;
    }

}