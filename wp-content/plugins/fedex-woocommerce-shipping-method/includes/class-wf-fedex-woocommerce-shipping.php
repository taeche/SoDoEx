<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wf_fedex_woocommerce_shipping_method extends WC_Shipping_Method {
	private $found_rates;
	private $services;

	public function __construct() {
		$this->id                               = WF_Fedex_ID;
		$this->method_title                     = __( 'FedEx Basic', 'wf-shipping-fedex' );
		$this->method_description               = __( 'Obtains  real time shipping rates via FedEx Shipping API. Upgrade to Premium version for Print label & Tracking features.', 'wf-shipping-fedex' );
		$this->rateservice_version              = 16;
		$this->addressvalidationservice_version = 2;
		$this->services                         = include( 'data-wf-service-codes.php' );
		$this->init();
	}

	private function init() {
		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->title           = $this->get_option( 'title', $this->method_title );
		$this->origin          = apply_filters( 'woocommerce_fedex_origin_postal_code', str_replace( ' ', '', strtoupper( $this->get_option( 'origin' ) ) ) );
		$this->origin_country  = apply_filters( 'woocommerce_fedex_origin_country_code', WC()->countries->get_base_country() );
		$this->account_number  = $this->get_option( 'account_number' );
		$this->meter_number    = $this->get_option( 'meter_number' );
		$this->smartpost_hub   = $this->get_option( 'smartpost_hub' );
		$this->indicia   	   = $this->get_option( 'indicia' );

		$this->api_key         = $this->get_option( 'api_key' );
		$this->api_pass        = $this->get_option( 'api_pass' );
		$this->production      = ( $bool = $this->get_option( 'production' ) ) && $bool == 'yes' ? true : false;
		$this->debug           = ( $bool = $this->get_option( 'debug' ) ) && $bool == 'yes' ? true : false;
		$this->insure_contents = ( $bool = $this->get_option( 'insure_contents' ) ) && $bool == 'yes' ? true : false;
		$this->request_type    = $this->get_option( 'request_type', 'LIST' );
		$this->packing_method  = $this->get_option( 'packing_method', 'per_item' );
		$this->boxes           = $this->get_option( 'boxes', array( ));
		$this->custom_services = $this->get_option( 'services', array( ));
		$this->offer_rates     = $this->get_option( 'offer_rates', 'all' );
		$this->convert_currency_to_base     = $this->get_option( 'convert_currency');
		$this->residential     = ( $bool = $this->get_option( 'residential' ) ) && $bool == 'yes' ? true : false;
		$this->freight_enabled = false;
		$this->fedex_one_rate  = ( $bool = $this->get_option( 'fedex_one_rate' ) ) && $bool == 'yes' ? true : false;
		$this->fedex_one_rate_package_ids = array(
			'FEDEX_SMALL_BOX',
			'FEDEX_MEDIUM_BOX',
			'FEDEX_LARGE_BOX',
			'FEDEX_EXTRA_LARGE_BOX',
			'FEDEX_PAK',
			'FEDEX_ENVELOPE',
		);

		// Insure contents requires matching currency to country
		switch ( WC()->countries->get_base_country() ) {
			case 'US' :
				if ( 'USD' !== get_woocommerce_currency() ) {
					$this->insure_contents = false;
				}
			break;
			case 'CA' :
				if ( 'CAD' !== get_woocommerce_currency() ) {
					$this->insure_contents = false;
				}
			break;
		}

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	public function debug( $message, $type = 'notice' ) {
		if ( $this->debug ) {
			wc_add_notice( $message, $type );
		}
	}

	private function wf_get_fedex_currency(){
		if(get_woocommerce_currency() == 'GBP')
			return 'UKL';

		return get_woocommerce_currency();
	}

	private function environment_check() {
		if ( ! in_array( get_woocommerce_currency(), array( 'USD' ) )) {
			echo '<div class="notice">
				<p>' . __( 'FedEx API returns the rates in USD. Please enable Rates in base currency option in the plugin. Conversion happens only if FedEx API provide the exchange rates.', 'wf-shipping-fedex' ) . '</p>
			</div>';
		}

		if ( ! $this->origin && $this->enabled == 'yes' ) {
			echo '<div class="error">
				<p>' . __( 'FedEx is enabled, but the origin postcode has not been set.', 'wf-shipping-fedex' ) . '</p>
			</div>';
		}
	}

	public function admin_options() {
		// Check users environment supports this method
		$this->environment_check();
		?>
		<div class="wf-banner updated below-h2">
			<img class="scale-with-grid" src="http://www.wooforce.com/wp-content/uploads/2015/07/WooForce-Logo-Admin-Banner-Basic.png" alt="Wordpress / WooCommerce USPS, Canada Post Shipping | WooForce">
  			<p class="main"><strong>FedEx Premium version streamlines your complete shipping process and saves time</strong></p>
			<p>&nbsp;-&nbsp;Print shipping label with postage.<br>
			&nbsp;-&nbsp;Auto Shipment Tracking: It happens automatically while generating the label.<br>
			&nbsp;-&nbsp;Box packing.<br>
			&nbsp;-&nbsp;Enable/disable, edit the names of, and add handling costs to shipping services.<br>
			&nbsp;-&nbsp;Excellent Support for setting it up!</p>
			<p><a href="http://www.wooforce.com/product/fedex-woocommerce-shipping-with-print-label-plugin/" target="_blank" class="button button-primary">Upgrade to Premium Version</a> <a href="http://fedex.wooforce.com/wp-admin/admin.php?page=wc-settings&tab=shipping&section=wf_fedex_woocommerce_shipping_method" target="_blank" class="button">Live Demo</a></p>
		</div>
		<style>
		.wf-banner img {
			float: right;
			margin-left: 1em;
			padding: 15px 0
		}
		</style>
		<?php
		// Show settings
		parent::admin_options();
	}

	public function init_form_fields() {
		$this->form_fields  = include( 'data-wf-settings.php' );
	}

	public function generate_services_html() {
		return '';
	}

	public function validate_services_field( $key ) {
		$services         = array();
		$posted_services  = $this->services;

		foreach ( $posted_services as $code => $name ) {
			$services[ $code ] = array(
				'name'               => $name,
				'order'              => '',
				'enabled'            => true,
				'adjustment'         => '',
				'adjustment_percent' => ''
			);
		}

		return $services;
	}

	public function get_fedex_packages( $package ) {
		switch ( $this->packing_method ) {
			case 'per_item' :
			default :
				return $this->per_item_shipping( $package );
			break;
		}
	}

	private function per_item_shipping( $package ) {
		$to_ship  = array();
		$group_id = 1;

		// Get weight of order
		foreach ( $package['contents'] as $item_id => $values ) {

			if ( ! $values['data']->needs_shipping() ) {
				$this->debug( sprintf( __( 'Product # is virtual. Skipping.', 'wf-shipping-fedex' ), $item_id ), 'error' );
				continue;
			}

			if ( ! $values['data']->get_weight() ) {
				$this->debug( sprintf( __( 'Product # is missing weight. Aborting.', 'wf-shipping-fedex' ), $item_id ), 'error' );
				return;
			}

			$group = array();

			$group = array(
				'GroupNumber'       => $group_id,
				'GroupPackageCount' => $values['quantity'],
				'Weight' => array(
					'Value' => max( '0.5', round( woocommerce_get_weight( $values['data']->get_weight(), 'lbs' ), 2 ) ),
					'Units' => 'LB'
				),
				'packed_products' => array( $values['data'] )
			);

			if ( $values['data']->length && $values['data']->height && $values['data']->width ) {

				$dimensions = array( $values['data']->length, $values['data']->width, $values['data']->height );

				sort( $dimensions );

				$group['Dimensions'] = array(
					'Length' => max( 1, round( woocommerce_get_dimension( $dimensions[2], 'in' ), 2 ) ),
					'Width'  => max( 1, round( woocommerce_get_dimension( $dimensions[1], 'in' ), 2 ) ),
					'Height' => max( 1, round( woocommerce_get_dimension( $dimensions[0], 'in' ), 2 ) ),
					'Units'  => 'IN'
				);
			}

			$group['InsuredValue'] = array(
				'Amount'   => round( $values['data']->get_price() ),
				'Currency' => $this->wf_get_fedex_currency()
			);

			$to_ship[] = $group;

			$group_id++;
		}

		return $to_ship;
	}

	public function residential_address_validation( $package ) {
		$residential = $this->residential;

		// Address Validation API only available for production
		if ( $this->production ) {

			// Check if address is residential or commerical
			try {

				$client = new SoapClient( plugin_dir_path( dirname( __FILE__ ) ) . 'fedex-wsdl/production/AddressValidationService_v' . $this->addressvalidationservice_version. '.wsdl', array( 'trace' => 1 ) );

				$request = array();

				$request['WebAuthenticationDetail'] = array(
					'UserCredential' => array(
						'Key'      => $this->api_key,
						'Password' => $this->api_pass
					)
				);
				$request['ClientDetail'] = array(
					'AccountNumber' => $this->account_number,
					'MeterNumber'   => $this->meter_number
				);
				$request['TransactionDetail'] = array( 'CustomerTransactionId' => ' *** Address Validation Request v2 from WooCommerce ***' );
				$request['Version'] = array( 'ServiceId' => 'aval', 'Major' => $this->addressvalidationservice_version, 'Intermediate' => '0', 'Minor' => '0' );
				$request['RequestTimestamp'] = date( 'c' );
				$request['Options'] = array(
					'CheckResidentialStatus' => 1,
					'MaximumNumberOfMatches' => 1,
					'StreetAccuracy' => 'LOOSE',
					'DirectionalAccuracy' => 'LOOSE',
					'CompanyNameAccuracy' => 'LOOSE',
					'ConvertToUpperCase' => 1,
					'RecognizeAlternateCityNames' => 1,
					'ReturnParsedElements' => 1
				);
				$request['AddressesToValidate'] = array(
					0 => array(
						'AddressId' => 'WTC',
						'Address' => array(
							'StreetLines' => array( $package['destination']['address'], $package['destination']['address_2'] ),
							'PostalCode'  => $package['destination']['postcode'],
						)
					)
				);

				$response = $client->addressValidation( $request );

				if ( $response->HighestSeverity == 'SUCCESS' ) {
					if ( is_array( $response->AddressResults ) )
						$addressResult = $response->AddressResults[0];
					else
						$addressResult = $response->AddressResults;

					if ( $addressResult->ProposedAddressDetails->ResidentialStatus == 'BUSINESS' )
						$residential = false;
					elseif ( $addressResult->ProposedAddressDetails->ResidentialStatus == 'RESIDENTIAL' )
						$residential = true;
				}

			} catch (Exception $e) {}

		}

		$this->residential = apply_filters( 'woocommerce_fedex_address_type', $residential, $package );

		if ( $this->residential == false ) {
			$this->debug( __( 'Business Address', 'wf-shipping-fedex' ) );
		}
	}

	private function get_fedex_api_request( $package ) {
		$request = array();

		// Prepare Shipping Request for FedEx
		$request['WebAuthenticationDetail'] = array(
			'UserCredential' => array(
				'Key'      => $this->api_key,
				'Password' => $this->api_pass
			)
		);
		$request['ClientDetail'] = array(
			'AccountNumber' => $this->account_number,
			'MeterNumber'   => $this->meter_number
		);
		$request['TransactionDetail'] = array(
			'CustomerTransactionId'     => ' *** WooCommerce Rate Request ***'
		);
		$request['Version'] = array(
			'ServiceId'              => 'crs',
			'Major'                  => $this->rateservice_version,
			'Intermediate'           => '0',
			'Minor'                  => '0'
		);
		//$request['ReturnTransitAndCommit'] = false;
		$request['RequestedShipment']['PreferredCurrency'] = $this->wf_get_fedex_currency();
		$request['RequestedShipment']['DropoffType']       = 'REGULAR_PICKUP';
		$request['RequestedShipment']['ShipTimestamp']     = date( 'c' , strtotime( '+1 Weekday' ) );
		$request['RequestedShipment']['PackagingType']     = 'YOUR_PACKAGING';
		$request['RequestedShipment']['Shipper']           = array(
			'Address'               => array(
				'PostalCode'              => $this->origin,
				'CountryCode'             => $this->origin_country,
			)
		);
		$request['RequestedShipment']['ShippingChargesPayment'] = array(
			'PaymentType' => 'SENDER',
			'Payor' => array(
				'ResponsibleParty' => array(
					'AccountNumber'           => $this->account_number,
					'CountryCode'             => WC()->countries->get_base_country()
				)
			)
		);
		$request['RequestedShipment']['RateRequestTypes'] = $this->request_type === 'LIST' ? 'LIST' : 'NONE';
		$request['RequestedShipment']['Recipient'] = array(
			'Address' => array(
				'Residential'         => $this->residential,
				'PostalCode'          => str_replace( ' ', '', strtoupper( $package['destination']['postcode'] ) ),
				'City'                => strtoupper( $package['destination']['city'] ),
				'StateOrProvinceCode' => strlen( $package['destination']['state'] ) == 2 ? strtoupper( $package['destination']['state'] ) : '',
				'CountryCode'         => $package['destination']['country']
			)
		);

		return $request;
	}

	private function get_fedex_requests( $fedex_packages, $package, $request_type = '' ) {
		$requests = array();

		// All reguests for this package get this data
		$package_request = $this->get_fedex_api_request( $package );

		if ( $fedex_packages ) {
			// Fedex Supports a Max of 99 per request
			$parcel_chunks = array_chunk( $fedex_packages, 99 );

			foreach ( $parcel_chunks as $parcels ) {
				$request        = $package_request;
				$total_value    = 0;
				$total_packages = 0;
				$total_weight   = 0;
				$commodoties    = array();
				$freight_class  = '';

				// Store parcels as line items
				$request['RequestedShipment']['RequestedPackageLineItems'] = array();

				foreach ( $parcels as $key => $parcel ) {

					$single_package_weight = $parcel['Weight']['Value'];

					$parcel_request = $parcel;
					$total_value    += $parcel['InsuredValue']['Amount'] * $parcel['GroupPackageCount'];
					$total_packages += $parcel['GroupPackageCount'];
					$total_weight   += $parcel['Weight']['Value'] * $total_packages;

					// Work out the commodoties for CA shipments
					if ( $parcel_request['packed_products'] ) {
						foreach ( $parcel_request['packed_products'] as $product ) {
							if ( isset( $commodoties[ $product->id ] ) ) {
								$commodoties[ $product->id ]['Quantity'] ++;
								$commodoties[ $product->id ]['CustomsValue']['Amount'] += round( $product->get_price() );
								continue;
							}
							$commodoties[ $product->id ] = array(
								'Name'                 => sanitize_title( $product->get_title() ),
								'NumberOfPieces'       => 1,
								'Description'          => '',
								'CountryOfManufacture' => ( $country = get_post_meta( $product->id, 'CountryOfManufacture', true ) ) ? $country : WC()->countries->get_base_country(),
								'Weight'               => array(
									'Units'            => 'LB',
									'Value'            => max( '0.5', round( woocommerce_get_weight( $product->get_weight(), 'lbs' ), 2 ) ),
								),
								'Quantity'             => $parcel['GroupPackageCount'],
								'UnitPrice'            => array(
									'Amount'           => round( $product->get_price() ),
									'Currency'         => $this->wf_get_fedex_currency()
								),
								'CustomsValue'         => array(
									'Amount'           => $parcel['InsuredValue']['Amount'] * $parcel['GroupPackageCount'],
									'Currency'         => $this->wf_get_fedex_currency()
								)
							);
						}
					}

					// Is this valid for a ONE rate? Smart post does not support it
					if ( $this->fedex_one_rate && '' === $request_type && isset($parcel_request['package_id']) && in_array( $parcel_request['package_id'], $this->fedex_one_rate_package_ids )) {
						$request['RequestedShipment']['PackagingType']                                   = $parcel_request['package_id'];
						if('US' === $package['destination']['country'] && 'US' === $this->origin_country){
							$request['RequestedShipment']['SpecialServicesRequested']['SpecialServiceTypes'] = 'FEDEX_ONE_RATE';
						}
					}

					// Remove temp elements
					unset( $parcel_request['packed_products'] );
					unset( $parcel_request['package_id'] );

					if ( ! $this->insure_contents || 'smartpost' === $request_type ) {
						unset( $parcel_request['InsuredValue'] );
					}

					$parcel_request = array_merge( array( 'SequenceNumber' => $key + 1 ), $parcel_request );
					$request['RequestedShipment']['RequestedPackageLineItems'][] = $parcel_request;
				}

				// Size
				$request['RequestedShipment']['PackageCount'] = $total_packages;

				$indicia = $this->indicia;

				if($indicia == 'AUTOMATIC' && $single_package_weight >= 1)
					$indicia = 'PARCEL_SELECT';
				elseif($indicia == 'AUTOMATIC' && $single_package_weight < 1)
					$indicia = 'PRESORTED_STANDARD';


				// Smart post
				if ( 'smartpost' === $request_type ) {
					$request['RequestedShipment']['SmartPostDetail'] = array(
						'Indicia'              => $indicia,
						'HubId'                => $this->smartpost_hub,
						'AncillaryEndorsement' => 'ADDRESS_CORRECTION',
						'SpecialServices'      => ''
					);
					$request['RequestedShipment']['ServiceType'] = 'SMART_POST';

					// Smart post does not support insurance, but is insured up to $100
					if ( $this->insure_contents && round( $total_value ) > 100 ) {
						return false;
					}
				} elseif ( $this->insure_contents ) {
					$request['RequestedShipment']['TotalInsuredValue'] = array(
						'Amount'   => round( $total_value ),
						'Currency' => $this->wf_get_fedex_currency()
					);
				}

				$core_countries = array('US','CA');
				if (WC()->countries->get_base_country() !== $package['destination']['country'] || !in_array(WC()->countries->get_base_country(),$core_countries)) {
					$request['RequestedShipment']['CustomsClearanceDetail']['DutiesPayment'] = array(
						'PaymentType' => 'SENDER',
						'Payor' => array(
							'ResponsibleParty' => array(
								'AccountNumber'           => strtoupper( $this->account_number ),
								'CountryCode'             => WC()->countries->get_base_country()
							)
						)
					);
					$request['RequestedShipment']['CustomsClearanceDetail']['Commodities'] = array_values( $commodoties );

					if( !in_array(WC()->countries->get_base_country(),$core_countries)){
						$request['RequestedShipment']['CustomsClearanceDetail']['CommercialInvoice'] = array(
							'Purpose' => 'SOLD'
						);
					}
				}

				// Add request
				$requests[] = $request;
			}
		}

		return $requests;
	}

	public function calculate_shipping( $package ) {
		// Clear rates
		$this->found_rates = array();

		// Debugging
		$this->debug( __( 'FEDEX debug mode is on - to hide these messages, turn debug mode off in the settings.', 'wf-shipping-fedex' ) );

		// See if address is residential
		$this->residential_address_validation( $package );

		// Get requests
		$fedex_packages   = $this->get_fedex_packages( $package );
		$fedex_requests   = $this->get_fedex_requests( $fedex_packages, $package );

		if ( $fedex_requests ) {
			$this->run_package_request( $fedex_requests );
		}

		if ( ! empty( $this->custom_services['SMART_POST']['enabled'] ) && ! empty( $this->smartpost_hub ) && $package['destination']['country'] == 'US' && ( $smartpost_requests = $this->get_fedex_requests( $fedex_packages, $package, 'smartpost' ) ) ) {
			$this->run_package_request( $smartpost_requests );
		}

		// Ensure rates were found for all packages
		$packages_to_quote_count = sizeof( $fedex_requests );

		if ( $this->found_rates ) {
			foreach ( $this->found_rates as $key => $value ) {
				if ( $value['packages'] < $packages_to_quote_count ) {
					unset( $this->found_rates[ $key ] );
				}

				//edited by yongun
				// limit displaying results only for fedex ground and fedex 2days
				if ( !($value['id']=='wf_fedex_woocommerce_shipping:FEDEX_GROUND'
					    ||$value['id']=='wf_fedex_woocommerce_shipping:FEDEX_2_DAY'
				      )
				   ) {
					unset( $this->found_rates[ $key ] );
				}
			}
		}

		$this->add_found_rates();
	}
	private function constains($heystack,$needle){
		if (strpos($heystack,$needle) !== false) {
			return true;
		}else{
			return false;
		}
	}
	public function run_package_request( $requests ) {
		try {
			foreach ( $requests as $key => $request ) {
				$this->process_result( $this->get_result( $request ) );
			}
		} catch ( Exception $e ) {
			$this->debug( print_r( $e, true ), 'error' );
			return false;
		}
	}

	private function get_result( $request ) {
		$this->debug( 'FedEx REQUEST: <a href="#" class="debug_reveal">Reveal</a><pre class="debug_info" style="background:#EEE;border:1px solid #DDD;padding:5px;">' . print_r( $request, true ) . '</pre>' );

		$client = new SoapClient( plugin_dir_path( dirname( __FILE__ ) ) . 'fedex-wsdl/' . ( $this->production ? 'production' : 'test' ) . '/RateService_v' . $this->rateservice_version. '.wsdl', array( 'trace' => 1 ) );
		$result = $client->getRates( $request );

		$this->debug( 'FedEx RESPONSE: <a href="#" class="debug_reveal">Reveal</a><pre class="debug_info" style="background:#EEE;border:1px solid #DDD;padding:5px;">' . print_r( $result, true ) . '</pre>' );

		wc_enqueue_js( "
			jQuery('a.debug_reveal').on('click', function(){
				jQuery(this).closest('div').find('.debug_info').slideDown();
				jQuery(this).remove();
				return false;
			});
			jQuery('pre.debug_info').hide();
		" );

		return $result;
	}

	private function process_result( $result = '' ) {
		if ( $result && ! empty ( $result->RateReplyDetails ) ) {

			$rate_reply_details = $result->RateReplyDetails;

			// Workaround for when an object is returned instead of array
			if ( is_object( $rate_reply_details ) && isset( $rate_reply_details->ServiceType ) )
				$rate_reply_details = array( $rate_reply_details );

			if ( ! is_array( $rate_reply_details ) )
				return false;

			foreach ( $rate_reply_details as $quote ) {

				if ( is_array( $quote->RatedShipmentDetails ) ) {

					if ( $this->request_type == "LIST" ) {
						// LIST quotes return both ACCOUNT rates (in RatedShipmentDetails[1])
						// and LIST rates (in RatedShipmentDetails[3])
						foreach ( $quote->RatedShipmentDetails as $i => $d ) {
							if ( strstr( $d->ShipmentRateDetail->RateType, 'PAYOR_LIST' ) ) {
								$details = $quote->RatedShipmentDetails[ $i ];
								break;
							}
						}
					} else {
						// ACCOUNT quotes may return either ACCOUNT rates only OR
						// ACCOUNT rates and LIST rates.
						foreach ( $quote->RatedShipmentDetails as $i => $d ) {
							if ( strstr( $d->ShipmentRateDetail->RateType, 'PAYOR_ACCOUNT' ) ) {
								$details = $quote->RatedShipmentDetails[ $i ];
								break;
							}
						}
					}

				} else {
					$details = $quote->RatedShipmentDetails;
				}

				if ( empty( $details ) )
					continue;

				$rate_code = strval( $quote->ServiceType );
				$rate_id   = $this->id . ':' . $rate_code;
				$rate_name = strval( $this->services[ $quote->ServiceType ] );
				$rate_cost = floatval( $details->ShipmentRateDetail->TotalNetCharge->Amount );
				$rate_cost = $this->convert_to_base_currency($details,$rate_cost);
				$this->prepare_rate( $rate_code, $rate_id, $rate_name, $rate_cost );
			}
		}
	}

	private function convert_to_base_currency($details,$rate_cost){
		$converted_rate = $rate_cost;
		if($this->convert_currency_to_base == 'yes'){
			if(property_exists($details->ShipmentRateDetail,'CurrencyExchangeRate')){
				$from_currency = $details->ShipmentRateDetail->CurrencyExchangeRate->FromCurrency;
				$convertion_rate = floatval( $details->ShipmentRateDetail->CurrencyExchangeRate->Rate);
				if($from_currency == $this->wf_get_fedex_currency()){
					$converted_rate = $converted_rate/$convertion_rate;
				}
			}
		}
		return $converted_rate;
	}

	private function prepare_rate( $rate_code, $rate_id, $rate_name, $rate_cost ) {

		// Name adjustment
		if ( ! empty( $this->custom_services[ $rate_code ]['name'] ) ) {
			$rate_name = $this->custom_services[ $rate_code ]['name'];
		}

		// Cost adjustment %
		if ( ! empty( $this->custom_services[ $rate_code ]['adjustment_percent'] ) ) {
			$rate_cost = $rate_cost + ( $rate_cost * ( floatval( $this->custom_services[ $rate_code ]['adjustment_percent'] ) / 100 ) );
		}
		// Cost adjustment
		if ( ! empty( $this->custom_services[ $rate_code ]['adjustment'] ) ) {
			$rate_cost = $rate_cost + floatval( $this->custom_services[ $rate_code ]['adjustment'] );
		}

		// Enabled check
		if ( isset( $this->custom_services[ $rate_code ] ) && empty( $this->custom_services[ $rate_code ]['enabled'] ) ) {
			return;
		}

		// Merging
		if ( isset( $this->found_rates[ $rate_id ] ) ) {
			$rate_cost = $rate_cost + $this->found_rates[ $rate_id ]['cost'];
			$packages  = 1 + $this->found_rates[ $rate_id ]['packages'];
		} else {
			$packages  = 1;
		}

		// Sort
		if ( isset( $this->custom_services[ $rate_code ]['order'] ) ) {
			$sort = $this->custom_services[ $rate_code ]['order'];
		} else {
			$sort = 999;
		}

		$this->found_rates[ $rate_id ] = array(
			'id'       => $rate_id,
			'label'    => $rate_name,
			'cost'     => $rate_cost,
			'sort'     => $sort,
			'packages' => $packages
		);
	}

	public function add_found_rates() {
		if ( $this->found_rates ) {

			if ( $this->offer_rates == 'all' ) {

				uasort( $this->found_rates, array( $this, 'sort_rates' ) );

				foreach ( $this->found_rates as $key => $rate ) {
					$this->add_rate( $rate );
				}
			} else {
				$cheapest_rate = '';

				foreach ( $this->found_rates as $key => $rate ) {
					if ( ! $cheapest_rate || $cheapest_rate['cost'] > $rate['cost'] ) {
						$cheapest_rate = $rate;
					}
				}

				$cheapest_rate['label'] = $this->title;

				$this->add_rate( $cheapest_rate );
			}
		}
	}

	public function sort_rates( $a, $b ) {
		if ( $a['sort'] == $b['sort'] ) return 0;
		return ( $a['sort'] < $b['sort'] ) ? -1 : 1;
	}
}
