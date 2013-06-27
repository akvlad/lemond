<?php
/**
* @package Joomla! 2.5
* @version 4.x
* @author 2008-2012 (c)  Denys Nosov (aka Dutch)
* @author web-site: www.joomla-ua.org
* @copyright This module is licensed under a Creative Commons Attribution-Noncommercial-No Derivative Works 3.0 License.
**/

// no direct access
defined('_JEXEC') or die;

$com_path = JPATH_SITE.'/components/com_content/';
require_once $com_path.'router.php';
require_once $com_path.'helpers/route.php';

JModelLegacy::addIncludePath($com_path . '/models', 'ContentModel');

abstract class modJUNewsUltraHelper
{
	public static function getList(&$params)
    {
        $app                = JFactory::getApplication();
		$db                 = JFactory::getDbo();
        $query              = $db->getQuery(true);
        $model              = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

        $version = new JVersion;
        $joomla = substr($version->getShortVersion(), 0, 3);

		$appParams          = JFactory::getApplication()->getParams();
		$model->setState('params', $appParams);

        // Introtext
		$show_intro         = $params->get( 'show_intro');
		$introtext_limit	= intval( $params->get( 'introtext_limit') );
		$li                 = $params->get('li');
		$lmttext            = $params->get('lmttext');
		$clear_tag          = $params->get('clear_tag');

        // Fulltext
		$show_full          = $params->get( 'show_full', 0);
		$fulltext_limit  	= intval( $params->get( 'fulltext_limit') );
		$li_full            = $params->get('li_full');
		$lmttext_full       = $params->get('lmttext_full');
		$clear_tag_full     = $params->get('clear_tag_full');

		// Date
		$data_format	    = $params->get('data_format');
		$df_d			    = $params->get('df_d');
		$df_m			    = $params->get('df_m');
		$df_y			    = $params->get('df_y');

        // Image
		$pik                = $params->def('pik');

		$imageWidth         = intval($params->get('imageWidth'));
		$imageHeight        = intval($params->get('imageHeight'));

		$thumb_width        = intval($params->get('thumb_width'));

		$noimage            = $params->def('noimage');
		$imglink            = $params->def('imglink');

        $link_enabled       = $params->get('link_enabled', 1);
   		$youtube_img_show   = $params->def('youtube_img_show', 1);

   		$gallery            = $params->def('gallery', 1);

		// Set the filters based on the module params
		$model->setState('list.start', (int) $params->get('count_skip', 0));
		$model->setState('list.limit', (int) $params->get('count', 5));
		$model->setState('filter.published', 1);

        if($joomla >= '3.0') {
    		$model->setState('list.select', 'a.id, a.asset_id, a.title, a.alias, a.introtext, a.fulltext, ' .
                            'CASE WHEN badcats.id is null THEN a.state ELSE 0 END AS state, ' .
    						'a.catid, a.created, a.created_by, a.created_by_alias, ' .
    						'CASE WHEN a.modified = ' . $db->q($db->getNullDate()) . ' THEN a.created ELSE a.modified END as modified, ' .
    						'a.modified_by, a.checked_out, a.checked_out_time, a.publish_up, a.publish_down, ' .
    						'a.images, a.urls, a.attribs, a.version, a.ordering, ' .
    						'a.metakey, a.metadesc, a.access, a.hits, a.metadata, a.featured, a.language, a.xreference'
            );
        } else {
    		$model->setState('list.select', 'a.fulltext, a.id, a.title, a.alias, a.title_alias, a.introtext, a.state, a.catid, a.created, a.created_by, a.created_by_alias,' .
    			' a.modified, a.modified_by, a.publish_up, a.publish_down, a.images, a.urls, a.attribs, a.metadata, a.metakey, a.metadesc, a.access,' .
    			' a.hits, a.featured' );
        }

		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);

        // User filter
		$userId = JFactory::getUser()->get('id');
		switch ($params->get('user_id')) {
			case 'by_me':
				$model->setState('filter.author_id', (int) $userId);
			break;
			case 'not_me':
				$model->setState('filter.author_id', $userId);
				$model->setState('filter.author_id.include', false);
			break;
			case '0':
			break;
			default:
				$model->setState('filter.author_id', (int) $params->get('user_id'));
			break;
		}

		// Filter by language
		$model->setState('filter.language', $app->getLanguageFilter());

		//  Featured switch
		switch ($params->get('show_featured')) {
			case '1':
				$model->setState('filter.featured', 'only');
			break;
			case '0':
				$model->setState('filter.featured', 'hide');
			break;
			default:
				$model->setState('filter.featured', 'show');
			break;
		}

		// Set ordering
	    $order_map = array(
			'title_asc'             => 'a.title',
			'title_desc'            => 'a.title DESC',
			'id_asc'                => 'a.id',
			'id_desc'               => 'a.id DESC',
            'hits_asc'              => 'a.hits',
            'hits_desc'             => 'a.hits DESC',
            'created_asc'           => 'a.created',
            'created_desc'          => 'a.created DESC',
            'modified_desc'         => 'a.modified DESC',
            'modified_created_dsc'  => 'a.modified DESC, a.created',
			'modified_touch_dsc'    => 'CASE WHEN (a.modified = '.$db->quote($db->getNullDate()).') THEN a.created ELSE a.modified END',
			'ordering_asc'          => 'a.ordering',
			'ordering_desc'         => 'a.ordering DESC',
			'rand'                  => 'rand()',
			'publish_dsc'           => 'a.publish_up',
			'rating_dsc'            => 'v.rating_count',
		);

		$ordering = JArrayHelper::getValue($order_map, $params->get('ordering'), 'a.publish_up');
		$dir = '';

		$model->setState('list.ordering', $ordering);
	   	$model->setState('list.direction', $dir);

		// Select article or categories
        if($params->def('display_article') == 1) {
    		// Article filter
    		$model->setState('filter.article_id', $params->def('articleid', array()));
        } else {

            // Category filter
            //$catids = $params->get('catid');
            $catids = $params->get('catid', array());

            if($params->get('category_filtering_type') == 1) {
                $model->setState('filter.category_id.include', (bool) $params->get('category_filtering_type'));
            }

    		if ($catids)
            {
    			if ($params->get('show_child_category_articles', 0) && (int) $params->get('levels', 0) > 0)
                {
    				$categories = JModelLegacy::getInstance('Categories', 'ContentModel', array('ignore_request' => true));

    				$categories->setState('params', $appParams);
    				$levels = $params->get('levels', 1) ? $params->get('levels', 1) : 99999;
    				$categories->setState('filter.get_children', $levels);
    				$categories->setState('filter.published', 1);
    				$categories->setState('filter.access', $access);
    				$additional_catids = array();

    				foreach($catids as $catid)
    				{
    					$categories->setState('filter.parentId', $catid);
    					$recursive = true;
    					$items = $categories->getItems($recursive);

    					if ($items) {
    						foreach($items as $category) {

    							$condition = (($category->level - $categories->getParent()->level) <= $levels);

    							if ($condition) {
    								$additional_catids[] = $category->id;
    							}
    						}
    					}
    				}

    				$catids = array_unique(array_merge($catids, $additional_catids));
    			}

        		$model->setState('filter.category_id', $catids);
    		}

    		$excluded_articles = $params->get('excluded_articles', '');

            if ($excluded_articles) {
    		    $excluded_articles = explode("\r\n", $excluded_articles);
    			$model->setState('filter.article_id', $excluded_articles);
    			$model->setState('filter.article_id.include', false); // Exclude
    	   	}
        }

        $date_filtering     = $params->get('date_filtering', 0);
        $relative_date      = $params->get('relative_date', 0);
		if ($date_filtering == 1) {

            $model->setState('filter.date_filtering', (($date_filtering == 1 && $relative_date == 1) ? 'range' : 'relative'));
			$model->setState('filter.date_field', $params->get('date_field', 'a.created'));

    		switch ($relative_date) {
    			case '1':
          			$model->setState('filter.start_date_range', $params->get('start_date_range', date('Y-m-d') .' 00:00:00'));
          			$model->setState('filter.end_date_range', $params->get('end_date_range', date('Y-m-d H:i:s')));
    			break;
    			case '2':
    				$model->setState('filter.relative_date', '7');
    			break;
    			case '3':
    				$model->setState('filter.relative_date', '14');
    			break;
    			case '4':
    				$model->setState('filter.relative_date', cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')));
    			break;
    			case '5':
    				$model->setState('filter.relative_date', '365');
    			break;

    			case '6':
    				$model->setState('filter.relative_date', $params->get('custom_days', '30'));
    			break;

                case '0':
    			default:
                    $model->setState('filter.relative_date', '1');
    			break;
    		}  
		}

        $items = $model->getItems();

        // JComments integration
        if ($params->def('JC') == 1 && count($items))
        {
            $comments = JPATH_SITE . '/components/com_jcomments/jcomments.php';
            if (file_exists($comments))
            {
                $ids = array();
                foreach($items as $item) {
                    $ids[] = $item->id;
                }

                $db->setQuery('SELECT object_id, count(*) AS cnt FROM #__jcomments WHERE object_group = "com_content" AND object_id IN ('.implode(',', $ids).') AND published = "1"');
                $commentsCount = $db->loadObjectList('object_id');

                foreach($items as &$item)
                {
                    $item->comments     = isset($commentsCount[$item->id]) ? $commentsCount[$item->id]->cnt : 0;
                    $item->commentslink = '#comments';
                    $item->commentstext = JText::plural('LINK_READ_COMMENTS', $item->comments);

                    if ($item->comments == 0) {
                        $item->comments     = '';
                        $item->commentslink = '#addcomments';
                        $item->commentstext = JText::_('LINK_ADD_COMMENT');
                    }
                }
            } else {
                echo '<strong style="color: green;">'. JText::_('MOD_JUNEWS_JC_NOT_INSTALLED') .'</strong>';
            }
        }

		foreach ($items as &$item) {

			$item->slug = $item->id.':'.$item->alias;
			$item->catslug = $item->catid.':'.$item->category_alias;

			if ($access || in_array($item->access, $authorised)) {
				$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
                $catlink = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catslug));
			} else {
				$item->link = JRoute::_('index.php?option=com_users&view=login');
                $catlink = $item->link;
			}

            // article title
            $item->title = preg_replace('#"(.*?)"#is', '«\\1»', strip_tags( $item->title ));

            // category title
            if($params->get('showcat') == 1) {
                $cattitle = strip_tags( $item->category_title );
                if($params->get('showcatlink') == 1) {
                    $item->cattitle = '<a href="'. $catlink .'">'. $cattitle .'</a>';
                } else {
                    $item->cattitle = $cattitle;
                }
            }

            if ($pik == 1) {

                if ($imglink == 1) {
                    $imlink  = '<a href="'. $item->link .'"'. ($params->get('tips') == 1 ? ' title="'. strip_tags( $item->title ) .'"' : '') .'>';
                    $imlink2 = '</a>';
                } else {
                    $imlink  = '';
                    $imlink2 = '';
                }
;
                $junuimgresmatche = $item->introtext . $item->fulltext;

                if (preg_match('/{gallery\s+(.*?)}/i', $junuimgresmatche, $junuimgsource) && $gallery == '1') {

                    $junuimgsource  = $junuimgsource[1];

                    $imglist = explode("|", $junuimgsource);
                    $junuimgsource  = $imglist[0];

                    $imglist        = '';
                    $root           = JPATH_BASE .'/';
                    $folder         = 'images/'. $junuimgsource;
                    $img_folder     = $root . $folder;

                    if (is_dir($img_folder)) {
                        $files          = array();
                        $dir            = opendir($img_folder);
                        while(($file = readdir($dir)) !== false) {
                            if($file !== '.' && $file !== '..' && (strtolower(substr($file, -3)) === 'jpg' || strtolower(substr($file, -3)) === 'png' || strtolower(substr($file, -3)) === 'gif')) {
                                $files[] = $file;
                                break;
                            }
                        }
                        closedir($dir);
                        sort($files);
                        $junuimgsource  = $folder .'/'. $files[0];
                    } else {
                        $junuimgsource = '';
                    }

                } elseif (preg_match('/<img(.*?)src="(.*?)"(.*?)>\s*(<\/img>)?/', $junuimgresmatche, $junuimgsource)) {
                    $junuimgsource  = $junuimgsource[2];
                }

                // images from article parameters
                $images = json_decode($item->images);

                if(  ($params->def('image_source', 0) > '0') &&
                    ((isset($images->image_intro) && !empty($images->image_intro) ) ||
                    (isset($images->image_fulltext) && !empty($images->image_fulltext) ))
                ) {
                    if( $params->def('image_source', 0) == '1' ) {
                        if( isset($images->image_intro) && !empty($images->image_intro) ) {
                            $junuimgsource = htmlspecialchars($images->image_intro);
                            // raw image source
                            $item->imagesource = htmlspecialchars($images->image_intro);
                        } elseif( isset($images->image_fulltext) && !empty($images->image_fulltext) ) {
                            $junuimgsource = htmlspecialchars($images->image_fulltext);
                            // raw image source
                            $item->imagesource = htmlspecialchars($images->image_fulltext);
                        }
                    } elseif( $params->def('image_source', 0) == '2' && ( isset($images->image_intro) && !empty($images->image_intro) ) ) {
                        $junuimgsource = htmlspecialchars($images->image_intro);
                        // raw image source
                        $item->imagesource = htmlspecialchars($images->image_intro);
                    } elseif( $params->def('image_source', 0) == '3' && ( isset($images->image_fulltext) && !empty($images->image_fulltext) ) ) {
                        $junuimgsource = htmlspecialchars($images->image_fulltext);
                        // raw image source
                        $item->imagesource = htmlspecialchars($images->image_fulltext);
                    }
                } else {
                    if( $junuimgsource ) {
                        $junuimgsource = str_replace(JURI::base(), '', $junuimgsource);
                        // raw image source
                        $item->imagesource = $junuimgsource;
                    } else {
                        $junuimgsource  = '';
                    }
                }

        		switch ($thumb_width) {

        			case '0':

                        $contentimage = $imlink .'<img src="'. $junuimgsource .'" width="'. $imageWidth .'" alt="'. strip_tags( $item->title ) .'" />'. $imlink2;
                        $blankimage = $imlink .'<img src="'. JURI::base().'/media/mod_junewsultra/' . $noimage .'" width="'. $imageWidth .'" alt="'. strip_tags( $item->title ) .'" />'. $imlink2;

                        if($youtube_img_show == 1) {

                            $regex1 = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^>"&?/ ]{11})%i';
                            $regex2 = '#(player.vimeo.com)/video/([0-9]+)#i';

                            if (preg_match($regex1, $junuimgresmatche, $match)) {

                                $yimg = modJUNewsUltraHelper::video('http://youtu.be/'. $match[1],'hqthumb');

                                $item->image = $imlink .'<img src="'. $yimg .'" width="'. $imageWidth .'" alt="'. strip_tags( $item->title ) .'" />'. $imlink2;
                                $item->imagesource = $yimg;
                            } elseif (preg_match($regex2, $junuimgresmatche, $match)) {

                                $yimg = modJUNewsUltraHelper::video('http://vimeo.com/'. $match[2],'hqthumb');

                                $item->image = $imlink .'<img src="'. $yimg .'" width="'. $imageWidth .'" alt="'. strip_tags( $item->title ) .'" />'. $imlink2;
                                $item->imagesource = $yimg;
                            } elseif( $junuimgsource ) {
                                $item->image = $contentimage;
                            } elseif($params->def('defaultimg', 1) == 1) {
                                $item->image = $blankimage;
                            }

                        } elseif( $junuimgsource ) {
                            $item->image = $contentimage;
                        } elseif($params->def('defaultimg', 1) == 1) {
                            $item->image = $blankimage;
                        }

        			break;

                    case '1':
        			default:

                        $thumb_img          = modJUNewsUltraHelper::RenderImage( $junuimgsource, &$params, '', '', '', '' );
                        list($width, $height, $type, $attr) = getimagesize( $thumb_img );
                        $contentimage       = $imlink .'<img src="'. $thumb_img .'" '. $attr .' alt="'. strip_tags( $item->title ) .'" />'. $imlink2;

                        $blank_thumb_img    = modJUNewsUltraHelper::RenderImage( 'media/mod_junewsultra/'. $noimage, &$params, '', '', '', '');
                        list($width, $height, $type, $attr) = getimagesize( $blank_thumb_img );
                        $blankimage         = $imlink .'<img src="'. $blank_thumb_img .'" '. $attr .' alt="'. strip_tags( $item->title ) .'" />'. $imlink2;

                        if(($youtube_img_show == 1) && ($link_enabled == 1)) {

                            $regex1 = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^>"&?/ ]{11})%i';
                            $regex2 = '#(player.vimeo.com)/video/([0-9]+)#i';

                            if (preg_match($regex1, $junuimgresmatche, $match)) {

                                $yimg           = modJUNewsUltraHelper::video('http://youtu.be/'. $match[1],'hqthumb');
                                $video_img      = modJUNewsUltraHelper::RenderImage($yimg, &$params, '', '', '', '');
                                list($width, $height, $type, $attr) = getimagesize( $video_img);

                                $item->image    = $imlink .'<img src="'. $video_img .'" '. $attr .' alt="'. strip_tags( $item->title ) .'" />'. $imlink2;
                                $item->imagesource = $yimg;
                            } elseif (preg_match($regex2, $junuimgresmatche, $match)) {

                                $yimg = modJUNewsUltraHelper::video('http://vimeo.com/'. $match[2],'hqthumb');
                                $video_img      = modJUNewsUltraHelper::RenderImage($yimg, &$params, '', '', '', '');
                                list($width, $height, $type, $attr) = getimagesize( $video_img);

                                $item->image = $imlink .'<img src="'. $video_img .'" '. $attr .' alt="'. strip_tags( $item->title ) .'" />'. $imlink2;
                                $item->imagesource = $yimg;
                            } elseif( $junuimgsource ) {
                                $item->image = $contentimage;
                            } elseif($params->def('defaultimg', 1) == 1) {
                                $item->image = $blankimage;
                            }

                        } elseif( $junuimgsource ) {
                            $item->image = $contentimage;
                        } elseif($params->def('defaultimg', 1) == 1) {
                            $item->image = $blankimage;
                        }

        			break;
                }
            }

            // introtext
            if($show_intro == '1')
            {
                if ($clear_tag == '1') {
                    $item->introtext = preg_replace('/{([a-zA-Z0-9\-_]*)\s*(.*?)}/i', '', $item->introtext);
                    $item->introtext = str_replace( '&nbsp;', ' ', $item->introtext );
                    $item->introtext = strip_tags( $item->introtext );
                } else {
                    $item->introtext = preg_replace('/{([a-zA-Z0-9\-_]*)\s*(.*?)}/i', '', $item->introtext);
                    $item->introtext = preg_replace('/<img(.*?)>/i', '', $item->introtext);
                }

                if ($li == '1')
                {
                    if ($lmttext == '1') {
                        $item->introtext = trim(implode(" ", array_slice(explode(" ", $item->introtext), 0, $introtext_limit)));
                    } else {
                        $item->introtext = trim(JString::substr($item->introtext, 0, $introtext_limit));
                    }
                    if (!preg_match('#(\.|\?|\!)$#ismu', $item->introtext)) {
                        $item->introtext = preg_replace('#^\s?(\,|\;|\:|\-)#ismu', '', $item->introtext);
                        $item->introtext = $item->introtext . '...';
                    }
                }
            }

            // fulltext
            if($show_full == '1')
            {
                if ($clear_tag == '1') {
                    $item->fulltext = preg_replace('/{([a-zA-Z0-9\-_]*)\s*(.*?)}/i', '', $item->fulltext);
                    $item->fulltext = str_replace( '&nbsp;', ' ', $item->fulltext );
                    $item->fulltext = strip_tags( $item->fulltext );
                } else {
                    $item->fulltext = preg_replace('/{([a-zA-Z0-9\-_]*)\s*(.*?)}/i', '', $item->fulltext);
                    $item->fulltext = preg_replace('/<img(.*?)>/i', '', $item->fulltext);
                }

                if ($li_full == '1')
                {
                    if ($lmttext_full == '1') {
                        $item->fulltext = trim(implode(" ", array_slice(explode(" ", $item->fulltext), 0, $fulltext_limit)));
                    } else {
                        $item->fulltext = trim(JString::substr($item->fulltext, 0, $fulltext_limit));
                    }
                    if (!preg_match('#(\.|\?|\!)$#ismu', $item->fulltext)) {
                        $item->fulltext = preg_replace('#^\s?(\,|\;|\:|\-)#ismu', '', $item->fulltext);
                        $item->fulltext = $item->fulltext . '...';
                    }
                }
            }

            // author
            if ($params->def('juauthor') == 1)
            {
                if ( $item->created_by_alias ) {
				    $item->author = $item->created_by_alias;
			    } else {
				    $item->author = $item->author;
			    }
            }

            // date
            if ($params->get('show_date') == 1)
            {
                $item->date   = JHtml::date($item->created, $data_format);
                $item->df_d   = JHtml::date($item->created, $df_d);
                $item->df_m   = JHtml::date($item->created, $df_m);
                $item->df_y   = JHtml::date($item->created, $df_y);
            }

            // hits
            if ($params->get('showHits') == 1) {
                $item->hits   = $item->hits;
            }
            
            // rating
            if ($params->get('showRating') == 1)
            {
                $template     = str_replace('_:', '', $params->def('template'));
    			$starImageOn  = JHTML::_('image.site', 'modules/mod_junewsultra/tmpl/'.$template.'/images/rating_star.png', null);;
    			$starImageOff = JHtml::_('image.site','modules/mod_junewsultra/tmpl/'.$template.'/images/rating_star_blank.png', NULL, NULL, true);

    			$img          = '';
    			for ($i=0; $i < $item->rating; $i++) {
    				$img .= $starImageOn;
    			}
                for ($i=$item->rating; $i < 5; $i++) {
    				$img .= $starImageOff;
    			}
    			$item->rating  = $img;
            }
		}

		return $items;
	}

	public static function RenderImage($url, &$params, $nw, $nh, $nzc, $nq)
	{
        $img_cache     	    = $params->get( 'img_cache', 'cache' );
        $cache_maxfiles_img = $params->get( 'cache_maxfiles_img', '200' );
        $cache_maxsize_img  = $params->get( 'cache_maxsize_img', '10' );
        $cache_maxage_img   = $params->get( 'cache_maxage_img', '30' );

		$w                  = ($nw ? $nw : intval($params->get( 'imageWidth' )));
		$h                  = ($nh ? $nh : intval($params->get( 'imageHeight' )));
		$zc                 = ($nzc ? $nzc : intval($params->get( 'Zoom_Crop', 1 )));
        $q                  = ($nq ? $nq : '99' );
        $f                  = $params->def( 'img_ext', 'jpg' );

        $thumb_filtercolor  = intval($params->get( 'thumb_filtercolor', 0 ));
        $colorized          = $params->get( 'colorized', '25' );
        $colorpicker        = $params->get( 'colorpicker', '#0000ff' );
        $thumb_th           = $params->def( 'thumb_th', 0 );
        $thumb_th_seting    = $params->def( 'thumb_th_seting', 0 );

        $link_enabled       = $params->get( 'link_enabled', 1 );
        $error_image        = JPATH_BASE .'/media/mod_junewsultra/'. $params->def( 'noimage' );

        $thumb_filters      = $params->def( 'thumb_filters', 1 );
        $usm                = $params->def( 'thumb_unsharp', 1 );
        $thumb_unsharp_amount = $params->def( 'thumb_unsharp_amount', 80 );
        $thumb_unsharp_radius = $params->def( 'thumb_unsharp_radius', 1 );
        $thumb_unsharp_threshold = $params->def( 'thumb_unsharp_threshold', 3 );
        $thumb_blur         = $params->def( 'thumb_blur', 0 );
        $thumb_blur_seting  = $params->def( 'thumb_blur_seting', 1 );
        $thumb_brit         = $params->def( 'thumb_brit', 0 );
        $thumb_brit_seting  = $params->def( 'thumb_brit_seting', 50 );
        $thumb_cont         = $params->def( 'thumb_cont', 0 );
        $thumb_cont_seting  = $params->def( 'thumb_cont_seting', 50 );

        if(preg_match('#^(http|https|ftp)://#i', $url)) {
            $url = $url;
        } else {
            $url = (!file_exists($url) ? $error_image : JPATH_BASE .'/'. $url);
        }

        $path_parts         = pathinfo($url);
        $imgfile            = explode(".", basename($url));
        $img_name           = $imgfile[0];

        $imgurl             = str_replace(array('.', ',', '-', '_', '|', '+', '=', '%', '^', '&', '(', ')', ' ', '"', '\''), '', $img_name);

        $subfolder          = $img_cache .'/junewsultra/'. $w.'x'.$h .'/'. substr( strtolower( MD5($path_parts['dirname'] ) ), -1);
        $target             = $subfolder .'/junews_'.
                            substr( strtolower( $imgurl ), 0, 150) .'-'.
                            MD5(
                                $url .
                                $q .
                                $zc .
                                $f .
                                $params->get('use_imagemagick') .
                                $usm .
                                $thumb_unsharp_amount .
                                $thumb_unsharp_radius .
                                $thumb_unsharp_threshold .
                                $thumb_filtercolor .
                                $colorized .
                                $colorpicker .
                                $thumb_blur .
                                $thumb_blur_seting .
                                $thumb_brit .
                                $thumb_brit_seting .
                                $thumb_cont .
                                $thumb_cont_seting .
                                $thumb_th .
                                $thumb_th_seting
                            ) .'.'. $f;

        if (($link_enabled == 0) && preg_match('#^(http|https|ftp)://#i', $url)) {
            $outpute = $error_image;

            return $outpute;
        }

        modJUNewsUltraHelper::MakeDirectory($dir = JPATH_BASE .'/'. $subfolder, $mode = 0777);

        if (file_exists(JPATH_BASE .'/'. $target)) {
            $outpute = JURI::base(false) . $target;
        } else {
            include_once(JPATH_BASE .'/modules/mod_junewsultra/img/phpthumb.class.php');
            $phpThumb = new phpThumb();
            $phpThumb->resetObject();

            $phpThumb->setParameter('config_max_source_pixels', round(max(intval(ini_get('memory_limit')), intval(get_cfg_var('memory_limit'))) * 1048576 / 6) );
            $phpThumb->setParameter('config_temp_directory', JPATH_BASE .'/'. $img_cache .'/junewsultra/' );
            $phpThumb->setParameter('config_cache_directory', JPATH_BASE .'/'. $img_cache .'/junewsultra/' );
            $phpThumb->setCacheDirectory();
            $phpThumb->setParameter('config_cache_maxfiles', $cache_maxfiles_img );
            $phpThumb->setParameter('config_cache_maxsize', $cache_maxsize_img * 1024 * 1024 );
            $phpThumb->setParameter('config_cache_maxage', 86400 * $cache_maxage_img );

            $phpThumb->setSourceFilename( $url );

            if($w) {
                $phpThumb->setParameter( 'w', $w );
            }

            if($h) {
                $phpThumb->setParameter( 'h', $h );
            }

            if($q) {
                $phpThumb->setParameter( 'q', $q );
            }

            $phpThumb->setParameter( 'f', $f );
            $phpThumb->setParameter( 'zc', $zc );
            $phpThumb->setParameter( 'aoe', '1' );

    		switch ($thumb_filtercolor) {
    			case '1':
    				$phpThumb->setParameter( 'fltr', 'gray' );
    			break;
    			case '2':
    				$phpThumb->setParameter( 'fltr', 'sep' );
    			break;
    			case '3':
    				$phpThumb->setParameter( 'fltr', 'th|'. $thumb_th_seting );
    			break;
    			case '4':
    				$phpThumb->setParameter( 'fltr', 'clr|'. $colorized .'|'. str_replace('#', '', $colorpicker) );
    			break;

    			default:
    			break;
    		}

            if($thumb_filters == 1) {
                if($usm == 1) {
                    $phpThumb->setParameter( 'fltr', 'usm|'. $thumb_unsharp_amount .'|'. $thumb_unsharp_radius .'|'. $thumb_unsharp_threshold );
                }
                if($thumb_blur == 1) {
                    $phpThumb->setParameter( 'fltr', 'blur|'. $thumb_blur_seting );
                }
                if($thumb_brit == 1) {
                    $phpThumb->setParameter( 'fltr', 'brit|'. $thumb_brit_seting );
                }
                if($thumb_cont == 1) {
                    $phpThumb->setParameter( 'fltr', 'cont|'. $thumb_cont_seting );
                }
            }

            if($params->get('use_imagemagick') == 1) {

                if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
                	$imagemagick = ($params->get( 'imagemagick_path' ) == '' ? 'C:/ImageMagick/convert.exe' : $params->get( 'imagemagick_path' ));
                } else {
                    $imagemagick = $params->get( 'imagemagick_path', '' );
                }

                $phpThumb->setParameter('config_imagemagick_path', $imagemagick);
                $phpThumb->setParameter('config_prefer_imagemagick', ($params->get('use_imagemagick') == 1 ? true : false) );
                $phpThumb->setParameter('config_imagemagick_use_thumbnail', true);

            }

            if ($phpThumb->GenerateThumbnail()) {

            	if ($phpThumb->RenderToFile( JPATH_BASE .'/'. $target )) {
            		$outpute = JURI::base(false) . $target;
            	} else {
                    $outpute = '';
            	}

            } else {
                $outpute = '';
            }
        }

		return  $outpute;
    }


    public static function MakeDirectory($dir, $mode)
    {
        if (is_dir($dir) || @mkdir($dir,$mode)) return TRUE;
        if (!modJUNewsUltraHelper::MakeDirectory(dirname($dir),$mode)) return FALSE;

        return @mkdir($dir,$mode);
    }

    /*
    * parse_video() PHP function
    * Author: takien
    * URL: http://takien.com
    *
    * Author: takien, slaffko
    * URL: http://takien.com, http://slaffko.name
    */

    public static function video($url,$return='embed',$width='',$height='',$rel=0)
    {
        $urls = parse_url($url);

        //url is http://vimeo.com/xxxx
        if($urls['host'] == 'vimeo.com'){
            $vid = ltrim($urls['path'],'/');
        }
        //url is http://youtu.be/xxxx
        else if($urls['host'] == 'youtu.be'){
            $yid = ltrim($urls['path'],'/');
        }
        //url is http://www.youtube.com/embed/xxxx
        else if(strpos($urls['path'],'embed') == 1){
            $yid = end(explode('/',$urls['path']));
        }
        //url is xxxx only
        else if(strpos($url,'/')===false){
            $yid = $url;
        }
        //http://www.youtube.com/watch?feature=player_embedded&v=m-t4pcO99gI
        //url is http://www.youtube.com/watch?v=xxxx
        else{
            parse_str($urls['query']);
            $yid = $v;
            if(!empty($feature)){
                $yid = end(explode('v=',$urls['query']));
                $arr = explode('&',$yid);
                $yid = $arr[0];
            }
        }
        if($yid) {
            //return embed iframe
            if($return == 'embed'){
                return '<iframe width="'.($width?$width:560).'" height="'.($height?$height:349).'" src="http://www.youtube.com/embed/'.$yid.'?rel='.$rel.'" frameborder="0" ebkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
            }
            //return normal thumb
            else if($return == 'thumb' || $return == 'thumbmed'){
                return 'http://i1.ytimg.com/vi/'.$yid.'/default.jpg';
            }
            //return hqthumb
            else if($return == 'hqthumb' ){
                return 'http://i1.ytimg.com/vi/'.$yid.'/hqdefault.jpg';
            }
            // else return id
            else{
                return $yid;
            }
        }
        else if($vid) {
            $vimeoObject = json_decode(file_get_contents("http://vimeo.com/api/v2/video/".$vid.".json"));
            if (!empty($vimeoObject)) {
                //return embed iframe
                if($return == 'embed'){
                    return '<iframe width="'.($width?$width:$vimeoObject[0]->width).'" height="'.($height?$height:$vimeoObject[0]->height).'" src="http://player.vimeo.com/video/'.$vid.'?title=0&byline=0&portrait=0" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
                }
                //return normal thumb
                else if($return == 'thumb'){
                    return $vimeoObject[0]->thumbnail_small;
                }
                //return medium thumb
                else if($return == 'thumbmed'){
                    return $vimeoObject[0]->thumbnail_medium;
                }
                //return hqthumb
                else if($return == 'hqthumb'){
                    return $vimeoObject[0]->thumbnail_large;
                }
                // else return id
                else{
                    return $vid;
                }
            }
        }
    }
}