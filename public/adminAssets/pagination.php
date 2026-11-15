<?php
function paginate($total, $currentPage, $limit, $baseUrl) {
    $maxPage = ceil($total / $limit);
    $pagination = [
        'first' => '',
        'prev' => '',
        'nav' => '',
        'next' => '',
        'last' => '',
    ];

    if ($currentPage > 1) {
        $pagination['first'] = '<li><a href="' . $baseUrl . '?page=1">[First Page]</a></li>';
        $pagination['prev']  = '<li><a href="' . $baseUrl . '?page=' . ($currentPage - 1) . '">[Prev]</a></li>';
    } else {
        $pagination['first'] = '<li class="disabled"><a href="#">[First Page]</a></li>';
        $pagination['prev']  = '<li class="disabled"><a href="#">[Prev]</a></li>';
    }

    /*for ($i = 1; $i <= $maxPage; $i++) {
        $pagination['nav'] .= ($i == $currentPage)
            ? '<li class="active"><a href="#">' . $i . '</a></li>'
            : '<li><a href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
    }*/

    if ($currentPage < $maxPage) {
        $pagination['next'] = '<li><a href="' . $baseUrl . '?page=' . ($currentPage + 1) . '">[Next]</a></li>';
        $pagination['last'] = '<li><a href="' . $baseUrl . '?page=' . $maxPage . '">[Last Page]</a></li>';
    } else {
        $pagination['next'] = '<li class="disabled"><a href="#">[Next]</a></li>';
        $pagination['last'] = '<li class="disabled"><a href="#">[Last Page]</a></li>';
    }

    return $pagination;
}

function paginationView($pagination){
    $view = '<ul class="pagination pagination-sm no-margin pull-right">';
    $view .= $pagination['first'];
    $view .= $pagination['prev'];
    $view .= $pagination['nav'];
    $view .= $pagination['next'];
    $view .= $pagination['last'];
    $view .= '</ul>';

    return $view;
}
