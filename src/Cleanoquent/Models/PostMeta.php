<?php
	
	namespace WPKit\Cleanoquent\Models;
	
	use WPEloquent\Model\Post\Meta as BasePostMeta;
	
	class PostMeta extends BasePostMeta {
		
		/**
	     * The hidden attributes that are mass assignable.
	     *
	     * @var array
	     */
		protected $hidden = [
			'meta_id',
			'post_id',
			'meta_key', 
			'meta_value'
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
		        $this->appends = array_merge([
					'key',
					'value'
				], $this->appends);
	    }
	    
	    /**
	     * Get Key Attribute
	     *
	     * @var string
	     */
	    public function getKeyAttribute(){
		    return $this->attributes['meta_key'];
		}
		
		/**
	     * Get Value Attribute
	     *
	     * @var string
	     */
		public function getValueAttribute(){
		    return maybe_unserialize( $this->attributes['meta_value'] );
		}
		
	}