<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once JPATH_THEMES.DS.'lemond/html'.DS.'simple_html_dom.php';

function pagination_list_render($list)
{
    if(JRequest::getVar('option')=='com_virtuemart') return pagination_list_virtuemart($list);
    if(JRequest::getVar('option')=='com_content') return pagination_list_content($list);

}
function pagination_list_virtuemart($list)
{
        $first=str_get_html($list['start']['data']);
    
    $first->find('.pagenav',0)->innertext='<img src="templates/lemond/images/'.
            ($list['start']['active'] ? 'first-active.png"/>' : 
                'first-inactive.png"/>');
    $prev=str_get_html($list['previous']['data']);
    $prev->find('.pagenav',0)->innertext='<img src="templates/lemond/images/'.
            ($list['previous']['active'] ? 'prev-active.png"/>' : 
                'prev-inactive.png"/>');
    $next=str_get_html($list['next']['data']);
    $next->find('.pagenav',0)->innertext='<img src="templates/lemond/images/'.
            ($list['next']['active'] ? 'next-active.png"/>' : 
                'next-inactive.png"/>');
    $last=str_get_html($list['end']['data']);
    $last->find('.pagenav',0)->innertext='<img src="templates/lemond/images/'.
            ($list['end']['active'] ? 'last-active.png"/>' : 
                'last-inactive.png"/>');
    $all=str_get_html($list['all']['data']);
    $all->find('.pagenav',0)->innertext='Показать все';	
    $current_page=1;
    $pages_num=count($list['pages']);
    for($i=1;isset($list['pages'][$i]);++$i)
    {
        if(!$list[$i]['active']){
            $current_page=$i;
            break;
        }
    }
    $first_page=max($current_page-1,1);
    $last_page=min($first+4,$pages_num);
    $content='<p class="p-first"><span class="span-prev"><span class="first">'.$first->save().'</span>';
    $content.='<span class="prev">'.$prev->save().'</span></span>';
    $content.='<span class="span-list">'.$list['pages'][1]['data'].($first_page>1 ? '...' : '');
    for($i=max($first_page,2);$i<=min($last_page,$pages_num-1);++$i)
    {
        $content.=$list['pages'][$i]['data'];
    }
    $content.=($last_page<$pages_num ? '...' : '').$list['pages'][$pages_num]['data'].'</span>';
    $content.='<span class="span-next"><span class="next">'.$next->save().'</span><span class="last">'.$last->save().'</span></span>';
   // $content.='<p class="p-second">'.$list['all']['data'];
   
	$content.='<p class="p-second">'.$all->save();
//$content.='<p>'.$list[]
    return $content;
}

function pagination_list_content($list)
{
        $first=str_get_html($list['start']['data']);
    
    $first->find('.pagenav',0)->innertext='<img src="templates/lemond/images/'.
            ($list['start']['active'] ? 'first-active.png"/>' : 
                'first-inactive.png"/>');
    $prev=str_get_html($list['previous']['data']);
    $prev->find('.pagenav',0)->innertext='<img src="templates/lemond/images/'.
            ($list['previous']['active'] ? 'prev-active.png"/>' : 
                'prev-inactive.png"/>');
    $next=str_get_html($list['next']['data']);
    $next->find('.pagenav',0)->innertext='<img src="templates/lemond/images/'.
            ($list['next']['active'] ? 'next-active.png"/>' : 
                'next-inactive.png"/>');
    $last=str_get_html($list['end']['data']);
    $last->find('.pagenav',0)->innertext='<img src="templates/lemond/images/'.
            ($list['end']['active'] ? 'last-active.png"/>' : 
                'last-inactive.png"/>');
    $all=str_get_html($list['all']['data']);
    $all->find('.pagenav',0)->innertext='Архив';
        $all->find('.pagenav',0)->href='/arhiv.html';
    $current_page=1;
    $pages_num=count($list['pages']);
    for($i=1;isset($list['pages'][$i]);++$i)
    {
        if(!$list[$i]['active']){
            $current_page=$i;
            break;
        }
    }
    $first_page=max($current_page-1,1);
    $last_page=min($first+4,$pages_num);
    $content='<p class="p-first"><span class="span-prev"><span class="first">'.$first->save().'</span>';
    $content.='<span class="prev">'.$prev->save().'</span></span>';
    $content.='<span class="span-list">'.$list['pages'][1]['data'].($first_page>1 ? '...' : '');
    for($i=max($first_page,2);$i<=min($last_page,$pages_num-1);++$i)
    {
        $content.=$list['pages'][$i]['data'];
    }
    $content.=($last_page<$pages_num ? '...' : '').$list['pages'][$pages_num]['data'].'</span>';
    $content.='<span class="span-next"><span class="next">'.$next->save().'</span><span class="last">'.$last->save().'</span></span>';
   // $content.='<p class="p-second">'.$list['all']['data'];
   
	$content.='<p class="p-second">'.$all->save();
//$content.='<p>'.$list[]
    return $content;
}

?>
