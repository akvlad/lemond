<?php
/**
* @package Joomla! 2.5
* @version 4.x
* @author 2008-2012 (c)  Denys Nosov (aka Dutch)
* @author web-site: www.joomla-ua.org
* @copyright This module is licensed under a Creative Commons Attribution-Noncommercial-No Derivative Works 3.0 License.
**/

/*******************PARAMS****************/
/*
/* $params->get('moduleclass_sfx') - module class suffix
/*
/* $item->link        -   display link
/* $item->title       -   display title
/*
/* $item->cattitle    -   display category title
/*
/* $item->image       -   display image
/* $item->imagesource -   display raw image source
/*
/* $item->date        -   display date & time
/* $item->df_d        -   display day
/* $item->df_m        -   display mounth
/* $item->df_y        -   display year
/*
/* $item->author      -   display author
/*
/* $item->hits        -   display Hits
/*
/* $item->rating      -   display Rating
/* $item->rating_count -  display Rating Count
/*
/* $item->introtext   -   display introtex
/* $item->fulltext    -   display fulltext
/* $item->text        -   display all text
/* $item->readmore    -   display 'Read more...'
/* $item->rmtext      -   display 'Read more...' text
/*
/* $item->commentslink -   display JComments link to comments
/* $item->commentstext -   display JComments text
/*
/*****************************************/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<div class="junewsultra <?php echo $params->get('moduleclass_sfx'); ?>">
<?php foreach ($list as $item) :  ?>
	<div class="jn">
        <div class="jn-head">
        		<h4><a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>"><?php echo $item->title; ?></a></h4>
            <div class="jn-left">
            </div>
        <?php if($params->get('show_intro') || $params->get('show_full')): ?>
        <div class="jn-intro">
        <?php if($params->get('show_intro')): ?>
            <?php echo $item->introtext; ?>
        <?php endif; ?>
        <?php if($params->get('show_full')): ?>
            <?php echo $item->fulltext; ?>
        <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php if($params->get('read_more') || $params->def('JC')): ?>
        <div class="jn-more">
            <?php if($params->get('read_more')): ?>
            <a href="<?php echo $item->link; ?>" class="readmore <?php echo $params->get('moduleclass_sfx'); ?>" title="<?php echo $item->text; ?>"><?php echo $params->def('rmtext'); ?></a>
            <?php endif; ?>
            <?php if($params->def('JC')): ?>
			<a class="jn-comment-link" href="<?php echo $item->link; ?><?php echo $item->commentslink; ?>"><?php echo $item->commentstext; ?></a>
            <?php endif; ?>
        </div>
       <?php endif; ?>
            <div class="jn-right">
                <div class="jn-info">
                    <?php if($params->get('show_date')): ?>
            		<span class="jn-small"><?php 
			
		$months=Array(1=>'января',
                    2=>"февраля", 
                    3=>"марта",
                    4=>"апреля",
                    5=>"мая",
                    6=>"июня", 
                    7=>"июля", 
                    8=>"августа", 
                    9=>"сентября", 
                    10=>"октября", 
                    11=>"ноября", 
                    12=>"декабря");
                    $creat_date=date_parse($item->date);
                    echo $creat_date['day'].' '.$months[$creat_date['month']].' '.$creat_date['year'];
			?></span>
                    <?php endif; ?>
                    <?php if($params->get('showcat')): ?>
                    | <span class="jn-small"><?php echo $item->cattitle; ?></span>
                    <?php endif; ?>
                    <?php if($params->def('juauthor')): ?>
            		| <span class="jn-small"><?php echo $item->author; ?></span>
                    <?php endif; ?>
                    <?php if($params->get('showRating') || $params->get('showRatingCount') || $params->get('showHits')): ?>
                    <div class="jn-hit-n-rating">
                    <?php if($params->get('showRating')): ?>
                		<span class="jn-small jn-rating"><?php echo $item->rating; ?></span>
                    <?php endif; ?>
                    <?php if($params->get('showRatingCount') && $item->rating_count > 0): ?>
                		<sup class="jn-small jn-rating-count"><?php echo $item->rating_count; ?></sup>
                    <?php endif; ?>
                    <?php if($params->get('showHits')): ?>
            		    <span class="jn-small jn-hits"><?php echo JText::_('JGLOBAL_HITS'); ?>: <?php echo $item->hits; ?></span>
                    <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
    	    </div>
	    </div>
	    <div class="jn-img">
		<?php if($params->get('pik')): ?>
			<?php echo $item->image; ?>
                <?php endif; ?>
	    </div>
	</div>

	
<?php endforeach; ?>
</div>
<div class="cl"></div>