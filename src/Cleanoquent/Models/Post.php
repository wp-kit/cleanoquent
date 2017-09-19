<?php
	
	namespace WPKit\Cleanoquent\Models;
	
	use WPEloquent\Model\Post as BasePost;
	use WPKit\SoftDeletes\SoftDeletes;
	use WPKit\MagicMeta\Traits\IsMagic;
	use WPKit\MagicMeta\Traits\TransformsQuery;
	
	class Post extends BasePost {
  
	    use SoftDeletes, IsMagic, TransformsQuery;
	    
	    /**
	     * Enable timestamps.
	     *
	     * @var boolean
	     */
	    public $timestamps = true;
	    
	    /**
	     * The name of the "created at" column.
	     *
	     * @var string
	     */
	    const CREATED_AT = 'post_date';
	
	    /**
	     * The name of the "updated at" column.
	     *
	     * @var string
	     */
	    const UPDATED_AT = 'post_modified';
	
	    /**
	     * The name of the "deleted at" column.
	     *
	     * @var string
	     */
	    const DELETED_AT = 'post_status';
			
		/**
	     * The hidden attributes that are mass assignable.
	     *
	     * @var array
	     */
		protected $hidden = [
			'ID',
			'post_title',
			'post_status',
			'post_author', 
			'post_content',
			'post_excerpt',
			'comment_status',
			'ping_status',
			'post_password',
			'to_ping',
			'pinged',
			'post_content_filtered',
			'post_parent',
			'guid',
			'menu_order',
			'post_type',
			'post_mime_type',
			'comment_count',
			'post_date_gmt',
			'post_modified_gmt',
			'post_name',
			'post_date',
			'post_modified'
		];
		
		/**
	     * Create a new Eloquent model instance.
	     *
	     * @param  array  $attributes
	     * @return void
	     */
	    public function __construct(array $attributes = [])
	    {
	        parent::__construct($attributes);
	        $this->buildAppends();
	    }
	    
	     /**
	     * Get post type
	     *
	     * @return string
	     */
	    public function getPostType() 
	    {
		    return $this->post_type;
	    }
	    
	    /**
	     * Build $appends property
	     *
	     * @return void
	     */
	    protected function buildAppends() {
		    
		    $appends = [
				'id',
			];
			
			$post_types = is_array( $this->getPostType() ) ? $this->getPostType() : [$this->getPostType()];
			
			foreach($post_types as $post_type) {
			
				$post_type = get_post_type_object( $post_type );
				
				if( $post_type ) {
				
					if( post_type_supports( $post_type->name, 'title' ) ) {
						
						$appends = array_merge($appends, [
							'title'
						]);
						
					}
					
					if( post_type_supports( $post_type->name, 'editor' ) ) {
						
						$appends = array_merge($appends, [
							'content'
						]);
						
					}
					
					if( post_type_supports( $post_type->name, 'author' ) ) {
						
						$appends = array_merge($appends, [
							'author_id'
						]);
						
					}
					
					if( post_type_supports( $post_type->name, 'thumbnail' ) ) {
						
						$appends = array_merge($appends, [
							'thumbnail_id'
						]);
						
					}
					
					if( $post_type->public ) {
						
						$appends = array_merge($appends, [
							'url',
							'comments_open'
						]);
						
					}
					
				}
				
			}
			
			if( is_multisite() ) {
					
				$appends = array_merge($appends, [
					'blog_id'
				]);
				
			}
			
			$appends = array_unique($appends);
			
			$this->setAppends(array_merge($appends, $this->appends));
		    
	    }
	    
	    /**
	     * Get Id Attribute
	     *
	     * @var string
	     */
	    public function getIdAttribute(){
		    return $this->attributes['ID'];
		}
		
		/**
	     * Get Author Id Attribute
	     *
	     * @var string
	     */
	    public function getAuthorIdAttribute(){
		    return $this->attributes['post_author'];
		}
		
		/**
	     * Get Blog Id Attribute
	     *
	     * @var string
	     */
	    public function getBlogIdAttribute(){
		    return ! empty( $this->attributes['blog_id'] ) ? $this->attributes['blog_id'] : null;
		}
		
		/**
	     * Get Thumbnail Id Attribute
	     *
	     * @var string
	     */
	    public function getThumbnailIdAttribute(){
		    return get_post_thumbnail_id( $this->ID );
		}
	    
	    /**
	     * Get Title Attribute
	     *
	     * @var string
	     */
	    public function getTitleAttribute(){
		    return $this->attributes['post_title'];
		}
		
		/**
	     * Get Url Attribute
	     *
	     * @var string
	     */
	    public function getUrlAttribute(){
		    return get_permalink( $this->ID );
		}
		
		/**
	     * Get Content Attribute
	     *
	     * @var string
	     */
	    public function getContentAttribute(){
		    return $this->attributes['post_content'];
		}
		
		/**
	     * Get Status Attribute
	     *
	     * @var string
	     */
		public function getStatusAttribute(){
		    return ! empty( $this->attributes['post_status'] ) ? $this->attributes['post_status'] : 'publish';
		}
		
		/**
	     * Get Type Attribute
	     *
	     * @var string
	     */
		public function getTypeAttribute(){
		    return ! empty( $this->attributes['post_type'] ) ? $this->attributes['post_type'] : 'post';
		}
		
		/**
	     * Get Date Added Attribute
	     *
	     * @var string
	     */
		public function getDateAddedAttribute(){
		    return $this->attributes['post_date'];
		}
		
		/**
	     * Get Date Modified Attribute
	     *
	     * @var string
	     */
		public function getDateModifiedAttribute(){
		    return $this->attributes['post_modified'];
		}
		
		/**
	     * Get Comments Open Attribute
	     *
	     * @var boolean
	     */
		public function getCommentsOpenAttribute() {
			return 'open' == $this->comment_status;
		}
		
		/**
	     * Convert the model's attributes to an array.
	     *
	     * @return array
	     */
	    public function attributesToArray()
	    {
	        $attributes = $this->getArrayableAttributes();
	
	        // If an attribute is a date, we will cast it to a string after converting it
	        // to a DateTime / Carbon instance. This is so we will get some consistent
	        // formatting while accessing attributes vs. arraying / JSONing a model.
	        foreach ($this->getDates() as $key) {
	            if (! isset($attributes[$key])) {
	                continue;
	            }
	
	            $attributes[$key] = $this->serializeDate(
	                $this->asDateTime($attributes[$key])
	            );
	        }
	
	        $mutatedAttributes = $this->getMutatedAttributes();
	
	        // We want to spin through all the mutated attributes for this model and call
	        // the mutator for the attribute. We cache off every mutated attributes so
	        // we don't have to constantly check on attributes that actually change.
	        foreach ($mutatedAttributes as $key) {
	            if (! array_key_exists($key, $attributes)) {
	                continue;
	            }
	
	            $attributes[$key] = $this->mutateAttributeForArray(
	                $key, $attributes[$key]
	            );
	        }
	
	        // Next we will handle any casts that have been setup for this model and cast
	        // the values to their appropriate type. If the attribute has a mutator we
	        // will not perform the cast on those attributes to avoid any confusion.
	        foreach ($this->getCasts() as $key => $value) {
	            if (! array_key_exists($key, $attributes) ||
	                in_array($key, $mutatedAttributes)) {
	                continue;
	            }
	
	            $attributes[$key] = $this->castAttribute(
	                $key, $attributes[$key]
	            );
	
	            if ($attributes[$key] && ($value === 'date' || $value === 'datetime')) {
	                $attributes[$key] = $this->serializeDate($attributes[$key]);
	            }
	        }
	        
	        // Here we will grab all of the appended, calculated attributes to this model
	        // as these attributes are not really in the attributes array, but are run
	        // when we need to array or JSON the model for convenience to the coder.
	        foreach ($this->getArrayableAppends() as $key) {
	            $attributes[$key] = $this->mutateAttributeForArray($key, null);
	        }
	        
	        // Here we will grab all of the magic meta, calculated attributes to this model
	        // as these attributes are not really in the attributes array, but are run
	        // when we need to array or JSON the model for convenience to the coder.
	        foreach($this->getMagicMeta() as $meta_key => $key) {
			    $attributes[$key] = $this->mutateMetaValue($key, $this->getMeta($meta_key));
		    }
	
	        // Here we will grab all of the appended, calculated attributes to this model
	        // as these attributes are not really in the attributes array, but are run
	        // when we need to array or JSON the model for convenience to the coder.
	        foreach ($this->getArrayableAppendsAfterMagicMeta() as $key) {
	            $attributes[$key] = $this->mutateAttributeForArray($key, null);
	        }
	
	        return $attributes;
	        
	    }
	    
	    public function mutateMetaValue($key, $value) {
		    
		    $value = is_numeric($value) ? (int) $value : $value;
		    
		    return $value;
		    
	    }
	    
	    public function getArrayableAppendsAfterMagicMeta() {
		    return [
				'status',
				'type',
				'date_added',
				'date_modified'
			];
	    }
		
	}
