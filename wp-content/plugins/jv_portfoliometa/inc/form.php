<div class="wrap-taxonomy-jvportfolio">
    <div id="taxonomy-jvportfolio" class="categorydiv">
        <ul id="jvportfolio-tabs" class="jvportfolio-tabs category-tabs">
            <li class="tabs"><a href="#jvportfolio-all"><?php _e( "General", 'jvportfolio' ); ?></a></li>
            <li class="hide-if-no-js"><a href="#jvportfolio-pop"><?php _e( "Config for item", 'jvportfolio' ); ?></a></li>
        </ul>
        
        <div id="jvportfolio-all" class="tabs-panel" style="max-height: initial;">
            
            <p>
                <label for="jvportfolio_col"><?php _e( "Column", 'jvportfolio' ); ?></label>
                <select class="widefat" name="jvportfolio_col" id="jvportfolio_col">
                    <option value="1" <?php selected( $data[ 'jvportfolio_col' ], '1' )?>><?php _e( "One column", 'jvportfolio' ); ?></option>        
                    <option value="2" <?php selected( $data[ 'jvportfolio_col' ], '2' )?>><?php _e( "Two column", 'jvportfolio' ); ?></option>
                    <option value="3" <?php selected( $data[ 'jvportfolio_col' ], '3' )?>><?php _e( "Three column", 'jvportfolio' ); ?></option>
                    <option value="4" <?php selected( $data[ 'jvportfolio_col' ], '4' )?>><?php _e( "Four column", 'jvportfolio' ); ?></option>
                    <option value="6" <?php selected( $data[ 'jvportfolio_col' ], '6' )?>><?php _e( "Six column", 'jvportfolio' ); ?></option>           
                </select>
            </p>
            
            <p>
                <label for="jvportfolio_fluid"><?php _e( "Full width", 'jvportfolio' ); ?></label>
                <select class="widefat" name="jvportfolio_fluid" id="jvportfolio_fluid">
                    <option value="0" <?php selected( $data[ 'jvportfolio_fluid' ], '0' )?>><?php _e( "False", 'jvportfolio' ); ?></option>        
                    <option value="1" <?php selected( $data[ 'jvportfolio_fluid' ], '1' )?>><?php _e( "True", 'jvportfolio' ); ?></option>
                </select>
            </p>

            <p>
                <label for="jvportfolio_limit"><?php _e( "Limit", 'jvportfolio' ); ?></label>
                <input class="widefat" type="number" name="jvportfolio_limit" id="jvportfolio_limit" min="1" value="<?php echo esc_attr( $data[ 'jvportfolio_limit' ] ); ?>" size="30" />
            </p>

            <p>
                <label for="jvportfolio_filter"><?php _e( "Filter", 'jvportfolio' ); ?></label>
                <select class="widefat" name="jvportfolio_filter" id="jvportfolio_filter">
                    <option value="0" <?php selected( $data[ 'jvportfolio_filter' ], '0' )?>><?php _e( "False", 'jvportfolio' ); ?></option>        
                    <option value="1" <?php selected( $data[ 'jvportfolio_filter' ], '1' )?>><?php _e( "True", 'jvportfolio' ); ?></option>
                </select>
            </p>

            <p>
                <label for="jvportfolio_sort" multiple><?php _e( "Sort", 'jvportfolio' ); ?></label>
                <select class="widefat" name="jvportfolio_sort[]" id="jvportfolio_sort" multiple>
                    <option value="date" <?php if( in_array( 'date', $data[ 'jvportfolio_sort' ] )) selected( 1 )?>><?php _e( "Date", 'jvportfolio' ); ?></option>        
                    <option value="name" <?php if( in_array( 'name', $data[ 'jvportfolio_sort' ] )) selected( 1 )?>><?php _e( "Name", 'jvportfolio' ); ?></option>
                </select>
            </p>

            <p>
                <label for="jvportfolio_mfetch"><?php _e( "Method fetch", 'jvportfolio' ); ?></label>
                <select class="widefat" name="jvportfolio_mfetch" id="jvportfolio_mfetch">
                    <option value="scroll" <?php selected( $data[ 'jvportfolio_mfetch' ], 'scroll' )?>><?php _e( "Scroll", 'jvportfolio' ); ?></option>        
                    <option value="button" <?php selected( $data[ 'jvportfolio_mfetch' ], 'button' )?>><?php _e( "Button", 'jvportfolio' ); ?></option>
                    <option value="nav" <?php selected( $data[ 'jvportfolio_mfetch' ], 'nav' )?>><?php _e( "Pagination", 'jvportfolio' ); ?></option>
                </select>
            </p>

            <p>
                <label for="jvportfolio_tags"><?php _e( "Tags", 'jvportfolio' ); ?></label>   
                <select class="widefat" name="jvportfolio_tags[]" id="jvportfolio_tags" multiple>
                    <?php foreach( $categories as $cat ) : ?>
                    <option value="<?php echo esc_attr( $cat->term_id ) ?>" <?php if( in_array( $cat->term_id, $data[ 'jvportfolio_tags' ] )) selected( 1 )?>><?php echo esc_attr( $cat->name ); ?></option>        
                    <?php endforeach; ?>                                                                                                            
                </select>
            </p>

        </div>
        
        <div id="jvportfolio-pop" class="tabs-panel" style="display: none; max-height: initial">
            
            <p>
                <label for="jvportfolio_stitle"><?php _e( "Show title", 'jvportfolio' ); ?></label>
                <select class="widefat" name="jvportfolio_stitle" id="jvportfolio_stitle">
                    <option value="0" <?php selected( $data[ 'jvportfolio_stitle' ], '0' )?>><?php _e( "False", 'jvportfolio' ); ?></option>        
                    <option value="1" <?php selected( $data[ 'jvportfolio_stitle' ], '1' )?>><?php _e( "True", 'jvportfolio' ); ?></option>
                </select>
            </p>

            <p>
                <label for="jvportfolio_stag"><?php _e( "Have tags", 'jvportfolio' ); ?></label>
                <select class="widefat" name="jvportfolio_stag" id="jvportfolio_stag">
                    <option value="0" <?php selected( $data[ 'jvportfolio_stag' ], '0' )?>><?php _e( "False", 'jvportfolio' ); ?></option>        
                    <option value="1" <?php selected( $data[ 'jvportfolio_stag' ], '1' )?>><?php _e( "True", 'jvportfolio' ); ?></option>
                </select>
            </p>

            <p>
                <label for="jvportfolio_sdate"><?php _e( "Show date", 'jvportfolio' ); ?></label>
                <select class="widefat" name="jvportfolio_sdate" id="jvportfolio_sdate">
                    <option value="0" <?php selected( $data[ 'jvportfolio_sdate' ], '0' )?>><?php _e( "False", 'jvportfolio' ); ?></option>        
                    <option value="1" <?php selected( $data[ 'jvportfolio_sdate' ], '1' )?>><?php _e( "True", 'jvportfolio' ); ?></option>
                </select>
            </p>
            
            <p>
                <label for="jvportfolio_slink"><?php _e( "Show link", 'jvportfolio' ); ?></label>
                <select class="widefat" name="jvportfolio_slink" id="jvportfolio_slink">
                    <option value="0" <?php selected( $data[ 'jvportfolio_slink' ], '0' )?>><?php _e( "False", 'jvportfolio' ); ?></option>        
                    <option value="1" <?php selected( $data[ 'jvportfolio_slink' ], '1' )?>><?php _e( "True", 'jvportfolio' ); ?></option>        
                </select>
            </p>
            
            <p>
                <label for="jvportfolio_sdesc"><?php _e( "Show short description", 'jvportfolio' ); ?></label>
                <select class="widefat" name="jvportfolio_sdesc" id="jvportfolio_sdesc">
                    <option value="0" <?php selected( $data[ 'jvportfolio_sdesc' ], '0' )?>><?php _e( "False", 'jvportfolio' ); ?></option>        
                    <option value="1" <?php selected( $data[ 'jvportfolio_sdesc' ], '1' )?>><?php _e( "True", 'jvportfolio' ); ?></option>        
                </select>
            </p>
            
        </div>
    </div>
    
    <p><label><strong><?php echo esc_attr_e( 'Shortcode', 'jvportfolio' ) ?></strong></label></p>
    <p><code class="widefat jvportfolio-view"></code></p>
</div>

<script>
	jQuery( function( $ ){
		
		var p = $( '[type="checkbox"][value="jvportoflio_optionmeta"]' )
			,c = $( '#page_template' )
			,v = $( '.postbox#jvportoflio_optionmeta' )
            ,w = $('.wrap-taxonomy-jvportfolio')
            ,s = w.find( '.jvportfolio-view' )
            ,m = {
                'jvportfolio_col'       : 'col',
                'jvportfolio_limit'     : 'limit',
                'jvportfolio_filter'    : 'filter',
                'jvportfolio_fluid'     : 'fluid',
                'jvportfolio_sort[]'    : 'sort',
                'jvportfolio_tags[]'    : 'tags',
                'jvportfolio_mfetch'    : 'mfetch',
                'jvportfolio_stitle'    : 'stitle',
                'jvportfolio_stag'      : 'stag',
                'jvportfolio_sdate'     : 'sdate',
                'jvportfolio_slink'     : 'slink',
                'jvportfolio_sdesc'     : 'sdesc'
            }
		;
		
		p.parent().hide();
		
		c.on({
			'change.template-name': function(){
			
				if( $( this ).val().match( /portfolio/ ) ) {
					
					v.removeClass( 'hide-if-js' );
					
				}else {
					
					v.addClass( 'hide-if-js' );
					
				}
			}
		}).trigger( 'change.template-name' );
        
        
        w.delegate( '[name]', 'change.soption', function(){
            
            var d = {}, code = [ "[jvpfo" ];
            
            $.each( w.find( '[name]' ).serializeArray(), function(){
                
                if( this.name in d ) {
                    
                    d[ this.name ] = [ d[ this.name ], this.value ].join( ',' );
                }else {
                    
                    d[ this.name ] = this.value;    
                }  
                
            } );
            
            $.each( m, function( k, v ) {
                
                !d[ k ] || code.push( [ v, '"' + d[ k ] + '"' ].join( '=' ) );        
                
            } );                          
            
            s.html( code.join( ' ' ) + ']' );
            
        }).find( '[name]' ).first().trigger( 'change.soption' );
	} );
</script>