<?php


require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/PhraseanetOauth2Connector.php';
require_once __DIR__.'/Databox.php';
require_once __DIR__.'/DataboxCollection.php';
require_once __DIR__.'/DataboxDocumentStructure.php';
require_once __DIR__.'/User.php';



use PhraseanetSDK\Exception\ExceptionInterface;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\UnauthorizedException;
use PhraseanetSDK\Repository\DataboxCollection;
use PhraseanetSDK\Repository\Databox;
use PhraseanetSDK\Repository\DataboxDocumentStructure;
use PhraseanetSDK\Repository\User;

/**
* Init Phraseanet_WP
* @param $client_id,$client_secret,$phraseanet_url,$callback_url
* @since 1.0.0
*
* */
class Phraseanet_WP
{
    protected $client_id;
    protected $client_secret;
    protected $phraseanet_url;
    protected $callback_url;
    protected $app;
    protected $api_adapter;
    public $token;

    /**
     * @param $phraseanet_client_id string
     * @param $phraseanet_client_secret string
     * @param $phraseanet_url string
     * @param $phraseanet_auth_type string
     * @param $options array
     * @since 1.0.0
     */
    public function __construct($phraseanet_client_id, $phraseanet_client_secret, $phraseanet_url, $phraseanet_auth_type, $options)
    {
        $this->client_id = $phraseanet_client_id;
        $this->client_secret = $phraseanet_client_secret;
        $this->phraseanet_url = $phraseanet_url;

        if (!empty($phraseanet_url) and !empty($phraseanet_client_secret) and !empty($phraseanet_client_id)) {
            try {
                $guzzleAdapter = PhraseanetSDK\Http\GuzzleAdapter::create($phraseanet_url, []);

                $guzzleAdapter->setExtended(true);



                $p = new PhraseanetOauth2Connector($guzzleAdapter, $phraseanet_client_id, $phraseanet_client_secret);


                //Check the auth type if login get token using phraseanet username and password
                if ($phraseanet_auth_type=='login') {
                    $phraseanet_username  = $options['phraseanet_username'];
                    $phraseanet_password  = $options['phraseanet_password'];

                    $this->token = $p->retrieveAccessTokenByPassword($phraseanet_username, $phraseanet_password);

                    if ($this->token == false) {
                        return false;
                    }
                } else {
                    $phraseanet_token = $options['phraseanet_token'];

                    $this->token = $phraseanet_token;
                }

                $connectedGuzzleAdapter = new PhraseanetSDK\Http\ConnectedGuzzleAdapter($this->token, $guzzleAdapter);

                $this->api_adapter = new PhraseanetSDK\Http\APIGuzzleAdapter($connectedGuzzleAdapter);

                return $this->app = new PhraseanetSDK\EntityManager($this->api_adapter);
            } catch (RuntimeException $e) {
                return false;
            }
        } else {
            return false;
        }
    }


    //Public facing methods

    /**
     * get assets list
     * @since 1.0.0
     */
    public function getMedia()
    {
        $search_query 	= sanitize_text_field($_POST['searchQuery']);
        $search_type  	= sanitize_text_field($_POST['searchType']);
        $record_type	= sanitize_text_field($_POST['recordType']);
        $collections	= !empty(sanitize_text_field($_POST['collections'])) ? explode(",", sanitize_text_field($_POST['collections'])) : array() ;
        $current_page 	= (sanitize_text_field($_POST['pageNb']) < 1) ? 1 : sanitize_text_field($_POST['pageNb']);
        $sort           = !empty(sanitize_text_field($_POST['sort'])) ? sanitize_text_field($_POST['sort']) : 'relevance';
        $ord            = !empty(sanitize_text_field($_POST['order'])) ? sanitize_text_field($_POST['order']) : 'desc';
        $mediaList 		= array();

        
        $search_query = str_ireplace("\\", "", $search_query);
        

        // The output contains at least a status and the search query parameters
        $output 		= array(
            's' 			=> 'success',
            'searchQuery'	=> $search_query,
            'searchType'	=> $search_type,
            'recordType'	=> $record_type
        );

        $recordRepository = $this->app->getRepository('Record');
        $record_type = $record_type == 'video' ? '' : $record_type;
        $perpage = empty(get_option('phraseanet_per_page_records')) ? 150 : get_option('phraseanet_per_page_records');


        $params = [

        'query' 		=> "$search_query",
        'bases' 		=> $collections,
        'offset_start' 	=> $current_page * $perpage - $perpage,
        'per_page' 		=> $perpage,
        'record_type' 	=> $record_type,
        'search_type'	=> $search_type,
        'sort'          => $sort,
        'ord'           => $ord
      ];

        require_once plugin_dir_path(dirname(__FILE__)) . './phraseanet.php';


        if (load_license()->can_use_premium_code()) {
            $params = [

            'query' 		=> "$search_query",
            'bases' 		=> $collections,
            'offset_start' 	=> $current_page * $perpage - $perpage,
            'per_page' 		=> $perpage,
            'record_type' 	=> $record_type,
            'search_type'	=> $search_type,
            'sort'          => $sort,
            'ord'           => $ord
          ];
        } else {
            $params = [

            'query' 		=> "$search_query",
            'bases' 		=> $collections,
            'offset_start' 	 => 0 ,
            'per_page' 		=> 100,
            'record_type' 	=> $record_type,
            'search_type'	=> $search_type,
            'sort'          => $sort,
            'ord'           => $ord
          ];

            if ($current_page > 1) {
                $output['s'] = 'activation-required';
                $output['sMsg'] = 'To get more results activate your license';
                return $output;
            }
        }



        try {
            $query = $recordRepository->search($params);
        } catch (RuntimeException $e) {
            $output['s'] = 'error';
            $output['sMsg'] = ('Error. You should check the Phraseanet Base URL in the plugin settings.');
        } catch (NotFoundException $e) {
            $output['s'] = 'error';
            $output['sMsg'] = ('Entity or Controler not Found.');
        } catch (UnauthorizedException $e) {
            $output['s'] = 'error';
            $output['sMsg'] = ('Error. You should check the Oauth Token in the plugin settings.');
        } catch (ExceptionInterface $e) {
            $output['s'] = 'error';
            $output['sMsg'] = ($e.'Error. There was a Exception thrown by the Phraseanet SDK.');
        } catch (\Exception $e) {
            $output['s'] = 'error';
            $output['sMsg'] = ('Error. Something went wrong with the Phraseanet SDK.');
        }

        if (!isset($query)) {
            $output['s'] = 'Something went wrong Please check your auth';
            $output['sMsg'] = 'Something went wrong Please check your auth';
            return $output;
        }



        $output['params'] = $params;
        array_push($output['params'], ['time'=>$query->getQueryTime(),'error'=>$query->getError()]);




        // If no error
        if ($output['s'] != 'error') {
            $results = $query->getResults()->getRecords();


            // Is there some results ?
            if (count($results) > 0) {
                $output['total'] = $query->getTotalResults();

                $total_pages = ceil($output['total'] / $perpage);

                // Correct the current_page
                if ($current_page > $total_pages) {
                    $current_page = $total_pages;
                }

                $output['pagination'] = $this->pagination($total_pages, $current_page);


                // Loop through records
                foreach ($results as $record) {
                    $mediaThumb = $record->getThumbnail();


                    $mediaList[] = array(
                            'id' 			    => $record->getRecordId(),
                            'title'			    => $record->getTitle(),
                            'thumb'			    => ($mediaThumb != null) ? $mediaThumb->getPermalink()->getUrl() : '' . 'no-preview/no-preview-' . $record->getPhraseaType() . '.png',
                            'download'          => $this->getUrl($record, $record->getPhraseaType()),
                            'phraseaType'	    => $record->getPhraseaType(),
                            'preview'		    => $this->mediaPreview($record),
                            'details'		    => $this->getTechDetails($record, 'All'), //$record->getTechnicalInformation(),
                            'meta'              => $this->getMetaData($record), //$record->getMetadata()
                            'phraseanet_url'    => $this->phraseanet_url,
                            'subdef'            => $this->getSubdefs($record)


                    );
                }

                $output['mediaList'] = $mediaList;
                $output['facets']    = $this->getFacets($query);
            }
            // No results
            else {
                $output['s'] = 'no-results';
                $output['sMsg'] = 'No results.';
            }
        }


        return $output;
    }


    /**
     *
     * @return String Token
     * @since 1.0.0
     */
    public function getToken()
    {
        return $this->token;
    }



    //Private/protected methods

    /**
     * Pagination
     * @param $total_pages
     * @param $current_page
     * @return HTML
     * @since 1.0.0
     */
    private function pagination($total_pages, $current_page)
    {
        $pagination = '';


        // Nb of pages displayed around a page number if in the middle of the list
        $adj_pages = 1;

        // Nb min of pages to get a truncature
        // it's calculated like this : First 2 pages + {truncature} + {adj_pages} + {current_page} + {adj_pages} + {truncature} + 2 last pages
        $min_pages_links = 2 + 1 + $adj_pages + 1 + $adj_pages + 1 + 2;

        if ($total_pages > 1) {
            $pagination .= '<nav aria-label="Page navigation d-flex justify-content-center"><ul class="pagination">';

            // ========================================================
            // Total pages lower than $min_pages_links -> No Truncature
            // ========================================================
            if ($total_pages < $min_pages_links) {
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $current_page) {
                        $pagination .= '<li class="page-item active"><a class="page-link" href="javascript:void(0);">'.$i.'</a></li>';
                    } else {
                        $pagination .= '<li class="page-item"><a class="page-link" id="'.$i.'" href="javascript:void(0);">'.$i.'</a></li>
                        ';
                    }
                }
            }
            // ================================================================
            // Total pages greater or equal than $min_pages_links -> Truncature
            // ================================================================
            else {

                // Page index max to consider the current_page in the first pages
                // Calculate : 2 first pages + {adj_pages} + {current_page}
                $page_index_max_for_first_pages = 2 + $adj_pages + 1;

                // Page index max to consider the current_page in the last pages
                // Calculate : {total_pages} - 2 last pages - {adj_pages} - {current_page} + 1 (because of the reverse count)
                $page_index_min_for_last_pages = $total_pages - 2 - $adj_pages - 1 + 1;

                // FIRST CASE : the current page is in the first pages
                if ($current_page <= $page_index_max_for_first_pages) {

                    // First pages until the truncature
                    // max number pages displayed is : First 2 pages + {adj_pages} + {current_page} + {adj_pages}
                    for ($i = 1; $i <= (2 + $adj_pages + 1 + $adj_pages); $i++) {
                        if ($i == $current_page) {
                            $pagination .= '<li class="page-item active"><a class="page-link" href="javascript:void(0);">'.$i.'</a></li> ';
                        } else {
                            $pagination .= '<li class="page-item"><a class="page-link"  id="'.$i.'" href="javascript:void(0);">'.$i.'</a></li>
                            ';
                        }
                    }

                    // The truncature and last 2 pages
                    $pagination .= '<span>&hellip;</span> ';
                    $pagination .= '<li class="page-item"><a class="page-link" id="'.($total_pages - 1).'" href="javascript:void(0);">'.($total_pages - 1).'</a></li>';
                    $pagination .= '<li class="page-item"><a class="page-link" id="'.$total_pages.'" href="javascript:void(0);">'.$total_pages.'</a></li> ';
                }
                // SECOND CASE : the current page is in the middle pages
                elseif (($current_page > $page_index_max_for_first_pages) && ($current_page < $page_index_min_for_last_pages)) {

                    // First 2 pages + truncature
                    $pagination .= '<li class="page-item"><a class="page-link" id="1" href="javascript:void(0);">1</a></li> ';
                    $pagination .= '<li class="page-item"><a class="page-link" id="2" href="javascript:void(0);">2</a></li>';
                    $pagination .= '<span>&hellip;</span> ';

                    // Pages in the middle
                    for ($i = $current_page - $adj_pages; $i <= $current_page + $adj_pages; $i++) {
                        if ($i == $current_page) {
                            $pagination .= '<li class="page-item active"><a class="page-link"  id="'.$i.'" href="javascript:void(0);">'.$i.'</a></li> ';
                        } else {
                            $pagination .= '<li class="page-item"><a class="page-link"  id="'.$i.'" href="javascript:void(0);">'.$i.'</a></li> ';
                        }
                    }

                    // Truncature and last 2 pages
                    $pagination .= '<span>&hellip;</span> ';
                    $pagination .= '<li class="page-item"><a class="page-link" id="'.($total_pages - 1).'" href="javascript:void(0);">'.($total_pages - 1).'</a></li>';
                    $pagination .= '<li class="page-item"><a class="page-link" id="'.$total_pages.'" href="javascript:void(0);">'.$total_pages.'</a></li>';
                }
                // THIRD CASE : the current page is in the last pages
                elseif ($current_page >= $page_index_min_for_last_pages) {

                    // First 2 pages + truncature
                    $pagination .= '<li class="page-item"><a class="page-link" id="1" href="javascript:void(0);">1</a></li>';
                    $pagination .= '<li class="page-item"><a class="page-link" id="2" href="javascript:void(0);">2</a></li>';
                    $pagination .= '<span>&hellip;</span> ';

                    // Last pages
                    for ($i = $page_index_min_for_last_pages - $adj_pages; $i <= $total_pages; $i++) {
                        if ($i == $current_page) {
                            $pagination .= '<li class="page-item active"><a class="page-link active" href="javascript:void(0);">'.$i.'</a></li>';
                        } else {
                            $pagination .= '<li class="page-item"><a class="page-link"  id="'.$i.'" href="javascript:void(0);">'.$i.'</a></li>';
                        }
                    }
                }
            }

            $pagination .= '</ul></nav>';
        }

        return $pagination;
    }




    /**
     *
     * get facets
     *
     * @since 1.0.0
     */
    public function getFacets($query='')
    {
        if (empty($query)) {
            $search_query 	= '';
            $search_type  	= '';
            $record_type	= '';
            $current_page 	= 1;
            $mediaList 		= array();
            // The output contains at least a status and the search query parameters
            $output 		= array(
                's' 			=> 'success',
                'searchQuery'	=> $search_query,
                'searchType'	=> $search_type,
                'recordType'	=> $record_type
            );


            $recordRepository = $this->app->getRepository('Record');
            $record_type = '';
            $perpage = 10;
            $params = [
            'query' 		=> "$search_query",
            'bases' 		=> array(),
            'offset_start' 	=> $current_page * 10 - 10,
            'per_page' 		=> $perpage,
            'record_type' 	=> $record_type,
            'search_type'	=> $search_type
          ];

            $query = $recordRepository->search($params);
        }


        $build_query = [];

        foreach ($query->getFacets() as $facets) {
            $parent = array($facets->getName());
            $items = array();

            foreach ($facets->getValues() as $facet) {
                array_push($items, ['parent'=>$facets->getName(),'value'=>$facet->getValue(),'query'=>$facet->getQuery(),'count'=>$facet->getCount()]);
            }

            array_push($parent, $items);

            array_push($build_query, $parent);
        }

        return $build_query;
    }



    /**
     * @param $record
     * @param $type
     * @return string
     * @since 1.0.0
     */
    private function getUrl($record, $type)
    {
        $url = '';

        foreach ($record->getSubdefs() as $i=>$subdef) {
            if ($type=='video') {
                if ($subdef->getMimeType()=='video/mp4' or $subdef->getMimeType()=='video/webm' or $subdef->getMimeType()=='video/ogg') {
                    $url = $subdef->getPermalink()->getUrl();
                    break;
                } else {
                    $url = ''; //Video not found
                }
            } else {
                if ($i==0) {
                    $url = $subdef->getPermalink()->getUrl();
                    break;
                }
            }
        }

        return $url;
    }


    /**
     * Return array of subdef's
     * @param $record
     * @return array
     * @since 1.0.0
     */
    private function getSubdefs($record)
    {
        $preview_infos = array();

        // Subdef exists ?
        try {
            $subDef = $record->getSubDefs('preview');
        } catch (NotFoundException $e) {
            $subDef = null;
        }

        switch ($record->getPhraseaType()) {

            case 'image':

                if ($subDef != null) {
                    foreach ($subDef as $i=> $sub) {
                        array_push($preview_infos, array(
                            'name'			=> $sub->getName(),
                            'thumb_url'		=> $sub->getPermalink()->getUrl(),
                            'width'			=> $sub->getWidth(),
                            'height'		=> $sub->getHeight()
                        ));
                    }
                }
                // No preview
                else {
                    array_push($preview_infos, array(
                        'name'			=> '',
                        'thumb_url'		=> '',
                        'width'			=> '',
                        'height'		=> ''
                    ));
                }

                break;
                case 'document':

                    if ($subDef != null) {
                        foreach ($subDef as $i=> $sub) {
                            array_push($preview_infos, array(
                                'name'			=> $sub->getName(),
                                'thumb_url'		=> $sub->getPermalink()->getUrl(),
                                'width'			=> $sub->getWidth(),
                                'height'		=> $sub->getHeight()
                            ));
                        }
                    }
                    // No preview
                    else {
                        array_push($preview_infos, array(
                            'name'			=> '',
                            'thumb_url'		=> '',
                            'width'			=> '',
                            'height'		=> ''
                        ));
                    }

                    break;
            case 'video':

                // Try the Thumbnail subdef for the video poster
                try {
                    $subDefThumb = $record->getSubDefs('thumbnail');
                } catch (NotFoundException $e) {
                    $subDefThumb = null;
                }

                if ($subDefThumb) {
                    foreach ($subDefThumb as $i=> $sub) {
                        array_push($preview_infos, array(
                            'name'			=> $sub->getName(),
                            'thumb_url'		=> $sub->getPermalink()->getUrl(),
                            'width'			=> $sub->getWidth(),
                            'height'		=> $sub->getHeight()
                        ));
                    }
                }

                break;

                case 'audio':

                    // Try the Thumbnail subdef for the video poster
                    try {
                        $subDefThumb = $record->getSubDefs('thumbnail');
                    } catch (NotFoundException $e) {
                        $subDefThumb = null;
                    }

                    if ($subDefThumb) {
                        foreach ($subDefThumb as $i=> $sub) {
                            array_push($preview_infos, array(
                                'name'			=> $sub->getName(),
                                'thumb_url'		=> $sub->getPermalink()->getUrl(),
                                'width'			=> $sub->getWidth(),
                                'height'		=> $sub->getHeight()
                            ));
                        }
                    }


                    break;

        }





        return $preview_infos;
    }

    public function getSubdefRaw($version)
    {
        if ($version == 'v3') {
            $databox = new Databox($this->app);
            return ($databox->getV3Subdefs());
        } else {
            $user = new User($this->app);
            return ($user->subdefs());
        }
    }



    /**
     * return array of meta data
     * @param $record
     * @return Array
     * @since 1.0.0
     */
    private function getMetaData($record)
    {
        $meta_data = [];

        foreach ($record->getMetaData() as $key=>$meta) {
            if (!in_array($meta->getName(), $meta_data)) {
                if (!empty($meta->getName())) {
                    if (@is_array($meta_data[$meta->getName()])) {
                        array_push($meta_data[$meta->getName()], [$meta->getName()]);
                    }
                }
            }

            if (!empty($meta_data[$meta->getName()])) {
                $meta_data[$meta->getName()] .= ", ".$meta->getValue();
            } else {
                @$meta_data[$meta->getName()] .= $meta->getValue();
            }
        }



        return $meta_data;
    }


    /**
     * return mediaPreview's
     * @param $record
     * @return Array
     * @since 1.0.0
     */
    private function mediaPreview($record)
    {
        $preview_infos = array();

        // Subdef exists ?
        try {
            $subDef = $record->getSubDefs('preview');
        } catch (NotFoundException $e) {
            $subDef = null;
        }

        switch ($record->getPhraseaType()) {

            case 'image':

                if ($subDef != null) {
                    foreach ($subDef as $i=> $sub) {
                        $preview_infos[] = array(
                        'thumb_url'		=> $sub->getPermalink()->getUrl(),
                        'width'			=> $sub->getWidth(),
                        'height'		=> $sub->getHeight()
                    );
                    }
                }
                // No preview
                else {
                    $preview_infos = array(
                        'url'		=> WPPSN_PLUGIN_IMAGES_URL . 'no-preview/no-preview-image-big.png'
                    );
                }

                break;

            case 'video':

                // Try the Thumbnail subdef for the video poster
                try {
                    $subDefThumb = $record->getSubDefs('thumbnail');
                } catch (NotFoundException $e) {
                    $subDefThumb = null;
                }

                if ($subDefThumb) {
                    foreach ($subDefThumb as $i=> $sub) {
                        $preview_infos['thumb_url'] = ($sub != null) ? $sub->getPermalink()->getUrl() : '';
                    }
                }



                    if ($subDef != null) {
                        foreach ($subDef as $sub) {
                            $mimeTypeArray = explode('/', $sub->getMimeType());

                            $preview_infos[$mimeTypeArray[1]] = $sub->getPermalink()->getUrl();
                        }
                    }
                    // No preview
                    else {
                        $preview_infos['nopreview']	= WPPSN_PLUGIN_IMAGES_URL . 'no-preview/no-preview-video-big.png';
                    }


                break;

                case 'audio':

                    // Try the Thumbnail subdef for the video poster
                    try {
                        $subDefThumb = $record->getSubDefs('thumbnail');
                    } catch (NotFoundException $e) {
                        $subDefThumb = null;
                    }

                    if ($subDefThumb) {
                        foreach ($subDefThumb as $i=> $sub) {
                            $preview_infos['thumb_url'] = ($sub != null) ? $sub->getPermalink()->getUrl() : '';
                        }
                    }



                        if ($subDef != null) {
                            foreach ($subDef as $sub) {
                                $mimeTypeArray = explode('/', $sub->getMimeType());

                                $preview_infos[$mimeTypeArray[1]] = $sub->getPermalink()->getUrl();
                            }
                        }
                        // No preview
                        else {
                            $preview_infos['nopreview']	= WPPSN_PLUGIN_IMAGES_URL . 'no-preview/no-preview-video-big.png';
                        }


                    break;

        }


        return $preview_infos;
    }



    /**
     * retrun technical details of the assets
     * @param $record
     * @param $type - Optional
     * @return Array
     * @since 1.0.0
     */
    private function getTechDetails($record, $type='All')
    {
        $details_array = [];
        foreach ($record->getTechnicalInformation() as $technical_detail) {
            if ($type=='All') {
                array_push($details_array, [$technical_detail->getName()=>$technical_detail->getValue()]);
            } elseif ($type==$technical_detail->getName()) {
                $details_array =  $technical_detail->getValue();
            }
        }


        return $details_array;
    }


    /**
     * get Collections
     * @return Array
     * @since 1.0.0
     */
    public function getCollection()
    {
        $store_collections = [];
        $store_collections_index = [];

        $boxes = new Databox($this->app);
        foreach ($boxes->findAll() as $box) {
            $collection = new DataboxCollection($this->app);

            try {
                if (!isset($store_collections_index[$box->name])) {
                    $store_collections_index[$box->name] = $box->name;
                }

                $colls = $collection->findByMyDatabox($box->databox_id);

                $box_collection = array();
                if ($colls!=false) {
                    foreach ($colls as $coll) {
                        if (!empty($coll->collection_id) and !empty($coll->name) and $box->databox_id == $coll->databox_id) {
                            $collection_array =  array('databox_name'=>$box->name,'databox_id'=>$box->databox_id,'base_id'=>$coll->base_id,'collection_id'=>$coll->collection_id,'collection_name'=>$coll->name);
                            array_push($box_collection, $collection_array);
                        }
                    }
                    unset($collection_array);
                }
            } catch (UnauthorizedException $e) {
            }

            if ($box_collection) {
                array_push($store_collections, $box_collection);
            }

            unset($box_collection);

            $store_collections_index[$box->name] = !empty($store_collections) ? array_shift($store_collections) : '';
        }

        foreach ($store_collections_index as $key=>$value) {
            if (is_null($value) || $value == '') {
                unset($store_collections_index[$key]);
            }
        }

        return $store_collections_index;
    }


    /**
     * getDataboxStructure
     * @since 1.0.0
     * @return Array
     */
    public function getDataboxStructure()
    {
        $databox_ids = !empty(sanitize_text_field($_POST['ids'])) ? sanitize_text_field($_POST['ids']) : '';



        $structure = new DataboxDocumentStructure($this->app);


        $structure_data = [];
        if (empty($databox_ids)) {
            $colls = $this->getCollection();
            $last_id = 0;
            foreach ($colls as $coll) {
                foreach ($coll as $c) {
                    if ($last_id!=$c['databox_id']) {
                        try {
                            $st = $structure->findByDatabox($c['databox_id']);

                            foreach ($st as $s) {
                                array_push($structure_data, $s->name);
                            }
                        } catch (Exception $e) {
                            echo "";
                        }
                    }

                    $last_id  = $c['databox_id'];
                }
            }
        } else {
            foreach (explode(",", $databox_ids) as $id) {
                try {
                    $st = $structure->findByDatabox($id);

                    foreach ($st as $s) {
                        array_push($structure_data, $s->name);
                    }
                } catch (Exception $e) {
                    echo "";
                }
            }
        }


        return $structure_data;
    }

    public function download_file()
    {
        $url = sanitize_text_field($_GET['file']);
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        $ext =  explode("?", $ext);
        $ext  = $ext[0];

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=".sanitize_text_field($_GET['file_name']).'.'.$ext);
        header("Content-Transfer-Encoding: binary ");
        echo readfile($url);
    }
}
