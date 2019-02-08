<?php
/**
 * Category model file
 * 
 * @name       Joomla HD Video Share
 * @SVN        3.8
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2015 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5
 */
/** Include component helper */
include_once (JPATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helper.php');

/** No direct access to this file */
defined ( '_JEXEC' ) || exitAction ( 'Restricted access' );

/** Import joomla model library */
jimport ( 'joomla.application.component.model' );

/** Import joomla pagination library */
jimport ( 'joomla.html.pagination' );

/**
 * Admin category model class.
 *
 * @package Joomla.Contus_HD_Video_Share
 * @subpackage Com_Contushdvideoshare
 * @since 1.5
 */
class ContushdvideoshareModelcategory extends ContushdvideoshareModel {
  /**
   * Constructor function to declae values globally
   */
  public function __construct() {
    global $mainframe, $db, $option;
    global $cateid;
    parent::__construct ();
    $cateid = 0;
    $mainframe = JFactory::getApplication ();
    $db = JFactory::getDBO ();
    $option = JRequest::getCmd ( 'option' );
  }
  
  /**
   * Function to fetch categories detail
   *
   * @return getcategory
   */
  public function getcategory() {
    global $option, $mainframe, $db;
    $query = $db->getQuery ( true );
    $filter_order = $mainframe->getUserStateFromRequest ( $option . 'filter_order_category', 'filter_order', 'a.ordering', 'cmd' );
    $filter_order_Dir = $mainframe->getUserStateFromRequest ( $option . 'filter_order_Dir_category', 'filter_order_Dir', 'asc', 'word' );
    $search = $mainframe->getUserStateFromRequest ( $option . CATEGORY_SEARCH, CATEGORY_SEARCH, '', 'string' );
    $state_filter = $mainframe->getUserStateFromRequest ( $option . CATEGORY_STATUS, CATEGORY_STATUS, '', 'int' );
    $search1 = $search;
    
    /** Default List Limit */
    $limit = $mainframe->getUserStateFromRequest ( $option . ' . limit', 'limit', $mainframe->getCfg ( 'list_limit' ), 'int' );
    $limitstart = $mainframe->getUserStateFromRequest ( $option . ' . limitstart', 'limitstart', 0, 'int' );
    
    $lists ['order_Dir'] = $filter_order_Dir;
    $lists ['order'] = $filter_order;
    $where = '';
    /** Call phpslashes function from helper */
    $search = phpSlashes ( $search );
    
    /** Query to fetch sub categories */
    if ($search) {
      $where .= " a.category LIKE '%$search%'";
      $lists [CATEGORY_SEARCH] = $search1;
    }
    
    /** Filtering based on status */
    if ($state_filter) {
      if ($state_filter == 1) {
        $state_filterval = 1;
      } elseif ($state_filter == 2) {
        $state_filterval = 0;
      } else {
        $state_filterval = - 2;
      }
      
      if ($search) {
        $where .= ' AND ';
      }
      
      $where .= " a.published = $state_filterval";
      $lists [CATEGORY_STATUS] = $state_filter;
    } else {
      if ($search) {
        $where .= ' AND ';
      }
      
      $where .= " a.published != -2";
    }
    
    $fields = array ( $db->quoteName ( 'a.id' ) . ' AS value', $db->quoteName ( 'a.category' ) . ' AS text',
        $db->quoteName ( 'a.ordering' ), $db->quoteName ( VIDEOPUBLISH ), 'COUNT(DISTINCT b.id) AS level' );
    $query->clear ()->select ( $fields )->from ( $db->quoteName ( CATEGORYTABLE ) . VIDEOTABLECONSTANT )->leftJoin ( CATEGORYTABLE . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt' )->where ( $where )->group ( $db->escape ( 'a.id' . ' ,' . 'a.category' . ' , ' . 'a.lft' . ' , ' . 'a.rgt' ) )->order ( $filter_order . ' ' . $filter_order_Dir );
    $db->setQuery ( $query );
    $db->query ();
    $categoryCount = $db->getNumRows ();
    
    /** Set pagination */
    $pageNav = new JPagination ( $categoryCount, $limitstart, $limit );
    
    $db->setQuery ( $query, $pageNav->limitstart, $pageNav->limit );
    $categorylist = $db->loadObjectList ();
    
    if ($db->getErrorNum ()) {
      JError::raiseWarning ( $db->getErrorNum (), $db->stderr () );
    }
    
    return array ( 'pageNav' => $pageNav, 'limitstart' => $limitstart, 'limit' => $limit, 'categoryFilter' => $lists, 'categorylist' => $categorylist );
  }
  
  /**
   * Function to category details
   *
   * @param int $id
   *          category id
   *          
   * @return getcategorydetails
   */
  public function getcategorydetails($id) {
    $db = $this->getDBO ();
    
    /** Query to fetch details of selected category */
    $query = $db->getQuery ( true );
    $query->clear ()->select ( $db->quoteName ( array ( 'id', 'member_id', CATEGORY, SEOCATEGORY, PARENT_ID, 'ordering', PUBLISH 
    ) ) )->from ( $db->quoteName ( CATEGORYTABLE ) )->where ( $db->quoteName ( 'id' ) . '= ' . $id );
    $db->setQuery ( $query );
    $category = $db->loadObject ();
    
    $fields = array ( $db->quoteName ( 'a.id' ) . ' AS value', $db->quoteName ( 'a.category' ) . ' AS text', 'COUNT(DISTINCT b.id) AS level' );
    $query->clear ()->select ( $fields )->from ( $db->quoteName ( CATEGORYTABLE ) . VIDEOTABLECONSTANT )->leftJoin ( CATEGORYTABLE . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->where ( $db->quoteName ( 'a.id' ) . ' != ' . $db->quote ( $id ) )->group ( $db->escape ( 'a.id' . ' ,' . 'a.category' . ' , ' . 'a.lft' . ' , ' . 'a.rgt' ) )->order ( 'a.lft ASC' );
    $db->setQuery ( $query );
    $categorylist = $db->loadObjectList ();
    
    foreach ( $categorylist as &$option ) {
      $option->text = str_repeat ( '- ', $option->level ) . $option->text;
    }
    
    if ($db->getErrorNum ()) {
      JError::raiseWarning ( $db->getErrorNum (), $db->stderr () );
    }
    
    return array ( $category, $categorylist );
  }
  
  /**
   * Function to fetch categories,ads and adding new video
   *
   * @return addvideosmodel
   */
  public function getNewcategory() {
    global $db;
    $query = $db->getQuery ( true );
    $fields = array ( $db->quoteName ( 'a.id' ) . ' AS value', $db->quoteName ( 'a.category' ) . ' AS text', 'COUNT(DISTINCT b.id) AS level' );
    $query->clear ()->select ( $fields )->from ( $db->quoteName ( CATEGORYTABLE ) . VIDEOTABLECONSTANT )->leftJoin ( CATEGORYTABLE . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt' )->where ( $db->quoteName ( VIDEOPUBLISH ) . ' = ' . $db->quote ( '1' ) )->group ( $db->escape ( 'a.id' . ' ,' . 'a.category' . ' , ' . 'a.lft' . ' , ' . 'a.rgt' ) )->order ( 'a.lft ASC' );
    $db->setQuery ( $query );
    $options = $db->loadObjectList ();
    
    foreach ( $options as &$option ) {
      $option->text = str_repeat ( '- ', $option->level ) . $option->text;
    }
    
    $objCategoryTable = $this->getTable ( CATEGORY );
    $objCategoryTable->id = 0;
    $objCategoryTable->category = '';
    $objCategoryTable->published = '';
    
    /**
     * get the most recent database error code
     * display the last database error message in a standard format
     */
    if ($db->getErrorNum ()) {
      JError::raiseWarning ( $db->getErrorNum (), $db->stderr () );
    }
    
    return array ( $objCategoryTable, $options );
  }
  
  /**
   * Function is used to check category is exists in db or not 
   * 
   * @param unknown $seo_category
   * @return Ambigous <mixed, NULL, multitype:unknown mixed >
   */
  public function checkCategoryExist ( $seo_category) {
    /** Get db connection to fetch category details to check */
    $db = $this->getDBO ();
    $query = $db->getQuery ( true );
    
    /** Query to select category details from db */
    $query->clear ()->select ( $db->quoteName ( array ( 'id', PUBLISH, SEOCATEGORY ) ) )->from ( $db->quoteName ( CATEGORYTABLE ) )->where ( $db->quoteName ( CATEGORY ) . '=\'' . $seo_category . '\'' );
    $db->setQuery ( $query );
    return $db->loadObjectList ();
  }
  
  /**
   * Function to fetch categories,ads and adding new video
   *
   * @param array $arrFormData
   *          category detail array
   *          
   * @return addvideosmodel
   */
  public function savecategory($arrFormData) {
    global $mainframe;
    $db       = $this->getDBO ();
    $query    = $db->getQuery ( true );
    $addFlag  = 0;
    $objCategoryTable = $this->getTable ( CATEGORY );
    $link             = 'index.php?option=com_contushdvideoshare&layout=category';
    $seoCatTitle  = '';
    /** Code for seo category name */
    $seo_category = $arrFormData [CATEGORY];
    $category_id  = $arrFormData ['id'];
    $category     = $this->checkCategoryExist ( $seo_category );
    
    if (isset ( $category [0]->id ) && $category [0]->id != 0 && $category [0]->published == 1) {
      if ($category [0]->id == $category_id) {
        $update = 0;
        $addFlag = 1;
      } else {
        $addFlag = 0;
        $msg = 'Category already exist';
        $mainframe->redirect ( $link, $msg, MESSAGE );
      }
    } elseif ( isset ($category [0]->published) && $category [0]->published == - 2) {
      $addFlag = 0;
      $msg = 'Category already exist. Please check in your trash . ';      
      $mainframe->redirect ( $link, $msg, MESSAGE );
    } else {
      $parent_id = $arrFormData [PARENT_ID];
      $query->clear ()->select ( $db->quoteName ( array ( 'ordering' ) ) )->from ( $db->quoteName ( CATEGORYTABLE ) )->where ( $db->quoteName ( PARENT_ID ) . '=\'' . $parent_id . '\'' );
      $db->setQuery ( $query );
      $ordering = $db->loadObjectList ();
      $ordering_count = count ( $ordering );
      $arrFormData ['ordering'] = $ordering_count + 1;      
      $update = 1;
      $addFlag = 1;
    }   
    
    $seoCatTitle = makeSEOTitle ( $seo_category );
    /** Get admin videos table */
    $table = $this->getTable ( 'category' );
    while ( $table->load ( array ( 'seo_category' => $seoCatTitle ) ) ) {
      /** Load admin videos table and get seo title */
      $seoCatTitle = JString::increment ( $seoCatTitle, 'dash' );
    } 
    $arrFormData [SEOCATEGORY] = $seoCatTitle;    
    if($addFlag == 1) {
      if (! $objCategoryTable->bind ( $arrFormData )) {
        JError::raiseWarning ( 500, $objCategoryTable->getError () );
      }      
      if (! $objCategoryTable->check ()) {
        JError::raiseWarning ( 500, $objCategoryTable->getError () );
      }      
      if (! $objCategoryTable->store ()) {
        JError::raiseWarning ( 500, $objCategoryTable->getError () );
      }  
      $this->createHiddenCategoryMenu ( $objCategoryTable, $update, $arrFormData );    
      $this->rebuild ( 0, 0 );
    }
  }
  
  /**
   * Function to create categories hidden menus in admin 
   * 
   * @param unknown $objCategoryTable
   * @param unknown $update
   * @param unknown $arrFormData 
   */
  public function createHiddenCategoryMenu ( $objCategoryTable, $update, $arrFormData ) {
    /** Get db connection to create category menu */ 
    $db           = $this->getDBO ();
    $query        = $db->getQuery ( true );
    $category_id  = $arrFormData ['id'];
    $catTitle     = $arrFormData [CATEGORY];
    $alias        = $arrFormData [SEOCATEGORY];
    $columns = array('menutype','title','alias','path','link','type',PUBLISH,PARENT_ID,'level','component_id','browserNav','access','params');
    
    /** Get extension id for videoshare component */
    $query->clear() ->select('extension_id') ->from('#__extensions') ->where( $db->quoteName('type') . ' = ' . $db->quote(COMPONENTTEXT) )
    ->where($db->quoteName('element') . ' = ' . $db->quote('com_contushdvideoshare')) ->where($db->quoteName('enabled') . ' = ' . $db->quote('1'))
    ->order('extension_id DESC');
    $db->setQuery($query,0,1);
    $extension_id = $db->loadResult();
    
    /** Get menu id for hidden category menu */
    $query->clear()->select('id') ->from('#__menu_types') ->where($db->quoteName('menutype') . ' = ' . $db->quote(HIDDENCATEGORYMENU));
    $db->setQuery($query,0,1);
    $menu_type_id = $db->loadResult();
    
    /** Check hidden category menu id is exists or not */
    if(empty($menu_type_id)) {
      /** If menu id is not exist then insert new menu id */
      $menu_type_values = array($db->quote(HIDDENCATEGORYMENU), $db->quote('Hidden HD Video Category Menu'), $db->quote('This is a hidden menu type for HD Video Share categories'));
      $query->clear()->insert($db->quoteName('#__menu_types')) ->columns($db->quoteName(array('menutype', 'title', 'description'))) ->values(implode(',', $menu_type_values));
      $db->setQuery($query);
      $db->query();
    }
    
    /** Set url for hidden category menu */ 
    $url = 'index.php?option=com_contushdvideoshare&view=category&catid='.$objCategoryTable->id;
    /** Check update is done */
    if($update == 1) {
      /** Check in the item */
      $objCategoryTable->checkin();
      $values = array($db->quote(HIDDENCATEGORYMENU), $db->quote( $catTitle ),$db->quote($alias),$db->quote($alias),$db->quote("$url"),$db->quote(COMPONENTTEXT),1,1,1,$extension_id,0,1,$db->quote('{"menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1}'));
      $query->clear() ->insert($db->quoteName(MENUTABLE)) ->columns($db->quoteName($columns)) ->values(implode(',', $values));
      $db->setQuery($query);
      $db->query();
    } else {
      /** Getcategory alias name */
       $query->clear() ->select('id') ->from(MENUTABLE)
      ->where($db->quoteName('link') . ' = ' . $db->quote('index.php?option=com_contushdvideoshare&view=category&catid=' . $category_id))
      ->where($db->quoteName('menutype') . ' = ' . $db->quote(HIDDENCATEGORYMENU)) ->where($db->quoteName('title') . ' = ' . $db->quote( $catTitle )) 
      ->where($db->quoteName(PUBLISH) . ' = ' . $db->quote('1')) ->order('id DESC');
      $db->setQuery($query);
      $Itemid = $db->loadResult();
    
      if(empty($Itemid)) {
        $values = array($db->quote(HIDDENCATEGORYMENU), $db->quote( $catTitle ), $db->quote($alias), $db->quote($alias), $db->quote("$url"), $db->quote(COMPONENTTEXT), 1, 1, 1, $extension_id, 0, 1, $db->quote('{"menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1}'));
        $query->clear() ->insert($db->quoteName(MENUTABLE)) ->columns($db->quoteName($columns)) ->values(implode(',', $values));
        $db->setQuery($query);
        $db->query();
      } else {
        /** Fields to update hidden category menu */
        $fields = array( $db->quoteName('menutype') . '=' . $db->quote(HIDDENCATEGORYMENU), $db->quoteName('title') . '=' . $db->quote( $catTitle ),
            $db->quoteName('alias') . '=' . $db->quote($alias), $db->quoteName('path') . '=' . $db->quote($alias), $db->quoteName('link') . '=' . $db->quote($url),
            $db->quoteName('type') . '=' . $db->quote(COMPONENTTEXT), $db->quoteName('component_id') . '=' . $db->quote($extension_id), $db->quoteName(PUBLISH) . '=' . $db->quote('1') );
        /** Conditions for which records should be updated. */
        $conditions = array( $db->quoteName('id') . '=' . (int) $Itemid );
    
        /**  Update streamer option,thumb url and file path */
        $query->clear() ->update($db->quoteName(MENUTABLE))->set($fields)->where($conditions);
        $db->setQuery($query);
        $db->query();
      }
    }
  }
  
  /**
   * Function to fetch categories,ads and adding new video
   *
   * @param int $parent_id
   *          category parent id
   * @param int $left
   *          category order
   *          
   * @return rebuild
   */
  public function rebuild($parent_id = 0, $left = 0) {
    /** Get the database object */
    $db = JFactory::getDBO ();
    $query = $db->getQuery ( true );
    
    /** Get all children of this node */
    $query->clear ()->select ( $db->quoteName ( array ( 'id' ) ) )->from ( $db->quoteName ( CATEGORYTABLE ) )->where ( $db->quoteName ( PARENT_ID ) . '=\'' . ( int ) $parent_id . '\'' )->order ( 'category ASC' );
    $db->setQuery ( $query );
    $children = $db->loadObjectList ();
    
    /** The right value of this node is the left value + 1 */
    $right = $left + 1;
    
    /** Execute this function recursively over all children */
    for($i = 0, $n = count ( $children ); $i < $n; $i ++) {
      /** $right is the current right value, which is incremented on recursion return */
      $right = $this->rebuild ( $children [$i]->id, $right );
      
      /** If there is an update failure, return false to break out of the recursion */
      if ($right === false) {
        return false;
      }
    }
    
    /** Fields to update */
    $fields = array ( $db->quoteName ( 'lft' ) . '=\'' . ( int ) $left . '\'', $db->quoteName ( 'rgt' ) . '=\'' . ( int ) $right . '\'' );
    
    /** Conditions for which records should be updated */
    $conditions = array ( $db->quoteName ( 'id' ) . '=' . ( int ) $parent_id  );
    
    /** Update streamer option,thumb url and file path */
    $query->clear ()->update ( $db->quoteName ( CATEGORYTABLE ) )->set ( $fields )->where ( $conditions );
    $db->setQuery ( $query );
    
    /** If there is an update failure, return false to break out of the recursion */
    if (! $db->query ()) {
      return false;
    }
    
    /** Return the right value of this node + 1 */
    return $right + 1;
  }
  
  /**
   * Function to fetch categories,ads and adding new video
   *
   * @param array $arrayIDs
   *          category detail array
   *          
   * @return addvideosmodel
   */
  public function deletecategary($arrayIDs) {
    global $db;
    $query = $db->getQuery ( true );
    
    if (count ( $arrayIDs )) {
      $cids = implode ( ',', $arrayIDs );
      $query->clear ()->delete ( $db->quoteName ( CATEGORYTABLE ) )->where ( $db->quoteName ( 'id' ) . 'IN (' . $cids . ')' );
      $db->setQuery ( $query );
      $db->query ();
    }
  }
  
  /**
   * Function to fetch category parent ids
   *
   * @param array $id
   *          category id
   *          
   * @return array
   */
  public function getcategoryids($id) {
    global $db, $cateid;
    $categoryid = '';
    $query = $db->getQuery ( true );
    $query->clear ()->select ( $db->quoteName ( array ( 'id' ) ) )->from ( $db->quoteName ( CATEGORYTABLE ) )->where ( $db->quoteName ( PARENT_ID ) . 'IN (' . $id . ') AND published != -2' );
    $db->setQuery ( $query );
    $categoryids = $db->loadColumn ();
    $cateid .= $id;
    
    if (! empty ( $categoryids )) {
      $cids = implode ( ',', $categoryids );
      $cateid .= ',';
      $categoryid = ContushdvideoshareModelcategory::getcategoryids ( $cids );
    }
    
    return $cateid;
  }
  
  /**
   * Function to fetch categories,ads and adding new video
   *
   * @param array $arrayIDs
   *          category detail array
   *          
   * @return addvideosmodel
   */
  public function changeStatus($arrayIDs) {
    global $mainframe, $db;
    $query = $db->getQuery ( true );
    $link = 'index.php?option=com_contushdvideoshare&layout=category';
    
    if ($arrayIDs ['task'] == "publish") {
      $publish = 1;
      $msg = 'Published Successfully';
    } elseif ($arrayIDs ['task'] == 'trash') {
      $publish = - 2;
      $msg = 'Trashed Successfully';
    } else {
      $publish = 0;
      $msg = 'Unpublished Successfully';
    }
    
    $cids1 = $arrayIDs ['cid'];
    $categoryTable = JTable::getInstance ( CATEGORY, 'Table' );
    $cids = implode ( ',', $arrayIDs ['cid'] );
    $query->clear ()->select ( $db->quoteName ( array ( PARENT_ID ) ) )->from ( $db->quoteName ( CATEGORYTABLE ) )
    ->where ( $db->quoteName ( 'id' ) . 'IN (' . $cids . ') AND published != -2' );
    $db->setQuery ( $query );
    $options = $db->loadResult ();
    
    /** Recurse through children if they exist */
    $categoryid = array ();
    
    if (! empty ( $cids1 )) {
      foreach ( $cids1 as $cids2 ) {
        $categoryid = ContushdvideoshareModelcategory::getcategoryids ( $cids2 );
      }
    }
    
    if ($options != 0) {
      $query->clear ()->select ( $db->quoteName ( array ( PUBLISH ) ) )->from ( $db->quoteName ( CATEGORYTABLE ) )
      ->where ( $db->quoteName ( 'id' ) . 'IN (' . $options . ') AND published != -2' );
      $db->setQuery ( $query );
      $published = $db->loadResult ();
      
      if ($published == 0) {
        $msg = 'Cannot change the published state when the parent category is of a lesser state . ';        
        $mainframe->redirect ( $link, $msg, MESSAGE );
      }
    }
    
    $categoryTable->publish ( $cids1, $publish );
    
    /** Fields to update */
    $fields = array ( $db->quoteName ( PUBLISH ) . '=' . $publish );
    
    /** Conditions for which records should be updated */
    $conditions = array ( $db->quoteName ( PARENT_ID ) . ' IN (' . $categoryid . ' ) AND published != -2' );
    
    /** Update streamer option,thumb url and file path */
    $query->clear ()->update ( $db->quoteName ( CATEGORYTABLE ) )->set ( $fields )->where ( $conditions );
    $db->setQuery ( $query );
    $db->query ();
    $mainframe->redirect ( $link, $msg, MESSAGE );
  }
}
