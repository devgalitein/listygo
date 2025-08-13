<?php
/**
 * Main FrontendHook  Class.
 *
 * The main class that initiates and runs the plugin.
 *
 * @package  review-schema-pro
 *
 * @since    1.0.0
 */

namespace Rtrsp\Controllers\Frontend;

use Rtrs\Helpers\Functions;
use Rtrsp\Traits\SingletonTrait;
/**
 * FrontendHook class
 */
class FrontendHook {
	/**
	 * Singletone.
	 */
	use SingletonTrait;

	/**
	 * Initialize hooks
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'rtseo_snippet_others_schema_output', [ &$this, 'schema_output' ], 10, 4 );
	}
	/**
	 * Schema output function
	 *
	 * @param [type] $schemaCat schemaCat.
	 * @param [type] $metaData metaData.
	 * @param [type] $without_script without_script.
	 * @param [type] $schema_obj schema_obj.
	 * @return void
	 */
	public function schema_output( $schemaCat, $metaData, $without_script, $schema_obj ) {
		$helper = new Functions();
		$html   = '';
		switch ( $schemaCat ) {
			case 'tv_series':
				$output             = [];
				$output['@context'] = 'https://schema.org';
				$output['@type']    = 'TVSeries';
				if ( ! empty( $metaData['name'] ) ) {
					$output['name'] = esc_html( $metaData['name'] );
				}
				$author_type = esc_html( $metaData['author-type'] ) ?? 'Person';
				if ( ! empty( $metaData['author'] ) ) {
					$output['author'] = [
						'@type' => $author_type,
						'name'  => esc_html( $metaData['author'] ),
					];
				}

				if ( ! empty( $metaData['actor'] ) ) {
					foreach ( $metaData['actor'] as $value ) {
						if ( ! empty( $value['actor-name'] ) ) {
							$output['actor'][] = [
								'@type' => 'Person',
								'name'  => esc_html( $value['actor-name'] ),
							];
						}
					}
				}
				if ( ! empty( $metaData['description'] ) ) {
					$output['description'] = esc_html( $metaData['description'] );
				}
				if ( ! empty( $metaData['season'] ) ) {
					foreach ( $metaData['season'] as $value ) {
						$tvsession = [
							'@type'            => 'TVSeason',
							'datePublished'    => esc_html( $value['date-published'] ),
							'name'             => esc_html( $value['season-name'] ),
							'numberOfEpisodes' => esc_html( $value['number-of-episodes'] ),
						];
						$episode   = [];
						if ( ! empty( $value['episode-name'] ) ) {
							$episode['name'] = esc_html( $value['episode-name'] );
						}
						if ( ! empty( $value['episode-number'] ) ) {
							$episode['episodeNumber'] = esc_html( $value['episode-number'] );
						}
						if ( ! empty( $episode ) ) {
							$tvsession['episode'] = array_merge(
								[
									'@type' => 'TVEpisode',
								],
								$episode
							);
						}
						$output['containsSeason'][] = $tvsession;
					}
				}
				if ( $without_script ) {
					$html = apply_filters( 'rtseo_snippet_tv_series', $output, $metaData );
				} else {
					$html .= $schema_obj->getJsonEncode( apply_filters( 'rtseo_snippet_tv_series', $output, $metaData ) );
				}
				break;
			case 'collection_page':
				$collection_page = [
					'@context' => 'https://schema.org',
					'@type'    => 'CollectionPage',
				];
				if ( ! empty( $metaData['name'] ) ) {
					$collection_page['name'] = $helper->sanitizeOutPut( $metaData['name'] );
				}
				if ( ! empty( $metaData['webpage_url'] ) ) {
					$collection_page['url'] = $helper->sanitizeOutPut( $metaData['webpage_url'], 'url' );
				}
				if ( ! empty( $metaData['description'] ) ) {
					$collection_page['description'] = $helper->sanitizeOutPut( $metaData['description'], 'textarea' );
				}
				if ( ! empty( $metaData['image'] ) ) {
					$img                      = $helper->imageInfo( absint( $metaData['image'] ) );
					$collection_page['image'] = [
						'@type'  => 'ImageObject',
						'url'    => $helper->sanitizeOutPut( $img['url'], 'url' ),
						'height' => $img['height'],
						'width'  => $img['width'],
					];
				}

				if ( ! empty( $metaData['itempage'] ) ) {
					foreach ( $metaData['itempage']  as $key => $has ) {
						$hasPart = [
							'@type'    => 'ItemPage',
							'position' => absint( $key ) + 1,
						];
						if ( ! empty( $has['itempage-name'] ) ) {
							$hasPart['name'] = $helper->sanitizeOutPut( $has['itempage-name'] );
						}
						if ( ! empty( $has['mainEntityOfPage'] ) ) {
							$hasPart['mainEntityOfPage'] = $helper->sanitizeOutPut( $has['mainEntityOfPage'], 'url' );
						}
						if ( ! empty( $has['itempage-description'] ) ) {
							$hasPart['description'] = $helper->sanitizeOutPut( $has['itempage-description'], 'textarea' );
						}
						$collection_page['hasPart'][] = $hasPart;
					}
				}

				if ( $without_script ) {
					$html = apply_filters( 'rtseo_snippet_collection_page', $collection_page, $metaData );
				} else {
					$html .= $schema_obj->getJsonEncode( apply_filters( 'rtseo_snippet_collection_page', $collection_page, $metaData ) );
				}
				break;
			case 'vacation_rental':
				$vacationRental = [];

				if ( ! empty( $metaData['additionalType'] ) ) {
					$vacationRental['additionalType'] = $helper->sanitizeOutPut( $metaData['additionalType'] ) ?: 'HolidayVillageRental';
				}
				if ( ! empty( $metaData['name'] ) ) {
					$vacationRental['name'] = $helper->sanitizeOutPut( $metaData['name'] );
				}
				if ( ! empty( $metaData['description'] ) ) {
					$vacationRental['description'] = $helper->sanitizeOutPut( $metaData['description'] );
				}
				if ( ! empty( $metaData['priceRange'] ) ) {
					$vacationRental['priceRange'] = $helper->sanitizeOutPut( $metaData['priceRange'] );
				}
				if ( ! empty( $metaData['telephone'] ) ) {
					$vacationRental['telephone'] = $helper->sanitizeOutPut( $metaData['telephone'] );
				}
				if ( ! empty( $metaData['identifier'] ) ) {
					$vacationRental['identifier'] = $helper->sanitizeOutPut( $metaData['identifier'] );
				}
				if ( ! empty( $metaData['latitude'] ) ) {
					$vacationRental['latitude'] = $helper->sanitizeOutPut( $metaData['latitude'] );
				}
				if ( ! empty( $metaData['longitude'] ) ) {
					$vacationRental['longitude'] = $helper->sanitizeOutPut( $metaData['longitude'] );
				}

				// Contains Place.
				$containsPlace = [];
				if ( ! empty( $metaData['containsPlaceType'] ) ) {
					$containsPlace['additionalType'] = $helper->sanitizeOutPut( $metaData['containsPlaceType'] );
				}
				if ( ! empty( $metaData['occupancy'] ) ) {
					$containsPlace['occupancy'] = [
						'@type' => 'QuantitativeValue',
						'value' => $helper->sanitizeOutPut( $metaData['occupancy'], 'number' ),
					];
				}
				if ( ! empty( $metaData['numberOfBathroomsTotal'] ) ) {
					$containsPlace['numberOfBathroomsTotal'] = $helper->sanitizeOutPut( $metaData['numberOfBathroomsTotal'], 'number' );
				}
				if ( ! empty( $metaData['numberOfBedrooms'] ) ) {
					$containsPlace['numberOfBedrooms'] = $helper->sanitizeOutPut( $metaData['numberOfBedrooms'], 'number' );
				}
				if ( ! empty( $metaData['numberOfRooms'] ) ) {
					$containsPlace['numberOfRooms'] = $helper->sanitizeOutPut( $metaData['numberOfRooms'], 'number' );
				}

				if ( ! empty( $metaData['floorSizeValue'] ) ) {
					$containsPlace['floorSize'] = [
						'@type'    => 'QuantitativeValue',
						'value'    => $helper->sanitizeOutPut( $metaData['floorSizeValue'], 'number' ),
						'unitCode' => $helper->sanitizeOutPut( $metaData['unitCode'] ),
					];
				}

				if ( ! empty( $metaData['beds'] ) ) {
					$beds = [];
					foreach ( $metaData['beds'] as $bed ) {
						$beds[] = [
							'@type'        => 'BedDetails',
							'numberOfBeds' => $helper->sanitizeOutPut( $bed['numberOfBeds'], 'number' ),
							'typeOfBed'    => $helper->sanitizeOutPut( $bed['typeOfBed'] ),
						];
					}
					if ( ! empty( $beds ) ) {
						$containsPlace['bed'] = $beds;
					}
				}

				if ( ! empty( $metaData['amenityFeature'] ) ) {
					$features = [];
					foreach ( $metaData['amenityFeature'] as $feature ) {
						$features[] = [
							'@type' => 'LocationFeatureSpecification',
							'name'  => $helper->sanitizeOutPut( $feature['feature'] ),
							'value' => ! empty( $feature['value'] ),
						];
					}
					if ( ! empty( $features ) ) {
						$containsPlace['amenityFeature'] = $features;
					}
				}

				if ( ! empty( $containsPlace ) ) {
					$vacationRental['containsPlace'] = [
						'@type' => 'Accommodation',
					] + $containsPlace;
				}
				// End Contains Place.
				if ( ! empty( $metaData['images'] ) ) {
					$images = [];
					foreach ( $metaData['images'] as $img ) {
						if ( empty( $img['image'] ) ) {
							continue;
						}
						$img      = $helper->imageInfo( absint( $img['image'] ) );
						$images[] = $helper->sanitizeOutPut( $img['url'], 'url' );
					}
					if ( ! empty( $images ) ) {
						$vacationRental['image'] = $images;
					}
				}

				if ( ! empty( $metaData['reviews'] ) ) {
					$reviews = [];
					foreach ( $metaData['reviews'] as $review ) {
						$rvw = [];
						if ( ! empty( $review['datePublished'] ) ) {
							$rvw['datePublished'] = $helper->sanitizeOutPut( $review['datePublished'] );
						}
						if ( ! empty( $review['author'] ) ) {
							$rvw['author'] = [
								'@type' => 'Person',
								'name'  => $helper->sanitizeOutPut( $review['author'] ),
							];
						}
						$reviewRating = [];
						if ( ! empty( $review['ratingValue'] ) ) {
							$reviewRating['ratingValue'] = $helper->sanitizeOutPut( $review['ratingValue'] );
						}
						if ( ! empty( $review['bestRating'] ) ) {
							$reviewRating['bestRating'] = $helper->sanitizeOutPut( $review['bestRating'] );
						}
						if ( ! empty( $reviewRating ) ) {
							$rvw['reviewRating'] = [
								'@type' => 'Rating',
							] + $reviewRating;
						}
						$reviews[] = $rvw;
					}

					if ( ! empty( $reviews ) ) {
						$vacationRental['review'] = $reviews;
					}
				}

				$aggregateRating = [];
				if ( isset( $metaData['aggregate_bestRating'] ) ) {
					$aggregateRating['bestRating'] = $helper->sanitizeOutPut( $metaData['aggregate_bestRating'], 'number' );
				}
				if ( isset( $metaData['aggregate_worstRating'] ) ) {
					$aggregateRating['worstRating'] = $helper->sanitizeOutPut( $metaData['aggregate_worstRating'], 'number' );
				}
				if ( isset( $metaData['aggregate_ratingCount'] ) ) {
					$aggregateRating['reviewCount'] = $helper->sanitizeOutPut( $metaData['aggregate_ratingCount'], 'number' );
				}
				if ( isset( $metaData['aggregate_ratingValue'] ) ) {
					$aggregateRating['ratingValue'] = $helper->sanitizeOutPut( $metaData['aggregate_ratingValue'], 'number' );
				}

				if ( ! empty( $aggregateRating ) ) {
					$vacationRental['aggregateRating'] = [ '@type' => 'AggregateRating' ] + $aggregateRating;
				}

				// Address Start.
				$address = [];
				if ( ! empty( $metaData['streetAddress'] ) ) {
					$address['streetAddress'] = $helper->sanitizeOutPut( $metaData['streetAddress'] );
				}
				if ( ! empty( $metaData['addressLocality'] ) ) {
					$address['addressLocality'] = $helper->sanitizeOutPut( $metaData['addressLocality'] );
				}
				if ( ! empty( $metaData['region'] ) ) {
					$address['addressRegion'] = $helper->sanitizeOutPut( $metaData['region'] );
				}
				if ( ! empty( $metaData['postalCode'] ) ) {
					$address['postalCode'] = $helper->sanitizeOutPut( $metaData['postalCode'] );
				}
				if ( ! empty( $metaData['addressCountry'] ) ) {
					$address['addressCountry'] = $helper->sanitizeOutPut( $metaData['addressCountry'] );
				}
				if ( ! empty( $address ) ) {
					$vacationRental['address'] = [ '@type' => 'PostalAddress' ] + $address;
				}

				$vacationRental = array_merge(
					[
						'@context' => 'http://schema.org',
						'@type'    => 'VacationRental',
					],
					$vacationRental
				);

				if ( $without_script ) {
					$html = apply_filters( 'rtseo_snippet_vacation_rental', $vacationRental, $metaData );
				} else {
					$html .= $schema_obj->getJsonEncode( apply_filters( 'rtseo_snippet_vacation_rental', $vacationRental, $metaData ) );
				}
				break;

			case 'vehicle_listing':
				$vehicleListing = [];
				if ( ! empty( $metaData['type'] ) ) {
					$vehicleListing['@type'] = $helper->sanitizeOutPut( $metaData['type'] );
				}
				if ( ! empty( $metaData['name'] ) ) {
					$vehicleListing['name'] = $helper->sanitizeOutPut( $metaData['name'] );
				}
				if ( ! empty( $metaData['IdentificationNumber'] ) ) {
					$vehicleListing['vehicleIdentificationNumber'] = $helper->sanitizeOutPut( $metaData['IdentificationNumber'] );
				}
				if ( ! empty( $metaData['url'] ) ) {
					$vehicleListing['url'] = $helper->sanitizeOutPut( $metaData['url'], 'url' );
				}
				if ( ! empty( $metaData['description'] ) ) {
					$vehicleListing['description'] = $helper->sanitizeOutPut( $metaData['description'] );
				}

				if ( ! empty( $metaData['offers_price'] ) ) {
					$vehicleListing['offers'] = [
						'@type' => 'Offer',
						'price' => $helper->sanitizeOutPut( $metaData['offers_price'] ),
					];

					if ( ! empty( $metaData['priceCurrency'] ) ) {
						$vehicleListing['offers']['priceCurrency'] = $helper->sanitizeOutPut( $metaData['priceCurrency'] );
					}
					if ( ! empty( $metaData['priceValidUntil'] ) ) {
						$vehicleListing['offers']['priceValidUntil'] = $helper->sanitizeOutPut( $metaData['priceValidUntil'] );
					}

					if ( ! empty( $metaData['availability'] ) ) {
						$vehicleListing['offers']['availability'] = $helper->sanitizeOutPut( $metaData['availability'] );
					}

					$MerchantReturnPolicy = [];
					if ( ! empty( $metaData['applicableCountry'] ) ) {
						$MerchantReturnPolicy['applicableCountry'] = $helper->sanitizeOutPut( $metaData['applicableCountry'] );
					}
					if ( ! empty( $metaData['merchantReturnDays'] ) ) {
						$MerchantReturnPolicy['merchantReturnDays'] = absint( $metaData['merchantReturnDays'] );
					}

					/*
					if ( ! empty( $metaData['returnPolicyCategory'] ) ) {
						$MerchantReturnPolicy['returnPolicyCategory'] = $helper->sanitizeOutPut( $metaData['returnPolicyCategory'] );
					}
					if ( ! empty( $metaData['returnMethod'] ) ) {
						$MerchantReturnPolicy['returnMethod'] = $helper->sanitizeOutPut( $metaData['returnMethod'] );
					}
					if ( ! empty( $metaData['returnFees'] ) ) {
						$MerchantReturnPolicy['returnFees'] = $helper->sanitizeOutPut( $metaData['returnFees'] );
					}
					*/
					if ( ! empty( $MerchantReturnPolicy ) ) {
						$vehicleListing['offers']['hasMerchantReturnPolicy'] = array_merge(
							[
								'@type'                => 'MerchantReturnPolicy',
								'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
								'returnMethod'         => 'https://schema.org/ReturnByMail',
								'returnFees'           => 'https://schema.org/FreeReturn',
							],
							$MerchantReturnPolicy
						);
					}

					$shippingDetails = [];
					if ( ! empty( $metaData['shippingRate'] ) ) {
						$shippingDetails['shippingRate'] = [
							'@type'    => 'MonetaryAmount',
							'value'    => $helper->sanitizeOutPut( $metaData['shippingRate'] ),
							'currency' => $vehicleListing['offers']['priceCurrency'],
						];
					}
					if ( ! empty( $metaData['shippingDestination'] ) ) {
						$shippingDetails['shippingDestination'] = [
							'@type'          => 'DefinedRegion',
							'addressCountry' => $metaData['shippingDestination'],
						];
						if ( ! empty( $metaData['addressRegion'] ) ) {
							$shippingDetails['shippingDestination']['addressRegion'] = '[' . $metaData['addressRegion'] . ']';
						}
					}

					$shippingDeliveryTime = [];
					if ( ! empty( $metaData['handlingTimeMinimum'] ) ) {
						$shippingDeliveryTime['handlingTime'] = [
							'@type'    => 'QuantitativeValue',
							'minValue' => absint( $metaData['handlingTimeMinimum'] ),
							'maxValue' => absint( $metaData['handlingTimeMaximum'] ),
							'unitCode' => 'DAY',
						];
					}
					if ( ! empty( $metaData['transitTimeMinimum'] ) ) {
						$shippingDeliveryTime['transitTime'] = [
							'@type'    => 'QuantitativeValue',
							'minValue' => absint( $metaData['transitTimeMinimum'] ),
							'maxValue' => absint( $metaData['transitTimeMaximum'] ),
							'unitCode' => 'DAY',
						];
					}
					if ( ! empty( $shippingDeliveryTime ) ) {
						$shippingDetails['deliveryTime'] = array_merge(
							[
								'@type' => 'ShippingDeliveryTime',
							],
							$shippingDeliveryTime
						);
					}

					if ( ! empty( $shippingDetails ) ) {
						$vehicleListing['offers']['shippingDetails'] = array_merge(
							[
								'@type' => 'OfferShippingDetails',
							],
							$shippingDetails
						);
					}
				}

				if ( ! empty( $metaData['itemCondition'] ) ) {
					$vehicleListing['itemCondition'] = $helper->sanitizeOutPut( $metaData['itemCondition'] );
				}
				if ( ! empty( $metaData['brand'] ) ) {
					$vehicleListing['brand'] = [
						'@type' => 'Brand',
						'name'  => $helper->sanitizeOutPut( $metaData['brand'] ),
					];
				}
				if ( ! empty( $metaData['model'] ) ) {
					$vehicleListing['model'] = $helper->sanitizeOutPut( $metaData['model'] );
				}
				if ( ! empty( $metaData['vehicleConfiguration'] ) ) {
					$vehicleListing['vehicleConfiguration'] = $helper->sanitizeOutPut( $metaData['vehicleConfiguration'] );
				}
				if ( ! empty( $metaData['vehicleModelDate'] ) ) {
					$vehicleListing['vehicleModelDate'] = $helper->sanitizeOutPut( $metaData['vehicleModelDate'] );
				}
				if ( ! empty( $metaData['mileageFromOdometer'] ) ) {
					$vehicleListing['mileageFromOdometer'] = [
						'@type'    => 'QuantitativeValue',
						'value'    => $helper->sanitizeOutPut( $metaData['mileageFromOdometer'] ),
						'unitCode' => $helper->sanitizeOutPut( $metaData['unitCode'] ),
					];
				}
				if ( ! empty( $metaData['color'] ) ) {
					$vehicleListing['color'] = $helper->sanitizeOutPut( $metaData['color'] );
				}
				if ( ! empty( $metaData['color'] ) ) {
					$vehicleListing['color'] = $helper->sanitizeOutPut( $metaData['color'] );
				}
				if ( ! empty( $metaData['vehicleInteriorColor'] ) ) {
					$vehicleListing['vehicleInteriorColor'] = $helper->sanitizeOutPut( $metaData['vehicleInteriorColor'] );
				}
				if ( ! empty( $metaData['vehicleInteriorType'] ) ) {
					$vehicleListing['vehicleInteriorType'] = $helper->sanitizeOutPut( $metaData['vehicleInteriorType'] );
				}
				if ( ! empty( $metaData['bodyType'] ) ) {
					$vehicleListing['bodyType'] = $helper->sanitizeOutPut( $metaData['bodyType'] );
				}
				if ( ! empty( $metaData['driveWheelConfiguration'] ) ) {
					$vehicleListing['driveWheelConfiguration'] = $helper->sanitizeOutPut( $metaData['driveWheelConfiguration'] );
				}
				if ( ! empty( $metaData['fuelType'] ) ) {
					$vehicleListing['vehicleEngine'] = [
						'@type'    => 'EngineSpecification',
						'fuelType' => $helper->sanitizeOutPut( $metaData['fuelType'] ),
					];
				}

				if ( ! empty( $metaData['vehicleTransmission'] ) ) {
					$vehicleListing['vehicleTransmission'] = $helper->sanitizeOutPut( $metaData['vehicleTransmission'] );
				}

				if ( ! empty( $metaData['numberOfDoors'] ) ) {
					$vehicleListing['numberOfDoors'] = $helper->sanitizeOutPut( $metaData['numberOfDoors'] );
				}
				if ( ! empty( $metaData['vehicleSeatingCapacity'] ) ) {
					$vehicleListing['vehicleSeatingCapacity'] = $helper->sanitizeOutPut( $metaData['vehicleSeatingCapacity'] );
				}

				if ( ! empty( $metaData['images'] ) ) {
					$images = [];
					foreach ( $metaData['images'] as $img ) {
						if ( empty( $img['image'] ) ) {
							continue;
						}
						$img      = $helper->imageInfo( absint( $img['image'] ) );
						$images[] = $helper->sanitizeOutPut( $img['url'], 'url' );
					}
					if ( ! empty( $images ) ) {
						$vehicleListing['image'] = $images;
					}
				}

				if ( ! empty( $metaData['ratingValue'] ) ) {
					$vehicleListing['aggregateRating'] = [
						'@type'       => 'AggregateRating',
						'ratingValue' => $helper->sanitizeOutPut( $metaData['ratingValue'] ),
						'reviewCount' => $helper->sanitizeOutPut( $metaData['reviewCount'] ),
					];
				}

				$review = [];
				if ( ! empty( $metaData['reviewRatingValue'] ) ) {
					$review['ratingValue'] = $helper->sanitizeOutPut( $metaData['reviewRatingValue'] );
				}
				if ( ! empty( $metaData['reviewBestRating'] ) ) {
					$review['bestRating'] = $helper->sanitizeOutPut( $metaData['reviewBestRating'] );
				}
				if ( ! empty( $metaData['reviewWorstRating'] ) ) {
					$review['worstRating'] = $helper->sanitizeOutPut( $metaData['reviewWorstRating'] );
				}
				if ( ! empty( $review ) ) {
					$vehicleListing['review'] = [
						'@type'        => 'Review',
						'reviewRating' => array_merge(
							[
								'@type' => 'Rating',
							],
							$review
						),
					];
					if ( ! empty( $metaData['reviewAuthor'] ) ) {
						$vehicleListing['review']['author'] = [
							'@type' => 'Person',
							'name'  => $helper->sanitizeOutPut( $metaData['reviewAuthor'] ),
						];
					}
				}

				$vehicleListing = array_merge(
					[
						'@context' => 'http://schema.org',
					],
					$vehicleListing
				);

				if ( $without_script ) {
					$html = apply_filters( 'rtseo_snippet_vehicle_listing', $vehicleListing, $metaData );
				} else {
					$html .= $schema_obj->getJsonEncode( apply_filters( 'rtseo_snippet_vehicle_listing', $vehicleListing, $metaData ) );
				}

				break;

			default:
		}
		echo wp_kses(
			$html,
			[
				'script' => [
					'type' => [],
				],
			]
		);
	}
}
